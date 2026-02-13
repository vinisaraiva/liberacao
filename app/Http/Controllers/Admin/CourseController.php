<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return view('admin.courses.index', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|unique:courses,nome',
        ]);

        Course::create($validated);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Curso criado com sucesso.');
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'nome' => 'required|string|unique:courses,nome,' . $course->id,
        ]);

        $course->update($validated);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Curso atualizado com sucesso.');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.courses.index')
            ->with('success', 'Curso removido com sucesso. O histórico foi preservado.');
    }
}
