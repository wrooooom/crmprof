import { RouteRecordRaw } from 'vue-router';
import Dashboard from './pages/Dashboard.vue';
import Orders from './pages/Orders/Index.vue';
import Drivers from './pages/Drivers/Index.vue';
import Vehicles from './pages/Vehicles/Index.vue';
import Login from './pages/Login.vue';
import MainLayout from './layouts/MainLayout.vue';

const routes: RouteRecordRaw[] = [
    {
        path: '/login',
        name: 'login',
        component: Login,
    },
    {
        path: '/',
        component: MainLayout,
        children: [
            {
                path: '',
                name: 'dashboard',
                component: Dashboard,
            },
            {
                path: 'orders',
                name: 'orders',
                component: Orders,
            },
            {
                path: 'drivers',
                name: 'drivers',
                component: Drivers,
            },
            {
                path: 'vehicles',
                name: 'vehicles',
                component: Vehicles,
            },
        ],
    },
];

export default routes;
