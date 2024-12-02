<?php

namespace App\Http\Controllers\api;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// /**
//  * @OA\Get(
//  *     path="/api/home",
//  *     summary="Home",
//  *     description="Home",
//  *     tags={"Home"},
//  *     @OA\Parameter(
//  *         name="name",
//  *         in="query",
//  *         required=true,
//  *         description="Provide your name"
//  *     ),
//  *     @OA\Response(
//  *         response="200",
//  *         description="Successful",
//  *         @OA\MediaType(
//  *             mediaType="application/json"
//  *         )
//  *     )
//  * )
//  */

// if there's changes in here don't forget to run php artisan l5-swagger:generate
class HomeController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'name' => $request->name,
            'message' => 'Attendance Monitoring System',
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/students",
     *     summary="List Students",
     *     description="Retrieve a list of all students",
     *     tags={"Students"},
     *     @OA\Response(
     *         response="200",
     *         description="Successful",
     *         @OA\MediaType(
     *             mediaType="application/json"
     *         )
     *     )
     * )
     */

    public function students(Request $request)
    {
        $students = Student::select('id', 'name', 'email', 'program', 'year', 'set')->get();
        return response()->json($students);
    }
    
    /**
     * @OA\Get(
     *     path="/api/students/list/{student}",
     *     summary="List Students",
     *     description="Retrieve one of all students",
     *     tags={"Students"},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=true,
     *         description="Provide student's name"
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successful",
     *         @OA\MediaType(
     *             mediaType="application/json"
     *         )
     *     )
     * )
     */

    public function getStudent(Request $request)
    {
        $student = Student::with('monitoring')->select('id', 'name', 'email', 'program', 'year', 'set')->where('name', 'LIKE', '%'.$request->name .'%')->get();
        return response()->json($student);
    }
}