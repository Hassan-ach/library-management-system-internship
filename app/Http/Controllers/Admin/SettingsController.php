<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function edit()
    {
        try{
            $settings = Setting::getSettings();
            return view('admin.settings.index', compact('settings'));
        }catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur de charger les paramètres: '.$e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request)
    {
        try{
            $validated = $request->validate([
                'DUREE_EMPRUNT_MAX' => 'required|integer|min:1',
                'NOMBRE_EMPRUNTS_MAX' => 'required|integer|min:1',
                'DUREE_RESERVATION' => 'required|integer|min:1'
            ]);

            Setting::getSettings()->update($validated);

            return back()->with('success', 'Paramètres mis à jour');
        }catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur de charger les paramètres: '.$e->getMessage())
                ->withInput();
        }
    }
}