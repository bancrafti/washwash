<template>
  <div class="thankyou-container">
    <div class="receipt">
      <h2>Thank You for Your Order!</h2>
      <p class="order-confirmation">Your laundry order has been successfully received.</p>

      <h3>Order Receipt</h3>
      <div class="order-number">
        <p>Order #: {{ orderNumber }}</p>
        <p>Date: {{ orderDate }}</p>
      </div>

      <div v-for="(serviceOrder, index) in cart" :key="index" class="receipt-service">
        <h4>{{ serviceOrder.service.name }}</h4>
        <div v-for="item in serviceOrder.items" :key="item.id" class="receipt-item">
          <template v-if="item.quantity > 0">
            <div class="item-details">
              <span class="item-name">{{ item.name }}</span>
              <span class="item-quantity">{{ item.quantity }} x Ksh {{ item.price }}</span>
              <span class="item-subtotal">Ksh {{ item.quantity * item.price }}</span>
            </div>
          </template>
        </div>
        <div class="service-subtotal">
          Service Subtotal: Ksh {{ calculateServiceTotal(serviceOrder.items) }}
        </div>
      </div>

      <div class="order-summary">
        <div class="summary-row">
          <span>Subtotal:</span>
          <span>Ksh {{ totalOrderCost }}</span>
        </div>
        <div class="summary-row">
          <span>Delivery Fee:</span>
          <span>Ksh {{ deliveryFee }}</span>
        </div>
        <div class="summary-row total">
          <span>Grand Total:</span>
          <span>Ksh {{ grandTotal }}</span>
        </div>
      </div>

      <div class="delivery-info">
        <h4>Delivery Information</h4>
        <p>Your clean laundry will be ready for pickup or delivery on: <strong>{{ estimatedDeliveryDate }}</strong></p>
        <p>For any questions regarding your order, please contact us at:</p>
        <p>Phone: 0700-123-456</p>
        <p>Email: support@cleanlaundry.co.ke</p>
      </div>

      <div class="thank-you-message">
        <p>Thank you for choosing our laundry service. We appreciate your business!</p>
      </div>

      <div class="actions">
        <button @click="startNewOrder" class="new-order-button">Start New Order</button>
        <button @click="trackOrder" class="track-button">Track Order</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const cart = ref([]);
const orderNumber = ref('');
const orderDate = ref('');
const estimatedDeliveryDate = ref('');
const deliveryFee = ref(150);

// Generate random order number
function generateOrderNumber() {
  const randomPart = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
  const datePart = new Date().toISOString().slice(2, 10).replace(/-/g, '');
  return `CL-${datePart}-${randomPart}`;
}

// Format date for display
function formatDate(date) {
  return new Intl.DateTimeFormat('en-KE', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }).format(date);
}

// Calculate estimated delivery date (24 hours from now)
function calculateEstimatedDelivery() {
  const deliveryDate = new Date();
  deliveryDate.setHours(deliveryDate.getHours() + 24);
  return formatDate(deliveryDate);
}

// Calculate service total
function calculateServiceTotal(items) {
  return items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
}

// Computed property for total cost
const totalOrderCost = computed(() => {
  return cart.value.reduce((total, serviceOrder) => {
    return total + calculateServiceTotal(serviceOrder.items);
  }, 0);
});

// Computed property for grand total (including delivery fee)
const grandTotal = computed(() => {
  return totalOrderCost.value + deliveryFee.value;
});

// Load cart data on component mount
onMounted(() => {
  const savedCart = localStorage.getItem('laundryCart');
  if (savedCart) {
    cart.value = JSON.parse(savedCart);
  }

  // Generate order information
  orderNumber.value = generateOrderNumber();
  orderDate.value = formatDate(new Date());
  estimatedDeliveryDate.value = calculateEstimatedDelivery();
});

// Navigation methods
function startNewOrder() {
  localStorage.removeItem('laundryCart');
  router.push('/');
}

function trackOrder() {
  alert(`Your order ${orderNumber.value} is being processed. You'll receive updates via SMS.`);
}
</script>
