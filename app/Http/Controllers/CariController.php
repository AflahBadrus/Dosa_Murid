<?php

namespace App\Http\Controllers;

use App\Models\Aksi;
use App\Models\ListPelanggaran;
use App\Models\Pelanggaran;
use App\Models\Siswa;
use Illuminate\Http\Request;

class CariController extends Controller
{
    public function cari()
    {
        return view('cari');
    }

    public function riwayat(Request $request)
    {
        $nis = $request->nis;
        $siswa = Siswa::where('nis', $nis)->with('aksi.listPelanggaran.pelanggaran')->first();
        $totalpoint = $this->totalPoint($nis);
        return view('riwayat', compact('siswa', 'totalpoint'));
    }
    public function totalPoint(int $nis): int
    {
        $siswa = Siswa::where('nis', $nis)->with('aksi.listPelanggaran.pelanggaran')->first();
        $total = 0;

        if ($siswa == null) {
            return $total;
        }

        foreach ($siswa->aksi as $aksi) {
            foreach ($aksi->listPelanggaran as $list) {
                $total += $list->pelanggaran->poin;
            }
        }

        return $total;
    }

    public function pelanggaran($aksi)
    {
        $kode_aksi = $aksi;
        $aksi = Aksi::where('kode_aksi', $aksi)
            ->with('siswa.kelas.jurusan', 'guruBK', 'listPelanggaran.pelanggaran')->first();
        $siswa = $aksi->siswa ?? null;
        $pelanggaranAll = Pelanggaran::all();
        return view('pelanggaran', compact('aksi', 'siswa', 'kode_aksi', 'pelanggaranAll'));
    }


    public function storeAksi(Request $request)
    {
        $request->validate([
            'nis' => 'required',
            'tanggal' => 'required|date',
            'waktu' => 'required|date_format:H:i',
            'kode_bk' => 'required'
        ]);

        $kode_aksi = 'AKS' . fake()->unique()->numerify("###");

        $siswa = Siswa::where('nis', $request->nis)->first();
        Aksi::create([
            'nis_siswa' => $siswa->nis,
            'kode_aksi' => $kode_aksi,
            'tanggal' => $request->tanggal,
            'waktu' => $request->waktu,
            'kode_bk' => $request->kode_bk,
        ]);
        return redirect()->route('pelanggaran', $kode_aksi);
    }
    public function removeAksi($aksi, Request $request)
    {
        $request->validate([
            'id_list' => 'required',
        ]);

        $list = ListPelanggaran::find($request->id_list);
        if ($list->kode_aksi == $aksi) {
            $list->delete();
        }

        return redirect()->back();
    }

    public function addAksi($kode_aksi, Request $request)
    {
        $request->validate([
            'kode_pelanggaran' => 'required',
            'keterangan' => 'required',
        ]);

        $list = ListPelanggaran::create([
            'kode_aksi' => $kode_aksi,
            'kode_pelanggaran' => $request->kode_pelanggaran,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->back();
    }
}
