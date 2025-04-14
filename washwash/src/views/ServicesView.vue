<template>
  <div class="services-page">
    <div class="services-page__sidebar">
      <div class="brand-container">
        <h1 class="brand-container__heading">Fresh & Clean</h1>
        <p class="brand-container__tagline">Professional Laundry Services</p>
      </div>

      <div class="services-list">
        <div
          v-for="(service, index) in services"
          :key="index"
          class="service-card"
          :class="{ 'service-card--active': selectedServiceIndex === index }"
          @click="selectService(index)"
          role="button"
          tabindex="0"
          @keydown.enter="selectService(index)"
          @keydown.space.prevent="selectService(index)"
        >
          <div class="service-card__icon">
            <span :class="service.icon" aria-hidden="true"></span>
          </div>
          <div class="service-card__info">
            <h2 class="service-card__title">{{ service.name }}</h2>
            <p class="service-card__brief">{{ service.brief }}</p>
          </div>
        </div>
      </div>

      <div class="contact-info">
        <p><span class="fas fa-phone contact-info__icon" aria-hidden="true"></span> 720 786 078</p>
        <p><span class="fas fa-clock contact-info__icon" aria-hidden="true"></span> Open 7AM - 9PM</p>
      </div>
    </div>

    <div class="services-page__details">
      <transition name="fade" mode="out-in">
        <div v-if="selectedServiceData" :key="selectedServiceIndex" class="details-content">
          <div class="details-header">
            <h1 class="details-header__heading">{{ selectedServiceData.name }}</h1>
            <div class="details-header__highlights">
              <span v-for="(highlight, i) in selectedServiceData.highlights" :key="i" class="highlight-badge">
                {{ highlight }}
              </span>
            </div>
          </div>

          <div class="details-image-container">
            <img :src="selectedServiceData.image" :alt="selectedServiceData.name" class="details-image-container__image" />
          </div>

          <div class="details-text">
            <p v-for="(paragraph, i) in selectedServiceData.description" :key="i" class="details-text__paragraph">
              {{ paragraph }}
            </p>

            <div class="service-features">
              <h3 class="service-features__title">What's included:</h3>
              <div class="service-features__grid">
                <div v-for="(feature, i) in selectedServiceData.features" :key="i" class="feature-item">
                  <div class="feature-item__icon">
                    <span class="fas fa-check" aria-hidden="true"></span>
                  </div>
                  <div class="feature-item__text">{{ feature }}</div>
                </div>
              </div>
            </div>

            <div class="cta-container">
               <router-link :to="{ path: '/garment', query: { service: selectedServiceIndex } }">
                <button class="cta-button cta-button--primary">
                    <span class="fas fa-calendar-check" aria-hidden="true"></span> Book Now
                </button>
              </router-link>
              </div>
          </div>
        </div>
         <div v-else>
            <p>Loading service details...</p>
            </div>
      </transition>
    </div>
  </div>
</template>

<script>
import { ref, watch, onMounted, computed } from 'vue'; // Using Composition API for reactivity
import { useRoute, useRouter } from 'vue-router'; // Using Vue Router hooks

// Import images (ensure paths are correct)
import washAndFold from '@/assets/images/wash-and-fold.webp';
import dryCleaning from '@/assets/images/dry-cleaning.jpg';
import ironingAndPressing from '@/assets/images/ironing-and-pressing.jpg';
import expressLaundry from '@/assets/images/express-laundry.jpg';

