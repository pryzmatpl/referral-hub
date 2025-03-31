<template>
  <div style="text-align: center">
    <h1>Confirm Adding Job</h1>
    <p>Looks like all is good and you are ready to submit your job proposal. Click the button below to submit!</p>
    <p>Adding a job to refrme costs <b>9.99$.</b></p>

    <div v-if="!paymentCompleted">
      <div id="payment-element" class="mb-4"></div>
      <button
          class="btn btn-lg btn-success"
          type="button"
          @click="handleSubmit"
          :disabled="!isAuthenticated || isLoading"
      >
        {{ isLoading ? 'Processing...' : 'Add New Job' }}
      </button>
    </div>

    <div v-else>
      <p>✅ Payment successful! Your job has been added.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useStore } from 'vuex'
import { loadStripe } from '@stripe/stripe-js'

// Stripe state
const stripe = ref(null)
const elements = ref(null)
const isLoading = ref(false)
const paymentCompleted = ref(false)

// Access Vuex store
const store = useStore()

// Emit from setup
const emit = defineEmits(['add-job'])

// Computed auth check
const isAuthenticated = computed(() => store.getters.isAuthenticated)

// Initialize Stripe and create payment intent
onMounted(async () => {
  stripe.value = await loadStripe(`${process.env.PUBLISHABLE_KEY}`)

  try {
    const { data } = await store.state.backend.post('/create-payment-intent', {
      amount: 999, // Should be dynamic if needed
      currency: 'usd',
      email: store.state.user_email,
      name: store.state.user_name,
    })

    const clientSecret = data.clientSecret

    elements.value = stripe.value.elements({ clientSecret })
    const paymentElement = elements.value.create('payment')
    paymentElement.mount('#payment-element')
  } catch (error) {
    console.error('Failed to initialize Stripe:', error.message)
  }
})

// Submit payment
const handleSubmit = async () => {
  isLoading.value = true

  const { error } = await stripe.value.confirmPayment({
    elements: elements.value,
    confirmParams: {
      return_url: window.location.href,
    },
  })

  if (error) {
    console.error('Stripe payment error:', error.message)
    isLoading.value = false
  } else {
    paymentCompleted.value = true
    emit('add-job')
  }
}
</script>

