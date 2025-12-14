<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Periode;
use App\Models\UnitKerja;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting Database Seeding...');

        // ========================================
        // 1. CREATE PERIODE
        // ========================================
        $this->command->info('📅 Creating Periode...');

        $periode = Periode::create([
            'tahun_mulai' => 2025,
            'tahun_selesai' => 2029,
            'status' => 'active',
        ]);

        $this->command->info('✓ Periode created: 2025-2029');

        // ========================================
        // 2. CREATE UNIT KERJA HIERARCHY
        // ========================================
        $this->command->info('🏢 Creating Unit Kerja...');

        // Sekretariat Utama (Root)
        $sekretariatUtama = UnitKerja::create([
            'kode' => 'SU',
            'nama' => 'Sekretariat Utama',
            'parent_id' => null,
            'level' => 'pusat',
        ]);

        // Deputi I
        $deputi1 = UnitKerja::create([
            'kode' => 'D1',
            'nama' => 'Deputi Bidang Identifikasi dan Deteksi',
            'parent_id' => $sekretariatUtama->id,
            'level' => 'deputi',
        ]);

        // Deputi II
        $deputi2 = UnitKerja::create([
            'kode' => 'D2',
            'nama' => 'Deputi Bidang Proteksi',
            'parent_id' => $sekretariatUtama->id,
            'level' => 'deputi',
        ]);

        // Deputi III
        $deputi3 = UnitKerja::create([
            'kode' => 'D3',
            'nama' => 'Deputi Bidang Penindakan dan Pemulihan',
            'parent_id' => $sekretariatUtama->id,
            'level' => 'deputi',
        ]);

        // Deputi IV
        $deputi4 = UnitKerja::create([
            'kode' => 'D4',
            'nama' => 'Deputi Bidang Keamanan Siber dan Sandi',
            'parent_id' => $sekretariatUtama->id,
            'level' => 'deputi',
        ]);

        // Subdeputi under Deputi III
        UnitKerja::create([
            'kode' => 'D31',
            'nama' => 'Subdeputi Penindakan Keamanan Siber Sektor Pemerintahan',
            'parent_id' => $deputi3->id,
            'level' => 'subdeputi',
        ]);

        UnitKerja::create([
            'kode' => 'D32',
            'nama' => 'Subdeputi Penindakan Keamanan Siber Sektor Strategis',
            'parent_id' => $deputi3->id,
            'level' => 'subdeputi',
        ]);

        UnitKerja::create([
            'kode' => 'D33',
            'nama' => 'Subdeputi Pemulihan Keamanan Siber',
            'parent_id' => $deputi3->id,
            'level' => 'subdeputi',
        ]);

        // Pusat-Pusat
        UnitKerja::create([
            'kode' => 'P1',
            'nama' => 'Pusat Operasi Keamanan Siber Nasional',
            'parent_id' => $sekretariatUtama->id,
            'level' => 'pusat',
        ]);

        UnitKerja::create([
            'kode' => 'P2',
            'nama' => 'Pusat Data dan Informasi',
            'parent_id' => $sekretariatUtama->id,
            'level' => 'pusat',
        ]);

        UnitKerja::create([
            'kode' => 'P3',
            'nama' => 'Pusat Riset dan Pengembangan',
            'parent_id' => $sekretariatUtama->id,
            'level' => 'pusat',
        ]);

        $this->command->info('✓ Unit Kerja created: 11 units');

        // ========================================
        // 3. CREATE USERS
        // ========================================
        $this->command->info('👥 Creating Users...');

        // Super Admin
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@bssn.go.id',
            'password' => Hash::make('password123'),
            'role' => 'admin_eperforma',
        ]);

        // PKO Users
        $pko1 = User::create([
            'name' => 'PKO Deputi III',
            'email' => 'pko.d3@bssn.go.id',
            'password' => Hash::make('password123'),
            'role' => 'pko',
        ]);

        $pko2 = User::create([
            'name' => 'PKO Subdeputi III.1',
            'email' => 'pko.d31@bssn.go.id',
            'password' => Hash::make('password123'),
            'role' => 'pko',
        ]);

        // PMK Users
        $pmk1 = User::create([
            'name' => 'PMK Subdeputi III.1',
            'email' => 'pmk.d31@bssn.go.id',
            'password' => Hash::make('password123'),
            'role' => 'pmk',
        ]);

        $pmk2 = User::create([
            'name' => 'PMK Subdeputi III.2',
            'email' => 'pmk.d32@bssn.go.id',
            'password' => Hash::make('password123'),
            'role' => 'pmk',
        ]);

        $pmk3 = User::create([
            'name' => 'PMK Subdeputi III.3',
            'email' => 'pmk.d33@bssn.go.id',
            'password' => Hash::make('password123'),
            'role' => 'pmk',
        ]);

        $this->command->info('✓ Users created: 6 users');

        // ========================================
        // SUMMARY
        // ========================================
        $this->command->info('');
        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('===========================================');
        $this->command->info('📊 SUMMARY:');
        $this->command->info('- Periode: 1 (2025-2029)');
        $this->command->info('- Unit Kerja: 11');
        $this->command->info('- Users: 6');
        $this->command->info('===========================================');
        $this->command->info('🔑 DEFAULT CREDENTIALS:');
        $this->command->info('');
        $this->command->info('Admin ePerforma:');
        $this->command->info('  Email: admin@bssn.go.id');
        $this->command->info('  Password: password123');
        $this->command->info('');
        $this->command->info('PKO:');
        $this->command->info('  Email: pko.d3@bssn.go.id / pko.d31@bssn.go.id');
        $this->command->info('  Password: password123');
        $this->command->info('');
        $this->command->info('PMK:');
        $this->command->info('  Email: pmk.d31@bssn.go.id / pmk.d32@bssn.go.id / pmk.d33@bssn.go.id');
        $this->command->info('  Password: password123');
        $this->command->info('===========================================');
    }
}
