import { createApp } from "vue";
import { createRouter, createWebHistory } from "vue-router";
import router from "./router";
import store from "@/store/index.js";

import BootstrapVue3 from 'bootstrap-vue-3'


// import VueAnalytics from 'vue-analytics'
import App from "./App";

const isProd = process.env.NODE_ENV === "production";

const app = createApp(App);

app.use(BootstrapVue3);
app.use(router);
app.use(store);
app.config.devtools = true;
app.config.performance = true;
app.config.productionTip = false;



app.mount("#app");
