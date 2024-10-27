import Vue from 'vue'
import VueRouter from 'vue-router'
import router from './router'
import store from '../prizm-vuex.js'

Vue.use(VueRouter)
Vue.use(router)
Vue.router = router

Vue.config.productionTip = false

import App from './App'

new Vue({
  store,
  el: '#app',
  router,
  template: '<App/>',
  components: {
    App
  },
  computed: {
    isAuthenticated: vm => vm.$store.getters.isAuthenticated
  }
})
