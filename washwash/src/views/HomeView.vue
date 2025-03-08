<script setup lang="ts">
import NavigationBar from '@/components/NavigationBar.vue';
</script>

<template>
  <NavigationBar />

  <section id="headline">
    <img src="@/assets/images/wash.jpg" alt="A washing machine image">
    <div class="overlay">
      <h1>Laundry Made Simple</h1>
      <button class="book-now">Book Now</button>
    </div>
  </section>

  <section class="services-container">
    <div class="service" v-for="(service, index) in services" :key="index">
      <img :src="service.image" :alt="service.name">
      <div class="description">{{ service.name }}</div>
    </div>
  </section>
</template>

<style scoped>
/* Headline Section */
#headline {
  margin-top: 1em;
  position: relative;
  width: 100%;
  height: 80vh;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  border-radius: 0.5em;
}

#headline img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  filter: brightness(0.5);
}

.overlay {
  position: absolute;
  text-align: center;
  color: white;
}

.overlay h1 {
  font-size: clamp(2rem, 6vw, 4rem);
  font-weight: bold;
  margin-bottom: 1rem;
}

.book-now {
  padding: 0.8em 1.5em;
  font-size: 1.2rem;
  font-weight: bold;
  border: 2px solid white;
  color: white;
  background: transparent;
  border-radius: 50px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.book-now:hover {
  background: white;
  color: black;
}

/* Services Section */
.services-container {
  display: grid;
  gap: 1em;
  margin: 2em auto;
  padding: 0 1em;
  max-width: 1200px;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
}

.service {
  position: relative;
  border-radius: 0.5em;
  overflow: hidden;
  background: black;
}

.service img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.service:hover img {
  transform: scale(1.05);
}

.description {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: white;
  font-weight: bold;
  font-size: 1.5rem;
  text-align: center;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
  #headline {
    height: 60vh;
  }

  .overlay h1 {
    font-size: clamp(1.8rem, 5vw, 3rem);
  }

  .book-now {
    font-size: 1rem;
    padding: 0.6em 1.2em;
  }

  .description {
    font-size: 1.2rem;
  }
}
</style>
