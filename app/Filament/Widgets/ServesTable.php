<?php

namespace App\Filament\Widgets;

use App\Models\MealServe;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget as BaseWidget;

class ServesTable extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 'full';

    protected static bool $isDiscovered = false;


    public function table(Table $table): Table
    {

        $startDate = !is_null($this->filters['startDate'] ?? null) ?
            Carbon::parse($this->filters['startDate'])->startOfDay() :
            now()->startOfYear();

        $endDate = ! is_null($this->filters['endDate'] ?? null) ?
            Carbon::parse($this->filters['endDate'])->endOfDay() :
            now()->endOfDay();

        return $table
            ->query(MealServe::query()->whereBetween('served_at', [$startDate, $endDate]))
            ->columns([
                TextColumn::make('meal.name')->searchable(),
                TextColumn::make('student.name')->searchable(),
                TextColumn::make('failure_reason')
                    ->default('successful')
                    ->searchable()
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'successful' => 'success',
                        default => 'danger',
                    }),
                TextColumn::make('servedBy.name')->searchable(),
                TextColumn::make('served_at')->dateTime()->sortable(),
            ])->filters([
                SelectFilter::make('status')
                    ->options([
                        'successful' => 'Successful',
                        'failed' => 'Failed',
                    ]),
                SelectFilter::make('meal_id')
                    ->label('Meal')
                    ->options(
                        MealServe::with('meal')->get()->pluck('meal.name', 'meal.id')->toArray()
                    ),
                SelectFilter::make('failure_reason')
                    ->options(
                        MealServe::select('failure_reason')->whereNotNull('failure_reason')->distinct()->get()->pluck('failure_reason', 'failure_reason')->toArray()
                    ),
            ])->defaultSort('served_at', "desc");
    }
}
