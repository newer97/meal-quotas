<?php

namespace App\Livewire;

use App\Models\Meal;
use App\Models\MealServe;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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
                'studentNumber' => 'required|numeric',
            ]
        );

        $student = Student::where('student_number', $this->studentNumber)->first();
        if (!$student) {
            return $this->addError('error_serve', 'Student not found');
        }

        $meal = Meal::find($this->selectedMealId);
        if (!$meal) {
            return $this->addError('error_serve', 'Meal not found');
        }

        if ($student->status !== 'active') {
            MealServe::create([
                "meal_id" => $meal->id,
                "student_id" => $student->id,
                "status" => "failed",
                "failure_reason" => "Student not active",
                "served_by" => Auth::user()->id,
            ]);
            return $this->addError('error_serve', 'Student not active');
        }

        $currentTime = Carbon::now();
        $start = Carbon::parse($meal->start_time);
        $end = Carbon::parse($meal->end_time);

        if (!$currentTime->between($start, $end)) {
            MealServe::create([
                "meal_id" => $meal->id,
                "student_id" => $student->id,
                "status" => "failed",
                "failure_reason" => "Meal not served at this time",
                "served_by" => Auth::user()->id,

            ]);
            return $this->addError('error_serve', 'Meal not served at this time');
        }

        //check if already served

        $alreadyServed = MealServe::where('meal_id', $meal->id)
            ->where('student_id', $student->id)
            ->where("status", "successful")
            ->whereDate('served_at', '=', Carbon::today()->toDateString())
            ->first();
        if ($alreadyServed) {
            MealServe::create([
                "meal_id" => $meal->id,
                "student_id" => $student->id,
                "status" => "failed",
                "failure_reason" => "Meal already served",
                "served_by" => Auth::user()->id,
            ]);
            return $this->addError('error_serve', 'Meal already served');
        }

        MealServe::create([
            "meal_id" => $meal->id,
            "student_id" => $student->id,
            "status" => "successful",
            "served_by" => Auth::user()->id,
        ]);

        $this->showModal = false;
        return $this->addError(
            'error_serve',
            "served"
        );
    }
}
