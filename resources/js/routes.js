// Views
import LoginComponent from './views/auth/LoginComponent.vue';
import UserHomeComponent from './views/home/UserHome.vue';

// Holidays
import HolidayViewComponent from './views/holiday/HolidayView.vue';

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
    }
];