<template>
  <div class="garment-selector">
    <h2 class="section-title">🧺 Select Your Laundry Services</h2>

    <div class="services-grid">
      <div v-for="(service, index) in services" :key="index" class="service-card">
        <h3 class="service-title">{{ service.name }}</h3>
        <div v-for="(garment, garmentIndex) in garmentTypes" :key="garmentIndex" class="garment-row">
          <label class="garment-label">{{ garment.name }}:</label>

          <div class="input-group">
            <input
              type="number"
              v-model.number="garmentCounts[service.name][garment.name].washAndFold"
              placeholder="Wash & Fold"
              min="0"
              class="quantity-input"
            />
          </div>

          <div v-if="garment.canBeIroned" class="input-group">
            <input
              type="number"
              v-model.number="garmentCounts[service.name][garment.name].ironing"
              placeholder="Ironing"
              min="0"
              class="quantity-input"
            />
          </div>
          <span v-else class="disabled-option">-</span>

          <label class="express-checkbox">
            <input
              type="checkbox"
              v-model="garmentCounts[service.name][garment.name].expressLaundry"
              class="checkbox-input"
            />
            Express <span class="express-fee">(+KSh {{ expressLaundryFee }})</span>
          </label>
        </div>
      </div>
    </div>

    <div class="total-section">
      <h3 class="total-label">Total Amount: <span class="total-price">KSh {{ totalAmount }}</span></h3>
      <div class="button-group">
        <button @click="addAnotherService" class="button secondary">➕ Add Service</button>
        <button @click="proceedToCheckout" :disabled="!isValidForm" class="button primary">🛒 Proceed to Checkout</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      prices: {
        'Wash & Fold': {
          Shirt: 50,
          Pants: 80,
          Suit: 150,
          Dress: 120,
        },
        Ironing: {
          Shirt: 40,
          Pants: 60,
          Suit: 100,
          Dress: 80,
        },
        expressLaundryFee: 100,
      },

      garmentTypes: [
        { name: 'Shirt', canBeIroned: true },
        { name: 'Pants', canBeIroned: false },
        { name: 'Suit', canBeIroned: true },
        { name: 'Dress', canBeIroned: true },
      ],

      services: [
        { name: 'Wash & Fold' },
        { name: 'Ironing' },
        { name: 'Express Laundry' },
      ],

      garmentCounts: {
        'Wash & Fold': {
          Shirt: { washAndFold: 0, ironing: 0, expressLaundry: false },
          Pants: { washAndFold: 0, ironing: 0, expressLaundry: false },
          Suit: { washAndFold: 0, ironing: 0, expressLaundry: false },
          Dress: { washAndFold: 0, ironing: 0, expressLaundry: false },
        },
        'Ironing': {
          Shirt: { washAndFold: 0, ironing: 0, expressLaundry: false },
          Pants: { washAndFold: 0, ironing: 0, expressLaundry: false },
          Suit: { washAndFold: 0, ironing: 0, expressLaundry: false },
          Dress: { washAndFold: 0, ironing: 0, expressLaundry: false },
        },
        'Express Laundry': {
          Shirt: { washAndFold: 0, ironing: 0, expressLaundry: false },
          Pants: { washAndFold: 0, ironing: 0, expressLaundry: false },
          Suit: { washAndFold: 0, ironing: 0, expressLaundry: false },
          Dress: { washAndFold: 0, ironing: 0, expressLaundry: false },
        },
      },

      expressLaundryFee: 100,
    };
  },
  computed: {
    totalAmount() {
      let total = 0;
      Object.keys(this.garmentCounts).forEach((service) => {
        Object.keys(this.garmentCounts[service]).forEach((garment) => {
          const counts = this.garmentCounts[service][garment];
          if (counts.washAndFold > 0) {
            total += counts.washAndFold * this.prices['Wash & Fold'][garment];
          }
          if (counts.ironing > 0) {
            total += counts.ironing * this.prices['Ironing'][garment];
          }
          if (counts.expressLaundry) {
            total += this.expressLaundryFee;
          }
        });
      });
      return total;
    },
    isValidForm() {
      return Object.keys(this.garmentCounts).some((service) =>
        Object.keys(this.garmentCounts[service]).some((garment) =>
          this.garmentCounts[service][garment].washAndFold > 0 ||
          this.garmentCounts[service][garment].ironing > 0
        )
      );
    },
  },
  methods: {
    addAnotherService() {
      alert('Add another service feature coming soon!');
    },
    proceedToCheckout() {
      if (this.isValidForm) {
        alert('Proceeding to checkout!');
      } else {
        alert('Please add items to your cart.');
      }
    },
  },
};
</script>

