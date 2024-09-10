import { createApp } from "vue";
import { createRouter, createWebHistory } from "vue-router";
import App from "./App.vue";

import LandingPage from "./pages/LandingPage.vue";
import HomePage from "./pages/HomePage.vue";
import Login from "./pages/Login.vue";
import Register from "./pages/Register.vue";
import "./global.css";

const routes = [
  {
    path: "/",
    name: "Landing",
    component: LandingPage,
    meta: { title: "Welcome to MarocConnect", description: "Connect with friends and explore Morocco" }
  },
  {
    path: "/home",
    name: "Home",
    component: HomePage,
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
    meta: { title: "Login - MarocConnect", description: "Log in to your MarocConnect account" }
  },
  {
    path: '/register',
    name: 'Register',
    component: Register,
    meta: { title: "Register - MarocConnect", description: "Create a new MarocConnect account" }
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Simple auth check function
const isAuthenticated = () => {
  return localStorage.getItem('user') !== null;
};

router.beforeEach((to, from, next) => {
  const metaTitle = to?.meta?.title;
  const metaDesc = to?.meta?.description;

  window.document.title = metaTitle || "MarocConnect";
  if (metaDesc) {
    addMetaTag(metaDesc);
  }

  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!isAuthenticated()) {
      next('/login');
    } else {
      next();
    }
  } else {
    next();
  }
});

const addMetaTag = (value) => {
  let element = document.querySelector(`meta[name='description']`);
  if (element) {
    element.setAttribute("content", value);
  } else {
    element = document.createElement('meta');
    element.name = 'description';
    element.content = value;
    document.getElementsByTagName('head')[0].appendChild(element);
  }
};

createApp(App).use(router).mount("#app");

export default router;