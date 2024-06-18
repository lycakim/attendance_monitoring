<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Event;
use Illuminate\View\View;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function show_events(Request $request): View
    {
        $events = Event::all();
        return view('events', \compact('events'));
    }

    public function show_all()
    {
        $events = Event::all();

        foreach($events as $calendar_event) {
            $ev = [];
            $ev["title"] = $calendar_event->title;
            
            $ev['description'] = $calendar_event->description;
            
            $ev["color"] = $calendar_event->calendar->color ?? "hsl(".rand(0,359).",100%,50%)";

            $ev["allDay"] = true;

            $ev["start"]  = Carbon::parse($calendar_event->event_date)->format("Y-m-d H:i:s");

            $results[] = $ev;
        }
        
        return response($results);
    }

    public function store(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required|unique:events',
            'settings' => 'required',
            'consequence' => 'required',
            'event_date' => 'required',
        ]);
            
        $request->merge([
            'description' => $request->description ?? null,
            'morning_login_start' => $request->morning_login_start ?? null,
            'morning_login_finish' => $request->morning_login_finish ?? null,
            'morning_logout_start' => $request->morning_logout_start ?? null,
            'morning_logout_finish' => $request->morning_logout_finish ?? null,
            'afternoon_login_start' => $request->afternoon_login_start ?? null,
            'afternoon_login_finish' => $request->afternoon_login_finish ?? null,
            'afternoon_logout_start' => $request->afternoon_logout_start ?? null,
            'afternoon_logout_finish' => $request->afternoon_logout_finish ?? null,
        ]);
        
        $event->create($request->all());

        return response('ok', 200);
    }

    public function get_event(Event $event)
    {
        return $event;
    }

    public function update_event(Request $request, $id)
    {   
        $request->validate([
            'title' => 'required',
            'settings' => 'required',
            'consequence' => 'required',
            'event_date' => 'required',
        ]);
        
        $request->merge([
            'description' => $request->description ?? null,
            'morning_login_start' => $request->morning_login_start ?? null,
            'morning_login_finish' => $request->morning_login_finish ?? null,
            'morning_logout_start' => $request->morning_logout_start ?? null,
            'morning_logout_finish' => $request->morning_logout_finish ?? null,
            'afternoon_login_start' => $request->afternoon_login_start ?? null,
            'afternoon_login_finish' => $request->afternoon_login_finish ?? null,
            'afternoon_logout_start' => $request->afternoon_logout_start ?? null,
            'afternoon_logout_finish' => $request->afternoon_logout_finish ?? null,
        ]);
        
        $events = Event::find($id);
        
        $events->update($request->all());

        if(session()->has('event_name')){
            session()->put('event_name', $request->title);  
        }
        
        if(session()->has('option') && session()->get('option') == 'Login'){
            session()->put('time_in', $events->morning_login_start);
            session()->put('time_out', $events->morning_login_finish);
        }
        else if(session()->has('option') && session()->get('option') == 'Logout'){
            session()->put('time_in', $events->morning_logout_start);
            session()->put('time_out', $events->morning_logout_finish);
        }
        else if(session()->has('option') && session()->get('option') == 'Login Afternoon'){
            session()->put('time_in', $events->afternoon_login_start);
            session()->put('time_out', $events->afternoon_login_finish);
        }
        else if(session()->has('option') && session()->get('option') == 'Logout Afternoon'){
            session()->put('time_in', $events->afternoon_logout_start);
            session()->put('time_out', $events->afternoon_logout_finish);
        }

        return response('ok', 200); 
    }   

    public function destroy_event(Event $event)
    {
        $event->delete();

        return response('ok', 200);
    }
}