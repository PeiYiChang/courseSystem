<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;  // 正確引入 Request 類別
use Illuminate\Support\Facades\Auth;  // 引入 Auth 類別，這是正確的地方
use App\Models\Course;  // 引入 Course 模型
use App\Models\User;    // 引入 User 模型
use App\Models\Enrollment; 

class UserController extends Controller
{
    public function addCredit(Request $request)
    {
        $user = Auth::user();  // 使用 Auth 來取得當前登入的使用者
        $courseID = $request->courseID;
        $course = Course::where('courseID', $courseID)->get();  // get all time (3)
        $studentID = $user->studentID;
        foreach ($course as $courseItem) {
            if ($this->scheduleConflict($courseItem, $studentID)) {
                return response()->json(['message' => 'There is a schedule conflict with another course.']);
            }
        }
        if ($course) {
            if($user->credit + $course->first()->credit > 25){
                return response()->json([
                    'message' => 'Failed!!! You cannot add more courses!'
                ]);
            }elseif ($course->first()->maxCapacity <= $course->first()->currentCapacity) {
                return response()->json([
                    'message' => 'Failed! Course is full.'
                ]);
            }
            else{
                $user->credit += $course->first()->credit;  // 增加學分
                $user->save();  // 保存使用者資料
                foreach ($course as $courseItem) {
                    $courseItem->currentCapacity +=1;
                    $courseItem->save();
                }
                
                return response()->json([
                    'message' => 'Course added successfully'
                ]);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
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
    
    public function deleteCredit(Request $request)
    {
        $user = Auth::user();
        $courseID = $request->courseID;
        $course = Course::where('courseID', $courseID)->get();  // get all time (3)
        if ($course) {
            if($user->credit -$course->first()->credit < 9){
                return response()->json([
                    'message' => 'Failed!!! You cannot remove more courses!'
                ]);
            }else if($course->first()->mandatory == 1){
                return response()->json([
                    'message' => 'Failed!!! You cannot remove this course!'
                ]);
            }else{
                $user->credit -= $course->first()->credit;
                $user->save();
                foreach ($course as $courseItem) {
                    $courseItem->currentCapacity -=1;
                    $courseItem->save();
                }
                return response()->json([
                    'message' => 'Course removed successfully'
                ]);
            }
        } else {
            return response()->json(['error' => 'Course not found'], 404);
        }
    }
}
