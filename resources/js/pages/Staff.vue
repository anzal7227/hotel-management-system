<template>
    <div class="p-4">
        <div class="card-dark p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h5 mb-0">Staff Accounts & Permissions</h2>

                <button type="button" class="btn btn-accent-primary btn-sm" @click="showCreateModal = true">
                    <i class="bi bi-person-plus"></i>
                    Create Staff Account
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-dark-custom">
                    <thead>
                        <tr>
                            <th>Staff Name</th>
                            <th>Email Address</th>
                            <th>Granted Access Modules</th>
                            <th>Account Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="staff in staffList.data" :key="staff.id">
                            <td class="fw-bold text-white">{{ staff.name }}</td>
                            <td>{{ staff.email }}</td>

                            <td>
                                <span v-for="permission in staff.permissions" :key="permission" class="badge bg-dark border border-secondary text-info me-1">
                                    {{ permission }}
                                </span>
                            </td>

                            <td>
                                <span class="badge-status badge-checkedin">
                                    {{ staff.status }}
                                </span>
                            </td>

                            <td class="text-end">
                                <button type="button" class="btn-icon-action" @click="editStaff(staff)">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <button type="button" class="btn-icon-action danger" @click="deleteStaff(staff)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>

                        <tr v-if="staffList.data.length === 0">
                            <td colspan="5" class="text-center">No staff found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Create Staff Modal -->
        <div v-if="showCreateModal" class="modal fade show d-block" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Create Staff Account</h5>
                        <button type="button" class="btn-close btn-close-white" @click="showCreateModal = false"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fs-12 text-dim fw-bold">Full Name</label>
                            <input type="text" class="form-control" v-model="form.name">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-12 text-dim fw-bold">Email</label>
                            <input type="email" class="form-control" v-model="form.email">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-12 text-dim fw-bold">Password</label>
                            <input type="password" class="form-control" v-model="form.password">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-12 text-dim fw-bold">Section Access</label>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="bookings" v-model="form.permissions">
                                <label class="form-check-label">Bookings</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="rooms" v-model="form.permissions">
                                <label class="form-check-label">Rooms</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="guests" v-model="form.permissions">
                                <label class="form-check-label">Guests</label>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-theme" @click="showCreateModal = false">
                            Cancel
                        </button>

                        <button type="button" class="btn btn-accent-primary" @click="createStaff">
                            Create Account
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Staff Modal -->
        <div v-if="showEditModal && selectedStaff" class="modal fade show d-block" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Edit Staff Account</h5>
                        <button type="button" class="btn-close btn-close-white" @click="showEditModal = false"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fs-12 text-dim fw-bold">Full Name</label>
                            <input type="text" class="form-control" v-model="selectedStaff.name">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-12 text-dim fw-bold">Email</label>
                            <input type="email" class="form-control" v-model="selectedStaff.email">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-12 text-dim fw-bold">Section Access</label>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="bookings" v-model="selectedStaff.permissions">
                                <label class="form-check-label">Bookings</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="rooms" v-model="selectedStaff.permissions">
                                <label class="form-check-label">Rooms</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="guests" v-model="selectedStaff.permissions">
                                <label class="form-check-label">Guests</label>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-theme" @click="showEditModal = false">
                            Cancel
                        </button>

                        <button type="button" class="btn btn-accent-primary" @click="updateStaff">
                            Update
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Staff Modal -->
        <div v-if="showDeleteModal && selectedStaff" class="modal fade show d-block" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content text-center p-2">
                    <div class="modal-body pt-4">
                        <h5 class="fw-bold mb-2">Delete Staff User?</h5>
                        <p class="text-muted fs-13">
                            Are you sure you want to permanently delete this user account?
                        </p>
                    </div>

                    <div class="modal-footer justify-content-center border-0 pt-0">
                        <button type="button" class="btn btn-outline-theme" @click="showDeleteModal = false">
                            Cancel
                        </button>

                        <button type="button" class="btn btn-danger" @click="confirmDelete">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted, onUnmounted, inject } from 'vue';
import axios from 'axios';

const openCreateStaffModal = () => {
    showCreateModal.value = true;
};

const setNavbarButton = inject('setNavbarButton');

const staffList = ref({
    data: []
});

const selectedStaff = ref(null);
const showCreateModal = ref(false);
const showDeleteModal = ref(false);
const showEditModal = ref(false);

const form = reactive({
    name: '',
    email: '',
    password: '',
    permissions: []
});

const getStaff = async () => {
    const response = await axios.get('/api/staff');
    staffList.value = response.data;
};

const createStaff = async () => {
    await axios.post('/api/staff', form);

    showCreateModal.value = false;

    form.name = '';
    form.email = '';
    form.password = '';
    form.permissions = [];

    getStaff();
};

const deleteStaff = (staff) => {
    selectedStaff.value = staff;
    showDeleteModal.value = true;
};

const confirmDelete = async () => {
    await axios.delete('/api/staff/' + selectedStaff.value.id);

    showDeleteModal.value = false;
    getStaff();
};

const editStaff = (staff) => {
    selectedStaff.value = {
        ...staff,
        permissions: staff.permissions || []
    };

    showEditModal.value = true;
};

const updateStaff = async () => {
    await axios.put('/api/staff/' + selectedStaff.value.id, {
        name: selectedStaff.value.name,
        email: selectedStaff.value.email,
        permissions: selectedStaff.value.permissions
    });

    showEditModal.value = false;
    getStaff();
};

onUnmounted(() => {
    setNavbarButton('', '', null);
});

onMounted(() => {
    getStaff();

    setNavbarButton(
        'Create Staff Account',
        'bi bi-person-plus',
        openCreateStaffModal
    );
});
</script>