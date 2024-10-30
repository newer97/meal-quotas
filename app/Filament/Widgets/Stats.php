<?php

namespace App\Filament\Widgets;

use App\Models\MealServe;
use Filament\Widgets\Widget;


use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Log;

class Stats extends BaseWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $count = MealServe::groupBy('status')->selectRaw('status, COUNT(*) as count')->get();
        $total = $count->sum('count');
        return [
            Stat::make('Total Attempts', $total)->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('info'),
            Stat::make('Successful Serves', $count->where('status', 'successful')->first()->count)
                ->chart([3, 10, 2, 7, 15, 4, 17])
                ->color('success'),
            Stat::make('Failed Serves', $count->where('status', 'failed')->first()->count)
                ->chart([2, 7, 3, 10, 15, 4, 17])
                ->color('danger'),

        ];
    }
}
