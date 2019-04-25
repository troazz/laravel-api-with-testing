require('./bootstrap')

import Vue from 'vue'
import VueRouter from 'vue-router'
import Vuex from 'vuex'
import VeeValidate from 'vee-validate'
import Notifications from 'vue-notification'
import Loading from 'vue-loading-overlay'
import { routes } from './routes.js'
import MainApp from './components/MainApp.vue'
import storeData from './store.js'
import 'vue-loading-overlay/dist/vue-loading.css'

Vue.use(VeeValidate)
Vue.use(VueRouter)
Vue.use(Vuex)
Vue.use(Notifications)
Vue.use(Loading, {
  loader: 'dots'
})

const router = new VueRouter({
  routes,
  mode: 'hash'
})

// Creates a `nextMiddleware()` function which not only
// runs the default `next()` callback but also triggers
// the subsequent Middleware function.
function nextFactory(context, middleware, index) {
  const subsequentMiddleware = middleware[index]
  // If no subsequent Middleware exists,
  // the default `next()` callback is returned.
  if (!subsequentMiddleware) return context.next

  return (...parameters) => {
    // Run the default Vue Router `next()` callback first.
    context.next(...parameters)
    // Then run the subsequent Middleware with a new
    // `nextMiddleware()` callback.
    const nextMiddleware = nextFactory(context, middleware, index)
    subsequentMiddleware({ ...context, next: nextMiddleware })
  }
}

router.beforeEach((to, from, next) => {
  if (to.meta.middleware) {
    const middleware = Array.isArray(to.meta.middleware)
      ? to.meta.middleware
      : [to.meta.middleware]

    const context = {
      from,
      next,
      router,
      to
    }
    const nextMiddleware = nextFactory(context, middleware, 1)

    return middleware[0]({ ...context, next: nextMiddleware })
  }

  return next()
})

const store = new Vuex.Store(storeData)

const app = new Vue({
  el: '#app',
  router,
  store,
  components: {
    MainApp
  },
  mounted: function() {
    this.$store.commit('getProfile')
  }
})
