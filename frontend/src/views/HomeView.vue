<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'

interface Product {
  id: number
  name: string
  price: number
  image_url: string
  description: string
}

const featuredProducts = ref<Product[]>([])

onMounted(async () => {
  try {
    const res = await axios.get('http://localhost:8001/api/products')
    const products = res.data.map((p: any) => ({
      ...p,
      price: parseFloat(p.price)
    }))
    featuredProducts.value = products.slice(0, 4)

  } catch (err) {
    console.error('Failed to load products:', err)
  }
})
</script>

<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
   <section class="relative  bg-teal-500 text-white py-20 px-6 md:px-16 text-center flex flex-col items-center justify-center gap-5"> 
      <h1 class="text-5xl font-extrabold mb-4 leading-tight tracking-tight">
        Discover Your Next Favorite Product
      </h1>
      <p class="text-lg text-blue-100 mb-8 max-w-2xl">
        Shop with ease and confidence — powered by Laravel Microservices.
      </p>
     <router-link
        to="/products" 
      >
        <span
          class="inline-block font-bold px-8 py-3 rounded-xl transition bg-white text-gray-700 hover:bg-blue-700 hover:text-white shadow-lg hover:shadow-xl cursor-pointer"
        >
          Shop Now
        </span>
      </router-link>

    </section>


    <!-- Featured Products -->
    <section class="mx-auto py-16 px-6 md:px-10">
      <h2 class="text-3xl font-bold text-center text-gray-800 mb-20">
        Featured Products
      </h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <div
          v-for="p in featuredProducts"
          :key="p.id"
          class="group bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300"
        >
          <div class="relative overflow-hidden">
            <img
              :src="p.image_url"
              alt="Product Image"
              class="w-full h-56 object-cover transform group-hover:scale-105 transition duration-300"
            />
          </div>
          <div class="p-5">
            <h3 class="font-semibold text-lg text-gray-900 truncate mb-1">
              {{ p.name }}
            </h3>
            <p class="text-gray-600 mb-3">${{ p.price.toFixed(2) }}</p>
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
