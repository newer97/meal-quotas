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
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\Log;

use function Illuminate\Log\log;

class CountMonthly extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Total Monthly Serves';

    protected static ?int $sort = 2;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $monthlyData = array_fill(0, 12, ['successful' => 0, 'failed' => 0]);

        $startDate = !is_null($this->filters['startDate'] ?? null) ?
            Carbon::parse($this->filters['startDate'])->startOfDay() :
            now()->startOfYear();

        $endDate = ! is_null($this->filters['endDate'] ?? null) ?
            Carbon::parse($this->filters['endDate'])->endOfDay() :
            now()->endOfDay();

        $mealServed = MealServe::select('status')->selectRaw('MONTH(served_at) as month, COUNT(*) as count')
            ->whereBetween('served_at', [$startDate, $endDate])
            ->groupBy('month', 'status')->get();
        foreach ($mealServed as $serve) {
            $index = $serve->month - 1; // Month indexes for Jan-Dec (0-11)
            $monthlyData[$index][$serve->status] = $serve->count;
        }

        $monthlyData = array_filter($monthlyData, fn($data) => $data['successful'] > 0 || $data['failed'] > 0);


        return [
            'datasets' => [
                [
                    'label' => 'Successful Serves',
                    'data' => array_column($monthlyData, 'successful'),
                    'backgroundColor' => 'rgba(0, 255, 100, 0.5)',
                    'borderColor' => 'rgba(0, 255, 100, 1)',
                    'fill' => 'start',

                ],
                [
                    'label' => 'Failed Serves',
                    'data' => array_column($monthlyData, 'failed'),
                    'backgroundColor' => 'rgba(255, 113, 113, 0.5)',
                    'borderColor' => 'rgb(255, 113, 113)',
                    'fill' => 'start',
                ],
            ],
            'labels' => [
                ...array_map(fn($i) => date('M', mktime(0, 0, 0, $i + 1, 1)), array_keys($monthlyData)),
            ],
        ];
    }
}
