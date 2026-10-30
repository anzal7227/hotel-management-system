import { createRouter, createWebHistory } from 'vue-router';
import Login from '../pages/Login.vue';
import AdminLayout from '../layouts/AdminLayout.vue';
import Dashboard from '../pages/Dashboard.vue';
import Guests from '../pages/Guests.vue';
import Rooms from '../pages/Rooms.vue';
import Staff from '../pages/Staff.vue';

const routes = [
    {
        path: '/login',
        name: 'login',
        component: Login
    },
    {
        path: '/',
        component: AdminLayout,
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'dashboard',
                component: Dashboard,
                meta: {
                    title: 'Dashboard Overview',
                    permission: 'bookings'
                }
            },
            {
                path: 'guests',
                name: 'guests',
                component: Guests,
                meta: {
                    title: 'Guests Directory',
                    permission: 'guests'
                }
            },
            {
                path: 'rooms',
                name: 'rooms',
                component: Rooms,
                meta: {
                    title: 'Rooms Management',
                    permission: 'rooms'
                }
            },
            {
                path: 'staff',
                name: 'staff',
                component: Staff,
                meta: {
                    title: 'Staff Accounts & Permissions',
                    adminOnly: true
                }
            }
        ]
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach((to) => {
    const token = localStorage.getItem('token');
    const user = JSON.parse(localStorage.getItem('user') || 'null');

    if (to.meta.requiresAuth && !token) {
        return { name: 'login' };
    }

    if (to.name === 'login' && token) {
        return { name: 'dashboard' };
    }

    if (to.meta.adminOnly && user?.role !== 'admin') {
        return { name: 'dashboard' };
    }

    if (to.meta.permission && user?.role !== 'admin') {
        const permissions = user?.permissions || [];

        if (!permissions.includes(to.meta.permission)) {
            return { name: 'dashboard' };
        }
    }

    return true;
});

export default router;