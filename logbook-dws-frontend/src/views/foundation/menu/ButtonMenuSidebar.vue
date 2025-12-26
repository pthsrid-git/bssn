<template>
  <router-link :to="route">
    <button
      :id="buttonId"
      :data-collapse-toggle="hasChild ? menuId : null"
      :aria-controls="hasChild ? menuId : undefined"
      :class="
        (isParentActive(route) ? 'text-white bg-primary-600' : 'text-gray-400 bg-transparent') +
        ' w-full flex items-center px-6 py-3 text-sm hover:text-white hover:bg-primary-600 cursor-pointer'
      "
      type="button"
    >
      <div class="flex items-center justify-between w-full">
        <div class="flex items-center overflow-hidden">
          <div class="flex items-center flex-1 gap-2 overflow-hidden flex-nowrap">
            <font-awesome-icon v-if="!!route.meta?.icon" class="w-4 h-4" :icon="route.meta?.icon" />
            <span class="truncate text-start text-nowrap"> {{ route.meta?.title }}</span>
          </div>
        </div>
        <font-awesome-icon
          v-if="hasChild && !isParentActive(route)"
          :icon="['fas', 'angle-down']"
          class="w-3.5 h-3.5 ml-2.5"
        />
        <font-awesome-icon
          v-if="hasChild && isParentActive(route)"
          :icon="['fas', 'angle-up']"
          class="w-3.5 h-3.5 ml-2.5"
        />
      </div>
    </button>

    <div
      v-if="hasChild"
      :id="menuId"
      :class="isParentActive(route) ? 'block' : 'hidden'"
      :aria-labelledby="buttonId"
    >
      <li v-for="(child, index) in children" :key="index">
        <router-link :to="child">
          <button
            :class="
              (isChildActive(child.name) ? `text-white` : 'text-gray-400') +
              ' w-full flex items-center pl-10 pr-6 py-3 text-sm hover:text-white bg-primary-600 cursor-pointer'
            "
            type="button"
          >
            <div class="flex items-center flex-1 gap-2 overflow-hidden flex-nowrap">
              <font-awesome-icon
                v-if="!!child.meta?.icon"
                class="w-4 h-4"
                :icon="child.meta?.icon"
              />
              <span class="truncate text-start text-nowrap"> {{ child.meta?.title }}</span>
            </div>
          </button>
        </router-link>
      </li>
    </div>
  </router-link>
</template>

<script setup lang="ts">
import type { RouteMetaData } from '@bssn/ui-kit-frontend'
import { initDropdowns } from 'flowbite'
import { v4 as uuidv4 } from 'uuid'
import { computed, onMounted } from 'vue'
import { useRouter, type RouteRecordName, type RouteRecordRaw } from 'vue-router'

type MenuRoute = RouteRecordRaw & {
  meta: RouteMetaData
}

const props = defineProps<{
  route: MenuRoute
}>()

const router = useRouter()

const currentName = computed(() => router.currentRoute.value.name)

const splitName = (name?: RouteRecordName) => name?.toString().split('.') ?? []

const isParentActive = (route: MenuRoute) => {
  if (!route.name) return false

  const parentParts = splitName(route.name)
  const currentParts = splitName(currentName.value)

  if (currentParts.length < parentParts.length) return false

  return parentParts.every((p, i) => currentParts[i] === p)
}

const isChildActive = (routeName: RouteRecordName) => {
  if (!routeName) return false
  const target = routeName.toString().split('.')
  const current = (currentName.value?.toString() ?? '').split('.')

  return target.every((part, i) => current[i] === part)
}

const children = computed(
  () => props.route.children?.filter((child: any): child is MenuRoute => !!child.meta?.menu) ?? []
)
const hasChild = computed(() => children.value.length > 0)

const buttonId = 'ButtonMenuSidebarButton-' + uuidv4()
const menuId = 'ButtonMenuSidebarMenu-' + uuidv4()

onMounted(() => {
  initDropdowns()
})
</script>
