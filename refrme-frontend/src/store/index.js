import { createStore } from 'vuex';
import axios from 'axios';

// Explicitly import the process polyfill to ensure it's available for store initialization
import processPolyfill from 'process';

// Make process globally available immediately 
if (typeof window !== 'undefined') {
  window.process = window.process || processPolyfill;
}
// Also ensure it's available in the current scope
const process = window.process || processPolyfill;

console.log('[DEBUG:Store] Beginning store initialization');

// Safely access environment variables
const getEnvVar = (name, defaultValue = '') => {
  try {
    // Check if process is defined before accessing it
    const processEnv = process?.env || {};
    return processEnv[name] || defaultValue;
  } catch (error) {
    console.error(`[DEBUG:Store] Error accessing env var ${name}:`, error);
    return defaultValue;
  }
};

// Safely determine if we're in production
const isProd = () => {
  try {
    return getEnvVar('NODE_ENV') === 'production';
  } catch (error) {
    console.error('[DEBUG:Store] Error determining environment:', error);
    return false;
  }
};

const BACKEND_URL = getEnvVar('VUE_APP_BACKEND_URL', 'http://localhost');
console.log('[DEBUG:Store] Backend URL:', BACKEND_URL);

// Define default constants for development fallback
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

// Create axios instance with safe error handling
console.log('[DEBUG:Store] Creating axios instance');
let backend;
try {
  backend = axios.create({
    baseURL: BACKEND_URL,
    timeout: 5000,
    crossDomain: true,
  });
  console.log('[DEBUG:Store] Axios instance created successfully');
} catch (error) {
  console.error('[DEBUG:Store] Error creating axios instance:', error);
  // Create a fallback axios instance that will handle errors gracefully
  backend = {
    get: (url) => Promise.reject(new Error('Backend not available')),
    post: (url, data) => Promise.reject(new Error('Backend not available')),
    put: (url, data) => Promise.reject(new Error('Backend not available')),
    delete: (url) => Promise.reject(new Error('Backend not available'))
  };
}

