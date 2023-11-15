<template>
  <div
    aria-live="assertive"
    class="pointer-events-none fixed inset-0 flex items-end gap-4 flex-col px-4 py-6 z-50"
  >
    <VToast
      v-if="$page.props.messages.length > 0"
      v-for="flash in $page.props.messages"
      class="flex w-full flex-col items-center space-y-4 sm:items-end right-0"
      :key="Math.random()"
      :notification="flash"
    />
  </div>
</template>
