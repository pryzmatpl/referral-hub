<template>
  <div class="container col-10">
    <form>
      <div class="row">
        <div class="col-4">
          <router-link class="nav-link py-3 px-0 float-left" to="/"> &lt; Back </router-link>
        </div>
        <div class="col-4">
          <h2 class="mt-3 mb-4 text-center">Detail search</h2>
        </div>
        <div class="col-4 d-flex justify-content-end">
          <a href="#" class="py-3 px-0 text-white" @click="clearAllFilters">Clear all filters</a>
        </div>
      </div>
      <div class="card glass-effect">
        <div class="card-body">
          <h5>Basic filter</h5>
          <div class="form-row mb-2 d-flex">
            <div class="form-group col-12 col-sm-4 col-md-4 p-2">
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
            <div class="form-group col-12 col-sm-4 col-md-4 p-2">
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
                <template slot="tag" slot-scope="props">
                  <span class="custom__tag">
                    <span>{{ props.option.language }}</span>
                    <span class="custom__remove" @click="props.remove(props.option)">❌</span>
                  </span>
                </template>
              </multiselect>
            </div>
            <div class="form-group col-12 col-sm-6 col-md-4 p-2">
              <label>Location</label>
              <multiselect
                v-model="filterSelections.city"
                @input="updateFilterCity"
                :options="filterDefaults.cities"
                :searchable="false"
                :close-on-select="true"
                :show-labels="false"
                placeholder="Pick a city"
              />
            </div>
          </div>
        </div>
      </div>
      <div class="card mt-3 glass-effect">
        <div class="card-body">
          <h5>Contract type</h5>
          <div class="form-row d-flex flex-wrap">
            <div class="form-group col-4 p-2">
              <label>Employment type</label>
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
            <div class="form-group col-4 p-2">
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
            <div class="form-group col-12 col-sm-6 col-md-4 p-2">
              <label>Remote</label>
              <b-button-group class="w-100">
                    <b-button
                      v-for="remoteOption in  [{ name: 'Yes', value: 1 }, { name: 'Partially', value: 0.5 }, { name: 'No', value: 0 }]"
                      :key="remoteOption.value"
                      type="button"
                      variant="outline-secondary"
                      @click="updateFilterRemote(remoteOption.value)"
                      :class="{ active: $store.getters.filterSelections.remote === remoteOption.value }"
                      class="w-100"
                    >
                      {{ remoteOption.name }}
                    </b-button>
                  </b-button-group>
            </div>
            <div class="form-group col-12 col-sm-6 col-md-4 p-2">
              <label>Relocation package</label>
              <div class="ml-1">
                <div
                  v-for="option in [{ name: 'Yes', value: 1 }, { name: 'No', value: 0 }]"
                  :key="option.value"
                  class="form-check form-check-inline"
                >
                  <input
                    id="inlineRadio1"
                    class="form-check-input"
                    type="radio"
                    v-model="relocation"
                    :value="option.value"
                    name="inlineRadioOptions"
                  />
                  <label class="form-check-label" for="inlineRadio1">{{ option.name }}</label>
                </div>
              </div>
            </div>
            <div class="form-group col-12 col-sm-6 col-md-4 p-2">
              <label>Minimum salary (monthly/gross)</label>
              <Slider
                v-model="localSalary"
                :min="0"
                :max="20000"
                tooltip="hover"
                :interval="1000"
                @change="handleSalaryChange"
                ref="sal"
              />
              <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vue-slider-component@latest/theme/default.css">
              <p>{{ localSalary }} PLN</p>
            </div>
            <div class="form-group col-12 col-sm-6 col-md-4 p-2">
              <label>Project team size</label>
              <b-button-group class="w-100">
                <b-button
                  v-for="teamSizeOption in ['<10', '<50', '100+']"
                  :key="teamSizeOption"
                  type="button"
                  variant="outline-secondary"
                  @click="updateFilterTeamSize(teamSizeOption)"
                  :class="{ active: filterSelections.teamSize === teamSizeOption }"
                  class="w-100"
                >{{ teamSizeOption }}</b-button>
              </b-button-group>
            </div>
          </div>
        </div>
      </div>
      <div class="card mt-2 glass-effect">
        <div class="card-body">
          <h5>Other perks</h5>
          <div class="row p-2">
            <div
              v-for="perk in filterDefaults.perks"
              :key="perk"
              class="form-check col-12 col-sm-6 col-md-3 mb-1"
            >
              <input
                class="form-check-input"
                type="checkbox"
                :value="perk"
                v-model="perks"
                :id="perk"
              />
              <label class="form-check-label" :for="perk">{{ perk }}</label>
            </div>
          </div>
        </div>
      </div>
      <div class="card mt-2 glass-effect">
        <div class="card-body">
          <h5>Project</h5>
          <div class="row p-2">
            <div
              v-for="(item, index) in formResources.methodologies"
              :key="index"
              class="form-check col-12 col-sm-6 col-md-3 mb-1"
            >
              <input
                class="form-check-input"
                type="checkbox"
                :value="item"
                v-model="selectedMethodologies"
                :id="index"
              />
              <label class="form-check-label" :for="item">{{ item }}</label>
            </div>
          </div>
        </div>
      </div>
      <div class="row justify-content-center pt-2 m-4">
        <router-link class="btn custom-btn mb-5 w-50" to="/results" tag="button">
          {{ jobListingLength }} Jobs You Can Start Today
        </router-link>
      </div>
    </form>
  </div>
