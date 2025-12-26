<template>
  <div class="flex flex-col w-full gap-4 mt-4 xl:gap-6">
    <div class="font-semibold">
      Detail Pendapatan {{ monthNames[parseInt(record.data!.periode.split('-')[1], 10) - 1] }}
      {{ record.data!.periode.split('-')[0] }}
    </div>
    <CardDefault backgroundClass="bg-gray-50">
      <div class="grid grid-cols-1 gap-4 xl:gap-x-6 xl:grid-cols-2">
        <EntryDefault label="Nama">
          {{ record.data!.nama }}
        </EntryDefault>
        <EntryDefault label="NIP">
          {{ record.data!.nip }}
        </EntryDefault>
        <EntryDefault label="Pangkat / Golongan">
          {{ record.data!.pangkatGolongan }}
        </EntryDefault>
        <EntryDefault label="Jabatan">
          {{ record.data!.jabatan }}
        </EntryDefault>
        <EntryDefault label="Status Pernikahan">
          {{ record.data!.statusPernikahan }}
        </EntryDefault>
      </div>
    </CardDefault>
    <TableDefault>
      <template #header>
        <tr>
          <TableHeader alignment="center" customClass="w-0">No</TableHeader>
          <TableHeader alignment="center">Jenis</TableHeader>
          <TableHeader alignment="center">Nominal</TableHeader>
        </tr>
      </template>
      <template #body>
        <tr>
          <TableData alignment="center" customClass="w-0"> 1 </TableData>
          <TableData customClass="w-0 text-nowrap"> Detail Gaji </TableData>
          <TableData>
            <div class="flex flex-col w-full gap-4 xl:gap-6">
              <div
                class="flex items-center justify-between w-full min-h-8 min-w-[450px] cursor-pointer"
                @click="gajiToggle = !gajiToggle"
              >
                <div>Gaji Diterima {{ formatRupiah(record.data!.gaji.gajiDiterima) }}</div>
                <ButtonLink>
                  <div class="flex flex-row items-center gap-2">
                    <div>Lihat Detail</div>
                    <font-awesome-icon
                      v-if="gajiToggle"
                      :icon="['fas', 'angle-down']"
                      class="w-3.5 h-3.5 text-warning-500"
                    />
                    <font-awesome-icon
                      v-else
                      :icon="['fas', 'angle-up']"
                      class="w-3.5 h-3.5 text-warning-500"
                    />
                  </div>
                </ButtonLink>
              </div>
              <div v-if="gajiToggle" class="flex flex-col gap-3 divide-gray-200 divide-y-1">
                <div class="flex items-center justify-between w-full min-h-8 min-w-[450px] pb-3">
                  <div>Gaji Pokok</div>
                  <div>{{ formatRupiah(record.data!.gaji.gajiPokok) }}</div>
                </div>
                <div class="flex items-center justify-between w-full min-h-8 min-w-[450px] pb-3">
                  <div>Tunjangan Istri</div>
                  <div>{{ formatRupiah(record.data!.gaji.tunjanganIstri) }}</div>
                </div>
                <div class="flex items-center justify-between w-full min-h-8 min-w-[450px] pb-3">
                  <div>Tunjangan Anak</div>
                  <div>{{ formatRupiah(record.data!.gaji.tunjanganAnak) }}</div>
                </div>
                <div class="flex items-center justify-between w-full min-h-8 min-w-[450px] pb-3">
                  <div>Tunjangan Umum PNS</div>
                  <div>{{ formatRupiah(record.data!.gaji.tunjanganUmumPns) }}</div>
                </div>
                <div class="flex items-center justify-between w-full min-h-8 min-w-[450px] pb-3">
                  <div>Tunjangan Struktural</div>
                  <div>{{ formatRupiah(record.data!.gaji.tunjanganStruktural) }}</div>
                </div>
                <div class="flex items-center justify-between w-full min-h-8 min-w-[450px] pb-3">
                  <div>Tunjangan Fungsional</div>
                  <div>{{ formatRupiah(record.data!.gaji.tunjanganFungsional) }}</div>
                </div>
                <div class="flex items-center justify-between w-full min-h-8 min-w-[450px] pb-3">
                  <div>Tunjangan Daerah</div>
                  <div>{{ formatRupiah(record.data!.gaji.tunjanganDaerah) }}</div>
                </div>
                <div class="flex items-center justify-between w-full min-h-8 min-w-[450px] pb-3">
                  <div>Tunjangan Daerah Terpencil</div>
                  <div>{{ formatRupiah(record.data!.gaji.tunjanganDaerahTerpencil) }}</div>
                </div>
                <div class="flex items-center justify-between w-full min-h-8 min-w-[450px] pb-3">
                  <div>Tunjangan Lain</div>
                  <div>{{ formatRupiah(record.data!.gaji.tunjanganLain) }}</div>
                </div>
                <div class="flex items-center justify-between w-full min-h-8 min-w-[450px] pb-3">
                  <div>TPP</div>
                  <div>{{ formatRupiah(record.data!.gaji.tpp) }}</div>
                </div>
                <div class="flex items-center justify-between w-full min-h-8 min-w-[450px] pb-3">
                  <div>Pembulatan</div>
                  <div>{{ formatRupiah(record.data!.gaji.pembulatan) }}</div>
                </div>
                <div class="flex items-center justify-between w-full min-h-8 min-w-[450px] pb-3">
                  <div>Tunjangan Beras</div>
                  <div>{{ formatRupiah(record.data!.gaji.tunjanganBeras) }}</div>
                </div>
                <div class="flex items-center justify-between w-full min-h-8 min-w-[450px] pb-3">
                  <div>Tunjangan PPH</div>
                  <div>{{ formatRupiah(record.data!.gaji.tunjanganPph) }}</div>
                </div>
                <div class="flex items-center justify-between w-full min-h-8 min-w-[450px] pb-3">
                  <div>Potongan Iuran Wajib PNS</div>
                  <div>{{ formatRupiah(record.data!.gaji.potonganIuranWajibPns) }}</div>
                </div>
                <div class="flex items-center justify-between w-full min-h-8 min-w-[450px] pb-3">
                  <div>Potongan PPH</div>
                  <div>{{ formatRupiah(record.data!.gaji.potonganPph) }}</div>
                </div>
                <div class="flex items-center justify-between w-full min-h-8 min-w-[450px] pb-3">
                  <div>Potongan Tabungan Rumah</div>
                  <div>{{ formatRupiah(record.data!.gaji.potonganTabunganRumah) }}</div>
                </div>
                <div class="flex items-center justify-between w-full min-h-8 min-w-[450px] pb-3">
                  <div>Potongan BPJS</div>
                  <div>{{ formatRupiah(record.data!.gaji.potonganBpjs) }}</div>
                </div>
                <div class="flex items-center justify-between w-full min-h-8 min-w-[450px] pb-3">
                  <div>Potongan BPJS Keluarga Lainnya</div>
                  <div>{{ formatRupiah(record.data!.gaji.potonganBpjsKeluargaLainnya) }}</div>
                </div>
              </div>
            </div>
          </TableData>
        </tr>
        <tr>
          <TableData alignment="center" customClass="w-0"> 2 </TableData>
          <TableData customClass="w-0 text-nowrap"> Detail Tunjangan Kinerja </TableData>
          <TableData>
            <div class="flex flex-col w-full gap-4 xl:gap-6">
              <div
                class="flex items-center justify-between w-full min-h-8 min-w-[450px] cursor-pointer"
                @click="tunjanganKinerjaToggle = !tunjanganKinerjaToggle"
              >
                <div>
                  Tunjangan Kinerja Diterima
                  {{ formatRupiah(record.data!.tunjanganKinerja.tunjanganKinerjaDiterima) }}
                </div>
                <ButtonLink>
                  <div class="flex flex-row items-center gap-2">
                    <div>Lihat Detail</div>
                    <font-awesome-icon
                      v-if="tunjanganKinerjaToggle"
                      :icon="['fas', 'angle-down']"
                      class="w-3.5 h-3.5 text-warning-500"
                    />
                    <font-awesome-icon
                      v-else
                      :icon="['fas', 'angle-up']"
                      class="w-3.5 h-3.5 text-warning-500"
                    />
                  </div>
                </ButtonLink>
              </div>
              <div
                v-if="tunjanganKinerjaToggle"
                class="flex flex-col gap-3 divide-gray-200 divide-y-1"
              >
                <div class="flex items-center justify-between w-full min-h-8 min-w-[450px] pb-3">
                  <div></div>
                  <div></div>
                </div>
              </div>
            </div>
          </TableData>
        </tr>
        <tr>
          <TableData alignment="center" customClass="w-0"> 3 </TableData>
          <TableData customClass="w-0 text-nowrap"> Detail Tunjangan Makan </TableData>
          <TableData>
            <div class="flex flex-col w-full gap-4 xl:gap-6">
              <div
                class="flex items-center justify-between w-full min-h-8 min-w-[450px] cursor-pointer"
                @click="tunjanganMakanToggle = !tunjanganMakanToggle"
              >
                <div>
                  Tunjangan Makan Diterima
                  {{ formatRupiah(record.data!.tunjanganMakan.tunjanganMakanDiterima) }}
                </div>
                <ButtonLink>
                  <div class="flex flex-row items-center gap-2">
                    <div>Lihat Detail</div>
                    <font-awesome-icon
                      v-if="tunjanganMakanToggle"
                      :icon="['fas', 'angle-down']"
                      class="w-3.5 h-3.5 text-warning-500"
                    />
                    <font-awesome-icon
                      v-else
                      :icon="['fas', 'angle-up']"
                      class="w-3.5 h-3.5 text-warning-500"
                    />
                  </div>
                </ButtonLink>
              </div>
              <div
                v-if="tunjanganMakanToggle"
                class="flex flex-col gap-3 divide-gray-200 divide-y-1"
              >
                <div class="flex items-center justify-between w-full min-h-8 min-w-[450px] pb-3">
                  <div></div>
                  <div></div>
                </div>
              </div>
            </div>
          </TableData>
        </tr>
        <tr>
          <TableData alignment="center" customClass="w-0"> 4 </TableData>
          <TableData customClass="w-0 text-nowrap"> Detail Potongan </TableData>
          <TableData>
            <div class="flex flex-col w-full gap-4 xl:gap-6">
              <div
                class="flex items-center justify-between w-full min-h-8 min-w-[450px] cursor-pointer"
                @click="potonganToggle = !potonganToggle"
              >
                <div>
                  Jumlah Potongan
                  {{ formatRupiah(record.data!.potongan.jumlahPotongan) }}
                </div>
                <ButtonLink>
                  <div class="flex flex-row items-center gap-2">
                    <div>Lihat Detail</div>
                    <font-awesome-icon
                      v-if="potonganToggle"
                      :icon="['fas', 'angle-down']"
                      class="w-3.5 h-3.5 text-warning-500"
                    />
                    <font-awesome-icon
                      v-else
                      :icon="['fas', 'angle-up']"
                      class="w-3.5 h-3.5 text-warning-500"
                    />
                  </div>
                </ButtonLink>
              </div>
              <div v-if="potonganToggle" class="flex flex-col gap-3 divide-gray-200 divide-y-1">
                <div class="flex items-center justify-between w-full min-h-8 min-w-[450px] pb-3">
                  <div></div>
                  <div></div>
                </div>
              </div>
            </div>
          </TableData>
        </tr>
        <tr>
          <TableData :colspan="2" customClass="w-0">
            <div class="font-semibold">Pendapatan Bersih</div>
          </TableData>
          <TableData>
            <div class="flex flex-col w-full gap-2">
              <div class="font-semibold">{{ formatRupiah(record.data!.pendapatanBersih) }}</div>
              <div class="flex flex-row items-start gap-2">
                <div class="text-nowrap">Terbilang :</div>
                <div class="font-semibold">
                  {{ spellout(record.data!.pendapatanBersih) }}
                </div>
              </div>
            </div>
          </TableData>
        </tr>
      </template>
    </TableDefault>
  </div>
</template>

<script setup lang="ts">
import {
  ButtonLink,
  CardDefault,
  EntryDefault,
  formatRupiah,
  monthNames,
  TableData,
  TableDefault,
  TableHeader,
  type RequestState
} from '@bssn/ui-kit-frontend'
import { ref, type PropType } from 'vue'

import { spellout } from '@/helpers/custom'
import type { PendapatanData } from '@/models/pegawai/pendapatan'

// ================================================================================================
// Props & Emits
// ================================================================================================
defineProps({
  record: {
    type: Object as PropType<RequestState<PendapatanData>>,
    required: true
  }
})

const gajiToggle = ref<Boolean>(false)
const tunjanganKinerjaToggle = ref<Boolean>(false)
const tunjanganMakanToggle = ref<Boolean>(false)
const potonganToggle = ref<Boolean>(false)
</script>
