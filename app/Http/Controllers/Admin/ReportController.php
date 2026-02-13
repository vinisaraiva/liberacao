<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReleaseRequest;
use App\Models\Student;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function releases(Request $request)
    {
        $query = ReleaseRequest::with(['student', 'confirmedTerminal']);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $releases = $query->orderBy('created_at', 'desc')->paginate(30)->withQueryString();

        return view('admin.reports.releases', compact('releases'));
    }

    public function student(Request $request)
    {
        $student = null;
        $releases = collect();

        if ($request->filled('matricula')) {
            $student = Student::where('matricula', $request->matricula)->first();
            if ($student) {
                $releases = ReleaseRequest::where('student_id', $student->id)
                    ->orderBy('created_at', 'desc')
                    ->paginate(30)
                    ->withQueryString();
            }
        }

        return view('admin.reports.student', compact('student', 'releases'));
    }
}
