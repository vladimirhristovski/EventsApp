<?php

namespace App\Filament\Employee\Resources;

use App\Filament\Employee\Resources\EventResource\Pages;
use App\Models\Event;
use App\Models\Registration;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Events';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Event::query()->where('start_date', '>=', now())->orderBy('start_date')
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('location'),
                Tables\Columns\TextColumn::make('start_date')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('end_date')->dateTime(),
                Tables\Columns\TextColumn::make('capacity')->numeric(),
                Tables\Columns\TextColumn::make('registrations_count')
                    ->counts('registrations')
                    ->label('Registered'),
                Tables\Columns\TextColumn::make('status')
                    ->getStateUsing(fn($record) => $record->isFull() ? 'Full' : 'Available')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Full' => 'danger',
                        'Available' => 'success',
                        default => 'gray',
                    }),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('register')
                    ->label('Register')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->visible(fn($record) => !Registration::where('user_id', auth()->id())->where('event_id', $record->id)->exists() && !$record->isFull())
                    ->action(function ($record) {
                        Registration::create([
                            'user_id' => auth()->id(),
                            'event_id' => $record->id,
                        ]);
                        Notification::make()->title('Registered successfully!')->success()->send();
                    }),
                Tables\Actions\Action::make('unregister')
                    ->label('Unregister')
                    ->icon('heroicon-o-minus-circle')
                    ->color('danger')
                    ->visible(fn($record) => Registration::where('user_id', auth()->id())->where('event_id', $record->id)->exists())
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        Registration::where('user_id', auth()->id())->where('event_id', $record->id)->delete();
                        Notification::make()->title('Unregistered successfully!')->warning()->send();
                    }),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
