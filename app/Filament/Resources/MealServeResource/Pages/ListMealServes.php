<?php

namespace App\Filament\Resources\MealServeResource\Pages;

use App\Filament\Resources\MealServeResource;
use App\Filament\Resources\MealServeResource\Widgets\StatsOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMealServes extends ListRecords
{
    protected static string $resource = MealServeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
