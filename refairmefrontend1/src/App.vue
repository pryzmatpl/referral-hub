<template lang="pug">
  div
    nav#mainNav.navbar.navbar-expand-lg.navbar-light.text-uppercase
      router-link.navbar-brand(to='/')
        //img(src="./assets/refairme_logo.png" height="50")
        img.ml-2(src="./assets/refair-me-logo.svg" height="70")
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
            router-link.nav-link.py-3.px-0.px-lg-3(to='/search') Search
          li.nav-item.mx-0.mx-lg-1(v-if='isAuthenticated')
            router-link.nav-link.py-3.px-0.px-lg-3(to='/jobs') Jobs
          li.nav-item.mx-0.mx-lg-1(v-if='isAuthenticated')
            router-link.nav-link.py-3.px-0.px-lg-3(to='/profile') Profile
          li.nav-item.mx-0.mx-lg-1(v-if='isAuthenticated && isUserAllowed')
            router-link.nav-link.py-3.px-0.px-lg-3(to='/job/add') Add
          li.nav-item.mx-0.mx-lg-1(v-if='!isAuthenticated')
            router-link.nav-link.py-3.px-0.px-lg-3(to='/auth/signup') Signup
          li.nav-item.mx-0.mx-lg-1(v-if='!isAuthenticated')
            router-link.nav-link.py-3.px-0.px-lg-3(to='/auth/signin') Signin
          li.nav-item.mx-0.mx-lg-1(v-if='isAuthenticated')
            span.nav-link.py-3.px-0.px-lg-3(style='cursor: pointer', v-on:click='logout') Logout
    .container.p-0(v-bind:class="[isPathHome ? 'max-width' : '']")
      router-view
</template>

<script>
import store from '@/store/index.js'
import cookieconsent from 'cookieconsent'

export default {
  store,

  computed: {
    isAuthenticated: vm => vm.$store.getters.isAuthenticated,
    path: vm => vm.$route.path,
    isPathHome: vm => vm.path == '/',
    currentRole: vm => vm.$store.state.dehashedData.CURRENT_ROLE,
    isUserAllowed: vm => vm.currentRole === 'admin' || vm.currentRole === 'recruiter',
  },

  mounted () {
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:959248,hjsv:6};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');

    window.addEventListener("load", function(){
    window.cookieconsent.initialise({
      "palette": {
        "popup": {
          "background": "#eaf7f7",
          "text": "#5c7291"
        },
        "button": {
          "background": "transparent",
          "text": "#56cbdb",
          "border": "#56cbdb"
        }
      },
      "position": "bottom-left"
    })});
  },

  data () {
    return {
      locations: [],
      referrals: [],
      refkeywords: []
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
  //@import '../node_modules/bootstrap/scss/bootstrap.scss'
  @import url('https://fonts.googleapis.com/css?family=Source+Sans+Pro')
  @import '@/assets/settings.sass'

  body
    font-family: 'Source Sans Pro', sans-serif;
  nav
    background-color: white
    border-bottom: 1px solid rgba(0,0,0,0.1)
    margin: 0 auto
  .max-width
    max-width: 100% !important
  h1,h2,a
    color: $primaryColor
</style>
