import { createApp } from "vue";
import { createRouter, createWebHistory } from "vue-router";
import router from "@/router/index.js";
import store from "@/store/index.js";
import process from 'process';

window.process = process;

import vue3GoogleLogin from 'vue3-google-login'
import { createModal } from '@kolirt/vue-modal'

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

import BootstrapVue3 from 'bootstrap-vue-3'

// import function to register Swiper custom elements
import { register } from 'swiper/element/bundle';
// register Swiper custom elements
register();

// import VueAnalytics from 'vue-analytics'
import App from "./App";
const isProd = process.env.NODE_ENV === "production";

// Create Vue app
const app = createApp(App);

// Set production mode configuration
if (isProd) {
  app.config.devtools = false;
  app.config.performance = false;
  app.config.productionTip = false;
} else {
  app.config.devtools = true;
  app.config.performance = true;
  app.config.productionTip = false;
}

// Ensure Vue has access to the provide/inject capabilities
app.provide('store', store);

// Register plugins
app.use(BootstrapVue3);
app.use(router);
app.use(store);
app.use(vue3GoogleLogin, {
    clientId: '254777876915-4lgmqe9diaebsjchptmdrt2edlqo3tn8.apps.googleusercontent.com',
    buttonConfig: {
      size: "large",
      shape: "pill", 
      width: "300px"
    }
  });
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

app.component('font-awesome-icon', FontAwesomeIcon)

// Mount the app to the DOM
app.mount("#app");
