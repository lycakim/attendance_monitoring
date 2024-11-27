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
        $monitoring_reports = Monitoring::where('default', 'student')->orderBy('created_at', 'desc')->get();
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

        $columns = ['Student ID Number', 'Student Name', 'Program', 'Year', 'Set', 'Event', 'Date'];

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
                        $data->created_at
                    ]);
                }

                fclose($file);
            };

        return response()->stream($callback, 200, $headers);
    }
}