import { createApp } from "vue";
import { createRouter, createWebHistory } from "vue-router";
import router from "./router";
import store from "@/store/index.js";

import vue3GoogleLogin from 'vue3-google-login'
import { createModal } from '@kolirt/vue-modal'

import { library } from '@fortawesome/fontawesome-svg-core' 
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faMoneyBillAlt } from "@fortawesome/fontawesome-free-solid";
library.add(faMoneyBillAlt)

import BootstrapVue3 from 'bootstrap-vue-3'

// import function to register Swiper custom elements
import { register } from 'swiper/element/bundle';
// register Swiper custom elements
register();


// import VueAnalytics from 'vue-analytics'
import App from "./App";

const isProd = process.env.NODE_ENV === "production";

const app = createApp(App);

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
  })
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
  }))
app.config.devtools = true;
app.config.performance = true;
app.config.productionTip = false;



app.component('font-awesome-icon', FontAwesomeIcon).mount("#app");
