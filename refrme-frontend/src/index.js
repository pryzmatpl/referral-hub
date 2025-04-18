import { createApp } from "vue";
// Import process in a way that's compatible with Webpack 5
import process from 'process';

console.log('[DEBUG] Starting app initialization');
window.process = process;

// Import core Vue plugins first
import { createRouter, createWebHistory } from "vue-router";
import { createStore } from 'vuex';
import vue3GoogleLogin from 'vue3-google-login'
import { createModal } from '@kolirt/vue-modal'

// Import router and store modules
console.log('[DEBUG] Importing router and store');
import router from "@/router/index.js";
import store from "@/store/index.js";

// Make store available globally for components that need it
console.log('[DEBUG] Making store available globally');
try {
  // Create a reactive proxy to keep global state in sync with store changes
  window.__INITIAL_STATE__ = {
    isAuthenticated: store?.state?.isAuthenticated || false,
    store: store // Add the actual store reference for easier access
  };
  
  // Add a watcher to update the global state when authentication changes
  if (store) {
    store.watch(
      state => state.isAuthenticated,
      (newValue) => {
        console.log('[DEBUG] Authentication state changed:', newValue);
        window.__INITIAL_STATE__.isAuthenticated = newValue;
      }
    );
  }
} catch (error) {
  console.error('[DEBUG] Error setting up global state:', error);
  window.__INITIAL_STATE__ = { isAuthenticated: false };
}

console.log('[DEBUG] Importing FontAwesome components');
import { library } from '@fortawesome/fontawesome-svg-core' 
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { 
  faCheckCircle, 
  faTimesCircle,
  faTags,
  faCog,
  faEdit,
  faTrash,
  faEye,
  faEyeSlash, 
  faMoneyBillAlt
} from '@fortawesome/free-solid-svg-icons'

// Register only the specific icons we need - more efficient than importing all
library.add(
  faCheckCircle,
  faTimesCircle,
  faTags,
  faCog,
  faEdit,
  faTrash,
  faEye,
  faEyeSlash,
  faMoneyBillAlt
)

console.log('[DEBUG] Importing BootstrapVue3');
import BootstrapVue3 from 'bootstrap-vue-3'

// import function to register Swiper custom elements
console.log('[DEBUG] Registering Swiper');
import { register } from 'swiper/element/bundle';
// register Swiper custom elements
register();

console.log('[DEBUG] Importing App component');
// import VueAnalytics from 'vue-analytics'
import App from "./App";

// Safely determine environment
const isProd = () => {
  try {
    return process.env.NODE_ENV === 'production';
  } catch (error) {
    console.error('[DEBUG] Error determining environment:', error);
    return false;
  }
};

console.log('[DEBUG] Creating Vue app instance');
const app = createApp(App);

// Configure Vue to recognize Swiper custom elements
app.config.compilerOptions.isCustomElement = tag => {
  return tag.startsWith('swiper-')
};

// Add global error handler for debugging
app.config.errorHandler = (err, vm, info) => {
  console.error('[VUE ERROR]', err);
  console.error('[VUE ERROR] Info:', info);
  console.error('[VUE ERROR] Component:', vm);
};

// Set production mode configuration
console.log('[DEBUG] Configuring app for environment:', isProd() ? 'production' : 'development');
if (isProd()) {
  app.config.devtools = false;
  app.config.performance = false;
  app.config.productionTip = false;
} else {
  app.config.devtools = true;
  app.config.performance = true;
  app.config.productionTip = false;
}

// Register plugins in a specific order to avoid dependencies issues
console.log('[DEBUG] Registering BootstrapVue3');
try {
  app.use(BootstrapVue3);
  console.log('[DEBUG] BootstrapVue3 registered successfully');
} catch (error) {
  console.error('[DEBUG] Error registering BootstrapVue3:', error);
}

// Register router BEFORE store to prevent circular dependency issues
console.log('[DEBUG] Registering router');
try {
  app.use(router);
  console.log('[DEBUG] Router registered successfully');
} catch (error) {
  console.error('[DEBUG] Error registering router:', error);
}

// Register store AFTER router 
console.log('[DEBUG] Registering store');
try {
  app.use(store);
  console.log('[DEBUG] Store registered successfully');
} catch (error) {
  console.error('[DEBUG] Error registering store:', error);
}

// Only provide the store once
console.log('[DEBUG] Setting up store for provide/inject');
try {
  // Check if the store is already provided to avoid duplicate provide warnings
  app.provide('store', store);
  console.log('[DEBUG] Store provided successfully');
} catch (error) {
  console.error('[DEBUG] Error providing store:', error);
}

console.log('[DEBUG] Registering Google Login');
try {
  app.use(vue3GoogleLogin, {
    clientId: '254777876915-4lgmqe9diaebsjchptmdrt2edlqo3tn8.apps.googleusercontent.com',
    buttonConfig: {
      size: "large",
      shape: "pill", 
      width: "300px"
    }
  });
  console.log('[DEBUG] Google Login registered successfully');
} catch (error) {
  console.error('[DEBUG] Error registering Google Login:', error);
}

console.log('[DEBUG] Registering Modal');
try {
  app.use(createModal({
    transitionTime: 200,
    animationType: 'slideDown',
    modalStyle: {
      padding: '5rem 2rem',
      align: 'center',
      'z-index': 201
    },
    overlayStyle: {
      'background-color': 'rgba(0, 0, 0, .5)',
      'backdrop-filter': 'blur(5px)',
      'z-index': 200
    }
  }));
  console.log('[DEBUG] Modal registered successfully');
} catch (error) {
  console.error('[DEBUG] Error registering Modal:', error);
}

console.log('[DEBUG] Registering FontAwesome component');
try {
  app.component('font-awesome-icon', FontAwesomeIcon);
  console.log('[DEBUG] FontAwesome component registered successfully');
} catch (error) {
  console.error('[DEBUG] Error registering FontAwesome component:', error);
}

// Mount the app to the DOM
console.log('[DEBUG] Mounting app to #app element');
try {
  // In dev mode, mount immediately without setTimeout which can cause issues
  app.mount("#app");
  console.log('[DEBUG] App mounted successfully');
} catch (error) {
  console.error('[DEBUG] Error mounting app:', error);
}
