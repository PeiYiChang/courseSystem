<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseController extends Controller
{
    // Reusable function to format course data
    private function formatCourses($courses)
    {
        $dayMap = [
            1 => 'Mon',
            2 => 'Tue',
            3 => 'Wed',
            4 => 'Thu',
            5 => 'Fri',
            6 => 'Sat',
            7 => 'Sun',
        ];

        // Group courses by courseID
        $groupedCourses = $courses->groupBy('courseID');
        $formattedCourses = [];

        foreach ($groupedCourses as $courseID => $group) {
            $courseTitle = $group->first()->courseTitle;
            $credit = $group->first()->credit;
            $mandatory = $group->first()->mandatory;
            $grade = $group->first()->grade;
            $major = $group->first()->major;
            $instructorList = $group->pluck('instructor')->unique()->implode(', ');

            // Combine day and period
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

        return $formattedCourses;
    }

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

        // Get all matching courses
        $courses = $query->get();

        // Get unique course IDs and fetch all associated data
        $uniqueCourseIDs = $courses->pluck('courseID')->unique()->toArray();
        $allCourses = Course::whereIn('courseID', $uniqueCourseIDs)->get();

        // Format the courses using the reusable method
        $formattedCourses = $this->formatCourses($allCourses);

        return Inertia::render('Finder', ['courses' => $formattedCourses]);
    }

    public function indexOne(Request $request)
    {
        // Validate the request
        $request->validate([
            'courseID' => 'required|string',
        ]);

        $courseID = $request->input('courseID');

        // Fetch all courses with the given courseID
        $courseInfo = Course::where('courseID', $courseID)->get();


        // Format the courses using the reusable method
        $formattedCourses = $this->formatCourses($courseInfo);

        return Inertia::render('CourseRegister', ['courseInfo' => $formattedCourses]);
    }
}
