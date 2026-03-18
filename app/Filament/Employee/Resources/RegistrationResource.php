<?php

namespace App\Filament\Employee\Resources;

use App\Filament\Employee\Resources\RegistrationResource\Pages;
use App\Models\Registration;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class RegistrationResource extends Resource
{
    protected static ?string $model = Registration::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'My Registrations';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Registration::query()->where('user_id', auth()->id())
            )
            ->columns([
                Tables\Columns\TextColumn::make('event.title')->label('Event'),
                Tables\Columns\TextColumn::make('event.location')->label('Location'),
                Tables\Columns\TextColumn::make('event.start_date')->label('Date')->dateTime(),
                Tables\Columns\TextColumn::make('event.end_date')->label('End Date')->dateTime(),
                Tables\Columns\IconColumn::make('attended')
                    ->label('Attended')
                    ->boolean()
                    ->getStateUsing(fn($record) => $record->isAttended()),
            ])
            ->actions([
                Tables\Actions\Action::make('view_qr')
                    ->label('View QR')
                    ->icon('heroicon-o-qr-code')
                    ->modalHeading('Your QR Code')
                    ->modalContent(function ($record) {
                        $qr = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(250)->generate($record->qr_code);
                        return new HtmlString('<div style="text-align:center;padding:20px;">' . $qr . '</div>');
                    })
                    ->modalSubmitAction(false),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRegistrations::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
