<?php

namespace App\Imports;

use App\Models\IntermediateOutcomeLv2;
use App\Models\UnitKerja;
use App\Models\PenerjemahanIntermediateLv2;
use App\Models\PenerjemahanIntermediateLv2Indicator;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PenerjemahanIntermediateLv2Import implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                $intermediateOutcomeLv2 = IntermediateOutcomeLv2::where('kode_int_o_lv2', $row['kode_int_o_lv2'])
                    ->first();

                if (!$intermediateOutcomeLv2) {
                    throw new \Exception("Intermediate Outcome Level 2 dengan kode {$row['kode_int_o_lv2']} tidak ditemukan");
                }

                $unitKerja = UnitKerja::where('kode', $row['kode_unit_kerja'])->first();

                if (!$unitKerja) {
                    throw new \Exception("Unit Kerja dengan kode {$row['kode_unit_kerja']} tidak ditemukan");
                }

                $penerjemahan = PenerjemahanIntermediateLv2::create([
                    'intermediate_outcome_lv2_id' => $intermediateOutcomeLv2->id,
                    'unit_kerja_id' => $unitKerja->id,
                    'tahun' => $row['tahun'],
                ]);

                $indikators = $this->parseMultipleValues($row['iksp']);

                foreach ($indikators as $indikator) {
                    if (!empty(trim($indikator))) {
                        PenerjemahanIntermediateLv2Indicator::create([
                            'penerjemahan_intermediate_lv2_id' => $penerjemahan->id,
                            'iksp' => trim($indikator),
                        ]);
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("PenerjemahanIntermediateLv2 Import failed", ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function rules(): array
    {
        return [
            'kode_int_o_lv2' => 'required|string|max:30',
            'kode_unit_kerja' => 'required|string|max:10',
            'tahun' => 'required|integer',
            'iksp' => 'required|string',
        ];
    }

    private function parseMultipleValues($value)
    {
        return preg_split('/[|;\n\r]+/', $value);
    }
}
