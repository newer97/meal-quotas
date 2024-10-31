<?php

namespace App\Filament\Widgets;

use App\Models\MealServe;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestServes extends BaseWidget
{
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 'full';



    public function table(Table $table): Table
    {
        return $table
            ->query(MealServe::query()->latest('served_at'))
            ->columns([
                TextColumn::make('meal.name'),
                TextColumn::make('student.name'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'successful' => 'success',
                        'failed' => 'danger',
                        default => 'warning',
                    })->description(fn(MealServe $record): string => $record->failure_reason ?? ''),
                TextColumn::make('servedBy.name'),
                TextColumn::make('served_at')->dateTime(),
            ]);
    }
}
