<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function filter(Request $request)
{   
    $request->validate([
        'courseID' => 'nullable|string',
        'courseTitle' => 'nullable|string',
        'courseInstructor' => 'nullable|string',
        'courseDay' => 'nullable|integer',
        'coursePeriod' => 'nullable|integer',
    ]);
    
    $query = Course::query();
    if ($request->filled('courseID')) {
        $query->where('courseID', $request->input('courseID'));
    }
    if ($request->filled('courseTitle')) {
        $query->where('courseTitle', 'like', '%' . $request->input('courseTitle') . '%');
    }
    if ($request->filled('courseInstructor')) {
        $query->where('instructor', 'like', '%' . $request->input('courseInstructor') . '%');
    }
    if ($request->filled('courseDay')) {
        $query->where('day', $request->input('courseDay'));
    }
    if ($request->filled('coursePeriod')) {
        $query->where('period', $request->input('coursePeriod'));
    }
    $courses = $query->get();
    // take all the unique course IDs out due to how I store data
    $uniqueCourseIDs = $courses->pluck('courseID')->unique()->toArray();

    // fetch all courses info with the unique IDs
    $allCourses = Course::whereIn('courseID', $uniqueCourseIDs)->get();

    $groupedCourses = $allCourses->groupBy('courseID');

    $formattedCourses = [];
    $dayMap = [
        1 => 'Mon',
        2 => 'Tue',
        3 => 'Wed',
        4 => 'Thu',
        5 => 'Fri',
        6 => 'Sat',
        7 => 'Sun',
    ];

    
    foreach ($groupedCourses as $courseID => $group) {
        
        $courseTitle = $group->first()->courseTitle;
        $credit = $group->first()->credit;
        $mandatory = $group->first()->mandatory;
        $grade = $group->first()->grade;
        $major = $group->first()->major;

        $instructorList = $group->pluck('instructor')->unique()->implode(', ');

        $timeSegments = $group->map(function ($course) use ($dayMap) {
            return $dayMap[$course->day] . '-' . $course->period; 
        })->unique(); 

        $time = $timeSegments->implode(', ');

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

    return Inertia::render('Finder', ['courses' => $formattedCourses]);
}

public function indexOne(Request $request)
{
    // Validate that courseID is present in the request
    $request->validate([
        'courseID' => 'required|string', // Adjust validation rules as needed
    ]);

    // Get the courseID from the request
    $courseID = $request->input('courseID');

    // Query the Course model for the specified courseID
    $courseInfo = Course::where('courseID', $courseID)->first(); // Use first() to get a single course

    // If the course is not found, you might want to handle that
    if (!$courseInfo) {
        return response()->json(['message' => 'Course not found'], 404);
    }

    // Create a mapping for days
    $dayMap = [
        1 => 'Mon',
        2 => 'Tue',
        3 => 'Wed',
        4 => 'Thu',
        5 => 'Fri',
        6 => 'Sat',
        7 => 'Sun',
    ];

    // Format the course time directly in this method
    $time = $dayMap[$courseInfo->day] . '-' . $courseInfo->period;

    // Format the course information
    $formattedCourses = [
        'id' => $courseInfo->id, // Assuming you want to return the model's ID
        'courseID' => $courseInfo->courseID,
        'courseTitle' => $courseInfo->courseTitle,
        'credit' => $courseInfo->credit,
        'mandatory' => $courseInfo->mandatory,
        'instructor' => $courseInfo->instructor,
        'grade' => $courseInfo->grade,
        'major' => $courseInfo->major,
        'time' => $time, // Use the formatted time directly
    ];

    return Inertia::render('CourseRegister', ['courseInfo' => [$formattedCourses]]); // Wrap in an array
}

}
