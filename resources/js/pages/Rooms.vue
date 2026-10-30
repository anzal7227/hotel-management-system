<template>
    <div class="p-4">
        <!-- Room Stats -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card-dark p-3">
                    <div class="text-dim fs-12 fw-semibold text-uppercase">
                        Total Rooms
                    </div>
                    <div class="fs-3 fw-bold text-white mt-1">
                        {{ rooms.total }}
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card-dark p-3">
                    <div class="text-dim fs-12 fw-semibold text-uppercase">
                        Available Rooms
                    </div>
                    <div class="fs-3 fw-bold text-success mt-1">0</div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card-dark p-3">
                    <div class="text-dim fs-12 fw-semibold text-uppercase">
                        Booked Rooms
                    </div>
                    <div class="fs-3 fw-bold text-warning mt-1">0</div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card-dark p-3">
                    <div class="text-dim fs-12 fw-semibold text-uppercase">
                        Average Rate
                    </div>
                    <div class="fs-3 fw-bold text-white mt-1">$0/night</div>
                </div>
            </div>
        </div>

        <!-- Filters & Listing -->
        <div class="card-dark p-4">
            <div class="filter-bar-card mb-4">
                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <input type="text" class="form-control" placeholder="Search room number..." v-model="filters.search">
                    </div>

                    <div class="col-12 col-sm-6 col-md-3">
                        <select class="form-select" v-model="filters.type">
                            <option value="">All Types</option>
                            <option value="standard">Standard</option>
                            <option value="deluxe">Deluxe</option>
                            <option value="suite">Suite</option>
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-md-3">
                        <select class="form-select" v-model="filters.status">
                            <option value="">All Statuses</option>
                            <option value="available">Available</option>
                            <option value="booked">Booked</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-2">
                        <button class="btn btn-accent-primary w-100" @click="getRooms()">
                            Filter
                        </button>
                    </div>
                </div>
            </div>

            <!-- Rooms Table -->
            <div class="table-responsive">
                <table class="table table-dark-custom">
                    <thead>
                        <tr>
                            <th>Room Number</th>
                            <th>Type</th>
                            <th>Price Per Night</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="room in rooms.data" :key="room.id">
                            <td class="fw-bold text-white">
                                Room {{ room.room_number }}
                            </td>

                            <td>
                                <span class="badge bg-dark border border-secondary">
                                    {{ room.type }}
                                </span>
                            </td>

                            <td class="fw-bold text-white">
                                ${{ room.price_per_night }}
                            </td>

                            <td>
                                <span class="badge-status" :class="room.status === 'available' ? 'badge-checkedin' : 'badge-pending'">
                                    {{ room.status }}
                                </span>
                            </td>

                            <td class="text-end">
                                <button type="button" class="btn-icon-action" title="Edit Room" @click="editRoom(room)">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <button type="button" class="btn-icon-action danger" title="Delete Room" @click="deleteRoom(room)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>

                        <tr v-if="rooms.data.length === 0">
                            <td colspan="5" class="text-center">No rooms found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top" style="border-color: var(--app-border) !important;">
                <div class="text-dim fs-13">
                    Showing {{ rooms.from }} to {{ rooms.to }} of {{ rooms.total }} rooms
                </div>

                <ul class="pagination pagination-sm pagination-dark mb-0">
                    <li class="page-item" :class="{ disabled: rooms.current_page === 1 }">
                        <button class="page-link" @click="getRooms(rooms.current_page - 1)">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                    </li>

                    <li v-for="page in rooms.last_page" :key="page" class="page-item" :class="{ active: rooms.current_page === page }"
                    >
                        <button class="page-link" @click="getRooms(page)">
                            {{ page }}
                        </button>
                    </li>

                    <li class="page-item" :class="{ disabled: rooms.current_page === rooms.last_page }">
                        <button class="page-link" @click="getRooms(rooms.current_page + 1)">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Add Room Modal -->
        <div v-if="showAddModal" class="modal fade show d-block" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Add New Room</h5>

                        <button type="button" class="btn-close btn-close-white" @click="showAddModal = false"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fs-12 text-dim fw-bold">
                                Room Number
                            </label>
                            <input type="text" class="form-control" v-model="form.room_number">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-12 text-dim fw-bold">
                                Room Type
                            </label>
                            <select class="form-select" v-model="form.type">
                                <option value="standard">Standard</option>
                                <option value="deluxe">Deluxe</option>
                                <option value="suite">Suite</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-12 text-dim fw-bold">
                                Price Per Night ($)
                            </label>
                            <input type="number" class="form-control" v-model="form.price_per_night">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-12 text-dim fw-bold">
                                Status
                            </label>
                            <select class="form-select" v-model="form.status">
                                <option value="available">Available</option>
                                <option value="booked">Booked</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-theme" @click="showAddModal = false">
                            Cancel
                        </button>

                        <button type="button" class="btn btn-accent-primary" @click="createRoom">
                            Save Room
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Room Modal -->
        <div v-if="showEditModal && selectedRoom" class="modal fade show d-block" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">
                            Edit Room (Room {{ selectedRoom.room_number }})
                        </h5>

                        <button type="button" class="btn-close btn-close-white" @click="showEditModal = false"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fs-12 text-dim fw-bold">
                                Room Number
                            </label>
                            <input type="text" class="form-control" v-model="selectedRoom.room_number">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-12 text-dim fw-bold">
                                Room Type
                            </label>
                            <select class="form-select" v-model="selectedRoom.type">
                                <option value="standard">Standard</option>
                                <option value="deluxe">Deluxe</option>
                                <option value="suite">Suite</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-12 text-dim fw-bold">
                                Price Per Night ($)
                            </label>
                            <input type="number" class="form-control" v-model="selectedRoom.price_per_night">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-12 text-dim fw-bold">
                                Status
                            </label>
                            <select class="form-select" v-model="selectedRoom.status">
                                <option value="available">Available</option>
                                <option value="booked">Booked</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-theme" @click="showEditModal = false">
                            Cancel
                        </button>

                        <button type="button" class="btn btn-accent-primary" @click="updateRoom">
                            Update Room
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Room Modal -->
        <div v-if="showDeleteModal && selectedRoom" class="modal fade show d-block" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content text-center p-2">
                    <div class="modal-body pt-4">
                        <h5 class="fw-bold mb-2">Delete Room?</h5>
                        <p class="text-muted fs-13">
                            Are you sure you want to remove this room?
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

