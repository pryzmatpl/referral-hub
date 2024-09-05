import { createStore } from 'vuex';
import axios from 'axios';
import helper from '@/helpers.js';
import { reactive } from 'vue';

// Create a reactive state object
const state = reactive({
  showloading: 0,
  isAuthenticated: false,
  backend: axios.create({
    baseURL: process.env.BACKEND_URL,
    timeout: 5000,
    crossDomain: true,
  }),
  dehashedData: {
    SESSION_AUTH: '',
    SESSION_STATE: '',
    SESSION_ID: '',
    TIMESTAMP: ''
  },
  filterDefaults: {
    options: [
      { language: 'JavaScript', technology: 'Frontend' },
      { language: 'Vue.js', technology: 'Frontend' },
      { language: 'Angular', technology: 'Frontend' },
      { language: 'HTML', technology: 'Frontend' },
      { language: 'CSS', technology: 'Frontend' },
      { language: 'Node.js', technology: 'Backend' },
      { language: 'Ruby', technology: 'Backend' },
      { language: 'PHP', technology: 'Backend' },
      { language: 'Android', technology: 'Mobile' },
      { language: 'iOS', technology: 'Mobile' },
      { language: 'DevOps', technology: 'DevOps' },
      { language: 'Support', technology: 'DevOps' },
      { language: 'Testing', technology: 'DevOps' },
      { language: 'UI/UX', technology: 'Design' },
      { language: 'Business Inteligence', technology: 'Data' },
      { language: 'Business Analyst', technology: 'Data' },
    ],
    cities: ['Wroclaw','Koszalin','Krakow','Warszawa'],
    employment: ['B2B', 'UoP', 'UZ'],
    workload: ['All', 'Full-time', 'Part-time', 'Side-gig','Contractor'],
    perks: [
      'Free beverages',
      'Free snacks',
      'Free lunch',
      'Kitchen/canteen',
      'In-house trainings',
      'Training budget',
      'Office gym',
      'Shower',
      'Sports subscription',
      'Bike parking',
      'Car parking',
      'In-house hack days',
      'Team events',
      'Play room',
      'Private health care',
      'Kindergarten'
    ]
  },
  filterSelections: {
    technology: '',
    languages: [],
    city: '',
    employment: '',
    workload: '',
    teamSize: '',
    remote: '',
    salary: 0,
    relocation: '',
    perks: []
  },
  jobListing: [],
  jobsCount: '',
  resultPages: 0,
  currentPage: 0
});


