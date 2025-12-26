<template>
    <div class="flex flex-col gap-4">
        <!-- Header Section -->
        <div class="flex flex-col gap-4">
            <!-- Title and Add Button -->
            <div class="flex justify-between items-center">
                <div class="text-2xl font-bold">Pengisian Logbook</div>
                <ButtonDefault variant="warning" @click="onAdd">Tambah Aktivitas Harian</ButtonDefault>
            </div>

            <!-- Filter Bar -->
            <div class="flex justify-between items-center">
                <!-- Left side - Month/Date -->
                <div class="flex items-center gap-2 px-3 py-2 border border-gray-300 rounded-lg bg-white">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm">{{ formattedMonth }}</span>
                </div>

                <!-- Right side - Filter, Search -->
                <div class="flex items-center gap-3">
                    <FieldSearch
                        name="field-keywords"
                        placeholder="Cari..."
                        @update:value="onFilterKeywordUpdate"
                    />
                </div>
            </div>
        </div>

        <!-- Content -->
        <SectionDefault :section-status="logbookRecords.status">
            <PengisianTable :records="logbookRecords" @viewClick="onViewClick"/>
        </SectionDefault>
    </div>
</template>

<script setup lang="ts">
import { ButtonDefault, FieldSearch, SectionDefault, type PageDefaultExposed } from '@bssn/ui-kit-frontend';
import { computed, onMounted, onUnmounted, ref, type PropType } from 'vue';

import PengisianDetail from './form/PengisianDetail.vue';
import PengisianTable from './PengisianTable.vue';

import type { LogbookData } from '@/models/pegawai/logbook';
import { useLogbookStore } from '@/stores/pegawai/logbookStore';
import PengisianAdd from './form/PengisianAdd.vue';
import { formatCurrentMonth } from '@/helpers/custom';

const props = defineProps({
    pageDefault: {
        type: Object as PropType<PageDefaultExposed | null>,
        default: null
    }
})

const filterKeyword = ref<string>('')
const currentPage = ref<number>(1)

const formattedMonth = computed(() => formatCurrentMonth())

const onFilterKeywordUpdate = (value: string) => {
    filterKeyword.value = value;
    currentPage.value = 1;
    onLoad();
}

const onViewClick = (record: LogbookData) => {
    props.pageDefault?.openDrawerDefault(
        'Detail Logbook',
        PengisianDetail,
        { record: record }
    )
}

const onAdd = () => {
    props.pageDefault?.openDrawerDefault(
        `Tambah Aktifitas Harian`,
        PengisianAdd
    )
}

const onLoad = async () => {await logbookStore.callRecords(filterKeyword.value, currentPage.value)};

//============================================================================
// Lifecycle Hooks
//============================================================================
const logbookStore = useLogbookStore();
const logbookRecords = computed(() => logbookStore.records);

onMounted(async () => {
    await onLoad();
});

onUnmounted(() => {
    logbookStore.clearRecords();
});

</script>