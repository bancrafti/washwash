<template>
  <div class="services-page">
    <div class="services-sidebar">
      <h1 class="main-heading">Our Services</h1>
      <div class="services-list">
        <div
          v-for="(service, index) in availableServices"
          :key="service.id"
          class="service-card"
          :class="{ active: selectedService === index }"
          @click="selectService(index)"
        >
          <h2 class="service-title">{{ service.name }}</h2>
          <p class="service-brief">{{ service.brief }}</p> <!-- Brief here -->
        </div>
      </div>
    </div>

    <div class="service-details">
      <transition name="fade" mode="out-in">
        <div v-if="currentScreen === 'services'" :key="selectedService" class="details-content">
          <h1 class="details-heading">{{ availableServices[selectedService].name }}</h1>
          <div class="details-image-container">
            <img :src="availableServices[selectedService].image" :alt="availableServices[selectedService].name" class="details-image" />
          </div>
          <div class="details-text">
            <!-- Full description here -->
            <p v-for="(paragraph, i) in availableServices[selectedService].description" :key="i" class="details-paragraph">
              {{ paragraph }}
            </p>
            <div class="cta-container">
              <button @click="openModal" class="book-now-btn">Select Service</button>
            </div>
          </div>
        </div>
      </transition>
    </div>
  </div>

  <!-- Modal -->
  <div v-if="selectedService !== null && currentScreen === 'modal'" class="modal-overlay">
    <div class="modal-box">
      <h3>{{ availableServices[selectedService].name }} Service</h3>
      <p>{{ availableServices[selectedService].description }}</p>

      <div class="items-grid">
        <div v-for="(item, index) in serviceItems[availableServices[selectedService].id]" :key="index" class="item-row">
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
</template>

<script>
import { ref, computed } from 'vue';
import washAndFold from '@/assets/images/wash-and-fold.webp';
import dryCleaning from '@/assets/images/dry-cleaning.jpg';
import ironingAndPressing from '@/assets/images/ironing-and-pressing.jpg';
import expressLaundry from '@/assets/images/express-laundry.jpg';

export default {
  setup() {
    const selectedService = ref(0);
    const currentScreen = ref('services');
    const availableServices = ref([
      {
        id: 'wash-fold',
        name: 'Wash and Fold',
        brief: "Perfect for everyday laundry needs", // Brief displayed in sidebar
        image: washAndFold,
        description: [
          "Our Wash & Fold service is perfect for everyday laundry needs. We carefully sort, wash, dry, and fold your items with attention to detail.",
          "This service is ideal for casual wear, bed linens, and towels. Your items will be returned fresh, clean, and neatly folded."
        ]
      },
      {
        id: 'dry-cleaning',
        name: 'Dry Cleaning',
        brief: "Best for delicate fabrics and formal attire", // Brief displayed in sidebar
        image: dryCleaning,
        description: [
          "Dry Cleaning is the best option for delicate fabrics and garments that require special care. Our professional process removes stains while protecting fabric integrity.",
          "This service is ideal for suits, dresses, formal attire, and garments with 'dry clean only' labels."
        ]
      },
      {
        id: 'ironing',
        name: 'Ironing and Pressing',
        brief: "For perfectly smooth, wrinkle-free clothes", // Brief displayed in sidebar
        image: ironingAndPressing,
        description: [
          "Our Ironing & Pressing service ensures your clothes are perfectly smooth and wrinkle-free. Each item receives careful attention from our experienced staff.",
          "Ideal for business wear, uniforms, dress shirts, and special occasion garments. We can also press previously laundered items."
        ]
      },
      {
        id: 'express',
        name: 'Express Service',
        brief: "Same-day service for urgent needs", // Brief displayed in sidebar
        image: expressLaundry,
        description: [
          "Need your laundry done fast? Our Express Laundry service is the solution with same-day turnaround for items dropped off before 10am.",
          "Perfect for busy individuals, professionals, and last-minute needs. All the quality of our standard service, just faster!"
        ]
      }
    ]);

    const serviceItems = ref({
      'wash-fold': [
        { id: 'wf-tshirt', name: 'T-Shirts', price: 30, quantity: 0, description: 'Casual cotton t-shirts' },
        { id: 'wf-shirt', name: 'Shirts', price: 35, quantity: 0, description: 'Button-up casual or formal shirts' },
        { id: 'wf-trousers', name: 'Trousers', price: 40, quantity: 0, description: 'Regular or formal trousers' }
      ],
      'dry-cleaning': [
        { id: 'dc-suit', name: 'Suits (2-piece)', price: 150, quantity: 0, description: 'Jacket and trousers/skirt' },
        { id: 'dc-dress', name: 'Dresses (Formal)', price: 180, quantity: 0, description: 'Evening gowns and special occasion dresses' }
      ]
    });

    const cart = ref([]);
    const currentServiceTotal = computed(() => {
      return cart.value.reduce((total, serviceOrder) => {
        return total + serviceOrder.items.reduce((subTotal, item) => {
          return subTotal + (item.price * item.quantity);
        }, 0);
      }, 0);
    });

    const selectService = (index) => {
      selectedService.value = index;
      currentScreen.value = 'services'; // Ensure screen is 'services' when service is selected
    };

    const openModal = () => {
      currentScreen.value = 'modal';
    };

    const closeModal = () => {
      currentScreen.value = 'services';
    };

    const incrementQuantity = (item) => {
      item.quantity += 1;
    };

    const decrementQuantity = (item) => {
      if (item.quantity > 0) item.quantity -= 1;
    };

    const addToCart = () => {
      const serviceOrder = {
        service: availableServices.value[selectedService.value],
        items: serviceItems.value[availableServices.value[selectedService.value].id].filter(item => item.quantity > 0)
      };
      cart.value.push(serviceOrder);
      closeModal();
    };

    return {
      selectedService,
      currentScreen,
      availableServices,
      serviceItems,
      cart,
      currentServiceTotal,
      selectService,
      openModal,
      closeModal,
      incrementQuantity,
      decrementQuantity,
      addToCart
    };
  }
};
</script>


