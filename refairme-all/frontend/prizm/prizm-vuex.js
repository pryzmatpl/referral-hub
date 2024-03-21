import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
import VueAxios from 'vue-axios';
import helper from './src/helpers.js'
require('dotenv').config();
require('jquery')

Vue.use(Vuex);
Vue.use(VueAxios, axios);
Vue.prototype.http = axios
// root state object.
// each Vuex instance is just a single state tree.

const backend = axios.create({
  baseURL: process.env.BACKEND_URL,
  timeout: 5000,
  crossDomain: true,
});

var state = {
  showloading: 0,
  isAuthenticated: false,
  backend,
  planck: 'FRESH',
  dehashedData: {
    SESSION_AUTH: '',
    SESSION_STATE: '',
    SESSION_ID: '',
    TIMESTAMP: ''
  },
}

backend.defaults.headers['planck'] = state.planck

// mutations are operations that actually mutates the state.
// each mutation handler gets the entire state tree as the
// first argument, followed by additional payload arguments.
// mutations must be synchronous and can be recorded by plugins
// for debugging purposes.
var mutations = {
  show(state) {
    state.showloading = true;
  },
  hide(state) {
    state.showloading = false;
  },
  authenticate(state, planck) {
    if (!state.isAuthenticated) {
      state.planck = planck;
      var dehashedData = helper.iwadehash(planck, process.env.HASH_BASE);
      var splitData = dehashedData.split('~');
      var obj = {}
      for (var i = 0; i < splitData.length; i++) {
        var temp = splitData[i].split(':')
        state.dehashedData[temp[0]] = temp[1]
      }
      state.isAuthenticated = true
      backend.defaults.headers['planck'] = state.planck
    }
  },
  unauthenticate(state) {
    state.isAuthenticated = false
  }
}
// actions are functions that cause side effects and can involve
// asynchronous operations.
var actions = {
  show: ({
    commit
  }) => commit('show'),
  hide: ({
    commit
  }) => commit('hide'),
  signin({commit}, payload, resolve) {
    console.log("SIGNIN action")

    const params = {
      email: payload.locmail,
      password: payload.locpass
    }

    return backend
    .get("api/auth/signin", {params})
    .then(function(ret) {
      if (ret.data.auth) {
        commit('authenticate', ret.data.planck)
      }
      return Promise.resolve(ret)
    });
  },
  signout({commit}) {
    return backend
    .get("api/auth/signout")
    .then(ret => {
      commit('unauthenticate')
      console.log(ret)
      return Promise.resolve(ret)
    })
    .catch(error => console.error(error));
  }
}

// getters are functions
var getters = {
  mystate: (event, getters) => {
    return state.showloading;
  },
  isAuthenticated: (state) => {
    return state.isAuthenticated
  },
  dehashedData: (state) => {
    return state.dehashedData
  },
  backend: (state) => {
    return state.backend
  }
}

// A Vuex instance is created by combining the state, mutations, actions,
// and getters.
export default new Vuex.Store({
  state,
  getters,
  actions,
  mutations,
  helper
});
