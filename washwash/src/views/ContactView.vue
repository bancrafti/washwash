<template>
  <NavigationBar />
  <div class="page-container">

    <div class="content">
      <h1>Contact Us</h1>
      <p>Have a question? Reach out and we'll get back to you!</p>

      <form @submit.prevent="submitForm">
        <div class="input-group">
          <label>Name</label>
          <input v-model="name" type="text" required />
        </div>

        <div class="input-group">
          <label>Email</label>
          <input v-model="email" type="email" required />
        </div>

        <div class="input-group">
          <label>Message</label>
          <textarea v-model="message" required></textarea>
        </div>

        <button type="submit" class="submit-btn">Send Message</button>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import NavigationBar from '@/components/NavigationBar.vue';

const name = ref('');
const email = ref('');
const message = ref('');

const submitForm = () => {
  alert('Thank you! Your message has been sent.');
  name.value = '';
  email.value = '';
  message.value = '';
};
</script>

<style scoped>
.page-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  background-color: #f4f4f4;
  min-height: 84vh;
  padding-top: 70px; /* Prevent navbar overlap */
}

.content {
  max-width: 500px;
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

p {
  font-size: 1.1rem;
  color: #333;
}

.input-group {
  text-align: left;
  margin-top: 15px;
}

label {
  font-size: 1rem;
  font-weight: bold;
  color: #555;
}

input, textarea {
  width: 100%;
  padding: 10px;
  margin-top: 5px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

textarea {
  height: 100px;
}

.submit-btn {
  margin-top: 20px;
  padding: 10px 20px;
  background: #0077b6;
  color: white;
  border: none;
  cursor: pointer;
  border-radius: 5px;
  transition: 0.3s;
}

.submit-btn:hover {
  background: #005f8b;
}
</style>
