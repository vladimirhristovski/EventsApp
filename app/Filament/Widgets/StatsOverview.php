<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use App\Models\Event;
use App\Models\Registration;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Events', Event::count())
                ->icon('heroicon-o-calendar')
                ->color('primary'),
            Stat::make('Total Registrations', Registration::count())
                ->icon('heroicon-o-clipboard-document-list')
                ->color('success'),
            Stat::make('Total Attendances', Attendance::count())
                ->icon('heroicon-o-check-circle')
                ->color('warning'),
        ];
    }
}
