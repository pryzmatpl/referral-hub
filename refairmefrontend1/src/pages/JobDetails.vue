<template lang="pug">
  div
    .row(style="background:white; padding-top:26px")
      .col-12.col-lg-6
        .row
          .col-4.col-sm-3
            img(:src="job.company.logo" style="max-width: 140px")
          .col-8.col-sm-9
            h4 {{job.title}}
            p {{job.company.name}}
      .col-12.col-lg-6
        .row
          .col-5
            h4(v-if="job.fund") {{job.fund[0] | groupZeros}} - {{job.fund[1] | groupZeros}} PLN
            p monthly / gross
          .col(style="color: #FF0000;text-align: right" v-b-tooltip title="Get a reward of up to this amount if your referral is hired")
            h4(v-if="job.fund") {{job.fund[0] * 0.25 | groupZeros}} PLN
            p REWARD

    .row
      .col
        a.nav-link.py-3.px-0(href="#" @click="back") < Back

    .row
      .col-12.col-sm-6.d-flex.pl-0
        .white.shadow
          h4.mb-3.blue-font Project info
          .row
            .col-4
              p Project type:
            .col-8l
              p 
          .row
            .col-4
              p Project team size:
            .col-8
              p {{job.project.staff}}
          .row
            .col-4
              p Stage:
            .col-8
              p {{job.project.stage}}
          .row
            .col-4
              p Travel involved:
            .col-8
              b-progress(:max="100", show-value)
                b-progress-bar.blue(:value="job.travelPercentage") {{job.travelPercentage}}%
          .row
            .col-4
              p Remote possible:
            .col-8
              b-progress(:max="100", show-value)
                b-progress-bar.blue(:value="job.remotePercentage") {{job.remotePercentage}}%

      .col-12.col-sm-6.d-flex.pr-0
        .white.shadow
          h4.mb-3.blue-font Job profile
          .row
            .col-4
              p Location:
            .col-8
              p {{job.location}}
          .row
            .col-4
              p Contract type:
            .col-8
              p {{formattedContractType}}
          .row
            .col-4
              p Workload:
            .col-8
              p {{job.project.workload}}
          .row
            .col-4
              p Remote required:
            .col-8
              p {{job.remote == 0 ? 'No' : 'Yes'}}
          .row
            .col-4
              p Relocation required:
            .col-8
              p {{job.relocation == 0 ? 'No' : 'Yes'}}
          .row
            .col-4
              p Relocation package:
            .col-8
              p {{job.relocationPackage == 0 ? 'No' : 'Yes'}}

    .row.mt-4(style="background: white").shadow
      .col(style="padding:24px")
        h4.blue-font Required skills
        .row
          .col-12.col-sm-6.col-lg-4(v-for="skill in job.skills", :key="skill.name"
              v-b-tooltip
              :title="skill.exp == 3 ? '5+ years of experience' : skill.exp == 2 ? '3-5 years of experience' : skill.exp == 1 ? '1-2 years of experience' : ''"
            ).mt-3
            span {{skill.name}}
            br
            span.small(v-if="skill.exp == 1") BASIC
            span.small(v-if="skill.exp == 2") ADVANCED
            span.small(v-if="skill.exp == 3") EXPERT
            span.dot.blue
            span.dot(:class="[skill.exp == '3' || skill.exp == '2' ? 'blue' : '']")
            span.dot(:class="[skill.exp == '3' ? 'blue' : '']")

    .row.mt-4(style="background: white").shadow
      .col(style="padding:24px")
        h4.blue-font Work time division
        .row
          .col-6.col-sm-4.col-lg-2(v-for="(value, key, index) in job.project.breakdown", :key="key")
            p(style="text-align: center") {{value.label}}
            .GaugeMeter#GaugeMeter_1(
              :data-percent="value.value"
              data-append="%"
              data-theme="DarkBlue-LightBlue"
              data-style="Full"
              data-width=12
              data-size="150px"
            )

    .row.mt-4(style="background:white").shadow
      .col(style="padding:24px")
        h4.blue-font Methodologies
        .row
          .col-12.col-sm-6.col-lg-3.mt-4(v-for="m in job.project.methodology", :key="m")
            font-awesome-icon.fa-2x.mr-2(
              :icon="yesIcon"
              :color="'#26A4ED'"
              style="vertical-align: middle"
            )
            span {{m}}

    .row.mt-4(style="background: white").shadow
      .col(style="padding:24px")
        h4.blue-font Other perks
        .row
          .col-12.col-sm-6.col-lg-3.mt-4(v-for="perk in job.project.perks", :key="perk.name")
            //font-awesome-icon.fa-2x.mr-2(
              :icon="perk.available ? yesIcon : noIcon"
              :color="[perk.available ? '#26A4ED' : 'gray']"
              style="vertical-align: middle"
              )
            font-awesome-icon.fa-2x.mr-2(
              :icon="yesIcon"
              :color="'#26A4ED'"
              style="vertical-align: middle"
            )
            span {{perk.name}}

    .row.mt-4(style="background: white").shadow
      .col-12.col-md-6(style="padding: 24px")
        h4.blue-font Why work on this project?
        .row
          .col-12
            p {{job.project.description}}
      .col-12.col-md-6(style="padding: 24px")
        h4.blue-font What's the tech stack?
        .row
          .col-12
            p {{job.project.stack}}

    .row.mt-4(style="background: white").shadow
      .col(style="padding: 24px")
        h4.blue-font Description
        .row
          .col-12
            p {{job.description}}

    .row.mt-2
      .col
        button.btn.btn-lg.btn-primary.float-right.w-100(@click="apply") Apply
      .col
        a(:href="`mailto:recruit@techsorted.com?subject=Job nr: ${job.id}&body=Hello!%0A%0AYou are applying for postion: ${job.title}%0Ain company: ${job.company.name}%0A%0APlease attach CV and fill in details below:%0A-when best to call you:%0A-what's your notice period:%0A-what salary are you interested in:%0A-best method of contact: (email/sms)%0A%0AWe'll reply soon!%0A%0AThanks,%0ARefair.me team`")
          button.btn.btn-lg.btn-primary.float-right.w-100 Apply with CV
      .col
        button.btn.btn-lg.btn-primary.w-100(@click="showReferralModal") Refer
    .row.mt-1.mb-5
      .col
        a.nav-link.py-3.px-0(href="#" @click="back") < Back
        
    b-modal#editModal(
      v-model="modalShow"
      title="Send your CV"
      size="lg"
      hide-footer
    )
      p Please send your CV to: 
        a(href="mailto: w@refair.me") refair@refair.me
        
    b-modal#referModal(
      v-model="referModalShow"
      title="Refer a friend"
      size="lg"
      hide-footer
    )
      p For this referral you can earn at least {{reward}} PLN
      input.form-control(v-model="referralEmail" type="email" placeholder="your friend's email")
      b-btn.btn-success.float-right.mt-2(@click="sendReferral") Send
