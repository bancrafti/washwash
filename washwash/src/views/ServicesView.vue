<template>
  <div class="services-page">
    <div class="services-sidebar">
      <h1 class="main-heading">Our Services</h1>
      <div class="services-list">
        <div
          v-for="(service, index) in services"
          :key="index"
          class="service-card"
          :class="{ active: selectedService === index }"
          @click="selectService(index)"
        >
          <h2 class="service-title">{{ service.name }}</h2>
          <p class="service-brief">{{ service.brief }}</p>
        </div>
      </div>
    </div>

    <div class="service-details">
      <transition name="fade" mode="out-in">
        <div :key="selectedService" class="details-content">
          <h1 class="details-heading">{{ services[selectedService].name }}</h1>
          <div class="details-image-container">
            <img :src="services[selectedService].image" :alt="services[selectedService].name" class="details-image" />
          </div>
          <div class="details-text">
            <p v-for="(paragraph, i) in services[selectedService].description" :key="i" class="details-paragraph">
              {{ paragraph }}
            </p>
            <div class="cta-container">
              <button class="book-now-btn">Book This Service</button>
              <button class="pricing-btn">View Pricing</button>
            </div>
          </div>
        </div>
      </transition>
    </div>
  </div>
</template>

<script>
import washAndFold from '@/assets/images/wash-and-fold.webp';
import dryCleaning from '@/assets/images/dry-cleaning.jpg';
import ironingAndPressing from '@/assets/images/ironing-and-pressing.jpg';
import expressLaundry from '@/assets/images/express-laundry.jpg';

export default {
  data() {
    return {
      selectedService: 0,
      services: [
        {
          name: "Wash & Fold",
          brief: "Perfect for everyday laundry needs",
          image: washAndFold,
          description: [
            "Our Wash & Fold service is perfect for everyday laundry needs. We carefully sort, wash, dry, and fold your items with attention to detail.",
            "This service is ideal for casual wear, bed linens, and towels. Your items will be returned fresh, clean, and neatly folded."
          ]
        },
        {
          name: "Dry Cleaning",
          brief: "Best for delicate fabrics and formal attire",
          image: dryCleaning,
          description: [
            "Dry Cleaning is the best option for delicate fabrics and garments that require special care. Our professional process removes stains while protecting fabric integrity.",
            "This service is ideal for suits, dresses, formal attire, and garments with 'dry clean only' labels."
          ]
        },
        {
          name: "Ironing & Pressing",
          brief: "For perfectly smooth, wrinkle-free clothes",
          image: ironingAndPressing,
          description: [
            "Our Ironing & Pressing service ensures your clothes are perfectly smooth and wrinkle-free. Each item receives careful attention from our experienced staff.",
            "Ideal for business wear, uniforms, dress shirts, and special occasion garments. We can also press previously laundered items."
          ]
        },
        {
          name: "Express Laundry",
          brief: "Same-day service for urgent needs",
          image: expressLaundry,
          description: [
            "Need your laundry done fast? Our Express Laundry service is the solution with same-day turnaround for items dropped off before 10am.",
            "Perfect for busy individuals, professionals, and last-minute needs. All the quality of our standard service, just faster!"
          ]
        }
      ]
    };
  },
  methods: {
    selectService(index) {
      this.selectedService = index;
    }
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
.pricing-btn {
  background-color: transparent;
  color: #3498db;
  border: 2px solid #3498db;
  padding: 1rem 2rem;
  border-radius: 8px;
  font-size: 1.1rem;
  cursor: pointer;
  transition: all 0.3s ease;
}
.pricing-btn:hover {
  background-color: #f0f8ff;
}
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s, transform 0.5s;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
  transform: translateY(20px);
}
</style>
