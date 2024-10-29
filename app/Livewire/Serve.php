<?php

namespace App\Livewire;

use App\Models\Meal;
use App\Models\Student;
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

        $meal = Meal::find($this->selectedMealId);
        if (!$meal) {
            return $this->addError('error_serve', 'Meal not found');
        }
        $currentTime = now()->format('H:i:s');

        log::info(
            "time: " . now()->format('H:i:s') . " meal start time: " . $meal->start_time . " meal end time: " . $meal->end_time . " is active " . (($currentTime > $meal->start_time && $currentTime < $meal->end_time) ? 'true' : 'false')
        );

        // check if the time is between the meal time and the meal end time
        $currentTime = now()->format('H:i:s');
        if ($currentTime < $meal->start_time || $currentTime > $meal->end_time) {
            return $this->addError('error_serve', 'Meal not served at this time');
        }




        //return error served
        return $this->addError("served", "Error served");

        $this->showModal = false;
        $meal = Meal::find($this->selectedMealId);
    }
}
