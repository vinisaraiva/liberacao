<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StudentService;

class StudentController extends Controller
{
    protected $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    public function search(Request $request)
    {
        $term = $request->input('q');
        if (!$term) {
            return response()->json([]);
        }

        $students = $this->studentService->search($term);
        return response()->json($students);
    }
}
