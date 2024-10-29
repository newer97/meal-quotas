<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MealServe extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'meal_id',
        'student_id',
        'status',
        'failure_reason',
        'served_by',
        'served_at',
    ];

    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function servedBy()
    {
        return $this->belongsTo(User::class, 'served_by');
    }
}
