<?php

namespace App\Filament\Resources\ConductorResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Enums\ConductorRol; // ¡Importa tu Enum ConductorRol aquí!
use App\Enums\RolConductorEstado;

class VehiculosRelationManager extends RelationManager
{
    protected static string $relationship = 'vehiculos'; // Esta es la relación PLURAL en el modelo Conductor

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // ****** CORRECCIÓN CRÍTICA AQUÍ ******
                Forms\Components\Select::make('vehiculo_id')
                    // NO uses `relationship()` directamente aquí con el nombre singular.
                    // En su lugar, usa `getOptionQuery` o simplemente `options` si la lista es pequeña.
                    // La forma más robusta para `BelongsToMany` es esta:
                    ->options(
                        \App\Models\Vehiculo::all()->pluck('placa', 'id')
                    )
                    // O MEJOR AÚN, PARA RELACIONES Y BÚSQUEDA:
                    // Esto asume que tienes un `VehiculoResource` definido
                    // Esto hace que el Select sea un "Relationship Select" para la FK
                    // ->relationship('vehiculos') // Apunta a la relación Many-to-Many definida en el ConductorResource
                    // Y luego Filament internamente sabe que el `vehiculo_id`
                    // es para el modelo relacionado, y usa `placa` como titleAttribute
                    ->getOptionLabelUsing(fn($value): ?string => \App\Models\Vehiculo::find($value)?->placa) // Asegura que la etiqueta se muestre correctamente
                    ->searchable()
                    ->preload() // Precarga las opciones para mejorar la UX
                    ->required()
                    ->label('Vehículo'),

                // Campos de la tabla pivote
                Forms\Components\DatePicker::make('fecha_inicio_asignacion')
                    ->required()
                    ->default(now())
                    ->label('Fecha de Inicio'),

                Forms\Components\DatePicker::make('fecha_fin_asignacion')
                    ->nullable()
                    ->label('Fecha de Fin'),

                Forms\Components\Select::make('rol_conductor')
                    ->options(RolConductorEstado::class)
                    ->default(RolConductorEstado::Principal)
                    ->required()
                    ->label('Rol del Conductor'),
            ]);
    }
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('placa') // Usa la placa como título del registro
            ->columns([
                // Columnas de la tabla 'vehiculos'
                Tables\Columns\TextColumn::make('placa')
                    ->searchable()
                    ->sortable()
                    ->label('Placa'),
                Tables\Columns\TextColumn::make('marca')
                    ->searchable()
                    ->sortable()
                    ->label('Marca'),
                Tables\Columns\TextColumn::make('modelo')
                    ->searchable()
                    ->sortable()
                    ->label('Modelo'),

                // Columnas de la tabla pivote (accedidas a través de 'pivot')
                Tables\Columns\TextColumn::make('pivot.fecha_inicio_asignacion')
                    ->date() // Formatea como fecha
                
                    ->label('Fecha Inicio Asignación'),
                Tables\Columns\TextColumn::make('pivot.fecha_fin_asignacion')
                    ->date()
                  
                    ->label('Fecha Fin Asignación'),
                Tables\Columns\TextColumn::make('pivot.rol_conductor')
                    // Utiliza la etiqueta del Enum para mostrar el rol
                    ->formatStateUsing(fn(int $state): string => RolConductorEstado::tryFrom($state)?->getLabel() ?? 'Desconocido')
                 
                     ->badge()
                    ->label('Rol'),
            ])
            ->filters([
                // Puedes añadir filtros aquí, por ejemplo por rol o por estado de asignación
                // Tables\Filters\SelectFilter::make('rol_conductor')
                //     ->options(ConductorRol::class)
                //     ->label('Filtrar por Rol'),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make() // Usa AttachAction para adjuntar un vehículo existente
                    ->form(fn(Forms\Form $form) => $form->schema([
                         Forms\Components\Select::make('recordId')
                            // NO uses `relationship()` directamente aquí con el nombre singular.
                            // En su lugar, usa `getOptionQuery` o simplemente `options` si la lista es pequeña.
                            // La forma más robusta para `BelongsToMany` es esta:
                            ->options(
                                \App\Models\Vehiculo::all()->pluck('placa', 'id')
                            )
                            // O MEJOR AÚN, PARA RELACIONES Y BÚSQUEDA:
                            // Esto asume que tienes un `VehiculoResource` definido
                            // Esto hace que el Select sea un "Relationship Select" para la FK
                            // ->relationship('vehiculos') // Apunta a la relación Many-to-Many definida en el ConductorResource
                            // Y luego Filament internamente sabe que el `vehiculo_id`
                            // es para el modelo relacionado, y usa `placa` como titleAttribute
                            ->getOptionLabelUsing(fn($value): ?string => \App\Models\Vehiculo::find($value)?->placa) // Asegura que la etiqueta se muestre correctamente
                            ->searchable()
                            ->preload() // Precarga las opciones para mejorar la UX
                            ->required()
                            ->label('Vehículo'),
                        // Aquí definimos los campos adicionales de la tabla pivote que deben llenarse al adjuntar
                        Forms\Components\DatePicker::make('fecha_inicio_asignacion')
                            ->required()
                            ->default(now())
                            ->label('Fecha de Inicio'),
                        Forms\Components\DatePicker::make('fecha_fin_asignacion')
                            ->nullable()
                            ->label('Fecha de Fin'),
                        Forms\Components\Select::make('rol_conductor')
                            ->options(RolConductorEstado::class) // Asegúrate de importar ConductorRol
                            ->default(RolConductorEstado::Principal)
                           
                            ->required()
                            ->label('Rol del Conductor'),
                    ])),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
