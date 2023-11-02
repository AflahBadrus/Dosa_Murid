<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Kelas::create([
            "kode_kelas" => "RPL231",
            "nama_kelas" => "X RPL 1",
            "kode_jurusan" => "RPL",
        ]);
        Kelas::create([
            "kode_kelas" => "RPL232",
            "nama_kelas" => "X RPL 2",
            "kode_jurusan" => "RPL",
        ]);
        Kelas::create([
            "kode_kelas" => "OTKP221",
            "nama_kelas" => "XI OTKP 1",
            "kode_jurusan" => "OTKP",
        ]);
    }
}
