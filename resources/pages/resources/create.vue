<script lang="ts" setup>
import { useForm } from "@inertiajs/vue3";

let props = defineProps<{ form: any; errors: any }>();

const _form = useForm({
  ...props.form.data,
});

function submit() {
  _form.post(props.form.action);
}
</script>

<template>
  <form class="flex flex-col gap-4 mt-8" @submit.prevent="submit">
    <VPageHeader>
      <VButton class="btn-primary">
        Save
      </VButton>
    </VPageHeader>

    <VCard>
      <div class="flex flex-col gap-8">
        <VFormInput
          v-for="field in form.fields"
          v-bind="field"
          v-model="_form[field.name]"
          :error="$page.props.errors[field.name]"
          :label="field.label"
        />
      </div>
    </VCard>
  </form>
</template>
