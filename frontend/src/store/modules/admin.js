
import api from '@/services/api';

export default {
  namespaced: true,
  state: {
    users: [],
    posts: [],
    comments: [],
    dashboardData: {},
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
      state.dashboardData = data;
    },
  },
  actions: {
    async fetchUsers({ commit }) {
      try {
        const response = await api.get('/admin/users');
        commit('SET_USERS', response.data);
      } catch (error) {
        console.error('Error fetching users:', error);
      }
    },
    async fetchPosts({ commit }) {
      try {
        const response = await api.get('/admin/posts');
        commit('SET_POSTS', response.data);
      } catch (error) {
        console.error('Error fetching posts:', error);
      }
    },
    async fetchComments({ commit }) {
      try {
        const response = await api.get('/admin/comments');
        commit('SET_COMMENTS', response.data);
      } catch (error) {
        console.error('Error fetching comments:', error);
      }
    },
    async fetchDashboardData({ commit }) {
      try {
        const response = await api.get('/admin/dashboard-data');
        commit('SET_DASHBOARD_DATA', response.data);
      } catch (error) {
        console.error('Error fetching dashboard data:', error);
      }
    },
  },
  getters: {
    users: state => state.users,
    posts: state => state.posts,
    comments: state => state.comments,
    dashboardData: state => state.dashboardData,
  },
};