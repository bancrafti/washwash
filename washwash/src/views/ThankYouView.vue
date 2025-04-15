<template>
  <div class="thankyou-container">
    <div class="receipt-card">
      <div class="receipt-header">
        <h2>Thank You for Your Order!</h2>
        <p class="order-confirmation">Your laundry order has been successfully received.</p>
      </div>

      <div class="receipt-content">
        <h3>Order Receipt</h3>
        <div class="order-number">
          <p><span>Order #:</span> {{ orderNumber }}</p>
          <p><span>Date:</span> {{ orderDate }}</p>
        </div>

        <div v-for="(serviceOrder, index) in cart" :key="index" class="receipt-service">
          <h4>{{ serviceOrder.service.name }}</h4>
          <div class="service-items">
            <div v-for="item in serviceOrder.items" :key="item.id" class="receipt-item">
              <template v-if="item.quantity > 0">
                <div class="item-details">
                  <div class="item-name-qty">
                    <span class="item-name">{{ item.name }}</span>
                    <span class="item-quantity">{{ item.quantity }} x Ksh {{ item.price }}</span>
                  </div>
                  <span class="item-subtotal">Ksh {{ item.quantity * item.price }}</span>
                </div>
              </template>
            </div>
          </div>
          <div class="service-subtotal">
            <span>Service Subtotal:</span>
            <span>Ksh {{ calculateServiceTotal(serviceOrder.items) }}</span>
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
      </div>

      <div class="delivery-info">
        <h4>Delivery Information</h4>
        <p>Your clean laundry will be ready for pickup or delivery on: <strong>{{ estimatedDeliveryDate }}</strong></p>
        <div class="contact-info">
          <p>For any questions regarding your order, please contact us at:</p>
          <p><i class="phone-icon"></i> 0700-123-456</p>
          <p><i class="email-icon"></i> support@cleanlaundry.co.ke</p>
        </div>
      </div>

      <div class="thank-you-message">
        <p>Thank you for choosing our laundry service. We appreciate your business!</p>
      </div>

      <div class="actions">
        <button @click="submitOrderToBackend"class="track-button" >Save Order</button>
        <button @click="trackOrder" class="track-button">Track Order</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

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
async function submitOrderToBackend() {
  try {
    const payload = {
      orderNumber: orderNumber.value,
      orderDate: new Date().toISOString(),
      estimatedDeliveryDate: new Date(new Date().getTime() + 24 * 60 * 60 * 1000).toISOString(),
      deliveryFee: deliveryFee.value,
      subtotal: totalOrderCost.value,
      grandTotal: grandTotal.value,
      cart: cart.value
    };

    const response = await axios.post('http://localhost/washwash/backend/receipt.php', payload);

     if (response.data.success) {
      alert('Order saved successfully to backend! Redirecting to dashboard...')
      setTimeout(() => {
        router.push('/home') // redirect to the dashboard
      }, 2000) // 2-second delay
    } else {
      alert('Failed to save order: ' + response.data.message)
    }
  } catch (error) {
    alert('Error saving order: ' + error.message)
  }
}
</script>

<style scoped>
.thankyou-container {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  max-width: 800px;
  margin: 0 auto;
  padding: 2rem;
  background-color: #f7f9fc;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: flex-start;
}

.receipt-card {
  background-color: white;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  width: 100%;
  overflow: hidden;
}

.receipt-header {
  background-color: #0078d7;
  color: white;
  padding: 2rem;
  text-align: center;
}

.receipt-header h2 {
  margin: 0;
  font-size: 1.8rem;
  font-weight: 600;
}

.order-confirmation {
  margin-top: 0.5rem;
  font-size: 1.1rem;
  opacity: 0.9;
}

.receipt-content {
  padding: 2rem;
}

.receipt-content h3 {
  font-size: 1.5rem;
  color: #333;
  margin-top: 0;
  margin-bottom: 1.5rem;
  padding-bottom: 0.75rem;
  border-bottom: 2px solid #f0f0f0;
}

.order-number {
  display: flex;
  justify-content: space-between;
  margin-bottom: 2rem;
  font-size: 1rem;
  color: #555;
}

.order-number p {
  margin: 0;
}

.order-number span {
  font-weight: 600;
  color: #333;
}

.receipt-service {
  margin-bottom: 2rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid #eee;
}

.receipt-service h4 {
  color: #0078d7;
  margin-bottom: 1rem;
  font-size: 1.2rem;
}

.service-items {
  margin-left: 1rem;
}

.receipt-item {
  margin-bottom: 0.75rem;
}

.item-details {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.item-name-qty {
  display: flex;
  flex-direction: column;
}

.item-name {
  font-weight: 600;
  color: #333;
}

.item-quantity {
  color: #666;
  font-size: 0.9rem;
  margin-top: 0.2rem;
}

.item-subtotal {
  font-weight: 600;
  color: #333;
}

.service-subtotal {
  display: flex;
  justify-content: space-between;
  padding: 0.75rem 0;
  margin-top: 0.5rem;
  font-weight: 600;
  color: #555;
  border-top: 1px dashed #eee;
}

.order-summary {
  margin-top: 2rem;
  background-color: #f9f9f9;
  border-radius: 8px;
  padding: 1.5rem;
}

.summary-row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 0.75rem;
  color: #555;
}

.summary-row.total {
  margin-top: 1.5rem;
  padding-top: 1rem;
  border-top: 2px solid #eee;
  font-size: 1.2rem;
  font-weight: 700;
  color: #0078d7;
}

.delivery-info {
  padding: 2rem;
  background-color: #f0f7ff;
  border-top: 1px solid #e0e0e0;
}

.delivery-info h4 {
  color: #0078d7;
  margin-top: 0;
  margin-bottom: 1rem;
  font-size: 1.2rem;
}

.delivery-info p {
  margin: 0.5rem 0;
  color: #555;
  line-height: 1.5;
}

.contact-info {
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 1px dashed #cce0ff;
}

.phone-icon::before {
  content: "📞 ";
}

.email-icon::before {
  content: "✉️ ";
}

.thank-you-message {
  padding: 1.5rem 2rem;
  text-align: center;
  font-style: italic;
  color: #555;
  background-color: #f9f9f9;
  border-top: 1px solid #e0e0e0;
}

.actions {
  display: flex;
  padding: 1.5rem 2rem;
  gap: 1rem;
  justify-content: center;
  background-color: #fff;
  border-top: 1px solid #e0e0e0;
}

.new-order-button, .track-button {
  padding: 0.75rem 1.5rem;
  border-radius: 6px;
  font-weight: 600;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.2s ease;
  min-width: 150px;
  text-align: center;
  border: none;
}

.new-order-button {
  background-color: #0078d7;
  color: white;
}

.new-order-button:hover {
  background-color: #005a9e;
}

.track-button {
  background-color: white;
  color: #0078d7;
  border: 1px solid #0078d7;
}

.track-button:hover {
  background-color: #f0f7ff;
}

@media (max-width: 600px) {
  .thankyou-container {
    padding: 1rem;
  }

  .receipt-header {
    padding: 1.5rem;
  }

  .receipt-content, .delivery-info, .actions {
    padding: 1.5rem;
  }

  .order-number {
    flex-direction: column;
    gap: 0.5rem;
  }

  .item-details {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
  }

  .item-subtotal {
    align-self: flex-end;
  }

  .actions {
    flex-direction: column;
  }

  .new-order-button, .track-button {
    width: 100%;
  }
}
</style>
