<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseLessonContent extends Model
{
    use HasFactory;
    /**
     * Get the lesson that owns the content.
     */
    public function lesson()
    {
        return $this->belongsTo(CourseLesson::class, 'lesson_id');
    }        
}
