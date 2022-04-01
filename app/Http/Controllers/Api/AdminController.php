<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StudentInfo;
use App\Models\User;
use App\Notifications\StudentAssignNotification;
use App\Notifications\UserApproveNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class AdminController extends Controller
{
    public function approveUser($id)
    {
        $user = User::findOrfail($id);
        $user->status = 1;
        $user->save();
        if ($user->status == 1) {
            Notification::send($user, new UserApproveNotification($user));
            return response()->json([
                'message' => 'User successfully approved',
                'user' => $user,
            ], 201);
        } else {
            return response()->json([
                'errors' => 'Sorry, something went wrong.',
                'user' => $user,
            ], 400);
        }
    }

    public function assignTeacher($teacher_id, $student_id)
    {
        $teacher = User::findOrfail($teacher_id);
        if (!$teacher->hasRole('teacher'))
            return response()->json(['error' => 'Teacher is not exist.'], 422);

        $student = User::findOrfail($student_id);
        if (!$student->hasRole('student'))
            return response()->json(['error' => 'Student is not exist.'], 422);

        $student_info = StudentInfo::where('student_id', $student_id)->firstOrfail();
        $student_info->teacher_id = $teacher_id;
        $student_info->save();
        Notification::send($teacher, new StudentAssignNotification($student));
        return response()->json([
            'message' => 'Teacher successfully assigned to student.',
        ], 201);
    }
}
