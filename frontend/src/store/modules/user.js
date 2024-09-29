import api from "../../services/api";

export default {
  namespaced: true,
  state: {
    currentUser: null,
    suggestedUsers: [],
    profileUser: null,
    loading: false,
    error: null,
  },
  mutations: {
    SET_CURRENT_USER(state, user) {
      state.currentUser = user;
    },
    SET_SUGGESTED_USERS(state, users) {
      state.suggestedUsers = users;
    },
    REMOVE_SUGGESTED_USER(state, userId) {
      state.suggestedUsers = state.suggestedUsers.filter(
        (user) => user.id !== userId
      );
    },
    SET_PROFILE_USER(state, user) {
      state.profileUser = user;
    },
    SET_LOADING(state, isLoading) {
      state.loading = isLoading;
    },
    SET_ERROR(state, error) {
      state.error = error;
    },
    SET_PROFILE_USER(state, user) {
      state.profileUser = {
        ...user,
        followers_count: user.followers_count || 0,
        following_count: user.following_count || 0,
      };
    },
  },
  actions: {
    async fetchCurrentUser({ commit }) {
      try {
        const response = await api.get("/user");
        commit("SET_CURRENT_USER", response.data);
        return response.data;
      } catch (error) {
        console.error("Error fetching current user:", error);
        throw error;
      }
    },
    async fetchSuggestedUsers({ commit }) {
      try {
        const response = await api.get("/users/suggested");
        commit("SET_SUGGESTED_USERS", response.data);
        return response.data;
      } catch (error) {
        console.error("Error fetching suggested users:", error);
        throw error;
      }
    },
    async fetchUserProfile({ commit, rootState }, userId) {
      commit("SET_LOADING", true);
      commit("SET_ERROR", null);
      try {
        const [userResponse, followersResponse, followingResponse] =
          await Promise.all([
            api.get(`/users/${userId}`),
            api.get(`/users/${userId}/followers`),
            api.get(`/users/${userId}/following`),
          ]);

        const currentUserId = rootState.auth.user?.id;

        const userData = {
          ...userResponse.data,
          followers_count: followersResponse.data.length,
          following_count: followingResponse.data.length,
          is_followed_by_current_user: followersResponse.data.some(
            (follower) => follower.id === currentUserId
          ),
        };

        commit("SET_PROFILE_USER", userData);
        return userData;
      } catch (error) {
        console.error("Error fetching user profile:", error);
        commit("SET_ERROR", error.message || "Failed to fetch user profile");
        throw error;
      } finally {
        commit("SET_LOADING", false);
      }
    },
    async followUser({ commit, dispatch }, userId) {
      try {
        await api.post(`/users/${userId}/follow`);
        await dispatch("fetchUserProfile", userId);
      } catch (error) {
        console.error("Error following user:", error);
        throw error;
      }
    },

    async unfollowUser({ commit, dispatch }, userId) {
      try {
        await api.post(`/users/${userId}/unfollow`);
        await dispatch("fetchUserProfile", userId);
      } catch (error) {
        console.error("Error unfollowing user:", error);
        throw error;
      }
    },
    async updateProfile({ commit }, userData) {
      try {
        const response = await api.put("/users/profile", userData);
        commit("SET_CURRENT_USER", response.data);
        return { success: true, data: response.data };
      } catch (error) {
        console.error("Error updating profile:", error.response?.data || error);
        return {
          success: false,
          message: error.response?.data?.message || "Profile update failed",
        };
      }
    },
    async uploadAvatar({ commit }, { avatar }) {
      try {
        const response = await api.post("/users/avatar", { avatar });
        commit("SET_CURRENT_USER", response.data);
        return { success: true, data: response.data };
      } catch (error) {
        console.error("Error uploading avatar:", error.response?.data || error);
        return {
          success: false,
          message:
            error.response?.data?.message ||
            error.response?.data?.error ||
            "Avatar upload failed",
        };
      }
    },
  },
  getters: {
    currentUser: (state) => state.currentUser,
    suggestedUsers: (state) => state.suggestedUsers,
    profileUser: (state) => state.profileUser,
    isLoading: (state) => state.loading,
    error: (state) => state.error,
  },
};
