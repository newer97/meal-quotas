<?php

namespace App\Filament\Widgets;

use App\Models\MealServe;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class FailureReasons extends ChartWidget
{
    protected static ?int $sort = 3;
    protected static ?string $heading = 'Failure Reasons';

    protected array|string|int $columnSpan = 1;

    protected array $colors = [
        "#FF6384", // Red
        "#36A2EB", // Blue
        "#FFCE56", // Yellow
        "#4BC0C0", // Teal
        "#9966FF", // Purple
        "#FF9F40", // Orange
        "#FFCD56", // Light Yellow
        "#C9CBCF", // Light Gray
        "#4D5360", // Dark Gray
        "#FF6384", // Pinkish Red
    ];

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getColors(int $count): array
    {
        return array_slice($this->colors, 0, $count);
    }


    protected function getTableQuery(): Builder
    {
        return MealServe::query()
            ->select('failure_reason', DB::raw('count(*) as total'))
            ->whereNotNull('failure_reason')
            ->groupBy('failure_reason')->orderBy('total', 'desc');
    }

    protected function getData(): array
    {
        $data = $this->getTableQuery()->get();
        return [
            'labels' => $data->pluck('failure_reason'),
            'datasets' => [
                [
                    'data' => $data->pluck('total'),
                    'backgroundColor' => $this->getColors($data->count()),
                ],
            ],
        ];
    }
}
