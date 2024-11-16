<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = ['studentID', 'courseID'];

    public function student()
    {
        return $this->belongsTo(User::class, 'studentID', 'studentID');
    }
    public function course()
    {
        return $this->belongsTo(Course::class, 'courseID', 'courseID');
    }
}
