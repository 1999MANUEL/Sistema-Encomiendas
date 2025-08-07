<?php

namespace App\Filament\Resources;

use App\Enums\EncomiendaEstado;
use App\Filament\Resources\EncomiendaResource\Pages;
use App\Filament\Resources\EncomiendaResource\RelationManagers;
use App\Models\Encomienda;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use App\Filament\Resources\EncomiendaResource\Widgets\StatsOverviewWidget;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Table;
use Filament\Resources\Components\Tab;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\ViewAction;

use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;



class EncomiendaResource extends Resource
{
    protected static ?string $model = Encomienda::class;

    protected static ?string $navigationIcon = 'heroicon-s-squares-plus';
    protected static ?string $navigationGroup = 'Envios y Encomiendas';

    public static function getWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
        ];
    }

    public static function form(Form $form): Form
    {

      
        $recalcularTotales = function (Forms\Get $get, Forms\Set $set) {
            $paquetes = $get('paquetes') ?? [];

            $precioTotal = collect($paquetes)->sum(fn($item) => (float) ($item['precio'] ?? 0));
            $set('total_precio_display', number_format($precioTotal, 2, '.', ''));
            $set('precio', $precioTotal);

            $pesoTotal = collect($paquetes)->sum(fn($item) => (float) ($item['peso'] ?? 0));
            $set('total_peso_display', number_format($pesoTotal, 2, '.', ''));
            $set('peso', $pesoTotal);

            $alturaMax = collect($paquetes)->max(fn($item) => (float) ($item['altura'] ?? 0)) ?? 0;
            $set('total_altura_display', number_format($alturaMax, 2, '.', ''));
            $set('altura', $alturaMax);

            $anchoMax = collect($paquetes)->max(fn($item) => (float) ($item['ancho'] ?? 0)) ?? 0;
            $set('total_ancho_display', number_format($anchoMax, 2, '.', ''));
            $set('ancho', $anchoMax);

            $largoMax = collect($paquetes)->max(fn($item) => (float) ($item['largo'] ?? 0)) ?? 0;
            $set('total_largo_display', number_format($largoMax, 2, '.', ''));
            $set('largo', $largoMax);

            $set('cantidad_paquetes', count($paquetes));
        };


        return $form
            ->schema([
                Forms\Components\Section::make('Información General ')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('numero_guia')
                            //->unique(ignoreRecord: true)
                            //->required()
                            ->label('Número de Guía')
                            ->default('Generado Automaticamente')
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\Select::make('estado')
                            ->options(EncomiendaEstado::class)
                            ->label('Estado de la Encomienda')
                            ->required()

                            ->default(1),
                    ]),
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\Section::make('Datos del Remitente')
                        ->columnSpan(1)
                        ->columns(2)
                        ->schema([
                            Forms\Components\TextInput::make('nombre_remitente')
                                ->required()
                                ->maxLength(255)
                                ->label('Nombre Completo'),

                            Forms\Components\TextInput::make('contacto_remitente')
                                ->maxLength(255)
                                ->nullable()
                                ->label('Contacto/Celular'),
                            Forms\Components\Textarea::make('direccion_remitente')
                                ->nullable()
                                ->columnSpan('2')
                                ->label('Dirección'),
                        ]),

                    Forms\Components\Section::make('Datos del Destinatario')
                        ->columnSpan(1)
                        ->columns(2)
                        ->schema([
                            Forms\Components\TextInput::make('nombre_destinatario')
                                ->required()
                                ->maxLength(255)
                                ->label('Nombre Completo'),

                            Forms\Components\TextInput::make('contacto_destinatario')
                                ->maxLength(255)
                                ->nullable()
                                ->label('Contacto/Celular'),
                            Forms\Components\Textarea::make('direccion_destinatario')
                                ->maxLength(255)
                                ->nullable()
                                ->columnSpan('2')
                                ->label('Dirección'),
                        ]),
                ]),

                Forms\Components\Section::make('Ruta de la Encomienda')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('sucursal_origen_id')
                            ->relationship('sucursalOrigen', 'ciudad')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('Sucursal de Origen'),
                        Forms\Components\Select::make('sucursal_destino_id')
                            ->relationship('sucursalDestino', 'ciudad')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('Sucursal de Destino'),
                    ]),
                Forms\Components\Section::make('Valor Declarado para la Encomienda')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Checkbox::make('tiene_valor_declarado')
                            ->label('¿Declarar un valor específico para la encomienda?')
                            ->live()
                            ->default(false),

                        Forms\Components\TextInput::make('valor_declarado')
                            ->numeric()
                            ->prefix('Bs.')
                            ->nullable()
                            ->requiredWith('tiene_valor_declarado')
                            ->visible(fn(Forms\Get $get): bool => $get('tiene_valor_declarado'))
                            ->label('Valor Declarado'),

                    ]),

                Forms\Components\Section::make('Detalles de los Paquetes')
                    ->description('Añada el peso, dimensiones y precio por cada paquete.')
                    ->schema([

                        Forms\Components\Textarea::make('descripcion_contenido')
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->label('Descripción General de la Carga')
                            ->required()
                            ->helperText('⚠ Este campo es obligatorio para generar el manifiesto. Detalla claramente lo que contiene la carga.')
                            ->extraAttributes([
                                'class' => 'border-red-500'
                            ]),


                        Forms\Components\Repeater::make('paquetes')
                            ->relationship()
                            ->label('Listado de Paquetes')
                            ->schema([


                                Forms\Components\TextInput::make('precio')->numeric()->required()->suffix('Bs'),
                                Forms\Components\TextInput::make('peso')->numeric()->required()->suffix('kg'),
                                Forms\Components\TextInput::make('altura')->numeric()->required()->suffix('cm'),
                                Forms\Components\TextInput::make('ancho')->numeric()->required()->suffix('cm'),
                                Forms\Components\TextInput::make('largo')->numeric()->required()->suffix('cm'),
                                Forms\Components\TextInput::make('descripcion')
                                    ->label('Descripción')
                                    ->helperText('Opcional: puedes detallar aquí si lo deseas, o hacerlo por paquete.')
                                    ->columnSpan(5),


                            ])
                            ->columns(5)
                            ->defaultItems(1)
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->minItems(1)
                            ->live()
                            ->afterStateUpdated($recalcularTotales)
                            ->afterStateHydrated(function (callable $get, callable $set) {
                                $paquetes = $get('paquetes') ?? [];

                                $precioTotal = 0;
                                $pesoTotal = 0;
                                $alturaTotal = 0;
                                $anchoTotal = 0;
                                $largoTotal = 0;

                                foreach ($paquetes as $p) {
                                    $precioTotal += floatval($p['precio'] ?? 0);
                                    $pesoTotal += floatval($p['peso'] ?? 0);
                                    $alturaTotal += floatval($p['altura'] ?? 0);
                                    $anchoTotal += floatval($p['ancho'] ?? 0);
                                    $largoTotal += floatval($p['largo'] ?? 0);
                                }

                                $set('precio', $precioTotal);
                                $set('peso', $pesoTotal);
                                $set('altura', $alturaTotal);
                                $set('ancho', $anchoTotal);
                                $set('largo', $largoTotal);
                                $set('cantidad_paquetes', count($paquetes));
                            })
                            ->dehydrateStateUsing(fn($state) => $state),

                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('total_precio_display')
                                    ->label('💰 Total Precio')
                                    ->prefix('Bs.')
                                    ->readOnly()
                                    ->live()
                                    ->default('0.00')
                                    ->afterStateHydrated(function (callable $set, $state, $record) {
                                        if ($record) {
                                            $set('total_precio_display', number_format($record->precio ?? 0, 2, '.', ''));
                                        }
                                    })
                                    ->extraInputAttributes(['style' => 'font-weight: bold; font-size: 1.1em;']),

                                Forms\Components\TextInput::make('total_peso_display')
                                    ->label('⚖️ Peso Total')
                                    ->suffix(' kg')
                                    ->readOnly()
                                    ->live()
                                    ->default('0.00')
                                    ->afterStateHydrated(function (callable $set, $state, $record) {
                                        if ($record) {
                                            $set('total_peso_display', number_format($record->peso ?? 0, 2, '.', ''));
                                        }
                                    })
                                    ->extraInputAttributes(['style' => 'font-weight: bold;']),

                            ])
                            ->columns(2),

                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('total_altura_display')
                                    ->label('📏 Altura Máxima')
                                    ->suffix(' cm')
                                    ->readOnly()
                                    ->live()
                                    ->default('0.00')
                                    ->afterStateHydrated(
                                        fn(callable $set, $state, $record) =>
                                        $record ? $set('total_altura_display', number_format($record->altura ?? 0, 2, '.', '')) : null
                                    ),

                                Forms\Components\TextInput::make('total_ancho_display')
                                    ->label('📐 Ancho Máximo')
                                    ->suffix(' cm')
                                    ->readOnly()
                                    ->live()
                                    ->default('0.00')
                                    ->afterStateHydrated(
                                        fn(callable $set, $state, $record) =>
                                        $record ? $set('total_ancho_display', number_format($record->ancho ?? 0, 2, '.', '')) : null
                                    ),

                                Forms\Components\TextInput::make('total_largo_display')
                                    ->label('📏 Largo Máximo')
                                    ->suffix(' cm')
                                    ->readOnly()
                                    ->live()
                                    ->default('0.00')
                                    ->afterStateHydrated(
                                        fn(callable $set, $state, $record) =>
                                        $record ? $set('total_largo_display', number_format($record->largo ?? 0, 2, '.', '')) : null
                                    ),

                            ])
                            ->columns(3),

                        Forms\Components\Hidden::make('cantidad_paquetes')->default(1),
                        Forms\Components\Hidden::make('precio')->default(0),
                        Forms\Components\Hidden::make('peso')->default(0),
                        Forms\Components\Hidden::make('altura')->default(0),
                        Forms\Components\Hidden::make('ancho')->default(0),
                        Forms\Components\Hidden::make('largo')->default(0),

                        Forms\Components\Hidden::make('paquetes_data')
                            ->dehydrateStateUsing(
                                fn(Forms\Get $get) =>
                                json_encode($get('temp_paquetes') ?? [])
                            ),
                        Forms\Components\Radio::make('estado_pago')
                            ->label('Estado de Pago')
                            ->options([
                                'pagado' => 'Pagado',
                                'con_saldo' => 'Con saldo pendiente',
                            ])
                            ->default('con_saldo')
                            ->reactive()
                            ->live(),

                        Forms\Components\TextInput::make('monto_pagado')
                            ->label('Monto Pagado')
                            ->prefix('Bs.')
                            ->numeric()
                            ->default(0)
                            ->reactive()
                            ->live() // 🔥 Asegura actualización instantánea
                            ->visible(fn(Forms\Get $get) => $get('estado_pago') === 'con_saldo')
                            ->afterStateUpdated(function ($state, callable $set, Forms\Get $get) {
                                $precioTotal = (float) ($get('total_precio_display') ?? 0);
                                $saldo = max(0, $precioTotal - (float) $state);
                                $set('saldo_pendiente', $saldo);
                            }),

                        Forms\Components\TextInput::make('saldo_pendiente')
                            ->label('💵 Saldo Pendiente')
                            ->prefix('Bs.')
                            ->disabled()
                            ->default(0)
                            ->live()
                            ->visible(fn(Forms\Get $get) => $get('estado_pago') === 'con_saldo')
                            ->dehydrated(),



                    ])


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('numero_guia')
                    ->searchable()
                    ->sortable()
                    ->label('Nº Guía'),
                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->badge(),
                Tables\Columns\TextColumn::make('sucursalOrigen.ciudad')
                    ->searchable()
                    ->sortable()
                    ->label('Oficina Origen'),
                Tables\Columns\TextColumn::make('sucursalDestino.ciudad')
                    ->searchable()
                    ->sortable()
                    ->label('Oficina Destino'),
                Tables\Columns\TextColumn::make('cantidad_paquetes')
                    ->numeric()
                    ->label('Cant.'),
                Tables\Columns\TextColumn::make('precio')
                    ->numeric()
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
                    ->options(EncomiendaEstado::class)
                    ->label('Estado de Encomienda'),


            ])
            ->actions([
                ViewAction::make()
                    ->modalHeading('📦 Detalles de Encomienda')
                    ->modalWidth('5xl')
                    ->color('secondary')
                    ->infolist([
                        Section::make('Información General')
                            ->schema([
                                TextEntry::make('numero_guia')
                                    ->label('📄 Número de Guía'),
                                TextEntry::make('estado')
                                    ->badge()
                                    ->label('📌 Estado'),
                                TextEntry::make('created_at')
                                    ->dateTime()
                                    ->label('🗓 Fecha de Registro'),
                            ])
                            ->columns(3),

                        Section::make('Participantes')
                            ->schema([
                                Group::make()
                                    ->schema([
                                        TextEntry::make('nombre_remitente')
                                            ->label('✉️ Remitente'),
                                        TextEntry::make('sucursalOrigen.ciudad')
                                            ->label('🏢 Ciudad de Origen'),
                                    ]),
                                Group::make()
                                    ->schema([
                                        TextEntry::make('nombre_destinatario')
                                            ->label('📥 Receptor'),
                                        TextEntry::make('sucursalDestino.ciudad')
                                            ->label('🏬 Ciudad de Destino'),
                                    ]),
                            ])
                            ->columns(2),

                        Section::make('Detalles del Envío')
                            ->schema([
                                TextEntry::make('peso')
                                    ->suffix(' kg')
                                    ->label('⚖️ Peso'),
                                TextEntry::make('precio')
                                    ->numeric()
                                    ->label('💰 Precio Total'),
                                TextEntry::make('descripcion_contenido')
                                    ->label('📝 Descripción del Contenido')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),
                    ])->url(null),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEncomiendas::route('/'),
            'create' => Pages\CreateEncomienda::route('/create'),
            'view' => Pages\ViewEncomienda::route('/{record}'),
            'edit' => Pages\EditEncomienda::route('/{record}/edit'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'todos' => Tab::make('Todos')
                ->badge(Encomienda::count()),

            'registradas' => Tab::make('Registradas')
                ->badge(Encomienda::where('estado', EncomiendaEstado::Registrada)->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('estado', EncomiendaEstado::Registrada)),



            'entregadas' => Tab::make('Entregadas')
                ->badge(Encomienda::where('estado', EncomiendaEstado::Entregada)->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('estado', EncomiendaEstado::Entregada)),


        ];
    }
}
