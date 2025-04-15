<template>
  <div class="laundry-app">
    <h1 class="main-title">WashWash Laundry Services</h1>
    <p class="intro-text">Professional laundry services tailored to your needs</p>

    <div class="content-columns">
      <!-- Left Column: Services List -->
      <div class="services-list-column">
        <h2 class="column-title">Our Services</h2>
        <div class="services-list">
          <div
            v-for="service in availableServices"
            :key="service.id"
            class="service-list-item"
            :class="{ 'active': selectedViewService && selectedViewService.id === service.id }"
            @click="viewServiceDetails(service)"
          >
            <div class="service-icon">{{ getServiceIcon(service.id) }}</div>
            <div class="service-list-name">{{ service.name }}</div>
          </div>
        </div>

        <!-- Cart Summary (moved to left column) -->
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

      <!-- Right Column: Service Details -->
      <div class="service-details-column">
        <div v-if="selectedViewService" class="service-details">
          <h2 class="service-title">{{ selectedViewService.name }}</h2>
          <div class="service-image">
            <div class="image-placeholder">
              <span class="service-icon-large">{{ getServiceIcon(selectedViewService.id) }}</span>
            </div>
          </div>
          <div class="service-description">
            <p>{{ selectedViewService.description }}</p>
          </div>
          <div class="service-features">
            <h3>Features</h3>
            <ul>
              <li v-for="(feature, index) in getServiceFeatures(selectedViewService.id)" :key="index">
                {{ feature }}
              </li>
            </ul>
          </div>
          <div class="pricing-preview">
            <h3>Sample Pricing</h3>
            <div class="pricing-items">
              <div v-for="(item, index) in getSamplePricing(selectedViewService.id)" :key="index" class="price-item">
                <span class="item-name">{{ item.name }}</span>
                <span class="item-price">Ksh {{ item.price }}</span>
              </div>
            </div>
          </div>
          <button @click="selectService(selectedViewService)" class="select-service-btn">Select {{ selectedViewService.name }}</button>
        </div>
        <div v-else class="no-service-selected">
          <div class="placeholder-message">
            <div class="message-icon">👈</div>
            <p>Please select a service from the list to view details</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Service Modal (remains the same functionality) -->
    <div v-if="selectedService && currentScreen === 'modal'" class="modal-overlay">
      <div class="modal-box">
        <div class="modal-header">
          <h3>{{ selectedService.name }} Service</h3>
          <button @click="closeModal" class="close-button">×</button>
        </div>
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

// Service features for the right panel
const serviceFeatures = {
  'wash-fold': [
    'Premium eco-friendly detergents',
    'Gentle cycle for delicate items',
    'Temperature-controlled drying',
    'Neat fold and packaging',
    'Stain treatment at no extra cost'
  ],
  'dry-cleaning': [
    'Specialized solvents for sensitive fabrics',
    'Stain removal expertise',
    'Color preservation techniques',
    'Gentle pressing and finishing',
    'Proper garment handling and storage'
  ],
  'ironing': [
    'Professional-grade equipment',
    'Steam treatment for stubborn wrinkles',
    'Perfect creases and pleats',
    'Specialized care for different fabrics',
    'Careful attention to details and finishes'
  ],
  'express': [
    'Same-day turnaround',
    'Priority handling',
    'SMS notifications',
    'Express delivery options',
    'Quality inspection before delivery'
  ]
};

// Service icons for visual representation
const getServiceIcon = (serviceId) => {
  const icons = {
    'wash-fold': '🧺',
    'dry-cleaning': '👔',
    'ironing': '🔥',
    'express': '⚡'
  };
  return icons[serviceId] || '📦';
};

// Get feature list for a service
const getServiceFeatures = (serviceId) => {
  return serviceFeatures[serviceId] || [];
};

// Get sample pricing for preview (just show 3 items)
const getSamplePricing = (serviceId) => {
  return serviceItems[serviceId].slice(0, 3);
};

// State management
const currentScreen = ref('services');
const selectedService = ref(null);
const selectedViewService = ref(null); // For displaying in the right column
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
function viewServiceDetails(service) {
  selectedViewService.value = service;
}

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
  color: #333;
}

.main-title {
  text-align: center;
  color: #0078d7;
  margin-bottom: 0.5rem;
}

.intro-text {
  text-align: center;
  margin-bottom: 2rem;
  font-size: 1.1rem;
  color: #555;
}

/* Two-column layout */
.content-columns {
  display: flex;
  gap: 2rem;
  margin-top: 2rem;
}

