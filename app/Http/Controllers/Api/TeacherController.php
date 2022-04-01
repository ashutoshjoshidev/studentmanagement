<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TeacherInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    public function registration(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'address' => 'required',
            'picture' => 'required|mimes:jpeg,jpg,png,gif|max:10000',
            'experience' => 'required',
            'expertise_subjects' => 'required',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        $user->assignRole('teacher');

        $teacher_info = new TeacherInfo();
        $teacher_info->teacher_id = $user->id;
        $teacher_info->address = $request->address;
        $filename = '';
        if ($request->hasFile('picture')) {
            $filename = time() . '-' . $request->picture->getClientOriginalName();
            $request->picture->storeAs('images/teachers', $filename, 'public');
        }
        $teacher_info->picture = $filename;
        $teacher_info->current_school = $request->current_school;
        $teacher_info->previous_school = $request->previous_school;
        $teacher_info->experience = $request->experience;
        $teacher_info->expertise_subjects = $request->expertise_subjects;
        $teacher_info->save();

        return response()->json([
            'message' => 'Teacher successfully registered',
            'user' => $user,
        ], 201);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'address' => 'required',
            'experience' => 'required',
            'expertise_subjects' => 'required',
        ]);

        $user = $request->user();
        $user->name = $request->name;
        $user->save();

        $teacher_info = TeacherInfo::where('teacher_id', $user->id)->firstOrFail();
        $teacher_info->current_school = $request->current_school;
        $teacher_info->previous_school = $request->previous_school;
        $teacher_info->address = $request->address;
        $teacher_info->experience = $request->experience;
        $teacher_info->expertise_subjects = $request->expertise_subjects;
        $teacher_info->save();

        return response()->json([
            'message' => 'Teacher successfully updated',
            'user' => $user,
        ], 201);
    }

    public function changeAvatar(Request $request)
    {
        $this->validate($request, [
            'picture' => 'required|mimes:jpeg,jpg,png,gif|max:10000',
        ]);
        $user = $request->user();
        $teacher_info = TeacherInfo::where('teacher_id', $user->id)->firstOrFail();
        if ($request->hasFile('picture')) {
            if ($teacher_info->picture) {
                Storage::delete('public/images/teachers/' . $teacher_info->picture);
            }
            $filename = time() . '-' . $request->picture->getClientOriginalName();
            $request->picture->storeAs('images/teachers', $filename, 'public');
            $teacher_info->picture = $filename;
            $teacher_info->save();
        }
        return response()->json([
            'message' => 'Avatar successfully updated',
            'user' => $user,
        ], 201);
    }
}
