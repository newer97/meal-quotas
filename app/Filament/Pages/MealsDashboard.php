<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\MealsTypes;
use App\Filament\Widgets\ServesTable;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Pages\Page;

class MealsDashboard extends Page
{
    use HasFiltersForm;


    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static string $view = 'filament.pages.meals-dashboard';


    public static function getNavigationLabel(): string
    {
        return 'Meals';
    }

    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        DatePicker::make('startDate')
                            ->default(now()->startOfYear())
                            ->maxDate(fn(Get $get) => $get('endDate') ?: now()),
                        DatePicker::make('endDate')
                            ->default(now())
                            ->minDate(fn(Get $get) => $get('startDate') ?: now())
                            ->maxDate(now()),
                    ])
                    ->columns(2),
            ]);
    }

    public function getFooterWidgets(): array
    {

        return [
            MealsTypes::make(['filters' => $this->filters]),
            ServesTable::make(['filters' => $this->filters]),
        ];
    }
}
