<template>
  <div class="container">
    <a class="nav-link py-3 px-0" href="#" @click="back">< Back</a>
    <div class="row">
      <div class="col d-flex justify-content-between">
        <h2 style="display: inline-block">Search results</h2>
        <a class="float-right" href="#" @click="clearAllFilters">Clear filters (show all jobs)</a>
      </div>
    </div>
    <div class="row">
      <div class="col-3 d-none d-lg-block">
        <div class="card shadow">
          <div class="card-body">
            <h3>Filter by</h3>
            <hr>
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label>Job Category</label>
                  <multiselect
                    v-model="filterSelections.technology"
                    @input="updateFilterSelection"
                    :options="technologyList"
                    :preselect-first="true"
                    selectLabel=""
                    deselectLabel=""
                  />
                </div>
                <div class="form-group">
                  <label class="typo__label">Language</label>
                  <multiselect
                    v-model="filterSelections.languages"
                    @input="updateFilterLanguages"
                    :options="languageList"
                    :multiple="true"
                    :searchable="false"
                    :close-on-select="false"
                    :clear-on-select="false"
                    :hide-selected="true"
                    :preserve-search="true"
                    placeholder="Pick some"
                    label="language"
                    track-by="language"
                    :preselect-first="false"
                    selectLabel=""
                    deselectLabel=""
                  >
                    <template #tag="props">
                      <span class="custom__tag">
                        <span>{{ props.option.language }}</span>
                        <span class="custom__remove" @click="props.remove(props.option)">❌</span>
                      </span>
                    </template>
                  </multiselect>
                </div>
                <div class="form-group">
                  <label>Contract type</label>
                  <multiselect
                    v-model="filterSelections.employment"
                    @input="updateFilterEmployment"
                    :options="filterDefaults.employment"
                    :searchable="false"
                    :close-on-select="true"
                    :show-labels="false"
                    placeholder="Pick a type"
                  />
                </div>
                <div class="form-group">
                  <label>Workload</label>
                  <multiselect
                    v-model="filterSelections.workload"
                    @input="updateFilterWorkload"
                    :options="filterDefaults.workload"
                    :searchable="false"
                    :close-on-select="true"
                    :show-labels="false"
                    placeholder="Pick a type"
                  />
                </div>
                <div class="form-group">
                  <label>Remote</label>
                  <b-button-group class="w-100">
                    <b-button
                      v-for="remoteOption in ['Yes', 'Partially', 'No']"
                      :key="remoteOption"
                      type="button"
                      variant="outline-secondary"
                      @click="$store.commit('filterChange', { arg: 'remote', value: remoteOption })"
                      :class="{ active: $store.getters.filterSelections.remote === remoteOption }"
                      class="w-100"
                    >
                      {{ remoteOption }}
                    </b-button>
                  </b-button-group>
                </div>
                <div class="form-group">
                  <label>Project team size</label>
                  <b-button-group class="w-100">
                    <b-button
                      v-for="teamSizeOption in ['<10', '<50', '100+']"
                      :key="teamSizeOption"
                      type="button"
                      variant="outline-secondary"
                      @click="$store.commit('filterChange', { arg: 'teamSize', value: teamSizeOption })"
                      :class="{ active: $store.getters.filterSelections.teamSize === teamSizeOption }"
                      class="w-100"
                    >
                      {{ teamSizeOption }}
                    </b-button>
                  </b-button-group>
                </div>
                <div class="form-group">
                  <label>Minimum salary (monthly/gross)</label>
                  <Slider
                    :min="0"
                    :max="50000"
                    tooltip="hover"
                    :interval="1000"
                    v-model="filterSelections.salary"
                    @callback="$store.commit('filterChange', { arg: 'salary', value: $refs['sal'].getValue() })"
                    ref="sal"
                  />
                  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vue-slider-component@latest/theme/default.css">
                  <p>{{ filterSelections.salary }} PLN</p>
                </div>
                <div class="form-group">
                  <label>Relocation package</label>
                  <div>
                    <div class="form-check form-check-inline" v-for="option in [{ name: 'Yes', value: 1 }, { name: 'No', value: 0 }]" :key="option.value">
                      <input
                        id="inlineRadio1"
                        class="form-check-input"
                        type="radio"
                        name="inlineRadioOptions"
                        :value="option.value"
                        v-model="relocation"
                      />
                      <label class="form-check-label">{{ option.name }}</label>
                    </div>
                  </div>
                </div>
                <hr>
                <h5>Other perks</h5>
                <span class="float-right">></span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-9">
        <div class="card mb-2 shadow">
          <div class="card-body">
            <h5>{{ jobListingLength }} results for your search</h5>
            <p>{{ languagesSelected.length == 0 ? 'No filters selected' : languagesSelected }}</p>
            <JobListItem
              v-for="job in jobListing"
              :job="job"
              :key="job.id"
            />
            <nav aria-label="Page navigation example">
              <ul class="pagination justify-content-center">
                <li class="page-item" v-if="currentPage > 0">
                  <a class="page-link" href="#" aria-label="Previous" @click="updateCurrentPage(currentPage - 1)">
                    <span aria-hidden="true">«</span>
                    <span class="sr-only">Previous</span>
                  </a>
                </li>
                <li class="page-item" v-for="o in resultPages" :key="o">
                  <a class="page-link" href="#" @click="updateCurrentPage(o - 1)">{{ o }}</a>
                </li>
                <li class="page-item" v-if="currentPage < (resultPages - 1)">
                  <a class="page-link" href="#" aria-label="Next" @click="updateCurrentPage(currentPage + 1)">
                    <span aria-hidden="true">»</span>
                    <span class="sr-only">Next</span>
                  </a>
                </li>
              </ul>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import JobListItem from '@/components/JobListItem'
