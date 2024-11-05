<?php

namespace App\Filament\Widgets;

use App\Models\MealServe;
use Carbon\Carbon;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class MealsTypes extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Meals Served';

    protected static bool $isDiscovered = false;

    protected array|string|int $columnSpan = 'full';

    protected static ?int $sort = 2;

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

        $mealServed = MealServe::with('meal')->select(['status', 'meal_id'])
            ->selectRaw('COUNT(*) as count')
            ->whereBetween('served_at', [$startDate, $endDate])
            ->groupBy('meal_id', 'status')->get();

        // Prepare the data structure for each meal
        $mealData = [];
        foreach ($mealServed as $serve) {
            $mealName = $serve->meal->name; // Get the meal name
            if (!isset($mealData[$mealName])) {
                $mealData[$mealName] = ['successful' => 0, 'failed' => 0];
            }
            $mealData[$mealName][$serve->status] = $serve->count; // Increment the count based on status
        }

        return [
            'datasets' => [
                [
                    'label' => 'Successful Serves',
                    'data' => array_column($mealData, 'successful'), // Get successful counts
                    'backgroundColor' => 'rgba(0, 255, 100, 0.5)',
                    'borderColor' => 'rgba(0, 255, 100, 1)',
                    'fill' => 'start',
                ],
                [
                    'label' => 'Failed Serves',
                    'data' => array_column($mealData, 'failed'), // Get failed counts
                    'backgroundColor' => 'rgba(255, 113, 113, 0.5)',
                    'borderColor' => 'rgb(255, 113, 113)',
                    'fill' => 'start',
                ],
            ],
            'labels' => array_keys($mealData), // Get meal names for labels
        ];
    }

    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<JS
        {
            scales: {
                y: {
                    stacked: false,
                    ticks: {
                        callback: function(value) {if (value % 1 === 0) {return value;}}
                    },
                },
            },
        }
    JS);
    }
}
