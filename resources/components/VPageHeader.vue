<template>
  <header v-if="$page.props.title">
    <div class="w-full flex justify-between items-center">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $page.props.title }}
      </h2>
      <div>
        <VButton v-for="action in $page.props.actions" v-bind="action">
          {{ action.title }}
        </VButton>
        <slot></slot>
      </div>
    </div>
  </header>
</template>
