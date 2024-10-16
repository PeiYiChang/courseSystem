<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function filter(Request $request)
    {
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

        // Return the results to the view or as JSON for API response
        return inertia('Index', ['courses' => $courses]);

    }
}
