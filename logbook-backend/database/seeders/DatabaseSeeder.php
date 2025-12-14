<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            // PKO (Staff)
            [
                'name' => 'Kemal',
                'fullname' => 'Moh. Kemal Hibatullah Ammar',
                'email' => 'pko@bssn.go.id',
                'fpid' => '1234',
                'nip' => '482716594203857194',
                'pangkat' => 'Penata Muda Tk. I (III/b)',
                'jabatan' => 'Pranata Komputer Pertama',
                'password' => Hash::make('password'),
                'role' => 'pko',
                'kode_unit_organisasi' => '38',
            ],

            // PMK (Katim)
            [
                'name' => 'Ahmad',
                'fullname' => 'Ahmad Kepala Tim',
                'email' => 'pmk@bssn.go.id',
                'fpid' => '1235',
                'nip' => '482716594203857195',
                'pangkat' => 'Penata (III/c)',
                'jabatan' => 'Kepala Tim',
                'password' => Hash::make('password'),
                'role' => 'pmk',
                'kode_unit_organisasi' => '38',
            ],

            // Ka-unit
            [
                'name' => 'Budi',
                'fullname' => 'Budi Kepala Unit',
                'email' => 'kaunit@bssn.go.id',
                'fpid' => '1236',
                'nip' => '482716594203857196',
                'pangkat' => 'Penata Tingkat I (III/d)',
                'jabatan' => 'Kepala Unit Kerja',
                'password' => Hash::make('password'),
                'role' => 'ka-unit',
                'kode_unit_organisasi' => '38',
            ],

            // Admin
            [
                'name' => 'Admin',
                'fullname' => 'Administrator Sistem',
                'email' => 'admin@bssn.go.id',
                'fpid' => '1237',
                'nip' => '482716594203857197',
                'pangkat' => 'Pembina (IV/a)',
                'jabatan' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'kode_unit_organisasi' => '38',
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }

        // Set hierarchy
        $pko = User::where('role', 'pko')->first();
        $katim = User::where('role', 'pmk')->first();
        $kaunit = User::where('role', 'ka-unit')->first();

        if ($pko && $katim) {
            $pko->update(['parent_id' => $katim->id]);
        }

        if ($katim && $kaunit) {
            $katim->update(['parent_id' => $kaunit->id]);
        }

        echo "✓ Created 4 test users (PKO, PMK, Ka-unit, Admin)\n";
        echo "✓ Set user hierarchy (PKO → PMK → Ka-unit)\n";
        echo "✓ All passwords: 'password'\n";
    }
}
