<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function riwayat(Request $request)
    {
        $nis = $request->nis;
        $siswa = Siswa::where('nis', $nis)->first();
        return view('riwayat', compact('nis', 'siswa',));
    }
}
