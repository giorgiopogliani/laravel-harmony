<script>
export default {
  inheritAttrs: false,
  props: {
    href: {
      type: String,
    },
  },
};
</script>

<template>
  <li>
    <Link
      v-bind="$attrs"
      :href="href"
      class="text-sm hover:underline flex items-center text-left transition text-gray-800 dark:text-gray-200"
    >
      <slot />
    </Link>
  </li>
</template>
