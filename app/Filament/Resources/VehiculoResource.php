<?php

namespace App\Filament\Resources;

use App\Enums\VehiculosEstado;
use App\Filament\Resources\VehiculoResource\Pages;
use App\Filament\Resources\VehiculoResource\RelationManagers;
use App\Models\Vehiculo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VehiculoResource extends Resource
{
    protected static ?string $model = Vehiculo::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
      protected static ?string $navigationGroup='Logistica y Vehiculos';

    public static function form(Form $form): Form
    {
      return $form
    ->schema([
        Forms\Components\Section::make('Información del Vehículo')
            ->description('Complete los datos técnicos y de identificación del vehículo')
            ->icon('heroicon-o-truck')
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('marca')
                            ->label('Marca del Vehículo')
                            ->placeholder('Ej: Toyota, Ford, Chevrolet')
                            ->prefixIcon('heroicon-o-building-office')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),
                        
                        Forms\Components\TextInput::make('modelo')
                            ->label('Modelo del Vehículo')
                            ->placeholder('Ej: Corolla, Focus, Cruze')
                            ->prefixIcon('heroicon-o-tag')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),
                    ]),
                
                Forms\Components\TextInput::make('placa')
                    ->label('Número de Placa')
                    ->placeholder('Ej: 1234-ABC')
                    ->prefixIcon('heroicon-o-rectangle-stack')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(2)
                    ->extraInputAttributes(['style' => 'text-transform: uppercase']),
            ]),
        
        Forms\Components\Section::make('Estado del Vehículo')
            ->description('Configuración del estado operativo actual')
            ->icon('heroicon-o-cog-6-tooth')
            ->schema([
                Forms\Components\Select::make('estado')
                    ->label('Estado del Vehículo')
                    ->placeholder('Seleccione el estado operativo')
                    ->options(VehiculosEstado::class)
                    ->prefixIcon('heroicon-o-flag')
                    ->default(1)
                    ->native(false),
            ]),
    ])
    ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('marca')
                    ->searchable(),
                Tables\Columns\TextColumn::make('modelo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('placa')
                    ->searchable(),
                Tables\Columns\TextColumn::make('estado')
                    ->badge(),
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
                //
            ])
            ->actions([
                    Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListVehiculos::route('/'),
            'create' => Pages\CreateVehiculo::route('/create'),
            'edit' => Pages\EditVehiculo::route('/{record}/edit'),
        ];
    }
}
