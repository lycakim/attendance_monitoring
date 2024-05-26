<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\Student;
use App\Models\Monitoring;
use Illuminate\Http\Request;

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
        
        session()->put('event_id', $request->event_id);
        session()->put('event_name', $request->event_name);
        session()->put('option', $request->option);

        return response('ok', 200);
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
        
        session()->forget('event_id');
        session()->forget('event_name');
        session()->forget('option');
        
        return response('ok', 200);
    }

    public function student_store(Request $request, Monitoring $monitoring)
    {
        try {
            $request->validate([
                'id_number' => 'required'
            ]);

            $student = Student::where('id_number', $request->id_number)->first();

            if($student == null){
                return response('Not found', 404);
            }
            
            $monitoring->create([
                'default' => 'student',
                'student_id' => $student->id,
                'user_id' => auth()->user()->id,
                'event_id' => session()->get('event_id'),
                'option' => session()->get('option'),
            ]);
    
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
}