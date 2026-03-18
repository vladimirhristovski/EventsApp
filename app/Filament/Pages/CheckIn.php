<?php

namespace App\Filament\Pages;

use App\Models\Attendance;
use App\Models\Registration;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class CheckIn extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-qr-code';
    protected static ?string $navigationLabel = 'Check-in';
    protected static string $view = 'filament.pages.check-in';

    public ?array $data = [];
    public ?string $message = null;
    public bool $success = false;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('qr_code')
                    ->label('QR Code')
                    ->placeholder('Scan or type QR code here...')
                    ->required(),
            ])
            ->statePath('data');
    }

    public function checkIn(): void
    {
        $data = $this->form->getState();

        $registration = Registration::where('qr_code', $data['qr_code'])->first();

        if (!$registration) {
            $this->success = false;
            $this->message = 'QR code not found.';
            Notification::make()->title('Not found')->danger()->send();
            return;
        }

        if ($registration->isAttended()) {
            $this->success = false;
            $this->message = $registration->user->name . ' already checked in for ' . $registration->event->title;
            Notification::make()->title('Already checked in')->warning()->send();
            return;
        }

        Attendance::create([
            'registration_id' => $registration->id,
            'checked_in_at' => now(),
        ]);

        $this->success = true;
        $this->message = '✅ ' . $registration->user->name . ' checked in for ' . $registration->event->title;
        Notification::make()->title('Checked in successfully!')->success()->send();
        $this->form->fill();
    }
}
