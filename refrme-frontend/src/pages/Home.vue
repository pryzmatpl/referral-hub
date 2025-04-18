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
                    v-model="filterSelections.technology"
                    @input="updateFilterSelection"
                    :options="technologyList"
                    select-label=""
                    deselect-label=""
                  ></multiselect>
                </div>
                <div class="form-group col-12 col-sm-6 col-lg-3 m-1">
                  <label class="typo__label">Subcategory</label>
                  <multiselect
                    v-model="filterSelections.languages"
                    @input="updateFilterLanguages"
                    :options="languageList"
                    multiple
                    searchable="false"
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
                    v-model="filterSelections.city"
                    @input="updateFilterCity"
                    :options="filterDefaults.cities"
                    searchable=false
                    close-on-select
                    show-labels=false
                    placeholder="Pick a city"
                  ></multiselect>
                </div>
                
              </div>
              <div class="form-group d-flex justify-content-center mt-4 text-light">
                  <label>&nbsp;</label>
                  <router-link class="btn custom-btn form-control" :to="{ path: '/results' }">{{ jobListingLength }} RESULTS</router-link>
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
import { mapState, mapActions, mapGetters } from 'vuex'
import { faMoneyBillAlt, faWrench, faUsers } from '@fortawesome/fontawesome-free-solid'
import JobCarousel from '../components/JobCarousel.vue';

export default {
  components: {
    Multiselect,
    JobCarousel
  },

  computed: {
    ...mapState(['filterDefaults', 'filterSelections']),
    ...mapGetters(['technologyList', 'languageList', 'jobListingLength']),
    moneyIcon: () => faMoneyBillAlt,
    wrenchIcon: () => faWrench,
    usersIcon: () => faUsers
  },

  mounted() {
    this.$store.dispatch('getJobs')
  },

  watch: {
    'filterSelections.languages'() {
      this.$store.dispatch('getJobs')
    },
    'filterSelections.city'() {
      this.$store.dispatch('getJobs')
    }
  },

  methods: {
    ...mapActions(['updateFilterSelection', 'updateFilterLanguages', 'updateFilterCity'])
  },

  data() {
    return {
      selectedCity: '',
      resultsNumber: 0
    }
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

