<template>
  <div>
    <h2 class="mt-3 mb-4 text-center">So you think you can create an competitive offer?</h2>
    <div class="card">
      <div class="card-body">
        <ul class="nav nav-justified">
          <li v-for="(value, index) in tabs" :key="value.name" class="nav-item">
            <a
              :class="{ active: tabs[currentTab].name === value.name }"
              @click="currentTab = index"
              class="nav-link"
            >
              {{ value.name }}
            </a>
          </li>
        </ul>
        <transition name="fade">
          <keep-alive>
            <component
              :is="currentTabComponent"
              @job="updateJob"
              @add-job="addJob"
              :companyId="jobBuilder.company.id"
              :projectId="jobBuilder.project.id"
              class="tab mt-4"
            ></component>
          </keep-alive>
        </transition>
      </div>
    </div>
  </div>
</template>

<script>

import JobBuilderCompanyProfile from '@/components/JobBuilderCompanyProfile'
import JobBuilderAboutProject from '@/components/JobBuilderAboutProject'
import JobBuilderAboutJob from '@/components/JobBuilderAboutJob'
import JobBuilderConfirmation from '@/components/JobBuilderConfirmation'

export default{
  components:{
    JobBuilderCompanyProfile,
    JobBuilderAboutProject,
    JobBuilderAboutJob,
    JobBuilderConfirmation
  },

  computed: {
    currentTabComponent: vm => vm.tabs[vm.currentTab].comp
  },

  watch: {
    currentTab: function(newValue, oldValue) {
      const isNextTab = (tab, nextTab) => oldValue == tab && newValue >= nextTab
      const isCompanyIdEmpty = () => this.jobBuilder.company.id == ''
      const isProjectIdEmpty = () => this.jobBuilder.project.id == ''

      if(isNextTab(0,1) && isCompanyIdEmpty()
        || isNextTab(1,2) && isProjectIdEmpty()){
        this.currentTab = oldValue
        alert('Please fill the form and click Next button')
      }
    }
  },

  data () {
    return {
      currentTab: 0,
      tabs: [
        {
          name: 'Create a company profile',
          comp: 'JobBuilderCompanyProfile',
        },
        {
          name: 'Describe the project',
          comp: 'JobBuilderAboutProject'
        },
        {
          name:'Present the job',
          comp: 'JobBuilderAboutJob'
        },
        {
          name: 'Submit',
          comp: 'JobBuilderConfirmation'
        }
      ],
      jobBuilder: {
        company: {
          id: ''
        },
        project: {
          id: ''
        },
        job: {}
      }
    }
  },

  methods: {
    updateJob (object, source) {
      this.jobBuilder[source] = object
      this.currentTab += 1
    },

    selectActive (event) {
      const siblings = $(event.target).parent().siblings().children('a')
      $(event.target).addClass('active')
      siblings.removeClass('active')
    },

    addJob (event) {
      console.log('adding jobs')

      this.$store.state.backend
      .post('/job/add', this.jobBuilder.job)
      .then(ret => {
        alert('Job added!');
        console.log(ret);
        // set defaults
        this.currentTab = 0
        this.jobBuilder = {
          company: {
            id: ''
          },
          project: {
            id: ''
          }
        }
      })
      .catch(error => alert(error.message))
    }
  }
}
</script>
<style lang="scss">
.card {
  box-shadow: 0 2px 6px 0 hsla(0,0%,0%,0.1);
  border: 0;
}

a {
  color: inherit;
  cursor: pointer;
}

.nav-link.active {
  border-bottom: 4px solid #28a745;
}

.nav-justified {
  border-bottom: 0.25px solid lightgray;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity .1s;
}

.fade-enter, .fade-leave-to {
  opacity: 0;
}

@import 'vue-multiselect/dist/vue-multiselect.css';
</style>

