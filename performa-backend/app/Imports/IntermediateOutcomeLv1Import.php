<?php

namespace App\Imports;

use App\Models\FinalOutcome;
use App\Models\IntermediateOutcomeLv1;
use App\Models\IntermediateOutcomeLv1Indicator;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IntermediateOutcomeLv1Import implements
    ToCollection,
    WithHeadingRow,
    WithValidation,
    SkipsEmptyRows
{
    protected $periodeId;
    protected $importedCount = 0;
    protected $updatedCount = 0;
    protected $errors = [];

    public function __construct($periodeId)
    {
        $this->periodeId = $periodeId;
    }

    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        try {
            foreach ($rows as $index => $row) {
                try {
                    $this->processRow($row->toArray(), $index + 2);
                } catch (\Exception $e) {
                    $this->errors[] = [
                        'row' => $index + 2,
                        'data' => $row->toArray(),
                        'error' => $e->getMessage()
                    ];
                    Log::error("Error processing row " . ($index + 2), [
                        'error' => $e->getMessage(),
                        'data' => $row->toArray()
                    ]);
                }
            }

            DB::commit();
            Log::info("Import completed. Created: {$this->importedCount}, Updated: {$this->updatedCount}, Errors: " . count($this->errors));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    protected function processRow(array $row, int $rowNumber)
    {
        // Get column values
        $kodeUo = $this->getColumnValue($row, ['kode_uo', 'kode_u_o', 'kode uo']);
        $kodeIntOLv1 = $this->getColumnValue($row, ['kode_int_o_lv1', 'kode int o lv1', 'kode_intermediate_lv1']);
        $sasaran = $this->getColumnValue($row, ['sasaran']);
        $indikatorKinerja = $this->getColumnValue($row, ['indikator_kinerja', 'indikator kinerja']);

        // Validate required fields
        if (empty($kodeUo)) {
            throw new \Exception("Row {$rowNumber}: Kode UO tidak boleh kosong");
        }

        if (empty($kodeIntOLv1)) {
            throw new \Exception("Row {$rowNumber}: Kode Int. O LV1 tidak boleh kosong");
        }

        if (empty($sasaran)) {
            throw new \Exception("Row {$rowNumber}: Sasaran tidak boleh kosong");
        }

        if (empty($indikatorKinerja)) {
            throw new \Exception("Row {$rowNumber}: Indikator Kinerja tidak boleh kosong");
        }

        Log::info("Processing row {$rowNumber}", [
            'kode_uo' => $kodeUo,
            'kode_int_o_lv1' => $kodeIntOLv1,
            'sasaran' => substr($sasaran, 0, 50) . '...',
        ]);

        // Find Final Outcome by kode_uo and periode
        $finalOutcome = FinalOutcome::where('kode_uo', $kodeUo)
            ->where('periode_id', $this->periodeId)
            ->first();

        if (!$finalOutcome) {
            throw new \Exception("Row {$rowNumber}: Final Outcome dengan Kode UO {$kodeUo} tidak ditemukan untuk periode ini");
        }

        // Check if already exists
        $intermediateOutcome = IntermediateOutcomeLv1::where('final_outcome_id', $finalOutcome->id)
            ->where('kode_int_o_lv1', $kodeIntOLv1)
            ->first();

        if ($intermediateOutcome) {
            // Update existing
            $intermediateOutcome->update([
                'kode_uo' => $kodeUo,
                'sasaran' => $sasaran,
            ]);

            // Delete old indicators
            $intermediateOutcome->indicators()->delete();

            Log::info("Updated existing Intermediate LV1", [
                'id' => $intermediateOutcome->id,
                'kode_int_o_lv1' => $kodeIntOLv1
            ]);

            $this->updatedCount++;
        } else {
            // Create new
            $intermediateOutcome = IntermediateOutcomeLv1::create([
                'final_outcome_id' => $finalOutcome->id,
                'kode_uo' => $kodeUo,
                'kode_int_o_lv1' => $kodeIntOLv1,
                'sasaran' => $sasaran,
            ]);

            Log::info("Created new Intermediate LV1", [
                'id' => $intermediateOutcome->id,
                'kode_int_o_lv1' => $kodeIntOLv1
            ]);

            $this->importedCount++;
        }

        // Save indicators
        $indicatorCount = $this->saveIndikators($intermediateOutcome, $indikatorKinerja);
        Log::info("Saved {$indicatorCount} indicators for Intermediate LV1 ID: {$intermediateOutcome->id}");
    }

    protected function getColumnValue($row, $possibleKeys)
    {
        foreach ($possibleKeys as $key) {
            if (isset($row[$key]) && !empty($row[$key])) {
                return trim($row[$key]);
            }

            $lowerKey = strtolower($key);
            if (isset($row[$lowerKey]) && !empty($row[$lowerKey])) {
                return trim($row[$lowerKey]);
            }

            $spaceKey = str_replace('_', ' ', $key);
            if (isset($row[$spaceKey]) && !empty($row[$spaceKey])) {
                return trim($row[$spaceKey]);
            }
        }

        return null;
    }

    protected function saveIndikators($intermediateOutcome, $indikatorString)
    {
        $savedCount = 0;
        $indikators = [];

        $indikatorString = (string) $indikatorString;

        // Split by pipe (|)
        if (strpos($indikatorString, '|') !== false) {
            $indikators = explode('|', $indikatorString);
        }
        // Split by semicolon (;)
        elseif (strpos($indikatorString, ';') !== false) {
            $indikators = explode(';', $indikatorString);
        }
        // Split by newline
        elseif (strpos($indikatorString, "\n") !== false || strpos($indikatorString, "\r") !== false) {
            $indikators = preg_split('/\r\n|\r|\n/', $indikatorString);
        }
        // Use as single indicator
        else {
            $indikators = [$indikatorString];
        }

        // Clean and save each indicator
        foreach ($indikators as $index => $indikator) {
            $indikator = trim($indikator);

            if (strlen($indikator) < 3) {
                continue;
            }

            try {
                $intermediateOutcome->indicators()->create([
                    'indikator_kinerja' => $indikator,
                ]);
                $savedCount++;
            } catch (\Exception $e) {
                Log::error("Failed to save indicator", [
                    'index' => $index + 1,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $savedCount;
    }

    public function rules(): array
    {
        return [
            'kode_uo' => 'required|string|max:50',
            'kode_int_o_lv1' => 'required|string|max:50',
            'sasaran' => 'required|string',
            'indikator_kinerja' => 'required|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'kode_uo.required' => 'Kode UO wajib diisi pada baris :row',
            'kode_int_o_lv1.required' => 'Kode Int. O LV1 wajib diisi pada baris :row',
            'sasaran.required' => 'Sasaran wajib diisi pada baris :row',
            'indikator_kinerja.required' => 'Indikator Kinerja wajib diisi pada baris :row',
        ];
    }

    public function getImportedCount()
    {
        return $this->importedCount + $this->updatedCount;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function headingRow(): int
    {
        return 1;
    }
}
