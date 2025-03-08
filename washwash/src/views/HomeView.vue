<script setup lang="ts">
import NavigationBar from '@/components/NavigationBar.vue';
</script>

<template>
  <NavigationBar/>
  <div id="headline" class="container">
    <img src="@/assets/images/wash.jpg" alt="A washing machine image" style="width: 100%;">
    <div id="headline-text" class="text">Laundry Made Simple</div>
    <button id="book-now">Book Now</button>
  </div>

</template>
<style scoped>
  /* Headline */
#headline{
  margin-top: 1em;
  border-radius: 0.3em;
  overflow: hidden;
  display: flex;
  flex-direction: row;
  align-content: flex-start;
  position: relative;
  text-align: center;
  width: 100%;
  height: 85vh;
  object-fit: contain;
}
#headline-text{
  position: absolute;
  width: 1em;
  top: 0.2em;
  right: 2.99em;
  text-align: end;
  color: rgb(255, 255, 255);
  font-weight: bold;
  font-size: clamp(3.8rem, 12vw, 25em);
  font-family:Arial, Helvetica, sans-serif;
}
/* Button */
#book-now{
  position: absolute;
  bottom: 0.2em;
  left: 50%;
  width: 7em;
  height:2.2em;
  font-size: 2em;
  font-weight: bold;
  transform: translate(-50%,-50%);
  border-radius: 3em;
  border: solid white;
  color: white;
  background-color: transparent;
}
.services-container{
  display: grid;
  margin-top: 2em;
  height: 50em;
  grid-template-rows: 1fr 1fr;
  grid-template-columns: 1fr 1fr;
  gap: 1em;
}
.service{
  position: relative;
  background-color: rgb(0, 0, 0);
  border-radius: 0.5em;
  overflow: hidden;
}
img{
  object-fit: cover;
  width: 100%;
  height: 100%;
}
.description{
  position:absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  font-size: 1em;
  color: white;
  font-weight: bolder;
}
</style>
