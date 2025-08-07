<?php

namespace App\Filament\Resources;

use App\Enums\ConductoresEstado;
use App\Filament\Resources\ConductorResource\Pages;
use App\Filament\Resources\ConductorResource\RelationManagers;
use App\Models\Conductor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Resources\Components\Tab;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ConductorResource extends Resource
{
    protected static ?string $model = Conductor::class;

    protected static ?string $navigationIcon = 'heroicon-s-truck';
    protected static ?string $navigationLabel = 'Conductores / Choferes';
    protected static ?string $modelLabel = 'Conductores';
    protected static ?string $navigationGroup = 'Personal y Administrativo';
    public static function form(Form $form): Form
    {
        return $form
    ->schema([
        Forms\Components\Section::make('Información del Conductor')
            ->description('Complete los datos personales y profesionales del conductor')
            ->icon('heroicon-o-user')
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('nombre_conductor')
                            ->label('Nombre Completo')
                            ->placeholder('Ingrese el nombre completo del conductor')
                            ->prefixIcon('heroicon-o-user')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),
                        
                        Forms\Components\TextInput::make('celular_conductor')
                            ->label('Número de Celular')
                            ->placeholder('Ej: 78945612')
                            ->prefixIcon('heroicon-o-phone')
                            ->tel()
                            ->required()
                            ->maxLength(8),
                        
                        Forms\Components\TextInput::make('conductor_num_licencia')
                            ->label('Número de Licencia')
                            ->placeholder('Ingrese número de licencia')
                            ->prefixIcon('heroicon-o-identification')
                            ->required()
                            ->maxLength(10),
                    ]),
            ]),
        
        Forms\Components\Section::make('Fechas Importantes')
            ->description('Información sobre licencia y contratación')
            ->icon('heroicon-o-calendar')
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\DatePicker::make('fecha_exp_licencia')
                            ->label('Fecha de Expiración de Licencia')
                            ->placeholder('Seleccione la fecha')
                            ->prefixIcon('heroicon-o-calendar-days')
                            ->displayFormat('d/m/Y')
                            ->required()
                            ->native(false),
                        
                        Forms\Components\DatePicker::make('fecha_contratacion')
                            ->label('Fecha de Contratación')
                            ->placeholder('Seleccione la fecha')
                            ->prefixIcon('heroicon-o-briefcase')
                            ->displayFormat('d/m/Y')
                            ->required()
                            ->native(false),
                    ]),
            ]),
        
        Forms\Components\Section::make('Estado del Conductor')
            ->description('Configuración del estado actual')
            ->icon('heroicon-o-cog-6-tooth')
            ->schema([
                Forms\Components\Select::make('estado')
                    ->label('Estado del Conductor')
                    ->placeholder('Seleccione el estado')
                    ->options(ConductoresEstado::class)
                    ->prefixIcon('heroicon-o-flag')
                    ->required()
                    ->default(ConductoresEstado::Activo)
                    ->native(false),
            ]),
    ])
    ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre_conductor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('celular_conductor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('conductor_num_licencia')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_exp_licencia')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_contratacion')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('estado')
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
                ->options(ConductoresEstado::class)
                ->label('Estado del Conductor'),
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
            RelationManagers\VehiculosRelationManager::class, 
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListConductors::route('/'),
            'create' => Pages\CreateConductor::route('/create'),
            'edit' => Pages\EditConductor::route('/{record}/edit'),
        ];
    }
        public function getTabs(): array
    {
        return [
            'todos' => Tab::make('Todos')
                ->badge(Conductor::count()),

            'registradas' => Tab::make('Activos')
                ->badge(Conductor::where('estado', ConductoresEstado::Activo)->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('estado', ConductoresEstado::Activo)),

            'en_transito' => Tab::make('Inactivos')
                ->badge(Conductor::where('estado', ConductoresEstado::Inactivo)->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('estado', ConductoresEstado::Inactivo)),

            'entregadas' => Tab::make('Vacaciones')
                ->badge(Conductor::where('estado', ConductoresEstado::Vacaciones)->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('estado', ConductoresEstado::Vacaciones)),

            'canceladas' => Tab::make('Suspendido')
                ->badge(Conductor::where('estado', ConductoresEstado::Suspendido)->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('estado', ConductoresEstado::Suspendido)),
        ];
    }
}
