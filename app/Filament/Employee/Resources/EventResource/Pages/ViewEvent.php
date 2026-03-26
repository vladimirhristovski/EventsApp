<?php

namespace App\Filament\Employee\Resources\EventResource\Pages;

use App\Filament\Employee\Resources\EventResource;
use App\Models\Registration;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\Action;

class ViewEvent extends ViewRecord
{
    protected static string $resource = EventResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            \Filament\Infolists\Components\ImageEntry::make('image')
                ->height(300)
                ->columnSpanFull()
                ->getStateUsing(fn($record) => $record->image ? asset('storage/' . $record->image) : null)
                ->visible(fn($record) => $record->image !== null),
            Section::make('Event Details')->schema([
                TextEntry::make('title')->label('Title'),
                TextEntry::make('location')->label('Location'),
                TextEntry::make('start_date')->label('Start Date')->dateTime(),
                TextEntry::make('end_date')->label('End Date')->dateTime(),
                TextEntry::make('capacity')->label('Capacity'),
                TextEntry::make('registrations_count')
                    ->label('Registered')
                    ->getStateUsing(fn($record) => $record->registrations()->count() . ' / ' . $record->capacity),
                TextEntry::make('status')
                    ->label('Status')
                    ->getStateUsing(fn($record) => $record->status)
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'upcoming' => 'success',
                        'ongoing' => 'warning',
                        'past' => 'danger',
                        default => 'gray',
                    }),
                TextEntry::make('description')->label('Description')->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    protected function getHeaderActions(): array
    {
        $record = $this->getRecord();
        $isRegistered = Registration::where('user_id', auth()->id())
            ->where('event_id', $record->id)
            ->exists();

        if ($record->status === 'past') {
            return [];
        }

        if ($isRegistered) {
            return [
                Action::make('unregister')
                    ->label('Unregister')
                    ->color('danger')
                    ->icon('heroicon-o-minus-circle')
                    ->requiresConfirmation()
                    ->action(function () use ($record) {
                        Registration::where('user_id', auth()->id())
                            ->where('event_id', $record->id)
                            ->delete();
                        \Filament\Notifications\Notification::make()
                            ->title('Unregistered successfully!')
                            ->warning()
                            ->send();
                        $this->redirect(EventResource::getUrl('index'));
                    }),
            ];
        }

        if ($record->isFull()) {
            return [];
        }

        return [
            Action::make('register')
                ->label('Register for this Event')
                ->color('success')
                ->icon('heroicon-o-plus-circle')
                ->action(function () use ($record) {
                    Registration::create([
                        'user_id' => auth()->id(),
                        'event_id' => $record->id,
                    ]);
                    \Filament\Notifications\Notification::make()
                        ->title('Registered successfully!')
                        ->success()
                        ->send();
                    $this->redirect(EventResource::getUrl('index'));
                }),
        ];
    }
}
