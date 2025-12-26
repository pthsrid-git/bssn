<template>
    <TableDefault>
        <template #header>
            <tr>
                <TableHeader alignment="center" customClass="w-0">No</TableHeader>
                <TableHeader alignment="center">Aspek Perilaku Kerja</TableHeader>
                <TableHeader alignment="center">Rincian</TableHeader>
                <TableHeader alignment="center">Ekspektasi Khusus Pimpinan</TableHeader>
            </tr>
        </template>
        <template #body>
            <template v-if="skpDetail.perilakuKerja && skpDetail.perilakuKerja.length > 0">
                <tr v-for="(perilaku, idx) in skpDetail.perilakuKerja" :key="idx">
                    <TableData alignment="center" customClass="w-0">{{ idx + 1 }}</TableData>
                    <TableData alignment="left"> {{ perilaku.aspek }} </TableData>
                    <TableData alignment="left">
                        <ul class="list-decimal list-inside space-y-1 text-sm text-gray-700">
                            <li v-for="(rincian, ridx) in perilaku.rincian" :key="ridx">{{ rincian }}</li>
                        </ul>
                    </TableData>
                    <TableData alignment="left">{{ perilaku.ekspektasi }}</TableData>
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
