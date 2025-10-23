<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'
</script>

<template>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <!-- Navbar -->
    <header class="bg-white shadow-sm">
      <div class="mx-auto px-4 py-4 flex justify-between items-center">
        <RouterLink to="/" class="text-2xl font-bold text-blue-600">
          Ecommerce
        </RouterLink>

        <nav class="flex gap-6 text-gray-700 font-medium">
          <RouterLink to="/" class="hover:text-blue-600" active-class="text-blue-600">
            Home
          </RouterLink>
          <RouterLink to="/products" class="hover:text-blue-600" active-class="text-blue-600">
            Products
          </RouterLink> 
          <RouterLink to="/about" class="hover:text-blue-600" active-class="text-blue-600">
            About
          </RouterLink>
        </nav>
      </div>
    </header>

    <!-- Main content -->
    <main class="flex-1 mx-auto w-full">
      <RouterView />
    </main>

    <!-- Footer -->
    <footer class="bg-gray-100 text-center py-4 text-gray-600 text-sm border-t">
      © {{ new Date().getFullYear() }} Ecommerce. All rights reserved.
    </footer>
  </div>
</template>

<style scoped>
/* Tailwind handles most styling; add custom CSS only if needed */
</style>
