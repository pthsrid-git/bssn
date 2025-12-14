<?php

namespace App\Imports;

use App\Models\IntermediateOutcomeLv1;
use App\Models\IntermediateOutcomeLv2;
use App\Models\IntermediateOutcomeLv2Indicator;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IntermediateOutcomeLv2Import implements ToCollection, WithHeadingRow, WithValidation
{
    protected $periodeId;

    public function __construct($periodeId)
    {
        $this->periodeId = $periodeId;
    }

    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                $intermediateOutcomeLv1 = IntermediateOutcomeLv1::where('kode_int_o_lv1', $row['kode_int_o_lv1'])
                    ->whereHas('finalOutcome', function ($q) {
                        $q->where('periode_id', $this->periodeId);
                    })
                    ->first();

                if (!$intermediateOutcomeLv1) {
                    throw new \Exception("Intermediate Outcome Level 1 dengan kode {$row['kode_int_o_lv1']} tidak ditemukan untuk periode ini");
                }

                $intermediateOutcome = IntermediateOutcomeLv2::create([
                    'intermediate_outcome_lv1_id' => $intermediateOutcomeLv1->id,
                    'kode_uo' => $row['kode_uo'],
                    'kode_int_o_lv1' => $row['kode_int_o_lv1'],
                    'kode_int_o_lv2' => $row['kode_int_o_lv2'],
                    'sasaran_program' => $row['sasaran_program'],
                ]);

                $indikators = $this->parseMultipleValues($row['iksp']);

                foreach ($indikators as $indikator) {
                    if (!empty(trim($indikator))) {
                        IntermediateOutcomeLv2Indicator::create([
                            'intermediate_outcome_lv2_id' => $intermediateOutcome->id,
                            'iksp' => trim($indikator),
                        ]);
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("IntermediateOutcomeLv2 Import failed", ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function rules(): array
    {
        return [
            'kode_uo' => 'required|string|max:10',
            'kode_int_o_lv1' => 'required|string|max:20',
            'kode_int_o_lv2' => 'required|string|max:30',
            'sasaran_program' => 'required|string',
            'iksp' => 'required|string',
        ];
    }

    private function parseMultipleValues($value)
    {
        return preg_split('/[|;\n\r]+/', $value);
    }
}
