<script lang="ts" setup>
import { ref, onMounted } from "vue";
import RenderComponents from "~/components/RenderComponents.vue";

defineProps<{
    title: string;
    columns: number;
    children: any[];
}>();
</script>

<template>
    <div
        class="grid grid-cols-1 lg:grid-cols-[var(--columns)] gap-8"
        :style="`--columns: repeat(${columns}, 1fr)`"
    >
        <RenderComponents :components="children" />
    </div>
</template>