// mutations are operations that actually mutates the state.
// each mutation handler gets the entire state tree as the
// first argument, followed by additional payload arguments.
// mutations must be synchronous and can be recorded by plugins
// for debugging purposes.
const mutations = {
  show(state) {
    state.showloading = true;
  },

  hide(state) {
    state.showloading = false;
  },

  authenticate(state, planck) {
    if (!state.isAuthenticated) {
      state.planck = planck;
      var dehashedData = helper.iwadehash(planck, 'prizm');
      var splitData = dehashedData.split('~');
      var obj = {}
      for (var i = 0; i < splitData.length; i++) {
        var temp = splitData[i].split(':')
        state.dehashedData[temp[0]] = temp[1]
      }
      state.isAuthenticated = true
    }
  },

  unauthenticate(state) {
    state.isAuthenticated = false
    state.dehashedData = {
      SESSION_AUTH: '',
      SESSION_STATE: '',
      SESSION_ID: '',
      TIMESTAMP: ''
    }
  },

  filterChange(state, payload) {
    state.filterSelections[payload.arg] = payload.value
  },

  changeTech(state, payload) {
    state.filterSelections.technology = payload
  },

  setJobs(state, payload) {
    state.jobListing = payload
  },

  setJobsCount(state, payload) {
    state.jobsCount = payload
  },

  setResultPages(state, payload) {
    state.resultPages = payload
  },

  setCurrentPage(state, payload) {
    state.currentPage = payload
  },

  clearAllFilters(state) {
    state.filterSelections = {
      //basic
      technology: '',
      languages: [],
      city: '',
      //advanced
      employment: '',
      workload: '',
      teamSize: '',
      remote: '',
      salary: 1000,
      relocation: '',
      perks: []
    }
  }
}
// actions are functions that cause side effects and can involve
// asynchronous operations.
const actions = {
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

    return axios
    .get("api/auth/signin", {params})
    .then(function(ret) {
      if (ret.data.auth) {
        commit('authenticate', ret.data.planck)
      }
      return Promise.resolve(ret)
    });
  },

  passwordRecovery({commit}, payload) {
    const recoveryPageUrl = 'https://refair.me/auth/recovery'
    let link = `api/auth/password/recoverlink?link_back=${recoveryPageUrl}&email=${payload.email}`
    
    return backend
    .get(link)
    .then(ret => Promise.resolve(ret))
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
  },

  updateFilterSelection: ({ commit }, value) => commit('filterChange',{arg: 'technology', value}),
  updateFilterLanguages: ({commit}, value) => commit('filterChange', {arg: 'languages',value}),
  updateFilterCity: ({commit}, value) => commit('filterChange', {arg: 'city',value}),
  updateFilterEmployment: ({commit}, value) => commit('filterChange',{arg: 'employment', value}),
  updateFilterWorkload: ({commit}, value) => commit('filterChange',{arg: 'workload', value}),
  updateFilterRelocation: ({commit}, value) => commit('filterChange',{arg: 'relocation', value}),
  updateFilterPerks: ({commit}, value) => commit('filterChange',{arg: 'perks', value}),

  updateCurrentPage: ({commit}, value) => commit('setCurrentPage', value),

  getJobs ({commit, state, getters}) {
    let baseUrl = process.env.BACKEND_URL + `/getjobs?logic=and&with=company,project&page=${state.currentPage}`
    if(getters.keywordsToString != '') baseUrl += `&keywords=${this.getters.keywordsToString}`
    if(state.filterSelections.city !== '' && state.filterSelections.city !== null) baseUrl += `&location=${state.filterSelections.city}`
    if(state.filterSelections.employment !== '' && state.filterSelections.employment !== null) baseUrl += `&contractType=${state.filterSelections.employment}`
    //if(state.filterSelections.workload !== '' && state.filterSelections.workload !== null) baseUrl += `&workload=${state.filterSelections.workload}`
    //if(state.filterSelections.teamSize !== '' && state.filterSelections.teamSize !== null) baseUrl += `&teamSize=${state.filterSelections.teamSize}`
    //if(state.filterSelections.remote !== '' && state.filterSelections.remote !== null) baseUrl += `&remote=${state.filterSelections.remote}`
    if(state.filterSelections.salary !== 0 && state.filterSelections.salary !== null) baseUrl += `&salary_min=${state.filterSelections.salary}`
    if(state.filterSelections.relocation !== '' && state.filterSelections.relocation !== null) baseUrl += `&relocation=${state.filterSelections.relocation}`
    if(state.filterSelections.perks.length != 0) baseUrl += `&perks=${state.filterSelections.perks}`

    state.backend
      .get(baseUrl)
      .then(ret => {
        commit('setJobs', ret.data.data)
        commit('setJobsCount', ret.data.count)
        commit('setResultPages', ret.data.pages)
        console.log(ret)
      })
  },

  clearAllFilters ({commit, dispatch}) {
    commit('clearAllFilters')
    dispatch('getJobs')
  }
}

// getters are functions
const getters = {
  mystate: (event, getters) => state.showloading,

  isAuthenticated: (state) => state.isAuthenticated,

  dehashedData: (state) => state.dehashedData,
  
  backend: (state) => state.backend,

  filterSelections: (state) => state.filterSelections,

  technologyList: (state) => state.filterDefaults.options
                              .map(object => object.technology)
                              .filter((elem, pos, arr) => arr.indexOf(elem) == pos),

  languageList: (state) => state.filterDefaults.options
                            .filter(({technology}) => technology === state.filterSelections.technology),

  keywordsToString: (state) => state.filterSelections.languages.map(o => o.language).join(),

  jobListing: (state) => state.jobListing,
  jobListingLength: (state) => state.jobsCount,
  resultPages: (state) => state.resultPages,
  currentPage: (state) => state.currentPage
}

// A Vuex instance is created by combining the state, mutations, actions,
// and getters.
export default createStore({
  state,
  mutations,
  actions,
  getters
});