<template>
  <div class="flex flex-col w-full gap-4">
    <TableDefault>
      <template #header>
        <tr>
          <TableHeader alignment="center" customClass="w-0">No</TableHeader>
          <TableHeader alignment="center">Bulan</TableHeader>
          <TableHeader alignment="center">Gaji</TableHeader>
          <TableHeader alignment="center">Tunjangan Kinerja</TableHeader>
          <TableHeader alignment="center">Tunjangan Makan</TableHeader>
          <TableHeader alignment="center">Potongan</TableHeader>
        </tr>
      </template>
      <template #body>
        <template v-if="records.length > 0">
          <tr v-for="(record, recordIndex) in records" :key="recordIndex">
            <TableData alignment="center" customClass="w-0">
              {{ recordIndex + 1 }}
            </TableData>
            <TableData>
              {{ record.waktu }}
            </TableData>
            <TableData alignment="center">
              <div class="flex items-center">
                <font-awesome-icon
                  v-if="record.gaji"
                  :icon="['fad', 'circle-check']"
                  class="w-4 h-4 mr-1.5 text-green-500"
                />
                <font-awesome-icon
                  v-else
                  :icon="['fad', 'times-circle']"
                  class="w-4 h-4 mr-1.5 text-danger-500"
                />
                {{ record.gaji }}
              </div>
            </TableData>
            <TableData alignment="center">
              <div class="flex items-center">
                <font-awesome-icon
                  v-if="record.tunkin"
                  :icon="['fad', 'circle-check']"
                  class="w-4 h-4 mr-1.5 text-green-500"
                />
                <font-awesome-icon
                  v-else
                  :icon="['fad', 'times-circle']"
                  class="w-4 h-4 mr-1.5 text-danger-500"
                />
                {{ record.tunkin }}
              </div>
            </TableData>
            <TableData alignment="center">
              <div class="flex items-center">
                <font-awesome-icon
                  v-if="record.tunmak"
                  :icon="['fad', 'circle-check']"
                  class="w-4 h-4 mr-1.5 text-green-500"
                />
                <font-awesome-icon
                  v-else
                  :icon="['fad', 'times-circle']"
                  class="w-4 h-4 mr-1.5 text-danger-500"
                />
                {{ record.tunmak }}
              </div>
            </TableData>
            <TableData alignment="center">
              <div class="flex items-center">
                <font-awesome-icon
                  v-if="record.potongan"
                  :icon="['fad', 'circle-check']"
                  class="w-4 h-4 mr-1.5 text-green-500"
                />
                <font-awesome-icon
                  v-else
                  :icon="['fad', 'times-circle']"
                  class="w-4 h-4 mr-1.5 text-danger-500"
                />
                {{ record.potongan }}
              </div>
            </TableData>
          </tr>
        </template>
        <template v-else>
          <tr>
            <TableDataNone />
          </tr>
        </template>
      </template>
    </TableDefault>
  </div>
</template>

<script setup lang="ts">
import { TableData, TableDataNone, TableDefault, TableHeader } from '@bssn/ui-kit-frontend'
import type { PropType } from 'vue'

import type { PeriodeData } from '@/models/periode'

// ================================================================================================
// Props & Emits
// ================================================================================================
defineProps({
  records: {
    type: Array as PropType<PeriodeData[]>,
    required: true
  }
})
</script>
