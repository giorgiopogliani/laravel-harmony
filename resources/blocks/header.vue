<script lang="ts" setup>
import { ref, onMounted } from "vue";

defineProps<{
    title: string;
}>();
</script>

<template>
    <h2 class="text-lg font-medium mb-4">
        {{ title }}
    </h2>
</template>
