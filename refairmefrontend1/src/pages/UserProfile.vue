<template>
  <div>
    <h2 class="mt-3 mb-4 text-center">My profile</h2>
    <div class="card">
      <div class="card-body">
        <ul class="nav nav-justified">
          <li v-for="(value, index) in tabs" class="nav-item">
            <a :class="[{active: tabs[currentTab].name === value.name}]" :key="value.name" @click="currentTab = index" class="nav-link">{{value.name}}</a>
          </li>
        </ul>
        <keep-alive>
          <component :is="currentTabComponent" @user="updateUserInput" @exp="updateExp" :exp="experience" @jobs="updateJobs" @loading="updateLoading"></component>
        </keep-alive>
      </div>
    </div>
    <h1 class="mt-3" style="color: #B0AFAB;">
      Profiled Jobs
      <font-awesome-icon v-if="isLoading" :icon="cogIcon" spin=""></font-awesome-icon>
    </h1>
    <template v-if="!jobs.message">
      <JobListItem class="mt-2" v-for="job in jobs" :job="job" :key="job.id"></JobListItem>
    </template>
  </div>
</template>
<script>
import Vue from 'vue'

import {
  faCog
} from '@fortawesome/fontawesome-free-solid'
import JobListItem from '@/components/JobListItem'
import ProfileBuilder from '@/components/UserProfileBuilder'
import ProfileExperience from '@/components/UserProfileExperience'
import ProfileReferrals from '@/components/UserProfileReferrals'
import ProfileApplied from '@/components/UserProfileJobsApplied'

export default {
  components: {
    
    JobListItem,
    ProfileBuilder,
    ProfileExperience,
    ProfileReferrals,
    ProfileApplied
  },

  computed: {
    cogIcon: () => faCog,
    email: vm => decodeURIComponent(vm.$store.state.dehashedData.EMAIL),
    currentTabComponent: vm => vm.tabs[vm.currentTab].comp
  },

  mounted () {
    if(this.$route.params.tab != undefined)
      this.currentTab = this.$route.params.tab
  },

  data () {
    return {
      jobs: [],
      userInput: {},
      experience: [],
      currentTab: 0,
      tabs: [
        {
          name: 'My profile',
          comp: 'ProfileBuilder',
        },
        {
          name: 'My experience',
          comp: 'ProfileExperience',
        },
        {
          name: 'Referrals and endorsements',
          comp: 'ProfileReferrals'
        },
        {
          name: 'Jobs I applied',
          comp: 'ProfileApplied'
        }
      ],
      isLoading: false,
      patterndatakw: {
        labels: ['Backend', 'Full Stack', 'Mobile/Embedded', 'Testing', 'Frontend', 'Dev Ops', 'Business Intelligence', 'IT Trainee', 'Project Management', 'Support', 'UX Designer', 'Business Analyst', 'Other'],
        datasets: [{
          label: 'Refair.me Profile ',
          backgroundColor: '#a84979',
          data: [0.05, 0.2, 0.1, 0.5, 0.2, 0.05, 0, 0, 0, 0, 0]
        }]
      }
    }
  },

  methods: {
    populateWeights (weights) {
      return {
        labels: ['Backend', 'Full Stack', 'Mobile/Embedded', 'Testing', 'Frontend', 'Dev Ops', 'Business Intelligence', 'IT Trainee', 'Project Management', 'Support', 'UX Designer', 'Business Analyst', 'Other'],
        datasets: [{
          label: 'Refair.me Profile ',
          backgroundColor: '#a84979',
          data: weights
        }]
      }
    },

    updateUserInput (userInput) {
      this.userInput = userInput
    },

    updateExp (exp) {
      this.experience = exp
    },

    updateJobs (jobs) {
      this.jobs = jobs
    },

    updateLoading (value) {
      this.isLoading = value
      console.log(value)
    },

    openJobDetails (jobId) {
      this.$router.push({
        path: `api/job/${jobId}`
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.h1 {
  margin-bottom: 100px;
}

tbody tr {
  cursor: pointer;
}

.card {
  box-shadow: 0 2px 6px 0 hsla(0,0%,0%,0.1);
  border: 0;
}

a {
  color: inherit;
  cursor: pointer;
}

.nav-link.active {
  border-bottom: 4px solid #42bff4;
}

.nav-justified {
  border-bottom: 0.25px solid lightgray;
}
</style>
