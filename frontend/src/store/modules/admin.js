// store/modules/admin.js
import api from "@/services/api";

export default {
  namespaced: true,
  state: {
    users: [],
    posts: [],
    comments: [],
    dashboardData: {
      totalUsers: 0,
      totalPosts: 0,
      totalComments: 0,
      activeUsers: 0,
      userGrowthData: [],
      postActivityData: []
    },
  },
  mutations: {
    SET_USERS(state, users) {
      state.users = users;
    },
    SET_POSTS(state, posts) {
      state.posts = posts;
    },
    SET_COMMENTS(state, comments) {
      state.comments = comments;
    },
    SET_DASHBOARD_DATA(state, data) {
      state.dashboardData = {
        ...state.dashboardData,
        ...data,
      };
    },
    UPDATE_USER(state, updatedUser) {
      const index = state.users.findIndex((user) => user.id === updatedUser.id);
      if (index !== -1) {
        state.users.splice(index, 1, updatedUser);
      }
    },
    DELETE_USER(state, userId) {
      state.users = state.users.filter((user) => user.id !== userId);
    },
  },
  actions: {
    async fetchUsers({ commit }) {
      try {
        const response = await api.get("/admin/users");
        commit("SET_USERS", response.data);
      } catch (error) {
        console.error("Error fetching users:", error);
      }
    },
    async fetchPosts({ commit }) {
      try {
        const response = await api.get("/admin/posts");
        commit("SET_POSTS", response.data);
      } catch (error) {
        console.error("Error fetching posts:", error);
      }
    },
    async fetchComments({ commit }) {
      try {
        const response = await api.get("/admin/comments");
        commit("SET_COMMENTS", response.data);
      } catch (error) {
        console.error("Error fetching comments:", error);
      }
    },
    async fetchDashboardData({ commit }) {
      try {
        const response = await api.get("/admin/dashboard-data");
        commit("SET_DASHBOARD_DATA", response.data);
      } catch (error) {
        console.error("Error fetching dashboard data:", error);
      }
    },
    async updateUser({ commit }, updatedUser) {
      try {
        const response = await api.put(
          `/admin/users/${updatedUser.id}`,
          updatedUser
        );
        commit("UPDATE_USER", response.data);
      } catch (error) {
        console.error("Failed to update user:", error);
      }
    },
    async deleteUser({ commit }, userId) {
      try {
        await api.delete(`/admin/users/${userId}`);
        commit("DELETE_USER", userId);
      } catch (error) {
        console.error("Failed to delete user:", error);
      }
    },
  },
  getters: {
    users: (state) => state.users,
    posts: (state) => state.posts,
    comments: (state) => state.comments,
    dashboardData: (state) => state.dashboardData,
  },
};
