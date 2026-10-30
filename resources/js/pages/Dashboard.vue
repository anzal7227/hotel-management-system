<template>
    <div class="p-4">

      <!-- Stats Overview Row -->
      <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
          <div class="card-dark p-3">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <div class="text-dim fs-12 fw-semibold text-uppercase tracking-wider">Total Bookings</div>
                <div class="fs-3 fw-bold text-white mt-1">{{ stats.total_bookings }}</div>
                <div class="text-success fs-12 fw-semibold mt-1">
                  <i class="bi bi-arrow-up-short"></i> +12.5% this month
                </div>
              </div>
              <div class="stat-card-icon" style="background-color: rgba(99, 102, 241, 0.1); color: var(--accent-primary);">
                <i class="bi bi-calendar-range"></i>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
          <div class="card-dark p-3">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <div class="text-dim fs-12 fw-semibold text-uppercase tracking-wider">Occupancy Rate</div>
                <div class="fs-3 fw-bold text-white mt-1">84.2%</div>
                <div class="text-success fs-12 fw-semibold mt-1">
                  <i class="bi bi-arrow-up-short"></i> +3.1% vs last week
                </div>
              </div>
              <div class="stat-card-icon" style="background-color: rgba(6, 182, 212, 0.1); color: var(--accent-cyan);">
                <i class="bi bi-door-closed"></i>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
          <div class="card-dark p-3">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <div class="text-dim fs-12 fw-semibold text-uppercase tracking-wider">Active Guests</div>
                <div class="fs-3 fw-bold text-white mt-1">{{ stats.total_guests }}</div>
                <div class="text-dim fs-12 fw-semibold mt-1">
                  Across 42 active rooms
                </div>
              </div>
              <div class="stat-card-icon" style="background-color: rgba(16, 185, 129, 0.1); color: #10b981;">
                <i class="bi bi-people"></i>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
          <div class="card-dark p-3">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <div class="text-dim fs-12 fw-semibold text-uppercase tracking-wider">Total Revenue</div>
                <div class="fs-3 fw-bold text-white mt-1">${{ stats.total_revenue }}</div>
                <div class="text-success fs-12 fw-semibold mt-1">
                  <i class="bi bi-arrow-up-short"></i> +18.4% YTD
                </div>
              </div>
              <div class="stat-card-icon" style="background-color: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                <i class="bi bi-currency-dollar"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Bookings Section Header & Actions -->
      <div class="card-dark p-4 mb-4">
        
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
          <div>
            <h2 class="h5 mb-1">Bookings Management</h2>
            <p class="text-muted fs-14 mb-0">Search, filter, edit and export hotel reservations in real-time.</p>
          </div>
          
          <div class="d-flex align-items-center gap-2">
            <button class="btn btn-dark-subtle btn-sm d-flex align-items-center gap-2">
              <i class="bi bi-file-earmark-pdf text-danger"></i>
              <span>Export PDF</span>
            </button>
            <button class="btn btn-dark-subtle btn-sm d-flex align-items-center gap-2">
              <i class="bi bi-file-earmark-excel text-success"></i>
              <span>Export Excel</span>
            </button>
          </div>
        </div>

        <!-- Filters Section Container -->
        <div class="filter-bar-card mb-4">
          <div class="row g-3">
            <div class="col-12 col-md-4 col-lg-3">
              <label class="form-label fs-12 text-dim fw-bold">Search Guest</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Name, email, or phone..." v-model="filters.search">
              </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3 col-lg-2">
              <label class="form-label fs-12 text-dim fw-bold">Booking Status</label>
              <select class="form-select" v-model="filters.status">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="checked_in">Checked-in</option>
                <option value="checked_out">Checked-out</option>
                <option value="cancelled">Cancelled</option>
              </select>
            </div>

            <div class="col-12 col-sm-6 col-md-3 col-lg-2">
              <label class="form-label fs-12 text-dim fw-bold">Room Type</label>
              <select class="form-select" v-model="filters.room_type">
                <option value="">All Types</option>
                <option value="standard">Standard</option>
                <option value="deluxe">Deluxe</option>
                <option value="suite">Suite</option>
              </select>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
              <label class="form-label fs-12 text-dim fw-bold">Check-In Date Range</label>
              <div class="input-group">
                <input type="date" class="form-control" placeholder="Start" v-model="filters.from_date">
                <span class="input-group-text">to</span>
                <input type="date" class="form-control" placeholder="End" v-model="filters.to_date">
              </div>
            </div>

            <div class="col-12 col-md-6 col-lg-2 d-flex align-items-end gap-2">
              <button class="btn btn-accent-primary w-100 btn-sm" @click="getBookings()">Filter</button>
              <button class="btn btn-outline-theme btn-sm px-3" title="Reset Filters" @click="resetFilters"><i class="bi bi-arrow-counterclockwise"></i></button>
            </div>
          </div>
        </div>

        <!-- Bookings Table -->
        <div class="table-responsive">
          <table class="table table-dark-custom">
            <thead>
              <tr>
                <th>Booking ID</th>
                <th>Guest Detail</th>
                <th>Room Info</th>
                <th>Check-In <i class="bi bi-arrow-down-up ms-1 text-dim"></i></th>
                <th>Check-Out</th>
                <th>Status</th>
                <th>Total Amount <i class="bi bi-arrow-down-up ms-1 text-dim"></i></th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
                <tr v-for="booking in bookings.data" :key="booking.id">
                    <td class="fw-bold text-white">#BK-{{ booking.id }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img v-if="booking.guest.profile_image" :src="`/storage/${booking.guest.profile_image}`" class="rounded-circle object-fit-cover" width="36" height="36" alt="Guest" />
                            <img v-else src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80" class="rounded-circle object-fit-cover" width="36" height="36" alt="Guest" />

                            <div>
                                <div class="fw-bold text-white">{{ booking.guest.name }}</div>
                                <div class="text-dim fs-12">{{ booking.guest.email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="fw-semibold">Room {{ booking.room.room_number }}</div>
                        <span class="badge bg-dark border border-secondary text-dim fs-11">
                            {{ booking.room.type }}
                        </span>
                    </td>
                    <td>{{ booking.check_in_date }}</td>
                    <td>{{ booking.check_out_date }}</td>
                    <td>
                        <span class="badge-status" :class="statusClass(booking.status)">
                            {{ formatStatus(booking.status) }}
                        </span>
                    </td>
                    <td class="fw-bold text-white">${{ booking.total_amount }}</td>
                    <td class="text-end">
                        <div class="d-inline-flex gap-1">
                            <button type="button" class="btn-icon-action" title="View Details" @click="viewBooking(booking)">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button type="button" class="btn-icon-action" title="Edit" @click="editBooking(booking)">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button type="button" class="btn-icon-action danger" title="Delete" @click="deleteBooking(booking)">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr v-if="bookings.data.length === 0">
                    <td colspan="8" class="text-center">No bookings found.</td>
                </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination Controls -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 mt-4 pt-3 border-top" style="border-color: var(--app-border) !important;">
            <div class="text-dim fs-13">
                Showing
                <span class="text-white fw-bold">{{ bookings.from }}</span>
                to
                <span class="text-white fw-bold">{{ bookings.to }}</span>
                of
                <span class="text-white fw-bold">{{ bookings.total }}</span>
                entries
            </div>
            <nav>
                <ul class="pagination pagination-sm pagination-dark mb-0">
                    <li class="page-item" :class="{ disabled: bookings.current_page === 1 }">
                        <button class="page-link" @click="getBookings(bookings.current_page - 1)">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                    </li>
                    <li v-for="page in bookings.last_page" :key="page" class="page-item" :class="{ active: bookings.current_page === page }">
                        <button class="page-link" @click="getBookings(page)">
                            {{ page }}
                        </button>
                    </li>
                    <li class="page-item" :class="{ disabled: bookings.current_page === bookings.last_page }">
                        <button class="page-link" @click="getBookings(bookings.current_page + 1)">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </li>
                </ul>
            </nav>
        </div>

      </div>

      <!-- Secondary Info Section -->
      <div class="row g-4">
        
        <div class="col-12 col-lg-6">
            <div class="card-dark p-4 h-100">
                <h3 class="h6 mb-3">Room Overview & Availability</h3>

                <div class="d-flex flex-column gap-3">
                    <div>
                        <div class="d-flex justify-content-between text-muted fs-13 mb-1">
                            <span>{{ roomOverview.standard.label }} ({{ roomOverview.standard.active }} Active)</span>
                            <span class="text-white fw-bold">{{ roomOverview.standard.occupancy }}% Occupied</span>
                        </div>
                        <div class="progress bg-dark" style="height: 6px;">
                            <div class="progress-bar bg-indigo" :style="{ width: roomOverview.standard.occupancy + '%', backgroundColor: 'var(--accent-primary)' }"
                            ></div>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex justify-content-between text-muted fs-13 mb-1">
                            <span>{{ roomOverview.deluxe.label }} ({{ roomOverview.deluxe.active }} Active)</span>
                            <span class="text-white fw-bold">{{ roomOverview.deluxe.occupancy }}% Occupied</span>
                        </div>
                        <div class="progress bg-dark" style="height: 6px;">
                            <div class="progress-bar bg-cyan" :style="{ width: roomOverview.deluxe.occupancy + '%', backgroundColor: 'var(--accent-cyan)' }"
                            ></div>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex justify-content-between text-muted fs-13 mb-1">
                            <span>{{ roomOverview.suite.label }} ({{ roomOverview.suite.active }} Active)</span>
                            <span class="text-white fw-bold">{{ roomOverview.suite.occupancy }}% Occupied</span>
                        </div>
                        <div class="progress bg-dark" style="height: 6px;">
                            <div class="progress-bar bg-warning" :style="{ width: roomOverview.suite.occupancy + '%' }"
                            ></div>
                        </div>
                    </div>
                </div>
            </div></div>
        

        <div class="col-12 col-lg-6">
          <div class="card-dark p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <h3 class="h6 mb-0">Staff Access Permissions</h3>
              <button type="button" class="btn btn-outline-theme btn-sm" @click="$router.push('/staff')">
                <i class="bi bi-person-plus me-1"></i> Add Staff
              </button>
            </div>
            
            <p class="text-muted fs-13 mb-3">Administrators can grant section-level access to individual staff accounts.</p>
            
            <div class="d-flex flex-column gap-2">
              <div class="d-flex align-items-center justify-content-between p-2 rounded" style="background-color: var(--app-surface-subtle); border: 1px solid var(--app-border);">
                <div class="d-flex align-items-center gap-2">
                  <i class="bi bi-person-circle text-indigo fs-5"></i>
                  <div>
                    <div class="fw-bold text-white fs-13">John Miller (Staff)</div>
                    <div class="text-dim fs-11">john.m@appcom.com</div>
                  </div>
                </div>
                <div>
                  <span class="badge bg-dark text-success border border-success-subtle fs-11">Bookings</span>
                  <span class="badge bg-dark text-success border border-success-subtle fs-11">Rooms</span>
                </div>
              </div>

              <div class="d-flex align-items-center justify-content-between p-2 rounded" style="background-color: var(--app-surface-subtle); border: 1px solid var(--app-border);">
                <div class="d-flex align-items-center gap-2">
                  <i class="bi bi-person-circle text-cyan fs-5"></i>
                  <div>
                    <div class="fw-bold text-white fs-13">Lisa Ray (Staff)</div>
                    <div class="text-dim fs-11">lisa.r@appcom.com</div>
                  </div>
                </div>
                <div>
                  <span class="badge bg-dark text-success border border-success-subtle fs-11">Guests</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
    
<!-- ========================================== -->
<!-- MODAL: ADD NEW BOOKING                     -->
<!-- ========================================== -->
<div v-if="showCreateModal" class="modal fade show d-block" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Create New Reservation</h5>
        <button type="button" class="btn-close btn-close-white" aria-label="Close" @click="showCreateModal = false"></button>
      </div>
      <div class="modal-body">
        <form id="createBookingForm">
          
          <!-- Autocomplete Search-Based Guest Selection (Dedicated Full Width Row) -->
          <div class="row mb-3">
            <div class="col-12 position-relative">
              <label class="form-label fs-12 text-dim fw-bold">Select Guest <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person-search"></i></span>
                <input type="text" class="form-control" placeholder="Type guest name, email, or phone to search..." v-model="guestSearch" @input="searchGuests" @focus="loadGuests" @click="loadGuests">
              </div>
              
              <!-- Autocomplete Results Container -->
              <div v-if="guests.length > 0" class="autocomplete-results">
                  <div v-for="guest in guests" :key="guest.id" class="autocomplete-item" @click="selectGuest(guest)">
                      <img v-if="guest.profile_image" :src="`/storage/${guest.profile_image}`" class="rounded-circle object-fit-cover" width="36" height="36" alt="Guest"/>
                      <img v-else src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80" class="rounded-circle object-fit-cover" width="36" height="36" alt="Guest"/>
                      <div>
                          <div class="fw-bold text-white fs-13">{{ guest.name }}</div>
                          <div class="text-dim fs-11">{{ guest.email }} | {{ guest.phone }}</div>
                      </div>
                  </div>
              </div>
            </div>
          </div>

          <div class="row g-3">
            <!-- Room Selection -->
            <div class="col-12 col-md-6">
              <label class="form-label fs-12 text-dim fw-bold">Select Room <span class="text-danger">*</span></label>
              <select class="form-select" v-model="form.room_id">
                <option value="">Choose available room...</option>
                <option v-for="room in rooms" :key="room.id" :value="room.id">
                    Room {{ room.room_number }} - {{ room.type }} (${{ room.price_per_night }}/night)
                </option>
              </select>
            </div>

            <!-- Booking Status -->
            <div class="col-12 col-md-6">
              <label class="form-label fs-12 text-dim fw-bold">Initial Status</label>
              <select class="form-select" v-model="form.status">
                <option value="pending">Pending</option>
                <option value="confirmed" selected>Confirmed</option>
                <option value="checked_in">Checked-in</option>
              </select>
            </div>

            <!-- Check-In Date -->
            <div class="col-12 col-md-6">
              <label class="form-label fs-12 text-dim fw-bold">Check-In Date <span class="text-danger">*</span></label>
              <input type="date" class="form-control" v-model="form.check_in_date">
            </div>

            <!-- Check-Out Date -->
            <div class="col-12 col-md-6">
              <label class="form-label fs-12 text-dim fw-bold">Check-Out Date <span class="text-danger">*</span></label>
              <input type="date" class="form-control" v-model="form.check_out_date">
            </div>

            <!-- Total Calculated Amount Display -->
            <div class="col-12">
              <div class="p-3 rounded d-flex align-items-center justify-content-between" style="background-color: var(--app-surface-subtle); border: 1px solid var(--app-border);">
                <div>
                  <div class="text-dim fs-12">Calculated Total Amount</div>
                  <div class="fs-11 text-muted">Based on night count and room price</div>
                </div>
                <div class="fs-4 fw-bold text-success" >${{ calculatedTotal }}</div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-theme" @click="showCreateModal = false">Cancel</button>
        <button type="button" class="btn btn-accent-primary" @click="createBooking">Create Booking</button>
      </div>
    </div>
  </div>
</div>

<!-- ========================================== -->
<!-- MODAL: EDIT BOOKING                        -->
<!-- ========================================== -->
<div v-if="showEditModal && selectedBooking" class="modal fade show d-block" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Edit Booking (#BK-8041)</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="row g-3 mb-3">
            <div class="col-6">
              <label class="form-label fs-12 text-dim fw-bold">Guest</label>
              <input type="text" class="form-control" :value="selectedBooking.guest.name" disabled readonly>
            </div>
            <div class="col-6">
              <label class="form-label fs-12 text-dim fw-bold">Room</label>
              <input type="text" class="form-control" :value="'Room ' + selectedBooking.room.room_number + ' ($' + selectedBooking.room.price_per_night + '/night)'" disabled readonly>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fs-12 text-dim fw-bold">Check-In Date</label>
            <input type="date" class="form-control" :value="selectedBooking.check_in_date" disabled readonly>
          </div>

          <div class="mb-3">
            <label class="form-label fs-12 text-dim fw-bold">New Check-Out Date <span class="text-danger">*</span></label>
            <input type="date" class="form-control" v-model="selectedBooking.check_out_date">
          </div>

          <div class="p-3 rounded d-flex align-items-center justify-content-between" style="background-color: var(--app-surface-subtle); border: 1px solid var(--app-border);">
            <div>
              <div class="text-dim fs-12">Updated Total Amount</div>
              <div class="fs-11 text-muted" id="editNightsCount">4 Nights calculated</div>
            </div>
            <div class="fs-4 fw-bold text-success" >${{ selectedBooking.total_amount }}</div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-theme" @click="showEditModal = false">Cancel</button>
        <button type="button" class="btn btn-accent-primary" data-bs-dismiss="modal">Save Changes</button>
      </div>
    </div>
  </div>
</div>

<!-- ========================================== -->
<!-- MODAL: DELETE CONFIRMATION                 -->
<!-- ========================================== -->
<div v-if="showDeleteModal && selectedBooking" class="modal fade show d-block" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content text-center p-2">
      <div class="modal-body pt-4">
        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 56px; height: 56px; background-color: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3);">
          <i class="bi bi-exclamation-triangle-fill fs-4"></i>
        </div>
        <h5 class="fw-bold mb-2">Delete Booking?</h5>
        <p class="text-muted fs-13 mb-0">Are you sure you want to remove this record? This action cannot be undone.</p>
      </div>
      <div class="modal-footer justify-content-center border-0 pt-0 pb-3">
        <button type="button" class="btn btn-outline-theme px-3" @click="showDeleteModal = false">Cancel</button>
        <button type="button" class="btn btn-danger px-3" @click="confirmDelete" style="background-color: #ef4444; border: none;">Delete</button>
      </div>
    </div>
  </div>
</div>

<!-- ========================================== -->
<!-- MODAL: ADD STAFF USER                      -->
<!-- ========================================== -->
<div class="modal fade" id="addStaffModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Create Staff Account</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="mb-3">
            <label class="form-label fs-12 text-dim fw-bold">Full Name</label>
            <input type="text" class="form-control" placeholder="e.g. John Miller">
          </div>
          <div class="mb-3">
            <label class="form-label fs-12 text-dim fw-bold">Email Address</label>
            <input type="email" class="form-control" placeholder="john.m@appcom.com">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-theme" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-accent-primary" data-bs-dismiss="modal">Create Staff</button>
      </div>
    </div>
  </div>
</div>
    
<!-- ========================================== -->
<!-- MODAL: VIEW BOOKING DETAILS               -->
<!-- ========================================== -->
<div v-if="showViewModal && selectedBooking" class="modal fade show d-block" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center gap-2">
          <h5 class="modal-title fw-bold mb-0">Booking Details</h5>
          <span class="badge-status" :class="statusClass(selectedBooking.status)">
              {{ formatStatus(selectedBooking.status) }}
          </span>
        </div>
        <button type="button" class="btn-close btn-close-white" @click="showCreateModal = false"></button>
      </div>
      <div class="modal-body">
        
        <!-- Booking Ref Header -->
        <div class="p-3 rounded mb-4 d-flex flex-wrap align-items-center justify-content-between gap-3" style="background-color: var(--app-surface-subtle); border: 1px solid var(--app-border);">
          <div>
            <div class="text-dim fs-12 uppercase fw-bold">Reservation Reference</div>
            <div class="fs-4 fw-bold text-white">#BK-{{ selectedBooking.id }}</div>
          </div>
          <div class="text-end">
            <div class="text-dim fs-12 uppercase fw-bold">Total Billable Amount</div>
            <div class="fs-4 fw-bold text-success">${{ selectedBooking.total_amount }}</div>
          </div>
        </div>

        <div class="row g-4">
          <!-- Guest Information -->
          <div class="col-12 col-md-6">
            <div class="card p-3 h-100" style="background-color: var(--app-surface-subtle); border: 1px solid var(--app-border);">
              <div class="text-dim fs-12 uppercase fw-bold mb-3"><i class="bi bi-person me-1"></i> Guest Profile</div>
              <div class="d-flex align-items-center gap-3">
                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80" id="viewGuestImg" class="avatar-img" style="width:48px; height:48px;" alt="Guest">
                <div>
                  <div class="fw-bold text-white fs-15">{{ selectedBooking.guest.name }}</div>
                  <div class="text-muted fs-13">{{ selectedBooking.guest.email }}</div>
                  <div class="text-dim fs-12 mt-1" id="viewGuestPhone"><i class="bi bi-telephone me-1"></i> {{ selectedBooking.guest.phone }}</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Room & Stay Schedule -->
          <div class="col-12 col-md-6">
            <div class="card p-3 h-100" style="background-color: var(--app-surface-subtle); border: 1px solid var(--app-border);">
              <div class="text-dim fs-12 uppercase fw-bold mb-3"><i class="bi bi-door-open me-1"></i> Room & Stay Details</div>
              <div class="row g-2 fs-13">
                <div class="col-6 text-dim">Room Number:</div>
                <div class="col-6 text-white fw-semibold text-end" >Room {{ selectedBooking.room.room_number }} ({{ selectedBooking.room.type }})</div>

                <div class="col-6 text-dim">Nightly Rate:</div>
                <div class="col-6 text-white fw-semibold text-end" >${{ selectedBooking.room.price_per_night }} / night</div>

                <div class="col-6 text-dim">Check-In Date:</div>
                <div class="col-6 text-white fw-semibold text-end" >{{ selectedBooking.check_in_date }}</div>

                <div class="col-6 text-dim">Check-Out Date:</div>
                <div class="col-6 text-white fw-semibold text-end" >{{ selectedBooking.check_out_date }}</div>

                <div class="col-6 text-dim">Duration:</div>
                <div class="col-6 text-white fw-semibold text-end" id="viewNights">{{ bookingNights(selectedBooking) }} Nights</div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-theme" @click="showViewModal = false">Close</button>
        <button type="button" class="btn btn-accent-primary" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#editBookingModal">
          <i class="bi bi-pencil me-1"></i> Extend / Edit Stay
        </button>
      </div>
    </div>
  </div>
</div>

<!-- ========================================== -->
<!-- MODAL: DELETE CONFIRMATION                 -->
<!-- ========================================== -->
<div class="modal fade" id="deleteBookingModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content text-center p-2">
      <div class="modal-body pt-4">
        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 56px; height: 56px; background-color: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3);">
          <i class="bi bi-exclamation-triangle-fill fs-4"></i>
        </div>
        <h5 class="fw-bold mb-2">Delete Booking?</h5>
        <p class="text-muted fs-13 mb-0">Are you sure you want to remove this record? This action cannot be undone.</p>
      </div>
      <div class="modal-footer justify-content-center border-0 pt-0 pb-3">
        <button type="button" class="btn btn-outline-theme px-3" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger px-3" data-bs-dismiss="modal" style="background-color: #ef4444; border: none;">Delete</button>
      </div>
    </div>
  </div>
</div>

<!-- ========================================== -->
<!-- MODAL: ADD STAFF USER                      -->
<!-- ========================================== -->
<div class="modal fade" id="addStaffModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Create Staff Account</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="mb-3">
            <label class="form-label fs-12 text-dim fw-bold">Full Name</label>
            <input type="text" class="form-control" placeholder="e.g. John Miller">
          </div>
          <div class="mb-3">
            <label class="form-label fs-12 text-dim fw-bold">Email Address</label>
            <input type="email" class="form-control" placeholder="john.m@appcom.com">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-theme" @click="showCreateModal = false">Cancel</button>
        <button type="button" class="btn btn-accent-primary" data-bs-dismiss="modal">Create Staff</button>
      </div>
    </div>
  </div>
</div>

</template>

<script setup>
import { ref, reactive, onMounted, onUnmounted, inject, watch } from 'vue';
import axios from 'axios';

const setNavbarButton = inject('setNavbarButton');

const openCreateBookingModal = () => {
    showCreateModal.value = true;
};

const bookings = ref({
    data: [],
    current_page: 1,
    last_page: 1,
    total: 0,
    from: 0,
    to: 0
});

const roomOverview = ref({
    standard: {
        label: 'Standard Rooms',
        active: 0,
        occupancy: 0
    },
    deluxe: {
        label: 'Deluxe Rooms',
        active: 0,
        occupancy: 0
    },
    suite: {
        label: 'Executive Suites',
        active: 0,
        occupancy: 0
    }
});

const stats = ref({
    total_bookings: 0,
    total_guests: 0,
    total_rooms: 0,
    total_revenue: 0
});

const filters = reactive({
    search: '',
    status: '',
    room_type: '',
    from_date: '',
    to_date: ''
});

const selectedBooking = ref(null);
const showCreateModal = ref(false);
const showViewModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const rooms = ref([]);
const guests = ref([]);
const guestSearch = ref('');
const calculatedTotal = ref(0);

const form = reactive({
    guest_id: '',
    room_id: '',
    status: 'confirmed',
    check_in_date: '',
    check_out_date: ''
});

const getRoomOverview = async () => {
    const response = await axios.get('/api/room-overview');
    roomOverview.value = response.data;
};

const getStats = async () => {
    const response = await axios.get('/api/dashboard-stats');
    stats.value = response.data;
};

const getBookings = async (page = 1) => {
    const response = await axios.get('/api/bookings', {
        params: {
            page: page,
            search: filters.search,
            status: filters.status,
            room_type: filters.room_type,
            from_date: filters.from_date,
            to_date: filters.to_date
        }
    });

    bookings.value = response.data;
};

const resetFilters = () => {
    filters.search = '';
    filters.status = '';
    filters.room_type = '';
    filters.from_date = '';
    filters.to_date = '';

    getBookings();
};

const viewBooking = (booking) => {
    selectedBooking.value = booking;
    showViewModal.value = true;
};

const editBooking = (booking) => {
    selectedBooking.value = booking;
    showEditModal.value = true;
};

const deleteBooking = (booking) => {
    selectedBooking.value = booking;
    showDeleteModal.value = true;
};

const confirmDelete = async () => {
    await axios.delete('/api/bookings/' + selectedBooking.value.id);

    showDeleteModal.value = false;
    getBookings();
};

const statusClass = (status) => {
    if (status === 'pending') return 'badge-pending';
    if (status === 'confirmed') return 'badge-confirmed';
    if (status === 'checked_in') return 'badge-checkedin';
    if (status === 'checked_out') return 'badge-checkedout';
    if (status === 'cancelled') return 'badge-cancelled';

    return '';
};

const formatStatus = (status) => {
    return status.replace('_', ' ');
};

const getRooms = async () => {
    const response = await axios.get('/api/rooms');
    rooms.value = response.data.data;
};

const loadGuests = async () => {
    const response = await axios.get('/api/guests');
    guests.value = response.data.data;
};

const searchGuests = async () => {
    if (guestSearch.value.length < 2) {
        guests.value = [];
        return;
    }

    const response = await axios.get('/api/guests', {
        params: {
            search: guestSearch.value
        }
    });

    guests.value = response.data.data;
};

const selectGuest = (guest) => {
    form.guest_id = guest.id;
    guestSearch.value = guest.name + ' (' + guest.email + ')';
    guests.value = [];
};

const createBooking = async () => {
    await axios.post('/api/bookings', form);

    showCreateModal.value = false;

    form.guest_id = '';
    form.room_id = '';
    form.status = 'confirmed';
    form.check_in_date = '';
    form.check_out_date = '';
    guestSearch.value = '';
    calculatedTotal.value = 0;

    getBookings();
};

const bookingNights = (booking) => {
    const checkIn = new Date(booking.check_in_date);
    const checkOut = new Date(booking.check_out_date);

    return Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
};

const calculateTotal = () => {
    if (!form.room_id || !form.check_in_date || !form.check_out_date) {
        calculatedTotal.value = 0;
        return;
    }

    const room = rooms.value.find(room => room.id == form.room_id);

    if (!room) {
        calculatedTotal.value = 0;
        return;
    }

    const checkIn = new Date(form.check_in_date);
    const checkOut = new Date(form.check_out_date);

    const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));

    calculatedTotal.value = nights > 0 ? nights * room.price_per_night : 0;
};

watch(
    () => [form.room_id, form.check_in_date, form.check_out_date],
    calculateTotal
);

onMounted(() => {
    getBookings();
    getRooms();
    getStats();
    getRoomOverview();

    setNavbarButton(
        'New Booking',
        'bi bi-plus-lg',
        openCreateBookingModal
    );
});

onUnmounted(() => {
    setNavbarButton('', '', null);
});
  
</script>