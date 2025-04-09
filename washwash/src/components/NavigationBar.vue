<script setup lang="ts">
import { ref } from 'vue';

const isMenuOpen = ref(false);

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value;
};
</script>

<template>
  <nav id="navigation-bar">
    <router-link to="/" id="logo">W.</router-link>

    <!-- Desktop Navigation -->
    <div id="links" :class="{ 'open': isMenuOpen }">
      <router-link to="/" active-class="active" @click="isMenuOpen = false">Home</router-link>
      <router-link to="/about" active-class="active" @click="isMenuOpen = false">About</router-link>
      <router-link to="/contact" active-class="active" @click="isMenuOpen = false">Contact</router-link>
    </div>

    <!-- Hamburger Menu -->
    <div id="menu-icon" @click="toggleMenu">
      <span :class="{ 'open': isMenuOpen }"></span>
      <span :class="{ 'open': isMenuOpen }"></span>
      <span :class="{ 'open': isMenuOpen }"></span>
    </div>
  </nav>
</template>

<style scoped>
/* Navigation Bar */
#navigation-bar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 20px;
  background-color: #f8f9fa;
  position: relative;
  z-index: 1000;
  transition: all 0.3s ease-in-out;
}

/* Logo */
#logo {
  font-size: 2em;
  font-weight: bold;
  text-decoration: none;
  color: #333;
  transition: transform 0.3s ease;
}

#logo:hover {
  transform: scale(1.1);
}

/* Navigation Links */
#links {
  display: flex;
  gap: 20px;
}

#links a {
  text-decoration: none;
  color: #000;
  font-size: 18px;
  font-weight: 500;
  position: relative;
  transition: color 0.3s ease-in-out;
}

/* Underline Animation */
#links a::after {
  content: "";
  width: 0%;
  height: 2px;
  background-color: palevioletred;
  position: absolute;
  bottom: -3px;
  left: 50%;
  transform: translateX(-50%);
  transition: width 0.3s ease-in-out;
}

#links a:hover::after {
  width: 100%;
}

/* Active Page Styling */
#links .active {
  color: palevioletred;
  font-weight: bold;
}

#links .active::after {
  width: 100%;
}

/* Menu Icon (Hamburger) */
#menu-icon {
  display: none;
  flex-direction: column;
  cursor: pointer;
  gap: 5px;
}

#menu-icon span {
  width: 30px;
  height: 4px;
  background-color: #333;
  transition: all 0.3s ease-in-out;
}

#menu-icon span.open:nth-child(1) {
  transform: rotate(45deg) translate(5px, 5px);
}

#menu-icon span.open:nth-child(2) {
  opacity: 0;
}

#menu-icon span.open:nth-child(3) {
  transform: rotate(-45deg) translate(6px, -6px);
}

/* Responsive Design */
@media (max-width: 768px) {
  #menu-icon {
    display: flex;
  }

  #links {
    position: absolute;
    top: 60px;
    right: 0;
    background-color: #f8f9fa;
    flex-direction: column;
    width: 100%;
    text-align: center;
    gap: 10px;
    padding: 10px 0;
    transform: translateY(-200%);
    transition: transform 0.3s ease-in-out;
  }

  #links.open {
    transform: translateY(0);
  }

  #links a {
    font-size: 20px;
    padding: 10px 0;
  }
}
</style>
