<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UpcomingEvents extends BaseWidget
{
    protected static ?string $heading = 'Upcoming Events';
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Event::query()
                    ->where('start_date', '>=', now())
                    ->orderBy('start_date')
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
                    ->getStateUsing(fn($record) => $record->status)
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'upcoming' => 'success',
                        'ongoing' => 'warning',
                        'past' => 'danger',
                        default => 'gray',
                    }),
            ]);
    }
}
