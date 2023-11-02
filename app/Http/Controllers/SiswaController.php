<?php

namespace App\Http\Controllers;

use App\Models\GuruBK;
use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index()
    {
        return view('siswa');
    }


    public function result(Request $request)
    {
        $nis = $request->nis;
        $siswa = Siswa::where('nis', $nis)->first();
        $guruBK = GuruBK::all();
        return view('result', compact('nis', 'siswa', 'guruBK'));
    }
}