export default {
  name: 'ServicesView',
  setup() {
    const route = useRoute();
    const router = useRouter();

    // Service Data (can be moved to a separate file/store)
    const services = ref([
      {
        id: 'wash-fold', // Added ID for better identification
        name: "Wash & Fold",
        brief: "Perfect for everyday laundry needs",
        icon: "fas fa-tshirt", // Assuming FontAwesome
        image: washAndFold,
        highlights: ["Eco-friendly", "Sorted by color"],
        description: [
          "Our Wash & Fold service is perfect for everyday laundry needs. We carefully sort, wash, dry, and fold your items with attention to detail.",
          "This service is ideal for casual wear, bed linens, and towels. Your items will be returned fresh, clean, and neatly folded, ready to be put away."
        ],
        features: [
          "Sorting by color and fabric type",
          "Premium detergents & fabric softeners",
          "Precise temperature control",
          "Neatly folded and packaged",
          "24-hour turnaround available"
        ]
      },
      {
        id: 'dry-clean',
        name: "Dry Cleaning",
        brief: "Best for delicate fabrics and formal attire",
        icon: "fas fa-user-tie",
        image: dryCleaning,
        highlights: ["Stain removal", "Fabric protection"],
        description: [
          "Dry Cleaning is the best option for delicate fabrics and garments that require special care. Our professional process removes stains while protecting fabric integrity.",
          "This service is ideal for suits, dresses, formal attire, and garments with 'dry clean only' labels. We use environmentally friendly solvents for superior results."
        ],
        features: [
          "Pre-treatment of stains and spots",
          "Eco-friendly solvents",
          "Button and embellishment protection",
          "Careful inspection process",
          "Properly pressed and hung"
        ]
      },
      {
        id: 'ironing',
        name: "Ironing & Pressing",
        brief: "For perfectly smooth, wrinkle-free clothes",
        icon: "fas fa-steam", // Changed icon for better representation
        image: ironingAndPressing,
        highlights: ["Professional finish", "Attention to detail"],
        description: [
          "Our Ironing & Pressing service ensures your clothes are perfectly smooth and wrinkle-free. Each item receives careful attention from our experienced staff.",
          "Ideal for business wear, uniforms, dress shirts, and special occasion garments. We can also press previously laundered items."
        ],
        features: [
          "Steam pressing for optimal results",
          "Careful attention to collars and cuffs",
          "Precise temperature for each fabric type",
          "Professional folding or hanging",
          "Same-day service available"
        ]
      },
      {
        id: 'express',
        name: "Express Laundry",
        brief: "Same-day service for urgent needs",
        icon: "fas fa-bolt",
        image: expressLaundry,
        highlights: ["Same-day service", "Priority handling"],
        description: [
          "Need your laundry done fast? Our Express Laundry service is the solution with same-day turnaround for items dropped off before 10am.",
          "Perfect for busy individuals, professionals, and last-minute needs. All the quality of our standard service, just faster!"
        ],
        features: [
          "Same-day turnaround (before 10am)",
          "Priority handling",
          "Text notifications when ready",
          "Standard or premium detergents",
          "Rush service available for additional fee"
        ]
      }
    ]);

    // State
    const selectedServiceIndex = ref(0); // Default to the first service

    // Computed property to get the data of the currently selected service
    const selectedServiceData = computed(() => {
      return services.value[selectedServiceIndex.value] || null;
    });

    // Methods
    const selectService = (index) => {
      // Update the route query parameter, which will trigger the watcher
      router.push({ query: { service: index } });
    };

    const updateTitle = (index) => {
      const serviceName = services.value[index]?.name;
      document.title = serviceName ? `${serviceName} | WashWash Services` : 'WashWash Services';
    };

    const setServiceFromRoute = (queryParam) => {
        const index = parseInt(queryParam, 10);
        // Validate the index
        if (!isNaN(index) && index >= 0 && index < services.value.length) {
            if(selectedServiceIndex.value !== index) { // Avoid unnecessary updates
                selectedServiceIndex.value = index;
            }
        } else {
            // If invalid index in query, default to 0 and update URL (optional)
            selectedServiceIndex.value = 0;
            // Optionally, replace the route if the query was invalid/missing
            // router.replace({ query: { service: 0 } });
        }
        updateTitle(selectedServiceIndex.value); // Update title after setting index
    };

    // Watchers
    watch(() => route.query.service, (newServiceQuery) => {
        setServiceFromRoute(newServiceQuery);
    }, { immediate: true }); // immediate: true runs the watcher once on component mount

    // Lifecycle Hooks
    onMounted(() => {
      // Initial check is handled by the immediate watcher now
      // updateTitle(selectedServiceIndex.value); // Update title on mount
    });

    return {
      services,
      selectedServiceIndex,
      selectedServiceData,
      selectService,
    };
  },
};
</script>

<style scoped>
/* Using CSS Variables for better theming */
:root {
  --primary-color: palevioletred;
  --secondary-color: rgb(245, 230, 230); /* Lighter pink for backgrounds */
  --background-color: #fdfdfe; /* Off-white */
  --text-color: #333;
  --text-muted: #666;
  --white: #fff;
  --border-light: rgba(0, 0, 0, 0.08);
  --shadow-soft: 0 4px 12px rgba(0, 0, 0, 0.06);
  --shadow-medium: 0 6px 15px rgba(0, 0, 0, 0.08);
  --border-radius-medium: 12px;
  --border-radius-large: 16px;
}

.services-page {
  display: flex;
  min-height: 100vh; /* Use min-height instead of height for flexibility */
  background-color: var(--background-color);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  color: var(--text-color);
}

/* Sidebar Styles */
.services-page__sidebar {
  width: 320px; /* Fixed width for larger screens */
  flex-shrink: 0; /* Prevent sidebar from shrinking */
  background-color: var(--white);
  box-shadow: 2px 0 15px var(--border-light);
  padding: 2rem 1.5rem;
  display: flex;
  flex-direction: column;
  overflow-y: auto;
  height: 100vh; /* Full height sidebar */
  position: sticky; /* Stick sidebar on scroll */
  top: 0;
}

