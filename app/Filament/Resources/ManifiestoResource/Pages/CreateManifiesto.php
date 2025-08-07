<?php

namespace App\Filament\Resources\ManifiestoResource\Pages;

use App\Enums\ManifiestoEstado;
use App\Filament\Resources\ManifiestoResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use App\Models\Encomienda;

class CreateManifiesto extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;
    protected static string $resource = ManifiestoResource::class;
    protected ?int $manifiestoId = null;

    protected function getSteps(): array
    {
        return [
            Step::make('Manifiesto')
                ->schema([
                    Forms\Components\TextInput::make('codigo_manifiesto')
                       ->default('Generado Automaticamente')
                            ->disabled()
                            ->dehydrated(),
                    Forms\Components\DatePicker::make('fecha_envio')
                        ->label('Fecha de Envío')
                        ->placeholder('Seleccione la fecha')
                        ->prefixIcon('heroicon-o-calendar-days')
                        ->default(now()->format('Y-m-d'))
                        ->required()
                        ->columnSpan(1),
                    Forms\Components\Select::make('hora_envio')
                        ->label('Hora de Envío')
                        ->placeholder('Seleccione la hora')
                        ->prefixIcon('heroicon-o-clock')
                        ->options([
                            '00:00' => '00:00',
                            '01:00' => '01:00',
                            '02:00' => '02:00',
                            '03:00' => '03:00',
                            '04:00' => '04:00',
                            '05:00' => '05:00',
                            '06:00' => '06:00',
                            '07:00' => '07:00',
                            '08:00' => '08:00',
                            '09:00' => '09:00',
                            '10:00' => '10:00',
                            '11:00' => '11:00',
                            '12:00' => '12:00',
                            '13:00' => '13:00',
                            '14:00' => '14:00',
                            '15:00' => '15:00',
                            '16:00' => '16:00',
                            '17:00' => '17:00',
                            '18:00' => '18:00',
                            '19:00' => '19:00',
                            '20:00' => '20:00',
                            '21:00' => '21:00',
                            '22:00' => '22:00',
                            '23:00' => '23:00',
                        ])
                        ->default(now()->format('H:00'))
                        ->required()
                        ->columnSpan(1),

                    Forms\Components\Select::make('conductor_id')
                        ->label('Conductor')
                        ->options(
                            \App\Models\Conductor::whereHas('vehiculos')
                                ->with('vehiculos')
                                ->get()
                                ->mapWithKeys(function ($conductor) {
                                    $vehiculosCount = $conductor->vehiculos->count();
                                    $primerVehiculo = $conductor->vehiculos->first();

                                    return [
                                        $conductor->id => $conductor->nombre_conductor .
                                            ($primerVehiculo ? " (Vehículo: {$primerVehiculo->placa})" : '') .
                                            ($vehiculosCount > 1 ? " +{$vehiculosCount} vehículos" : '')
                                    ];
                                })
                                ->toArray()
                        )
                        ->searchable()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($state) {
                                $conductor = \App\Models\Conductor::with('vehiculos')->find($state);

                                if ($conductor && $conductor->vehiculos->isNotEmpty()) {
                                    $vehiculoPrincipal = $conductor->vehiculos()
                                        ->wherePivot('rol_conductor', \App\Enums\RolConductorEstado::Principal->value)
                                        ->first();

                                    $vehiculoSeleccionado = $vehiculoPrincipal ?? $conductor->vehiculos->first();

                                    $set('vehiculo_id', $vehiculoSeleccionado->id);
                                } else {
                                    $set('vehiculo_id', null);
                                }
                            } else {
                                $set('vehiculo_id', null);
                            }
                        }),

                    Forms\Components\Select::make('vehiculo_id')
                        ->label('Vehículo')

                        ->options(function (callable $get) {
                            $conductorId = $get('conductor_id');

                            if (!$conductorId) {
                                return [];
                            }
                            $conductor = \App\Models\Conductor::with('vehiculos')->find($conductorId);

                            if ($conductor && $conductor->vehiculos->isNotEmpty()) {
                                return $conductor->vehiculos->mapWithKeys(function ($vehiculo) use ($conductor) {
                                    $pivotInfo = $vehiculo->pivot;
                                    $rolLabel = \App\Enums\RolConductorEstado::tryFrom($pivotInfo->rol_conductor)?->getLabel() ?? 'Sin rol';

                                    return [
                                        $vehiculo->id =>
                                        "{$vehiculo->placa} - {$vehiculo->marca} {$vehiculo->modelo} ({$rolLabel})"
                                    ];
                                })->toArray();
                            }

                            return [];
                        })
                        ->searchable()
                        ->reactive()
                        ->disabled(false)
                        ->dehydrated(),

                    Forms\Components\Select::make('estado')
                        ->required()
                        ->options(ManifiestoEstado::class)
                        ->default(1),

                    Forms\Components\Section::make('Destinos')
                        ->schema(
                            [
                                Forms\Components\Select::make('sucursal_origen_id')
                                    ->relationship('sucursalOrigen', 'ciudad')
                                    ->required(),
                                Forms\Components\Select::make('sucursal_destino_id')
                                    ->relationship('sucursalDestino', 'ciudad')
                                    ->required(),
                            ]
                        ),


                ]),

            Step::make('📦 Encomiendas')
                ->schema([
                    Forms\Components\Repeater::make('encomiendas_temporales')
                        ->label('📦 Encomiendas')
                        ->schema([
                            Forms\Components\Select::make('encomienda_id')
                                ->label('Encomienda')
                                ->options(
                                    \App\Models\Encomienda::whereNull('manifiesto_id')
                                        ->whereNotNull('numero_guia')
                                        ->pluck('numero_guia', 'id')
                                )
                                ->searchable()
                                ->required()
                                ->reactive()
                                ->live()
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                  
                                    if ($state) {
                                        $encomienda = \App\Models\Encomienda::find($state);
                                        if ($encomienda) {
                                            $set('nombre_remitente', $encomienda->nombre_remitente);
                                            $set('contenido', $encomienda->descripcion_contenido);
                                            $set('precio', $encomienda->precio);
                                        }
                                    } else {
                                    
                                        $set('nombre_remitente', '');
                                        $set('contenido', '');
                                        $set('precio', 0);
                                    }

                                  
                                    $encomiendas = $get('../../encomiendas_temporales') ?? [];
                                    $total = 0;

                                    foreach ($encomiendas as $encomiendaItem) {
                                        if (isset($encomiendaItem['precio']) && is_numeric($encomiendaItem['precio'])) {
                                            $total += (float) $encomiendaItem['precio'];
                                        }
                                    }

                                    $set('../../precio_total', $total); 
                                }),

                            Forms\Components\TextInput::make('nombre_remitente')
                                ->label('🧑‍💼 Remitente')
                                ->disabled()
                                ->columnSpan(2)
                                ->live(), 

                            Forms\Components\Textarea::make('contenido')
                                ->label('📄 Contenido')
                                ->rows(2)
                                ->disabled()
                                ->columnSpan(2)
                                ->live(),

                            Forms\Components\TextInput::make('precio')
                                ->label('💰 Precio (Bs.)')
                                ->prefix('Bs.')
                                ->disabled()
                                ->default(0)
                                ->columnSpan(1)
                                ->live(), 
                        ])
                        ->afterStateUpdated(function ($state, callable $set) {
                            
                            $total = 0;

                            if (is_array($state)) {
                                foreach ($state as $item) {
                                    if (isset($item['precio']) && is_numeric($item['precio'])) {
                                        $total += (float) $item['precio'];
                                    }
                                }
                            }

                            $set('precio_total', $total); 
                        })
                        ->minItems(1)
                        ->maxItems(10)
                        ->columns(2)
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('precio_total')
                        ->label('💵 Precio Total de Encomiendas')
                        ->prefix('Bs.')
                        ->default(0)
                        ->disabled()
                        ->live() 
                        ->required(),

                ]),
        ];
    }



    protected function afterCreate(): void
    {
        $manifiesto = $this->record;

        if ($this->data['encomiendas_temporales'] ?? false) {
            $ids = collect($this->data['encomiendas_temporales'])->pluck('encomienda_id')->toArray();

            $manifiesto->encomiendas()->sync($ids);

            $precioTotal = \App\Models\Encomienda::whereIn('id', $ids)->sum('precio');
            $manifiesto->precio_total = $precioTotal;
            $manifiesto->save();
        }
    }
}