<style scoped>
.services-page {
  display: flex;
  height: 100vh;
  background-color: #f9f9f9;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  overflow: hidden;
}
.services-sidebar {
  width: 30%;
  min-width: 280px;
  background-color: white;
  box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
  padding: 2rem 1rem;
  overflow-y: auto;
}
.main-heading {
  font-size: 1.8rem;
  color: #2c3e50;
  margin-bottom: 2rem;
  text-align: center;
  font-weight: 600;
}
.service-card {
  background-color: #f8f9fa;
  border-radius: 12px;
  padding: 1.5rem;
  cursor: pointer;
  transition: all 0.3s ease;
  border-left: 4px solid transparent;
}
.service-card.active {
  border-left-color: #3498db;
  background-color: #ebf5fb;
}
.service-details {
  flex: 1;
  padding: 3rem;
  overflow-y: auto;
}
.details-image-container {
  width: 100%;
  height: 350px;
  overflow: hidden;
  border-radius: 12px;
  margin-bottom: 2rem;
}
.details-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}
.details-image:hover {
  transform: scale(1.03);
}
.cta-container {
  display: flex;
  gap: 1rem;
  margin-top: 1.5rem;
  justify-content: center;
}
.book-now-btn {
  background-color: #3498db;
  color: white;
  padding: 1rem 2rem;
  border-radius: 8px;
  font-size: 1.1rem;
  cursor: pointer;
  transition: background-color 0.3s ease;
  border: none;
}
.book-now-btn:hover {
  background-color: #2980b9;
}
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s, transform 0.5s;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
  transform: translateY(20px);
}

/* Modal */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}
.modal-box {
  background-color: white;
  border-radius: 12px;
  padding: 2rem;
  width: 60%;
  max-width: 800px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}
.items-grid {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}
.item-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.5rem;
  background-color: #f8f9fa;
  border-radius: 8px;
}
.item-info {
  display: flex;
  flex-direction: column;
}
.item-name {
  font-weight: bold;
}
.item-price {
  color: #2c3e50;
}
.item-controls {
  display: flex;
  gap: 0.5rem;
}
.quantity-input {
  width: 50px;
  text-align: center;
  padding: 0.5rem;
}
.quantity-btn {
  background-color: #3498db;
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 5px;
  cursor: pointer;
}
.quantity-btn:hover {
  background-color: #2980b9;
}
.service-total {
  text-align: center;
  margin-top: 2rem;
  font-size: 1.2rem;
  font-weight: bold;
}
.primary-button, .secondary-button {
  padding: 1rem 2rem;
  border-radius: 8px;
  cursor: pointer;
  font-size: 1.2rem;
  border: none;
}
.primary-button {
  background-color: #3498db;
  color: white;
}
.primary-button:hover {
  background-color: #2980b9;
}
.secondary-button {
  background-color: #ccc;
  color: #333;
  margin-left: 1rem;
}
.secondary-button:hover {
  background-color: #bbb;
}
</style>
