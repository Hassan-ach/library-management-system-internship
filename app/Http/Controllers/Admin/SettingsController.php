<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function edit()
    {
        $settings = Setting::getSettings();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'DUREE_EMPRUNT_MAX' => 'required|integer|min:1',
            'NOMBRE_EMPRUNTS_MAX' => 'required|integer|min:1',
            'DUREE_RESERVATION' => 'required|integer|min:1'
        ]);

        Setting::getSettings()->update($validated);

        return back()->with('success', 'Paramètres mis à jour');
    }
}