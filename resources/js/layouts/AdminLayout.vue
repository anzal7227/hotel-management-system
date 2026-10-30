<template>
    <div class="app-wrapper">
        <!-- Sidebar -->
        <aside class="app-sidebar" :class="{ collapsed: sidebarCollapsed }">
            <div class="sidebar-brand">
                <div class="brand-logo">
                    <i class="bi bi-building"></i>
                </div>

                <div class="brand-text">
                    <div class="fw-bold leading-none text-white" style="font-size: 0.95rem;">
                        Appcom Hotel
                    </div>

                    <div class="text-dim fs-11" style="font-size: 0.72rem;">
                        Management System
                    </div>
                </div>
            </div>

            <ul class="sidebar-menu">
                <li class="menu-header">Main Menu</li>

                <li>
                    <router-link to="/" class="nav-link" :class="{ active: route.name === 'dashboard' }">
                        <i class="bi bi-calendar-check-fill"></i>
                        <span class="menu-text">Bookings</span>
                    </router-link>
                </li>

                <li>
                    <router-link to="/rooms" class="nav-link" active-class="active">
                        <i class="bi bi-door-open-fill"></i>
                        <span class="menu-text">Rooms</span>
                    </router-link>
                </li>

                <li>
                    <router-link to="/guests" class="nav-link" active-class="active">
                        <i class="bi bi-people-fill"></i>
                        <span class="menu-text">Guests</span>
                    </router-link>
                </li>

                <li class="menu-header mt-3">
                    <span>Administration</span>
                    <span class="badge bg-indigo-subtle text-indigo fs-10 ms-1 badge-role">
                        ADMIN
                    </span>
                </li>

                <li v-if="user && user.role === 'admin'">
                    <router-link to="/staff" class="nav-link" active-class="active">
                        <i class="bi bi-person-badge-fill"></i>
                        <span class="menu-text">Staff Users</span>
                    </router-link>
                </li>
            </ul>

            <div class="p-3 border-top" style="border-color: var(--app-border) !important;">
                <div class="d-flex align-items-center gap-2">
                    <img v-if="user && user.profile_image" :src="`/storage/${user.profile_image}`" class="rounded-circle object-fit-cover" width="36" height="36" alt="Admin"/>

                    <img v-else src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80" class="rounded-circle object-fit-cover" width="36" height="36" alt="Admin"/>
                    <div class="brand-text overflow-hidden">
                        <div class="fw-bold text-white text-truncate fs-14">
                            {{ user ? user.name : 'User' }}
                        </div>

                        <div class="text-dim fs-11">
                            {{ user ? user.role : '' }}
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Area -->
        <main class="app-main">
            <header class="app-header">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-dark-subtle btn-sm px-2 py-1" type="button" @click="toggleSidebar">
                        <i class="bi bi-list fs-5"></i>
                    </button>

                    <h1 class="h5 mb-0 fw-bold">
                        {{ pageTitle }}
                    </h1>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <span v-if="route.name === 'dashboard'" class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 fs-12">
                        <i class="bi bi-shield-check me-1"></i>
                        Admin Session Active
                    </span>

                    <!-- Page-specific navbar button -->
                    <button v-if="navbarAction" type="button" class="btn btn-accent-primary btn-sm d-flex align-items-center gap-2" @click="navbarAction()">
                        <i :class="navbarIcon"></i>
                        <span class="d-none d-sm-inline">
                            {{ navbarText }}
                        </span>
                    </button>

                    <div class="dropdown">
                        <button class="btn btn-dark-subtle btn-sm dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle fs-6"></i>

                            <span class="d-none d-md-inline">
                                {{ user ? user.name : 'User' }}
                            </span>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 style-dark-dropdown"
                            style="background-color: var(--app-surface); border: 1px solid var(--app-border) !important;">
                            <li>
                                <a class="dropdown-item text-light" href="#">
                                    <i class="bi bi-person me-2"></i>
                                    Profile
                                </a>
                            </li>

                            <li>
                                <hr class="dropdown-divider" style="border-color: var(--app-border);">
                            </li>

                            <li>
                                <a class="dropdown-item text-danger" href="#" @click.prevent="logout">
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <router-view />
        </main>
    </div>
</template>

<script setup>
import { ref, computed, provide, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const router = useRouter();

const user = ref(null);
const sidebarCollapsed = ref(false);

const navbarAction = ref(null);
const navbarText = ref('');
const navbarIcon = ref('');

const pageTitle = computed(() => {
    return route.meta.title || 'Appcom Hotel Management';
});

const toggleSidebar = () => {
    sidebarCollapsed.value = !sidebarCollapsed.value;
};

const setNavbarButton = (text, icon, action) => {
    navbarText.value = text;
    navbarIcon.value = icon;
    navbarAction.value = action;
};

provide('setNavbarButton', setNavbarButton);

const getUser = async () => {
    try {
        const response = await axios.get('/api/me');
        user.value = response.data.user;
    } catch (error) {
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        router.push('/login');
    }
};

const logout = async () => {
    try {
        await axios.post('/api/logout');
    } catch (error) {
        //  error
    }

    localStorage.removeItem('token');
    localStorage.removeItem('user');

    delete axios.defaults.headers.common['Authorization'];

    router.push('/login');
};

onMounted(() => {
    getUser();
});
</script>