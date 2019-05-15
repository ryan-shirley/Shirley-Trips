/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
import { routes } from './routes';
import globalComponents from './GlobalComponents'

// Routes
Vue.use(VueRouter);

// Configure Vue Router & Vue
const router = new VueRouter({
    routes: routes
});

const app = new Vue({
    el: '#app',
    router: router
});