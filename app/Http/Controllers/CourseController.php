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

        // Apply filters based on user input
        if ($request->filled('courseID')) {
            $query->where('courseID', 'like', '%' . $request->input('courseID') . '%');
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

        // Fetch the filtered results
        $courses = $query->get();
        // \Log::info($query->toSql());

        $formattedCourses = $courses->map(function ($course) {
            return [
                'courseID' => $course->courseID,
                'courseTitle' => $course->courseTitle,
                'credit' => $course->credit,
                'mandatory' => $course->mandatory,
                'instructor' => $course->instructor,
                'major' => $course->major,
                'grade' => $course->grade,
                'time' => $course->day . ' ' . $course->period, 
            ];
        });
        //dd($formattedCourses);
        
        
        return Inertia::render('Finder', ['courses' => $formattedCourses->toArray(),]);
        

    }
}
