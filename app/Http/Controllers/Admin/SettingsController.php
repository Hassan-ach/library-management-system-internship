<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;


class SettingsController extends Controller
{
    public function edit()
    {
        $settings = Settings::firstOrCreate([]);
        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'max_books_per_user' => 'required|integer|min:1',
            'max_loan_duration' => 'required|integer|min:1',
            'reservation_duration' => 'required|integer|min:1'
        ]);

        Settings::firstOrCreate([])->update($validated);

        return redirect()->route('admin.settings.edit')
            ->with('success', 'Settings updated successfully!');
    }
}
