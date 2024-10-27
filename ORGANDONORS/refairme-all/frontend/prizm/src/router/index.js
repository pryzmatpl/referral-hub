import Vue from 'vue'
import Router from 'vue-router'
import store from '../../prizm-vuex.js'

import Home from '@/pages/Home.vue'
import ProfileBuilder from '@/pages/ProfileBuilder.vue'
import SignUp from  '@/pages/SignUp.vue'
import SignIn from '@/pages/SignIn.vue'
import JobBuilder from '@/pages/JobBuilder.vue'
import JobDetails from '@/pages/JobDetails.vue'
import JobListing from '@/pages/JobListing'
import SearchDetail from '@/pages/SearchDetail'

const router = new Router({
  mode:'history',
  routes: [
    {
      path: '/',
      name: 'Home',
      component: Home

    },{
      path: '/refinesearch',
      component: SearchDetail
    },{
      path: '/auth/signup',
      name: 'SignUp',
      component: SignUp
    },{
      path: '/auth/signin',
      name: 'SignIn',
      component: SignIn
    },{
      path: '/auth/signout',
      redirect: '/auth/signin'
    },{
      path: '/jobs',
      name: 'jobs',
      component: JobListing,
      meta: {
        requiresAuth: true
      }
    },{
      path: '/profilebuild',
      name: 'profilebuild',
      component: ProfileBuilder,
      meta: {
        requiresAuth: true
      }
    },{
      path: '/job/add',
      name: 'jobadd',
      component: JobBuilder,
      meta: {
        requiresAuth: true
      }
    },{
      path: '/api/job/:id',
      component: JobDetails
    }
    /*
    ,{
      path: '*',
      redirect: '/'
    }*/
  ],
  root: process.env.BASE_URL
});

router.beforeEach((to,from,next) => {
  if(to.meta.requiresAuth && !store.state.isAuthenticated){
    next('/signin')
  } else {
    next()
  }
})

export default router
