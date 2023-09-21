<script lang="ts" setup>
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";

let props = defineProps<{
  selectedRows: number[];
  actions: any[];
}>();

let emit = defineEmits(["deselectAll"])

let action = ref(null);

function submit() {
  if (action.value) {
    let form = useForm({
      ids: props.selectedRows,
    });

    form.post(action.value);

    emit('deselectAll');
  }
}
</script>

<template>
  <div
    v-if="selectedRows.length > 0"
    class="bg-gray-50 p-2 flex items-center w-full gap-4"
  >
    <select v-model="action" class="form-input w-full max-w-xs">
      <option value="0">Seleziona...</option>
      <option v-for="action in actions" :value="action.href">
        {{ action.title }}
      </option>
    </select>
    <VButton class="btn btn-yellow" @click="submit"> Submit </VButton>
    <span class="text-sm whitespace-nowrap text-gray-600">
      ({{ selectedRows.length }} selected)
    </span>
  </div>
</template>
