<template>
    <div class="grid grid-cols-3 gap-6">
        <!-- Dukungan Sumber Daya Table -->
        <div>
            <TableDefault>
                <template #header>
                    <tr>
                        <TableHeader alignment="center">Dukungan Sumber Daya</TableHeader>
                    </tr>
                </template>
                <template #body>
                    <template v-if="skpDetail.lampiran && skpDetail.lampiran.dukunganSumberDaya && skpDetail.lampiran.dukunganSumberDaya.length > 0">
                        <tr v-for="(item, idx) in skpDetail.lampiran.dukunganSumberDaya" :key="idx">
                            <TableData alignment="left">{{ item.nama }}</TableData>
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

        <!-- Pengembangan Table -->
        <div>
            <TableDefault>
                <template #header>
                    <tr>
                        <TableHeader alignment="center">Pengembangan</TableHeader>
                    </tr>
                </template>
                <template #body>
                    <template v-if="skpDetail.lampiran && skpDetail.lampiran.pengembangan && skpDetail.lampiran.pengembangan.length > 0">
                        <tr v-for="(item, idx) in skpDetail.lampiran.pengembangan" :key="idx">
                            <TableData alignment="left">{{ item.nama }}</TableData>
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

        <!-- Penghargaan Table -->
        <div>
            <TableDefault>
                <template #header>
                    <tr>
                        <TableHeader alignment="center">Penghargaan</TableHeader>
                    </tr>
                </template>
                <template #body>
                    <template v-if="skpDetail.lampiran && skpDetail.lampiran.penghargaan && skpDetail.lampiran.penghargaan.length > 0">
                        <tr v-for="(item, idx) in skpDetail.lampiran.penghargaan" :key="idx">
                            <TableData alignment="left">{{ item.nama }}</TableData>
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
    </div>
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