</template>
<script>
import BaseFormGroupInput from '@/components/BaseFormGroupInput'
import BaseFormCheckbox from '@/components/BaseFormCheckbox'
import Multiselect from 'vue-multiselect'
import Slider from 'vue-slider-component'
import {mapState, mapActions, mapGetters} from 'vuex'

export default {
  components: {
    BaseFormGroupInput,
    BaseFormCheckbox,
    Multiselect,
    Slider
  },

  computed: {
    ...mapState(['filterDefaults','filterSelections']),
    ...mapGetters(['technologyList', 'languageList','jobListingLength']),
    relocation: {
      get () {
        return this.$store.state.filterSelections.relocation
      },
      set (value) {
        this.$store.dispatch('updateFilterRelocation', value)
      }
    },
    perks: {
      get() {
        return this.$store.state.filterSelections.perks
      },
      set (value) {
        this.$store.dispatch('updateFilterPerks', value)
      }
    }
  },

  mounted () {
    this.$store.dispatch('getJobs')
    // Initialize local salary from store
    this.localSalary = this.$store.state.filterSelections.salary
  },

  watch: {
    'filterSelections.languages' () {this.$store.dispatch('getJobs')},
    'filterSelections.city' () {this.$store.dispatch('getJobs')},
    'filterSelections.employment' () {this.$store.dispatch('getJobs')},
    'filterSelections.salary' () {
      // Update local salary when store changes
      this.localSalary = this.$store.state.filterSelections.salary
      this.$store.dispatch('getJobs')
    },
    //'filterSelections.perks' () {this.$store.dispatch('getJobs')},
    'filterSelections.relocation' () {this.$store.dispatch('getJobs')},
    'filterSelections.remote' () {this.$store.dispatch('getJobs')}
  },

  methods: {
    ...mapActions([
      'updateFilterSelection',
      'updateFilterLanguages',
      'updateFilterCity',
      'updateFilterEmployment',
      'updateFilterWorkload',
      'updateFilterRelocation',
      'updateFilterPerks',
      'updateFilterTeamSize',
      'updateFilterRemote',
      'updateFilterSalary',
      'clearAllFilters'
    ]),
    
    // New method to handle salary changes
    handleSalaryChange(value) {
      this.updateFilterSalary(value)
    }
  },

  data () {
    return {
      localSalary: 1000, // Default value
      //remotePossible: '',
      formResources: {
        methodologies: [
          'Issue tracking tool',
          'Knowledge repository',
          'Agile management',
          'Code reviews',
          'Pair programming',
          'Unit tests',
          'Integration tests',
          'Build server',
          'Static code analysis',
          'Version control system',
          'Testers',
          'QA Manager',
          'Freedom to choose tools',
          'One command build possible?',
          'Up and running ithin 2h?',
          'Commit on the first day?'
        ]
      },
      selectedMethodologies: [],
      //value: []
    }
  }

}
</script>
<style lang="scss" scoped>
@use '../assets/settings.scss' as settings;

.shadow {
  box-shadow: 0 4px 24px 0 rgba(37, 38, 94, 0.1);
  border: 0;
}

#inlineRadio1 {
  border: 1px solid black;
}

.custom-btn {
  background-color: #0DB3B4;
  backdrop-filter: blur(10px);
  width: 10rem;
}

@import 'vue-multiselect/dist/vue-multiselect.css';
</style>

