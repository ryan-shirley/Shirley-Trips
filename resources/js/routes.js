// Views
import LoginComponent from './views/auth/LoginComponent.vue';
import UserHomeComponent from './views/home/UserHome.vue';

// Holidays
import HolidayViewComponent from './views/holiday/HolidayView.vue';

import AddMessageComponent from './views/holiday/messages/AddMessage.vue';
import EditMessageComponent from './views/holiday/messages/EditMessage.vue';

import AddFlightComponent from './views/holiday/flights/AddFlight.vue';
import EditFlightComponent from './views/holiday/flights/EditFlight.vue';

import AddPhotoComponent from './views/holiday/photos/AddPhoto.vue';

export const routes = [
    {
        path: '/',
        components: {
            default: LoginComponent
        },
        name: 'login'
    },
    {
        path: '/home',
        name: 'account.home',
        component: UserHomeComponent,
    },
    {
        path: '/holiday/:id',
        name: 'holiday.view',
        component: HolidayViewComponent,
    },
    {
        path: '/holiday/:id/add/:day/message',
        name: 'holiday.add.message',
        component: AddMessageComponent,
    },
    {
        path: '/holiday/:id/edit/:day/message/:commentId',
        name: 'holiday.edit.message',
        component: EditMessageComponent,
    },
    {
        path: '/holiday/:id/add/:day/flight',
        name: 'holiday.add.flight',
        component: AddFlightComponent,
    },
    {
        path: '/holiday/:id/edit/:day/flight/:flightId',
        name: 'holiday.edit.flight',
        component: EditFlightComponent,
    },
    {
        path: '/holiday/:id/add/:day/photo',
        name: 'holiday.add.photo',
        component: AddPhotoComponent,
    },
];