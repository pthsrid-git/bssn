<?php

namespace App\Exports;

use App\Models\Logbook;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AbkReportExport implements WithMultipleSheets
{
    protected $year;

    public function __construct($year)
    {
        $this->year = $year;
    }

    /**
     * Return an array of sheets
     * Sheet 1: Form A1 - berisi semua tabel untuk setiap rencana SKP
     * Sheet 2: Form A2 - perhitungan rata-rata dengan struktur berbeda
     * Sheet 3: Form B - rekapitulasi jumlah beban kerja jabatan
     * Sheet 4: Form C - perhitungan kebutuhan pejabat/pegawai
     * Sheet 5: Form D - rekapitulasi kebutuhan pejabat tingkat efektivitas unit
     */
    public function sheets(): array
    {
        $sheets = [];

        // Sheet 1: Form A1 yang berisi semua SKP
        $sheets[] = new AbkFormA1Sheet($this->year);

        // Sheet 2: Form A2 untuk perhitungan rata-rata
        $sheets[] = new AbkFormA2Sheet($this->year);

        // Sheet 3: Form B untuk rekapitulasi beban kerja jabatan
        $sheets[] = new AbkFormBSheet($this->year);

        // Sheet 4: Form C untuk perhitungan kebutuhan pejabat/pegawai
        $sheets[] = new AbkFormCSheet($this->year);

        // Sheet 5: Form D untuk rekapitulasi kebutuhan pejabat tingkat efektivitas unit
        $sheets[] = new AbkFormDSheet($this->year);

        return $sheets;
    }
}
