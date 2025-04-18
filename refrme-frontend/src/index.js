import { createApp } from "vue";
import { createRouter, createWebHistory } from "vue-router";
import router from "@/router/index.js";
import store from "@/store/index.js";
import process from 'process';

console.log('[DEBUG] Starting app initialization');
window.process = process;

import vue3GoogleLogin from 'vue3-google-login'
import { createModal } from '@kolirt/vue-modal'

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
const isProd = process.env.NODE_ENV === "production";

console.log('[DEBUG] Creating Vue app instance');
const app = createApp(App);

// Add global error handler for debugging
app.config.errorHandler = (err, vm, info) => {
  console.error('[VUE ERROR]', err);
  console.error('[VUE ERROR] Info:', info);
  console.error('[VUE ERROR] Component:', vm);
};

// Set production mode configuration
console.log('[DEBUG] Configuring app for environment:', isProd ? 'production' : 'development');
if (isProd) {
  app.config.devtools = false;
  app.config.performance = false;
  app.config.productionTip = false;
} else {
  app.config.devtools = true;
  app.config.performance = true;
  app.config.productionTip = false;
}

// Provide store for injection
console.log('[DEBUG] Setting up store for provide/inject');
try {
  app.provide('store', store);
  console.log('[DEBUG] Store provided successfully');
} catch (error) {
  console.error('[DEBUG] Error providing store:', error);
}

// Register plugins
console.log('[DEBUG] Registering BootstrapVue3');
try {
  app.use(BootstrapVue3);
  console.log('[DEBUG] BootstrapVue3 registered successfully');
} catch (error) {
  console.error('[DEBUG] Error registering BootstrapVue3:', error);
}

console.log('[DEBUG] Registering router');
try {
  app.use(router);
  console.log('[DEBUG] Router registered successfully');
} catch (error) {
  console.error('[DEBUG] Error registering router:', error);
}

console.log('[DEBUG] Registering store');
try {
  app.use(store);
  console.log('[DEBUG] Store registered successfully');
} catch (error) {
  console.error('[DEBUG] Error registering store:', error);
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
  app.mount("#app");
  console.log('[DEBUG] App mounted successfully');
} catch (error) {
  console.error('[DEBUG] Error mounting app:', error);
}
