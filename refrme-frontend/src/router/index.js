import { createRouter, createWebHistory } from 'vue-router'
// Remove direct import to avoid circular dependency
// import store from '@/store/index.js'

import Home from '@/pages/Home.vue'
import UserProfile from '@/pages/UserProfile'
import SignIn from '@/pages/SignIn.vue'
import JobBuilder from '@/pages/JobBuilder.vue'
import JobDetails from '@/pages/JobDetails.vue'
import JobListing from '@/pages/JobListing'
import SearchDetail from '@/pages/SearchDetail'
import SearchResults from '@/pages/SearchResults'
import PasswordRecovery from '@/pages/PasswordRecovery'
import PrivacyPolicy from '@/pages/PrivacyPolicy'
import SalarySurvey from '@/components/SalarySurvey'

const routes = [
  { path: '/', name: 'Home', component: Home },
  { path: '/salary', component: SalarySurvey },
  { path: '/search', component: SearchDetail },
  { name: 'Results', path: '/results', component: SearchResults },
  { path: '/auth/signin', name: 'SignIn', component: SignIn },
  { path: '/auth/signout', redirect: '/auth/signin' },
  { path: '/auth/recovery', component: PasswordRecovery },
  { path: '/jobs', name: 'jobs', component: JobListing, meta: { requiresAuth: true } },
  { path: '/profile', name: 'Profile', component: UserProfile, meta: { requiresAuth: true } },
  { path: '/job/add', name: 'jobadd', component: JobBuilder, meta: { requiresAuth: true } },
  { path: '/job/:id', component: JobDetails },
  { path: '/privacypolicy', component: PrivacyPolicy },
  { path: '/:pathMatch(.*)*', redirect: '/' }
]

const router = createRouter({
  history: createWebHistory(),
  routes: routes
})

// Use improved approach to access authentication state
router.beforeEach((to, from, next) => {
  // Get the store directly from window.__INITIAL_STATE__
  let isAuthenticated = false;
  
  try {
    // Check multiple potential sources for authentication state
    isAuthenticated = window.__INITIAL_STATE__?.isAuthenticated ||
                     window.__INITIAL_STATE__?.store?.state?.isAuthenticated ||
                     false;
    
    console.log('[DEBUG:Router] Authentication check:', isAuthenticated);
  } catch (error) {
    console.error('[DEBUG:Router] Error checking authentication:', error);
    isAuthenticated = false;
  }
  
  if (to.meta.requiresAuth && !isAuthenticated) {
    console.log('[DEBUG:Router] Redirecting to SignIn from:', to.path);
    next({ name: 'SignIn' });
  } else {
    next();
  }
})

export default router