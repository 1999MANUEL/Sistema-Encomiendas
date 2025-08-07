<?php

namespace App\Filament\Resources;

use App\Enums\EncomiendaEstado;
use App\Enums\ManifiestoEstado;
use App\Filament\Resources\ManifiestoEncomiendaResource\RelationManagers\EncomiendasRelationManager;
use App\Filament\Resources\ManifiestoResource\Pages;
use App\Filament\Resources\ManifiestoResource\RelationManagers;
use App\Filament\Resources\ManifiestoResource\RelationManagers\EncomiendasRelationManager as RelationManagersEncomiendasRelationManager;
use App\Models\Conductor;
use App\Models\Encomienda;
use App\Models\Manifiesto;
use App\Models\Vehiculo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Components\Tab;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;

class ManifiestoResource extends Resource
{
    protected static ?string $model = Manifiesto::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationGroup = 'Envios y Encomiendas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detalles Generales del Manifiesto')
                    ->description('Información principal para la creación y gestión del manifiesto.')
                    ->columns(2)
                    ->collapsible()
                    ->schema([
                        Forms\Components\TextInput::make('codigo_manifiesto')
                            ->label('Número de Manifiesto')
                            //   ->unique(ignoreRecord: true)                 
                            ->default('Generado Automaticamente')
                            ->disabled()
                            ->dehydrated(),
                        Forms\Components\DatePicker::make('fecha_envio')
                            ->label('🗓️ Fecha de Envío')
                            ->default(now())
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                        Forms\Components\Select::make('hora_envio')
                            ->label('⏰ Hora de Envío')
                            ->options(collect(range(0, 23))->mapWithKeys(fn($h) => [sprintf('%02d:00', $h) => sprintf('%02d:00', $h)]))
                            ->default(now()->format('H:00'))
                            ->required()
                            ->searchable(),
                    ]),
                Forms\Components\Section::make('Datos del Transporte')
                    ->description('Asigna el conductor y el vehículo principal para este envío.')
                    ->columns(2)
                    ->collapsible()
                    ->schema([
                        Forms\Components\Select::make('conductor_id')
                            ->label('🧑‍✈️ Conductor Asignado')
                            ->relationship('conductor', 'nombre_conductor')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->native(false)
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $conductor = \App\Models\Conductor::with('vehiculos')->find($state);
                                    $vehiculo = $conductor?->vehiculos->first();
                                    $set('vehiculo_id', $vehiculo?->id);
                                } else {
                                    $set('vehiculo_id', null);
                                }
                            }),

                        Forms\Components\Select::make('vehiculo_id')
                            ->label('🚚 Vehículo Asignado')
                            ->relationship(
                                'vehiculo',
                                'placa',
                                fn($query, $get) =>
                                $query->when(
                                    $get('conductor_id'),
                                    fn($query, $conductorId) =>
                                    $query->whereHas('conductores', fn($q) => $q->where('conductor_id', $conductorId))
                                )
                            )
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->required(),
                    ]),

                Forms\Components\Section::make('Ruta del Manifiesto')
                    ->description('Define las sucursales de origen y destino del envío.')
                    ->columns(2)
                    ->collapsible()
                    ->schema([
                        Forms\Components\Select::make('sucursal_origen_id')
                            ->label('📍 Sucursal de Origen')
                            ->relationship('sucursalOrigen', 'ciudad')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->required(),

                        Forms\Components\Select::make('sucursal_destino_id')
                            ->label('🎯 Sucursal de Destino')
                            ->relationship('sucursalDestino', 'ciudad')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->required(),
                    ]),


                Forms\Components\Section::make('Estado y Resumen del Manifiesto')
                    ->description('Define el estado actual del manifiesto y su valor total.')
                    ->columns(2)
                    ->collapsible()
                    ->schema([
                        Forms\Components\Select::make('estado')
                            ->label('📊 Estado del Manifiesto')
                            ->required()
                            ->options(ManifiestoEstado::class)
                            ->default(ManifiestoEstado::Creado->value)
                            ->native(false),

                        Forms\Components\TextInput::make('precio_total')
                            ->label('💵 Precio Total de Encomiendas')
                            ->prefix('Bs.')
                            ->default(fn($record) => $record ? $record->precio_total : 0)
                            ->disabled()
                            ->live()
                            ->required()
                            ->extraAttributes([
                                'class' => 'font-bold text-lg',
                                'x-on:refresh-form-data.window' => '$wire.$refresh()',
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codigo_manifiesto')
                    ->label('Cod. Manifiesto')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('conductor.nombre_conductor')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vehiculo.placa')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_envio')
                    ->label('Fecha de Envío')
                    ->dateTime('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('hora_envio')
                    ->label(' Hora de Envío')
                    ->time('H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sucursalOrigen.ciudad')
                    ->label('Of. Origen')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('sucursalDestino.ciudad')
                    ->label('Of. Destino')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('estado')
                    ->searchable()
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('estado')
                    ->options(ManifiestoEstado::class)
                    ->label('Estado de Manifiesto'),

            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modal()
                    ->modalHeading('Vista de Manifiesto')
                    ->modalWidth('4xl')
                    ->infolist([
                        Section::make('Información General del Envío')
                            ->columns(2)
                            ->schema([
                                TextEntry::make('codigo_manifiesto')
                                    ->label('Número de Manifiesto'),
                                TextEntry::make('fecha_envio')
                                    ->label('Fecha de Envío')
                                    ->dateTime('d/m/Y'),
                            ]),

                        Section::make('')
                            ->columns(2)
                            ->compact()
                            ->schema([
                                TextEntry::make('hora_envio')
                                    ->label('Hora de Envío')
                                    ->time('H:i'),

                                TextEntry::make('conductor.nombre_conductor')
                                    ->label('Conductor'),

                                TextEntry::make('vehiculo.placa')
                                    ->label('Vehículo'),

                                TextEntry::make('estado')
                                    ->label('Estado')
                                    ->badge(),

                            ]),

                     
                        Section::make('Destinos')
                            ->columns(2)
                            ->schema([
                                TextEntry::make('sucursalOrigen.ciudad')
                                    ->label('Sucursal origen'),
                                TextEntry::make('sucursalDestino.ciudad')
                                    ->label('Sucursal destino'),
                            ]),

                       
                        Section::make('Precio Total de Encomiendas')
                            ->schema([
                                TextEntry::make('precio_total')
                                    ->label('Precio Total')
                                    ->numeric(decimalPlaces: 2, thousandsSeparator: ',', decimalSeparator: '.')
                                    ->prefix('Bs. '),
                            ]),
                    ]),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public static function recalcularTotal(callable $get, callable $set): void
    {
        $items = $get('encomiendas_temporales') ?? [];
        $precioTotal = 0;

        foreach ($items as $item) {
            if (isset($item['precio']) && is_numeric($item['precio'])) {
                $precioTotal += (float) $item['precio'];
            }
        }

        $set('precio_total', $precioTotal);
    }
    public static function getRelations(): array
    {
        return [
            RelationManagersEncomiendasRelationManager::class,

        ];
    }

    public function getTabs(): array
    {
        return [
            'todos' => Tab::make('Todos')
                ->badge(Manifiesto::count()),

            'registradas' => Tab::make('Registradas')
                ->badge(Manifiesto::where('estado', ManifiestoEstado::Creado)->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('estado', ManifiestoEstado::Creado)),



            'entregadas' => Tab::make('Entregadas')
                ->badge(Manifiesto::where('estado', ManifiestoEstado::Entregado)->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('estado', ManifiestoEstado::Entregado)),

            'enespera' => Tab::make('En Espera')
                ->badge(Manifiesto::where('estado', ManifiestoEstado::EnEspera)->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('estado', ManifiestoEstado::EnEspera)),


        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListManifiestos::route('/'),
            'create' => Pages\CreateManifiesto::route('/create'),
            'edit' => Pages\EditManifiesto::route('/{record}/edit'),
        ];
    }
}
