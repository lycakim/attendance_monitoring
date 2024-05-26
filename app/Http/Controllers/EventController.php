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
        $request->merge(['description' => $request->description ?? null]);
        
        $data = $request->validate([
            'title' => 'required|unique:events',
            'fines' => 'required',
            'event_date' => 'required',
            'login_start' => 'required',
            'login_finish' => 'required',
            'logout_start' => 'required',
            'logout_finish' => 'required'
        ]);

        $event->create($data);

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
            'fines' => 'required',
            'event_date' => 'required',
            'login_start' => 'required',
            'login_finish' => 'required',
            'logout_start' => 'required',
            'logout_finish' => 'required'
        ]);
        
        $events = Event::find($id);
        
        $events->update([
            'title' => $request->title,
            'description' => $request->description,
            'fines' => $request->fines,
            'event_date' => $request->event_date,
            'login_start' => $request->login_start,
            'login_finish' => $request->login_finish,
            'logout_start' => $request->logout_start,
            'logout_finish' => $request->logout_finish
        ]);

        return response('ok', 200);
    }

    public function destroy_event(Event $event)
    {
        $event->delete();

        return response('ok', 200);
    }
}