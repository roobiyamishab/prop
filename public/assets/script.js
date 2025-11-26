let currentUser = null;

function navigateTo(pageName) {
  const pages = document.querySelectorAll('.page');
  pages.forEach(page => page.classList.remove('active'));
  
  const targetPage = document.getElementById(`${pageName}-page`);
  if (targetPage) {
    targetPage.classList.add('active');
  }
  
  window.scrollTo(0, 0);
}


function showDashboardView(viewName) {
  const views = document.querySelectorAll('.dashboard-view');
  views.forEach(view => view.classList.remove('active'));
  
  const targetView = document.getElementById(viewName);
  if (targetView) {
    targetView.classList.add('active');
  }
  
  const sidebarItems = document.querySelectorAll('.sidebar-item');
  sidebarItems.forEach(item => item.classList.remove('active'));
  event.currentTarget.classList.add('active');
}

function handleSignup(event) {
  event.preventDefault();
  
  const name = document.getElementById('signup-name').value;
  const phone = document.getElementById('signup-phone').value;
  const email = document.getElementById('signup-email').value;
  const password = document.getElementById('signup-password').value;
  
  const userData = {
    name: name,
    phone: phone,
    email: email,
    password: password,
    createdAt: new Date().toISOString()
  };
  
  console.log('User Signup Data:', JSON.stringify(userData, null, 2));
  
  currentUser = userData;
  
  alert(`Welcome ${name}! Your account has been created successfully.`);
  
  navigateTo('dashboard');
}

function handleLogin(event) {
  event.preventDefault();
  
  const email = document.getElementById('login-email').value;
  const password = document.getElementById('login-password').value;
  
  const loginData = {
    email: email,
    password: password,
    loginTime: new Date().toISOString()
  };
  
  console.log('User Login Data:', JSON.stringify(loginData, null, 2));
  
  currentUser = { email: email };
  
  alert(`Welcome back! You've logged in successfully.`);
  
  navigateTo('dashboard');
}

function handleLogout() {
  if (confirm('Are you sure you want to logout?')) {
    currentUser = null;
    navigateTo('landing');
    alert('You have been logged out successfully.');
  }
}

function toggleAccordion(element) {
  const accordionCard = element.parentElement;
  const wasActive = accordionCard.classList.contains('active');
  
  const allAccordions = document.querySelectorAll('.accordion-card');
  allAccordions.forEach(card => card.classList.remove('active'));
  
  if (!wasActive) {
    accordionCard.classList.add('active');
  }
}

function handleFormSubmit(event, formType) {
  event.preventDefault();
  
  const form = event.target.closest('form');
  if (!form) {
    console.error('Form not found');
    return false;
  }
  
  const formData = new FormData(form);
  const data = {};
  
  for (let [key, value] of formData.entries()) {
    data[key] = value;
  }
  
  const checkboxes = form.querySelectorAll('input[type="checkbox"]:checked');
  const checkboxData = {};
  checkboxes.forEach(cb => {
    if (cb.name) {
      if (!checkboxData[cb.name]) {
        checkboxData[cb.name] = [];
      }
      checkboxData[cb.name].push(cb.value || 'checked');
    }
  });
  Object.assign(data, checkboxData);
  
  data.formType = formType;
  data.userId = currentUser ? currentUser.email : 'guest';
  data.submittedAt = new Date().toISOString();
  
  console.log(`Form Submission (${formType}):`, JSON.stringify(data, null, 2));
  
  const formTypeNames = {
    'preferred-land': 'Preferred Land',
    'preferred-building': 'Preferred Building',
    'preferred-investment': 'Preferred Investment',
    'land-sale': 'Land Sale Listing',
    'building-sale': 'Building Sale Listing',
    'investment-listing': 'Investment Opportunity'
  };
  
  alert(`${formTypeNames[formType]} has been saved successfully!\n\nCheck the browser console to see the submitted data.`);
  
  displaySampleJSON(data, formType);
  
  return false;
}

function displaySampleJSON(data, formType) {
  console.log('\n=== SAMPLE JSON OUTPUT ===');
  console.log(`Form Type: ${formType}`);
  console.log('Data Structure:');
  console.log(JSON.stringify(data, null, 2));
  console.log('========================\n');
}

function updateBudgetValue(value) {
  const budgetDisplay = document.getElementById('budget-value');
  if (budgetDisplay) {
    const formatted = new Intl.NumberFormat('en-IN', {
      style: 'currency',
      currency: 'INR',
      maximumFractionDigits: 0
    }).format(value);
    budgetDisplay.textContent = formatted;
  }
}

