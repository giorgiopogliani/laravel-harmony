<script lang="ts" setup>
import { defineAsyncComponent } from "vue";

defineProps<{
    components: any[];
}>();

function resolve(component: any) {
    return defineAsyncComponent({
        loader: () => import(`../blocks/${component.type}.vue`),
    });
}
</script>

<template>
    <component
        :is="resolve(component)"
        v-for="component in components"
        v-bind="component"
    />
</template>