.column-title {
  color: #0078d7;
  margin-bottom: 1.5rem;
  font-size: 1.5rem;
  border-bottom: 2px solid #0078d7;
  padding-bottom: 0.5rem;
}

/* Left column - Services List */
.services-list-column {
  flex: 1;
  background-color: #f9f9f9;
  border-radius: 10px;
  padding: 1.5rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  max-width: 300px;
}

.services-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.service-list-item {
  display: flex;
  align-items: center;
  padding: 1rem;
  border-radius: 8px;
  background-color: white;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  cursor: pointer;
  transition: all 0.2s ease;
}

.service-list-item:hover {
  transform: translateX(5px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.service-list-item.active {
  background-color: #e6f2ff;
  border-left: 4px solid #0078d7;
}

.service-icon {
  font-size: 1.8rem;
  margin-right: 1rem;
}

.service-list-name {
  font-weight: 600;
}

/* Right column - Service Details */
.service-details-column {
  flex: 2;
  background-color: white;
  border-radius: 10px;
  padding: 2rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.service-details {
  height: 100%;
}

.service-title {
  color: #0078d7;
  margin-bottom: 1.5rem;
  font-size: 1.8rem;
}

.service-image {
  height: 200px;
  background-color: #f0f8ff;
  border-radius: 10px;
  display: flex;
  justify-content: center;
  align-items: center;
  margin-bottom: 1.5rem;
  overflow: hidden;
}

.image-placeholder {
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  background: linear-gradient(45deg, #e6f2ff, #ccdfff);
}

.service-icon-large {
  font-size: 5rem;
}

.service-description {
  line-height: 1.6;
  margin-bottom: 2rem;
  color: #444;
}

.service-features {
  margin-bottom: 2rem;
}

.service-features h3 {
  color: #0078d7;
  margin-bottom: 0.75rem;
}

.service-features ul {
  list-style-type: none;
  padding: 0;
}

.service-features li {
  padding: 0.5rem 0;
  position: relative;
  padding-left: 1.5rem;
}

.service-features li:before {
  content: "✓";
  color: #0078d7;
  position: absolute;
  left: 0;
}

.pricing-preview {
  margin-bottom: 2rem;
}

.pricing-preview h3 {
  color: #0078d7;
  margin-bottom: 0.75rem;
}

.pricing-items {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.price-item {
  display: flex;
  justify-content: space-between;
  padding: 0.5rem;
  background-color: #f9f9f9;
  border-radius: 4px;
}

.item-price {
  font-weight: 600;
  color: #0078d7;
}

.select-service-btn {
  padding: 0.75rem 1.5rem;
  background-color: #0078d7;
  color: white;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  width: 100%;
  transition: background-color 0.2s ease;
  font-size: 1rem;
}

.select-service-btn:hover {
  background-color: #005a9e;
}

.no-service-selected {
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
}

.placeholder-message {
  text-align: center;
  color: #888;
}

.message-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
}

/* Cart Summary (moved to left column) */
.cart-summary {
  margin-top: 2rem;
  padding: 1.5rem;
  background-color: white;
  border-radius: 10px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.cart-summary h3 {
  color: #0078d7;
  margin-bottom: 1rem;
}

.cart-item {
  margin-bottom: 1rem;
  padding-bottom: 0.75rem;
  border-bottom: 1px solid #eee;
}

.cart-item h4 {
  margin-bottom: 0.5rem;
  color: #0078d7;
  font-size: 0.95rem;
}

.cart-item-detail {
  margin-left: 0.75rem;
  font-size: 0.9rem;
  line-height: 1.4;
}

.cart-total {
  font-weight: bold;
  margin: 1rem 0;
}

.action-buttons {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.primary-button, .secondary-button {
  padding: 0.75rem;
  border-radius: 6px;
  font-weight: 600;
  cursor: pointer;
  font-size: 0.9rem;
  width: 100%;
}

.primary-button {
  background-color: #0078d7;
  color: white;
  border: none;
}

.secondary-button {
  background-color: #f0f0f0;
  color: #333;
  border: 1px solid #ddd;
}

/* Modal Styling (kept similar with slight improvements) */
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
  padding: 2rem;
  width: 90%;
  max-width: 700px;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.close-button {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #888;
}

.modal-box h3 {
  color: #0078d7;
  margin-bottom: 0.5rem;
}

.modal-box > p {
  color: #555;
  margin-bottom: 1.5rem;
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
  margin-top: 1.5rem;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
  .content-columns {
    flex-direction: column;
  }

  .services-list-column, .service-details-column {
    max-width: 100%;
  }

  .service-image {
    height: 150px;
  }
}
</style>