// Create store with proper error handling
console.log('[DEBUG:Store] Creating and exporting store');
let store;
try {
  store = createStore({
    strict: !isProd(), // Avoid using strict mode in production
    
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
        // Ensure we're not overwriting the entire object if data is incomplete
        state.dehashedData = { ...state.dehashedData, ...data };
      },
      SET_FILTER: (state, { key, value }) => state.filterSelections[key] = value,
      SET_JOBS: (state, jobs) => state.jobListing = jobs || [],
      SET_JOBS_COUNT: (state, count) => state.jobsCount = count || 0,
      SET_JOBS_APPLIED: (state, jobApplied) => state.jobApplied = jobApplied || [],
      SET_RESULT_PAGES: (state, pages) => state.resultPages = pages || 0,
      SET_CURRENT_PAGE: (state, page) => state.currentPage = page || 0,
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

          if (response.data && response.data.auth) {
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
          
          if (response.data && response.data.auth) {
            // Make sure to commit authentication change BEFORE setting user data
            console.log('[DEBUG:Store] Setting authentication to true');
            commit('SET_AUTH', true);
            
            // Ensure we're setting data safely
            const userData = {
              USER_ID: uniqueId,
              CURRENT_ROLE: response.data.role || 'user' // Use role from response if available
            };
            commit('SET_DEHASHED_DATA', userData);
          }
          
          try {
            const jobApplied = await backend.get(`/getapply/${uniqueId}`);
            if(jobApplied && jobApplied.data) {
              commit('SET_JOBS_APPLIED', jobApplied.data);
            }
          } catch (jobError) {
            console.error('[DEBUG:Store] Error fetching applied jobs:', jobError);
            // Don't throw the error, just log it
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
          let response;
          try {
            response = await backend.get("api/auth/signout");
          } catch (apiError) {
            console.warn('[DEBUG:Store] API signout failed, clearing locally:', apiError);
            // Continue with local cleanup even if API call fails
          }
          
          // Always clear local state regardless of API response
          console.log('[DEBUG:Store] Setting authentication to false');
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
          // Don't rethrow to ensure UI can proceed with signout
          return { success: false, error: error.message };
        }
      },

      // Filter actions
      updateFilter: ({ commit }, { key, value }) => commit('SET_FILTER', { key, value }),
      updateFilterSelection: ({ commit }, value) => commit('SET_FILTER', { key: 'technology', value }),
      updateFilterLanguages: ({ commit }, value) => commit('SET_FILTER', { key: 'languages', value }),
      updateFilterCity: ({ commit }, value) => commit('SET_FILTER', { key: 'city', value }),
      updateCurrentPage: ({ commit }, page) => commit('SET_CURRENT_PAGE', page),
      updateJobApplied: ({commit, state}, job) => commit('SET_JOBS_APPLIED', [...state.jobApplied, job]),
      
      // Add missing filter action handlers for SearchDetail
      updateFilterEmployment: ({ commit }, value) => commit('SET_FILTER', { key: 'employment', value }),
      updateFilterWorkload: ({ commit }, value) => commit('SET_FILTER', { key: 'workload', value }),
      updateFilterRelocation: ({ commit }, value) => commit('SET_FILTER', { key: 'relocation', value }),
      updateFilterPerks: ({ commit }, value) => commit('SET_FILTER', { key: 'perks', value }),
      updateFilterTeamSize: ({ commit }, value) => commit('SET_FILTER', { key: 'teamSize', value }),
      updateFilterRemote: ({ commit }, value) => commit('SET_FILTER', { key: 'remote', value }),
      updateFilterSalary: ({ commit }, value) => commit('SET_FILTER', { key: 'salary', value }),

      async getJobs({ commit, state, getters }) {
        console.log('[DEBUG:Store] Get jobs action called');
        try {
          const queryParams = new URLSearchParams({
            logic: 'all',
            page: (state.currentPage || 0).toString()
          });

          // Add optional parameters
          const { filterSelections } = state;
          const optionalParams = {
            keywords: getters.keywordsToString,
            location: filterSelections.city,
            contractType: filterSelections.employment,
            salary_min: filterSelections.salary,
            relocation: filterSelections.relocation,
            perks: filterSelections.perks && filterSelections.perks.length ? filterSelections.perks.join(',') : null
          };

          // Safely add parameters
          Object.entries(optionalParams).forEach(([key, value]) => {
            if (value) queryParams.append(key, value.toString());
          });

          const response = await state.backend.get(`/getjobs?${queryParams.toString()}`);
          const data = response.data || { jobs: [], count: 0, pages: 0 };

          commit('SET_JOBS', data.jobs || data); // Handle different response formats
          commit('SET_JOBS_COUNT', data.count || (Array.isArray(data) ? data.length : 0));
          commit('SET_RESULT_PAGES', data.pages || 1);

          return data;
        } catch (error) {
          console.error('[DEBUG:Store] Get jobs error:', error);
          // Set empty default values on error
          commit('SET_JOBS', []);
          commit('SET_JOBS_COUNT', 0);
          commit('SET_RESULT_PAGES', 0);
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

      keywordsToString: state => {
        if (!state.filterSelections.languages) return '';
        return state.filterSelections.languages
          .map(item => (item && typeof item === 'object' && 'language' in item) ? item.language : '')
          .filter(Boolean)
          .join(',');
      },

      jobApplied: state => state.jobApplied || [],
      jobListing: state => state.jobListing || [],
      jobListingLength: state => state.jobsCount || 0,
      resultPages: state => state.resultPages || 0,
      currentPage: state => state.currentPage || 0
    }
  });
  console.log('[DEBUG:Store] Store created successfully');
} catch (error) {
  console.error('[DEBUG:Store] Error creating store:', error);
  // Create a minimal working store as fallback
  store = createStore({
    state: () => ({
      isAuthenticated: false,
      dehashedData: { CURRENT_ROLE: '' },
      filterDefaults: FILTER_DEFAULTS,
      filterSelections: INITIAL_FILTER_SELECTIONS,
      jobListing: [],
      jobApplied: [],
      jobsCount: 0,
      resultPages: 0,
      currentPage: 0
    }),
    getters: {
      isAuthenticated: () => false,
      technologyList: state => [...new Set(FILTER_DEFAULTS.options.map(({ technology }) => technology))],
      languageList: state => FILTER_DEFAULTS.options.filter(({ technology }) => 
        technology === state.filterSelections.technology
      ),
      keywordsToString: () => '',
      jobListingLength: () => 0
    },
    actions: {
      // Add stub actions that return resolved promises
      getJobs: () => Promise.resolve([]),
      updateFilter: () => Promise.resolve(),
      updateFilterSelection: () => Promise.resolve(),
      updateFilterLanguages: () => Promise.resolve(),
      updateFilterCity: () => Promise.resolve(),
      signin: () => Promise.resolve({ data: { auth: false } }),
      signout: () => Promise.resolve()
    }
  });
  console.warn('[DEBUG:Store] Created fallback store');
}

export default store;