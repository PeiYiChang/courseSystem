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
    
        // Group courses by ID
        $groupedCourses = $courses->groupBy('courseID');
    
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
            // Extract course details
            $courseTitle = $group->first()->courseTitle;
            $credit = $group->first()->credit;
            $mandatory = $group->first()->mandatory;
            $grade = $group->first()->grade;
            $major = $group->first()->major;
            // pluck: retrieves all instructor names, unique: remove duplicates, implode: joins the unique names with, 
            $instructorList = $group->pluck('instructor')->unique()->implode(', ');
    
            // Collect periods and format time
            $timeSegments = $group->map(function ($course) use ($dayMap) {
                return $dayMap[$course->day] . '-' . $course->period; // Format as "Day Period"
            })->unique(); // Ensure unique time segments
    
            // Combine time segments into a single string
            $time = $timeSegments->implode(', ');
    
            // Push the formatted course to the array
            $formattedCourses[] = [
                'id' => $courseID,
                'courseID' => $courseID,
                'courseTitle' => $courseTitle,
                'credit' => $credit,
                'mandatory' => $mandatory,
                'instructor' => $instructorList,
                'grade'=>$grade,
                'major' => $major,
                'time' => $time,
            ];
        }
    
        return Inertia::render('Finder', ['courses' => $formattedCourses]);
        
    }
}
