import { createApp } from "vue";
import { createRouter, createWebHistory } from "vue-router";
import router from "./router";
import store from "@/store/index.js";

import { BootstrapVue } from "bootstrap-vue";
import "bootstrap/dist/css/bootstrap.css";
import "bootstrap-vue/dist/bootstrap-vue.css";
// import VueAnalytics from 'vue-analytics'

const isProd = process.env.NODE_ENV === "production";

const app = createApp(App);

app.use(BootstrapVue);
app.use(createRouter({
  history: createWebHistory(),
  routes: router,
}));
app.use(store);
app.config.devtools = true;
app.config.performance = true;
app.config.productionTip = false;

import App from "./App";

app.mount("#app");
