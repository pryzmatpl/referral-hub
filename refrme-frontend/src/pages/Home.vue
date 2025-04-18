<template>
  <div class="container" style="max-width: 100%;">
    <div class="search-panel">
      <div class="row justify-content-center pt-5">
        <div class="col-8 title p-0 d-none d-sm-block">
          <h1 class="text-center fw-bold" style="color: white; font-size: 48px;">Find a job by criteria:</h1>
        </div>
      </div>
      <div class="row justify-content-center">
        <div class="card col-8">
          <div class="card-body text-light">
            <form>
              <div class="form-row d-flex justify-content-center mb-2">
                <div class="form-group col-12 col-sm-6 col-lg-3 m-1">
                  <label>Job Category</label>
                  <multiselect
                    v-model="localFilters.technology"
                    @input="safeUpdateFilter('technology', $event)"
                    :options="safeTechnologyList"
                    select-label=""
                    deselect-label=""
                  ></multiselect>
                </div>
                <div class="form-group col-12 col-sm-6 col-lg-3 m-1">
                  <label class="typo__label">Subcategory</label>
                  <multiselect
                    v-model="localFilters.languages"
                    @input="safeUpdateFilter('languages', $event)"
                    :options="safeLanguageList"
                    multiple
                    :searchable="false"
                    :close-on-select="false"
                    :clear-on-select="false"
                    hide-selected
                    preserve-search
                    placeholder="Pick some"
                    label="language"
                    track-by="language"
                    select-label=""
                    deselect-label=""
                  >
                    <template #tag="{ option, remove }">
                      <span class="custom__tag">
                        <span>{{ option.language }}</span>
                        <span class="custom__remove" @click="remove(option)">❌</span>
                      </span>
                    </template>
                  </multiselect>
                </div>
                <div class="form-group col-12 col-sm-6 col-lg-3 m-1">
                  <label>Location</label>
                  <multiselect
                    v-model="localFilters.city"
                    @input="safeUpdateFilter('city', $event)"
                    :options="safeCities"
                    :searchable="false"
                    close-on-select
                    :show-labels="false"
                    placeholder="Pick a city"
                  ></multiselect>
                </div>
                
              </div>
              <div class="form-group d-flex justify-content-center mt-4 text-light">
                  <label>&nbsp;</label>
                  <router-link class="btn custom-btn form-control" :to="{ path: '/results' }">{{ safeJobsCount }} RESULTS</router-link>
                </div>
            </form>
            <small class="d-flex justify-content-center mt-2">
              <router-link to="/search" class="text-light">Refine search</router-link>
            </small>
          </div>
        </div>
      </div>
    </div>
    <JobCarousel />
  </div>
</template>
<script>
import Multiselect from 'vue-multiselect'
import { computed, reactive, onMounted, watch } from 'vue'
import { useStore } from 'vuex'
import { faMoneyBillAlt, faWrench, faUsers } from '@fortawesome/fontawesome-free-solid'
import JobCarousel from '../components/JobCarousel.vue';

