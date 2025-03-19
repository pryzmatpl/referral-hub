<template>
  <div>
    <div class="row" style="background:white; padding-top:26px">
      <div class="col-12 col-lg-6">
        <div class="row">
          <div class="col-4 col-sm-3">
            <!-- <img :src="job.company.logo" style="max-width: 140px"> -->
          </div>
          <div class="col-8 col-sm-9">
            <h4>{{ job.title }}</h4>
            <p>{{ job.company.name }}</p>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-6">
        <div class="row">
          <div class="col-8">
            <h4 v-if="job.fund">{{ job.fund[0] | groupZeros }} - {{ job.fund[1] | groupZeros }} {{ job.currency }}</h4>
            <p>monthly / gross</p>
          </div>
          <div v-if="job.fund" class="col" style="color: #FF0000;text-align: right" v-b-tooltip
            title="Get a reward of up to this amount if your referral is hired">
            <h4>{{ reward }} {{ job.currency }}</h4>
            <p>REWARD</p>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <a class="nav-link py-3 px-0" href="#" @click="back">&lt; Back</a>
      </div>
    </div>

    <div class="row">
      <div class="col-12 col-sm-6 d-flex pl-0">
        <div class="white shadow">
          <h4 class="mb-3 blue-font">Company info</h4>
          <div class="row">
            <div class="col-12">
              <p>{{ job.company.name }}</p>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <p>{{ job.company.description }}</p>
            </div>
            <div class="col-8">
             <!--  <p>{{ job.project.staff }}</p> -->
            </div>
          </div>

        </div>
      </div>
      <div class="col-12 col-sm-6 d-flex pr-0">
        <div class="white shadow">
          <h4 class="mb-3 blue-font">Job profile</h4>
          <div class="row">
            <div class="col-4">
              <p>Location:</p>
            </div>
            <div class="col-8">
              <p>{{ job.location }}</p>
            </div>
          </div>
          <div class="row">
            <div class="col-4">
              <p>Contract type:</p>
            </div>
            <div class="col-8">
              <p>{{ formattedContractType }}</p>
            </div>
          </div>
          <div class="row">
            <div class="col-4">
              <p>Remote required:</p>
            </div>
            <div class="col-8">
              <p>{{ job.remote == 0 ? 'No' : 'Yes' }}</p>
            </div>
          </div>
          <div class="row">
            <div class="col-4">
              <p>Relocation required:</p>
            </div>
            <div class="col-8">
              <p>{{ job.relocation == 0 ? 'No' : 'Yes' }}</p>
            </div>
          </div>
          <div class="row">
            <div class="col-4">
              <p>Relocation package:</p>
            </div>
            <div class="col-8">
              <p>{{ job.relocationPackage == 0 ? 'No' : 'Yes' }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4 shadow" style="background: white">
      <div class="col" style="padding: 24px">
        <h4 class="blue-font">Description</h4>
        <div class="row">
          <div class="col-12">
            <p>{{ job.description }}</p>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4 shadow" style="background: white">
      <div class="col" style="padding:24px">
        <h4 class="blue-font">Required skills</h4>
        <div class="row">
          <div v-for="skill in job.skills" :key="skill.name" v-b-tooltip :title="skill.exp == 3 ? '5+ years of experience' : skill.exp == 2 ? '3-5 years of experience' : skill.exp == 1 ? '1-2 years of experience' : ''" class="col-12 col-sm-6 col-lg-4 mt-3">
            <span>{{ skill.name }}</span>
            <br>
            <span v-if="skill.exp == 1" class="small">BASIC</span>
            <span v-if="skill.exp == 2" class="small">ADVANCED</span>
            <span v-if="skill.exp == 3" class="small">EXPERT</span>
            <span class="dot blue"></span>
            <span :class="[skill.exp == '3' || skill.exp == '2' ? 'blue' : '']" class="dot"></span>
            <span :class="[skill.exp == '3' ? 'blue' : '']" class="dot"></span>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4 shadow" style="background: white">
      <div class="col" style="padding: 24px">
        <h4 class="blue-font">Must have</h4>
        <div class="row">
          <div class="col-12">
            <p>{{ job.musthave }}</p>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4 shadow" style="background: white">
      <div class="col" style="padding: 24px">
        <h4 class="blue-font">Nice to have</h4>
        <div class="row">
          <div class="col-12">
            <p>{{ job.nicetohave }}</p>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4 shadow" style="background: white">
      <div class="col" style="padding: 24px">
        <h4 class="blue-font">Essentials</h4>
        <div class="row">
          <div class="col-12">
            <p>{{ job.essentials }}</p>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4 shadow" style="background: white">
      <div class="col" style="padding: 24px">
        <h4 class="blue-font">Specs</h4>
        <div class="row">
          <div class="col-12">
            <p>{{ job.specs }}</p>
          </div>
        </div>
      </div>
    </div>

<!--     <div class="row mt-4 shadow" style="background: white">
      <div class="col" style="padding:24px">
        <h4 class="blue-font">Work time division</h4>
        <div class="row">
          <div v-for="(value, key, index) in job.project.breakdown" :key="key" class="col-6 col-sm-4 col-lg-2">
            <p style="text-align: center">{{ value.label }}</p>
            <div class="GaugeMeter" :data-percent="value.value" data-append="%" data-theme="DarkBlue-LightBlue" data-style="Full" data-width="12" data-size="150px"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4 shadow" style="background:white">
      <div class="col" style="padding:24px">
        <h4 class="blue-font">Methodologies</h4>
        <div class="row">
          <div v-for="m in job.project.methodology" :key="m" class="col-12 col-sm-6 col-lg-3 mt-4">
            <font-awesome-icon :icon="yesIcon" :color="'#26A4ED'" class="fa-2x mr-2" style="vertical-align: middle"></font-awesome-icon>
            <span>{{ m }}</span>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4 shadow" style="background: white">
      <div class="col" style="padding:24px">
        <h4 class="blue-font">Other perks</h4>
        <div class="row">
          <div v-for="perk in job.project.perks" :key="perk.name" class="col-12 col-sm-6 col-lg-3 mt-4">
            <font-awesome-icon :icon="perk.available ? yesIcon : noIcon" :color="perk.available ? '#26A4ED' : 'gray'" class="fa-2x mr-2" style="vertical-align: middle"></font-awesome-icon>
            <span>{{ perk.name }}</span>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4 shadow" style="background: white">
      <div class="col-12 col-md-6" style="padding: 24px">
        <h4 class="blue-font">Why work on this project?</h4>
        <div class="row">
          <div class="col-12">
            <p>{{ job.project.description }}</p>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-6" style="padding: 24px">
        <h4 class="blue-font">What's the tech stack?</h4>
        <div class="row">
          <div class="col-12">
            <p>{{ job.project.stack }}</p>
          </div>
        </div>
      </div>
    </div> -->


    <div class="row mt-2">
      <div class="col">
        <button class="btn btn-lg btn-primary float-right w-100" @click="apply">Apply</button>
      </div>
      <div class="col">
        <!-- <a :href="`mailto:recruit@techsorted.com?subject=Job nr: ${job.id}&body=Hello!%0A%0AYou are applying for postion: ${job.title}%0Ain company: ${job.company.name}%0A%0APlease attach CV and fill in details below:%0A-when best to call you:%0A-what's your notice period:%0A-what salary are you interested in:%0A-best method of contact: (email/sms)%0A%0AWe'll reply soon!%0A%0AThanks,%0ARefair.me team`">
          <button class="btn btn-lg btn-primary float-right w-100">Apply with CV</button>
        </a> -->
      </div>
      <div class="col">
        <button type="button" class="btn btn-primary btn-lg w-100" data-bs-toggle="modal" data-bs-target="#referModal">
          Refer a Friend
        </button>

        <!-- Modal -->
        <div class="modal fade" id="referModal" tabindex="-1" aria-labelledby="referModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="referModalLabel">Refer a Friend</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <p>For this referral you can earn at least {{ reward }} {{ job?.currency }}</p>
                <input type="email" @input="updateReferralEmail" placeholder="Your friend's email">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-success float-right" @click="sendReferral">Send</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row mt-1 mb-5">
      <div class="col">
        <a class="nav-link py-3 px-0" href="#" @click="back">&lt; Back</a>
      </div>
    </div>

    <b-modal id="editModal" v-if="modalShow" title="Send your CV" size="lg" hide-footer>
      <p>Please send your CV to: <a href="mailto: w@refair.me">refair@refair.me</a></p>
    </b-modal>

    <b-modal id="referModal" v-model="referModalShow" title="Refer a friend" size="lg" hide-footer>
      <p>For this referral you can earn at least {{ reward }} PLN</p>
      <input class="form-control" v-model="referralEmail" type="email" placeholder="your friend's email">
      <b-btn class="btn-success float-right mt-2" @click="sendReferral">Send</b-btn>
    </b-modal>
  </div>
</template>

<script>
import gauge from '../GaugeMeter.js'

import { toRaw } from 'vue';

import {
  faCheckCircle,
  faTimesCircle
} from '@fortawesome/fontawesome-free-solid'

export default {
  props: ['jobId'],

  components: {
   
  },

  mounted () {
    // https://github.com/Mictronics/GaugeMeter
    let id = this.jobId > 0 ? this.jobId : this.$route.params.id

    this.$store.state.backend
      .get(`/getjobs?id=${id}&with=company`)
      .then(ret => {
        this.job = ret.data
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
    /* filteredPerks: function(){
      let b = this.job.project.perks.map(obj => obj.name)
      return this.perks.map(
        item => b.includes(item.name) 
        ? {name: item.name, available: true} 
        : {name: item.name, available: false}
      )
    }, */
    reward: vm => vm.job.fund?.[0] * 0.25
  },

  watch: {
    'job.contractType': function (newValue) {
      const formatted = toRaw(newValue).join(', ')
      this.formattedContractType = formatted
    }
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
      job: {
        company: {}
      },
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
     /* let total = this.job.project.breakdown.reduce((prevValue, currValue) => prevValue + currValue.value, 0)
      this.job.project.breakdown.map(obj => obj.value = Math.round((obj.value / total) * 100 ))
    */
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

    updateReferralEmail(event) {
      this.referralEmail = event.target.value;
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
        email: this.referralEmail,
        job_id: this.job.id
      })
      .then(ret => alert(ret.data.message))
      .then(ret => this.referModalShow = false)
      .catch(err => alert('Something went wrong :/'))
    },

    back () {
      this.$router.go(-1)
    }
  }
}
</script>
<style lang="scss" scoped>
@import '@/assets/settings.scss';

.white {
  background: white;
  padding: 30px;
  width: 100%;
}

* {
  color: black;
}

.dot {
  height: 10px;
  width: 10px;
  margin: 0 5px;
  background-color: #bbb;
  border-radius: 50%;
  display: inline-block;
}

.blue {
  background-color: $primaryColor;
}

.blue-font {
  color: $primaryColor;
}

.font-awesome-icon {
  margin-right: 10px;
}

.shadow {
  box-shadow: 0 4px 24px 0 rgba(37, 38, 94, 0.1);
  border: 0;
}

// Add the styles from the second block here
.GaugeMeter {
  position: relative;
  text-align: center;
  overflow: hidden;
  cursor: default;

  span,
  b {
    top: 23%;
    font-size: 45px;
    width: 100%;
    left: 0%;
    position: absolute;
    text-align: center;
    display: inline-block;
    color: rgba(0, 0, 0, 0.8);
    font-weight: 400;
    font-family: "Open Sans", Arial;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
  }
}
</style>


