<script lang="ts" setup>
import { useSidebarStore } from "~/stores/useSidebarStore";

const sidebarStore = useSidebarStore();
</script>

<template>
  <div>
    <TheNotifications />

    <TheHeader />

    <div
      class="transition min-h-screen md:grid md:grid-cols-[12rem,1fr] bg-gray-50 pb-12 pt-6"
      :class="{
        'md:grid-cols-[0rem,1fr]': !sidebarStore.isOpen,
        'md:grid-cols-[12rem,1fr]': sidebarStore.isOpen,
      }"
    >
      <TheSidebar />
      <main class="transition">
        <VContainer>
          <slot />
        </VContainer>
      </main>
    </div>
  </div>
</template>
