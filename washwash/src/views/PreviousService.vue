<template>
  <div class="laundry-app">
    <!-- Service Selection Screen -->
    <div v-if="currentScreen === 'services'" class="service-screen">
      <h2>Laundry Services</h2>
      <p class="intro-text">Select from our professional laundry services below:</p>

      <div class="service-grid">
        <div v-for="service in availableServices" :key="service.id" class="service-card">
          <h3>{{ service.name }}</h3>
          <p>{{ service.description }}</p>
          <button @click="selectService(service)" class="service-button">Select Service</button>
        </div>
      </div>

      <div v-if="cart.length > 0" class="cart-summary">
        <h3>Current Order</h3>
        <div v-for="(serviceOrder, index) in cart" :key="index" class="cart-item">
          <h4>{{ serviceOrder.service.name }}</h4>
          <div v-for="item in serviceOrder.items" :key="item.id" class="cart-item-detail">
            <span v-if="item.quantity > 0">{{ item.name }} x {{ item.quantity }}</span>
          </div>
        </div>
        <p class="cart-total">Total: Ksh {{ totalOrderCost }}</p>
        <div class="action-buttons">
          <button @click="proceedToCheckout" class="primary-button">Complete Order</button>
          <button @click="cancelOrder" class="secondary-button">Cancel Order</button>
        </div>
      </div>
    </div>

    <!-- Service Modal -->
    <div v-if="selectedService && currentScreen === 'modal'" class="modal-overlay">
      <div class="modal-box">
        <h3>{{ selectedService.name }} Service</h3>
        <p>{{ selectedService.description }}</p>

        <div class="items-grid">
          <div v-for="(item, index) in currentItems" :key="index" class="item-row">
            <div class="item-info">
              <span class="item-name">{{ item.name }}</span>
              <span class="item-price">Ksh {{ item.price }}</span>
              <span v-if="item.description" class="item-description">{{ item.description }}</span>
            </div>
            <div class="item-controls">
              <button @click="decrementQuantity(item)" class="quantity-btn">-</button>
              <input v-model.number="item.quantity" type="number" min="0" class="quantity-input" />
              <button @click="incrementQuantity(item)" class="quantity-btn">+</button>
            </div>
          </div>
        </div>

        <div class="service-total">
          <p>Service Total: Ksh {{ currentServiceTotal }}</p>
        </div>

        <div class="modal-actions">
          <button @click="addToCart" class="primary-button">Add to Order</button>
          <button @click="closeModal" class="secondary-button">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

// Available services with detailed descriptions
const availableServices = [
  {
    id: 'wash-fold',
    name: 'Wash and Fold',
    description: 'Our comprehensive wash and fold service handles all your everyday laundry needs with care. We use premium detergents and fabric softeners to ensure your clothes are thoroughly cleaned, perfectly dried, and neatly folded, ready to be placed directly into your wardrobe.'
  },
  {
    id: 'dry-cleaning',
    name: 'Dry Cleaning',
    description: 'Our professional dry cleaning service is perfect for delicate fabrics and special garments that require extra care. Using specialized solvents and techniques, we remove stains and clean your valuable items without water damage, preserving colors and extending the life of your fine clothing.'
  },
  {
    id: 'ironing',
    name: 'Ironing and Pressing',
    description: 'Achieve that perfectly crisp, wrinkle-free look with our expert ironing and pressing service. Our skilled professionals use premium equipment to ensure every garment receives the appropriate treatment, from light pressing for delicate fabrics to sharp creases for formal attire.'
  },
  {
    id: 'express',
    name: 'Express Service',
    description: 'When time is of the essence, our express service delivers rapid results without compromising on quality. With same-day turnaround available, your urgent laundry needs are handled efficiently, perfect for last-minute events or unexpected situations that require immediate attention.'
  }
];

