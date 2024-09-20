import { createStore } from 'vuex'
import posts from './modules/post'
import auth from './modules/auth'
import notification from './modules/notification'
import user from './modules/user'

console.log('Auth module:', auth)
console.log('Auth actions:', auth.actions)

const store = createStore({
  modules: {
    posts,
    auth,
    notification,
    user,
  }
})

console.log('Store modules:', store._modules.root._children)
console.log('Store actions:', store._actions)

export default store

