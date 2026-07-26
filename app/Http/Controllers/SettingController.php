<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();

        if (!$setting) {

            $setting = Setting::create([
                'app_name'        => 'Event Receipt',
                'company_name'    => 'BINUS University',
                'app_url'         => config('app.url'),
                'timezone'        => 'Asia/Jakarta',
                'qr_size'         => 250,
                'enable_webcam'   => true,
                'auto_capture'    => false,
                'capture_delay'   => 2,
            ]);

        }

        return view('settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name'       => 'required|string|max:255',
            'company_name'   => 'nullable|string|max:255',
            'app_url'        => 'required|url',
            'timezone'       => 'required|string',
            'qr_size'        => 'required|integer|min:100|max:1000',
            'capture_delay'  => 'required|integer|min:1|max:10',
        ]);

        $setting = Setting::first();

        $setting->update([
            'app_name'        => $request->app_name,
            'company_name'    => $request->company_name,
            'app_url'         => $request->app_url,
            'timezone'        => $request->timezone,
            'qr_size'         => $request->qr_size,
            'enable_webcam'   => $request->has('enable_webcam'),
            'auto_capture'    => $request->has('auto_capture'),
            'capture_delay'   => $request->capture_delay,
        ]);

        return redirect()
            ->route('settings.index')
            ->with('success', 'Settings berhasil disimpan.');
    }
}
