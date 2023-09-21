<script setup lang="ts">
import RenderComponents from "~/components/RenderComponents.vue";
import { Head } from "@inertiajs/vue3";
import { defineAsyncComponent } from "vue";

defineProps<{
    title: string;
    widgets: any;
}>();
</script>

<template>
    <Head :title="title" />

    <div class="py-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div v-for="widget in widgets" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-2 bg-white px-4 py-10 sm:px-6 xl:px-8">
                    <dt class="text-sm font-medium leading-6 text-gray-500">
                        {{ widget.title }}
                    </dt>
                    <dd class="w-full flex-none text-3xl font-medium leading-10 tracking-tight text-gray-900">
                        {{ widget.count }}
                    </dd>
                </div>
            </div>
        </div>
    </div>
</template>