// Items for each service with descriptions
const serviceItems = {
  'wash-fold': [
    { id: 'wf-tshirt', name: 'T-Shirts', price: 30, quantity: 0, description: 'Casual cotton t-shirts' },
    { id: 'wf-shirt', name: 'Shirts', price: 35, quantity: 0, description: 'Button-up casual or formal shirts' },
    { id: 'wf-trousers', name: 'Trousers', price: 40, quantity: 0, description: 'Regular or formal trousers' },
    { id: 'wf-jeans', name: 'Jeans', price: 45, quantity: 0, description: 'Denim jeans of any style' },
    { id: 'wf-shorts', name: 'Shorts', price: 25, quantity: 0, description: 'Casual or sports shorts' },
    { id: 'wf-bedsheet', name: 'Bedsheets (Single)', price: 50, quantity: 0, description: 'Single bed sheets and pillowcases' },
    { id: 'wf-bedsheet-double', name: 'Bedsheets (Double)', price: 70, quantity: 0, description: 'Double or queen bed sheets' },
    { id: 'wf-blanket-small', name: 'Blankets (Small)', price: 80, quantity: 0, description: 'Throw blankets or small coverings' },
    { id: 'wf-blanket-large', name: 'Blankets (Large)', price: 120, quantity: 0, description: 'Large comforters and duvets' },
    { id: 'wf-towel', name: 'Towels', price: 40, quantity: 0, description: 'Bath and hand towels' },
    { id: 'wf-pillowcase', name: 'Pillowcases', price: 15, quantity: 0, description: 'Individual pillowcases' }
  ],
  'dry-cleaning': [
    { id: 'dc-suit', name: 'Suits (2-piece)', price: 150, quantity: 0, description: 'Jacket and trousers/skirt' },
    { id: 'dc-suit-3pc', name: 'Suits (3-piece)', price: 200, quantity: 0, description: 'Jacket, trousers/skirt, and waistcoat' },
    { id: 'dc-dress', name: 'Dresses (Simple)', price: 120, quantity: 0, description: 'Casual or light fabric dresses' },
    { id: 'dc-dress-formal', name: 'Dresses (Formal)', price: 180, quantity: 0, description: 'Evening gowns and special occasion dresses' },
    { id: 'dc-coat', name: 'Coats', price: 130, quantity: 0, description: 'Heavy winter coats and overcoats' },
    { id: 'dc-jacket', name: 'Jackets', price: 100, quantity: 0, description: 'Light jackets and blazers' },
    { id: 'dc-trousers', name: 'Trousers', price: 100, quantity: 0, description: 'Formal or special fabric trousers' },
    { id: 'dc-skirt', name: 'Skirts', price: 90, quantity: 0, description: 'Simple or pleated skirts' },
    { id: 'dc-blazer', name: 'Blazers', price: 110, quantity: 0, description: 'Formal blazers and sport coats' },
    { id: 'dc-sweater', name: 'Sweaters', price: 100, quantity: 0, description: 'Wool, cashmere or delicate knit sweaters' },
    { id: 'dc-blanket', name: 'Blankets', price: 200, quantity: 0, description: 'Special care blankets and comforters' }
  ],
  'ironing': [
    { id: 'ir-shirt', name: 'Shirts', price: 20, quantity: 0, description: 'Button-up shirts with perfect collar and cuff press' },
    { id: 'ir-tshirt', name: 'T-Shirts', price: 15, quantity: 0, description: 'Casual t-shirts with smooth finish' },
    { id: 'ir-trousers', name: 'Trousers', price: 25, quantity: 0, description: 'Formal trousers with sharp crease' },
    { id: 'ir-jeans', name: 'Jeans', price: 30, quantity: 0, description: 'Denim jeans with structured finish' },
    { id: 'ir-dress', name: 'Dresses (Simple)', price: 30, quantity: 0, description: 'Light fabric casual dresses' },
    { id: 'ir-dress-complex', name: 'Dresses (Complex)', price: 45, quantity: 0, description: 'Multi-layered or detailed dresses' },
    { id: 'ir-skirt', name: 'Skirts', price: 25, quantity: 0, description: 'All types of skirts with proper pleating when needed' },
    { id: 'ir-blouse', name: 'Blouses', price: 25, quantity: 0, description: 'Women\'s tops with detailed attention to collars and cuffs' },
    { id: 'ir-bedsheet', name: 'Bedsheets', price: 35, quantity: 0, description: 'Smooth and crisp finish for all bedsheets' },
    { id: 'ir-pillowcase', name: 'Pillowcases', price: 10, quantity: 0, description: 'Perfect corners and smooth finish' },
    { id: 'ir-tablecloth', name: 'Tablecloths', price: 40, quantity: 0, description: 'Wrinkle-free finish for table linens' }
  ],
  'express': [
    { id: 'ex-shirt', name: 'Shirts', price: 50, quantity: 0, description: 'Rushed wash and press service for urgent needs' },
    { id: 'ex-tshirt', name: 'T-Shirts', price: 40, quantity: 0, description: '3-hour turnaround for casual tops' },
    { id: 'ex-trousers', name: 'Trousers', price: 60, quantity: 0, description: 'Same-day cleaning and pressing' },
    { id: 'ex-jeans', name: 'Jeans', price: 65, quantity: 0, description: 'Quick wash and dry for denim' },
    { id: 'ex-dress-light', name: 'Light Dresses', price: 70, quantity: 0, description: 'Fast service for simple dresses' },
    { id: 'ex-suit', name: 'Suits', price: 250, quantity: 0, description: 'Priority handling for business and formal suits' },
    { id: 'ex-jacket', name: 'Jackets', price: 150, quantity: 0, description: 'Quick turnaround for outerwear' },
    { id: 'ex-blouse', name: 'Blouses', price: 55, quantity: 0, description: 'Express cleaning for women\'s tops' },
    { id: 'ex-skirt', name: 'Skirts', price: 60, quantity: 0, description: 'Same-day service for skirts' },
    { id: 'ex-bedsheet', name: 'Bedsheets', price: 90, quantity: 0, description: 'Urgent cleaning for bedding' }
  ]
};

// State management
const currentScreen = ref('services');
const selectedService = ref(null);
const currentItems = ref([]);
const cart = ref([]);

// Computed properties
const currentServiceTotal = computed(() => {
  return currentItems.value.reduce((sum, item) => sum + (item.price * item.quantity), 0);
});

