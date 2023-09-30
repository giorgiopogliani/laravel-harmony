<script lang="ts" setup>
import { router } from "@inertiajs/vue3";

let props = defineProps<{
  href?: null | string;
  ask?: null | string;
  method?: null | string;
  as?: null | string;
}>();

const submit = () => {
  if (props.ask && !confirm(props.ask)) return;

  if (!props.method) {
    throw new Error(
      "You must provide a `method` prop when using `v-button` without an `href`."
    );
  }

  //@ts-ignore
  router[props.method](props.href);
};
</script>

<template>
  <component :is="as" type="button" v-if="as" class="btn" @click="submit()">
    <slot />
  </component>
  <Link v-else-if="href" :href="href" class="btn">
    <slot />
  </Link>
  <button v-else class="btn">
    <slot></slot>
  </button>
</template>