</template>
<script>
import gauge from '../GaugeMeter.js'
import FontAwesomeIcon from '@fortawesome/vue-fontawesome'
import {
  faCheckCircle,
  faTimesCircle
} from '@fortawesome/fontawesome-free-solid'

export default {
  props: ['jobId'],

  components: {
    FontAwesomeIcon
  },

  mounted () {
    // https://github.com/Mictronics/GaugeMeter
    let id = this.jobId > 0 ? this.jobId : this.$route.params.id

    this.$store.state.backend
      .get(`/getjobs?id=${id}&with=company,project`)
      .then(ret => {
        this.job = ret.data.data[0]
        this.evenPercentagesOut()
        $(document).ready(function(){
          $(".GaugeMeter").gaugeMeter();
        })
        console.log(ret)
      })
      .catch(error => console.log("Error (mounted):",error))
  },

  computed: {
    yesIcon: () => faCheckCircle,
    noIcon: () => faTimesCircle,
    formattedContractType: vm => vm.job.contractType.join(', '),
    filteredPerks: function(){
      let b = this.job.project.perks.map(obj => obj.name)
      return this.perks.map(
        item => b.includes(item.name) 
        ? {name: item.name, available: true} 
        : {name: item.name, available: false}
      )
    },
    reward: vm => vm.job.salaryMin * 0.25
  },

  filters: {
    groupZeros: function(value) {
      return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ")
    }
  },

  data () {
    return {
      modalShow: false,
      referModalShow: false,
      referralEmail: '',
      job: {},
      project: {
        breakdown: [],
        perks:[],
        contractType: '',
        staff: 0,
        workload: ''
      },
      perks: [
        {name: 'Free beverages', available: true},
        {name: 'Free snacks', available: false},
        {name: 'Free lunch', available: false},
        {name: 'Kitchen/canteen', available: true},
        {name: 'In-house trainings', available: true},
        {name: 'Training budget', available: true},
        {name: 'Office gym', available: false},
        {name: 'Shower', available: false},
        {name: 'Sports subscription', available: true},
        {name: 'Bike parking', available: false},
        {name: 'Car parking', available: true},
        {name: 'In-house hack-days', available: false},
        {name: 'Team events', available: false},
        {name: 'Play Room', available: true},
        {name: 'Private health care', available: true},
        {name: 'Kindergarten', available: false}
      ],
      
      /*breakdown: [
        {name: 'New functionalities', percent: 55},
        {name: 'Bug fixing', percent: 5},
        {name: 'Self-development', percent: 5},
        {name: 'Meetings', percent: 45},
        {name: 'Client support', percent: 25},
        {name: 'Documentation', percent: 25}
      ]*/
    }
  },

  methods: {
    evenPercentagesOut () {
      let total = this.job.project.breakdown.reduce((prevValue, currValue) => prevValue + currValue.value, 0)
      this.job.project.breakdown.map(obj => obj.value = Math.round((obj.value / total) * 100 ))
    },

    apply () {
      if(this.$store.state.isAuthenticated){
        this.loading = true;
        const id = this.$route.params.id;
        this.$store.state.backend.post('/api/apply', {
          job_id: id,
          email: this.$store.state.dehashedData.EMAIL
        })
        .then(ret => this.applyResponse = ret.data)
        .then(ret => console.log('applied'))
        .then(ret => alert('Successfully applied! Please wait for email from us.'))
        .catch(err => alert('Something went wrong. Please contact with us directly'))
        .finally(() => this.loading = false)
      } else {
        alert('Please sign in to apply')
        this.$router.push({path: '/auth/signin', query: {job: this.job.id}})
      }
    },

    showReferralModal () {
      if(this.$store.state.isAuthenticated){
        this.referModalShow = true
      } else {
        this.$router.push({path: '/auth/signin', query: {job: this.job.id}})
      }
    },

    sendReferral () {
      this.$store.state.backend.post('/referral/add',{
        name: '', //not required
        email: this.refferalEmail,
        job_id: this.job.id
      })
      .then(ret => alert('Referral sent! Thanks!'))
      .then(ret => this.referModalShow = false)
      .catch(err => alert('Something went wrong :/'))
    },

    back () {
      this.$router.go(-1)
    }
  }
}
</script>
<style lang="sass" scoped>
  @import '@/assets/settings.sass'
  
  .white
    background: white
    padding: 30px
    width: 100%
  .dot
    height: 10px
    width: 10px
    margin: 0 5px
    background-color: #bbb
    border-radius: 50%
    display: inline-block

  .blue
    background-color: $primaryColor

  .blue-font
    color: $primaryColor
  
  .font-awesome-icon
    margin-right: 10px
  
  .shadow
    box-shadow: 0 4px 24px 0 rgba(37, 38, 94, 0.1)
    border: 0

</style>
<style>
.GaugeMeter{
	Position:        Relative;
	Text-Align:      Center;
	Overflow:        Hidden;
	Cursor:          Default;
}

.GaugeMeter SPAN,
    .GaugeMeter B{
      top: 23%;
      font-size: 45px;
      Width: 100%;
      left: 0%;
      Position: Absolute;
      Text-align: Center;
      Display: Inline-Block;
      Color: RGBa(0,0,0,.8);
      Font-Weight: 400;
      Font-Family: "Open Sans", Arial;
      Overflow: Hidden;
      White-Space: NoWrap;
      Text-Overflow: Ellipsis;
}
.GaugeMeter[data-style="Semi"] B{
	Margin:          0 10%;
	Width:           80%;
}

.GaugeMeter S,
    .GaugeMeter U{
    	Text-Decoration: None;
    	Font-Size:       .5em;
    	Opacity:         .5;
}

.GaugeMeter B{
	Color:           Black;
	Font-Weight:     300;
	Font-Size:       .5em;
	Opacity:         .8;
}
</style>

