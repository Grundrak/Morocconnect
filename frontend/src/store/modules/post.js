import api from "../../services/api";
import { loadState, saveState } from '@/utils/localStorage';
const BOOKMARKS_STORAGE_KEY = 'bookmarkedPosts';

export default {
  namespaced: true,
  state: {
    posts: [],
    loading: false,
    error: null,
    pagination: null,
    bookmarkedPosts: loadState(BOOKMARKS_STORAGE_KEY) || [],
  },
  mutations: {
    SET_POSTS(state, posts) {
      state.posts = posts;
    },
    ADD_POST(state, post) {
      state.posts.unshift(post);
    },
    UPDATE_POST(state, updatedPost) {
      const index = state.posts.findIndex((post) => post.id === updatedPost.id);
      if (index !== -1) {
        state.posts.splice(index, 1, { ...state.posts[index], ...updatedPost });
      }
    },
    REMOVE_POST(state, postId) {
      state.posts = state.posts.filter((post) => post.id !== postId);
    },
    UPDATE_POST_LIKES(state, { postId, likesCount, isLiked }) {
      const post = state.posts.find((p) => p.id === postId);
      if (post) {
        post.likes_count = likesCount;
        post.likes = likesCount;
        post.is_liked = isLiked;
      }
    },
    INCREMENT_SHARES(state, postId) {
      const post = state.posts.find((p) => p.id === postId);
      if (post) {
        post.shares_count++;
      }
    },
    SET_PAGINATION(state, pagination) {
      state.pagination = pagination;
    },
    SET_LOADING(state, loading) {
      state.loading = loading;
    },
    SET_ERROR(state, error) {
      state.error = error;
    },
    ADD_BOOKMARK(state, post) {
      if (!state.bookmarkedPosts.some((p) => p.id === post.id)) {
        state.bookmarkedPosts.push(post);
        saveState(BOOKMARKS_STORAGE_KEY, state.bookmarkedPosts);
      }
    },
    REMOVE_BOOKMARK(state, postId) {
      state.bookmarkedPosts = state.bookmarkedPosts.filter(
        (p) => p.id !== postId
      );
      saveState(BOOKMARKS_STORAGE_KEY, state.bookmarkedPosts);
    },
  },
  actions: {
    async fetchPosts({ commit, rootState }) {
      commit("SET_LOADING", true);
      try {
        const response = await api.get("/post");
        const posts = response.data.data.map((post) => ({
          ...post,
          is_liked: Array.isArray(post.likes)
            ? post.likes.some((like) => like.user_id === rootState.auth.user.id)
            : false,
        }));
        commit("SET_POSTS", posts);
        commit("SET_PAGINATION", {
          currentPage: response.data.current_page,
          lastPage: response.data.last_page,
          total: response.data.total,
          perPage: response.data.per_page,
        });
      } catch (error) {
        console.error("Error fetching posts:", error);
        commit("SET_ERROR", error.message || "Failed to fetch posts");
      } finally {
        commit("SET_LOADING", false);
      }
    },
    async createPost({ commit }, formData) {
      try {
        const response = await api.post("/post", formData);
        commit("ADD_POST", response.data);
        return { success: true, post: response.data };
      } catch (error) {
        console.error("Error creating post:", error);
        return {
          success: false,
          error: error.message || "Failed to create post",
        };
      }
    },
    async updatePost({ commit }, { id, formData }) {
      try {
        const response = await api.post(`/post/${id}`, formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        });
        commit("UPDATE_POST", response.data);
        return { success: true, post: response.data };
      } catch (error) {
        console.error("Error updating post:", error);
        return {
          success: false,
          error: error.message || "Failed to update post",
        };
      }
    },
    async deletePost({ commit }, postId) {
      try {
        await api.delete(`/post/${postId}`);
        commit("REMOVE_POST", postId);
        return { success: true };
      } catch (error) {
        console.error("Error deleting post:", error);
        return {
          success: false,
          error: error.message || "Failed to delete post",
        };
      }
    },
    async likePost({ commit }, postId) {
      try {
        const response = await api.post(`/post/${postId}/like`);
        commit("UPDATE_POST_LIKES", {
          postId,
          likesCount: response.data.likes_count || response.data.likes,
          isLiked: true,
        });
        return response.data;
      } catch (error) {
        if (error.response && error.response.status === 400) {
          const data = error.response.data;
          commit("UPDATE_POST_LIKES", {
            postId,
            likesCount: data.likes,
            isLiked: data.is_liked,
          });
          return data;
        }
        throw error;
      }
    },
    async unlikePost({ commit }, postId) {
      try {
        const response = await api.post(`/post/${postId}/unlike`);
        commit("UPDATE_POST_LIKES", {
          postId,
          likesCount: response.data.likes_count || response.data.likes,
          isLiked: false,
        });
        return response.data;
      } catch (error) {
        if (error.response && error.response.status === 400) {
          const data = error.response.data;
          commit("UPDATE_POST_LIKES", {
            postId,
            likesCount: data.likes,
            isLiked: data.is_liked,
          });
          return data;
        }
        throw error;
      }
    },
    async sharePost({ commit }, postId) {
      try {
        const response = await api.post(`/post/${postId}/share`);
        commit("INCREMENT_SHARES", postId);
        return response.data;
      } catch (error) {
        console.error("Error sharing post:", error);
        throw error;
      }
    },
    async fetchComments({ commit }, postId) {
      try {
        const response = await api.get(`/post/${postId}/comments`);
        const commentsData = response.data.data || response.data;
        if (!Array.isArray(commentsData)) {
          console.error("Unexpected comments data format:", commentsData);
          return [];
        }
        return commentsData.map((comment) => ({
          ...comment,
          user: comment.user || { name: "Anonymous" },
        }));
      } catch (error) {
        console.error("Error fetching comments:", error);
        return [];
      }
    },
    async addComment({ commit, state }, { postId, content }) {
      try {
        const response = await api.post(`/post/${postId}/comments`, {
          content,
        });
        if (response.data) {
          const newComment = response.data;
          const updatedPost = state.posts.find((p) => p.id === postId);
          if (updatedPost) {
            commit("UPDATE_POST", {
              ...updatedPost,
              comments_count: (updatedPost.comments_count || 0) + 1,
            });
          }
          return newComment;
        } else {
          console.warn("Incomplete response data for addComment:", response);
          return null;
        }
      } catch (error) {
        console.error("Error adding comment:", error);
        throw error;
      }
    },
    addBookmark({ commit }, post) {
      commit("ADD_BOOKMARK", post);
    },
    removeBookmark({ commit }, postId) {
      commit("REMOVE_BOOKMARK", postId);
    },
  },
  getters: {
    allPosts: (state) => state.posts,
    isLoading: (state) => state.loading,
    error: (state) => state.error,
    pagination: (state) => state.pagination,
    bookmarkedPosts: state => state.bookmarkedPosts,
    isPostBookmarked: state => postId => state.bookmarkedPosts.some(p => p.id === postId),
  }
};
