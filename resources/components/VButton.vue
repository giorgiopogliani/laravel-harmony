<script lang="ts" setup>
import { router } from "@inertiajs/vue3";

let props = defineProps<{
  href?: null | string;
  ask?: null | string;
  method?: null | string;
  as?: null | string;
  inertia?: boolean;
}>();

const submit = () => {
  if (props.ask && !confirm(props.ask)) return;

  // @ts-ignore
  router[props.method](props.href);
};
</script>

<template>
  <button type="button" v-if="['post', 'delete', 'put'].includes(method ?? '')" class="btn" @click="submit()">
    <slot />
  </button>
  <a v-else-if="inertia === false" :href="href ?? '#'" class="btn">
    <slot />
  </a>
  <Link v-else-if="href" :href="href" class="btn">
    <slot />
  </Link>
  <button v-else class="btn">
    <slot></slot>
  </button>
</template>
