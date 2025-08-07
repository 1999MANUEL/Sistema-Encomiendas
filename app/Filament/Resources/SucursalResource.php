<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SucursalResource\Pages;
use App\Filament\Resources\SucursalResource\RelationManagers;
use App\Models\Sucursal;
use Filament\Actions\DeleteAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SucursalResource extends Resource
{
    protected static ?string $model = Sucursal::class;

    protected static ?string $navigationIcon = 'heroicon-s-truck';
    protected static ?string $navigationLabel = 'Oficinas ';
    protected static ?string $navigationGroup='Logistica y Vehiculos';
    protected static ?string $modelLabel = 'Oficinas';
    public static function form(Form $form): Form
    {
       return $form
    ->schema([
        Forms\Components\Section::make('Información de la Oficina')
            ->description('Complete los datos de identificación y ubicación de la oficina')
            ->icon('heroicon-o-building-office-2')
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('codigo_sucursal')
                            ->label('Código de Oficina')
                            ->placeholder('Ej: OF-001, SUC-LP')
                            ->prefixIcon('heroicon-o-hashtag')
                            ->required()
                            ->maxLength(50)
                            ->columnSpan(1),
                        
                        Forms\Components\TextInput::make('contacto')
                            ->label('Teléfono de Contacto')
                            ->placeholder('Ej: 78945612')
                            ->prefixIcon('heroicon-o-phone')
                            ->tel()
                            ->required()
                            ->maxLength(8)
                            ->columnSpan(1),
                    ]),
            ]),
        
        Forms\Components\Section::make('Dirección de la Oficina')
            ->description('Ubicación completa y detallada de la oficina')
            ->icon('heroicon-o-map-pin')
            ->schema([
                Forms\Components\Textarea::make('calle')
                    ->label('Dirección / Calle')
                    ->placeholder('Ej: Av. 6 de Agosto #2170 entre calles Rosendo Gutiérrez y Belisario Salinas')
                   // ->prefixIcon('heroicon-o-map')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
                
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\Textarea::make('zona')
                            ->label('Zona / Barrio')
                            ->placeholder('Ej: Sopocachi, Centro, San Pedro')
                            //->prefixIcon('heroicon-o-building-storefront')
                            ->required()
                            ->rows(2)
                            ->columnSpan(1),
                        
                        Forms\Components\Textarea::make('ciudad')
                            ->label('Ciudad / Municipio')
                            ->placeholder('Ej: La Paz, El Alto, Santa Cruz')
                           // ->prefixIcon('heroicon-o-globe-americas')
                            ->required()
                            ->rows(2)
                            ->columnSpan(1),
                    ]),
            ]),
    ])
    ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codigo_sucursal')
                ->label('Cod. Oficina')
                    ->searchable(),
                       Tables\Columns\TextColumn::make('ciudad')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contacto')
                ->label('No. Contacto')
                    ->searchable(),
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
             
                 Tables\Actions\ViewAction::make()
                    ->modal() 
                    ->modalHeading('Detalles de la sucursal')                    
                    ->modalWidth('5xl')
                   ->url(null),
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
            'index' => Pages\ListSucursals::route('/'),
            'create' => Pages\CreateSucursal::route('/create'),
            'view' => Pages\ViewSucursal::route('/{record}'),
            'edit' => Pages\EditSucursal::route('/{record}/edit'),
        ];
    }
}
