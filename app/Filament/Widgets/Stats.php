<?php

namespace App\Filament\Widgets;

use App\Models\MealServe;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\Widget;


use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Log;

class Stats extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $startDate = ! is_null($this->filters['startDate'] ?? null) ?
            Carbon::parse($this->filters['startDate'])->startOfDay() :
            now()->startOfYear();

        $endDate = ! is_null($this->filters['endDate'] ?? null) ?
            Carbon::parse($this->filters['endDate'])->endOfDay() :
            now()->endOfDay();

        $count = MealServe::groupBy('status')->selectRaw('status, COUNT(*) as count')
            ->whereBetween('served_at', [$startDate, $endDate])
            ->get();
        $total = $count->sum('count');

        $successfulServes = $count->where('status', 'successful')->first();
        $failedServes = $count->where('status', 'failed')->first();

        return [
            Stat::make('Total Attempts', $total)->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('info'),
            Stat::make('Successful Serves', $successfulServes ? $successfulServes->count : 0)
                ->chart([3, 10, 2, 7, 15, 4, 17])
                ->color('success'),
            Stat::make('Failed Serves', $failedServes ? $failedServes->count : 0)
                ->chart([2, 7, 3, 10, 15, 4, 17])
                ->color('danger'),
        ];
    }
}
