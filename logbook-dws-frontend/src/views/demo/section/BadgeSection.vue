<template>
  <div class="flex flex-row flex-wrap items-center justify-center gap-2">
    <BadgeDefault variant="default">Default Badge</BadgeDefault>
    <BadgeDefault variant="primary">Primary Badge</BadgeDefault>
    <BadgeDefault variant="warning">Warning Badge</BadgeDefault>
    <BadgeDefault variant="success">Success Badge</BadgeDefault>
    <BadgeDefault variant="danger">Danger Badge</BadgeDefault>
    <BadgeDefault variant="info">Info Badge</BadgeDefault>
  </div>
  <div class="flex flex-row flex-wrap items-center justify-center gap-2">
    <BadgeNotification :value="1" variant="default" />
    <BadgeNotification :value="10" variant="primary" />
    <BadgeNotification :value="90" variant="warning" />
    <BadgeNotification :value="99" variant="success" />
    <BadgeNotification :value="100" variant="danger" />
    <BadgeNotification :value="50" variant="info" />
  </div>
</template>

<script setup lang="ts">
import BadgeDefault from '@/components/badge/BadgeDefault.vue'
import BadgeNotification from '@/components/badge/BadgeNotification.vue'
</script>
