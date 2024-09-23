
import api from '@/services/api';

export default {
  namespaced: true,
  state: {
    searchResults: {
      users: [],
      posts: [],
      comments: []
    },
    isSearching: false,
    error: null
  },
  mutations: {
    SET_SEARCH_RESULTS(state, results) {
      state.searchResults = results;
    },
    SET_IS_SEARCHING(state, isSearching) {
      state.isSearching = isSearching;
    },
    SET_ERROR(state, error) {
      state.error = error;
    }
  },
  actions: {
    async performSearch({ commit }, query) {
      commit('SET_IS_SEARCHING', true);
      commit('SET_ERROR', null);
      try {
        const response = await api.get('/search', { params: { query } });
        commit('SET_SEARCH_RESULTS', response.data);
      } catch (error) {
        console.error('Search error:', error);
        commit('SET_ERROR', 'An error occurred while searching');
      } finally {
        commit('SET_IS_SEARCHING', false);
      }
    }
  },
  getters: {
    searchResults: state => state.searchResults,
    isSearching: state => state.isSearching,
    error: state => state.error
  }
};