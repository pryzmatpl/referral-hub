<template lang="pug">
  .container
    a.nav-link.py-3.px-0(href="#" @click="back") < Back
    .row
      .col
        h2(style="display: inline-block") Search results
        a.float-right(href="#" @click="clearAllFilters") Clear filters (show all jobs)
    .row
      .col-3.d-none.d-lg-block
        .card.shadow
          .card-body
            h3 Filter by
            hr
            .row
              .col
                .form-group
                  label Job Category
                  multiselect(
                    :value="filterSelections.technology"
                    @input="updateFilterSelection"
                    :options="technologyList"
                    :preselect-first="true"
                    selectLabel=""
                    deselectLabel=""
                  )
                .form-group
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
            .form-group
              label Contract type
              multiselect(
                :value="filterSelections.employment"
                @input="updateFilterEmployment"
                :options="filterDefaults.employment"
                :searchable="false",
                :close-on-select="true",
                :show-labels="false"
                placeholder="Pick a type"
              )
            //.form-group
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
            //.form-group
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
            //.form-group
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
            .form-group
              label Minimum salary (monthly/gross)
              Slider(
                :min='0', :max='50000', tooltip='hover', :interval='1000',:value="filterSelections.salary",
                @callback="$store.commit('filterChange', {arg: 'salary', value: $refs['sal'].getValue()})", ref='sal'
              )
              p {{filterSelections.salary}} PLN
            .form-group
              label Relocation package
              div
                .form-check.form-check-inline(v-for="option in [{name: 'Yes', value: 1},{name: 'No', value: 0}]")
                  input#inlineRadio1.form-check-input(
                    v-model="relocation", :value='option.value', 
                    type='radio', name='inlineRadioOptions' 
                  )
                  label.form-check-label(for='inlineRadio1') {{option.name}}
            hr
            h5 Other perks
              span.float-right >
      .col-12.col-lg-9
        .card.mb-2.shadow
          .card-body
            h5 {{jobListingLength}} results for your search
            p {{languagesSelected.length == 0 ? 'No filters selected' : languagesSelected}}
        JobListItem(
          v-for="job in jobListing"
          :job="job"
          :key="job.id"
        )
        nav(aria-label='Page navigation example')
          ul.pagination.justify-content-center
            li.page-item(v-if="currentPage > 0")
              a.page-link(href='#', aria-label='Previous' @click="updateCurrentPage(currentPage - 1)")
                span(aria-hidden='true') «
                span.sr-only Previous
            li.page-item(v-for="o in resultPages")
              a.page-link(href='#' @click="updateCurrentPage(o - 1)") {{o}}
            li.page-item(v-if="currentPage < (resultPages - 1)")
              a.page-link(href='#', aria-label='Next' @click="updateCurrentPage(currentPage - 1)")
                span(aria-hidden='true') »
                span.sr-only Next

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
<style lang="sass" scoped>
  .btn.active
    z-index: 0 // without that gets in front of vue-multiselect
  .shadow
    box-shadow: 0 4px 24px 0 rgba(37, 38, 94, 0.1)
    border: 0
</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

