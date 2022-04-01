<?php

namespace App\Http\Controllers\API;

use App\Events\NewUserEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\StudentResource;
use App\Models\StudentInfo;
use App\Models\StudentParentInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function registration(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'address' => 'required',
            'picture' => 'required|mimes:jpeg,jpg,png,gif|max:10000',
            'current_school' => 'required',
            'previous_school' => 'required',
            'parents_info.father_name' => 'required',
            'parents_info.father_phone' => 'required|digits:10',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        $user->assignRole('student');

        $student_info = new StudentInfo();
        $student_info->student_id = $user->id;
        $student_info->address = $request->address;
        $filename = '';
        if ($request->hasFile('picture')) {
            $filename = time() . '-' . $request->picture->getClientOriginalName();
            $request->picture->storeAs('images/students', $filename, 'public');
        }
        $student_info->picture = $filename;
        $student_info->current_school = $request->current_school;
        $student_info->previous_school = $request->previous_school;
        $student_info->save();

        $s_parent_info = new StudentParentInfo();
        $s_parent_info->student_id = $user->id;
        $s_parent_info->father_name = $request->parents_info['father_name'];
        $s_parent_info->father_phone = $request->parents_info['father_phone'];
        if ($request->has('parents_info.mother_name'))
            $s_parent_info->mother_name = $request->parents_info['mother_phone'];
        if ($request->has('parents_info.mother_phone'))
            $s_parent_info->mother_phone = $request->parents_info['mother_phone'];
        if ($request->has('parents_info.parent_address'))
            $s_parent_info->parent_address = $request->parents_info['parent_address'];
        $s_parent_info->save();

        event(new NewUserEvent($user));

        return response()->json([
            'message' => 'Student successfully registered',
            'user' => $user,
        ], 201);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'address' => 'required',
            'current_school' => 'required',
            'previous_school' => 'required'
        ]);

        $user = $request->user();
        $user->name = $request->name;
        $user->save();

        $student_info = StudentInfo::where('student_id', $user->id)->firstOrFail();
        $student_info->address = $request->address;
        $student_info->current_school = $request->current_school;
        $student_info->previous_school = $request->previous_school;
        $student_info->save();

        $s_parent_info = StudentParentInfo::where('student_id', $user->id)->firstOrFail();
        if ($request->has('parents_info.father_name'))
            $s_parent_info->father_name = $request->parents_info['father_name'];
        if ($request->has('parents_info.father_phone'))
            $s_parent_info->father_phone = $request->parents_info['father_phone'];
        if ($request->has('parents_info.mother_name'))
            $s_parent_info->mother_name = $request->parents_info['mother_name'];
        if ($request->has('parents_info.mother_phone'))
            $s_parent_info->mother_phone = $request->parents_info['mother_phone'];
        if ($request->has('parents_info.parent_address'))
            $s_parent_info->parent_address = $request->parents_info['parent_address'];
        $s_parent_info->save();

        return response()->json([
            'message' => 'Student successfully updated',
            'user' => $user,
        ], 201);
    }

    public function changeAvatar(Request $request)
    {
        $this->validate($request, [
            'picture' => 'required|mimes:jpeg,jpg,png,gif|max:10000',
        ]);
        $user = $request->user();
        $student_info = StudentInfo::where('student_id', $user->id)->firstOrFail();
        if ($request->hasFile('picture')) {
            if ($student_info->picture) {
                Storage::delete('public/images/students/' . $student_info->picture);
            }
            $filename = time() . '-' . $request->picture->getClientOriginalName();
            $request->picture->storeAs('images/students', $filename, 'public');
            $student_info->picture = $filename;
            $student_info->save();
        }
        return response()->json([
            'message' => 'Avatar successfully updated',
            'user' => $user,
        ], 201);
    }
}