.brand-container {
  text-align: center;
  margin-bottom: 2.5rem;
  padding-bottom: 1.5rem;
  border-bottom: 1px solid var(--border-light);
}

.brand-container__heading {
  font-size: 2rem;
  color: var(--primary-color);
  margin-bottom: 0.5rem;
  font-weight: 700;
}

.brand-container__tagline {
  color: var(--text-muted);
  font-size: 1rem;
}

.services-list {
  flex-grow: 1; /* Allow list to take available space */
  display: flex;
  flex-direction: column;
  gap: 0.8rem; /* Slightly reduced gap */
}

.service-card {
  background-color: var(--white);
  border-radius: var(--border-radius-medium);
  padding: 1rem 1.25rem;
  cursor: pointer;
  /* Apply transition to properties that change on hover/active */
  transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease, border-left-color 0.3s ease;
  border-left: 5px solid transparent;
  display: flex;
  align-items: center;
  gap: 1rem;
}

.service-card:hover {
  background-color: var(--secondary-color);
  /* Subtle hover effect */
  transform: translateY(-3px);
  box-shadow: var(--shadow-soft);
}

.service-card--active {
  border-left-color: var(--primary-color);
  background-color: var(--secondary-color);
  box-shadow: var(--shadow-soft);
}

.service-card__icon {
  font-size: 1.6rem; /* Slightly larger icon */
  width: 3rem;
  height: 3rem;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--primary-color);
  background-color: rgba(219, 112, 147, 0.1);
  border-radius: 50%;
  flex-shrink: 0;
}

.service-card__info {
  flex: 1;
  overflow: hidden; /* Prevent text overflow issues */
}

.service-card__title {
  font-size: 1.15rem; /* Slightly smaller title */
  font-weight: 600;
  margin: 0 0 0.25rem 0;
  color: var(--text-color);
}

.service-card__brief {
  font-size: 0.85rem; /* Slightly smaller brief */
  color: var(--text-muted);
  margin: 0;
  white-space: nowrap; /* Prevent wrapping */
  overflow: hidden;
  text-overflow: ellipsis; /* Add ellipsis if too long */
}

.contact-info {
  margin-top: 2rem;
  padding-top: 1.5rem;
  border-top: 1px solid var(--border-light);
  font-size: 0.9rem;
  color: var(--text-muted);
}

.contact-info p {
  margin: 0.5rem 0;
  display: flex;
  align-items: center;
  gap: 0.75rem; /* Increased gap */
}

.contact-info__icon {
  color: var(--primary-color);
  width: 1.2em; /* Ensure icons align well */
  text-align: center;
}

/* Service Details Styles */
.services-page__details {
  flex-grow: 1; /* Allow details section to take remaining space */
  padding: 2rem 3rem; /* Adjusted padding */
  overflow-y: auto;
  background-color: var(--background-color);
  /* Optional: Add subtle shadow for depth */
  /* box-shadow: inset 5px 0 15px rgba(0, 0, 0, 0.03); */
}

.details-content {
    max-width: 900px; /* Limit content width for readability */
    margin: 0 auto; /* Center content */
}

.details-header {
  margin-bottom: 2.5rem;
  padding-bottom: 1.5rem;
  border-bottom: 1px solid var(--border-light);
}

.details-header__heading {
  font-size: 2.8rem; /* Larger heading */
  color: var(--primary-color);
  margin-bottom: 1rem;
  font-weight: 700;
}

.details-header__highlights {
  display: flex;
  gap: 0.75rem;
  flex-wrap: wrap;
}

.highlight-badge {
  background-color: rgba(219, 112, 147, 0.1);
  color: var(--primary-color);
  padding: 0.5rem 1.2rem; /* Adjusted padding */
  border-radius: 30px;
  font-size: 0.9rem; /* Slightly larger */
  font-weight: 500;
}

.details-image-container {
  width: 100%;
  max-height: 400px; /* Limit image height */
  overflow: hidden;
  border-radius: var(--border-radius-large);
  margin-bottom: 2.5rem;
  position: relative;
  box-shadow: var(--shadow-medium);
}

.details-image-container__image {
  width: 100%;
  height: 100%;
  object-fit: cover; /* Cover ensures the image fills the container */
  transition: transform 0.5s ease; /* Smoother zoom */
  display: block; /* Remove potential bottom space */
}

.details-image-container:hover .details-image-container__image {
  transform: scale(1.04); /* Slightly less zoom */
}

.details-text {
  color: var(--text-color);
  line-height: 1.7; /* Increased line height */
}

