<?php

namespace Database\Factories;

use App\Models\Kelas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Siswa>
 */
class SiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kode_kelas = Kelas::pluck('kode_kelas')->toArray();
        return [
            'nama' => fake()->name(),
            'nisn' => fake()->unique()->numerify('##########'),
            'nis' => fake()->unique()->numerify('####'),
            'tempat_lahir' => fake()->city(),
            'tanggal_lahir' => fake()->date(),
            'jenis_kelamin' => fake()->randomElement(['L','P']),
            'alamat' => fake()->address(),
            'no_telepon' => fake()->phoneNumber(),
            'kode_kelas' => fake()->randomElement($kode_kelas),
        ];
    }
}
