<template lang="pug">
  .container
    form
      .row
        .col-4
          router-link.nav-link.py-3.px-0(to='/').float-left < Back
        .col-4
          h2.mt-3.mb-4.text-center Detail search
        .col-4
          a.float-right.py-3.px-0(href="#" @click="clearAllFilters") Clear all filters
      .card.shadow
        .card-body
          h5 Basic filter
          .form-row.mb-2
            .form-group.col-12.col-sm-6.col-md-4
              label Job Category
              multiselect(
                :value="filterSelections.technology"
                @input="updateFilterSelection"
                :options="technologyList"
                :preselect-first="true"
                selectLabel=""
                deselectLabel=""
              )
            .form-group.col-12.col-sm-6.col-md-4
              label.typo__label Language
              multiselect(
                :value="filterSelections.languages"
                @input="updateFilterLanguages"
                :options="languageList",
                :multiple="true",
                :searchable="false",
                :close-on-select="false",
                :clear-on-select="false",
                :hide-selected="true",
                :preserve-search="true",
                placeholder="Pick some"
                label="language",
                track-by="language",
                :preselect-first="false"
                selectLabel=""
                deselectLabel=""
              )
                template(slot="tag", slot-scope="props")
                  span.custom__tag
                    span {{ props.option.language }}
                    span.custom__remove(@click="props.remove(props.option)") ❌
            .form-group.col-12.col-sm-6.col-md-4
              label Location
              multiselect(
                :value="filterSelections.city"
                @input="updateFilterCity"
                :options="filterDefaults.cities",
                :searchable="false",
                :close-on-select="true",
                :show-labels="false"
                placeholder="Pick a city"
              )
      .card.mt-3.shadow
        .card-body
          h5 Contract type
          .form-row
            .form-group.col-12.col-sm-6.col-md-4
              label Employment type
              multiselect(
                :value="filterSelections.employment"
                @input="updateFilterEmployment"
                :options="filterDefaults.employment"
                :searchable="false",
                :close-on-select="true",
                :show-labels="false"
                placeholder="Pick a type"
              )
            //.form-group.col-12.col-sm-6.col-md-3
              label Workload
              multiselect(
                :value="filterSelections.workload"
                @input="updateFilterWorkload"
                :options="filterDefaults.workload"
                :searchable="false",
                :close-on-select="true",
                :show-labels="false"
                placeholder="Pick a type"
              )
            //.form-group.col-12.col-sm-6.col-md-3
              label Remote
              b-button-group.w-100
                b-button.w-100(
                  v-for="remoteOption in ['Yes','Partially','No']"
                  :key="remoteOption"
                  type="button"
                  variant="outline-secondary"
                  @click="$store.commit('filterChange', {arg: 'remote', value: remoteOption})"
                  :class="{active: $store.getters.filterSelections.remote === remoteOption}"
                ) {{remoteOption}}
            .form-group.col-12.col-sm-6.col-md-4
              label Relocation package
              div.ml-1
                .form-check.form-check-inline(v-for="option in [{name: 'Yes', value: 1},{name: 'No', value: 0}]")
                  input#inlineRadio1.form-check-input(
                    v-model="relocation", :value='option.value', 
                    type='radio', name='inlineRadioOptions' 
                  )
                  label.form-check-label(for='inlineRadio1') {{option.name}}
            .form-group.col-12.col-sm-6.col-md-4
              label Minimum salary (monthly/gross)
              Slider(
                :min='0', :max='20000', tooltip='hover', :interval='1000',:value="filterSelections.salary",
                @callback="$store.commit('filterChange', {arg: 'salary', value: $refs['sal'].getValue()})", ref='sal'
              )
              p {{filterSelections.salary}} PLN
          //.form-row
            .form-group.col-12.col-sm-6.col-md-3
              label Project team size
              b-button-group.w-100
                b-button.w-100(
                  v-for="teamSizeOption in ['<10','<50','100+']"
                  :key="teamSizeOption"
                  type='button'
                  variant="outline-secondary"
                  @click="$store.commit('filterChange', {arg: 'teamSize', value: teamSizeOption})"
                  :class="{active: $store.getters.filterSelections.teamSize === teamSizeOption}"
                ) {{teamSizeOption}}
            
      //.card.mt-2.shadow
        .card-body
          h5 Other perks
          .row
            .form-check.col-12.col-sm-6.col-md-3.mb-1(v-for="perk in filterDefaults.perks")
              input.form-check-input(
                type='checkbox', :value='perk', v-model="perks"
                :id='perk', :key='perk'
              )
              label.form-check-label(:for='perk')
                | {{perk}}
      .row.justify-content-center.pt-2.mb-5
        router-link.btn.btn-info.w-50(to="/results" tag="button") {{jobListingLength}} Jobs You Can Start Today

      //h2 Specs
      //.form-row
        // testing base components
        BaseFormGroupInput.col-md-4(name="Travel involved" error=true)
        BaseFormGroupInput.col-md-4(name="Remote possible" v-model="remotePossible")
        BaseFormGroupInput.col-md-4(name="Relocation package")
      //h2 Project
      //template(v-for="(item,index) in formResources.methodologies")
        BaseFormCheckbox(:name="item" v-model="selectedMethodologies", :val="item")
      //span {{selectedMethodologies}}
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
