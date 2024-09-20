// src/store/modules/notification.js
import api from "../../services/api";

export default {
  namespaced: true,
  state: {
    notifications: [],
    followNotifications: [],
    loading: false,
    error: null,
  },
  mutations: {
    SET_NOTIFICATIONS(state, notifications) {
      state.notifications = notifications;
    },
    SET_FOLLOW_NOTIFICATIONS(state, notifications) {
      state.followNotifications = notifications.filter(n => 
        n.type === 'App\\Notifications\\NewFollowerNotification' ||
        n.type === 'App\\Notifications\\UnfollowNotification'
      );
    },
    SET_LOADING(state, loading) {
      state.loading = loading;
    },
    SET_ERROR(state, error) {
      state.error = error;
    },
    REMOVE_NOTIFICATION(state, id) {
      state.notifications = state.notifications.filter(n => n.id !== id);
      state.followNotifications = state.followNotifications.filter(n => n.id !== id);
    },
  },
  actions: {
    async fetchNotifications({ commit }) {
      commit("SET_LOADING", true);
      try {
        const response = await api.get("/notifications");
        console.log("Fetched notifications:", response.data);
        commit("SET_NOTIFICATIONS", response.data);
        commit("SET_FOLLOW_NOTIFICATIONS", response.data);
      } catch (error) {
        console.error("Error fetching notifications:", error);
        commit("SET_ERROR", error.message || "Failed to fetch notifications");
      } finally {
        commit("SET_LOADING", false);
      }
    },
    async removeNotification({ commit }, id) {
      try {
        await api.delete(`/notifications/${id}`);
        commit("REMOVE_NOTIFICATION", id);
      } catch (error) {
        console.error("Error removing notification:", error);
        throw error;
      }
    },
  },
  getters: {
    allNotifications: (state) => state.notifications,
    followNotifications: (state) => state.followNotifications,
    isLoading: (state) => state.loading,
    error: (state) => state.error,
  },
};