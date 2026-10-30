  // Collapsible Sidebar Toggle
  document.getElementById('sidebarToggle').addEventListener('click', function() {
    document.getElementById('appSidebar').classList.toggle('collapsed');
  });

  // Autocomplete Search Behavior for Modal
  const guestSearchInput = document.getElementById('guestSearchInput');
  const guestSearchResults = document.getElementById('guestSearchResults');

  if (guestSearchInput && guestSearchResults) {
    guestSearchInput.addEventListener('focus', function() {
      guestSearchResults.classList.remove('d-none');
    });

    guestSearchInput.addEventListener('input', function() {
      guestSearchResults.classList.remove('d-none');
    });

    document.addEventListener('click', function(e) {
      if (!guestSearchInput.contains(e.target) && !guestSearchResults.contains(e.target)) {
        guestSearchResults.classList.add('d-none');
      }
    });
  }

  function selectGuest(guestName) {
    if (guestSearchInput && guestSearchResults) {
      guestSearchInput.value = guestName;
      guestSearchResults.classList.add('d-none');
    }
  }

  // Edit Modal Check-out Date Recalculation
  const editCheckInInput = document.getElementById('editCheckInDate');
  const editCheckOutInput = document.getElementById('editCheckOutDate');
  const editCalculatedTotal = document.getElementById('editCalculatedTotal');
  const editNightsCount = document.getElementById('editNightsCount');
  const roomNightlyRate = 300;

  if (editCheckOutInput) {
    editCheckOutInput.addEventListener('change', function() {
      const startDate = new Date(editCheckInInput.value);
      const endDate = new Date(editCheckOutInput.value);

      if (startDate && endDate && endDate > startDate) {
        const diffDays = Math.ceil(Math.abs(endDate - startDate) / (1000 * 60 * 60 * 24));
        const total = diffDays * roomNightlyRate;

        editNightsCount.textContent = `${diffDays} Night${diffDays > 1 ? 's' : ''} calculated`;
        editCalculatedTotal.textContent = `$${total.toLocaleString('en-US', { minimumFractionDigits: 2 })}`;
      } else {
        editNightsCount.textContent = 'Invalid date range';
        editCalculatedTotal.textContent = '$0.00';
      }
    });
  }