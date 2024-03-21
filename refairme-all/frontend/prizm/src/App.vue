<template lang="pug">
  div
    nav#mainNav.navbar.navbar-expand-lg.navbar-light.sticky-top.text-uppercase(style='background-color: white; border-bottom: 1px solid rgba(0,0,0,0.1)')
      router-link.navbar-brand(to='/')
        img(src="./assets/refairme_logo.png" height="50")
      button.navbar-toggler.navbar-toggler-right.rounded(
        type='button',
        data-toggle='collapse',
        data-target='#navbarResponsive',
        aria-controls='navbarResponsive',
        aria-expanded='false',
        aria-label='Toggle navigation'
      )
        span.navbar-toggler-icon
      #navbarResponsive.collapse.navbar-collapse
        ul.navbar-nav.ml-auto
          li.nav-item.mx-0.mx-lg-1(v-if='isAuthenticated')
            router-link.nav-link.py-3.px-0.px-lg-3(to='/jobs') Jobs
          li.nav-item.mx-0.mx-lg-1(v-if='isAuthenticated')
            router-link.nav-link.py-3.px-0.px-lg-3(to='/profilebuild') Profile
          li.nav-item.mx-0.mx-lg-1(v-if='isAuthenticated')
            router-link.nav-link.py-3.px-0.px-lg-3(to='/job/add') Add
          li.nav-item.mx-0.mx-lg-1(v-if='!isAuthenticated')
            router-link.nav-link.py-3.px-0.px-lg-3(to='/auth/signup') Signup
          li.nav-item.mx-0.mx-lg-1(v-if='!isAuthenticated')
            router-link.nav-link.py-3.px-0.px-lg-3(to='/auth/signin') Signin
          li.nav-item.mx-0.mx-lg-1(v-if='isAuthenticated')
            span.nav-link.py-3.px-0.px-lg-3(style='cursor: pointer', v-on:click='logout') Logout
    .container(style='margin-top: 10px;')
      router-view
</template>

<script>
import store from '../prizm-vuex.js'

export default {
  store,

  computed: {
    isAuthenticated: vm => vm.$store.getters.isAuthenticated
  },

  data () {
    return {
      locations: [],
      referrals: [],
      baseUrl: process.env.BASE_URL,
      refkeywords: [],
      //jobsearchurl: process.env.BASE_URL + '/search/jobs',
    }
  },

  methods: {
    logout () {
      this.$store.dispatch('signout')
      .then(ret => this.$router.push('/'))
    }
  }
}
</script>
<style lang="sass">
@import '../node_modules/bootstrap/scss/bootstrap.scss'
</style>
