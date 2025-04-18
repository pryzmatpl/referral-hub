import { createStore } from 'vuex';
import axios from 'axios';

console.log('[DEBUG:Store] Beginning store initialization');
console.log('[DEBUG:Store] Backend URL:', process.env["VUE_APP_BACKEND_URL"]);

// Create axios instance
console.log('[DEBUG:Store] Creating axios instance');
let backend;
try {
  backend = axios.create({
    baseURL: process.env["VUE_APP_BACKEND_URL"],
    timeout: 5000,
    crossDomain: true,
  });
  console.log('[DEBUG:Store] Axios instance created successfully');
} catch (error) {
  console.error('[DEBUG:Store] Error creating axios instance:', error);
}

// Constants
console.log('[DEBUG:Store] Setting up constants');
const INITIAL_FILTER_SELECTIONS = {
  technology: '',
  languages: [],
  city: '',
  employment: '',
  workload: '',
  teamSize: '',
  remote: '',
  salary: 1000,
  relocation: '',
  perks: []
};

const FILTER_DEFAULTS = {
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
  cities: ['Wroclaw', 'Koszalin', 'Krakow', 'Warszawa'],
  employment: ['B2B', 'UoP', 'UZ'],
  workload: ['All', 'Full-time', 'Part-time', 'Side-gig', 'Contractor'],
  perks: [
    'Free beverages', 'Free snacks', 'Free lunch', 'Kitchen/canteen',
    'In-house trainings', 'Training budget', 'Office gym', 'Shower',
    'Sports subscription', 'Bike parking', 'Car parking', 'In-house hack days',
    'Team events', 'Play room', 'Private health care', 'Kindergarten'
  ]
};

