<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function switchSchool(Request $request)
    {
        $data = $request->validate([
            'school_id' => ['required', 'exists:schools,id'],
        ]);

        session(['active_school_id' => (int) $data['school_id']]);

        return redirect()->route('dashboard')->with('success', 'Sekolah aktif diubah.');
    }

    public function clearSchool(Request $request)
    {
        session()->forget('active_school_id');

        return redirect()->route('dashboard')->with('success', 'Kembali ke tampilan semua sekolah.');
    }
}
