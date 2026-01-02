<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AbkFormDSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
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
        return 'Form D';
    }

    /**
     * Generate data array for the sheet
     * Form D: Rekapitulasi Kebutuhan Pejabat, Tingkat Efektivitas dan Efisiensi Unit (EU)
     */
    public function array(): array
    {
        $data = [];

        // Header - Row 1-4 (12 kolom A-L, bukan 13)
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', '']; // Row 1
        $data[] = ['REKAPITULASI KEBUTUHAN PEJABAT,', '', '', '', '', '', '', '', '', '', '', '']; // Row 2 - judul akan di-merge A2:L2
        $data[] = ['TINGKAT EFEKTIVITAS DAN EFISIENSI UNIT (EU)', '', '', '', '', '', '', '', '', '', '', '']; // Row 3 - judul akan di-merge A3:L3
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', '']; // Row 4

        // Info section (A-B untuk label merge, C untuk value)
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', '']; // Row 5
        $data[] = ['UNIT ORGANISASI', '', '', '', 'Direktorat Operasi Keamanan Siber', '', '', '', '', '', '', '']; // Row 6
        $data[] = ['SATUAN ORGANISASI', '', 'D2', '', '', '', '', '', '', '', '', '']; // Row 7
        $data[] = ['LOKASI', '', 'Jakarta Selatan', '', '', '', '', '', '', '', '', '']; // Row 8
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', '']; // Row 9

        // Formulir D marker akan ada di row 10 (nempel dengan tabel)
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', '']; // Row 10

        // Table header - Row 11-15 (12 kolom A-L)
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', '']; // Row 11
        $data[] = ['No.', 'Nama Bagian/Bidang/Subdit', 'Jml. Beban Kerja Unit', '', '', '', '', '', '', 'Jumlah Pej. yang ada', '+/-', 'EU']; // Row 12
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', '']; // Row 13
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', '']; // Row 14
        $data[] = ['(1)', '(2)', '(3)', '', '', '', '', '', '', '(5)', '(6)', '(7)']; // Row 15

        // Data structure:
        // Row kosong
        // Data 1: Deputi Bidang Operasi Keamanan Siber dan Sandi
        // Row kosong
        // Data 2: Direktorat Operasi Keamanan Siber
        // Row kosong
        // Jumlah

        // Add row kosong pertama
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', ''];

        // Data 1: Deputi Bidang Operasi Keamanan Siber dan Sandi (row 17)
        $row17 = count($data) + 1;
        $data[] = [
            '1', // No. (A)
            'Deputi Bidang Operasi Keamanan Siber dan Sandi', // Nama Bagian (B)
            "='Form C'!C19", // Jml. Beban Kerja Unit (C) - reference ke Form C row 19
            ':', // (D) Separator
            '1250', // (E) Fixed
            '=', // (F) Separator
            "=C{$row17}/E{$row17}", // (G) Hasil pembagian (C/E) - tidak di-round
            '=', // (H) Separator
            "=ROUND(G{$row17},0)", // (I) Hasil ROUND
            '1', // Jumlah Pej. yang ada (J)
            "=J{$row17}-I{$row17}", // +/- (K) = yang ada - kebutuhan
            "=IFERROR(C{$row17}/(G{$row17}*E{$row17}),\"#DIV/0!\")" // EU (L) = Beban Kerja / (hasil pembagian * 1250)
        ];

        // Row kosong
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', ''];

        // Data 2: Direktorat Operasi Keamanan Siber (row 19)
        $row19 = count($data) + 1;
        $data[] = [
            '2', // No. (A)
            'Direktorat Operasi Keamanan Siber', // Nama Bagian (B)
            "='Form C'!C48-'Form C'!C19", // Jml. Beban Kerja Unit (C) - Form C row 48 - row 19
            ':', // (D) Separator
            '1250', // (E) Fixed
            '=', // (F) Separator
            "=C{$row19}/E{$row19}", // (G) Hasil pembagian
            '=', // (H) Separator
            "=ROUND(G{$row19},0)", // (I) Hasil ROUND
            '182', // Jumlah Pej. yang ada (J)
            "=J{$row19}-I{$row19}", // +/- (K)
            "=IFERROR(C{$row19}/(G{$row19}*E{$row19}),\"#DIV/0!\")" // EU (L)
        ];

        // Row kosong
        $data[] = ['', '', '', '', '', '', '', '', '', ''];

        // Summary row: Jumlah (row 21)
        $summaryRow = count($data) + 1;
        $data[] = [
            '', // (A)
            'Jumlah', // (B)
            "=SUM(C17:C19)", // (C) SUM of beban kerja
            ':', // (D)
            '1250', // (E)
            '=', // (F)
            "=C{$summaryRow}/E{$summaryRow}", // (G)
            '=', // (H)
            "=ROUND(G{$summaryRow},0)", // (I)
            "=SUM(J17:J19)", // (J) SUM of pejabat yang ada
            "=SUM(K17:K19)", // (K) SUM of +/-
            "=IFERROR(C{$summaryRow}/(G{$summaryRow}*E{$summaryRow}),0)" // EU (L)
        ];

        // Add rows untuk tanggal dan tanda tangan (12 kolom)
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', ''];
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', ''];
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', ''];

        // Format tanggal: Jakarta, DD Month YYYY (contoh: Jakarta, 18 Desember 2024)
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $currentDate = date('j') . ' ' . $months[(int)date('n')] . ' ' . date('Y');

        $data[] = ['', '', '', '', '', '', '', '', 'Jakarta, ' . $currentDate, '', '', ''];
        $data[] = ['', '', '', '', '', '', '', '', 'Direktur Operasi Keamanan Siber', '', '', ''];

        // Add more empty rows for signature space
        for ($i = 0; $i < 4; $i++) {
            $data[] = ['', '', '', '', '', '', '', '', '', '', '', ''];
        }

        return $data;
    }

    /**
     * Column widths
     */
    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 40,  // Nama Bagian/Bidang/Subdit
            'C' => 15,  // Jml. Beban Kerja Unit
            'D' => 3,   // :
            'E' => 8,   // 1250
            'F' => 3,   // =
            'G' => 10,  // Hasil pembagian
            'H' => 3,   // =
            'I' => 8,   // ROUND result
            'J' => 10,  // Jumlah Pej. yang ada
            'K' => 8,   // +/-
            'L' => 10,  // EU
        ];
    }

    /**
     * Apply styles
     */
    public function styles(Worksheet $sheet)
    {
        // Title styling (row 2-3) - merge dari A2:L2 dan A3:L3
        $sheet->mergeCells('A2:L2');
        $sheet->mergeCells('A3:L3');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getStyle('A3')->applyFromArray([
            'font' => ['bold' => true, 'size' => 10],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        // Formulir D marker di kolom L row 11 (nempel dengan tabel, satu row sebelum header)
        $sheet->setCellValue('L11', 'Formulir D');
        $sheet->getStyle('L11')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFF00']
            ],
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
        ]);

        // Info section styling (merge A-B untuk label, value di kolom lain)
        $sheet->mergeCells('A6:B6');
        $sheet->mergeCells('A7:B7');
        $sheet->mergeCells('A8:B8');

        $sheet->getStyle('A6:B6')->applyFromArray(['font' => ['bold' => true]]);
        $sheet->getStyle('A7:B7')->applyFromArray(['font' => ['bold' => true]]);
        $sheet->getStyle('A8:B8')->applyFromArray(['font' => ['bold' => true]]);

        // Merge value cells untuk Unit Organisasi
        $sheet->mergeCells('C6:H6');

        // Table header styling (rows 12-15, 12 kolom A-L) - PUTIH (no fill color)
        $sheet->getStyle('A12:L15')->applyFromArray([
            'font' => ['bold' => true],
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
        $sheet->mergeCells('A12:A14'); // No
        $sheet->mergeCells('B12:B14'); // Nama Bagian
        $sheet->mergeCells('C12:I14'); // Jmlh Keb. Pej (merge C12:I14 - sesuai permintaan)
        $sheet->mergeCells('J12:J14'); // Jumlah Pej. yang ada
        $sheet->mergeCells('K12:K14'); // +/-
        $sheet->mergeCells('L12:L14'); // EU

        // Row 15: Merge C15:I15 untuk tulisan (3)
        $sheet->mergeCells('C15:I15');

        // Set row heights
        $sheet->getRowDimension(12)->setRowHeight(30);
        $sheet->getRowDimension(13)->setRowHeight(20);
        $sheet->getRowDimension(14)->setRowHeight(20);
        $sheet->getRowDimension(15)->setRowHeight(20);

        $lastRow = $sheet->getHighestRow();

        // Data rows styling (rows 16-21 based on structure)
        $dataStartRow = 16; // Row kosong pertama
        $dataEndRow = 21; // Summary row

        if ($dataEndRow >= $dataStartRow) {
            // Apply vertical borders only untuk data rows (kecuali summary row)
            for ($row = $dataStartRow; $row <= $dataEndRow - 1; $row++) {
                // Column A
                $sheet->getStyle('A' . $row)->applyFromArray([
                    'borders' => [
                        'left' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                        'right' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
                    ]
                ]);
                // Column B
                $sheet->getStyle('B' . $row)->applyFromArray([
                    'borders' => [
                        'right' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
                    ]
                ]);
                // Column C
                $sheet->getStyle('C' . $row)->applyFromArray([
                    'borders' => [
                        'left' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                        'right' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
                    ]
                ]);
                // Column D - hanya border kiri
                $sheet->getStyle('D' . $row)->applyFromArray([
                    'borders' => [
                        'left' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
                    ]
                ]);
                // Column E-H - tidak ada border sama sekali
                for ($col = 'E'; $col <= 'H'; $col++) {
                    $sheet->getStyle($col . $row)->applyFromArray([
                        'borders' => [
                            'left' => ['borderStyle' => Border::BORDER_NONE],
                            'right' => ['borderStyle' => Border::BORDER_NONE],
                            'top' => ['borderStyle' => Border::BORDER_NONE],
                            'bottom' => ['borderStyle' => Border::BORDER_NONE]
                        ]
                    ]);
                }
                // Column I - hanya border kanan
                $sheet->getStyle('I' . $row)->applyFromArray([
                    'borders' => [
                        'right' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
                    ]
                ]);
                // Column J
                $sheet->getStyle('J' . $row)->applyFromArray([
                    'borders' => [
                        'right' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
                    ]
                ]);
                // Column K
                $sheet->getStyle('K' . $row)->applyFromArray([
                    'borders' => [
                        'right' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
                    ]
                ]);
                // Column L (EU)
                $sheet->getStyle('L' . $row)->applyFromArray([
                    'borders' => [
                        'right' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
                    ]
                ]);
            }

            // Bold semua data rows (rows 17, 19 - data rows only, not empty rows)
            $sheet->getStyle('A17:L17')->applyFromArray(['font' => ['bold' => true]]);
            $sheet->getStyle('A19:L19')->applyFromArray(['font' => ['bold' => true]]);

            // Center align specific columns for data rows
            $sheet->getStyle('A17:A19')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('C17:L19')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Wrap text for column B (Nama Bagian)
            $sheet->getStyle('B17:B19')->getAlignment()->setWrapText(true);
        }

        // Style summary row (row 21) - border atas, bawah, dan vertical
        $summaryRow = 21;
        $sheet->getStyle('A' . $summaryRow . ':L' . $summaryRow)->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        // Apply borders untuk summary row (atas, bawah, dan vertical)
        // Column A
        $sheet->getStyle('A' . $summaryRow)->applyFromArray([
            'borders' => [
                'left' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'right' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
            ]
        ]);
        // Column B
        $sheet->getStyle('B' . $summaryRow)->applyFromArray([
            'borders' => [
                'right' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
            ]
        ]);
        // Column C
        $sheet->getStyle('C' . $summaryRow)->applyFromArray([
            'borders' => [
                'left' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'right' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
            ]
        ]);
        // Column D - border kiri, atas, dan bottom
        $sheet->getStyle('D' . $summaryRow)->applyFromArray([
            'borders' => [
                'left' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
            ]
        ]);
        // Column E-H - border atas dan bottom
        for ($col = 'E'; $col <= 'H'; $col++) {
            $sheet->getStyle($col . $summaryRow)->applyFromArray([
                'borders' => [
                    'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                    'bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
                ]
            ]);
        }
        // Column I - border kanan, atas, dan bottom
        $sheet->getStyle('I' . $summaryRow)->applyFromArray([
            'borders' => [
                'right' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
            ]
        ]);
        // Column J
        $sheet->getStyle('J' . $summaryRow)->applyFromArray([
            'borders' => [
                'right' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
            ]
        ]);
        // Column K
        $sheet->getStyle('K' . $summaryRow)->applyFromArray([
            'borders' => [
                'right' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
            ]
        ]);
        // Column L (EU)
        $sheet->getStyle('L' . $summaryRow)->applyFromArray([
            'borders' => [
                'right' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
            ]
        ]);

        return [];
    }
}
