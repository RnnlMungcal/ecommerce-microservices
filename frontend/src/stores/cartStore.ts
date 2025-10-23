import { defineStore } from 'pinia'

interface CartItem {
  id: number
  name: string
  price: number
  quantity: number
  image_url?: string
}

export const useCartStore = defineStore('cart', {
  state: () => ({
    items: [] as CartItem[],
  }),

  getters: {
    totalItems: (state) => state.items.reduce((sum, i) => sum + i.quantity, 0),
    totalPrice: (state) => state.items.reduce((sum, i) => sum + i.price * i.quantity, 0),
  },

  actions: {
    addToCart(product: Omit<CartItem, 'quantity'>) {
      const existing = this.items.find((item) => item.id === product.id)
      if (existing) {
        existing.quantity += 1
      } else {
        this.items.push({ ...product, quantity: 1 })
      }
      this.saveCart()
    },

    removeFromCart(id: number) {
      this.items = this.items.filter((item) => item.id !== id)
      this.saveCart()
    },

    clearCart() {
      this.items = []
      localStorage.removeItem('cart')
    },

    saveCart() {
      localStorage.setItem('cart', JSON.stringify(this.items))
    },

    loadCart() {
      const saved = localStorage.getItem('cart')
      if (saved) {
        this.items = JSON.parse(saved)
      }
    },
  },
})
