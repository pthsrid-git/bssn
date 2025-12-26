<template>
    <PageDefault
        ref="pageDefault"
        pageStatus="success"
        :isViewerFile="true"
        :isDrawerDefault="true"
        :isModalDefault="true"
        :isModalConfirmation="false"
        :isModalAlert="false"
        @confirmModalConfirmation="() => {}"
    >
    <div class="flex flex-col w-full h-full gap-4 xl:gap-6">
        <TitleSection variant="warning">Logbook</TitleSection>
        <TabDefault
            ref="tableDefault"
            :tabs="tabs"
            initial-tab="pengisian"
            :loading="
            logbookRecords.status === 'loading'
            "
        >
        <template #pengisian>
            <PengisianView :pageDefault="pageDefault" />
        </template>
        <template #skp>
            <SkpView :pageDefault="pageDefault" />
        </template>
        </TabDefault>
    </div>
    </PageDefault>
</template>

<script setup lang="ts">
import { PageDefault, TabDefault, TitleSection, type PageDefaultExposed } from '@bssn/ui-kit-frontend';
import { computed, ref } from 'vue';
import { useLogbookStore } from '@/stores/pegawai/logbookStore';
import PengisianView from './pengisian/PengisianView.vue';
import SkpView from './skp/SkpView.vue';

const pageDefault = ref<PageDefaultExposed | null>(null);

const tableDefault = ref(null);

const logbookstore = useLogbookStore();
const logbookRecords = computed(() => logbookstore.records);

const tabs = [
    {
        name: 'pengisian',
        label: 'Pengisian Logbook',
    },
    {
        name: 'skp',
        label: 'SKP',
    }
];
</script>