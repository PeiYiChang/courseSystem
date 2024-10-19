<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WatchList;
use App\Models\Course;
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

    }

    public function deregister(Request $request){
        $courseID = $request->courseID;
        $user = Auth::user();
        $studentID = $user->studentID;

        Enrollment::where('studentID', $studentID)->where('courseID', $courseID)->delete();

    }

}
