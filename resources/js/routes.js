// Views
import LoginComponent from './views/auth/LoginComponent.vue';
import UserHomeComponent from './views/home/UserHome.vue';

// Holidays
import HolidayViewComponent from './views/holiday/HolidayView.vue';
import HolidayCreateComponent from './views/holiday/HolidayCreate.vue';
import DayViewComponent from './views/holiday/DayView.vue';

// Messages
import ViewMessageComponent from './views/holiday/messages/ViewMessage.vue';
import AddMessageComponent from './views/holiday/messages/AddMessage.vue';
import EditMessageComponent from './views/holiday/messages/EditMessage.vue';

// Flights
import AddFlightComponent from './views/holiday/flights/AddFlight.vue';
import EditFlightComponent from './views/holiday/flights/EditFlight.vue';

// Photos
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
        path: '/holiday/:holidayId',
        name: 'holiday.view',
        component: HolidayViewComponent,
        children: [
            {
                path: 'day/:dayId',
                name: 'holiday.view.day',
                component: DayViewComponent
            },
        ]
    },
    {
        path: '/createHoliday',
        name: 'holiday.create',
        component: HolidayCreateComponent,
    },
    // Messages
    {
        path: '/holiday/:holidayId/day/:dayId/message/:commentId',
        name: 'holiday.view.message',
        component: ViewMessageComponent,
    },
    {
        path: '/holiday/:holidayId/day/:dayId/message/add',
        name: 'holiday.add.message',
        component: AddMessageComponent,
    },
    {
        path: '/holiday/:holidayId/day/:dayId/message/:commentId/edit',
        name: 'holiday.edit.message',
        component: EditMessageComponent,
    },
    // Flights
    {
        path: '/holiday/:holidayId/day/:dayId/flight/add',
        name: 'holiday.add.flight',
        component: AddFlightComponent,
    },
    {
        path: '/holiday/:holidayId/day/:dayId/flight/:flightId/edit',
        name: 'holiday.edit.flight',
        component: EditFlightComponent,
    },
    // Photo Messages
    {
        path: '/holiday/:holidayId/day/:dayId/photo/add',
        name: 'holiday.add.photo',
        component: AddPhotoComponent,
    },
];