<script lang="ts" setup>
defineProps<{ row: any }>();
</script>

<template>
  <div class="">
    <Link class="link" :href="row.links.show">
      <slot></slot>
    </Link>
  </div>
</template>
