<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
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
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(255),
            Forms\Components\Textarea::make('description')
                ->rows(3)
                ->nullable(),
            Forms\Components\DateTimePicker::make('start_date')
                ->required(),
            Forms\Components\DateTimePicker::make('end_date')
                ->required()
                ->after('start_date'),
            Forms\Components\TextInput::make('location')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('capacity')
                ->numeric()
                ->required()
                ->minValue(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('location')->searchable(),
                Tables\Columns\TextColumn::make('start_date')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('end_date')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('capacity')->numeric(),
                Tables\Columns\TextColumn::make('registrations_count')
                    ->counts('registrations')
                    ->label('Registered'),
                Tables\Columns\TextColumn::make('capacity_status')
                    ->label('Capacity')
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
                Tables\Actions\Action::make('view_registrations')
                    ->label('Registrations')
                    ->icon('heroicon-o-users')
                    ->modalHeading(fn($record) => 'Registrations for ' . $record->title)
                    ->modalContent(function ($record) {
                        $registrations = $record->registrations()->with(['user', 'attendance'])->get();

                        if ($registrations->isEmpty()) {
                            return new \Illuminate\Support\HtmlString('<p style="padding:16px;">No registrations yet.</p>');
                        }

                        $rows = $registrations->map(function ($reg) {
                            $attended = $reg->isAttended()
                                ? '<span style="color:#16a34a;font-weight:600;">✅ Yes</span>'
                                : '<span style="color:#dc2626;">❌ No</span>';
                            $checkedIn = $reg->attendance?->checked_in_at?->format('d M Y, H:i') ?? '-';
                            return "
                <tr style='border-bottom:1px solid #e5e7eb;'>
                    <td style='padding:10px;'>{$reg->user->name}</td>
                    <td style='padding:10px;'>{$reg->user->email}</td>
                    <td style='padding:10px;'>{$attended}</td>
                    <td style='padding:10px;'>{$checkedIn}</td>
                </tr>
            ";
                        })->implode('');

                        $html = "
            <table style='width:100%;border-collapse:collapse;font-size:0.9rem;'>
                <thead>
                    <tr style='background:#f3f4f6;text-align:left;'>
                        <th style='padding:10px;'>Name</th>
                        <th style='padding:10px;'>Email</th>
                        <th style='padding:10px;'>Attended</th>
                        <th style='padding:10px;'>Checked in at</th>
                    </tr>
                </thead>
                <tbody>{$rows}</tbody>
            </table>
        ";

                        return new \Illuminate\Support\HtmlString($html);
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
