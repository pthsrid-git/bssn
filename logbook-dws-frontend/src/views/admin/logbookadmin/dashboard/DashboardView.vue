<template>
  <div>
    <!-- Show Dashboard (Pegawai List) -->
    <DashboardContent
      v-if="!selectedPegawai"
      @viewPegawai="handleViewPegawai"
    />

    <!-- Show Pegawai Logbook Detail -->
    <DashboardPegawaiDetail
      v-else
      :pegawai="selectedPegawai"
      :pageDefault="pageDefault"
      @back="backToPegawaiList"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, type PropType } from 'vue';
import { type PageDefaultExposed } from '@bssn/ui-kit-frontend';
import DashboardContent from './DashboardContent.vue';
import DashboardPegawaiDetail from './DashboardPegawaiDetail.vue';
import type { PegawaiAdminData } from '@/models/admin/logbookAdmin';
import { useLogbookAdminStore } from '@/stores/admin/logbookAdminStore';

const props = defineProps({
  pageDefault: {
    type: Object as PropType<PageDefaultExposed | null>,
    default: null
  }
});

const logbookAdminStore = useLogbookAdminStore();
const selectedPegawai = ref<PegawaiAdminData | null>(null);

const handleViewPegawai = (pegawai: PegawaiAdminData) => {
  selectedPegawai.value = pegawai;
};

const backToPegawaiList = () => {
  selectedPegawai.value = null;
  logbookAdminStore.clearPegawaiLogs();
};
</script>
