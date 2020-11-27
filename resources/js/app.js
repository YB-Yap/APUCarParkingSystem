/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// packages
import Vue from "vue";
import _ from "lodash";
import VueRouter from "vue-router";
import "@mdi/font/css/materialdesignicons.min.css";
import { routes } from "./routes";
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import "masonry-layout/dist/masonry.pkgd.min.js";
import {VueMasonryPlugin} from "vue-masonry";

// vue components
import LandingPage from "./pages/LandingPage";
import AdminLandingPage from "./pages/Admin/LandingPage";

Vue.use(VueRouter);
Vue.use(VueSweetalert2);
Vue.use(VueMasonryPlugin);

Vue.component('pagination', require('laravel-vue-pagination'));

const router = new VueRouter({
    mode: "history",
    base: "/",
    routes,
    linkActiveClass: "btm-nav-active",
    scrollBehavior() {
        return { x: 0, y: 0 };
    }
});

const app = new Vue({
    el: '#app',
    components: { LandingPage, AdminLandingPage },
    router,
});
