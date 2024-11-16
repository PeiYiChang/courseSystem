<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;  // 正確引入 Request 類別
use Illuminate\Support\Facades\Auth;  // 引入 Auth 類別，這是正確的地方
use App\Models\Course;  // 引入 Course 模型
use App\Models\User;    // 引入 User 模型

class UserController extends Controller
{
    public function addCredit(Request $request)
    {
        $user = Auth::user();  // 使用 Auth 來取得當前登入的使用者
        $courseID = $request->courseID;

        // 查找指定課程並取得其學分
        $course = Course::where('courseID', $courseID)->first();
        if ($course) {
            if($user->credit >= 25){
                return response()->json([
                    'message' => 'Failed!!! You cannot add more courses!'
                ]);
            }elseif ($course->maxCapacity <= $course->currentCapacity) {
                return response()->json([
                    'message' => 'Failed! Course is full.'
                ]);
            }
            else{
                $user->credit += $course->credit;  // 增加學分
                $user->save();  // 保存使用者資料
                $course->currentCapacity +=1;
                $course->save();
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
    
    public function deleteCredit(Request $request)
    {
        $user = Auth::user();
        $courseID = $request->courseID;
        $course = Course::where('courseID', $courseID)->first();
        if ($course) {
            if($user->credit <= 9){
                return response()->json([
                    'message' => 'Failed!!! You cannot remove more courses!'
                ]);
            }else if($course->mandatory == 1){
                return response()->json([
                    'message' => 'Failed!!! You cannot remove this course!'
                ]);
            }else{
                $user->credit -= $course->credit;
                $user->save();
                $course->currentCapacity -=1;
                $course->save();
                return response()->json([
                    'message' => 'Course removed successfully'
                ]);
            }
        } else {
            return response()->json(['error' => 'Course not found'], 404);
        }
    }
}
