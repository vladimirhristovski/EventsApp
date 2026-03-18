<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegistrationResource\Pages;
use App\Models\Registration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RegistrationResource extends Resource
{
    protected static ?string $model = Registration::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Registrations';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->relationship('user', 'name')
                ->required()
                ->searchable(),
            Forms\Components\Select::make('event_id')
                ->relationship('event', 'title')
                ->required()
                ->searchable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Employee')->searchable(),
                Tables\Columns\TextColumn::make('event.title')->label('Event')->searchable(),
                Tables\Columns\TextColumn::make('qr_code')->label('QR Code')->limit(20),
                Tables\Columns\IconColumn::make('attendance')
                    ->label('Attended')
                    ->boolean()
                    ->getStateUsing(fn($record) => $record->isAttended()),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('view_qr')
                    ->label('View QR')
                    ->icon('heroicon-o-qr-code')
                    ->modalHeading('QR Code')
                    ->modalContent(function ($record) {
                        $qr = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(250)->generate($record->qr_code);
                        return new \Illuminate\Support\HtmlString('<div style="text-align:center;padding:20px;">' . $qr . '</div>');
                    })
                    ->modalSubmitAction(false),
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
            'index' => Pages\ListRegistrations::route('/'),
            'create' => Pages\CreateRegistration::route('/create'),
            'edit' => Pages\EditRegistration::route('/{record}/edit'),
        ];
    }
}
