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

class AbkFormA2Sheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
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
        return 'Form A2';
    }

    /**
     * Generate data array for the sheet
     * Form A2: Perhitungan rata-rata dengan struktur berbeda dari Form A1
     */
    public function array(): array
    {
        $data = [];

        // Header - Row 1-3: Title (15 kolom untuk Form A2)
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        $data[] = ['', '', '', '', 'PENGUMPULAN DATA BEBAN KERJA NORMA WAKTU PRODUK', '', '', '', '', '', '', '', '', '', ''];
        $data[] = ['', '', '', '', 'BERDASARKAN NORMA PELAYANAN', '', '', '', '', '', '', '', '', '', ''];
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];

        // Info section (5 rows)
        $data[] = ['UNIT ORGANISASI', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        $data[] = ['SATUAN ORGANISASI', '', 'D2', '', '', '', '', '', '', '', '', '', '', '', ''];
        $data[] = ['LOKASI', '', 'Jakarta Selatan', '', '', '', '', '', '', '', '', '', '', '', ''];
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];

        // Table header (15 kolom A-O, Ket split menjadi 2 kolom L dan M)
        $data[] = [
            'No.',
            'Nama Produk',
            'Nama Jabatan Yang Melaksanakan',
            'Satuan Produk',
            'Norma Waktu',
            '',
            '',
            'Jumlah Volume Kerja',
            'Peralatan',
            '',
            '',
            'Ket.',
            '',
            'bobot',
            'Pelaksana seharusnya'
        ];
        $data[] = ['', '', '', '', 'Minimal', 'Maksimal', 'Rata-Rata', '', 'Manual', 'Semi Otomatis', 'Otomatis', '', '', '', ''];
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        $data[] = ['(1)', '(2)', '(3)', '(4)', '(5)', '(6)', '(7)', '(8)', '(9)', '(10)', '(11)', '(12)', '', '(13)', '(14)'];

        // Get all unique rencana SKP
        $rencanaSkpList = Logbook::whereYear('tanggal', $this->year)
            ->where('status', 'Disetujui')
            ->select('rencana_hasil_kinerja_skp')
            ->distinct()
            ->orderBy('rencana_hasil_kinerja_skp')
            ->pluck('rencana_hasil_kinerja_skp');

        $dataStartRow = count($data) + 1; // Track where data starts for TOTAL formula

        // Generate table data untuk setiap rencana SKP
        $skpNumber = 1;
        foreach ($rencanaSkpList as $rencanaSkp) {
            // Get logbook data untuk rencana SKP ini
            $logbooks = Logbook::where('rencana_hasil_kinerja_skp', $rencanaSkp)
                ->whereYear('tanggal', $this->year)
                ->where('status', 'Disetujui')
                ->with('user:id,fullname,name,jabatan')
                ->orderBy('tanggal')
                ->get();

            // Group by indikator
            $groupedData = [];
            foreach ($logbooks as $logbook) {
                $key = $logbook->indikator_hasil_rencana_kerja;

                if (!isset($groupedData[$key])) {
                    $groupedData[$key] = [
                        'jabatan' => $logbook->user->jabatan ?? ($logbook->user->fullname ?? $logbook->user->name),
                        'satuan' => $logbook->indikator_hasil_rencana_kerja,
                        'count' => 0,
                        'waktu_list' => [],
                        'pelaksana' => []
                    ];
                }

                $groupedData[$key]['count']++;

                // Calculate waktu in minutes
                $jamMulai = strtotime($logbook->jam_mulai);
                $jamSelesai = strtotime($logbook->jam_selesai);
                $waktuMenit = ($jamSelesai - $jamMulai) / 60;

                $groupedData[$key]['waktu_list'][] = $waktuMenit;

                // Collect unique pelaksana
                $pelaksanaName = $logbook->user->jabatan ?? ($logbook->user->fullname ?? $logbook->user->name);
                if (!in_array($pelaksanaName, $groupedData[$key]['pelaksana'])) {
                    $groupedData[$key]['pelaksana'][] = $pelaksanaName;
                }
            }

            // Add first row for this SKP (with SKP name in column B)
            $isFirstRow = true;
            foreach ($groupedData as $item) {
                $volumeKerja = $item['count'];
                $waktuList = $item['waktu_list'];

                $minimal = !empty($waktuList) ? min($waktuList) : 0;
                $maksimal = !empty($waktuList) ? max($waktuList) : 0;

                $manual = 'v';
                $semiOtomatis = '';
                $otomatis = '';
                $ketAngka = 1;
                $ketSatuan = 'orang';

                $pelaksanaSeharusnya = !empty($item['pelaksana']) ? implode(', ', $item['pelaksana']) : '';

                $currentRow = count($data) + 1;

                // First row untuk SKP ini memiliki nomor dan nama produk
                if ($isFirstRow) {
                    $data[] = [
                        $skpNumber, // No. (hanya di baris pertama untuk SKP ini)
                        $rencanaSkp, // Nama Produk (hanya di baris pertama)
                        $item['jabatan'], // Nama Jabatan
                        $item['satuan'], // Satuan Produk
                        round($minimal, 0), // Minimal
                        round($maksimal, 0), // Maksimal
                        '=ROUND((E' . $currentRow . '+F' . $currentRow . ')/2,0)', // Rata-Rata
                        $volumeKerja, // Jumlah Volume Kerja
                        $manual, // Manual
                        $semiOtomatis, // Semi Otomatis
                        $otomatis, // Otomatis
                        $ketAngka, // Ket. Angka (kolom L)
                        $ketSatuan, // Ket. Satuan (kolom M)
                        '=G' . $currentRow . '*H' . $currentRow . '*L' . $currentRow, // bobot (kolom N)
                        $pelaksanaSeharusnya // Pelaksana seharusnya (kolom O)
                    ];
                    $isFirstRow = false;
                } else {
                    // Baris selanjutnya untuk indikator lain di SKP yang sama (kolom A dan B kosong)
                    $data[] = [
                        '', // No. kosong
                        '', // Nama Produk kosong
                        $item['jabatan'], // Nama Jabatan
                        $item['satuan'], // Satuan Produk
                        round($minimal, 0), // Minimal
                        round($maksimal, 0), // Maksimal
                        '=ROUND((E' . $currentRow . '+F' . $currentRow . ')/2,0)', // Rata-Rata
                        $volumeKerja, // Jumlah Volume Kerja
                        $manual, // Manual
                        $semiOtomatis, // Semi Otomatis
                        $otomatis, // Otomatis
                        $ketAngka, // Ket. Angka (kolom L)
                        $ketSatuan, // Ket. Satuan (kolom M)
                        '=G' . $currentRow . '*H' . $currentRow . '*L' . $currentRow, // bobot (kolom N)
                        $pelaksanaSeharusnya // Pelaksana seharusnya (kolom O)
                    ];
                }
            }

            $skpNumber++;
        }

        // Add TOTAL row (15 kolom)
        $dataEndRow = count($data); // Last data row before TOTAL
        $totalRow = $dataEndRow + 1;
        $data[] = [
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '=SUM(N' . $dataStartRow . ':N' . $dataEndRow . ')', // TOTAL formula (kolom N untuk bobot)
            ''
        ];

        return $data;
    }

    /**
     * Column widths
     */
    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No.
            'B' => 35,  // Nama Produk
            'C' => 30,  // Nama Jabatan
            'D' => 25,  // Satuan Produk
            'E' => 10,  // Minimal
            'F' => 10,  // Maksimal
            'G' => 12,  // Rata-Rata
            'H' => 12,  // Jumlah Volume Kerja
            'I' => 10,  // Manual
            'J' => 15,  // Semi Otomatis
            'K' => 10,  // Otomatis
            'L' => 8,   // Ket. Angka
            'M' => 10,  // Ket. Satuan
            'N' => 12,  // bobot
            'O' => 25,  // Pelaksana seharusnya
        ];
    }

    /**
     * Apply styles
     */
    public function styles(Worksheet $sheet)
    {
        // Title styling (row 2-3)
        $sheet->mergeCells('E2:J2');
        $sheet->mergeCells('E3:J3');
        $sheet->getStyle('E2:J2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getStyle('E3:J3')->applyFromArray([
            'font' => ['bold' => true, 'size' => 10],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        // Formulir A2 marker di kolom O (kolom Pelaksana seharusnya/terakhir) row 8 - satu baris sebelum header
        $sheet->setCellValue('O8', 'Formulir A2');
        $sheet->getStyle('O8')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFF00']
            ],
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
        ]);

        // Info section styling
        $sheet->mergeCells('A5:B5');
        $sheet->mergeCells('A6:B6');
        $sheet->mergeCells('A7:B7');

        $sheet->getStyle('A5:B5')->applyFromArray([
            'font' => ['bold' => true]
        ]);
        $sheet->getStyle('A6:B6')->applyFromArray([
            'font' => ['bold' => true]
        ]);
        $sheet->getStyle('A7:B7')->applyFromArray([
            'font' => ['bold' => true]
        ]);

        // Table header styling (rows 9-12)
        $headerRow1 = 9;
        $headerRow2 = 10;
        $headerRow3 = 11;
        $headerRow4 = 12;

        // Style table header (A-O, 15 kolom)
        $sheet->getStyle('A' . $headerRow1 . ':O' . $headerRow4)->applyFromArray([
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

        // Merge cells for table header
        $sheet->mergeCells('A' . $headerRow1 . ':A' . $headerRow3);
        $sheet->mergeCells('B' . $headerRow1 . ':B' . $headerRow3);
        $sheet->mergeCells('C' . $headerRow1 . ':C' . $headerRow3);
        $sheet->mergeCells('D' . $headerRow1 . ':D' . $headerRow3);
        $sheet->mergeCells('E' . $headerRow1 . ':G' . $headerRow1);  // Norma Waktu
        $sheet->mergeCells('H' . $headerRow1 . ':H' . $headerRow3);  // Jumlah Volume Kerja
        $sheet->mergeCells('I' . $headerRow1 . ':K' . $headerRow1);  // Peralatan
        $sheet->mergeCells('L' . $headerRow1 . ':M' . $headerRow3);  // Ket. (merge L-M di semua 3 rows, seperti bobot)
        $sheet->mergeCells('N' . $headerRow1 . ':N' . $headerRow3);  // bobot
        $sheet->mergeCells('O' . $headerRow1 . ':O' . $headerRow3);  // Pelaksana seharusnya

        // Merge sub-header
        $sheet->mergeCells('E' . $headerRow2 . ':E' . $headerRow3);
        $sheet->mergeCells('F' . $headerRow2 . ':F' . $headerRow3);
        $sheet->mergeCells('G' . $headerRow2 . ':G' . $headerRow3);
        $sheet->mergeCells('I' . $headerRow2 . ':I' . $headerRow3);
        $sheet->mergeCells('J' . $headerRow2 . ':J' . $headerRow3);
        $sheet->mergeCells('K' . $headerRow2 . ':K' . $headerRow3);

        // Set row heights
        $sheet->getRowDimension($headerRow1)->setRowHeight(30);
        $sheet->getRowDimension($headerRow2)->setRowHeight(20);
        $sheet->getRowDimension($headerRow3)->setRowHeight(20);
        $sheet->getRowDimension($headerRow4)->setRowHeight(20);

        // Hilangkan border antara L dan M di row header 4 (column labels)
        $sheet->getStyle('L' . $headerRow4)->applyFromArray([
            'borders' => [
                'right' => [
                    'borderStyle' => Border::BORDER_NONE
                ]
            ]
        ]);
        $sheet->getStyle('M' . $headerRow4)->applyFromArray([
            'borders' => [
                'left' => [
                    'borderStyle' => Border::BORDER_NONE
                ]
            ]
        ]);

        // Find data rows (start from row 13)
        $dataStartRow = 13;
        $lastRow = $sheet->getHighestRow();
        $dataEndRow = $lastRow - 1; // Exclude TOTAL row

        // Apply borders to data cells (A-O, 15 kolom)
        if ($dataEndRow >= $dataStartRow) {
            $sheet->getStyle('A' . $dataStartRow . ':O' . $dataEndRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ]
            ]);

            // Hilangkan border kanan di kolom L agar terlihat seperti satu kolom dengan M
            for ($i = $dataStartRow; $i <= $dataEndRow; $i++) {
                $sheet->getStyle('L' . $i)->applyFromArray([
                    'borders' => [
                        'right' => [
                            'borderStyle' => Border::BORDER_NONE
                        ]
                    ]
                ]);
                $sheet->getStyle('M' . $i)->applyFromArray([
                    'borders' => [
                        'left' => [
                            'borderStyle' => Border::BORDER_NONE
                        ]
                    ]
                ]);
            }

            // Center align specific columns
            $sheet->getStyle('A' . $dataStartRow . ':A' . $dataEndRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('D' . $dataStartRow . ':M' . $dataEndRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('N' . $dataStartRow . ':N' . $dataEndRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Wrap text for column B and C
            $sheet->getStyle('B' . $dataStartRow . ':B' . $dataEndRow)->getAlignment()->setWrapText(true);
            $sheet->getStyle('C' . $dataStartRow . ':C' . $dataEndRow)->getAlignment()->setWrapText(true);
        }

        // Style TOTAL row
        $totalRow = $lastRow;

        // Merge cells A-M untuk "TOTAL" text
        $sheet->mergeCells('A' . $totalRow . ':M' . $totalRow);
        $sheet->setCellValue('A' . $totalRow, 'TOTAL');

        // Apply styling ke semua cells di TOTAL row
        $sheet->getStyle('A' . $totalRow . ':O' . $totalRow)->applyFromArray([
            'font' => ['bold' => true],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);

        // Center align TOTAL text dan bobot
        $sheet->getStyle('A' . $totalRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('N' . $totalRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return [];
    }
}
