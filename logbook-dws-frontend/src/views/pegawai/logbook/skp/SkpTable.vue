<template>
    <div class="space-y-4">
        <!-- Loading State -->
        <SectionDefault v-if="isLoading" :section-status="'loading'">
            <StateLoading info="Memuat data..." />
        </SectionDefault>

        <!-- Table -->
        <SectionDefault v-else :section-status="'success'">
            <TableDefault>
                <template #header>
                    <tr>
                        <TableHeader alignment="center" customClass="w-0">No</TableHeader>
                        <TableHeader alignment="center">Periode</TableHeader>
                        <TableHeader alignment="center">Pendekatan</TableHeader>
                        <TableHeader alignment="center">Unit Kerja</TableHeader>
                        <TableHeader alignment="center">Status Pegawai</TableHeader>
                        <TableHeader alignment="center">Status</TableHeader>
                        <TableHeader alignment="center">Jabatan</TableHeader>
                        <TableHeader alignment="center">Aksi</TableHeader>
                    </tr>
                </template>
                <template #body>
                    <template v-if="skpData.length > 0">
                        <tr v-for="(skp, index) in skpData" :key="skp.id">
                            <TableData alignment="center" customClass="w-0">{{ index + 1 }}</TableData>
                            <TableData alignment="center">{{ skp.periode }}</TableData>
                            <TableData alignment="center">
                                <BadgeDefault variant="info" customClass="opacity-80 cursor-not-allowed">
                                    {{ skp.pendekatan }}
                                </BadgeDefault>
                            </TableData>
                            <TableData alignment="left">{{ skp.unitKerja }}</TableData>
                            <TableData alignment="center">{{ skp.statusPegawai }}</TableData>
                            <TableData alignment="center">
                                <BadgeDefault variant="success" customClass="opacity-80 cursor-not-allowed">
                                    {{ skp.status }}
                                </BadgeDefault>
                            </TableData>
                            <TableData alignment="left">{{ skp.jabatan }}</TableData>
                            <TableData alignment="center">
                                <ButtonOutline variant="warning" @click="$emit('viewDetail', skp.id)">Lihat Detail</ButtonOutline>
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
        </SectionDefault>
    </div>
</template>

<script setup lang="ts">
import {
    SectionDefault,
    TableDefault,
    TableHeader,
    TableData,
    TableDataNone,
    BadgeDefault,
    ButtonOutline,
    StateLoading
} from '@bssn/ui-kit-frontend'

defineProps({
    skpData: {
        type: Array,
        required: true
    },
    isLoading: {
        type: Boolean,
        default: false
    }
})

defineEmits(['viewDetail'])
</script>
