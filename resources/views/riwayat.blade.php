<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-200">
    <div class="bg-white text-black py-10">
        <div class="container mx-auto text-center">
            <h3 class="font-bold">Dosa Murid | Riwayat Pelanggaran</h3>
        </div>
    </div>
    <form action="{{ route('pelanggaran.store.aksi') }}" method="POST">

        <div class="mt-4 bg-white rounded-xl p-4 container mx-auto">
            <div class="flex justify-center">
                <img src="{{ asset('img/helcurt1.jpg') }}" alt="Orang Tamvan" class="object-cover rounded-full">
    </form>
    </div>

    <div class="text-2xl font-semibold">NIS : {{ $siswa->nis }}
    </div>
    @if ($siswa == null)
        <div class="text-lg font-semibold mb-3">..:: SIswa Tidak Ditemukan ::..</div>
    @else
        <br>
        <div class="border p-5 rounded-xl border-slate-200">
            <div class="text-lg font-semibold mb-3">Nama : {{ $siswa->nama }}</div>
            <div class="text-lg font-semibold mb-3">NISN : {{ $siswa->nisn }}</div>
            <div class="text-lg font-semibold mb-3">Kelas : {{ $siswa->kelas->nama_kelas }}</div>
            <div class="text-lg font-semibold mb-3">Jurusan : {{ $siswa->kelas->jurusan->nama_jurusan }}</div>
            <div class="text-lg font-semibold mb-3">Total Poin Pelanggaran Yang yang Dimiliki Siswa ini
                Adalah : {{ $totalpoint }}</div>
    @endif

    </div>
    @foreach ($siswa->aksi as $listAksi)
        <div class="relative overflow-x-auto">
            <table class="w-full text-lg text-left text-gray-500 ">
                <thead class="text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Kode Pelanggaran
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Pelanggaran
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Keterangan
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($listAksi->listPelanggaran as $list)
                        <tr>
                            <th>{{ $list->pelanggaran->kode_pelanggaran }}</th>


                            <th>{{ $list->pelanggaran->nama_pelanggaran }}</th>


                            <th>{{ $list->keterangan }}</th>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
</body>

</html>