.details-text__paragraph {
  margin-bottom: 1.5rem;
  font-size: 1.05rem;
  color: var(--text-muted); /* Slightly muted paragraph text */
}
.details-text__paragraph:first-of-type {
    color: var(--text-color); /* Make first paragraph standard color */
    font-size: 1.1rem;
}


.service-features {
  background-color: var(--white);
  border-radius: var(--border-radius-medium);
  margin: 2.5rem 0;
  box-shadow: var(--shadow-soft);
  overflow: hidden;
  border: 1px solid rgba(219, 112, 147, 0.15);
}

.service-features__title {
  margin: 0;
  padding: 1.25rem 1.5rem; /* Adjusted padding */
  font-size: 1.2rem;
  color: var(--white);
  background-color: var(--primary-color);
  font-weight: 600;
}

.service-features__grid {
  padding: 1.5rem;
  display: grid;
  /* Responsive grid: 1 column on small, 2 on medium+ */
  grid-template-columns: 1fr;
  gap: 1rem; /* Reduced gap */
}

/* Adjust grid for larger screens inside a media query if needed */
@media (min-width: 600px) {
  .service-features__grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
  }
}


.feature-item {
  display: flex;
  align-items: flex-start;
  gap: 0.75rem;
  padding: 0.75rem;
  border-radius: 8px;
  background-color: transparent; /* Remove background, rely on parent card */
}

.feature-item__icon {
  background-color: var(--primary-color);
  color: white;
  width: 24px;
  height: 24px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.8rem; /* Smaller icon size */
  flex-shrink: 0;
  margin-top: 2px; /* Align icon slightly better with text */
}

.feature-item__text {
  font-size: 0.95rem;
  line-height: 1.5;
  color: var(--text-muted);
}

.cta-container {
  display: flex;
  gap: 1rem;
  margin-top: 3rem; /* Increased margin */
}

.cta-button {
  padding: 0.9rem 1.8rem; /* Slightly adjusted padding */
  border-radius: 8px;
  font-size: 1.05rem; /* Slightly smaller font */
  font-weight: 500; /* Medium weight */
  cursor: pointer;
  transition: all 0.3s ease;
  border: none;
  display: inline-flex; /* Use inline-flex for better alignment */
  align-items: center;
  justify-content: center;
  gap: 0.6rem; /* Gap between icon and text */
  text-decoration: none; /* Remove underline from router-link */
  flex-grow: 1; /* Make buttons fill container */
}

.cta-button--primary {
  background-color: var(--primary-color);
  color: white;
  box-shadow: 0 4px 10px rgba(219, 112, 147, 0.25);
}

.cta-button--primary:hover {
  background-color: #c9486d; /* Darker shade */
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(219, 112, 147, 0.35);
}

.cta-button--secondary {
  background-color: var(--white);
  color: var(--primary-color);
  border: 2px solid var(--primary-color);
  box-shadow: none;
}

.cta-button--secondary:hover {
  background-color: rgba(219, 112, 147, 0.05);
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
}


/* Transition effect for content swapping */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.fade-enter-from, .fade-leave-to {
  opacity: 0;
  transform: translateY(15px); /* Smoother vertical transition */
}


/* --- Mobile Responsive Design --- */
@media (max-width: 992px) { /* Adjust breakpoint if needed */
    .services-page__details {
        padding: 2rem; /* Reduce padding on medium screens */
    }

    .details-header__heading {
        font-size: 2.4rem;
    }
}


@media (max-width: 768px) {
  .services-page {
    flex-direction: column; /* Stack vertically */
    height: auto; /* Allow height to adjust to content */
  }

  .services-page__sidebar {
    width: 100%; /* Full width */
    height: auto; /* Adjust height */
    max-height: none; /* Remove max height limit */
    position: static; /* Remove sticky positioning */
    box-shadow: 0 2px 10px var(--border-light); /* Bottom shadow */
    border-bottom: 1px solid var(--border-light);
    overflow-y: visible; /* Allow content to scroll with page */
  }

  .services-list {
      /* Optional: Horizontal scroll for service cards on mobile */
      /* display: flex;
      flex-direction: row;
      overflow-x: auto;
      padding-bottom: 1rem;
      gap: 0.8rem; */
  }

  .service-card {
      /* Adjust card styles if using horizontal scroll */
      /* flex-shrink: 0; width: 200px; */
  }

  .services-page__details {
    padding: 1.5rem; /* Further reduce padding */
    border-radius: 0; /* Remove top radius */
    box-shadow: none; /* Remove side shadow */
  }

  .details-header__heading {
    font-size: 2rem; /* Smaller heading */
  }

  .details-image-container {
    max-height: 250px; /* Reduced image height */
    margin-bottom: 2rem;
  }

  .cta-container {
    flex-direction: column; /* Stack buttons */
  }

  .cta-button {
    width: 100%; /* Full width buttons */
  }
}

</style>
