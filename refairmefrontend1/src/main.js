import Vue from "vue"
import VueRouter from "vue-router"
import router from "./router"
import store from "@/store/index.js"

import BootstrapVue from "bootstrap-vue"
import "bootstrap/dist/css/bootstrap.css"
import "bootstrap-vue/dist/bootstrap-vue.css"
// import VueAnalytics from 'vue-analytics'
import VueAnalytics from "vue-ua"

const isProd = process.env.NODE_ENV === "production"

Vue.use(BootstrapVue)
Vue.use(VueRouter)
Vue.use(router)
Vue.router = router
Vue.use(VueAnalytics, {
  appName: "referralhub.com", // Mandatory
  appVersion: "0.9", // Mandatory
  trackingId: "UA-122813704-1", // Mandatory
  trackPage: true,
  vueRouter: router,
  debug: true,
})
/*
Vue.use(VueAnalytics, {
  id: 'UA-122813704-1',
  router,
  debug: {
    enabled: true, //!isProd
    sendHitTask: isProd
  }
})*/

Vue.config.devtools = true
Vue.config.performance = true
Vue.config.productionTip = false

import App from "./App"

new Vue({
  store,
  el: "#app",
  router,
  template: "<App/>",
  components: {
    App,
  },
  computed: {
    isAuthenticated: vm => vm.$store.getters.isAuthenticated,
  },
})
