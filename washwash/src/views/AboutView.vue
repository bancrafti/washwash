<script setup lang="ts">
import NavigationBar from '@/components/NavigationBar.vue';
</script>
<template>
<NavigationBar/>
  <div class="page-container">

    <div class="content">
      <h1>About WashWash</h1>
      <p>
        WashWash is your trusted online laundry service, offering a seamless and eco-friendly
        experience. We provide high-quality wash & fold, dry cleaning, ironing, and express services.
      </p>

      <div class="section">
        <h2>Our Mission</h2>
        <p>To deliver fast, reliable, and eco-conscious laundry services, making life easier for you.</p>
      </div>

      <div class="features">
        <div class="feature">
          <h3>🚀 Speed & Efficiency</h3>
          <p>Express service for urgent needs.</p>
        </div>
        <div class="feature">
          <h3>👕 Fabric-Friendly Care</h3>
          <p>Professional cleaning for all fabrics.</p>
        </div>
        <div class="feature">
          <h3>📲 Easy Online Booking</h3>
          <p>Schedule pickups & track orders online.</p>
        </div>
        <div class="feature">
          <h3>♻️ Eco-Friendly Cleaning</h3>
          <p>Safe for your clothes and the environment.</p>
        </div>
      </div>

      <a href="/contact" class="contact-btn">Get in Touch</a>
    </div>
  </div>
</template>

<style scoped>
.page-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  background-color: #f4f4f4;
  min-height: 83vh;
  padding-top: 4em; /* Prevent navbar overlap */
}

.content {
  max-width: 800px;
  text-align: center;
  padding: 40px;
  background: white;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  border-radius: 10px;
}

h1 {
  font-size: 2.5rem;
  color: #0077b6;
}

h2 {
  font-size: 2rem;
  color: #005f8b;
}

p {
  font-size: 1.1rem;
  color: #333;
}

.features {
  display: flex;
  flex-wrap: wrap;
  gap: 1em;
  justify-content: space-between;
  margin-top: 30px;
}

.feature {
  width: 45%;
  background: #e3f2fd;
  padding: 15px;
  border-radius: 8px;
}

.contact-btn {
  display: inline-block;
  margin-top: 20px;
  padding: 10px 20px;
  background: #0077b6;
  color: white;
  text-decoration: none;
  border-radius: 5px;
  transition: 0.3s;
}

.contact-btn:hover {
  background: #005f8b;
}
</style>
