/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// packages
import Vue from "vue";
import VueRouter from "vue-router";
import "@mdi/font/css/materialdesignicons.min.css";
import { routes } from "./routes";

// vue components
import Dashboard from "./pages/Dashboard";

Vue.use(VueRouter);

// Vue.component('index', Index);

const router = new VueRouter({
    mode: "history",
    base: "/",
    routes,
    linkActiveClass: "active",
    scrollBehavior() {
        return { x: 0, y: 0 };
    }
});

const app = new Vue({
    el: '#app',
    components: { Dashboard },
    router,
});
