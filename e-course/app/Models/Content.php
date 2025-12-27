<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    
    protected $guarded = ['id'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }


    public function progress()
    {
        return $this->hasMany(ContentProgress::class);
    }

    // Check if content is completed by specific student
    public function isCompletedBy($studentId)
    {
        return $this->progress()
            ->where('student_id', $studentId)
            ->where('is_completed', true)
            ->exists();
    }
}
