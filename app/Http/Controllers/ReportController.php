<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Student;
use App\Models\Monitoring;
use Illuminate\Http\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Database\Eloquent\Builder;

class ReportController extends Controller
{
    public function generateReport(Request $request)
    {
        $request->validate([
            'event_id' => 'required',
        ]); 

        $program_data = $request->program ?? null;
        $year_data = $request->year ?? null;
        $set_data = $request->set ?? null;

        $report = Monitoring::query()
            ->when($request->event_id, function ($q, $data) {
                $q->where('event_id', $data);
            })
            ->with('students')
            ->with('events')
            ->whereHas('students', function (Builder $query) use ($program_data, $year_data, $set_data) {
                $query->when($program_data != 'All', function($query) use ($program_data) {
                    $query->where('program','LIKE', '%' . $program_data . '%');
                });
                $query->when($year_data != 'All', function($query) use ($year_data) {
                    $query->where('year','LIKE', '%' . $year_data . '%');
                });
                $query->when($set_data != 'All', function($query) use ($set_data) {
                    $query->where('set','LIKE', '%' . $set_data . '%');
                });
            })
            ->where('default', 'student')
            ->get();

        session()->put('reports', $report);
        
        return response()->json($report);
    }

    public function index()
    {
        $events = Event::all();
        $set_list = Student::select('set')->distinct()->orderBy('set', 'asc')->get();
        $monitoring_reports = Monitoring::where('default', 'student')->with('students')->with('events')->orderBy('created_at', 'desc')->get();

        $cnt_late = 0;
        $totalHrs = 0;
        $conse = 0;
        if(count($monitoring_reports) > 0){
            foreach($monitoring_reports as $record){
                $conse = $record->events->consequence;
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
                else {
                    if($record->option == 'Login' && $record->remarks == 'late'){
                        $cnt_late = $cnt_late + 1;
                    }
                    else if($record->option == 'Logout' && $record->remarks == 'late'){
                        $cnt_late = $cnt_late + 1;
                    }
                    
                }
                $record['consequence'] = $cnt_late !== 0 ? ($cnt_late * $conse) :  $cnt_late;
                $cnt_late = 0;
                logger($conse . ' - '. $record->events->id . ' - ' . $cnt_late . ' - '. $record['consequence']);
            }
            
        }
        session()->put('reports', $monitoring_reports);
        return view('reports')->with(compact('set_list','events','monitoring_reports'));
    }

    public function export()
    {
        $fileName = 'reports.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Student ID Number', 'Student Name', 'Program', 'Year', 'Set', 'Event', 'Consequence', 'Date'];

            $callback = function() use($columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach (session()->get('reports') as $data) {
                    fputcsv($file, [
                        $data->students->id_number,
                        $data->students->name,
                        $data->students->program,
                        $data->students->year,
                        $data->students->set,
                        $data->events->title,
                        $this->getStudentInfo($data->students->id, $data->events->id),
                        $data->created_at
                    ]);
                }

                fclose($file);
            };

        return response()->stream($callback, 200, $headers);
    }

    public function getStudentInfo($student_id, $event_id)
    {
        $monitorings = Monitoring::where('student_id', $student_id)->where('event_id', $event_id)->with('events')->groupBy('option')->get();
        $cnt_late = 0;
        $cnt_absent = 0;
        $totalHrs = 0;
        $conse = 0;
        if(count($monitorings) > 0){
            foreach($monitorings as $record){
                $conse = $record->events->consequence;
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
        $totalHrs = $cnt_late !== 0 ? ($cnt_late * $conse) : ($cnt_absent * $conse);
        logger($totalHrs);
        logger($monitorings);
        return $totalHrs;
    }

    public function info_student(Student $student, Event $events)
    {
        // $events = Event::all();

        $mon = [];
        $totalHrs = 0;
        $i = 0;
        $total = 0;
        foreach($events as $eve){
            $monit = Monitoring::where('student_id', $student->id)->where('event_id', $eve->id)->with('events')->groupBy('option')->get();
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
            $totalHrs = $cnt_late !== 0 ? ($cnt_late * $eve->consequence) : ($cnt_absent * $eve->consequence);
            $total = $total + $mon[$i]['consequence'];
            logger($eve->title);
            logger('late:'.$cnt_late);
            logger('absent:'.$cnt_absent);
            $i++;
        }
        $mon['total'] = $total;
        return $totalHrs;
    }
}