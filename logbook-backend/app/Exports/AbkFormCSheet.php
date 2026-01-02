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

class AbkFormCSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
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
        return 'Form C';
    }

    /**
     * Generate data array for the sheet
     * Form C: Perhitungan kebutuhan pejabat/pegawai berdasarkan beban kerja
     */
    public function array(): array
    {
        $data = [];

        // Header - Row 1-8 (14 kolom A-N)
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', '']; // Row 1
        $data[] = ['PERHITUNGAN KEBUTUHAN PEJABAT/PEGAWAI,', '', '', '', '', '', '', '', '', '', '', '', '', '']; // Row 2
        $data[] = ['TINGKAT EFEKTIVITAS DAN EFISIENSI JABATAN (EJ)', '', '', '', '', '', '', '', '', '', '', '', '', '']; // Row 3
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', '']; // Row 4

        // Info section (A-B untuk label merge, C untuk value)
        $data[] = ['UNIT ORGANISASI', '', 'Direktorat Operasi Keamanan Siber', '', '', '', '', '', '', '', '', '', '', '']; // Row 5
        $data[] = ['SATUAN ORGANISASI', '', 'D2', '', '', '', '', '', '', '', '', '', '', '']; // Row 6
        $data[] = ['LOKASI', '', 'Jakarta Selatan', '', '', '', '', '', '', '', '', '', '', '']; // Row 7
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', '']; // Row 8

        // Fixed jabatan list (sama seperti Form B)
        $jabatanList = [
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

        // Table header - Row 9-13 (14 kolom A-N)
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', '']; // Row 9
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', '']; // Row 10 (Formulir C marker akan ada di sini, nempel dengan tabel)
        $data[] = ['No.', 'Nama Jabatan', 'Jumlah Beban Kerja Jab', 'Perhitungan Jumlah Kebutuhan Pejabat', '', '', '', '', '', '', '', 'Jumlah Peg/pej yang ada', '+/-', 'EJ']; // Row 11
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', '']; // Row 12
        $data[] = ['(1)', '(2)', '(3)', '(4)', '', '', '', '', '', '', '', '(5)', '(6)', '(7)']; // Row 13

        // Data rows - dengan row kosong di antara setiap data
        $rowNum = 1;
        $dataStartRow = count($data) + 1;

        // Add row kosong pertama
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', ''];

        foreach ($jabatanList as $jabatan) {
            $currentRow = count($data) + 1;

            // Column mappings for Form B references (after removing column B)
            // C=Kepala BSSN, D=Deputi, E=Direktur, F=Sandiman Utama, etc.
            $columnMap = [
                'Deputi Bidang Operasi Keamanan Siber dan Sandi' => 'D',
                'Direktur Operasi Keamanan Siber' => 'E',
                'Sandiman Ahli Utama' => 'F',
                'Sandiman Ahli Madya' => 'G',
                'Sandiman Ahli Muda' => 'H',
                'Sandiman Ahli Pertama' => 'I',
                'Manggala Informatika Ahli Utama' => 'J',
                'Manggala Informatika Ahli Madya' => 'K',
                'Manggala Informatika Ahli Muda' => 'L',
                'Manggala Informatika Ahli Pertama' => 'M',
                'Pemeriksa Forensik Digital' => 'N',
                'Penata Kelola Sistem dan Teknologi informasi' => 'O',
                'Penelaah Teknis Kebijakan' => 'P',
                'Pengolah Data dan Informasi' => 'Q'
            ];

            $formBColumn = $columnMap[$jabatan] ?? 'C';

            // Jumlah Peg/pej yang ada (dari database) - simplified to 0 for now
            $jumlahPegawai = 0; // TODO: Get from database based on jabatan

            $row = [
                $rowNum, // No. (A)
                $jabatan, // Nama Jabatan (B)
                "='Form B'!{$formBColumn}\$197", // Jumlah Beban Kerja Jab (C) - reference to Form B Jam row
                "='Form B'!{$formBColumn}\$197", // (D) Copy for calculation display
                ':', // (E) Separator
                '1250', // (F) Fixed number
                '=', // (G) Separator
                "=D{$currentRow}/F{$currentRow}", // (H) Hasil pembagian (Jam/1250) - tidak di-round
                '=', // (I) Separator
                "=ROUND(H{$currentRow},0)", // (J) Hasil ROUND
                'Orang', // (K) Label "Orang"
                $jumlahPegawai, // (L) Jumlah Peg/pej yang ada
                "=L{$currentRow}-J{$currentRow}", // (M) +/- = yang ada - kebutuhan
                "=IFERROR(C{$currentRow}/(H{$currentRow}*F{$currentRow}),\"#DIV/0!\")" // (N) EJ = Beban Kerja / (hasil pembagian * 1250)
            ];

            $data[] = $row;
            $rowNum++;

            // Add row kosong setelah setiap data
            $data[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        }

        $dataEndRow = count($data) - 1; // Exclude last empty row

        // Summary row (14 kolom)
        $summaryRow = $dataEndRow + 1;
        $data[] = [
            '', // A
            'Jumlah', // B
            "=SUM(C{$dataStartRow}:C{$dataEndRow})", // C
            "=SUM(C{$dataStartRow}:C{$dataEndRow})", // D - Duplicate for display
            ':', // E
            '1250', // F
            '=', // G
            "=D{$summaryRow}/F{$summaryRow}", // H - Hasil pembagian
            '=', // I
            "=ROUND(H{$summaryRow},0)", // J - Hasil ROUND
            'Orang', // K
            "=SUM(L{$dataStartRow}:L{$dataEndRow})", // L - Total peg yang ada
            "=SUM(M{$dataStartRow}:M{$dataEndRow})", // M - Total +/-
            "=IFERROR(C{$summaryRow}/(H{$summaryRow}*F{$summaryRow}),0)" // N - EJ untuk total
        ];

        return $data;
    }

    /**
     * Column widths
     */
    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 40,  // Nama Jabatan
            'C' => 15,  // Jumlah Beban Kerja Jab
            'D' => 12,  // Perhitungan (left part)
            'E' => 3,   // :
            'F' => 8,   // 1250
            'G' => 3,   // =
            'H' => 10,  // Hasil pembagian
            'I' => 3,   // =
            'J' => 8,   // ROUND result
            'K' => 10,  // "Orang"
            'L' => 10,  // Jumlah Peg/pej yang ada
            'M' => 8,   // +/-
            'N' => 10,  // EJ
        ];
    }

    /**
     * Apply styles
     */
    public function styles(Worksheet $sheet)
    {
        // Title styling (row 2-3) - merge 1 kolom full dari A sampai N
        $sheet->mergeCells('A2:N2');
        $sheet->mergeCells('A3:N3');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getStyle('A3')->applyFromArray([
            'font' => ['bold' => true, 'size' => 10],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        // Formulir C marker di kolom N row 10 (nempel dengan tabel)
        $sheet->setCellValue('N10', 'Formulir C');
        $sheet->getStyle('N10')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFF00']
            ],
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
        ]);

        // Info section styling (merge A-B untuk label, C untuk value)
        $sheet->mergeCells('A5:B5');
        $sheet->mergeCells('A6:B6');
        $sheet->mergeCells('A7:B7');

        $sheet->getStyle('A5:B5')->applyFromArray(['font' => ['bold' => true]]);
        $sheet->getStyle('A6:B6')->applyFromArray(['font' => ['bold' => true]]);
        $sheet->getStyle('A7:B7')->applyFromArray(['font' => ['bold' => true]]);

        $lastRow = $sheet->getHighestRow();

        // Table header styling (rows 11-13, 14 kolom A-N) - PUTIH (no fill color)
        $sheet->getStyle('A11:N13')->applyFromArray([
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
        $sheet->mergeCells('A11:A12'); // No
        $sheet->mergeCells('B11:B12'); // Nama Jabatan
        $sheet->mergeCells('C11:C12'); // Jumlah Beban Kerja Jab
        $sheet->mergeCells('D11:K12'); // Perhitungan Jumlah Kebutuhan Pejabat (merge D11 sampai K12)
        $sheet->mergeCells('L11:L12'); // Jumlah Peg/pej yang ada
        $sheet->mergeCells('M11:M12'); // +/-
        $sheet->mergeCells('N11:N12'); // EJ

        // Row 13: Merge D13:K13 untuk tulisan (4) di tengah
        $sheet->mergeCells('D13:K13');

        // Set row heights
        $sheet->getRowDimension(11)->setRowHeight(30);
        $sheet->getRowDimension(12)->setRowHeight(20);
        $sheet->getRowDimension(13)->setRowHeight(20);

        // Data rows styling
        $dataStartRow = 14; // Updated karena sekarang header sampai row 13
        $dataEndRow = $lastRow - 1; // Exclude summary row

        if ($dataEndRow >= $dataStartRow) {
            // Apply vertical borders only (left and right) untuk kolom A, B, C, L, M, N
            for ($row = $dataStartRow; $row <= $dataEndRow; $row++) {
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
                        'right' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
                    ]
                ]);
                // Column D - hanya border kiri
                $sheet->getStyle('D' . $row)->applyFromArray([
                    'borders' => [
                        'left' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
                    ]
                ]);
                // Column E-J - tidak ada border sama sekali
                for ($col = 'E'; $col <= 'J'; $col++) {
                    $sheet->getStyle($col . $row)->applyFromArray([
                        'borders' => [
                            'left' => ['borderStyle' => Border::BORDER_NONE],
                            'right' => ['borderStyle' => Border::BORDER_NONE],
                            'top' => ['borderStyle' => Border::BORDER_NONE],
                            'bottom' => ['borderStyle' => Border::BORDER_NONE]
                        ]
                    ]);
                }
                // Column K - hanya border kanan
                $sheet->getStyle('K' . $row)->applyFromArray([
                    'borders' => [
                        'right' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
                    ]
                ]);
                // Column L
                $sheet->getStyle('L' . $row)->applyFromArray([
                    'borders' => [
                        'right' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
                    ]
                ]);
                // Column M
                $sheet->getStyle('M' . $row)->applyFromArray([
                    'borders' => [
                        'right' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
                    ]
                ]);
                // Column N
                $sheet->getStyle('N' . $row)->applyFromArray([
                    'borders' => [
                        'right' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
                    ]
                ]);
            }

            // Bold semua data
            $sheet->getStyle('A' . $dataStartRow . ':N' . $dataEndRow)->applyFromArray([
                'font' => ['bold' => true]
            ]);

            // Center align specific columns
            $sheet->getStyle('A' . $dataStartRow . ':A' . $dataEndRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('C' . $dataStartRow . ':N' . $dataEndRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Wrap text for column B (Nama Jabatan)
            $sheet->getStyle('B' . $dataStartRow . ':B' . $dataEndRow)->getAlignment()->setWrapText(true);
        }

        // Style summary row (last row - 14 kolom A-N) - border atas, bawah, dan vertical
        $sheet->getStyle('A' . $lastRow . ':N' . $lastRow)->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        // Apply borders untuk summary row (atas, bawah, dan vertical)
        // Column A
        $sheet->getStyle('A' . $lastRow)->applyFromArray([
            'borders' => [
                'left' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'right' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
            ]
        ]);
        // Column B
        $sheet->getStyle('B' . $lastRow)->applyFromArray([
            'borders' => [
                'right' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
            ]
        ]);
        // Column C
        $sheet->getStyle('C' . $lastRow)->applyFromArray([
            'borders' => [
                'right' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
            ]
        ]);
        // Column D - border kiri, atas, dan bottom
        $sheet->getStyle('D' . $lastRow)->applyFromArray([
            'borders' => [
                'left' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
            ]
        ]);
        // Column E-J - border atas dan bottom
        for ($col = 'E'; $col <= 'J'; $col++) {
            $sheet->getStyle($col . $lastRow)->applyFromArray([
                'borders' => [
                    'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                    'bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
                ]
            ]);
        }
        // Column K - border kanan, atas, dan bottom
        $sheet->getStyle('K' . $lastRow)->applyFromArray([
            'borders' => [
                'right' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
            ]
        ]);
        // Column L
        $sheet->getStyle('L' . $lastRow)->applyFromArray([
            'borders' => [
                'right' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
            ]
        ]);
        // Column M
        $sheet->getStyle('M' . $lastRow)->applyFromArray([
            'borders' => [
                'right' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
            ]
        ]);
        // Column N
        $sheet->getStyle('N' . $lastRow)->applyFromArray([
            'borders' => [
                'right' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                'bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
            ]
        ]);

        // Add tanggal dan tanda tangan di luar table (tidak mengikuti format 14 kolom)
        $signatureRow = $lastRow + 3; // 3 rows setelah summary row

        // Format tanggal: Jakarta, DD Month YYYY
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $currentDate = date('j') . ' ' . $months[(int)date('n')] . ' ' . date('Y');

        // Set nilai tanggal dan nama jabatan
        $sheet->setCellValue('J' . $signatureRow, 'Jakarta, ' . $currentDate);
        $sheet->setCellValue('J' . ($signatureRow + 1), 'Direktur Operasi Keamanan Siber');

        // Styling untuk tanggal dan tanda tangan
        $sheet->getStyle('J' . $signatureRow . ':J' . ($signatureRow + 1))->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ]
        ]);

        return [];
    }
}
