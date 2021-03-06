import Vue from 'vue';

import dateSlider from './components/dateSlider.vue';
import Navigation from './components/Navbar.vue'
import activity_list from './components/activity-list.vue'
import newActivityPicker from './components/new-activity-picker.vue'
import flightDetails from './components/flight-details.vue'
import commentDetails from './components/comment-details.vue'
import hotelDetails from './components/hotel-details.vue'
import videoCard from './components/video-card.vue'
import holdidayCard from './components/holiday-card.vue'
import footer from './components/footer-global.vue'

Vue.component(dateSlider.name, dateSlider)
Vue.component(Navigation.name, Navigation)
Vue.component(activity_list.name, activity_list)
Vue.component(newActivityPicker.name, newActivityPicker)
Vue.component(flightDetails.name, flightDetails)
Vue.component(commentDetails.name, commentDetails)
Vue.component(hotelDetails.name, hotelDetails)
Vue.component(videoCard.name, videoCard)
Vue.component(holdidayCard.name, holdidayCard)
Vue.component(footer.name, footer)