<?php

namespace App\Filament\Employee\Resources\RegistrationResource\Pages;

use App\Filament\Employee\Resources\RegistrationResource;
use Filament\Resources\Pages\ListRecords;

class ListRegistrations extends ListRecords
{
    protected static string $resource = RegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
