<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $campusName = Setting::get('campus_name', 'Campus Vitória da Conquista');
        return view('admin.settings.index', compact('campusName'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'campus_name' => 'required|string|max:255',
        ]);

        Setting::set('campus_name', $request->campus_name);

        return back()->with('success', 'Configurações atualizadas com sucesso!');
    }
}
