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

        Enrollment::updateOrCreate(
            ['studentID' => $studentID, 'courseID' => $courseID],  // Search for duplicarte
            ['studentID' => $studentID, 'courseID' => $courseID]   // if found, do nothing, else create a new one
        );
        return response()->json(['message' => 'Successfully registered for the course']);
}
    

    public function deregister(Request $request){
        $courseID = $request->courseID;
        $user = Auth::user();
        $studentID = $user->studentID;

        Enrollment::where('studentID', $studentID)->where('courseID', $courseID)->delete();
        return response()->json(['message' => 'Successfully deregistered from the course']);
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


}
