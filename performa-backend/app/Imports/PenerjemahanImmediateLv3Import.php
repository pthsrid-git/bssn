<?php

namespace App\Imports;

use App\Models\ImmediateOutcomeLv3;
use App\Models\UnitKerja;
use App\Models\PenerjemahanImmediateLv3;
use App\Models\PenerjemahanImmediateLv3Indicator;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PenerjemahanImmediateLv3Import implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                $immediateOutcomeLv3 = ImmediateOutcomeLv3::where('kode_imm_o_lv3', $row['kode_imm_o_lv3'])
                    ->first();

                if (!$immediateOutcomeLv3) {
                    throw new \Exception("Immediate Outcome Level 3 dengan kode {$row['kode_imm_o_lv3']} tidak ditemukan");
                }

                $unitKerja = UnitKerja::where('kode', $row['kode_unit_kerja'])->first();

                if (!$unitKerja) {
                    throw new \Exception("Unit Kerja dengan kode {$row['kode_unit_kerja']} tidak ditemukan");
                }

                $penerjemahan = PenerjemahanImmediateLv3::create([
                    'immediate_outcome_lv3_id' => $immediateOutcomeLv3->id,
                    'unit_kerja_id' => $unitKerja->id,
                    'tahun' => $row['tahun'],
                ]);

                $indikators = $this->parseMultipleValues($row['iksk']);

                foreach ($indikators as $indikator) {
                    if (!empty(trim($indikator))) {
                        PenerjemahanImmediateLv3Indicator::create([
                            'penerjemahan_immediate_lv3_id' => $penerjemahan->id,
                            'iksk' => trim($indikator),
                        ]);
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("PenerjemahanImmediateLv3 Import failed", ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function rules(): array
    {
        return [
            'kode_imm_o_lv3' => 'required|string|max:40',
            'kode_unit_kerja' => 'required|string|max:10',
            'tahun' => 'required|integer',
            'iksk' => 'required|string',
        ];
    }

    private function parseMultipleValues($value)
    {
        return preg_split('/[|;\n\r]+/', $value);
    }
}
