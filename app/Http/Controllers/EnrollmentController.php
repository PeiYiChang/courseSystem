<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WatchList;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class EnrollmentController extends Controller
{
    public function register(Request $request) {
        $courseID = $request->courseID;
        $user = Auth::user();
        $studentID = $user->studentID;
        if($user->credit < 25){
            Enrollment::updateOrCreate(
                ['studentID' => $studentID, 'courseID' => $courseID],  // Search for duplicarte
                ['studentID' => $studentID, 'courseID' => $courseID]   // if found, do nothing, else create a new one
            );
            return response()->json(['message' => 'Successfully registered for the course']);
        }
}
    

    public function deregister(Request $request){
        $courseID = $request->courseID;
        $user = Auth::user();
        $studentID = $user->studentID;
        if($user->credit > 9){
            Enrollment::where('studentID', $studentID)->where('courseID', $courseID)->delete();
            return response()->json(['message' => 'Successfully deregistered from the course']);
        }
}

    

public function index(){
    // Get the logged-in user
    $user = Auth::user();
    $studentID = $user->studentID;

    // Get distinct courseIDs for the user
    $courseIDs = Enrollment::where('studentID', $studentID)->distinct()->pluck('courseID');

    // Return the courseIDs in a simple array format
    return response()->json($courseIDs);
}

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

public function enrolledAll()
{
    $user = Auth::user();
    $studentID = $user->studentID;

    // Get distinct courseIDs that the student is enrolled in
    $courseIDs = Enrollment::where('studentID', $studentID)->distinct()->pluck('courseID');

    // Fetch all the course information for the enrolled courses using `whereIn`
    $courseInfo = Course::whereIn('courseID', $courseIDs)->get();

    
    return response()->json($courseInfo);
}


}
