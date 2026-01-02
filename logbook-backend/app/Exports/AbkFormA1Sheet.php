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

class AbkFormA1Sheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
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
        return 'Form A1';
    }

    /**
     * Generate data array for the sheet
     * Semua rencana SKP dalam 1 sheet, setiap SKP punya tabel sendiri
     */
    public function array(): array
    {
        $data = [];

        // Header - Row 1-3: Title (15 kolom sekarang)
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        $data[] = ['', '', '', '', 'PENGUMPULAN DATA BEBAN KERJA NORMA WAKTU PRODUK', '', '', '', '', '', '', '', '', '', ''];
        $data[] = ['', '', '', '', 'BERDASARKAN NORMA PROSES TAHAPAN', '', '', '', '', '', '', '', '', '', ''];
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];

        // Get all unique rencana SKP
        $rencanaSkpList = Logbook::whereYear('tanggal', $this->year)
            ->where('status', 'Disetujui')
            ->select('rencana_hasil_kinerja_skp')
            ->distinct()
            ->orderBy('rencana_hasil_kinerja_skp')
            ->pluck('rencana_hasil_kinerja_skp');

        // Generate table untuk setiap rencana SKP
        foreach ($rencanaSkpList as $rencanaSkp) {
            // Info section untuk SKP ini (15 kolom)
            // Format: A-B untuk label (merge), C untuk value
            $data[] = ['NAMA PRODUK', '', $rencanaSkp, '', '', '', '', '', '', '', '', '', '', '', ''];
            $data[] = ['BAGIAN/BIDANG/SUBDIT', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
            $data[] = ['BIRO/PUSAT/DIT', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
            $data[] = ['UNIT KERJA', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
            $data[] = ['LOKASI', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
            $data[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];

            // Table header untuk SKP ini
            $data[] = [
                'No.',
                'Uraian Proses/Tahapan untuk menghasilkan Produk',
                'Satuan Produk',
                'Jumlah Volume Kerja',
                'Norma Waktu (Menit)',
                '',
                '',
                '',
                '',
                '',
                'Ket.',
                '',
                'pelaksana',
                'pelaksana seharusnya',
                'Total'
            ];
            $data[] = ['', '', '', '', 'Minimal', 'Maksimal', 'Rata-Rata', 'Manual', 'Semi Otomatis', 'Otomatis', '', '', '', '', ''];
            $data[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
            $data[] = ['(1)', '(2)', '(3)', '(4)', '(5)', '(6)', '(7)', '(8)', '(9)', '(10)', '(11)', '', '(12)', '(13)', '(14)'];

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
                        'uraian' => $logbook->aktivitas_kegiatan_harian,
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

            // Add data rows untuk SKP ini
            $rowNum = 1;
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

                $pelaksana = !empty($item['pelaksana']) ? implode(', ', $item['pelaksana']) : 'orang';
                $pelaksanaSeharusnya = $pelaksana;

                $data[] = [
                    $rowNum,
                    $item['uraian'],
                    $item['satuan'],
                    $volumeKerja,
                    round($minimal, 0),
                    round($maksimal, 0),
                    '=ROUND((E' . (count($data) + 1) . '+F' . (count($data) + 1) . ')/2,0)',
                    $manual,
                    $semiOtomatis,
                    $otomatis,
                    $ketAngka,
                    $ketSatuan,
                    $pelaksana,
                    $pelaksanaSeharusnya,
                    '=D' . (count($data) + 1) . '*G' . (count($data) + 1) . '*K' . (count($data) + 1)
                ];

                $rowNum++;
            }

            // Add spacing setelah tabel SKP ini (15 kolom)
            $data[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
            $data[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        }

        return $data;
    }

    /**
     * Column widths
     */
    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 35,
            'C' => 25,
            'D' => 12,
            'E' => 10,
            'F' => 10,
            'G' => 12,
            'H' => 10,
            'I' => 15,
            'J' => 10,
            'K' => 8,   // Ket. Angka
            'L' => 10,  // Ket. Satuan
            'M' => 25,  // pelaksana
            'N' => 25,  // pelaksana seharusnya
            'O' => 12,  // Total
        ];
    }

    /**
     * Apply styles
     * Styling dinamis untuk multiple tabel dalam 1 sheet
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

        $lastRow = $sheet->getHighestRow();

        // Loop through all rows untuk apply styling dinamis
        for ($row = 5; $row <= $lastRow; $row++) {
            $cellValue = $sheet->getCell('A' . $row)->getValue();

            // Detect baris kosong sebelum table header dan tambahkan marker Formulir A1
            if ($row > 5 && $cellValue === '' && $sheet->getCell('A' . ($row + 1))->getValue() === 'No.') {
                // Ini adalah baris kosong sebelum header tabel
                $sheet->setCellValue('O' . $row, 'Formulir A1');
                $sheet->getStyle('O' . $row)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFFF00']
                    ],
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
                ]);
            }

            // Detect Info section (NAMA PRODUK, BAGIAN, etc)
            if (in_array($cellValue, ['NAMA PRODUK', 'BAGIAN/BIDANG/SUBDIT', 'BIRO/PUSAT/DIT', 'UNIT KERJA', 'LOKASI'])) {
                // Merge cells A-B untuk label
                $sheet->mergeCells('A' . $row . ':B' . $row);

                // Apply styling to merged label cells
                $sheet->getStyle('A' . $row . ':B' . $row)->applyFromArray([
                    'font' => ['bold' => true]
                ]);
            }

            // Detect table header (No.)
            if ($cellValue === 'No.') {
                $headerRow1 = $row;
                $headerRow2 = $row + 1;
                $headerRow3 = $row + 2;
                $headerRow4 = $row + 3;

                // Style table header (sekarang A-O, 15 kolom)
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
                $sheet->mergeCells('E' . $headerRow1 . ':J' . $headerRow1);  // Norma Waktu
                $sheet->mergeCells('K' . $headerRow1 . ':L' . $headerRow3);  // Ket. (merge K-L di semua 3 rows, seperti bobot)
                $sheet->mergeCells('M' . $headerRow1 . ':M' . $headerRow3);  // pelaksana
                $sheet->mergeCells('N' . $headerRow1 . ':N' . $headerRow3);  // pelaksana seharusnya
                $sheet->mergeCells('O' . $headerRow1 . ':O' . $headerRow3);  // Total

                // Merge sub-header
                $sheet->mergeCells('E' . $headerRow2 . ':E' . $headerRow3);
                $sheet->mergeCells('F' . $headerRow2 . ':F' . $headerRow3);
                $sheet->mergeCells('G' . $headerRow2 . ':G' . $headerRow3);
                $sheet->mergeCells('H' . $headerRow2 . ':H' . $headerRow3);
                $sheet->mergeCells('I' . $headerRow2 . ':I' . $headerRow3);
                $sheet->mergeCells('J' . $headerRow2 . ':J' . $headerRow3);

                // Set row heights
                $sheet->getRowDimension($headerRow1)->setRowHeight(30);
                $sheet->getRowDimension($headerRow2)->setRowHeight(20);
                $sheet->getRowDimension($headerRow3)->setRowHeight(20);
                $sheet->getRowDimension($headerRow4)->setRowHeight(20);

                // Hilangkan border antara K dan L di row header 4 (column labels)
                $sheet->getStyle('K' . $headerRow4)->applyFromArray([
                    'borders' => [
                        'right' => [
                            'borderStyle' => Border::BORDER_NONE
                        ]
                    ]
                ]);
                $sheet->getStyle('L' . $headerRow4)->applyFromArray([
                    'borders' => [
                        'left' => [
                            'borderStyle' => Border::BORDER_NONE
                        ]
                    ]
                ]);

                // Find data rows untuk tabel ini (sampai baris kosong)
                $dataStartRow = $headerRow4 + 1;
                $dataEndRow = $dataStartRow;
                for ($i = $dataStartRow; $i <= $lastRow; $i++) {
                    $firstCell = $sheet->getCell('A' . $i)->getValue();
                    if ($firstCell === '' || $firstCell === null) {
                        $dataEndRow = $i - 1;
                        break;
                    }
                    $dataEndRow = $i;
                }

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

                    // Hilangkan border kanan di kolom K agar terlihat seperti satu kolom dengan L
                    for ($i = $dataStartRow; $i <= $dataEndRow; $i++) {
                        $sheet->getStyle('K' . $i)->applyFromArray([
                            'borders' => [
                                'right' => [
                                    'borderStyle' => Border::BORDER_NONE
                                ]
                            ]
                        ]);
                        $sheet->getStyle('L' . $i)->applyFromArray([
                            'borders' => [
                                'left' => [
                                    'borderStyle' => Border::BORDER_NONE
                                ]
                            ]
                        ]);
                    }

                    // Center align specific columns
                    $sheet->getStyle('A' . $dataStartRow . ':A' . $dataEndRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('D' . $dataStartRow . ':K' . $dataEndRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('L' . $dataStartRow . ':L' . $dataEndRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('O' . $dataStartRow . ':O' . $dataEndRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    // Wrap text for column B
                    $sheet->getStyle('B' . $dataStartRow . ':B' . $dataEndRow)->getAlignment()->setWrapText(true);
                }
            }
        }

        return [];
    }
}
