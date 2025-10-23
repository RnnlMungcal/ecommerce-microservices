<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'

interface Product {
  id: number
  name: string
  price: number
  image_url?: string
  description: string
}

const products = ref<Product[]>([])
const loading = ref(true)
const error = ref<string | null>(null)

onMounted(async () => {
  try {
    const res = await axios.get('http://localhost:8001/api/products')
    products.value = res.data.map((p: any) => ({
      ...p,
      price: parseFloat(p.price),
    }))
  } catch (err) {
    error.value = 'Failed to load products. Please try again later.'
    console.error(err)
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <section
      class="relative bg-teal-500 text-white py-16 px-6 md:px-16 text-center flex flex-col items-center justify-center gap-5"
    >
      <h1 class="text-4xl md:text-5xl font-extrabold mb-4 tracking-tight">Product Catalog</h1>
      <p class="text-lg text-blue-100 max-w-2xl mx-auto">
        Explore our full collection
      </p>
    </section>

    <!-- Loading & Error States -->
    <section class="py-16 px-6 md:px-10 text-center">
      <div v-if="loading" class="text-gray-500 text-lg">Loading products...</div>
      <div v-else-if="error" class="text-red-500 text-lg">{{ error }}</div>

      <!-- Product Grid -->
      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <div
          v-for="p in products"
          :key="p.id"
          class="group bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300"
        >
          <div class="relative overflow-hidden">
            <img
              :src="p.image_url || 'https://via.placeholder.com/400x300?text=No+Image'"
              :alt="p.name"
              class="w-full h-56 object-cover transform group-hover:scale-105 transition duration-300"
            />
          </div>
          <div class="p-5">
            <h3 class="font-semibold text-lg text-gray-900 truncate mb-1">
              {{ p.name }}
            </h3>
            <p class="text-gray-600 mb-3">${{ p.price.toFixed(2) }}</p>
            <p class="text-gray-500 text-sm mb-4 line-clamp-2">
              {{ p.description }}
            </p>
            <router-link
              :to="`/products/${p.id}`"
              class="inline-block text-sm text-blue-600 font-medium hover:text-blue-700 transition"
            >
              View Details →
            </router-link>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
