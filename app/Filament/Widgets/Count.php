<?php

namespace App\Filament\Widgets;

use App\Models\MealServe;
use Filament\Widgets\ChartWidget;

class Count extends ChartWidget
{
    protected static ?string $heading = 'Total Serves';

    protected static ?int $sort = 2;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $monthlyData = array_fill(0, 12, ['successful' => 0, 'failed' => 0]);



        $mealServed = MealServe::select('status')->selectRaw('MONTH(served_at) as month, COUNT(*) as count')
            ->whereYear('served_at', '=', date('Y'))
            ->groupBy('month', 'status')->get();
        foreach ($mealServed as $serve) {
            $index = $serve->month - 1; // Month indexes for Jan-Dec (0-11)
            $monthlyData[$index][$serve->status] = $serve->count;
        }



        return [
            'datasets' => [
                [
                    'label' => 'Successful Serves',
                    'data' => array_column($monthlyData, 'successful'),
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgb(254, 205, 46)',
                ],
                [
                    'label' => 'Failed Serves',
                    'data' => array_column($monthlyData, 'failed'),
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgb(255, 99, 132)',
                ],
            ],
            'labels' => [
                //spread operator to create an array of available months if the count is 0
                ...array_map(fn($i) => date('M', mktime(0, 0, 0, $i + 1, 1)), array_keys(array_filter($monthlyData, fn($data) => $data['successful'] > 0 || $data['failed'] > 0))),
            ],
        ];
    }
}
