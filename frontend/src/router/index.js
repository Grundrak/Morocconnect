// src/router.js

import { createRouter, createWebHistory } from "vue-router";
import LandingPage from "../pages/LandingPage.vue";
import HomePage from "../pages/HomePage.vue";
import Login from "../pages/Login.vue";
import Register from "../pages/Register.vue";
import store from "../store/index";
import UserProfil from "../pages/UserProfil.vue";

const routes = [
  {
    path: "/",
    name: "Landing",
    component: LandingPage,
    meta: {
      title: "Welcome to MarocConnect",
      description: "Connect with friends and explore Morocco",
    },
  },
  {
    path: "/home",
    name: "Home",
    component: HomePage,
    meta: {
      requiresAuth: true,
    },
    
  },
  {
    path: '/profile',
    name: 'UserProfile',
    component: UserProfil
  },
  {
    path: "/login",
    name: "Login",
    component: Login,
    meta: {
      title: "Login - MarocConnect",
      description: "Log in to your MarocConnect account",
    },
  },
  {
    path: "/register",
    name: "Register",
    component: Register,
    meta: {
      title: "Register - MarocConnect",
      description: "Create a new MarocConnect account",
    },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

const isAuthenticated = () => {
  return localStorage.getItem("jwt_token") !== null;
};

router.beforeEach((to, from, next) => {
  const metaTitle = to?.meta?.title;
  const metaDesc = to?.meta?.description;

  window.document.title = metaTitle || "MarocConnect";
  if (metaDesc) {
    addMetaTag(metaDesc);
  }

  if (to.matched.some((record) => record.meta.requiresAuth)) {
    if (!store.getters['auth/isAuthenticated']) {
      next("/login");
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
    element = document.createElement("meta");
    element.name = "description";
    element.content = value;
    document.getElementsByTagName("head")[0].appendChild(element);
  }
};

export default router;