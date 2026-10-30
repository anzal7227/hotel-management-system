<template>
    <div class="login-body">
        <div class="login-card-container">
            <div class="card-dark p-4 p-sm-5">
                <!-- Brand Header -->
                <div class="text-center mb-4">
                    <div class="brand-logo mx-auto mb-3" style="width: 48px; height: 48px; font-size: 1.5rem;">
                        <i class="bi bi-building"></i>
                    </div>

                    <h2 class="h4 mb-1">Appcom Hotel Management</h2>
                    <p class="text-muted fs-14 mb-0">
                        Sign in to access your dashboard
                    </p>
                </div>

                <!-- Login Form -->
                <form @submit.prevent="login">
                    <div v-if="errorMessage" class="alert alert-danger py-2 fs-13">
                        {{ errorMessage }}
                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-12 text-dim fw-bold">
                            Login Role
                        </label>

                        <div class="d-flex gap-2">
                            <input type="radio" class="btn-check" name="userRole" id="roleAdmin" value="admin" v-model="form.role">

                            <label class="btn btn-outline-theme w-50 py-2 fs-13 d-flex align-items-center justify-content-center gap-2"
                                for="roleAdmin">
                                <i class="bi bi-shield-lock"></i>
                                Admin
                            </label>

                            <input type="radio" class="btn-check" name="userRole" id="roleStaff" value="staff" v-model="form.role">
                            <label class="btn btn-outline-theme w-50 py-2 fs-13 d-flex align-items-center justify-content-center gap-2" for="roleStaff">
                                <i class="bi bi-person-badge"></i>
                                Staff
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-12 text-dim fw-bold">
                            Username or Email
                        </label>

                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-person"></i>
                            </span>

                            <input type="email" class="form-control" placeholder="admin@appcomsolution.com" v-model="form.email" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fs-12 text-dim fw-bold">
                            Password
                        </label>

                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-lock"></i>
                            </span>

                            <input type="password" class="form-control" placeholder="••••••••" v-model="form.password" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-accent-primary w-100 py-2 mb-3" :disabled="loading">
                        <span v-if="loading">Signing In...</span>
                        <span v-else>
                            Sign In
                            <i class="bi bi-arrow-right ms-1"></i>
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();

const form = reactive({
    role: 'admin',
    email: '',
    password: ''
});

const loading = ref(false);
const errorMessage = ref('');

const login = async () => {
    loading.value = true;
    errorMessage.value = '';

    try {
        const response = await axios.post('/api/login', form);

        localStorage.setItem('token', response.data.token);
        localStorage.setItem('user', JSON.stringify(response.data.user));

        axios.defaults.headers.common['Authorization'] =
            'Bearer ' + response.data.token;

        router.push('/');
    } catch (error) {
        errorMessage.value = error.response?.data?.message
            || 'Invalid email or password.';
    } finally {
        loading.value = false;
    }
};
</script>