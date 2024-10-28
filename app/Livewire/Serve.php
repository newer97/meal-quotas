<?php

namespace App\Livewire;

use App\Models\Meal;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Serve extends Component
{
    public $selectedMealId;
    public $showModal = false;
    public $studentNumber;

    public function render()
    {
        return view('livewire.serve', [
            'meals' => Meal::all(),
        ]);
    }

    public function selectMeal($mealId)
    {
        $this->selectedMealId = $mealId;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->studentNumber = '';
        $this->selectedMealId = null;
    }

    public function serve()
    {
        $this->validate(
            [
                'studentNumber' => 'required|numeric|exists:students,student_number',
            ],
            [
                'studentNumber.exists' => 'Student with student number :input does not exist.',
            ]
        );

        $this->showModal = false;
        //return error served
        return $this->addError("served", "Error served");

        $meal = Meal::find($this->selectedMealId);
    }
}
