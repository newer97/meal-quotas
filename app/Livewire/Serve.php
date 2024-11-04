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
    public $studentId;
    public $mode = 'scan';

    public function render()
    {

        $measl = Meal::where('start_time', '<=', Carbon::now())
            ->where('end_time', '>=', Carbon::now())
            ->get();

        return view('livewire.serve', [
            'meals' => $measl,
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


        $student = $this->validateStudent();
        if (!$student) {
            MealServe::create([
                "meal_id" => $this->selectedMealId,
                "student_id" => null,
                "status" => "failed",
                "failure_reason" => "Student not found",
                "served_by" => Auth::user()->id,
            ]);
            return $this->addError('error_serve', 'Student not found');
        }

        $meal = Meal::find($this->selectedMealId);
        if (!$meal) {
            MealServe::create([
                "meal_id" => null,
                "student_id" => $student->id,
                "status" => "failed",
                "failure_reason" => "Meal not found",
                "served_by" => Auth::user()->id,
            ]);
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

        if ($start->isAfter($end)) {
            // to fix meals that span across midnight
            $end->addDay();
        }


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
        $this->studentNumber = '';
        $this->selectedMealId = null;
        $this->dispatch('meal-served');
    }

    private function validateStudent()
    {
        switch ($this->mode) {
            case 'manual':
                $this->validate(
                    [
                        'studentNumber' => 'required|numeric',
                        'studentId' => 'required|numeric',
                    ]
                );
                return Student::where('student_number', '=', $this->studentNumber)
                    ->where('national_id', '=', $this->studentId)
                    ->first();
            case 'scan':
                $this->validate(
                    [
                        'studentNumber' => 'required|numeric',
                    ]
                );
                return Student::where('student_number', '=', $this->studentNumber)
                    ->first();
        }
    }

    public function switchMode($mode)
    {
        if ($mode !== 'manual' && $mode !== 'scan') {
            return;
        }
        $this->mode = $mode;
    }
}
