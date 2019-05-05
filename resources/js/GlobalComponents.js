import Vue from 'vue';

import dateSlider from './components/dateSlider.vue';
import Navigation from './components/Navbar.vue'
import activity_list from './components/activity-list.vue'

Vue.component(dateSlider.name, dateSlider)
Vue.component(Navigation.name, Navigation)
Vue.component(activity_list.name, activity_list)