<script lang="ts" setup>
defineProps<{ row: any; value: any }>();
</script>

<template>
  <div>
    <v-button v-bind="value">
      {{ value.title }}
    </v-button>
  </div>
</template>