const setNavbarButton = inject('setNavbarButton');

const openAddRoomModal = () => {
    showAddModal.value = true;
};

const rooms = ref({
    data: [],
    current_page: 1,
    last_page: 1,
    total: 0,
    from: 0,
    to: 0
});

const filters = reactive({
    search: '',
    type: '',
    status: ''
});

const selectedRoom = ref(null);
const showAddModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);

const form = reactive({
    room_number: '',
    type: 'standard',
    price_per_night: '',
    status: 'available'
});

const getRooms = async (page = 1) => {
    const response = await axios.get('/api/rooms', {
        params: {
            page: page,
            search: filters.search,
            type: filters.type,
            status: filters.status
        }
    });

    rooms.value = response.data;
};

const createRoom = async () => {
    await axios.post('/api/rooms', form);

    showAddModal.value = false;

    form.room_number = '';
    form.type = 'standard';
    form.price_per_night = '';
    form.status = 'available';

    getRooms();
};

const editRoom = (room) => {
    selectedRoom.value = room;
    showEditModal.value = true;
};

const updateRoom = async () => {
    await axios.put('/api/rooms/' + selectedRoom.value.id, selectedRoom.value);

    showEditModal.value = false;
    getRooms();
};

const deleteRoom = (room) => {
    selectedRoom.value = room;
    showDeleteModal.value = true;
};

const confirmDelete = async () => {
    await axios.delete('/api/rooms/' + selectedRoom.value.id);

    showDeleteModal.value = false;
    getRooms();
};

onUnmounted(() => {
    setNavbarButton('', '', null);
});

onMounted(() => {
    getRooms();

    setNavbarButton(
        'Add Room',
        'bi bi-plus-lg',
        openAddRoomModal
    );
});
</script>