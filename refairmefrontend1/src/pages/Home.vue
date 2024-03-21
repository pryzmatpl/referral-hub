<template lang="pug">
  .container(style='max-width:100%;')
    .search-panel
      .row.justify-content-center.pt-5
        .col-8.title.p-0.d-none.d-sm-block
          h1(style='color: white;font-size:48px') Research Engine
      .row.justify-content-center
        .card.col-8.shadow
          .card-body
            form
              .form-row.mb-2
                .form-group.col-12.col-sm-6.col-lg-3
                  label Job Category
                  multiselect(
                    :value="filterSelections.technology"
                    @input="updateFilterSelection"
                    :options="technologyList"
                    :preselect-first="true"
                    selectLabel=""
                    deselectLabel=""
                  )
                .form-group.col-12.col-sm-6.col-lg-3
                  label.typo__label Subcategory
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
                .form-group.col-12.col-sm-6.col-lg-3
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
                .form-group.col-12.col-sm-6.col-lg-3
                  label &nbsp;
                  router-link.btn.btn-danger.form-control(to="/results" tag="button") {{jobListingLength}} results
                  //button.btn.btn-info.form-control 1,315 results
              small.float-left
                router-link(to="/search") Refine search
              
    .row(style="margin: 0 auto;margin-top:-20px").justify-content-center
      .col-8.pl-0.pr-0
        .row
          .col-12.col-md-6.pl-0
            //max-width:1140px;
            .card.text-left.shadow.pointer(@click="$router.push({name: 'Profile', params: { tab: 1 }})")
              .card-body
                h2.card-title 
                  font-awesome-icon(:icon="moneyIcon")
                  |  Salary survey
                p.card-text Create profile and get free report on how your salary compares
              //.card-footer.text-muted
                | Salary survey
          //.col-12.col-md-4
            .card.text-left.shadow.pointer(@click="$router.push({name: 'Profile', params: { tab: 1 }})")
              .card-body
                h2.card-title 
                  font-awesome-icon(:icon="wrenchIcon")
                  |  Skill survey
                p.card-text Create profile see how your skills compare against your colleagues
              //.card-footer.text-muted
                | Skill survey
          .col-12.col-md-6.pr-0
            .card.text-left.shadow.pointer(@click="$router.push({name: 'Profile', params: { tab: 1 }})")
              .card-body
                h2.card-title
                  font-awesome-icon(:icon="usersIcon")
                  |  Career comparison
                p.card-text Create profile and compare your career path against your peers
              //.card-footer.text-muted
                | Career comparison
</template>
<script>
import Multiselect from 'vue-multiselect'
import {mapState, mapActions, mapGetters} from 'vuex'
import FontAwesomeIcon from '@fortawesome/vue-fontawesome'
import {
  faMoneyBillAlt,
  faWrench,
  faUsers
} from '@fortawesome/fontawesome-free-solid'

export default {
  components: {
    Multiselect,
    FontAwesomeIcon
  },

  computed: {
    ...mapState(['filterDefaults','filterSelections']),
    ...mapGetters(['technologyList','languageList','jobListingLength']),
    moneyIcon: () => faMoneyBillAlt,
    wrenchIcon: () => faWrench,
    usersIcon: () => faUsers
  },

  watch: {
    'filterSelections.languages' () {this.$store.dispatch('getJobs')},
    'filterSelections.city' () {this.$store.dispatch('getJobs')} 
  },

  mounted () {
    this.$store.dispatch('getJobs')
  },

  methods: {
    ...mapActions(['updateFilterSelection','updateFilterLanguages','updateFilterCity'])
  },

  data () {
    return {
      selectedCity: '',
      resultsNumber: 0
    }
  }
}
</script>
<style lang="sass" scoped>
  .container
    background-color: #42bff4
    background-size: 100% 100%
    max-width: 100%
    height: 500px

  .search-panel
    width: 100%
    height: 500px

  .col-4
    .card
      cursor: pointer
  
  .shadow
    box-shadow: 0 4px 24px 0 rgba(37, 38, 94, 0.1)
    border: 0
  
  .pointer
    cursor: pointer
</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
