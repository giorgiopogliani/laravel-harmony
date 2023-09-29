<script lang="ts" setup>
import { useForm } from "laravel-precognition-vue-inertia";

let props = defineProps<{ form: any; errors: any }>();

const _form = useForm("post", props.form.action, {
  ...props.form.data,
});

const submit = () => {
  _form.submit({
    preserveScroll: true,
    onSuccess: () => _form.reset(),
  });
};
</script>

<template>
  <form class="flex flex-col gap-4" @submit.prevent="submit">
    <VPageHeader>
      <VButton :disabled="_form.processing" type="submit" class="btn-primary">
        Save
      </VButton>
    </VPageHeader>

    <VCard>
      <div class="flex flex-col gap-8">
        <template v-for="field in form.fields">
          <VFormInput
            :class="{ 'border border-red-600': _form.invalid(field.name) }"
            v-bind="field"
            v-model="_form[field.name]"
            :error="_form.errors[field.name]"
            :label="field.label"
            @change="_form.validate(field.name)"
          />
        </template>
      </div>
    </VCard>
  </form>
</template>
