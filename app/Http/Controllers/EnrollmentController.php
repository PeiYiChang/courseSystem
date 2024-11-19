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
        $course = Course::where('courseID', $courseID)->get();  // get all time (3)
        $user = Auth::user();
        $studentID = $user->studentID;
        if(($user->credit + $course->first()->credit <= 25) && ($course->first()->maxCapacity > $course->first()->currentCapacity)){
            foreach ($course as $courseItem) {
                if ($this->scheduleConflict($courseItem, $studentID)) {
                    return response()->json(['message' => 'There is a schedule conflict with another course.']);
                }
            }
            Enrollment::updateOrCreate(
                ['studentID' => $studentID, 'courseID' => $courseID],  // Search for duplicarte
                ['studentID' => $studentID, 'courseID' => $courseID]   // if found, do nothing, else create a new one
            );
            return response()->json(['message' => 'Successfully registered for the course']);
        }
    }

    private function scheduleConflict($course, $studentID) {
        // 取得該學生已註冊的所有課程
        $enrollments = Enrollment::where('studentID', $studentID)->get();
    
        foreach ($enrollments as $enrollment) {
            // 查找學生已註冊的課程詳細資料（課程可能有多筆紀錄，表示不同時段）
            $existingCourses = Course::where('courseID', $enrollment->courseID)->get();  // 使用 get() 獲取所有該課程的時段
    
            foreach ($existingCourses as $existingCourse) {
                // 檢查兩門課程時間是否衝突
                if ($this->isTimeConflict($existingCourse, $course)) {
                    return true;  // 如果有衝突，返回 true
                }
            }
        }
    
        return false;  // 如果沒有衝突，返回 false
    }
    
    private function isTimeConflict($existingCourse, $newCourse) {
        // 檢查課程的 day 和 period 是否衝突
        if ($existingCourse->day == $newCourse->day && $existingCourse->period == $newCourse->period) {
            return true;  // 如果兩門課程的 day 和 period 相同，則表示衝堂
        }
    
        return false;  // 否則不衝堂
    }
    

    public function deregister(Request $request){
        $courseID = $request->courseID;
        $course = Course::where('courseID', $courseID)->first();
        $user = Auth::user();
        $studentID = $user->studentID;
        if(($user->credit - $course->first()->credit >= 9) && ($course->mandatory == 0)){
            Enrollment::where('studentID', $studentID)->where('courseID', $courseID)->delete();
            return response()->json(['message' => 'Successfully deregistered from the course']);
        }else {
            return response()->json(['message' => 'Failed!']);
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