console.log('[DEBUG:Store] Creating and exporting store');
let store;
try {
  store = createStore({
    state: () => {
      console.log('[DEBUG:Store] Initializing state');
      return {
        showloading: false,
        isAuthenticated: false,
        backend,
        dehashedData: {
          SESSION_AUTH: '',
          SESSION_STATE: '',
          SESSION_ID: '',
          TIMESTAMP: '',
          USER_ID: '',
          CURRENT_ROLE: '' // Ensure CURRENT_ROLE is initialized
        },
        filterDefaults: FILTER_DEFAULTS,
        filterSelections: { ...INITIAL_FILTER_SELECTIONS },
        jobListing: [],
        jobApplied: [],
        jobsCount: '',
        resultPages: 0,
        currentPage: 0
      };
    },

    mutations: {
      SET_LOADING: (state, value) => state.showloading = value,
      SET_AUTH: (state, value) => state.isAuthenticated = value,
      SET_DEHASHED_DATA: (state, data) => {
        console.log('[DEBUG:Store] Setting dehashed data:', data);
        state.dehashedData = data;
      },
      SET_FILTER: (state, { key, value }) => state.filterSelections[key] = value,
      SET_JOBS: (state, jobs) => state.jobListing = jobs,
      SET_JOBS_COUNT: (state, count) => state.jobsCount = count,
      SET_JOBS_APPLIED: (state, jobApplied) => state.jobApplied = jobApplied,
      SET_RESULT_PAGES: (state, pages) => state.resultPages = pages,
      SET_CURRENT_PAGE: (state, page) => state.currentPage = page,
      RESET_FILTERS: (state) => state.filterSelections = { ...INITIAL_FILTER_SELECTIONS }
    },

    actions: {
      setLoading: ({ commit }, value) => commit('SET_LOADING', value),

      async signup({ commit }, userData) {
        console.log('[DEBUG:Store] Signup action called');
        try {
          const response = await backend.post("/auth/signup", {
            ...userData
          });

          if (response.data.auth) {
            commit('SET_AUTH', true);
          }

          return response;
        } catch (error) {
          console.error('[DEBUG:Store] Signup error:', error);
          throw error;
        }
      },

      async signin({ commit }, { uniqueId }) {
        console.log('[DEBUG:Store] Signin action called');
        try {
          const response = await backend.post("/auth/signin", {
            params: { uniqueId }
          });
          if (response.data.auth) {
            commit('SET_AUTH', true);
            commit('SET_DEHASHED_DATA', {
              USER_ID: uniqueId,
              CURRENT_ROLE: 'user' // Default role if not specified in response
            });
          }
          const jobApplied = await backend.get(`/getapply/${uniqueId}`);
          if(jobApplied.data) {
            commit('SET_JOBS_APPLIED', jobApplied.data);
          }
          return response;
        } catch (error) {
          console.error('[DEBUG:Store] Signin error:', error.response?.data?.message || error.message);
          throw error;
        }
      },

      async passwordRecovery(_, { email }) {
        console.log('[DEBUG:Store] Password recovery action called');
        const recoveryPageUrl = '/auth/recovery';
        const link = `api/auth/password/recoverlink?link_back=${recoveryPageUrl}&email=${email}`;

        return await backend.get(link);
      },

      async signout({ commit }) {
        console.log('[DEBUG:Store] Signout action called');
        try {
          const response = await backend.get("api/auth/signout");
          commit('SET_AUTH', false);
          commit('SET_DEHASHED_DATA', {
            SESSION_AUTH: '',
            SESSION_STATE: '',
            SESSION_ID: '',
            TIMESTAMP: '',
            USER_ID: '',
            CURRENT_ROLE: ''
          });
          return response;
        } catch (error) {
          console.error('[DEBUG:Store] Signout error:', error);
          throw error;
        }
      },

      // Filter actions
      updateFilter: ({ commit }, { key, value }) => commit('SET_FILTER', { key, value }),
      updateCurrentPage: ({ commit }, page) => commit('SET_CURRENT_PAGE', page),
      updateJobApplied: ({commit, state}, job) => commit('SET_JOBS_APPLIED', [...state.jobApplied, job]),

      async getJobs({ commit, state, getters }) {
        console.log('[DEBUG:Store] Get jobs action called');
        try {
          const queryParams = new URLSearchParams({
            logic: 'all',
            page: state.currentPage.toString()
          });

          // Add optional parameters
          const { filterSelections } = state;
          const optionalParams = {
            keywords: getters.keywordsToString,
            location: filterSelections.city,
            contractType: filterSelections.employment,
            salary_min: filterSelections.salary,
            relocation: filterSelections.relocation,
            perks: filterSelections.perks.length ? filterSelections.perks.join(',') : null
          };

          Object.entries(optionalParams).forEach(([key, value]) => {
            if (value) queryParams.append(key, value.toString());
          });

          const response = await state.backend.get(`/getjobs?${queryParams.toString()}`);
          const { data } = response;

          commit('SET_JOBS', data);
          commit('SET_JOBS_COUNT', data.count);
          commit('SET_RESULT_PAGES', data.pages);

          return data;
        } catch (error) {
          console.error('[DEBUG:Store] Get jobs error:', error);
          throw error;
        }
      },

      clearAllFilters({ commit, dispatch }) {
        console.log('[DEBUG:Store] Clear all filters action called');
        commit('RESET_FILTERS');
        return dispatch('getJobs');
      }
    },

    getters: {
      isLoading: state => state.showloading,
      isAuthenticated: state => state.isAuthenticated,
      dehashedData: state => state.dehashedData,
      backend: state => state.backend,
      filterSelections: state => state.filterSelections,

      technologyList: state => [...new Set(
          state.filterDefaults.options.map(({ technology }) => technology)
      )],

      languageList: state => state.filterDefaults.options
          .filter(({ technology }) => technology === state.filterSelections.technology),

      keywordsToString: state => state.filterSelections.languages
          .map(({ language }) => language)
          .join(','),

      jobApplied: state => state.jobApplied,
      jobListing: state => state.jobListing,
      jobListingLength: state => state.jobsCount,
      resultPages: state => state.resultPages,
      currentPage: state => state.currentPage
    }
  });
  console.log('[DEBUG:Store] Store created successfully');
} catch (error) {
  console.error('[DEBUG:Store] Error creating store:', error);
}

export default store;