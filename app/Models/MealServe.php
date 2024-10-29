<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealServe extends Model
{
    protected $fillable = [
        'meal_id',
        'student_id',
        'status',
        'failure_reason',
        'served_by',
    ];

    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
