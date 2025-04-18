<template>
  <ModalTarget />
  <div class="main">
    <img class="position-fixed h-100 w-100 z-n1" src="./assets/background.png">
    <nav id="mainNav" class="navbar navbar-expand-lg navbar-light text-uppercase p-2 px-3 w-75">
      <router-link class="navbar-brand" to="/">
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
            <span class="nav-link py-3 px-0 px-lg-3" style="cursor: pointer" @click="logout">Logout</span>
          </li>
        </ul>
      </div>
    </nav>
    <div class="container p-0" :class="[isPathHome ? 'max-width' : '']">
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
import store from '@/store/index.js'
import cookieconsent from 'cookieconsent'
import {ModalTarget} from "@kolirt/vue-modal";
import { computed, onMounted, reactive, toRefs } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useStore } from 'vuex'

export default {
  components: { ModalTarget },
  setup() {
    console.log('[DEBUG:App] Starting App component setup');
    
    let router, route, store;
    
    try {
      console.log('[DEBUG:App] Initializing router');
      router = useRouter();
      console.log('[DEBUG:App] Router initialized successfully');
    } catch (error) {
      console.error('[DEBUG:App] Error initializing router:', error);
    }
    
    try {
      console.log('[DEBUG:App] Initializing route');
      route = useRoute();
      console.log('[DEBUG:App] Route initialized successfully');
    } catch (error) {
      console.error('[DEBUG:App] Error initializing route:', error);
    }
    
    try {
      console.log('[DEBUG:App] Initializing store with useStore()');
      store = useStore();
      console.log('[DEBUG:App] Store initialized successfully:', store ? 'store exists' : 'store is undefined');
    } catch (error) {
      console.error('[DEBUG:App] Error initializing store:', error);
    }
    
    console.log('[DEBUG:App] Creating reactive state');
    const state = reactive({
      locations: [],
      referrals: [],
      refkeywords: []
    })
    
    // Computed properties
    console.log('[DEBUG:App] Setting up computed properties');
    
    const isAuthenticated = computed(() => {
      console.log('[DEBUG:App] Computing isAuthenticated, store:', store ? 'exists' : 'undefined');
      console.log('[DEBUG:App] Store getters:', store?.getters ? 'exist' : 'undefined');
      return store?.getters?.isAuthenticated;
    });
    
    const path = computed(() => {
      console.log('[DEBUG:App] Computing path, route:', route ? 'exists' : 'undefined');
      return route?.path;
    });
    
    const isPathHome = computed(() => {
      console.log('[DEBUG:App] Computing isPathHome');
      return path.value == '/';
    });
    
    const currentRole = computed(() => {
      console.log('[DEBUG:App] Computing currentRole, store state:', store?.state ? 'exists' : 'undefined');
      console.log('[DEBUG:App] dehashedData:', store?.state?.dehashedData ? 'exists' : 'undefined');
      return store?.state?.dehashedData?.CURRENT_ROLE;
    });
    
    const isUserAllowed = computed(() => {
      console.log('[DEBUG:App] Computing isUserAllowed, currentRole:', currentRole.value);
      return currentRole.value === 'admin' || currentRole.value === 'recruiter';
    });
    
    // Methods
    console.log('[DEBUG:App] Setting up methods');
    const logout = () => {
      console.log('[DEBUG:App] Logging out');
      try {
        store.dispatch('signout')
          .then(ret => router.push('/'));
        console.log('[DEBUG:App] Logout dispatch successful');
      } catch (error) {
        console.error('[DEBUG:App] Error during logout:', error);
      }
    }
    
    // Lifecycle hooks
    console.log('[DEBUG:App] Setting up lifecycle hooks');
    onMounted(() => {
      console.log('[DEBUG:App] Component mounted');
      window.addEventListener("load", function(){
        console.log('[DEBUG:App] Window loaded, initializing cookieconsent');
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
        });
        console.log('[DEBUG:App] Cookieconsent initialized');
      });
    })
    
    console.log('[DEBUG:App] Setup complete, returning state');
    return {
      ...toRefs(state),
      isAuthenticated,
      path,
      isPathHome,
      currentRole,
      isUserAllowed,
      logout
    }
  }
}
</script>

<style lang="scss" scoped>
@use './assets/settings.scss' as settings;
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
