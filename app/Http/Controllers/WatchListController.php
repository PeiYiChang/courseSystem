<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WatchList;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class WatchListController extends Controller
{
    public function index()
{

    $user = Auth::user();
    $studentID = $user->studentID;

    // Fetch the course IDs from the watchlist for the current student
    $courseIDs = WatchList::where('studentID', $studentID)->pluck('courseID');

    // Fetch the corresponding course details
    $courses = Course::whereIn('courseID', $courseIDs)->get();

    // Check if courses are being retrieved correctly
    if ($courses->isEmpty()) {
        // You might want to handle this case by returning a specific response or message
        return response()->json(['message' => 'No courses found in watchlist'], 200);
    }

    // Define the day mapping
    $dayMap = [
        1 => 'Mon',
        2 => 'Tue',
        3 => 'Wed',
        4 => 'Thu',
        5 => 'Fri',
        6 => 'Sat',
        7 => 'Sun',
    ];

    // Initialize an array for formatted courses
    $formattedCourses = [];

    // Process each course group
    foreach ($courses->groupBy('courseID') as $courseID => $group) {
        $firstCourse = $group->first();  // Get the first instance of the course

        // Get course details
        $courseTitle = $firstCourse->courseTitle;
        $credit = $firstCourse->credit;
        $mandatory = $firstCourse->mandatory;
        $grade = $firstCourse->grade;
        $major = $firstCourse->major;

        // Get a unique list of instructors and the times for the course
        $instructorList = $group->pluck('instructor')->unique()->implode(', ');
        $timeSegments = $group->map(function ($course) use ($dayMap) {
            return $dayMap[$course->day] . '-' . $course->period;
        })->unique();

        $time = $timeSegments->implode(', ');

        // Build the formatted course data
        $formattedCourses[] = [
            'id' => $courseID,
            'courseID' => $courseID,
            'courseTitle' => $courseTitle,
            'credit' => $credit,
            'mandatory' => $mandatory,
            'instructor' => $instructorList,
            'grade' => $grade,
            'major' => $major,
            'time' => $time,
        ];
    }

    // Debugging output for formatted courses
    // dd($formattedCourses);

    // Return the formatted courses to the view using Inertia
    //return Inertia::render('WatchList', ['watchListData' => $formattedCourses]);    
    return response()->json($formattedCourses);
}

    
}

