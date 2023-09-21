<script lang="ts" setup>
import HInput from "~/components/HInput.vue";
import VButton from "~/components/VButton.vue";
import { useForm } from "@inertiajs/vue3";

let props = defineProps<{ form: any, errors: any }>();

const _form = useForm({
  ...props.form.data,
});

function submit() {
    _form.put(props.form.action);
}
</script>

<template>
  <form class="flex flex-col gap-4 mt-8" @submit.prevent="submit">
    <HInput
      v-for="field in form.fields"
      v-bind="field"
      v-model="_form[field.name]"
      :error="errors[field.name]"
      :label="`${field.label} ${(field.name)} `"
    />
    <div>
      <VButton class="btn btn-blue"> Save </VButton>
    </div>
  </form>
</template>
