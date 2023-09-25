<script lang="ts" setup>
import { ref, onMounted } from "vue";
import { useSidebarStore } from "~/stores/useSidebarStore";

const sidebarStore = useSidebarStore();
</script>

<template>
  <nav class="h-screen overflow-auto py-2 px-4 sm:px-6 transition" :class="{
    '-translate-x-full': !sidebarStore.isOpen,
    'translate-x-0': sidebarStore.isOpen,
  }">
    <!-- Primary Navigation Menu -->
    <div class="">
      <div class="flex flex-col">
        <div class="flex flex-col">
          <!-- Navigation Links -->
          <div class="hidden sm:flex sm:flex-col gap-6">
            <NavLink v-for="item in $page.props.menu.items" v-bind="item">
              {{ item.title }}
            </NavLink>
          </div>
        </div>

        <!-- Hamburger -->
        <div class="-mr-2 flex items-center sm:hidden">
          <button
            @click="showingNavigationDropdown = !showingNavigationDropdown"
            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
          >
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
              <path
                :class="{
                  hidden: showingNavigationDropdown,
                  'inline-flex': !showingNavigationDropdown,
                }"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"
              />
              <path
                :class="{
                  hidden: !showingNavigationDropdown,
                  'inline-flex': showingNavigationDropdown,
                }"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden">
      <div class="pt-2 pb-3 space-y-1">
        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
          Dashboard
        </ResponsiveNavLink>
      </div>

      <!-- Responsive Settings Options -->
      <div class="pt-4 pb-1 border-t border-gray-200">
        <div class="px-4">
          <div class="font-medium text-base text-gray-800">
            {{ $page.props.auth.user.name }}
          </div>
          <div class="font-medium text-sm text-gray-500">
            {{ $page.props.auth.user.email }}
          </div>
        </div>

        <div class="mt-3 space-y-1">
          <ResponsiveNavLink :href="route('profile.edit')"> Profile </ResponsiveNavLink>
          <ResponsiveNavLink :href="route('logout')" method="post" as="button"> Log Out </ResponsiveNavLink>
        </div>
      </div>
    </div>
  </nav>
</template>
