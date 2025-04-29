<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    /**
     * Get current item detective mode setting.
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            $mode = Setting::getValue('item_detective_mode', 'simple');
        } catch (\Exception $e) {
            Log::error('Settings load error: ' . $e->getMessage());
            $mode = 'simple';
        }
        return response()->json(['item_detective_mode' => $mode]);
    }

    /**
     * Update item detective mode.
     */
    public function update(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'item_detective_mode' => 'required|in:simple,tf,phash',
        ]);
        $mode = $request->input('item_detective_mode');
        Setting::setValue('item_detective_mode', $mode);
        return response()->json(['item_detective_mode' => $mode]);
    }
}
