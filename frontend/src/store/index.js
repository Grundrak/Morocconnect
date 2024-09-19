import { createStore } from 'vuex'
import posts from './modules/post'
import auth from './modules/auth'
import notification from './modules/notification'

console.log('Auth module:', auth)
console.log('Auth actions:', auth.actions)

const store = createStore({
  modules: {
    posts,
    auth,
    notification
  }
})

console.log('Store modules:', store._modules.root._children)
console.log('Store actions:', store._actions)

export default store

