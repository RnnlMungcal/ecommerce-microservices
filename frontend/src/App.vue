<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'
import { useCartStore } from '@/stores/cartStore'
import { computed } from 'vue'
import { ShoppingCart } from 'lucide-vue-next' // optional icon from lucide

const cart = useCartStore()
const cartCount = computed(() => cart.totalItems)
</script>

<template>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <!-- Navbar -->
    <header class="bg-white shadow-sm">
      <div class="mx-auto px-4 py-4 flex justify-between items-center max-w-7xl">
        <!-- Logo -->
        <RouterLink to="/" class="text-2xl font-bold text-blue-600">
          Ecommerce
        </RouterLink>

        <!-- Navigation -->
        <nav class="flex items-center gap-6 text-gray-700 font-medium">
          <RouterLink to="/" class="hover:text-blue-600" active-class="text-blue-600">
            Home
          </RouterLink>
          <RouterLink to="/products" class="hover:text-blue-600" active-class="text-blue-600">
            Products
          </RouterLink>
          <RouterLink to="/about" class="hover:text-blue-600" active-class="text-blue-600">
            About
          </RouterLink>

          <!-- Cart Button -->
          <RouterLink
            to="/checkout"
            class="relative flex items-center gap-1 text-gray-700 hover:text-blue-600 transition"
          >
            <ShoppingCart class="w-5 h-5" />
            <span>Cart</span>
            <span
              v-if="cartCount > 0"
              class="absolute -top-2 -right-3 bg-red-600 text-white text-xs font-bold rounded-full px-2 py-[2px]"
            >
              {{ cartCount }}
            </span>
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
