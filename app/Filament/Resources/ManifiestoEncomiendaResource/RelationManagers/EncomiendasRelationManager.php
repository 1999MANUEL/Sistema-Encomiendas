<?php

namespace App\Filament\Resources\ManifiestoResource\RelationManagers;

use App\Filament\Resources\EncomiendaResource\Widgets\StatsOverviewWidget;
use App\Models\Encomienda;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EncomiendasRelationManager extends RelationManager
{
    protected static string $relationship = 'encomiendas';

    protected static ?string $recordTitleAttribute = 'numero_guia';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('id')
                ->label('Encomienda')
                ->options(Encomienda::pluck('numero_guia', 'id'))
                ->searchable()
                ->required()
                ->reactive()
                ->live()
                ->afterStateUpdated(function ($state, callable $set) {
                    if ($state) {
                        $encomienda = Encomienda::find($state);
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
                }),

            Forms\Components\TextInput::make('nombre_remitente')
                ->label('Remitente')
                ->disabled(),

            Forms\Components\Textarea::make('contenido')
                ->label('Contenido')
                ->disabled(),

            Forms\Components\TextInput::make('precio')
                ->label('Precio (Bs.)')
                ->prefix('Bs.')
                ->disabled(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('numero_guia')->label('Número de Guía')->searchable(),
                Tables\Columns\TextColumn::make('nombre_remitente')->label('Remitente'),
                Tables\Columns\TextColumn::make('precio')->label('Precio (Bs.)')->prefix('Bs.'),
             
         
         
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    //->preload()
                    ->form(fn(Forms\Form $form) => $form->schema([
                        Forms\Components\Select::make('recordId')
                            ->label('Encomienda existente')
                            ->options(
                                Encomienda::whereDoesntHave('manifiestos') // Opcional: solo las que no están asignadas
                                    ->pluck('numero_guia', 'id')
                            )
                            ->searchable()
                            ->required(),
                    ]))
                    ->after(function ($record, $data, RelationManager $livewire) {
                        // Después de adjuntar, recalcular y guardar total
                        $manifiesto = $livewire->getOwnerRecord();
                        $allAttachedEncomiendaIds = $manifiesto->encomiendas()->pluck('id')->toArray();

                        $manifiesto->precio_total = $manifiesto->encomiendas()->sum('precio');
                        $manifiesto->save();

                   

                    }),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                    ->after(function ($record, RelationManager $livewire) {
                        // Después de desvincular, recalcular y guardar total
                        $manifiesto = $livewire->getOwnerRecord();
                        $manifiesto->precio_total = $manifiesto->encomiendas()->sum('precio');
                        $manifiesto->save();
                       
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make()
                    ->after(function ($records, RelationManager $livewire) {
                        $manifiesto = $livewire->getOwnerRecord();
                        $manifiesto->precio_total = $manifiesto->encomiendas()->sum('precio');
                        $manifiesto->save();
                    }),
            ]);
    }
    

    
}