const totalOrderCost = computed(() => {
  return cart.value.reduce((total, serviceOrder) => {
    return total + calculateServiceTotal(serviceOrder.items);
  }, 0);
});

// Methods
function selectService(service) {
  selectedService.value = service;
  // Create a deep copy of the service items to avoid modifying the original data
  currentItems.value = JSON.parse(JSON.stringify(serviceItems[service.id]));
  currentScreen.value = 'modal';
}

function incrementQuantity(item) {
  item.quantity++;
}

function decrementQuantity(item) {
  if (item.quantity > 0) {
    item.quantity--;
  }
}

function calculateServiceTotal(items) {
  return items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
}

function addToCart() {
  // Filter out items with zero quantity
  const itemsWithQuantity = currentItems.value.filter(item => item.quantity > 0);

  // Only add to cart if there are items selected
  if (itemsWithQuantity.length > 0) {
    cart.value.push({
      service: selectedService.value,
      items: currentItems.value
    });
  }

  // Return to services screen
  closeModal();
}

function closeModal() {
  selectedService.value = null;
  currentItems.value = [];
  currentScreen.value = 'services';
}

function proceedToCheckout() {
  // Store cart data in localStorage to share with thank you page
  localStorage.setItem('laundryCart', JSON.stringify(cart.value));
  // Navigate to thank you page
  router.push('/thankyou');
}

function cancelOrder() {
  cart.value = [];
}
</script>

<style scoped>
.laundry-app {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem;
}

.intro-text {
  text-align: center;
  margin-bottom: 2rem;
  font-size: 1.1rem;
  color: #555;
}

.service-screen {
  text-align: center;
}

.service-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 2rem;
  margin: 2rem 0;
}

.service-card {
  background-color: #f9f9f9;
  border-radius: 10px;
  padding: 1.5rem;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  text-align: left;
  transition: transform 0.2s ease;
}

.service-card:hover {
  transform: translateY(-5px);
}

.service-card h3 {
  color: #0078d7;
  margin-bottom: 1rem;
}

.service-card p {
  color: #555;
  margin-bottom: 1.5rem;
  line-height: 1.5;
}

.service-button {
  padding: 0.75rem 1.5rem;
  background-color: #0078d7;
  color: white;
  border: none;
  border-radius: 6px;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.2s ease;
  width: 100%;
}

.service-button:hover {
  background-color: #005a9e;
}

.cart-summary {
  margin-top: 3rem;
  padding: 1.5rem;
  background-color: #f5f5f5;
  border-radius: 10px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  text-align: left;
}

.cart-item {
  margin-bottom: 1.5rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid #ddd;
}

.cart-item h4 {
  margin-bottom: 0.5rem;
  color: #0078d7;
}

.cart-item-detail {
  margin-left: 1rem;
  line-height: 1.6;
}

.cart-total {
  font-size: 1.3rem;
  font-weight: bold;
  margin-top: 1.5rem;
}

.action-buttons {
  display: flex;
  justify-content: center;
  gap: 1.5rem;
  margin-top: 2rem;
}

.primary-button {
  padding: 0.75rem 2rem;
  background-color: #0078d7;
  color: white;
  border: none;
  border-radius: 6px;
  font-weight: 600;
  cursor: pointer;
  font-size: 1rem;
}

.secondary-button {
  padding: 0.75rem 2rem;
  background-color: #f0f0f0;
  color: #333;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-weight: 600;
  cursor: pointer;
  font-size: 1rem;
}

/* Modal */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.6);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-box {
  background-color: white;
  border-radius: 12px;
  padding: 2.5rem;
  width: 90%;
  max-width: 700px;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}

.modal-box h3 {
  color: #0078d7;
  margin-bottom: 0.5rem;
}

.modal-box > p {
  color: #555;
  margin-bottom: 2rem;
  line-height: 1.5;
}

.items-grid {
  margin: 1.5rem 0;
}

.item-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 0;
  border-bottom: 1px solid #eee;
}

.item-info {
  display: flex;
  flex-direction: column;
  max-width: 70%;
}

.item-name {
  font-weight: 600;
  font-size: 1.1rem;
}

.item-price {
  color: #0078d7;
  font-weight: 600;
  margin: 0.2rem 0;
}

.item-description {
  color: #666;
  font-size: 0.9rem;
  margin-top: 0.3rem;
}

.item-controls {
  display: flex;
  align-items: center;
}

.quantity-btn {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  background-color: #f0f0f0;
  border: 1px solid #ddd;
  font-size: 1.2rem;
  display: flex;
  justify-content: center;
  align-items: center;
  cursor: pointer;
}

.quantity-input {
  width: 50px;
  text-align: center;
  margin: 0 0.5rem;
  padding: 0.25rem;
  border: 1px solid #ddd;
  border-radius: 4px;
}

.service-total {
  font-size: 1.2rem;
  font-weight: bold;
  margin: 1.5rem 0;
  text-align: right;
  color: #0078d7;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  margin-top: 2rem;
}
</style>
