<?php

namespace App\Filament\Resources\ManifiestoResource\Pages;

use App\Models\Encomienda;
use App\Filament\Resources\ManifiestoResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Concerns\HasWizard;
use Filament\Notifications\Notification;
use Filament\Actions;
class EditManifiesto extends EditRecord
{
   
    protected static string $resource = ManifiestoResource::class;
 protected function getRedirectUrl(): string
{

    $record = $this->record;
    $editUrl = $this->getResource()::getUrl('edit', ['record' => $record]); 
    return $editUrl;
}
 protected function getHeaderActions(): array
    {
        return [

            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->title('Manifiesto eliminado con exito')
                        ->body('El registro ha sido eliminado con exito')
                        ->success()
                ),
                 Actions\Action::make('imprimir')
            ->label('Imprimir')
            ->url(fn () => route('manifiesto.pdf', ['manifiesto' => $this->record->id]))
            ->openUrlInNewTab()
            ->visible(fn () => $this->record !== null),
        ];
    }
    protected function afterUpdate(): void
{
    $manifiesto = $this->record;
    $data = $this->form->getState();

    $ids = collect($data['encomiendas_temporales'])->pluck('id')->toArray();

    $manifiesto->encomiendas()->sync($ids);

    $manifiesto->precio_total = Encomienda::whereIn('id', $ids)->sum('precio');
    $manifiesto->save();
}

  
    
}
