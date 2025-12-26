<template>
  <CardDefault v-if="wrapper" class="w-full h-full">
    <div class="flex flex-col w-full h-full gap-4">
      <div class="flex items-center justify-between gap-4">
        <h1 class="font-semibold text-gray-900 line-clamp-1">{{ displayTitle }}</h1>
        <ButtonClose @click="close" />
      </div>

      <div class="flex-1 w-full h-full">
        <div
          v-if="isVideo"
          class="flex flex-row items-center justify-center w-full h-[calc(100vh-10.75rem)] xl:h-[calc(100vh-12.75rem)] bg-black"
        >
          <video :src="url" controls preload="metadata" class="w-full h-full"></video>
        </div>

        <div
          v-else-if="isYouTube"
          class="flex flex-row items-center justify-center w-full h-[calc(100vh-10.75rem)] xl:h-[calc(100vh-12.75rem)] bg-black"
        >
          <iframe
            class="w-full h-full aspect-video"
            :src="youTubeEmbedUrl"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen
          ></iframe>
        </div>

        <div
          v-else-if="isImage"
          class="flex flex-row items-center justify-center w-full h-[calc(100vh-10.75rem)] xl:h-[calc(100vh-12.75rem)] bg-black"
        >
          <img :src="url" alt="Preview" class="object-contain w-full h-full" />
        </div>

        <embed
          v-else
          class="w-full h-[calc(100vh-10.75rem)] xl:h-[calc(100vh-12.75rem)]"
          :src="url"
          type="application/pdf"
          width="100%"
          height="100%"
        />

        <!-- <div
          v-else
          class="flex flex-row items-center justify-center w-full h-[calc(100vh-10.75rem)] xl:h-[calc(100vh-12.75rem)] text-gray-500"
        >
          <StateWarning message="Tipe file tidak diketahui" />
        </div> -->
      </div>
    </div>
  </CardDefault>

  <template v-else>
    <div v-if="isVideo" class="flex flex-row items-center justify-center w-full h-[70vh] bg-black">
      <video class="w-full h-full" :src="url" controls preload="metadata"></video>
    </div>

    <div
      v-else-if="isYouTube"
      class="flex flex-row items-center justify-center w-full h-[70vh] bg-black"
    >
      <iframe
        class="w-full h-full aspect-video"
        :src="youTubeEmbedUrl"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        allowfullscreen
      ></iframe>
    </div>

    <div
      v-else-if="isImage"
      class="flex flex-row items-center justify-center w-full h-[70vh] bg-black"
    >
      <img :src="url" alt="Preview" class="object-contain w-full h-full" />
    </div>

    <embed
      v-else
      class="w-full h-[70vh]"
      :src="url"
      type="application/pdf"
      width="100%"
      height="100%"
    />

    <!-- <div v-else class="flex flex-row items-center justify-center w-full h-[70vh] text-gray-500">
      <StateWarning message="Tipe file tidak diketahui" />
    </div> -->
  </template>
</template>

<script setup lang="ts">
import { computed } from 'vue'

import ButtonClose from '../button/ButtonClose.vue'
import CardDefault from '../card/CardDefault.vue'
// import StateWarning from '../state/StateWarning.vue'

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  url: {
    type: String,
    required: true
  },
  wrapper: {
    type: Boolean,
    default: true
  }
})

const emit = defineEmits(['close'])

const close = () => {
  emit('close')
}

// const isPdf = computed(() => props.url.toLowerCase().endsWith('.pdf'))

const isVideo = computed(() => /\.(mp4|webm|ogg|avi|mov)$/i.test(props.url))

const isImage = computed(() => /\.(jpg|jpeg|png|gif|webp|svg)$/i.test(props.url))

const isYouTube = computed(() => {
  const regex =
    /^(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:watch\?v=|embed\/|v\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/
  return regex.test(props.url)
})

const youTubeEmbedUrl = computed(() => {
  if (!isYouTube.value) return ''
  const match = props.url.match(/(?:v=|\/)([a-zA-Z0-9_-]{11})/)
  return match ? `https://www.youtube.com/embed/${match[1]}` : ''
})

const displayTitle = computed(() => {
  try {
    const url = new URL(props.title)
    const pathSegments = url.pathname.split('/')
    return pathSegments[pathSegments.length - 1] || props.title
  } catch {
    return props.title
  }
})
</script>
