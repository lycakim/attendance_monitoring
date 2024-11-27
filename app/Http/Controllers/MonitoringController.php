<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\Student;
use Illuminate\View\View;
use App\Models\Monitoring;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\Queue\Monitor;
use Illuminate\Support\Facades\Session;

class MonitoringController extends Controller
{
    public function store(Request $request, Monitoring $monitoring)
    {
        $request->validate([
            'event_id' => 'required',
            'option' => 'required',
        ]);
        
        $monitoring->create([
            'default' => 'system',
            'user_id' => auth()->user()->id,
            'event_id' => $request->event_id,
            'option' => $request->option,
        ]);

        $event = Event::find($request->event_id);
        $event->is_turn_on = true;
        $event->save();
        logger($request->option);
        
        session()->put('monitoring_progress', $request->event_id);
        session()->put('event_id', $request->event_id);
        session()->put('event_name', $request->event_name);
        
        if($request->option == 'Login'){
            session()->put('time_in', $event->morning_login_start);
            session()->put('time_out', $event->morning_login_finish);
        }
        else if($request->option == 'Logout'){
            session()->put('time_in', $event->morning_logout_start);
            session()->put('time_out', $event->morning_logout_finish);
        }
        else if($request->option == 'Login Afternoon'){
            session()->put('time_in', $event->afternoon_login_start);
            session()->put('time_out', $event->afternoon_login_finish);
        }
        else if($request->option == 'Logout Afternoon'){
            session()->put('time_in', $event->afternoon_logout_start);
            session()->put('time_out', $event->afternoon_logout_finish);
        }
        session()->put('option', $request->option);
        
        if(Session::has('monitoring_progress')){
            $events = Event::find($request->event_id);
            $monitorings = Monitoring::where('event_id', session()->get('event_id'))->where('option', session()->get('option'))->where('default', '!=', 'system')->orderBy('created_at', 'desc')->get();
            return view('monitoring-page')->with(compact('events', 'monitorings'));
        }
        else{
            abort(403);
            return redirect()->route('events');
        }
    }

    public function destroy_monitoring(Monitoring $monitoring)
    {
        $monitoring->create([
            'default' => 'system',
            'user_id' => auth()->user()->id,
            'event_id' => session()->get('event_id'),
            'option' => session()->get('option'),
        ]);

        $event = Event::find(session()->get('event_id'));
        $event->is_turn_on = false;
        $event->save();
        
        session()->forget('monitoring_progress');
        session()->forget('event_id');
        session()->forget('event_name');
        session()->forget('option');
        session()->forget('time_in');
        session()->forget('time_out');
        session()->forget('student_name');
        session()->forget('student_course');
        session()->forget('student_section');
        session()->forget('student_year');
        
        return response('ok', 200);
    }

    public function student_store(Request $request, Monitoring $monitoring)
    {
        try {
            $request->validate([
                'id_number' => 'required'
            ]);

            $student = Student::where('id_number', $request->id_number)->first();
            $if_ex = Monitoring::where('student_id', $student->id)->where('event_id', session()->get('event_id'))->where('option', session()->get('option'))->first();

            if($student == null){
                return response('Not found', 404);
            }
            
            if($if_ex != null){
                return response('Already monitored, 200');
            }

            $remarks = '';
            if(session()->has('time_out')){
                $actualCheckinTime = Carbon::now();
                $timeOut = Carbon::parse(session()->get('time_out'));

                if ($timeOut->hour == $actualCheckinTime->hour && $timeOut->minute == $actualCheckinTime->minute) {
                    $remarks = 'on time';
                }
                else if ($actualCheckinTime->greaterThan($timeOut)) {
                    $remarks = 'late';
                } else if($timeOut->format('A') !== $actualCheckinTime->format('A')){
                    $remarks = 'late';
                } else {
                    $remarks = 'on time';
                }
            }
            
            $monitoring->create([
                'default' => 'student',
                'student_id' => $student->id,
                'user_id' => auth()->user()->id,
                'event_id' => session()->get('event_id'),
                'remarks' => $remarks,
                'option' => session()->get('option'),
            ]);

            session()->put('student_name', $student->name);
            session()->put('student_course', $student->course);
            session()->put('student_section', $student->set);
            session()->put('student_year', $student->year);
    
            return response('ok', 200);
        } catch (\Throwable $th) {
            //throw $th;
        }   
    }

    public function get_events_and_monitoring()
    {
        $events = Event::all();
        $monitorings = Monitoring::orderBy('created_at', 'desc')->get();
        return view('monitoring')->with(compact('events', 'monitorings'));
    }

    public function progress_monitoring(Request $request, Monitoring $monitoring): View
    {   
        if(Session::has('monitoring_progress')){
            $events = Event::find(session()->get('event_id'));
            $monitorings = Monitoring::where('event_id', session()->get('event_id'))->where('option', session()->get('option'))->where('default', '!=', 'system')->orderBy('created_at', 'desc')->get();
            return view('monitoring-page')->with(compact('events', 'monitorings'));
        }
        else{
            abort(403);
            return redirect()->route('events');
        }
    }
}