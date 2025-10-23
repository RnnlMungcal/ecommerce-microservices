<script setup lang="ts">
import { useCartStore } from '@/stores/cartStore'
import { computed } from 'vue'
import { useRouter } from 'vue-router'

const cart = useCartStore()
const router = useRouter()

const total = computed(() => cart.totalPrice.toFixed(2))

const handleCheckout = () => {
  if (cart.items.length) router.push('/checkout')
}
</script>

<template>
  <div class="min-h-screen bg-gray-50 py-12 px-6 md:px-12">
    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-2xl p-8">
      <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">Your Cart</h1>

      <div v-if="cart.items.length">
        <div
          v-for="item in cart.items"
          :key="item.id"
          class="grid grid-cols-[auto_1fr_auto_auto_auto] items-center gap-6 border-b border-gray-100 py-4"
        >
          <!-- Product Image -->
          <img
            :src="item.image_url || 'https://via.placeholder.com/80x80?text=No+Image'"
            alt=""
            class="w-16 h-16 rounded-lg object-cover"
          />

          <!-- Product Info -->
          <div>
            <p class="font-medium text-gray-800">{{ item.name }}</p>
            <p class="text-sm text-gray-500">${{ item.price.toFixed(2) }}</p>
          </div>

          <!-- Quantity Controls -->
          <div class="flex items-center justify-center gap-3">
            <button
              @click="cart.decreaseQty(item.id)"
              class="w-8 h-8 flex items-center justify-center bg-gray-200 rounded hover:bg-gray-300 transition cursor-pointer"
            >
              −
            </button>
            <span class="text-gray-800 font-semibold w-6 text-center">
              {{ item.quantity }}
            </span>
            <button
              @click="cart.increaseQty(item.id)"
              class="w-8 h-8 flex items-center justify-center bg-gray-200 rounded hover:bg-gray-300 cursor-pointer"
            >
              +
            </button>
          </div>

          <!-- Subtotal -->
          <p class="font-semibold text-teal-600 text-right w-24">
            ${{ (item.price * item.quantity).toFixed(2) }}
          </p>

          <!-- Remove -->
          <button
            @click="cart.removeFromCart(item.id)"
            class="text-red-500 text-sm hover:underline text-right cursor-pointer"
          >
            Remove
          </button>
        </div>

        <!-- Cart Summary -->
        <div class="mt-8 flex justify-between items-center border-t border-gray-200 pt-6">
          <p class="text-lg font-semibold text-gray-800">Total</p>
          <p class="text-2xl font-bold text-teal-600">${{ total }}</p>
        </div>

        <!-- Actions -->
        <div class="mt-8 flex justify-between">
          <button
            @click="cart.clearCart"
            class="px-6 py-3 bg-gray-200 text-gray-700 font-medium rounded-xl hover:bg-gray-300 transition cursor-pointer"
          >
            Clear Cart
          </button>
          <button
            @click="handleCheckout"
            class="px-6 py-3 bg-teal-600 text-white font-medium rounded-xl hover:bg-teal-700 transition cursor-pointer"
          >
            Proceed to Checkout
          </button>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center text-gray-500 py-12">
        Your cart is empty.
        <RouterLink to="/products" class="text-blue-600 hover:underline">
          Continue Shopping
        </RouterLink>
      </div>
    </div>
  </div>
</template>
