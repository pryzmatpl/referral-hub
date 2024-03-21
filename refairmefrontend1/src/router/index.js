import Vue from 'vue'
import Router from 'vue-router'
import store from '@/store/index.js'
/*
import Home from '@/pages/Home.vue'
import ProfileBuilder from '@/pages/ProfileBuilder.vue'
import SignUp from  '@/pages/SignUp.vue'
import SignIn from '@/pages/SignIn.vue'
import JobBuilder from '@/pages/JobBuilder.vue'
import JobDetails from '@/pages/JobDetails.vue'
import JobListing from '@/pages/JobListing'
import SearchDetail from '@/pages/SearchDetail'
*/

import Home from '@/pages/Home.vue'
import UserProfile from '@/pages/UserProfile'
import SignUp from  '@/pages/SignUp.vue'
import SignIn from '@/pages/SignIn.vue'
import JobBuilder from '@/pages/JobBuilder.vue'
import JobDetails from '@/pages/JobDetails.vue'
import JobListing from '@/pages/JobListing'
import SearchDetail from '@/pages/SearchDetail'
import SearchResults from '@/pages/SearchResults'
import SignUpConfirmation from '@/pages/SignUpConfirmation'
import PasswordRecovery from '@/pages/PasswordRecovery'
import PrivacyPolicy from '@/pages/PrivacyPolicy'
import SalarySurvey from '@/components/SalarySurvey'

/*
const Home = () => import('@/pages/Home.vue')
const UserProfile = () => import('@/pages/UserProfile')
const SignUp = () => import( '@/pages/SignUp.vue')
const SignIn = () => import('@/pages/SignIn.vue')
const JobBuilder = () => import('@/pages/JobBuilder.vue')
const JobDetails = () => import('@/pages/JobDetails.vue')
const JobListing = () => import('@/pages/JobListing')
const SearchDetail = () => import('@/pages/SearchDetail')
const SearchResults = () => import('@/pages/SearchResults')
const SignUpConfirmation = () => import('@/pages/SignUpConfirmation')
const PasswordRecovery = () => import('@/pages/PasswordRecovery')
const PrivacyPolicy = () => import('@/pages/PrivacyPolicy')
*/

const router = new Router({
  mode:'history',
  routes: [
    {
      path: '/',
      name: 'Home',
      component: Home
    },{
      path: '/salary',
      component: SalarySurvey
    },{
      path: '/search',
      component: SearchDetail
    },{
      name: 'Results',
      path: '/results',
      component: SearchResults
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
      path: '/auth/confirm',
      name: 'SignUpConfirmation',
      component: SignUpConfirmation
    },{
      path: '/auth/recovery',
      component: PasswordRecovery
    },{
      path: '/jobs',
      name: 'jobs',
      component: JobListing,
      meta: {
        requiresAuth: true
      }
    },{
      path: '/profile',
      name: 'Profile',
      component: UserProfile,
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
      path: '/job/:id',
      component: JobDetails
    },{
      path: '/privacypolicy',
      component: PrivacyPolicy
    }
    /*
    ,{
      // catches all 404 errors
      path: '*',
      redirect: '/'
    }*/
  ]
});

router.beforeEach((to,from,next) => {
  if(to.meta.requiresAuth && !store.state.isAuthenticated){
    next('/auth/signin')
  } else {
    next()
  }
})

export default router
