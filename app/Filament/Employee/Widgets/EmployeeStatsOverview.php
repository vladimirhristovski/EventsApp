<?php

namespace App\Filament\Employee\Widgets;

use App\Models\Registration;
use App\Models\Event;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EmployeeStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $userId = auth()->id();

        $totalRegistrations = Registration::where('user_id', $userId)->count();

        $totalAttended = Registration::where('user_id', $userId)
            ->whereHas('attendance')
            ->count();

        $upcomingEvents = Registration::where('user_id', $userId)
            ->whereHas('event', fn($q) => $q->where('start_date', '>=', now()))
            ->count();

        return [
            Stat::make('My Registrations', $totalRegistrations)
                ->icon('heroicon-o-clipboard-document-list')
                ->color('primary'),
            Stat::make('Events Attended', $totalAttended)
                ->icon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Upcoming Events', $upcomingEvents)
                ->icon('heroicon-o-calendar')
                ->color('warning'),
        ];
    }
}
