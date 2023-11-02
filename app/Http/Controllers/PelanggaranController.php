<?php

namespace App\Http\Controllers;

use App\Models\Aksi;
use App\Models\ListPelanggaran;
use App\Models\Pelanggaran;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

class PelanggaranController extends Controller
{
    public function pelanggaran($aksi)
    {
        $kode_aksi = $aksi;
        $aksi = Aksi::where('kode_aksi', $aksi)
            ->with('siswa.kelas.jurusan', 'guruBK', 'listPelanggaran.pelanggaran')->first();
        $siswa = $aksi->siswa ?? null;
        $pelanggaranAll = Pelanggaran::all();
        return view('pelanggaran', compact('aksi', 'siswa', 'kode_aksi', 'pelanggaranAll'));
    }

    public function print(Request $request)
    {
        $request->validate([
            'kode_aksi' => 'required'
        ]);
        $kode_aksi = $request->kode_aksi;
        $aksi = Aksi::where('kode_aksi', $kode_aksi)
            ->with('siswa.kelas.jurusan', 'guruBK', 'listPelanggaran.pelanggaran')->first();
        $siswa = $aksi->siswa ?? null;

        if ($siswa == null) {
            return redirect()->back();
        }
        $connector = new WindowsPrintConnector("EPSON TM-T82 Receipt");
        $printer = new Printer($connector);

        // print logo
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $logo = public_path('img/Logo Aflah Badrutstsani.png');
        $logo = EscposImage::load($logo);
        $printer->graphics($logo);
        $printer->feed();
        $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH | Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_EMPHASIZED);
        $printer->text("Dosa Murid\n");
        $printer->selectPrintMode(Printer::MODE_EMPHASIZED);
        $printer->text("Sistem Pencatatan Pelanggaran Siswa\n");
        $printer->text("SMK Negeri 1 Bangsri");
        $printer->feed();

        // print data siswa
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->selectPrintMode();
        $printer->text(" Nama Siswa :" . $siswa->nama . "\n");
        $printer->text(" NISN :" . $siswa->nisn . "\n");
        $printer->text(" NIS : " . $siswa->nis . "\n");
        $printer->text(" Kelas :" . $siswa->kelas->nama_kelas . "\n");
        $printer->text(" Jurusan :" . $siswa->jurusan . "\n");
        $printer->text(" Alamat :" . $siswa->alamat . "\n");
        $printer->text(" Tanggal :" . $aksi->tanggal . "\n");
        $printer->text(" Waktu :" . $aksi->waktu . "\n");
        $printer->text(" GuruBK :" . $aksi->guruBK->nama . "\n");
        $printer->feed();

        // print list_pelanggaran
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text('***************************');
        $printer->selectPrintMode(Printer::MODE_EMPHASIZED);
        $printer->text('List Pelanggaran');
        $printer->selectPrintMode();
        $printer->text('***************************');
        $printer->feed();
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        foreach ($aksi->listPelanggaran as $list) {
            $printer->text("#" . $list->pelanggaran->nama_pelanggaran . "\n");
            $printer->text("#" . $list->keterangan . "\n");
            $printer->feed();
        }
        $printer->text('***************************');
        $printer->feed();
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("Sebaik-baik Manusia adalah yang tidak mengulangi dosanya kembali. dan mengajak temannya kepada kebaikan\n");
        $printer->feed();

        // print qr kode aksi
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->qrCode($kode_aksi, Printer::QR_ECLEVEL_L, 10);
        $printer->text('kode_aksi :' . "$kode_aksi . \n");
        $printer->feed(2);
        $printer->cut();
        $printer->close();

        return redirect()->back();
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
