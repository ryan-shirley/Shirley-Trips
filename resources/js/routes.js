// Views
import LoginComponent from './views/auth/LoginComponent.vue';
import UserHomeComponent from './views/home/UserHome.vue';

// Holidays
import HolidayViewComponent from './views/holiday/HolidayView.vue';
import HolidayEditComponent from './views/holiday/HolidayEdit.vue';
import HolidayCreateComponent from './views/holiday/HolidayCreate.vue';
import HolidayEditPermissionsComponent from './views/holiday/HolidayEditPermissions.vue';

// Day
import DayViewComponent from './views/holiday/DayView.vue';

// Hotel
import HotelViewComponent from './views/holiday/hotel/HotelView.vue';
import HotelAddComponent from './views/holiday/hotel/HotelAdd.vue';
import HotelEditComponent from './views/holiday/hotel/HotelEdit.vue';

// Messages
import ViewMessageComponent from './views/holiday/messages/ViewMessage.vue';
import AddMessageComponent from './views/holiday/messages/AddMessage.vue';
import EditMessageComponent from './views/holiday/messages/EditMessage.vue';

// Flights
import AddFlightComponent from './views/holiday/flights/AddFlight.vue';
import EditFlightComponent from './views/holiday/flights/EditFlight.vue';
import ViewFlightComponent from './views/holiday/flights/ViewFlight.vue';

// Videos
import VideoAddComponent from './views/holiday/videos/AddVideo.vue';
import VideoEditComponent from './views/holiday/videos/EditVideo.vue';

// Admin
import AdminHomeComponent from './views/admin/AdminHomeComponent.vue';
// Admin - Airlines
import AirlinesListComponent from './views/admin/airlines/AirlinesList.vue';
import AirlinesAddComponent from './views/admin/airlines/AirlinesAdd.vue';


// Middleware
import multiguard from 'vue-router-multiguard';

let state = {
    token: localStorage.getItem('token') || '',
    isAdmin: localStorage.getItem('isAdmin') || false,
  }

const isLoggedIn = (to, from, next) => {
    if(state.token) {
        next()
    }
    else {
        if(!checkLoginState(next)) {
            console.log('Not logged in. Routing to login page')
            next({ name: 'login' }); // go to '/login';
        }
    }
}

const isAdmin = (to, from, next) => {
    if(state.isAdmin) {
        next()
    }
    else {
        localStorage.removeItem('token')
        next({ name: 'login' })
    }
}

function checkLoginState(next) { 
    console.log('Updating login state')
    if(localStorage.getItem('token')) {
        state.token = true

        if(localStorage.getItem('isAdmin')) {
            state.isAdmin = true
        }

        next({ name: 'account.home' })

        return true
    }
    else {
        return false
    }
}

export const routes = [
    {
        path: '/',
        components: {
            default: LoginComponent
        },
        name: 'login',
    },
    {
        path: '/home',
        name: 'account.home',
        component: UserHomeComponent,
        beforeEnter: isLoggedIn
    },
    {
        path: '/holiday/:holidayId',
        name: 'holiday.view',
        component: HolidayViewComponent,
        beforeEnter: isLoggedIn,
        children: [
            {
                path: 'day/:dayId',
                name: 'holiday.view.day',
                component: DayViewComponent,
                beforeEnter: isLoggedIn,
            },
        ]
    },
    {
        path: '/createHoliday',
        name: 'holiday.create',
        component: HolidayCreateComponent,
    },
    {
        path: '/holiday/:holidayId/edit',
        name: 'holiday.edit',
        component: HolidayEditComponent,
        beforeEnter: isLoggedIn,
    },
    {
        path: '/holiday/:holidayId/permissions',
        name: 'holiday.edit.permissions',
        component: HolidayEditPermissionsComponent,
        beforeEnter: isLoggedIn,
    },
    // Messages
    {
        path: '/holiday/:holidayId/day/:dayId/message/:commentId',
        name: 'holiday.view.message',
        component: ViewMessageComponent,
        beforeEnter: isLoggedIn,
    },
    {
        path: '/holiday/:holidayId/day/:dayId/add/message',
        name: 'holiday.add.message',
        component: AddMessageComponent,
        beforeEnter: isLoggedIn,
    },
    {
        path: '/holiday/:holidayId/day/:dayId/message/:commentId/edit',
        name: 'holiday.edit.message',
        component: EditMessageComponent,
        beforeEnter: isLoggedIn,
    },
    // Flights
    {
        path: '/holiday/:holidayId/day/:dayId/flight/:flightId',
        name: 'holiday.view.flight',
        component: ViewFlightComponent,
        beforeEnter: isLoggedIn,
    },
    {
        path: '/holiday/:holidayId/day/:dayId/add/flight',
        name: 'holiday.add.flight',
        component: AddFlightComponent,
        beforeEnter: isLoggedIn,
    },
    {
        path: '/holiday/:holidayId/day/:dayId/flight/:flightId/edit',
        name: 'holiday.edit.flight',
        component: EditFlightComponent,
        beforeEnter: isLoggedIn,
    },
    // Hotel
    {
        path: '/holiday/:holidayId/day/:dayId/hotel/:hotelId',
        name: 'holiday.hotel.view',
        component: HotelViewComponent,
        beforeEnter: isLoggedIn,
    },
    {
        path: '/holiday/:holidayId/day/:dayId/add/hotel',
        name: 'holiday.add.hotel',
        component: HotelAddComponent,
        beforeEnter: isLoggedIn,
    },
    {
        path: '/holiday/:holidayId/day/:dayId/hotel/:hotelId/edit',
        name: 'holiday.edit.hotel',
        component: HotelEditComponent,
        beforeEnter: isLoggedIn,
    },
    // Video
    {
        path: '/holiday/:holidayId/day/:dayId/add/video',
        name: 'holiday.add.video',
        component: VideoAddComponent,
        beforeEnter: isLoggedIn,
    },
    {
        path: '/holiday/:holidayId/day/:dayId/video/:videoId/edit',
        name: 'holiday.edit.video',
        component: VideoEditComponent,
        beforeEnter: isLoggedIn,
    },
    // Admin Dashboard
    {
        path: '/admin',
        name: 'admin.home',
        component: AdminHomeComponent,
        beforeEnter: multiguard([isLoggedIn, isAdmin]),
        children: [
            // Airlines
            {
                path: 'airlines',
                name: 'admin.airlines.list',
                component: AirlinesListComponent,
            },
            {
                path: 'airlines/add',
                name: 'admin.airlines.add',
                component: AirlinesAddComponent,
            },
        ]
    },
    
];