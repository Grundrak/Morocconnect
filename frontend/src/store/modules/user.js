

import api from '../../services/api'

export default {
  namespaced: true,
  state: {
    currentUser: null,
    suggestedUsers: [],
    profileUser: null,
    loading: false,
    error: null
  },
  mutations: {
    SET_CURRENT_USER(state, user) {
      state.currentUser = user
    },
    SET_SUGGESTED_USERS(state, users) {
      state.suggestedUsers = users
    },
    REMOVE_SUGGESTED_USER(state, userId) {
      state.suggestedUsers = state.suggestedUsers.filter(user => user.id !== userId)
    },
    SET_PROFILE_USER(state, user) {
      state.profileUser = user
    },
    SET_LOADING(state, isLoading) {
      state.loading = isLoading
    },
    SET_ERROR(state, error) {
      state.error = error
    }
  },
  actions: {
    async fetchCurrentUser({ commit }) {
      try {
        const response = await api.get('/user')
        commit('SET_CURRENT_USER', response.data)
        return response.data
      } catch (error) {
        console.error('Error fetching current user:', error)
        throw error
      }
    },
    async fetchSuggestedUsers({ commit }) {
      try {
        const response = await api.get('/users/suggested')
        commit('SET_SUGGESTED_USERS', response.data)
        return response.data
      } catch (error) {
        console.error('Error fetching suggested users:', error)
        throw error
      }
    },
    async followUser({ commit }, userId) {
      try {
        const response = await api.post(`/users/${userId}/follow`)
        commit('REMOVE_SUGGESTED_USER', userId)
        return response.data
      } catch (error) {
        console.error('Error following user:', error)
        if (error.response && error.response.status === 400) {
          // User is already following or trying to follow themselves
          commit('REMOVE_SUGGESTED_USER', userId)
        }
        throw error
      }
    },
    async updateProfile({ commit }, userData) {
      try {
        const response = await api.put('/users/profile', userData)
        commit('SET_CURRENT_USER', response.data)
        return response.data
      } catch (error) {
        console.error('Error updating profile:', error)
        throw error
      }
    },
    async uploadAvatar({ commit }, formData) {
      try {
        const response = await api.post('/users/avatar', formData)
        commit('SET_CURRENT_USER', response.data)
        return response.data
      } catch (error) {
        console.error('Error uploading avatar:', error)
        throw error
      }
    },
    async fetchUserProfile({ commit }, userId) {
      commit('SET_LOADING', true)
      commit('SET_ERROR', null)
      try {
        const response = await api.get(`/users/${userId}`)
        commit('SET_PROFILE_USER', response.data)
        return response.data
      } catch (error) {
        console.error('Error fetching user profile:', error)
        commit('SET_ERROR', error.message || 'Failed to fetch user profile')
        throw error
      } finally {
        commit('SET_LOADING', false)
      }
    }
  },
  getters: {
    currentUser: state => state.currentUser,
    suggestedUsers: state => state.suggestedUsers,
    profileUser: state => state.profileUser,
    isLoading: state => state.loading,
    error: state => state.error
  }
}