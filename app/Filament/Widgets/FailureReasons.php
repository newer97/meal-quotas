<?php

namespace App\Filament\Widgets;

use App\Models\MealServe;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class FailureReasons extends BaseWidget
{
    protected static ?int $sort = 4;

    protected function getTableQuery(): Builder
    {
        return MealServe::query()
            ->select('failure_reason', DB::raw('count(*) as total'))
            ->whereNotNull('failure_reason')
            ->groupBy('failure_reason')->orderBy('total', 'desc');
    }
    public function getTableRecordKey($record): string
    {
        return $record->failure_reason;
    }
    public function table(Table $table): Table
    {

        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('failure_reason')
                    ->label('Reason')
                    ->badge()
                    ->color('danger'),
                TextColumn::make('total')
                    ->label('Count')
                    ->numeric(),
            ]);
    }
}