import Multiselect from 'vue-multiselect'
import Slider from 'vue-slider-component'
import {mapState, mapActions, mapGetters} from 'vuex'

export default {
  components: {
    JobListItem,
    Multiselect,
    Slider
  },

  mounted() {
    window.scroll(0,0)
    this.$store.dispatch('getJobs')
  },

  computed: {
    ...mapState(['filterDefaults','filterSelections','currentPage']),
    ...mapGetters(['technologyList', 'languageList','jobListing','jobListingLength','resultPages']),
    relocation: {
      get () {
        return this.$store.state.filterSelections.relocation
      },
      set (value) {
        this.$store.dispatch('updateFilterRelocation', value)
      }
    },
    languagesSelected: vm => vm.filterSelections.languages.map(obj => obj.language).join(', ')
  },

  watch: {
    'filterSelections.languages' () {this.$store.dispatch('getJobs')},
    'filterSelections.city' () {this.$store.dispatch('getJobs')},
    'filterSelections.employment' () {this.$store.dispatch('getJobs')},
    'filterSelections.salary' () {this.$store.dispatch('getJobs')},
    'filterSelections.relocation' () {this.$store.dispatch('getJobs')},
    'currentPage' () {this.$store.dispatch('getJobs')}
  },

  methods: {
    ...mapActions([
      'updateFilterEmployment',
      'updateFilterWorkload',
      'clearAllFilters',
      'updateCurrentPage',
      'updateFilterSelection',
      'updateFilterLanguages',
      'updateFilterCity'
    ]),

    back () {
      this.$router.go(-1)
    }
  },

  data () {
    return {
      userSelected: {
        employmentType: '',
        workload: '',
        remote: '',
        teamSize: '',
        remote: '',
        relocationPackage: ''
      }
    }
  }
}
</script>
<style lang="scss" scoped>
.btn.active {
  z-index: 0; // without that gets in front of vue-multiselect
}

.shadow {
  box-shadow: 0 4px 24px 0 rgba(37, 38, 94, 0.1);
  border: 0;
}

#inlineRadio1 {
  border: 1px solid black;
}

@import 'vue-multiselect/dist/vue-multiselect.css';
</style>


