// stores/logbookStore.ts
import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { LogbookService, type LogbookEntry, type LogbookFilter } from '@/services/logbookService'

export const useLogbookStore = defineStore('logbook', () => {
  // State
  const entries = ref<LogbookEntry[]>([])
  const currentEntry = ref<LogbookEntry | null>(null)
  const loading = ref(false)
  const error = ref<string | null>(null)

  // Pagination
  const currentPage = ref(1)
  const totalPages = ref(1)
  const totalItems = ref(0)
  const perPage = ref(10)

  // Filters
  const filters = ref<LogbookFilter>({})

  // Computed
  const hasEntries = computed(() => entries.value.length > 0)
  const isLoading = computed(() => loading.value)
  const hasError = computed(() => error.value !== null)

  // Statistics
  const approvedCount = computed(() => entries.value.filter((e) => e.status === 'Disetujui').length)
  const pendingCount = computed(() => entries.value.filter((e) => e.status === 'Disubmit').length)
  const draftCount = computed(() => entries.value.filter((e) => e.status === 'Draft').length)
  const rejectedCount = computed(() => entries.value.filter((e) => e.status === 'Ditolak').length)

  // Actions
  const fetchEntries = async (filterOptions?: LogbookFilter) => {
    loading.value = true
    error.value = null

    try {
      const mergedFilters = { ...filters.value, ...filterOptions }
      const response = await LogbookService.getEntries(mergedFilters)

      if (response.success) {
        entries.value = response.data || []
        totalItems.value = entries.value.length
        totalPages.value = Math.ceil(totalItems.value / perPage.value)
      } else {
        throw new Error(response.message || 'Failed to fetch logbook entries')
      }
    } catch (err: any) {
      error.value = err.message || 'Failed to fetch logbook entries'
      console.error('Error fetching logbook entries:', err)
    } finally {
      loading.value = false
    }
  }

  const fetchEntryById = async (id: number) => {
    loading.value = true
    error.value = null

    try {
      const response = await LogbookService.getEntryById(id)

      if (response.success) {
        currentEntry.value = response.data
      } else {
        throw new Error(response.message || 'Failed to fetch logbook entry')
      }
    } catch (err: any) {
      error.value = err.message || 'Failed to fetch logbook entry'
      console.error('Error fetching logbook entry:', err)
    } finally {
      loading.value = false
    }
  }

  const createEntry = async (data: any) => {
    loading.value = true
    error.value = null

    try {
      const response = await LogbookService.createEntry(data)

      if (response.success) {
        const newEntry = response.data
        entries.value.unshift(newEntry)
        totalItems.value = entries.value.length
        return newEntry
      } else {
        throw new Error(response.message || 'Failed to create logbook entry')
      }
    } catch (err: any) {
      error.value = err.message || 'Failed to create logbook entry'
      console.error('Error creating logbook entry:', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  const updateEntry = async (id: number, data: any) => {
    loading.value = true
    error.value = null

    try {
      const response = await LogbookService.updateEntry(id, data)

      if (response.success) {
        const updatedEntry = response.data
        const index = entries.value.findIndex((e) => e.id === id)
        if (index !== -1) {
          entries.value[index] = updatedEntry
        }

        if (currentEntry.value && currentEntry.value.id === id) {
          currentEntry.value = updatedEntry
        }

        return updatedEntry
      } else {
        throw new Error(response.message || 'Failed to update logbook entry')
      }
    } catch (err: any) {
      error.value = err.message || 'Failed to update logbook entry'
      console.error('Error updating logbook entry:', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  const deleteEntry = async (id: number) => {
    loading.value = true
    error.value = null

    try {
      const response = await LogbookService.deleteEntry(id)

      if (response.success) {
        entries.value = entries.value.filter((e) => e.id !== id)
        totalItems.value = entries.value.length

        if (currentEntry.value && currentEntry.value.id === id) {
          currentEntry.value = null
        }
      } else {
        throw new Error(response.message || 'Failed to delete logbook entry')
      }
    } catch (err: any) {
      error.value = err.message || 'Failed to delete logbook entry'
      console.error('Error deleting logbook entry:', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  const submitEntry = async (id: number) => {
    loading.value = true
    error.value = null

    try {
      const response = await LogbookService.submitEntry(id)

      if (response.success) {
        const submittedEntry = response.data
        const index = entries.value.findIndex((e) => e.id === id)
        if (index !== -1) {
          entries.value[index] = submittedEntry
        }
        return submittedEntry
      } else {
        throw new Error(response.message || 'Failed to submit logbook entry')
      }
    } catch (err: any) {
      error.value = err.message || 'Failed to submit logbook entry'
      console.error('Error submitting logbook entry:', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  const setFilters = (newFilters: Partial<LogbookFilter>) => {
    filters.value = { ...filters.value, ...newFilters }
  }

  const clearFilters = () => {
    filters.value = {}
  }

  const goToPage = (page: number) => {
    currentPage.value = page
  }

  const setPerPage = (count: number) => {
    perPage.value = count
    currentPage.value = 1
  }

  const clearError = () => {
    error.value = null
  }

  const downloadFile = async (fileUrl: string) => {
    try {
      await LogbookService.downloadFile(fileUrl)
    } catch (err: any) {
      error.value = err.message || 'Failed to download file'
      throw err
    }
  }

  return {
    // State
    entries,
    currentEntry,
    loading,
    error,
    currentPage,
    totalPages,
    totalItems,
    perPage,
    filters,

    // Computed
    hasEntries,
    isLoading,
    hasError,
    approvedCount,
    pendingCount,
    draftCount,
    rejectedCount,

    // Actions
    fetchEntries,
    fetchEntryById,
    createEntry,
    updateEntry,
    deleteEntry,
    submitEntry,
    setFilters,
    clearFilters,
    goToPage,
    setPerPage,
    clearError,
    downloadFile
  }
})
