<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <div
    data-slot="sidebar-content"
    data-sidebar="content"
    :class="cn('flex min-h-0 flex-1 flex-col gap-2 overflow-auto group-data-[collapsible=icon]:overflow-hidden', props.class)"
  >
    <slot />
  </div>
</template>

<style scoped>
[data-slot='sidebar-content']::-webkit-scrollbar {
  width: 6px;
  height: 6px;
}
[data-slot='sidebar-content']::-webkit-scrollbar-track {
  background: transparent;
}
[data-slot='sidebar-content']::-webkit-scrollbar-thumb {
  background-color: var(--sidebar-accent);
  border-radius: 6px;
}
[data-slot='sidebar-content']::-webkit-scrollbar-thumb:hover {
  background-color: var(--sidebar-accent-foreground);
}
/* For Firefox */
[data-slot='sidebar-content'] {
  scrollbar-width: thin;
  scrollbar-color: var(--sidebar-accent) transparent;
}
</style>