function updateStatusValue(value) {
  const statusDisplay = document.getElementById('status-value');
  if (statusDisplay) {
    statusDisplay.textContent = `${value}% Complete`;
  }
}

document.addEventListener('DOMContentLoaded', function() {
  console.log('AIPropMatch Platform Loaded');
  console.log('================================');
  console.log('Welcome to AIPropMatch - AI-Powered Real Estate Intelligence');
  console.log('All form submissions will be logged to this console as JSON');
  console.log('================================\n');
  
  const investmentBudgetSlider = document.getElementById('investment-budget');
  if (investmentBudgetSlider) {
    investmentBudgetSlider.addEventListener('input', function() {
      updateBudgetValue(this.value);
    });
  }
  
  const projectStatusSlider = document.getElementById('project-status');
  if (projectStatusSlider) {
    projectStatusSlider.addEventListener('input', function() {
      updateStatusValue(this.value);
    });
  }
  
  console.log('Sample JSON Structures:');
  console.log('======================\n');
  
  console.log('1. SIGNUP DATA EXAMPLE:');
  console.log(JSON.stringify({
    name: "John Doe",
    phone: "+91 9876543210",
    email: "john@example.com",
    password: "********",
    createdAt: "2024-01-01T10:00:00.000Z"
  }, null, 2));
  
  console.log('\n2. LAND SALE LISTING EXAMPLE:');
  console.log(JSON.stringify({
    formType: "land-sale",
    userId: "user@example.com",
    district: "Ernakulam",
    taluk: "Kanayannur",
    village: "Kakkanad",
    location: "Seaport-Airport Road",
    landmarks: "Near InfoPark",
    googleMapsLink: "https://maps.google.com/...",
    landArea: 15,
    unit: "Cent",
    frontage: 50,
    plotShape: "Rectangle",
    zoningType: "Residential",
    ownershipType: "Individual",
    pricePerCent: 800000,
    negotiability: "Negotiable",
    expectedAdvance: 20,
    submittedAt: "2024-01-01T10:00:00.000Z"
  }, null, 2));
  
  console.log('\n3. PREFERRED INVESTMENT EXAMPLE:');
  console.log(JSON.stringify({
    formType: "preferred-investment",
    userId: "investor@example.com",
    district: "Kochi",
    preferredLocation: "Kakkanad",
    propertyType: "Rental Buildings",
    budget: 5000000,
    profitExpectation: 18,
    submittedAt: "2024-01-01T10:00:00.000Z"
  }, null, 2));
  
  console.log('\n======================\n');
});

