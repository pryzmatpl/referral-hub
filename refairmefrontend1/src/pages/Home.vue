<template>
  <div class="container" style="max-width: 100%;">
    <div class="search-panel">
      <div class="row justify-content-center pt-5">
        <div class="col-8 title p-0 d-none d-sm-block">
          <h1 style="color: white; font-size: 48px;">Research Engine</h1>
        </div>
      </div>
      <div class="row justify-content-center">
        <div class="card col-8 shadow">
          <div class="card-body">
            <form>
              <div class="form-row d-flex mb-2">
                <div class="form-group col-12 col-sm-6 col-lg-3">
                  <label>Job Category</label>
                  <multiselect
                    :value="filterSelections.technology"
                    @input="updateFilterSelection"
                    :options="technologyList"
                    select-label=""
                    deselect-label=""
                  ></multiselect>
                </div>
                <div class="form-group col-12 col-sm-6 col-lg-3">
                  <label class="typo__label">Subcategory</label>
                  <multiselect
                    :value="filterSelections.languages"
                    @input="updateFilterLanguages"
                    :options="languageList"
                    multiple
                    searchable="false"
                    close-on-select="false"
                    clear-on-select="false"
                    hide-selected
                    preserve-search
                    placeholder="Pick some"
                    label="language"
                    track-by="language"
                    select-label=""
                    deselect-label=""
                  >
                    <template #tag="{ props }">
                      <span class="custom__tag">
                        <span>{{ props.option.language }}</span>
                        <span class="custom__remove" @click="props.remove(props.option)">❌</span>
                      </span>
                    </template>
                  </multiselect>
                </div>
                <div class="form-group col-12 col-sm-6 col-lg-3">
                  <label>Location</label>
                  <multiselect
                    :value="filterSelections.city"
                    @input="updateFilterCity"
                    :options="filterDefaults.cities"
                    searchable="false"
                    close-on-select
                    show-labels="false"
                    placeholder="Pick a city"
                  ></multiselect>
                </div>
                <div class="form-group col-12 col-sm-6 col-lg-3">
                  <label>&nbsp;</label>
                  <router-link class="btn btn-danger form-control" :to="{ path: '/results' }">{{ jobListingLength }} results</router-link>
                </div>
              </div>
            </form>
            <small class="float-left">
              <router-link to="/search">Refine search</router-link>
            </small>
          </div>
        </div>
      </div>
    </div>
    <div class="row justify-content-center" style="margin: 0 auto; margin-top: -20px;">
      <div class="col-8 pl-0 pr-0">
        <div class="row">
          <div class="col-12 col-md-4 pl-0">
            <div class="card text-left shadow" @click="$router.push({ name: 'Profile', params: { tab: 1 } })">
              <div class="card-body">
                <h2 class="card-title">
                  <font-awesome-icon :icon="moneyIcon" :style="{color: '#42bff4'}" />
                  Salary survey
                </h2>
                <p class="card-text">Create profile and get free report on how your salary compares</p>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-4 pr-0">
            <div class="card text-left shadow" @click="$router.push({ name: 'Profile', params: { tab: 1 } })">
              <div class="card-body">
                <h2 class="card-title">
                  <font-awesome-icon :icon="wrenchIcon" :style="{color: '#42bff4'}"/>
                  Skill survey
                </h2>
                <p class="card-text">Create profile see how your skills compare against your colleagues</p>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-4 pl-0">
            <div class="card text-left shadow" @click="$router.push({ name: 'Profile', params: { tab: 1 } })">
              <div class="card-body">
                <h2 class="card-title">
                  <font-awesome-icon :icon="usersIcon" :style="{color: '#42bff4'}"/>
                  Career comparison
                </h2>
                <p class="card-text">Create profile and compare your career path against your peers</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Multiselect from 'vue-multiselect'
import { mapState, mapActions, mapGetters } from 'vuex'
import { faMoneyBillAlt, faWrench, faUsers } from '@fortawesome/fontawesome-free-solid'

export default {
  components: {
    Multiselect,
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
.container {
  background-color: #42bff4;
  background-size: 100% 100%;
  max-width: 100%;
  height: 500px;
}

.search-panel {
  width: 100%;
  height: 500px;
}

.col-4 {
  .card {
    cursor: pointer;
  }
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

