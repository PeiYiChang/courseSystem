<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class WatchList extends Model
{
    protected $fillable = ['courseID', 'studentID'];

    public function student()
    {
        return $this->belongsTo(User::class, 'studentID', 'studentID');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'courseID', 'courseID'); // Assuming courseID is the key in the Course model
    }
}

/*
$watchList = WatchList::find(1); // Replace with your watch list ID
$student = $watchList->student; // Access the related student
$course = $watchList->course; // Access the related course
*/
