<script lang="ts" setup>
import { AnyRow, Pagination } from "~/types";

defineProps<{ pagination: Pagination<AnyRow>; query: any }>();
</script>

<template>
  <div class="flex items-center justify-between gap-3">
    <div class="w-full">
      <select class="form-input w-full max-w-[6rem]" v-model="query.per_page">
        <option value="10">10</option>
        <option value="15">15</option>
        <option value="20">20</option>
        <option value="50">50</option>
      </select>
    </div>
    <ul class="flex gap-4 w-full justify-end">
      <li v-for="link in pagination.links">
        <template v-if="link.url">
          <VButton :href="link.url" class="btn btn-neutral whitespace-nowrap" v-html="link.label" preserve-scroll>
          </VButton>
        </template>
        <template v-else>
          <span class="btn btn-neutral whitespace-nowrap" v-html="link.label"> </span>
        </template>
      </li>
    </ul>
  </div>
</template>