export default {
  components: {
    Multiselect,
    JobCarousel
  },

  setup() {
    // Use composition API for safer store access
    const store = useStore();
    
    // Default filter values
    const defaultFilterDefaults = {
      options: [
        { language: 'JavaScript', technology: 'Frontend' },
        { language: 'Vue.js', technology: 'Frontend' },
        { language: 'Angular', technology: 'Frontend' },
        { language: 'HTML', technology: 'Frontend' },
        { language: 'CSS', technology: 'Frontend' },
        { language: 'Node.js', technology: 'Backend' },
      ],
      cities: ['Wroclaw', 'Krakow', 'Warszawa'],
    };
    
    // Local reactive state
    const localState = reactive({
      selectedCity: '',
      resultsNumber: 0,
      filterDefaults: defaultFilterDefaults,
      filterSelections: {
        technology: '',
        languages: [],
        city: '',
      }
    });
    
    // Create a reactive local copy of filters
    const localFilters = reactive({
      technology: '',
      languages: [],
      city: ''
    });
    
    // Safe computed properties
    const safeFilterDefaults = computed(() => {
      try {
        return store.state.filterDefaults || defaultFilterDefaults;
      } catch (e) {
        console.error('[Home] Error accessing filterDefaults:', e);
        return defaultFilterDefaults;
      }
    });
    
    const safeFilterSelections = computed(() => {
      try {
        return store.state.filterSelections || localState.filterSelections;
      } catch (e) {
        console.error('[Home] Error accessing filterSelections:', e);
        return localState.filterSelections;
      }
    });
    
    const safeTechnologyList = computed(() => {
      try {
        if (store.getters.technologyList) {
          return store.getters.technologyList;
        }
      } catch (e) {
        console.error('[Home] Error accessing technologyList:', e);
      }
      
      // Fallback
      return [...new Set(safeFilterDefaults.value.options.map(({ technology }) => technology))];
    });
    
    const safeLanguageList = computed(() => {
      try {
        if (store.getters.languageList) {
          return store.getters.languageList;
        }
      } catch (e) {
        console.error('[Home] Error accessing languageList:', e);
      }
      
      // Fallback
      return safeFilterDefaults.value.options
        .filter(({ technology }) => technology === localFilters.technology);
    });
    
    const safeCities = computed(() => {
      return safeFilterDefaults.value.cities || defaultFilterDefaults.cities;
    });
    
    const safeJobsCount = computed(() => {
      try {
        return store.getters.jobListingLength || 0;
      } catch (e) {
        console.error('[Home] Error accessing jobListingLength:', e);
        return 0;
      }
    });
    
    // Initialize local filters from store values if available
    const initLocalFilters = () => {
      try {
        const storeSelections = store.state.filterSelections;
        if (storeSelections) {
          localFilters.technology = storeSelections.technology || '';
          localFilters.languages = storeSelections.languages || [];
          localFilters.city = storeSelections.city || '';
        }
      } catch (e) {
        console.error('[Home] Error initializing local filters:', e);
      }
    };
    
    // Safe update methods
    const safeDispatch = (action, payload) => {
      try {
        if (store && store.dispatch && typeof store.dispatch === 'function') {
          return store.dispatch(action, payload).catch(err => {
            console.error(`[Home] Error dispatching ${action}:`, err);
            return Promise.resolve(); // Return resolved promise to prevent further errors
          });
        } else {
          console.warn(`[Home] Store dispatch not available for action: ${action}`);
          return Promise.resolve(); // Return resolved promise
        }
      } catch (e) {
        console.error(`[Home] Error in safeDispatch for ${action}:`, e);
        return Promise.resolve(); // Return resolved promise
      }
    };
    
    const safeUpdateFilter = (key, value) => {
      // Update local state
      localFilters[key] = value;
      
      // Try to update store
      try {
        if (key === 'technology') {
          safeDispatch('updateFilter', { key, value });
        } else if (key === 'languages') {
          safeDispatch('updateFilter', { key, value });
        } else if (key === 'city') {
          safeDispatch('updateFilter', { key, value });
        }
        
        // Try to get jobs after filter update
        safeDispatch('getJobs');
      } catch (e) {
        console.error(`[Home] Error updating filter ${key}:`, e);
      }
    };
    
    // Support functions
    const remove = (option) => {
      if (localFilters.languages) {
        localFilters.languages = localFilters.languages.filter(
          lang => lang.language !== option.language
        );
      }
    };
    
    // Lifecycle hooks
    onMounted(() => {
      initLocalFilters();
      safeDispatch('getJobs');
    });
    
    // Watchers
    watch(() => localFilters.languages, () => {
      safeDispatch('getJobs');
    });
    
    watch(() => localFilters.city, () => {
      safeDispatch('getJobs');
    });
    
    return {
      localFilters,
      safeFilterDefaults,
      safeFilterSelections,
      safeTechnologyList,
      safeLanguageList,
      safeCities,
      safeJobsCount,
      safeUpdateFilter,
      remove, // Add remove function for tag removal
      moneyIcon: faMoneyBillAlt,
      wrenchIcon: faWrench,
      usersIcon: faUsers
    };
  }
}
</script>
<style lang="scss" scoped>
@use '../assets/settings.scss' as settings;

.container { 
  background-size: 100% 100%;
  max-width: 100%;
  height: 500px;
}

.search-panel {
  width: 100%;
  height: 50%;
}

.col-4 {
  .card {
    cursor: pointer;
  }
}

.card {
  background-color: transparent;
  border: none;
}

.custom-btn {
  background-color: #0DB3B4;
  backdrop-filter: blur(10px);
  width: 10rem;
}

.shadow {
  box-shadow: 0 4px 24px 0 rgba(37, 38, 94, 0.1);
  border: 0;
}

.d-flex label {
  margin-bottom: 0.5rem;
}

.pointer {
  cursor: pointer;
}

.card-title {
  color: #42bff4;
}

@import 'vue-multiselect/dist/vue-multiselect.css';
</style>

