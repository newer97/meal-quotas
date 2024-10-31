<?php

namespace App\Filament\Widgets;

use App\Models\MealServe;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class FailureReasons extends ChartWidget
{
    use InteractsWithPageFilters;
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
        $startDate = ! is_null($this->filters['startDate'] ?? null) ?
            Carbon::parse($this->filters['startDate'])->startOfDay() :
            now()->startOfYear();

        $endDate = ! is_null($this->filters['endDate'] ?? null) ?
            Carbon::parse($this->filters['endDate'])->endOfDay() :
            now()->endOfDay();
        return MealServe::query()
            ->select('failure_reason', DB::raw('count(*) as total'))
            ->whereNotNull('failure_reason')
            ->whereBetween('served_at', [
                $startDate,
                $endDate,
            ])

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