<style scoped>
.garment-selector {
  padding: 30px;
  max-width: 900px;
  margin: 30px auto; /* Added top/bottom margin for better spacing */
  background-color: #fff; /* Changed background to white for cleaner look */
  border-radius: 15px; /* Slightly less rounded corners */
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08); /* Softer, more modern shadow */
}

.section-title {
  text-align: center;
  color: #333; /* Darker, more readable text */
  margin-bottom: 30px;
  font-size: 2rem; /* Slightly larger title */
  font-weight: 500; /* Semi-bold font weight */
}

.services-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); /* Responsive grid layout */
  gap: 20px;
  margin-bottom: 25px;
}

.service-card {
  background-color: #f9f9f9; /* Light gray background for cards */
  padding: 20px;
  border: 1px solid #eee; /* Lighter border */
  border-radius: 10px; /* Slightly less rounded corners for cards */
}

.service-title {
  color: #555; /* Slightly lighter heading */
  margin-top: 0;
  margin-bottom: 15px;
  font-size: 1.5rem;
  font-weight: 400;
}

.garment-row {
  display: flex;
  align-items: center;
  gap: 15px;
  margin-bottom: 12px; /* Slightly reduced bottom margin */
}

.garment-label {
  flex: 1;
  font-weight: 500; /* Semi-bold label */
  color: #444;
}

.input-group {
  display: flex;
}

.quantity-input {
  width: 70px; /* Slightly smaller input width */
  padding: 8px; /* Slightly more padding */
  border-radius: 6px; /* More rounded input corners */
  border: 1px solid #ddd;
  font-size: 0.9rem;
}

.checkbox-input {
  margin-right: 8px;
}

.disabled-option {
  color: #bbb; /* Lighter disabled text */
  width: 70px;
  text-align: center;
  font-size: 0.9rem;
}

.express-checkbox {
  font-size: 0.9rem;
  color: #666;
  display: flex;
  align-items: center;
}

.express-fee {
  color: #008000; /* Green color for the fee */
  font-weight: 500;
  margin-left: 5px;
}

.total-section {
  text-align: center;
  margin-top: 30px;
}

.total-label {
  color: #333;
  font-size: 1.2rem;
  margin-bottom: 10px;
}

.total-price {
  color: #28a745; /* Bootstrap success color */
  font-weight: bold;
  font-size: 1.5rem;
}

.button-group {
  display: flex;
  justify-content: center;
  gap: 15px;
  margin-top: 20px;
}

.button {
  padding: 12px 24px; /* Increased padding */
  font-size: 1rem;
  border-radius: 8px; /* More rounded button corners */
  border: none;
  cursor: pointer;
  transition: background-color 0.3s ease; /* Smooth hover transition */
}

.primary {
  background-color: #007bff; /* Bootstrap primary color */
  color: white;
  box-shadow: 0 2px 5px rgba(0, 123, 255, 0.3); /* Subtle shadow */
}

.primary:hover {
  background-color: #0056b3;
}

.primary:disabled {
  background-color: #ccc;
  cursor: not-allowed;
  box-shadow: none;
}

.secondary {
  background-color: #e0e0e0; /* Light gray */
  color: #333;
  border: 1px solid #ccc;
}

.secondary:hover {
  background-color: #d0d0d0;
}
</style>