// Seller listings tab switcher
document.addEventListener('DOMContentLoaded', function () {
  const sellerTabs = document.querySelectorAll('.seller-tab');
  const sellerPanels = document.querySelectorAll('.seller-tab-panel');

  sellerTabs.forEach(tab => {
    tab.addEventListener('click', () => {
      const targetId = tab.getAttribute('data-target');

      // active tab
      sellerTabs.forEach(t => t.classList.remove('seller-tab-active'));
      tab.classList.add('seller-tab-active');

      // active panel
      sellerPanels.forEach(panel => {
        if (panel.id === targetId) {
          panel.classList.add('seller-tab-panel-active');
        } else {
          panel.classList.remove('seller-tab-panel-active');
        }
      });
    });
  });
});
// Buyer requirements tab switcher
document.addEventListener('DOMContentLoaded', function () {
  const buyerTabs = document.querySelectorAll('.buyer-tab');
  const buyerPanels = document.querySelectorAll('.buyer-tab-panel');

  buyerTabs.forEach(tab => {
    tab.addEventListener('click', () => {
      const targetId = tab.getAttribute('data-target');

      // active tab
      buyerTabs.forEach(t => t.classList.remove('buyer-tab-active'));
      tab.classList.add('buyer-tab-active');

      // active panel
      buyerPanels.forEach(panel => {
        if (panel.id === targetId) {
          panel.classList.add('buyer-tab-panel-active');
        } else {
          panel.classList.remove('buyer-tab-panel-active');
        }
      });
    });
  });
});

  function toggleAccordionById(id) {
    const el = document.getElementById(id);
    if (!el) return;

    // If you're hiding/showing via a class
    el.classList.toggle('is-open');

    // Scroll it into view so user sees the form
    el.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
function openEditModal(type) {
  var el = document.getElementById('edit-' + type + '-modal');
  if (el) el.style.display = 'flex';
}

function closeEditModal(type) {
  var el = document.getElementById('edit-' + type + '-modal');
  if (el) el.style.display = 'none';
}

// Optional: click outside box to close
document.addEventListener('click', function (e) {
  if (e.target.classList && e.target.classList.contains('buyer-modal-overlay')) {
    e.target.style.display = 'none';
  }
});
(function () {
  const modal     = document.getElementById('statusEditModal');
  if (!modal) return;

  const form      = document.getElementById('statusEditForm');
  const statusSel = document.getElementById('statusSelect');
  const closeBtn  = modal.querySelector('.status-modal-close');
  const cancelBtn = modal.querySelector('.status-modal-cancel');
  const backdrop  = modal.querySelector('.status-modal-backdrop');

  function openStatusModal(url, currentStatus) {
    if (!form || !statusSel) return;

    // Set target URL for PATCH
    form.action = url;

    // Normalise and pre-select status
    const normalized = (currentStatus || 'normal').toLowerCase();
    const values = Array.from(statusSel.options).map(o => o.value);
    statusSel.value = values.includes(normalized) ? normalized : 'normal';

    modal.classList.remove('hidden');
  }

  function closeStatusModal() {
    modal.classList.add('hidden');
    const note = document.getElementById('statusNote');
    if (note) note.value = '';
  }

  // Attach listeners to "Edit status" buttons
  document.querySelectorAll('.js-edit-status-btn').forEach(btn => {
    btn.addEventListener('click', function () {
      const url = this.dataset.url;
      const currentStatus = this.dataset.currentStatus || 'normal';
      if (!url) return;
      openStatusModal(url, currentStatus);
    });
  });

  // Close actions
  if (closeBtn) {
    closeBtn.addEventListener('click', closeStatusModal);
  }
  if (cancelBtn) {
    cancelBtn.addEventListener('click', closeStatusModal);
  }
  if (backdrop) {
    backdrop.addEventListener('click', closeStatusModal);
  }

  // ESC key closes modal
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
      closeStatusModal();
    }
  });
})();
 (function () {
    // ----- STATUS MODAL -----
    const statusModal     = document.getElementById('statusEditModal');
    const statusForm      = document.getElementById('statusEditForm');
    const statusSel       = document.getElementById('statusSelect');
    const statusCloseBtn  = statusModal?.querySelector('.status-modal-close');
    const statusCancelBtn = statusModal?.querySelector('.status-modal-cancel');
    const statusBackdrop  = statusModal?.querySelector('.status-modal-backdrop');

    function openStatusModal(url, currentStatus) {
      if (!statusModal || !statusForm || !statusSel) return;
      statusForm.action = url;
      const normalized = (currentStatus || 'normal').toLowerCase();
      const values = Array.from(statusSel.options).map(o => o.value);
      statusSel.value = values.includes(normalized) ? normalized : 'normal';
      statusModal.classList.remove('hidden');
    }

    function closeStatusModal() {
      if (!statusModal) return;
      statusModal.classList.add('hidden');
      const note = document.getElementById('statusNote');
      if (note) note.value = '';
    }

    document.querySelectorAll('.js-edit-status-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        const url = this.dataset.url;
        const currentStatus = this.dataset.currentStatus || 'normal';
        if (!url) return;
        openStatusModal(url, currentStatus);
      });
    });

    statusCloseBtn?.addEventListener('click', closeStatusModal);
    statusCancelBtn?.addEventListener('click', closeStatusModal);
    statusBackdrop?.addEventListener('click', closeStatusModal);

    // ----- DETAILS MODALS (per listing) -----
    function openDetailsModal(modalId) {
      const m = document.getElementById(modalId);
      if (!m) return;
      m.classList.remove('hidden');
    }

    function closeDetailsModal(modal) {
      modal.classList.add('hidden');
    }

    document.querySelectorAll('.js-edit-details-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        const modalId = this.dataset.modalId;
        if (!modalId) return;
        openDetailsModal(modalId);
      });
    });

    // Delegate close events
    document.querySelectorAll('.details-modal').forEach(modal => {
      const closeBtn  = modal.querySelector('.details-modal-close');
      const cancelBtn = modal.querySelector('.details-modal-cancel');
      const backdrop  = modal.querySelector('.details-modal-backdrop');

      closeBtn?.addEventListener('click', () => closeDetailsModal(modal));
      cancelBtn?.addEventListener('click', () => closeDetailsModal(modal));
      backdrop?.addEventListener('click', () => closeDetailsModal(modal));
    });

    // Global ESC to close any open modal
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        if (statusModal && !statusModal.classList.contains('hidden')) {
          closeStatusModal();
        }
        document.querySelectorAll('.details-modal').forEach(modal => {
          if (!modal.classList.contains('hidden')) {
            closeDetailsModal(modal);
          }
        });
      }
    });
  })();

