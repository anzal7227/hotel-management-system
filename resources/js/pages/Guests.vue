<template>
    <div class="p-4">
        <!-- Guest Stats -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card-dark p-3">
                    <div class="text-dim fs-12 fw-semibold text-uppercase">
                        Total Registered Guests
                    </div>
                    <div class="fs-3 fw-bold text-white mt-1">
                        {{ guests.total }}
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card-dark p-3">
                    <div class="text-dim fs-12 fw-semibold text-uppercase">
                        Currently In House
                    </div>
                    <div class="fs-3 fw-bold text-success mt-1">0</div>
                </div>
            </div>
        </div>

        <!-- Guest Listing -->
        <div class="card-dark p-4">
            <div class="filter-bar-card mb-4">
                <div class="row g-3">
                    <div class="col-12 col-md-8">
                        <input type="text" class="form-control" placeholder="Search guest by name, email, phone or nationality..."
                            v-model="search">
                    </div>

                    <div class="col-12 col-md-4">
                        <button class="btn btn-accent-primary w-100" @click="getGuests()">
                            Search Guests
                        </button>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-dark-custom">
                    <thead>
                        <tr>
                            <th>Profile Photo</th>
                            <th>Guest Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Nationality</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="guest in guests.data" :key="guest.id">
                            <td>
                                <img v-if="guest.profile_image" :src="`/storage/${guest.profile_image}`" class="avatar-img"
                                    alt="Guest Avatar"/>

                                <img v-else src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80" class="avatar-img" alt="Guest Avatar" />
                            </td>

                            <td class="fw-bold text-white">{{ guest.name }}</td>
                            <td>{{ guest.email }}</td>
                            <td>{{ guest.phone }}</td>
                            <td>{{ guest.nationality }}</td>

                            <td class="text-end">
                                <button type="button" class="btn-icon-action" title="Edit Guest" @click="editGuest(guest)">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <button type="button" class="btn-icon-action danger" title="Delete Guest" @click="deleteGuest(guest)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>

                        <tr v-if="guests.data.length === 0">
                            <td colspan="6" class="text-center">No guests found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top" style="border-color: var(--app-border) !important;">
                <div class="text-dim fs-13">
                    Showing {{ guests.from }} to {{ guests.to }} of {{ guests.total }} guests
                </div>

                <ul class="pagination pagination-sm pagination-dark mb-0">
                    <li class="page-item" :class="{ disabled: guests.current_page === 1 }">
                        <button class="page-link" @click="getGuests(guests.current_page - 1)">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                    </li>

                    <li v-for="page in guests.last_page" :key="page" class="page-item" :class="{ active: guests.current_page === page }">
                        <button class="page-link" @click="getGuests(page)">
                            {{ page }}
                        </button>
                    </li>

                    <li class="page-item" :class="{ disabled: guests.current_page === guests.last_page }">
                        <button class="page-link" @click="getGuests(guests.current_page + 1)">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Add Guest Modal -->
        <div v-if="showAddModal" class="modal fade show d-block" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Add New Guest</h5>

                        <button type="button" class="btn-close btn-close-white" @click="showAddModal = false"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fs-12 text-dim fw-bold">
                                Full Name
                            </label>
                            <input type="text" class="form-control" v-model="form.name">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-12 text-dim fw-bold">
                                Email Address
                            </label>
                            <input type="email" class="form-control" v-model="form.email">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-12 text-dim fw-bold">
                                Phone Number
                            </label>
                            <input type="text" class="form-control" v-model="form.phone">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-12 text-dim fw-bold">
                                Nationality
                            </label>
                            <input type="text" class="form-control" v-model="form.nationality">
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-white-50">Profile Image</label>
                            <input type="file" class="form-control bg-dark text-white border-secondary" accept="image/*" @change="onGuestImageChange" />
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-theme" @click="showAddModal = false">
                            Cancel
                        </button>

                        <button type="button" class="btn btn-accent-primary" @click="createGuest">
                            Save Guest
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Guest Modal -->
        <div v-if="showEditModal && selectedGuest" class="modal fade show d-block" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Edit Guest Profile</h5>

                        <button type="button" class="btn-close btn-close-white" @click="showEditModal = false"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fs-12 text-dim fw-bold">
                                Full Name
                            </label>
                            <input type="text" class="form-control" v-model="selectedGuest.name">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-12 text-dim fw-bold">
                                Email
                            </label>
                            <input type="email" class="form-control" v-model="selectedGuest.email">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-12 text-dim fw-bold">
                                Phone Number
                            </label>
                            <input type="text" class="form-control" v-model="selectedGuest.phone">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-12 text-dim fw-bold">
                                Nationality
                            </label>
                            <input type="text" class="form-control" v-model="selectedGuest.nationality">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-theme" @click="showEditModal = false">
                            Cancel
                        </button>

                        <button type="button" class="btn btn-accent-primary" @click="updateGuest">
                            Update
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Guest Modal -->
        <div v-if="showDeleteModal && selectedGuest" class="modal fade show d-block" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content text-center p-2">
                    <div class="modal-body pt-4">
                        <h5 class="fw-bold mb-2">Delete Guest Record?</h5>
                        <p class="text-muted fs-13">
                            Are you sure you want to delete this guest profile?
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
import { ref, onMounted, onUnmounted, inject } from 'vue';
import axios from 'axios';

const setNavbarButton = inject('setNavbarButton');

const openAddGuestModal = () => {
    showAddModal.value = true;
};

const guests = ref({
    data: [],
    current_page: 1,
    last_page: 1,
    total: 0,
    from: 0,
    to: 0
});

const search = ref('');
const selectedGuest = ref(null);
const showAddModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const guestImage = ref(null);

const form = ref({
    name: '',
    email: '',
    phone: '',
    nationality: ''
});

const onGuestImageChange = (event) => {
    guestImage.value = event.target.files[0];
};

const getGuests = async (page = 1) => {
    const response = await axios.get('/api/guests', {
        params: {
            page: page,
            search: search.value
        }
    });

    guests.value = response.data;
};

const createGuest = async () => {
    const formData = new FormData();

    formData.append('name', form.value.name);
    formData.append('email', form.value.email);
    formData.append('phone', form.value.phone);
    formData.append('nationality', form.value.nationality);

    if (guestImage.value) {
        formData.append('profile_image', guestImage.value);
    }

    await axios.post('/api/guests', formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    });

    showAddModal.value = false;

    form.value = {
        name: '',
        email: '',
        phone: '',
        nationality: ''
    };

    guestImage.value = null;

    getGuests();
};

const editGuest = (guest) => {
    selectedGuest.value = guest;
    showEditModal.value = true;
};

const updateGuest = async () => {
    await axios.put('/api/guests/' + selectedGuest.value.id, selectedGuest.value);

    showEditModal.value = false;
    getGuests();
};

const deleteGuest = (guest) => {
    selectedGuest.value = guest;
    showDeleteModal.value = true;
};

const confirmDelete = async () => {
    await axios.delete('/api/guests/' + selectedGuest.value.id);

    showDeleteModal.value = false;
    getGuests();
};

onUnmounted(() => {
    setNavbarButton('', '', null);
});

onMounted(() => {
    getGuests();

    setNavbarButton(
        'Add Guest',
        'bi bi-person-plus',
        openAddGuestModal
    );
});
</script>