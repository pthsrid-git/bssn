<template>
  <div class="flex flex-col items-center justify-center gap-2">
    <StateUnderConstruction />
    <StateLoading />
    <StateError message="Lorem Ipsum Dolor Sit Amet" />
    <StateInfo message="Lorem Ipsum Dolor Sit Amet" />
    <StateWarning message="Lorem Ipsum Dolor Sit Amet" />
    <StateResponseError message="Lorem Ipsum Dolor Sit Amet" />
    <StateResponseInfo message="Lorem Ipsum Dolor Sit Amet" />
    <StateResponseWarning message="Lorem Ipsum Dolor Sit Amet" />
  </div>
</template>

<script setup lang="ts">
import StateError from '@/components/state/StateError.vue'
import StateInfo from '@/components/state/StateInfo.vue'
import StateLoading from '@/components/state/StateLoading.vue'
import StateResponseError from '@/components/state/StateResponseError.vue'
import StateResponseInfo from '@/components/state/StateResponseInfo.vue'
import StateResponseWarning from '@/components/state/StateResponseWarning.vue'
import StateUnderConstruction from '@/components/state/StateUnderConstruction.vue'
import StateWarning from '@/components/state/StateWarning.vue'
</script>
