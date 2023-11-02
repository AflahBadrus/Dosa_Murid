<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_kelas',
        'nama_kelas',
        'kode_jurusan',
    ];

    public function Jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'kode_jurusan', 'kode_jurusan');
    }
    

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'kode_kelas', 'kode_kelas');
    }
}
