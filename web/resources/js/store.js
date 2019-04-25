import { getLoggedinUser } from './partials/auth'
export default {
  state: {
    currentUser: null,
    isLoggedIn: false
  },
  getters: {
    isLoggedin(state) {
      return state.isLoggedin
    },
    currentUser(state) {
      return state.currentUser
    }
  },
  mutations: {
    loginSuccess(state, payload) {
      state.isLoggedin = true
      state.currentUser = payload.user
      localStorage.setItem('access_token', payload.access_token)
    },
    logout(state) {
      localStorage.removeItem('access_token')
      state.isLoggedin = false
      state.currentUser = null
    },
    getProfile(state) {
      getLoggedinUser().then(res => {
        state.isLoggedin = true
        state.currentUser = res
      }).catch(res => {
        localStorage.removeItem('access_token');
      })
    }
  }
}
