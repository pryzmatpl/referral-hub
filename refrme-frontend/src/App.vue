<template>
  <ModalTarget />
  <div class="main">
    <img class="position-fixed h-100 w-100 z-n1" src="./assets/background.png">
    <nav id="mainNav" class="navbar navbar-expand-lg navbar-light text-uppercase p-2 px-3 w-75">
      <router-link class="navbar-brand" to="/">
        <!-- <img src="./assets/refairme_logo.png" height="50"> -->
        <img src="./assets/refair-me-logo.svg" height="70" class="ml-2">
      </router-link>
      <button class="navbar-toggler navbar-toggler-right rounded" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div id="navbarResponsive" class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item mx-0 mx-lg-1" v-if="isAuthenticated">
            <router-link class="nav-link py-3 px-0 px-lg-3" to="/search">Search</router-link>
          </li>
          <li class="nav-item mx-0 mx-lg-1" v-if="isAuthenticated">
            <router-link class="nav-link py-3 px-0 px-lg-3" to="/jobs">Jobs</router-link>
          </li>
          <li class="nav-item mx-0 mx-lg-1" v-if="isAuthenticated">
            <router-link class="nav-link py-3 px-0 px-lg-3" to="/profile">Profile</router-link>
          </li>
          <li class="nav-item mx-0 mx-lg-1" v-if="isAuthenticated && isUserAllowed">
            <router-link class="nav-link py-3 px-0 px-lg-3" to="/job/add">Add</router-link>
          </li>
          <li class="nav-item mx-0 mx-lg-1" v-if="!isAuthenticated">
            <router-link class="nav-link py-3 px-0 px-lg-3" to="/auth/signin">Signin</router-link>
          </li>
          <li class="nav-item mx-0 mx-lg-1" v-if="isAuthenticated">
            <span class="nav-link py-3 px-0 px-lg-3" style="cursor: pointer" v-on:click="logout">Logout</span>
          </li>
        </ul>
      </div>
    </nav>
    <div class="container p-0" v-bind:class="[isPathHome ? 'max-width' : '']">
      <router-view></router-view>
    </div>
  </div>

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
<style lang="scss">
//@import '../node_modules/bootstrap/scss/bootstrap.scss';
@import url('https://fonts.googleapis.com/css?family=Source+Sans+Pro');
@import '@/assets/settings.scss';
@import url('https://cdn.jsdelivr.net/npm/vue-slider-component@latest/theme/default.css');

body {
  font-family: 'Source Sans Pro', sans-serif;
  color: white;
}

nav {
  margin: 0 auto;
}

.max-width {
  max-width: 100% !important;
}

.nav-link {
  color: white;
}

.glass-effect {
  border: 1px solid rgba(255, 255, 255, 0.3);
  background: rgba(255, 255, 255, 0.1);
  box-shadow: 0 0 44.5px rgba(0, 0, 0, 0.25);
  margin-bottom: 50px;
  padding: 8px;
  backdrop-filter: blur(20px);
}

.glass-effect > :first-child {
  background-color: white;
  border-radius: .375rem;
}


</style>
