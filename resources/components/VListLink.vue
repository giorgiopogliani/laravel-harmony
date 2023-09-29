<script lang="ts">
export default {
  props: {
    icon: {
      type: String,
      default: null,
    },
    active: {
      type: Function,
      default: () => false
    },
  }
}
</script>

<template>
  <Link
    :class="[
      active()
        ? 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-gray-100'
        : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700',
      'w-full flex items-center px-2 h-8 text-sm font-medium rounded-md',
    ]"
    :aria-current="active() ? 'page' : undefined"
  >
    <v-icon v-if="icon" :name="icon" class="h-4 w-4 mb-0.5 flex-shrink-0 mr-2.5"></v-icon>
    <slot></slot>
  </Link>
</template>
