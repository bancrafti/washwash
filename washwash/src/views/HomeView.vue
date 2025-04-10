<script setup lang="ts">
import NavigationBar from '@/components/NavigationBar.vue';
</script>

<template>
  <NavigationBar />

  <section id="headline">
    <img src="@/assets/images/wash.jpg" alt="A washing machine image">
    <div class="overlay">
      <h1>Laundry Made Simple</h1>
      <router-link to="/services">
        <button class="book-now">Book Now</button>
      </router-link>
    </div>
  </section>
</template>


<style scoped>
/* Headline Section */
#headline {
  margin-top: 1em;
  position: relative;
  width: 100%;
  height: 90vh;
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
  font-weight: bolder1;
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
