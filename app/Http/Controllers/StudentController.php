<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Student;
use Illuminate\View\View;
use App\Models\Monitoring;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function show_students(Request $request): View
    {
        $students = Student::all();
        logger($request->all());
        return view('students', \compact('students'));
    }

    public function store(Request $request, Student $students)
    {
        $request->validate([
            'id_number' => 'required|unique:students',
            'fullname' => 'required',
            'course' => 'required',
            'program' => 'required',
            'year' => 'required'
        ]);

        $students->create([
            'id_number' => $request->id_number,
            'name' => $request->fullname,
            'email' => $request->email,
            'course' => $request->course,
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
            'course' => 'required',
            'program' => 'required',
            'year' => 'required'
        ]);
        
        $students = Student::find($id);
        
        $students->update([
            'id_number' => $request->id_number,
            'name' => $request->fullname,
            'email' => $request->email,
            'course' => $request->course,
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

    public function info_student(Student $student, Monitoring $monitoring, Event $event, Request $request)
    {
        $events = $event->all();

        $mon = [];
        $i = 0;
        $total = 0;
        foreach($events as $eve){
            
            // $mon[$i]['consequence'] = ($monitoring->where('student_id', $student->id)->where('event_id', $eve->id)->where('remarks', 'late')->groupBy('option')->count() * $eve->consequence);
            $monit = $monitoring->where('student_id', $student->id)->where('event_id', $eve->id)->with('events')->groupBy('option')->get();
            $cnt_late = 0;
            $cnt_absent = 0;
            if(count($monit) > 0){
                foreach($monit as $record){
                    if($record->events->settings && $record->events->settings == 'wday'){
                        if($record->option == 'Login' && $record->remarks == 'late'){
                            $cnt_late = $cnt_late + 1;
                        }
                        else if($record->option == 'Logout' && $record->remarks == 'late'){
                            $cnt_late = $cnt_late + 1;
                        }
                        else if($record->option == 'Login Afternoon' && $record->remarks == 'late'){
                            $cnt_late = $cnt_late + 1;
                        }
                        else if($record->option == 'Logout Afternoon' && $record->remarks == 'late'){
                            $cnt_late = $cnt_late + 1;
                        }
                    }
                    else if($record->events->settings && $record->events->settings == 'hday'){
                        if($record->option == 'Login' && $record->remarks == 'late'){
                            $cnt_late = $cnt_late + 1;
                        }
                        else if($record->option == 'Logout' && $record->remarks == 'late'){
                            $cnt_late = $cnt_late + 1;
                        }
                    }
                    else{
                        $cnt_absent = $cnt_absent + 1;
                    } 
                }
            }
            else{
                $cnt_absent = $cnt_absent + 1;
            }
            $mon[$i]['event_name'] = $eve->title;
            $mon[$i]['consequence'] = $cnt_late !== 0 ? ($cnt_late * $eve->consequence) : ($cnt_absent * $eve->consequence); 
            $total = $total + $mon[$i]['consequence'];
            logger($eve->title);
            logger('late:'.$cnt_late);
            logger('absent:'.$cnt_absent);
            $i++;
        }
        $mon['total'] = $total;
        logger($mon);
        return [
            'mon_data' => $mon,
            'student_data' => $student
        ];
    }
}