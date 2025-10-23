<script setup lang="ts">
import { ref, computed } from 'vue'
import axios from 'axios'
import { useCartStore } from '@/stores/cartStore'

const cart = useCartStore()

const loading = ref(false)
const message = ref<string | null>(null)
const error = ref<string | null>(null)

const total = computed(() => cart.totalPrice.toFixed(2))

async function checkout() {
  if (!cart.items.length) {
    error.value = 'Your cart is empty.'
    return
  }

  loading.value = true
  error.value = null
  message.value = null

  try {
    const res = await axios.post('http://localhost:8002/api/orders', {
      products: cart.items.map((item) => ({
        id: item.id,
        quantity: item.quantity,
      })),
    })

    message.value = `✅ Order placed successfully! Total: $${res.data.total.toFixed(2)}`
    cart.clearCart()
  } catch (err) {
    console.error(err)
    error.value = '❌ Failed to place order. Please try again.'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="min-h-screen bg-gray-50 p-8">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-md">
      <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Checkout</h1>

      <!-- Cart Summary -->
      <div v-if="cart.items.length" class="mb-8">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Your Items</h2>
        <div
          v-for="item in cart.items"
          :key="item.id"
          class="flex justify-between items-center py-3 border-b border-gray-100"
        >
          <div>
            <p class="font-medium text-gray-800">{{ item.name }}</p>
            <p class="text-sm text-gray-500">${{ item.price.toFixed(2) }} × {{ item.quantity }}</p>
          </div>
          <p class="font-semibold text-teal-600">${{ (item.price * item.quantity).toFixed(2) }}</p>
        </div>

        <div class="flex justify-between items-center mt-6">
          <span class="text-lg font-semibold text-gray-800">Total</span>
          <span class="text-2xl font-bold text-teal-600">${{ total }}</span>
        </div>
      </div>

      <!-- Empty Cart -->
      <div v-else class="text-center text-gray-500 mb-8">Your cart is empty.</div>

      <!-- Checkout Button -->
      <div class="text-center mt-8">
        <button
          @click="checkout"
          :disabled="loading || !cart.items.length"
          class="px-8 py-3 rounded-xl font-semibold text-white bg-teal-600 hover:bg-teal-700 transition disabled:opacity-50"
        >
          <span v-if="loading">Processing...</span>
          <span v-else>Place Order</span>
        </button>
      </div>

      <!-- Messages -->
      <div v-if="message" class="mt-6 text-center text-green-700 font-medium">
        {{ message }}
      </div>
      <div v-if="error" class="mt-6 text-center text-red-600 font-medium">
        {{ error }}
      </div>
    </div>
  </div>
</template>
