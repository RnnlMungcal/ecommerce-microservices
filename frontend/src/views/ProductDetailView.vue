<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import { useCartStore } from '@/stores/cartStore'

interface Product {
  id: number
  name: string
  price: number
  image_url?: string
  description: string
}

const route = useRoute()
const router = useRouter()
const cart = useCartStore()

const product = ref<Product | null>(null)
const loading = ref(true)
const error = ref<string | null>(null)
const added = ref(false)

onMounted(async () => {
  const id = route.params.id
  try {
    const res = await axios.get(`http://localhost:8001/api/products/${id}`)
    product.value = {
      ...res.data,
      price: parseFloat(res.data.price),
    }
  } catch (err) {
    error.value = 'Product not found or failed to load.'
    console.error(err)
  } finally {
    loading.value = false
  }

  cart.loadCart() // load cart from localStorage
})

const handleAddToCart = () => {
  if (!product.value) return
  cart.addToCart({
    id: product.value.id,
    name: product.value.name,
    price: product.value.price,
    image_url: product.value.image_url,
  })
  added.value = true
  setTimeout(() => (added.value = false), 2000)
}
</script>

<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <section class="bg-gradient-to-r from-teal-500 to-blue-600 text-white py-16 text-center">
      <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-2">Product Details</h1>
      <p class="text-blue-100 max-w-2xl mx-auto">
        Learn more about this product — powered by Laravel Microservices.
      </p>
    </section>

    <!-- Loading / Error -->
    <div v-if="loading" class="text-center py-20 text-gray-500 text-lg">
      Loading product details...
    </div>
    <div v-else-if="error" class="text-center py-20 text-red-500 text-lg">
      {{ error }}
    </div>

    <!-- Product Detail -->
    <section v-else class="max-w-6xl mx-auto py-16 px-6 md:px-10">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-start">
        <!-- Image -->
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
          <img
            :src="product?.image_url || 'https://via.placeholder.com/600x400?text=No+Image'"
            :alt="product?.name"
            class="w-full h-[400px] object-cover"
          />
        </div>

        <!-- Info -->
        <div>
          <h2 class="text-3xl font-bold text-gray-900 mb-4">
            {{ product?.name }}
          </h2>
          <p class="text-2xl font-semibold text-teal-600 mb-6">${{ product?.price.toFixed(2) }}</p>
          <p class="text-gray-700 mb-8 leading-relaxed">
            {{ product?.description }}
          </p>

          <!-- Buttons -->
          <div class="flex items-center gap-4">
            <button
              @click="handleAddToCart"
              class="px-6 py-3 bg-teal-600 text-white font-semibold rounded-xl hover:bg-teal-700 transition shadow-lg"
            >
              Add to Cart
            </button>
            <button
              @click="router.back()"
              class="px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-xl hover:bg-gray-300 transition"
            >
              Go Back
            </button>
          </div>

          <!-- Confirmation Toast -->
          <transition name="fade">
            <div
              v-if="added"
              class="mt-4 inline-block bg-green-100 text-green-800 px-4 py-2 rounded-lg font-medium shadow-sm"
            >
              Added to cart!
            </div>
          </transition>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
