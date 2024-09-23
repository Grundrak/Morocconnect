import api from "../../services/api";

export default {
  namespaced: true,
  state: {
    user: null,
    token: localStorage.getItem("jwt_token") || null,
  },
  mutations: {
    SET_USER(state, user) {
      state.user = user;
      state.isAuthenticated = !!user;
    },
    SET_TOKEN(state, token) {
      if (token) {
        state.token = token;
        localStorage.setItem("jwt_token", token);
      } else {
        console.error('Attempted to set null token');
      }
    },
    LOGOUT(state) {
      state.user = null;
      state.token = null;
      localStorage.removeItem("jwt_token");
    },
  },
  actions: {
    async login({ commit }, credentials) {
      try {
        const response = await api.post("/login", credentials);
        const { user, token } = response.data;
        if (token) {
          commit("SET_USER", user);
          commit("SET_TOKEN", token);
          api.defaults.headers.common['Authorization'] = `Bearer ${token}`;
          return true;  
        } else {
          console.error('No token received from server');
          return false;
        }
      } catch (error) {
        console.error("Login error:", error);
        return false;
      }
    },
    async logout({ commit }) {
      try {
        await api.post("/logout");
      } catch (error) {
        console.error("Logout error:", error);
      } finally {
        commit("LOGOUT");
        delete api.defaults.headers.common['Authorization'];
        return true;
      }
    },
    async fetchUser({ commit, state }) {
      if (!state.token) return false;
      try {
        const response = await api.get("/user");
        commit("SET_USER", response.data);
        return true;
      } catch (error) {
        console.error("Fetch user error:", error.response ? error.response.data : error);
        return false;
      }
    },
    async register({ commit }, userData) {
      try {
        const response = await api.post("/register", userData);
        if (response.data.success) {
          const { user, token } = response.data;
          commit("SET_USER", user);
          commit("SET_TOKEN", token);
          api.defaults.headers.common['Authorization'] = `Bearer ${token}`;
          return { success: true };
        } else {
          return { 
            success: false, 
            errors: response.data.errors || { general: 'Registration failed' }
          };
        }
      } catch (error) {
        console.error("Registration error:", error.response?.data || error.message);
        return { 
          success: false, 
          errors: error.response?.data?.errors || { general: 'An error occurred during registration' }
        };
      }
    },
  },
  getters: {
    isAuthenticated: (state) => !!state.token,
    currentUser: (state) => state.user,
  },
};