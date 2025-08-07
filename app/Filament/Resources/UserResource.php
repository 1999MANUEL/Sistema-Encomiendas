<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Personal y Administrativo';
    protected static ?string $navigationLabel = 'Usuarios y Empleados';
    protected static ?string $modelLabel = 'Usuarios';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Usuario')
                    ->description('Proporciona los detalles básicos para la cuenta del usuario.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre Completo')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ej: Juan Pérez'),

                        Forms\Components\TextInput::make('email')
                            ->label('Correo Electrónico')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->placeholder('Ej: juan.perez@example.com'),

                        Forms\Components\DateTimePicker::make('email_verified_at')
                            ->label('Correo Verificado El')
                            ->helperText('Deja vacío si el correo aún no ha sido verificado. Esto puede ser establecido manualmente o mediante un proceso de verificación.')
                            ->visibleOn('edit')
                            ->hiddenOn('create'),
                    ])->columns(2),

                Forms\Components\Section::make('Seguridad y Acceso')
                    ->description('Establece la contraseña del usuario y asigna roles y sucursales.')
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->label('Contraseña')
                            ->password()
                            ->required(fn(string $operation): bool => $operation === 'create')
                            ->confirmed()
                            ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                            ->dehydrated(fn(?string $state): bool => filled($state))
                            ->revealable()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('password_confirmation')
                            ->label('Confirmar Contraseña')
                            ->password()
                            ->required(fn(string $operation): bool => $operation === 'create')
                            ->maxLength(255)
                            ->hiddenOn('edit'),

                        Forms\Components\Select::make('sucursal_id')
                            ->label('Sucursal Asociada')
                            ->relationship('sucursal', 'ciudad')
                            ->placeholder('Selecciona una sucursal (opcional)')
                            ->nullable()
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('roles')
                            ->label('Roles del Usuario')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->helperText('Asigna uno o más roles para definir los permisos del usuario.'),
                    ])->columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre Completo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Correo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('sucursal.ciudad')
                    ->numeric()
                    ->badge()
                    ->color('warning')
                    ->sortable(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
