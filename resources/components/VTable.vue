<template>
  <div class="px-4 sm:px-6 lg:px-8 bg-white rounded shadow-sm">
    <div class="flow-root">
      <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
          <div class="relative">
            <table class="min-w-full table-fixed divide-y divide-gray-300">
              <slot></slot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
