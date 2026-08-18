<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::pluck('value', 'key')->toArray();

        return view('admin.settings.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'site_name' => ['nullable', 'string'],
            'phone' => ['nullable', 'string'],
            'email' => ['nullable', 'email'],
            'address' => ['nullable', 'string'],
            'facebook' => ['nullable', 'url'],
            'instagram' => ['nullable', 'url'],
            'delivery_inside_dhaka' => ['nullable', 'numeric'],
            'delivery_outside_dhaka' => ['nullable', 'numeric'],
            'hero_title' => ['nullable', 'string'],
            'hero_subtitle' => ['nullable', 'string'],
            'hero_image' => ['nullable', 'string'],
        ]);

        foreach ($data as $key => $value) {
            if ($value !== null) {
                SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
            }
        }

        return redirect()->route('admin.settings.index')->with('success', 'Site settings updated successfully.');
    }
}
