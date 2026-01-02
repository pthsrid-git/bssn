<template>
  <div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm p-6">
      <h2 class="text-xl font-semibold text-gray-900 mb-2">Laporan Analisis Beban Kerja (ABK)</h2>
      <p class="text-sm text-gray-600">Download laporan ABK berdasarkan periode tahun</p>
    </div>

    <!-- Filter & Download Section -->
    <div class="bg-white rounded-lg shadow-sm p-6">
      <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-end">
        <!-- Year Filter -->
        <div class="flex-1 w-full sm:w-auto">
          <FieldSelect
            :key="selectedYear"
            name="year"
            label="Pilih Tahun"
            :initialValue="selectedYear.toString()"
            :options="yearOptions"
            placeholder="Pilih Tahun"
            :required="false"
            :submitCount="0"
            @update:modelValue="handleYearChange"
          />
        </div>

        <!-- Download Button -->
        <ButtonDownload
          @click="handleDownloadReport"
          :disabled="isDownloading"
        >
          {{ isDownloading ? 'Mengunduh...' : 'Download Laporan ABK' }}
        </ButtonDownload>
      </div>

      <!-- Info Text -->
      <div class="mt-4 flex items-start gap-2 text-sm text-gray-600">
        <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
        </svg>
        <p>
          Laporan akan diunduh dalam format Excel (.xlsx) yang berisi data analisis beban kerja untuk tahun <strong>{{ selectedYear }}</strong>.
        </p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, type PropType } from 'vue';
import { ButtonDownload, FieldSelect, type PageDefaultExposed, type OptionData } from '@bssn/ui-kit-frontend';
import { useLogbookAdminStore } from '@/stores/admin/logbookAdminStore';

defineProps({
  pageDefault: {
    type: Object as PropType<PageDefaultExposed | null>,
    default: null
  }
});

const logbookAdminStore = useLogbookAdminStore();
// Set default to 2025 for testing (change back to new Date().getFullYear() later)
const selectedYear = ref<number>(2025);
const isDownloading = ref<boolean>(false);

// Generate year options for FieldSelect (current year and 5 years back)
const yearOptions = computed<OptionData[]>(() => {
  const currentYear = new Date().getFullYear();
  const options: OptionData[] = [];
  for (let i = 0; i < 6; i++) {
    const year = currentYear - i;
    options.push({
      value: year.toString(),
      label: year.toString()
    });
  }
  return options;
});

const handleYearChange = (value: string) => {
  console.log('Year changed to:', value);
  selectedYear.value = parseInt(value);
  console.log('selectedYear.value is now:', selectedYear.value);
};

const handleDownloadReport = async () => {
  if (isDownloading.value) return;

  console.log('Downloading ABK report for year:', selectedYear.value);
  isDownloading.value = true;
  try {
    await logbookAdminStore.downloadAbkReport(selectedYear.value);
  } catch (error) {
    console.error('Error downloading ABK report:', error);
  } finally {
    isDownloading.value = false;
  }
};

onMounted(() => {
  // Initialize with 2025 for testing (has data)
  selectedYear.value = 2025;
  console.log('Component mounted, selectedYear:', selectedYear.value);
});
</script>
