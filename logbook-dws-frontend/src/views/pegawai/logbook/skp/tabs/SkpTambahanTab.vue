<template>
    <TableDefault>
        <template #header>
            <tr>
                <TableHeader alignment="center" customClass="w-0">No</TableHeader>
                <TableHeader alignment="center">Rencana Hasil Kerja Pimpinan yang Diintervensi</TableHeader>
                <TableHeader alignment="center">Rencana Hasil Kerja</TableHeader>
                <TableHeader alignment="center">Aspek</TableHeader>
                <TableHeader alignment="center">Indikator Kinerja Individu</TableHeader>
                <TableHeader alignment="center">Target Tahunan</TableHeader>
            </tr>
        </template>
        <template #body>
            <template v-if="skpDetail && skpDetail.hasilKerjaTambahan && skpDetail.hasilKerjaTambahan.length > 0">
                <tr v-for="(hasil, idx) in skpDetail.hasilKerjaTambahan" :key="idx">
                    <TableData alignment="center" customClass="w-0">{{ idx + 1 }}</TableData>
                    <TableData alignment="left">{{ hasil.rencanaPimpinan }}</TableData>
                    <TableData alignment="left">{{ hasil.rencanaHasil }}</TableData>
                    <TableData alignment="left"><BadgeDefault variant="warning">{{ hasil.aspek }}</BadgeDefault></TableData>
                    <TableData alignment="left">{{ hasil.indikator }}</TableData>
                    <TableData alignment="center"><BadgeDefault variant="info">{{ hasil.target }}</BadgeDefault></TableData>
                </tr>
            </template>
            <template v-else>
                <tr>
                    <TableDataNone />
                </tr>
            </template>
        </template>
    </TableDefault>
</template>

<script setup lang="ts">
import { 
    TableDefault,
    TableHeader,
    TableData,
    TableDataNone,
    BadgeDefault
} from '@bssn/ui-kit-frontend'

defineProps({
    skpDetail: {
        type: Object,
        required: true
    }
})
</script>
