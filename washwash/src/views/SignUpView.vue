<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import NewtonCradle from "@/components/NewtonCradle.vue"

// Form input refs
const username = ref('')
const email = ref('')
const password = ref('')
const confirmPassword = ref('')

// Function to validate email format
const isValidEmail = (email) => {
  const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
  return emailRegex.test(email);
}

// Signup handler function
const router = useRouter()

const handleSignup = async () => {
  // Check for empty fields
  if (!username.value || !email.value || !password.value || !confirmPassword.value) {
    alert("All fields are required!");
    return;
  }

  // Check if email is valid
  if (!isValidEmail(email.value)) {
    alert("Please enter a valid email address!");
    return;
  }

  // Check if passwords match
  if (password.value !== confirmPassword.value) {
    alert("Passwords do not match.");
    return;
  }

  try {
    console.log("Sending request:", {
      username: username.value,
      email: email.value,
      password: password.value
    });

    const response = await axios.post('http://localhost/washwash/backend/auth/register.php', {
      username: username.value,
      email: email.value,
      password: password.value
    }, {
      headers: {
        'Content-Type': 'application/json'  // Ensures the request is sent as JSON
      }
    });

    console.log("Response from backend:", response.data);

    // Check if the status code is 201 (Created) for successful signup
    if (response.status === 201) {
      alert("Signup successful! You can now log in.");

      // Redirect to login page
      router.push('/login');
    }
  } catch (error) {
    console.error("Signup error:", error);

    // Handle specific error responses
    if (error.response) {
      const statusCode = error.response.status;

      // Map the status code to a custom error message
      switch (statusCode) {
        case 409:
          alert("Signup failed: Email already registered.");
          break;
        case 400:
          alert("Signup failed: Invalid input. Please check your details.");
          break;
        case 500:
          alert("Signup failed: Server error. Please try again later.");
          break;
        default:
          alert(`Signup failed with status code: ${statusCode}`);
      }
    } else {
      alert("An error occurred during signup. Please try again.");
    }
  }
};

</script>

<template>
  <div class="signup-container">
    <!-- Newton's Cradle at the Top -->
    <div class="cradle-wrapper">
      <NewtonCradle />
    </div>

    <!-- Signup Form -->
    <div class="signup-card">
      <h1 class="title">Create Account</h1>
      <p class="subtitle">Join us today by filling in your details</p>

      <div class="form">
        <label for="name">Full Name</label>
        <input type="text" v-model="username" placeholder="Enter your full name" />

        <label for="email">Email</label>
        <input type="email" v-model="email" placeholder="Enter your email" />

        <label for="password">Password</label>
        <input type="password" v-model="password" placeholder="Create a password" />

        <label for="confirm-password">Confirm Password</label>
        <input type="password" v-model="confirmPassword" placeholder="Confirm your password" />

        <button type="submit" class="signup-btn" @click="handleSignup">Sign Up</button>
      </div>

      <!-- Extra Links -->
      <div class="extra-links">
        <p>
          Already have an account?
          <router-link to="/login" active-class="active-link">Login</router-link>
        </p>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* 🌟 Background Styling */
.signup-container
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
  margin-bottom: 6em;
}

/* ✨ Glassmorphism Signup Card */
.signup-card
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

/* 🔘 Signup Button */
.signup-btn
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

.signup-btn:hover
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
</style>
