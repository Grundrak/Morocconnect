import api from "../../services/api";

export default {
  namespaced: true,
  state: {
    posts: [],
    loading: false,
    error: null,
    pagination: null,
  },
  mutations: {
    SET_POSTS(state, posts) {
      state.posts = posts;
    },
    UPDATE_POST(state, updatedPost) {
      const index = state.posts.findIndex((post) => post.id === updatedPost.id);
      if (index !== -1) {
        state.posts.splice(index, 1, { ...state.posts[index], ...updatedPost });
      }
    },
    UPDATE_POST_LIKES(state, { postId, likesCount, isLiked }) {
      console.log("Updating post likes:", postId, likesCount, isLiked);
      const post = state.posts.find((p) => p.id === postId);
      if (post) {
        post.likes_count = likesCount;
        post.likes = likesCount; // Update both properties
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
    async likePost({ commit }, postId) {
      try {
        const response = await api.post(`/post/${postId}/like`);
        console.log("Like response:", response.data);
        commit("UPDATE_POST_LIKES", {
          postId,
          likesCount: response.data.likes_count || response.data.likes,
          isLiked: true,
        });
        return response.data;
      } catch (error) {
        if (error.response && error.response.status === 400) {
          // Handle already liked case
          const data = error.response.data;
          commit("UPDATE_POST_LIKES", {
            postId,
            likesCount: data.likes,
            isLiked: data.is_liked,
          });
          return data;
        }
        console.error(
          "Error liking post:",
          error.response ? error.response.data : error
        );
        throw error;
      }
    },
    async unlikePost({ commit }, postId) {
      try {
        const response = await api.post(`/post/${postId}/unlike`);
        console.log("Unlike response:", response.data);
        commit("UPDATE_POST_LIKES", {
          postId,
          likesCount: response.data.likes_count || response.data.likes,
          isLiked: false,
        });
        return response.data;
      } catch (error) {
        if (error.response && error.response.status === 400) {
          // Handle already unliked case
          const data = error.response.data;
          commit("UPDATE_POST_LIKES", {
            postId,
            likesCount: data.likes,
            isLiked: data.is_liked,
          });
          return data;
        }
        console.error(
          "Error unliking post:",
          error.response ? error.response.data : error
        );
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
  },
  getters: {
    allPosts: (state) => state.posts,
    isLoading: (state) => state.loading,
    error: (state) => state.error,
    pagination: (state) => state.pagination,
  },
};
