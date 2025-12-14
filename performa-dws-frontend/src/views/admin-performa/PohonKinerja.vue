<template>
  <div class="p-6 space-y-6">
    <!-- Tabs Navigation -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
      <div class="border-b border-gray-200 overflow-x-auto">
        <nav class="flex min-w-max" aria-label="Tabs">
          <button
            v-for="tab in tabs"
            :key="tab.id"
            @click="activeTab = tab.id"
            :class="[
              'px-6 py-3 text-sm font-medium border-b-2 transition-colors whitespace-nowrap',
              activeTab === tab.id
                ? 'border-[#FBC143] text-[#19203B] bg-yellow-50'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
            ]"
          >
            {{ tab.label }}
          </button>
        </nav>
      </div>

      <!-- Tab Content -->
      <div class="p-6">
        <!-- Final Outcome Tab -->
        <div v-if="activeTab === 'final-outcome'">
          <FinalOutcomeTab />
        </div>

        <!-- Intermediate Outcome LV1 Tab -->
        <div v-else-if="activeTab === 'intermediate-lv1'">
          <IntermediateLv1Tab />
        </div>

        <!-- Intermediate Outcome LV2 Tab -->
        <div v-else-if="activeTab === 'intermediate-lv2'">
          <IntermediateLv2Tab />
        </div>

        <!-- Immediate Outcome LV3 Tab -->
        <div v-else-if="activeTab === 'immediate-lv3'">
          <ImmediateLv3Tab />
        </div>

        <!-- Output Tab -->
        <div v-else-if="activeTab === 'output'">
          <OutputTab />
        </div>

        <!-- Penerjemahan Intermediate LV2 Tab -->
        <div v-else-if="activeTab === 'penerjemahan-lv2'">
          <PenerjemahanLv2Tab />
        </div>

        <!-- Penerjemahan Immediate LV3 Tab -->
        <div v-else-if="activeTab === 'penerjemahan-lv3'">
          <PenerjemahanLv3Tab />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import FinalOutcomeTab from './pohon-kinerja/FinalOutcomeTab.vue'
import IntermediateLv1Tab from './pohon-kinerja/IntermediateLv1Tab.vue'
import IntermediateLv2Tab from './pohon-kinerja/IntermediateLv2Tab.vue'
import ImmediateLv3Tab from './pohon-kinerja/ImmediateLv3Tab.vue'
import OutputTab from './pohon-kinerja/OutputTab.vue'
import PenerjemahanLv2Tab from './pohon-kinerja/PenerjemahanLv2Tab.vue'
import PenerjemahanLv3Tab from './pohon-kinerja/PenerjemahanLv3Tab.vue'

const activeTab = ref('final-outcome')

const tabs = [
  { id: 'final-outcome', label: 'Final Outcome' },
  { id: 'intermediate-lv1', label: 'Intermediate Outcome LV1' },
  { id: 'intermediate-lv2', label: 'Intermediate Outcome LV2' },
  { id: 'immediate-lv3', label: 'Immediate Outcome LV3' },
  { id: 'output', label: 'Output' },
  { id: 'penerjemahan-lv2', label: 'Penerjemahan Intermediate LV2' },
  { id: 'penerjemahan-lv3', label: 'Penerjemahan Immediate LV3' }
]
</script>