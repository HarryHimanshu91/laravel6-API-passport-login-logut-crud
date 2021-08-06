<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Student;
use Validator;

class StudentController extends Controller
{
    public function saveStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'course' => 'required|max:191',
            'email' => 'required|email|unique:students',
            'phone' => 'required|size:10',
            
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validate_err' => $validator->messages()

            ]);
        } else {
            $student = new Student();
            $student->name = $request->name;
            $student->course = $request->course;
            $student->email = $request->email;
            $student->phone = $request->phone;
            $student->save();
            return response()->json([
                'status' => 200,
                'message' => 'Student Added successfully'
            ]);
        }
    }

    public function getAllStudents()
    {
        $student = Student::all();
        return response()->json([
            'status' => 200,
            'students' => $student,
        ]);
    }

    public function editStudent($id)
    {
        if (preg_match('/^[1-9][0-9]*$/', $id)) {     // check if user enter only valid number,non-negative
            $student = Student::where('id', $id)->first();

            if ($student) {
                return response()->json([
                    'status' => 200,
                    'student' => $student,
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "No Student ID found",
                ]);
            }
        } else {
            return response()->json([
                'status' => 404,
                'message' => "You have entered the wrong ID",
            ]);
        }
    }

    public function updateStudent($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'course' => 'required|max:191',
            'email' => 'required|email|unique:students,email,'.$id,
            'phone' => 'required|max:10|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validate_err' => $validator->messages()
            ]);
        } else {

            $student = Student::find($id);

            if ($student) {
                $student->name = $request->name;
                $student->course = $request->course;
                $student->email = $request->email;
                $student->phone = $request->phone;
              
                $student->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'Student Updated successfully'
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "No Student ID found",
                ]);
            }
        }
    }


    public function deleteStudent($id)
    {
        $student = Student::find($id);

        if($student) {
            $student->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Student Deleted successfully'
            ]);
        }else{
            return response()->json([
                'status' => 404,
                'message' => "No Student ID found",
            ]);
        }
       
    }
}
