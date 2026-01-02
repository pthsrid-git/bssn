<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Logbook;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AbkFormBSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    protected $year;

    public function __construct($year)
    {
        $this->year = $year;
    }

    /**
     * Return sheet title
     */
    public function title(): string
    {
        return 'Form B';
    }

    /**
     * Generate data array for the sheet
     * Form B: Rekapitulasi jumlah beban kerja jabatan
     */
    public function array(): array
    {
        $data = [];

        // Header - Row 1-7 (18 kolom A-R, tanpa kolom B)
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '']; // Row 1
        $data[] = ['', '', '', '', '', '', '', 'REKAPITULASI JUMLAH BEBAN KERJA JABATAN BERDASARKAN PRODUK', '', '', '', '', '', '', '', '', '', '']; // Row 2
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '']; // Row 3

        // Info section
        $data[] = ['UNIT ORGANISASI', 'Direktorat Operasi Keamanan Siber', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '']; // Row 4
        $data[] = ['SATUAN ORGANISASI', 'D2', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '']; // Row 5
        $data[] = ['LOKASI', 'Jakarta Selatan', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '']; // Row 6
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '']; // Row 7

        // Fixed jabatan list as specified
        $jabatanList = [
            'Kepala BSSN',
            'Deputi Bidang Operasi Keamanan Siber dan Sandi',
            'Direktur Operasi Keamanan Siber',
            'Sandiman Ahli Utama',
            'Sandiman Ahli Madya',
            'Sandiman Ahli Muda',
            'Sandiman Ahli Pertama',
            'Manggala Informatika Ahli Utama',
            'Manggala Informatika Ahli Madya',
            'Manggala Informatika Ahli Muda',
            'Manggala Informatika Ahli Pertama',
            'Pemeriksa Forensik Digital',
            'Penata Kelola Sistem dan Teknologi informasi',
            'Penelaah Teknis Kebijakan',
            'Pengolah Data dan Informasi'
        ];

        // Build column headers with fixed jabatan
        // Row 8: Main header row (tanpa kolom B)
        $headerRow1 = ['No.', 'Nama Produk', 'Jumlah Beban Kerja Jabatan (orang-menit) (OM)'];

        // Add empty cells for 14 more jabatan columns (total 15 jabatan in C-Q)
        for ($i = 0; $i < 14; $i++) {
            $headerRow1[] = '';
        }

        // Add Jumlah header
        $headerRow1[] = 'Jumlah';

        // Row 9: Jabatan names (tanpa kolom B)
        $headerRow2 = ['', ''];

        // Add all 15 jabatan columns (C through Q)
        foreach ($jabatanList as $jabatan) {
            $headerRow2[] = $jabatan;
        }

        // Add empty cell for Jumlah column
        $headerRow2[] = '';

        $data[] = $headerRow1; // Row 8
        $data[] = $headerRow2; // Row 9

        // Get all unique rencana SKP (Nama Produk)
        $rencanaSkpList = Logbook::whereYear('tanggal', $this->year)
            ->where('status', 'Disetujui')
            ->select('rencana_hasil_kinerja_skp')
            ->distinct()
            ->orderBy('rencana_hasil_kinerja_skp')
            ->pluck('rencana_hasil_kinerja_skp');

        // Data rows (starting from row 10)
        $rowNum = 1;
        $dataStartRow = count($data) + 1;
        foreach ($rencanaSkpList as $rencanaSkp) {
            $currentRow = count($data) + 1;

            $row = [$rowNum, $rencanaSkp];

            // Add SUMIF formula for each jabatan (columns C through Q - 15 jabatan)
            // Formula: =SUMIF('Form A2'!$O:$O, "jabatan", 'Form A2'!$N:$N)
            foreach ($jabatanList as $jabatan) {
                $row[] = "=SUMIF('Form A2'!\$O:\$O,\"" . $jabatan . "\",'Form A2'!\$N:\$N)";
            }

            // Add Jumlah column (column R) with formula =SUM(C{row}:Q{row})
            $row[] = "=SUM(C{$currentRow}:Q{$currentRow})";

            $data[] = $row;
            $rowNum++;
        }

        $dataEndRow = count($data);

        // Add spacing before summary
        $data[] = [];
        $data[] = [];

        // Final summary rows - add formulas for each column C-R
        $menitRow = count($data) + 1;
        $jamRow = count($data) + 2;

        // Build Menit row with SUM formulas for each column C-R
        $menitRowData = ['', 'Jumlah Beban Kerja Jabatan (Menit)'];
        // Columns C-Q (15 jabatan) + column R (Jumlah)
        $columns = ['C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R'];
        foreach ($columns as $col) {
            $menitRowData[] = "=SUM({$col}{$dataStartRow}:{$col}{$dataEndRow})";
        }
        $data[] = $menitRowData;

        // Build Jam row with division formulas for each column C-R
        $jamRowData = ['', 'Jumlah Beban Kerja Jabatan (Jam)'];
        foreach ($columns as $col) {
            $jamRowData[] = "={$col}{$menitRow}/60";
        }
        $data[] = $jamRowData;

        return $data;
    }

    /**
     * Column widths
     */
    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 35,  // Nama Produk
            'C' => 12,  // Kepala BSSN
            'D' => 12,  // Deputi Bidang Operasi Keamanan Siber dan Sandi
            'E' => 12,  // Direktur Operasi Keamanan Siber
            'F' => 12,  // Sandiman Ahli Utama
            'G' => 12,  // Sandiman Ahli Madya
            'H' => 12,  // Sandiman Ahli Muda
            'I' => 12,  // Sandiman Ahli Pertama
            'J' => 12,  // Manggala Informatika Ahli Utama
            'K' => 12,  // Manggala Informatika Ahli Madya
            'L' => 12,  // Manggala Informatika Ahli Muda
            'M' => 12,  // Manggala Informatika Ahli Pertama
            'N' => 12,  // Pemeriksa Forensik Digital
            'O' => 12,  // Penata Kelola Sistem dan Teknologi informasi
            'P' => 12,  // Penelaah Teknis Kebijakan
            'Q' => 12,  // Pengolah Data dan Informasi
            'R' => 12,  // Jumlah
        ];
    }

    /**
     * Apply styles
     */
    public function styles(Worksheet $sheet)
    {
        // Title styling (row 2)
        $sheet->mergeCells('H2:P2');
        $sheet->getStyle('H2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        // Formulir B marker di kolom R (kolom Jumlah) row 7 - satu baris sebelum header
        $sheet->setCellValue('R7', 'Formulir B');
        $sheet->getStyle('R7')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFF00']
            ],
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
        ]);

        // Info section styling (kolom A untuk label, kolom B untuk value)
        $sheet->getStyle('A4')->applyFromArray([
            'font' => ['bold' => true]
        ]);
        $sheet->getStyle('A5')->applyFromArray([
            'font' => ['bold' => true]
        ]);
        $sheet->getStyle('A6')->applyFromArray([
            'font' => ['bold' => true]
        ]);

        // Table header styling
        $lastRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        // Style header rows (8-9)
        $sheet->getStyle('A8:' . $highestColumn . '9')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D9D9D9']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);

        // Merge header cells
        $sheet->mergeCells('A8:A9');  // No
        $sheet->mergeCells('B8:B9');  // Nama Produk
        $sheet->mergeCells('C8:Q8');  // Jumlah Beban Kerja Jabatan (15 jabatan)
        $sheet->mergeCells('R8:R9');  // Jumlah

        // Data rows styling (from row 10)
        $dataStartRow = 10;
        $dataEndRow = $lastRow - 2; // Exclude summary rows
        $sheet->getStyle('A' . $dataStartRow . ':' . $highestColumn . $dataEndRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);

        // Center align
        $sheet->getStyle('A' . $dataStartRow . ':A' . $dataEndRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C' . $dataStartRow . ':' . $highestColumn . $dataEndRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Wrap text for column B (Nama Produk)
        $sheet->getStyle('B' . $dataStartRow . ':B' . $dataEndRow)->getAlignment()->setWrapText(true);

        // Style summary rows
        $sheet->getStyle('A' . ($lastRow - 1) . ':' . $highestColumn . $lastRow)->applyFromArray([
            'font' => ['bold' => true],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);

        return [];
    }
}
