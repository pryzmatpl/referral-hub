import { createApp } from "vue";
import { createRouter, createWebHistory } from "vue-router";
import router from "./router";
import store from "@/store/index.js";

import vue3GoogleLogin from 'vue3-google-login'

import { library } from '@fortawesome/fontawesome-svg-core' 
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faMoneyBillAlt } from "@fortawesome/fontawesome-free-solid";
library.add(faMoneyBillAlt)

import BootstrapVue3 from 'bootstrap-vue-3'


// import VueAnalytics from 'vue-analytics'
import App from "./App";

const isProd = process.env.NODE_ENV === "production";

const app = createApp(App);

app.use(BootstrapVue3);
app.use(router);
app.use(store);
app.use(vue3GoogleLogin, {
    clientId: '254777876915-4lgmqe9diaebsjchptmdrt2edlqo3tn8.apps.googleusercontent.com'
  })
app.config.devtools = true;
app.config.performance = true;
app.config.productionTip = false;



app.component('font-awesome-icon', FontAwesomeIcon).mount("#app");
