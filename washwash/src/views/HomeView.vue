<script setup lang="ts">
import NavigationBar from '@/components/NavigationBar.vue';
</script>

<template>
  <NavigationBar/>
  <div id="headline" class="container">
    <img src="C:\Users\waribu\Desktop\war\washwash\washwash\src\assets\images\wash.jpg" alt="A washing machine image" style="width: 100%;">
    <div id="headline-text" class="text">Laundry Made Simple</div>
    <button id="book-now">Book Now</button>
  </div>
  <h1>
    Services
  </h1>
  <div id="services" class="services-container">
    <div id="wash-and-fold" class="service">
      <img src="C:\Users\waribu\Desktop\war\washwash\washwash\src\assets\images\wash&fold.webp" alt="">
      <div class="description">Wash & Fold</div>
    </div>
    <div id="dry-cleaning" class="service">
      <img src="C:\Users\waribu\Desktop\war\washwash\washwash\src\assets\images\dry-cleaning.jpg" alt="">
      <div class="description">Dry Cleaning</div>
    </div>
    <div id="ironing-and-pressing" class="service">
      <img src="C:\Users\waribu\Desktop\war\washwash\washwash\src\assets\images\ironing&pressing.jpg" alt="">
      <div class="description">Ironing & Pressing</div>
    </div>
    <div id="express-laundry" class="service">
      <img src="C:\Users\waribu\Desktop\war\washwash\washwash\src\assets\images\express-laundry.jpg" alt="">
      <div class="description">Express Laundry</div>
    </div>
  </div>
</template>
