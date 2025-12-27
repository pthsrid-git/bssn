<template>
  <TableDefault>
    <template #header>
      <tr>
        <TableHeader alignment="center" custom-class="w-16">No</TableHeader>
        <TableHeader>Nama</TableHeader>
        <TableHeader>NIP</TableHeader>
        <TableHeader>Pangkat</TableHeader>
        <TableHeader>Jabatan</TableHeader>
        <TableHeader alignment="center" custom-class="w-32">Aksi</TableHeader>
      </tr>
    </template>
    <template #body>
      <tr v-if="loading">
        <TableDataNone label="Memuat data..." />
      </tr>
      <template v-else-if="teamMembers.length > 0">
        <tr v-for="(member, index) in teamMembers" :key="member.id" class="hover:bg-gray-50">
          <TableData alignment="center">{{ index + 1 }}</TableData>
          <TableData>{{ member.nama }}</TableData>
          <TableData>{{ member.nip }}</TableData>
          <TableData>{{ member.pangkat }}</TableData>
          <TableData>{{ member.jabatan }}</TableData>
          <TableData alignment="center">
            <ButtonOutline variant="info" @click="$emit('viewMemberLogbook', member)">
              Lihat Logbook
            </ButtonOutline>
          </TableData>
        </tr>
      </template>
      <tr v-else>
        <TableDataNone label="Tidak ada data anggota tim" />
      </tr>
    </template>
  </TableDefault>
</template>

<script setup lang="ts">
import { TableDefault, TableHeader, TableData, TableDataNone, ButtonOutline } from '@bssn/ui-kit-frontend';

defineProps<{
  teamMembers: any[];
  loading: boolean;
}>();

defineEmits<{
  viewMemberLogbook: [member: any];
}>();
</script>