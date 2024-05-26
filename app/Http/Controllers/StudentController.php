<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function show_students(Request $request): View
    {
        $students = Student::all();
        return view('students', \compact('students'));
    }

    public function store(Request $request, Student $students)
    {
        $request->validate([
            'id_number' => 'required|unique:students',
            'fullname' => 'required',
            'program' => 'required',
            'year' => 'required'
        ]);

        $students->create([
            'id_number' => $request->id_number,
            'name' => $request->fullname,
            'email' => $request->email,
            'program' => $request->program,
            'year' => $request->year,
            'set' => $request->set,
        ]);

        return response('ok', 200);
    }

    public function get_student(Student $student)
    {
        return $student;
    }

    public function update_student(Request $request, $id)
    {   
        $request->validate([
            'id_number' => 'required',
            'fullname' => 'required',
            'program' => 'required',
            'year' => 'required'
        ]);
        
        $students = Student::find($id);
        
        $students->update([
            'id_number' => $request->id_number,
            'name' => $request->fullname,
            'email' => $request->email,
            'program' => $request->program,
            'year' => $request->year,
            'set' => $request->set,
        ]);

        return response('ok', 200);
    }

    public function destroy_student(Student $student)
    {
        $student->delete();

        return response('ok', 200);
    }
}