<template>
  <div class="container">
    <form>
      <div class="row">
        <div class="col-4">
          <router-link class="nav-link py-3 px-0 float-left" to="/"> &lt; Back </router-link>
        </div>
        <div class="col-4">
          <h2 class="mt-3 mb-4 text-center">Detail search</h2>
        </div>
        <div class="col-4">
          <a href="#" class="float-right py-3 px-0" @click="clearAllFilters">Clear all filters</a>
        </div>
      </div>
      <div class="card shadow">
        <div class="card-body">
          <h5>Basic filter</h5>
          <div class="form-row mb-2">
            <div class="form-group col-12 col-sm-6 col-md-4">
              <label>Job Category</label>
              <multiselect
                :value="filterSelections.technology"
                @input="updateFilterSelection"
                :options="technologyList"
                :preselect-first="true"
                selectLabel=""
                deselectLabel=""
              />
            </div>
            <div class="form-group col-12 col-sm-6 col-md-4">
              <label class="typo__label">Language</label>
              <multiselect
                :value="filterSelections.languages"
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
            <div class="form-group col-12 col-sm-6 col-md-4">
              <label>Location</label>
              <multiselect
                :value="filterSelections.city"
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
      <div class="card mt-3 shadow">
        <div class="card-body">
          <h5>Contract type</h5>
          <div class="form-row">
            <div class="form-group col-12 col-sm-6 col-md-4">
              <label>Employment type</label>
              <multiselect
                :value="filterSelections.employment"
                @input="updateFilterEmployment"
                :options="filterDefaults.employment"
                :searchable="false"
                :close-on-select="true"
                :show-labels="false"
                placeholder="Pick a type"
              />
            </div>
            <div class="form-group col-12 col-sm-6 col-md-4">
              <label>Workload</label>
              <multiselect
                :value="filterSelections.workload"
                @input="updateFilterWorkload"
                :options="filterDefaults.workload"
                :searchable="false"
                :close-on-select="true"
                :show-labels="false"
                placeholder="Pick a type"
              />
            </div>
            <div class="form-group col-12 col-sm-6 col-md-4">
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
                >{{ remoteOption }}</b-button>
              </b-button-group>
            </div>
            <div class="form-group col-12 col-sm-6 col-md-4">
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
            <div class="form-group col-12 col-sm-6 col-md-4">
              <label>Minimum salary (monthly/gross)</label>
              <Slider
                :min="0"
                :max="20000"
                tooltip="hover"
                :interval="1000"
                :value="filterSelections.salary"
                @callback="$store.commit('filterChange', { arg: 'salary', value: $refs.sal.getValue() })"
                ref="sal"
              />
              <p>{{ filterSelections.salary }} PLN</p>
            </div>
            <div class="form-group col-12 col-sm-6 col-md-4">
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
                >{{ teamSizeOption }}</b-button>
              </b-button-group>
            </div>
          </div>
        </div>
      </div>
      <div class="card mt-2 shadow">
        <div class="card-body">
          <h5>Other perks</h5>
          <div class="row">
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
      <div class="row justify-content-center pt-2 mb-5">
        <router-link class="btn btn-info w-50" to="/results" tag="button">
          {{ jobListingLength }} Jobs You Can Start Today
        </router-link>
      </div>
      <div>
        <h2>Specs</h2>
        <div class="form-row">
          <BaseFormGroupInput class="col-md-4" name="Travel involved" :error="true" />
          <BaseFormGroupInput class="col-md-4" name="Remote possible" v-model="remotePossible" />
          <BaseFormGroupInput class="col-md-4" name="Relocation package" />
        </div>
        <h2>Project</h2>
        <div v-for="(item, index) in formResources.methodologies" :key="index">
          <BaseFormCheckbox :name="item" v-model="selectedMethodologies" :val="item" />
        </div>
        <span>{{ selectedMethodologies }}</span>
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
  },

  watch: {
    'filterSelections.languages' () {this.$store.dispatch('getJobs')},
    'filterSelections.city' () {this.$store.dispatch('getJobs')},
    'filterSelections.employment' () {this.$store.dispatch('getJobs')},
    'filterSelections.salary' () {this.$store.dispatch('getJobs')},
    //'filterSelections.perks' () {this.$store.dispatch('getJobs')},
    'filterSelections.relocation' () {this.$store.dispatch('getJobs')}
  },

  methods: {
    ...mapActions([
      'updateFilterSelection',
      'updateFilterLanguages',
      'updateFilterCity',
      'updateFilterEmployment',
      'updateFilterWorkload',
      'clearAllFilters'
    ])
  },

  data () {
    return {
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
      //selectedMethodologies: [],
      //value: []
    }
  }

}
</script>
<style lang="sass" scoped>
  .shadow
    box-shadow: 0 4px 24px 0 rgba(37, 38, 94, 0.1)
    border: 0
</style>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
