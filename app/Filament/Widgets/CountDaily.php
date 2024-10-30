<?php

namespace App\Filament\Widgets;

use App\Models\MealServe;
use Carbon\Carbon;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\Log;

use function Illuminate\Log\log;

class CountDaily extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Daily Serves';

    protected static ?int $sort = 3;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $startDate = !is_null($this->filters['startDate'] ?? null) ?
            Carbon::parse($this->filters['startDate'])->startOfDay() :
            now()->startOfYear();

        $endDate = ! is_null($this->filters['endDate'] ?? null) ?
            Carbon::parse($this->filters['endDate'])->endOfDay() :
            now()->endOfDay();

        $mealServed = MealServe::select('status')
            ->selectRaw('DATE(served_at) as date, COUNT(*) as count')
            ->whereBetween('served_at', [$startDate, $endDate])
            ->groupBy('date', 'status')
            ->orderBy('date')
            ->get();

        // Create an array of all dates in the range
        $dateRange = [];
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            $dateRange[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        // Initialize arrays to hold successful and failed serve counts
        $successfulServes = array_fill_keys($dateRange, 0); // Initialize with 0s
        $failedServes = array_fill_keys($dateRange, 0); // Initialize with 0s

        // Populate the arrays with counts based on status
        foreach ($mealServed as $serve) {
            if ($serve->status === 'successful') {
                $successfulServes[$serve->date] = $serve->count;
            } elseif ($serve->status === 'failed') {
                $failedServes[$serve->date] = $serve->count;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Successful Serves',
                    'data' => array_values($successfulServes), // Get counts as an array
                    'backgroundColor' => 'rgba(74, 222, 128,0.2)',
                    'borderColor' => 'rgb(74, 222, 128)',
                    'fill' => 'start',
                ],
                [
                    'label' => 'Failed Serves',
                    'data' => array_values($failedServes), // Get counts as an array
                    'backgroundColor' => 'rgba(248, 113, 113, 0.2)',
                    'borderColor' => 'rgb(248, 113, 113)',
                    'fill' => 'start',
                ],
            ],
            'labels' => array_map(fn($date) => Carbon::parse($date)->format('n/j'), $dateRange), // Format dates as m/d
        ];
    }
    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<JS
        {
            scales: {
                y: {
                    stacked: true,
                    ticks: {
                        callback: function(value) {if (value % 1 === 0) {return value;}}
                    },
                },
                x: {
                    stacked: true,
                }
            },
        }
    JS);
    }
}
