<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import NewtonCradle from "@/components/NewtonCradle.vue";

// Form input refs
const email = ref('');
const password = ref('');

// Router to redirect after login
const router = useRouter();

// Ref for showing messages
const message = ref('');
const messageType = ref(''); // 'success' or 'error'

// Login handler function
const handleLogin = async () => {
  // Check for empty fields
  if (!email.value || !password.value) {
    displayMessage("Both fields are required!", "error");
    return;
  }

  try {
    console.log("Sending login request:", {
      email: email.value,
      password: password.value
    });

    const response = await axios.post('http://localhost/washwash/backend/auth/login.php', {
      email: email.value,
      password: password.value
    }, {
      headers: {
        'Content-Type': 'application/json'  // Ensures the request is sent as JSON
      }
    });

    console.log("Response from backend:", response.data);

    if (response.status == 200) {
      // If login is successful, show success message
      displayMessage("Login successful!", "success");
      setTimeout(() => router.push('/home'), 2000);  // Redirect after 2 seconds
    } else {
      // Show error message
      displayMessage("Login failed: " + response.data.message, "error");
    }
  } catch (error) {
    console.error("Login error:", error);
    displayMessage("Incorrect Credentials. Please try again.", "error");
  }
};

// Function to display messages
const displayMessage = (msg, type) => {
  message.value = msg;
  messageType.value = type;

  // Automatically hide the message after 3 seconds
  setTimeout(() => {
    message.value = '';
  }, 3000);
};
</script>

<template>
  <div class="login-container">

    <!-- Newton's Cradle at the Top -->
    <div class="cradle-wrapper">
      <NewtonCradle />
    </div>

    <!-- Login Form -->
    <div class="login-card">
      <h1 class="title">Welcome Back</h1>
      <p class="subtitle">Enter your credentials to sign in</p>

      <div class="form">
        <label for="email">Email</label>
        <input type="text" v-model="email" placeholder="Enter your email" />

        <label for="password">Password</label>
        <input type="password" v-model="password" placeholder="Enter your password" />

        <button type="button" class="login-btn" @click="handleLogin">Login</button>
      </div>

      <!-- Extra Links -->
      <div class="extra-links">
        <a href="#">Forgot Password?</a>
        <p>
          Don't have an account?
          <router-link to="/signup" active-class="active-link">Sign Up</router-link>
        </p>
      </div>
    </div>

    <!-- Custom Message Alert -->
    <div v-if="message" :class="['alert', messageType]">
      {{ message }}
    </div>

  </div>
</template>

<style scoped>
/* 🌟 Background Styling */
.login-container
{
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: flex-start;
  min-height: 100vh;
  background: linear-gradient(to bottom right, #dbeafe, #93c5fd);
  padding: 40px 20px;
}

/* 🔵 Newton's Cradle Animation - At the Top */
.cradle-wrapper
{
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  max-width: 400px;
  height: 120px;
  overflow: hidden;
  margin-top: 10px;
  margin-bottom: 8em;
  /* Increased spacing between cradle and login form */
}

/* ✨ Glassmorphism Login Card */
.login-card
{
  background: rgba(255, 255, 255, 0.3);
  backdrop-filter: blur(15px);
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  padding: 35px;
  width: 360px;
  text-align: center;
  border: 1px solid rgba(255, 255, 255, 0.4);
}

/* 📝 Text Styling */
.title
{
  font-size: 24px;
  font-weight: bold;
  color: #1e3a8a;
}

.subtitle
{
  font-size: 14px;
  color: #4b5563;
  margin-top: 5px;
}

/* 🔹 Form Styling */
.form
{
  margin-top: 20px;
  text-align: left;
}

.form label
{
  display: block;
  font-weight: bold;
  color: #1e3a8a;
  margin-bottom: 5px;
}

.form input
{
  width: 100%;
  padding: 10px;
  border: 1px solid #93c5fd;
  border-radius: 6px;
  outline: none;
  background: rgba(255, 255, 255, 0.4);
  transition: all 0.3s ease;
}

.form input:focus
{
  border-color: #2563eb;
  box-shadow: 0 0 8px rgba(37, 99, 235, 0.3);
}

/* 🔘 Login Button */
.login-btn
{
  width: 100%;
  padding: 12px 2px;
  background: #2563eb;
  color: white;
  font-weight: bold;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  margin-top: 15px;
  transition: all 0.3s ease-in-out;
}

.login-btn:hover
{
  background: #1e40af;
  transform: translateY(-2px);
}

/* 📎 Extra Links */
.extra-links
{
  margin-top: 15px;
}

.extra-links a
{
  color: #2563eb;
  font-size: 14px;
  text-decoration: none;
  font-weight: bold;
}

.extra-links a:hover
{
  text-decoration: underline;
}

.extra-links p
{
  margin-top: 5px;
  color: #374151;
  font-size: 14px;
}

/* Custom Alert Styles */
.alert
{
  position: fixed;
  top: 20px;
  left: 50%;
  transform: translateX(-50%);
  padding: 15px 30px;
  border-radius: 5px;
  color: white;
  font-weight: bold;
  z-index: 9999;
  transition: opacity 0.5s ease-in-out;
}

.success
{
  background-color: #4CAF50;
  /* Green */
}

.error
{
  background-color: #f44336;
  /* Red */
}
</style>
