<template>
    <Link
        :disabled="disabled"
        v-if="inertia && href"
        :href="disabled ? 'javascript:void(0)' : href"
        v-bind="$attrs"
        :class="['btn', disabled ? 'opacity-60 cursor-not-allowed' : '']"
    >
        <VIcon v-if="icon" :name="icon" class="h-4 w-4 mr-2"></VIcon>
        <slot></slot>
    </Link>
    <a
        :disabled="disabled"
        v-else-if="href"
        :href="disabled ? 'javascript:void(0)' : href"
        v-bind="$attrs"
        :class="['btn', disabled ? 'opacity-60 cursor-not-allowed' : '']"
    >
        <VIcon v-if="icon" :name="icon" class="h-4 w-4 mr-2"></VIcon>
        <slot></slot>
    </a>
    <button
        :disabled="disabled"
        v-else
        v-bind="$attrs"
        :class="['btn', disabled ? 'opacity-60 cursor-not-allowed' : '']"
    >
        <VIcon v-if="icon" :name="icon" class="h-4 w-4 mr-2"></VIcon>
        <slot></slot>
    </button>
</template>

<script lang="ts">
export default {
    inheritAttrs: false,
};
</script>

<script setup lang="ts">
import VIcon from './VIcon.vue';

defineProps({
    disabled: {
        type: Boolean,
        default: false,
    },
    icon: {
        type: String,
        default: null,
    },
    href: {
        type: String,
        default: null,
    },
    inertia: {
        type: Boolean,
        default: false,
    },
});
</script>
