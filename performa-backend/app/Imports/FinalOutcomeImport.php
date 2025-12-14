<?php

namespace App\Imports;

use App\Models\FinalOutcome;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FinalOutcomeImport implements
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

    /**
     * @param Collection $rows
     */
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

    /**
     * Process single row
     */
    protected function processRow(array $row, int $rowNumber)
    {
        // Get column values
        $kodeUo = $this->getColumnValue($row, ['kode_uo', 'kode_u_o', 'kode uo']);
        $sasaran = $this->getColumnValue($row, ['sasaran']);
        $indikatorKinerja = $this->getColumnValue($row, ['indikator_kinerja', 'indikator kinerja']);

        // Validate required fields
        if (empty($kodeUo)) {
            throw new \Exception("Row {$rowNumber}: Kode UO tidak boleh kosong");
        }

        if (empty($sasaran)) {
            throw new \Exception("Row {$rowNumber}: Sasaran tidak boleh kosong");
        }

        if (empty($indikatorKinerja)) {
            throw new \Exception("Row {$rowNumber}: Indikator Kinerja tidak boleh kosong");
        }

        Log::info("Processing row {$rowNumber}", [
            'kode_uo' => $kodeUo,
            'sasaran' => substr($sasaran, 0, 50) . '...',
            'indikator_count' => strlen($indikatorKinerja)
        ]);

        // Check if already exists for THIS periode AND kode_uo
        $finalOutcome = FinalOutcome::where('periode_id', $this->periodeId)
            ->where('kode_uo', $kodeUo)
            ->first();

        if ($finalOutcome) {
            // Update existing
            $finalOutcome->update([
                'sasaran' => $sasaran,
            ]);

            // Delete old indicators
            $finalOutcome->indicators()->delete();

            Log::info("Updated existing Final Outcome", [
                'id' => $finalOutcome->id,
                'kode_uo' => $kodeUo
            ]);

            $this->updatedCount++;
        } else {
            // Create new
            $finalOutcome = FinalOutcome::create([
                'periode_id' => $this->periodeId,
                'kode_uo' => $kodeUo,
                'sasaran' => $sasaran,
            ]);

            Log::info("Created new Final Outcome", [
                'id' => $finalOutcome->id,
                'kode_uo' => $kodeUo
            ]);

            $this->importedCount++;
        }

        // Save indicators
        $indicatorCount = $this->saveIndikators($finalOutcome, $indikatorKinerja);

        Log::info("Saved {$indicatorCount} indicators for Final Outcome ID: {$finalOutcome->id}");
    }

    /**
     * Get column value with multiple possible keys
     */
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

    /**
     * Parse and save indicators
     */
    protected function saveIndikators($finalOutcome, $indikatorString)
    {
        $savedCount = 0;
        $indikators = [];

        $indikatorString = (string) $indikatorString;

        Log::info('Parsing indicators', [
            'length' => strlen($indikatorString),
            'preview' => substr($indikatorString, 0, 100)
        ]);

        // Split by pipe (|)
        if (strpos($indikatorString, '|') !== false) {
            $indikators = explode('|', $indikatorString);
            Log::info('Split by pipe', ['count' => count($indikators)]);
        }
        // Split by semicolon (;)
        elseif (strpos($indikatorString, ';') !== false) {
            $indikators = explode(';', $indikatorString);
            Log::info('Split by semicolon', ['count' => count($indikators)]);
        }
        // Split by newline
        elseif (strpos($indikatorString, "\n") !== false || strpos($indikatorString, "\r") !== false) {
            $indikators = preg_split('/\r\n|\r|\n/', $indikatorString);
            Log::info('Split by newline', ['count' => count($indikators)]);
        }
        // Use as single indicator
        else {
            $indikators = [$indikatorString];
            Log::info('Using as single indicator');
        }

        // Clean and save each indicator
        foreach ($indikators as $index => $indikator) {
            $indikator = trim($indikator);

            if (strlen($indikator) < 3) {
                continue;
            }

            try {
                $finalOutcome->indicators()->create([
                    'indikator_kinerja' => $indikator,
                ]);
                $savedCount++;

                Log::info("Saved indicator", [
                    'index' => $index + 1,
                    'preview' => substr($indikator, 0, 50) . (strlen($indikator) > 50 ? '...' : '')
                ]);
            } catch (\Exception $e) {
                Log::error("Failed to save indicator", [
                    'index' => $index + 1,
                    'error' => $e->getMessage()
                ]);
            }
        }

        if ($savedCount === 0) {
            Log::warning('No indicators saved', [
                'raw_string' => $indikatorString
            ]);
        }

        return $savedCount;
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'kode_uo' => 'required|string|max:50',
            'sasaran' => 'required|string',
            'indikator_kinerja' => 'required|string',
        ];
    }

    /**
     * Custom validation messages
     */
    public function customValidationMessages()
    {
        return [
            'kode_uo.required' => 'Kode UO wajib diisi pada baris :row',
            'kode_uo.max' => 'Kode UO maksimal 50 karakter pada baris :row',
            'sasaran.required' => 'Sasaran wajib diisi pada baris :row',
            'indikator_kinerja.required' => 'Indikator Kinerja wajib diisi pada baris :row',
        ];
    }

    /**
     * Get imported count
     */
    public function getImportedCount()
    {
        return $this->importedCount + $this->updatedCount;
    }

    /**
     * Get errors
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Heading row index
     */
    public function headingRow(): int
    {
        return 1;
    }
}
