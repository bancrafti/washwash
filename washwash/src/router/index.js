import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import AboutView from '../views/AboutView.vue'
import ContactView from '@/views/ContactView.vue'
import LoginView from '@/views/LoginView.vue'
import SignUpView from '@/views/SignUpView.vue'
import ServicesView from '@/views/ServicesView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/home',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/about',
      name: 'about',
      component: AboutView,
    },
    {
      path:'/contact',
      name:'contact',
      component:ContactView,
    },
    {
      path:'/',
      name:'login',
      component:LoginView,
    },
    {
      path:'/signup',
      name:'signup',
      component:SignUpView,
    },
    {
      path:'/services',
      name:'services',
      component:ServicesView,
    }
  ],
})

export default router
