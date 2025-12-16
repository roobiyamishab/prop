let currentUser = null;

/* ========== PAGE NAVIGATION ========== */

function navigateTo(pageName) {
  const pages = document.querySelectorAll('.page');
  pages.forEach(page => page.classList.remove('active'));

  const targetPage = document.getElementById(`${pageName}-page`);
  if (targetPage) targetPage.classList.add('active');

  window.scrollTo(0, 0);
}

function showDashboardView(viewName, evt) {
  const views = document.querySelectorAll('.dashboard-view');
  views.forEach(view => view.classList.remove('active'));

  const targetView = document.getElementById(viewName);
  if (targetView) targetView.classList.add('active');

  const sidebarItems = document.querySelectorAll('.sidebar-item');
  sidebarItems.forEach(item => item.classList.remove('active'));

  const trigger = evt?.currentTarget || (typeof event !== 'undefined' ? event.currentTarget : null);
  if (trigger) trigger.classList.add('active');
}

/* ========== AUTH HANDLERS ========== */

function handleSignup(event) {
  event.preventDefault();

  const name = document.getElementById('signup-name')?.value || '';
  const phone = document.getElementById('signup-phone')?.value || '';
  const email = document.getElementById('signup-email')?.value || '';
  const password = document.getElementById('signup-password')?.value || '';

  const userData = {
    name,
    phone,
    email,
    password,
    createdAt: new Date().toISOString(),
  };

  console.log('User Signup Data:', JSON.stringify(userData, null, 2));
  currentUser = userData;

  alert(`Welcome ${name}! Your account has been created successfully.`);
  navigateTo('dashboard');
}

function handleLogin(event) {
  event.preventDefault();

  const email = document.getElementById('login-email')?.value || '';
  const password = document.getElementById('login-password')?.value || '';

  const loginData = {
    email,
    password,
    loginTime: new Date().toISOString(),
  };

  console.log('User Login Data:', JSON.stringify(loginData, null, 2));
  currentUser = { email };

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

/* ========== ACCORDIONS ========== */

function toggleAccordion(element) {
  const accordionCard = element.parentElement;
  const wasActive = accordionCard.classList.contains('active');

  const allAccordions = document.querySelectorAll('.accordion-card');
  allAccordions.forEach(card => card.classList.remove('active'));

  if (!wasActive) accordionCard.classList.add('active');
}

function toggleAccordionById(id) {
  const el = document.getElementById(id);
  if (!el) return;

  el.classList.toggle('is-open');
  el.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

/* ========== GENERIC FORM HANDLER (LOG TO CONSOLE) ========== */

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

  // Collect checkbox groups into arrays
  const checkboxes = form.querySelectorAll('input[type="checkbox"]:checked');
  const checkboxData = {};
  checkboxes.forEach(cb => {
    if (!cb.name) return;
    if (!checkboxData[cb.name]) checkboxData[cb.name] = [];
    checkboxData[cb.name].push(cb.value || 'checked');
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
    'investment-listing': 'Investment Opportunity',
  };

  alert(
    `${formTypeNames[formType] || 'Form'} has been saved successfully!\n\nCheck the browser console to see the submitted data.`
  );

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

/* ========== SLIDER HELPERS ========== */

function updateBudgetValue(value) {
  const budgetDisplay = document.getElementById('budget-value');
  if (!budgetDisplay) return;

  const formatted = new Intl.NumberFormat('en-IN', {
    style: 'currency',
    currency: 'INR',
    maximumFractionDigits: 0,
  }).format(value);

  budgetDisplay.textContent = formatted;
}

function updateStatusValue(value) {
  const statusDisplay = document.getElementById('status-value');
  if (statusDisplay) statusDisplay.textContent = `${value}% Complete`;
}

/* ========== EDIT MODALS (BUYER + SELLER) ========== */
/**
 * This supports both:
 *  - openEditModal('land')  -> #edit-land-modal
 *  - openEditModal('edit-building-modal-12') -> direct id
 */
function openEditModal(typeOrId) {
  const byType = document.getElementById(`edit-${typeOrId}-modal`);
  const byId = document.getElementById(typeOrId);
  const el = byType || byId;
  if (!el) return;

  el.classList.remove('hidden');
  el.style.display = 'flex';
}

function closeEditModal(typeOrId) {
  const byType = document.getElementById(`edit-${typeOrId}-modal`);
  const byId = document.getElementById(typeOrId);
  const el = byType || byId;
  if (!el) return;

  el.style.display = 'none';
  el.classList.add('hidden');
}

/* ========== DOM READY ========== */

document.addEventListener('DOMContentLoaded', function () {
  console.log('AIPropMatch Platform Loaded');
  console.log('================================');
  console.log('Welcome to AIPropMatch - AI-Powered Real Estate Intelligence');
  console.log('All form submissions will be logged to this console as JSON');
  console.log('================================\n');

  /* --- Sliders --- */
  const investmentBudgetSlider = document.getElementById('investment-budget');
  if (investmentBudgetSlider) {
    investmentBudgetSlider.addEventListener('input', function () {
      updateBudgetValue(this.value);
    });
  }

  const projectStatusSlider = document.getElementById('project-status');
  if (projectStatusSlider) {
    projectStatusSlider.addEventListener('input', function () {
      updateStatusValue(this.value);
    });
  }

  /* --- Sample JSON examples (for console only) --- */
  console.log('Sample JSON Structures:');
  console.log('======================\n');

  console.log('1. SIGNUP DATA EXAMPLE:');
  console.log(
    JSON.stringify(
      {
        name: 'John Doe',
        phone: '+91 9876543210',
        email: 'john@example.com',
        password: '********',
        createdAt: '2024-01-01T10:00:00.000Z',
      },
      null,
      2
    )
  );

  console.log('\n2. LAND SALE LISTING EXAMPLE:');
  console.log(
    JSON.stringify(
      {
        formType: 'land-sale',
        userId: 'user@example.com',
        district: 'Ernakulam',
        taluk: 'Kanayannur',
        village: 'Kakkanad',
        location: 'Seaport-Airport Road',
        landmarks: 'Near InfoPark',
        googleMapsLink: 'https://maps.google.com/...',
        landArea: 15,
        unit: 'Cent',
        frontage: 50,
        plotShape: 'Rectangle',
        zoningType: 'Residential',
        ownershipType: 'Individual',
        pricePerCent: 800000,
        negotiability: 'Negotiable',
        expectedAdvance: 20,
        submittedAt: '2024-01-01T10:00:00.000Z',
      },
      null,
      2
    )
  );

  console.log('\n3. PREFERRED INVESTMENT EXAMPLE:');
  console.log(
    JSON.stringify(
      {
        formType: 'preferred-investment',
        userId: 'investor@example.com',
        district: 'Kochi',
        preferredLocation: 'Kakkanad',
        propertyType: 'Rental Buildings',
        budget: 5000000,
        profitExpectation: 18,
        submittedAt: '2024-01-01T10:00:00.000Z',
      },
      null,
      2
    )
  );

  console.log('\n======================\n');

  /* --- Seller listings tabs --- */
  const sellerTabs = document.querySelectorAll('.seller-tab');
  const sellerPanels = document.querySelectorAll('.seller-tab-panel');

  sellerTabs.forEach(tab => {
    tab.addEventListener('click', () => {
      const targetId = tab.getAttribute('data-target');

      sellerTabs.forEach(t => t.classList.remove('seller-tab-active'));
      tab.classList.add('seller-tab-active');

      sellerPanels.forEach(panel => {
        panel.id === targetId
          ? panel.classList.add('seller-tab-panel-active')
          : panel.classList.remove('seller-tab-panel-active');
      });
    });
  });

  /* --- Buyer requirement tabs --- */
  const buyerTabs = document.querySelectorAll('.buyer-tab');
  const buyerPanels = document.querySelectorAll('.buyer-tab-panel');

  buyerTabs.forEach(tab => {
    tab.addEventListener('click', () => {
      const targetId = tab.getAttribute('data-target');

      buyerTabs.forEach(t => t.classList.remove('buyer-tab-active'));
      tab.classList.add('buyer-tab-active');

      buyerPanels.forEach(panel => {
        panel.id === targetId
          ? panel.classList.add('buyer-tab-panel-active')
          : panel.classList.remove('buyer-tab-panel-active');
      });
    });
  });

  /* --- Close buyer modals when clicking overlay --- */
  document.addEventListener('click', function (e) {
    if (e.target.classList && e.target.classList.contains('buyer-modal-overlay')) {
      e.target.style.display = 'none';
      e.target.classList.add('hidden');
    }
  });

  /* ========== STATUS MODAL (LAND / BUILDING REQUEST STATUS) ========== */

  const statusModal = document.getElementById('statusEditModal');
  if (statusModal) {
    const statusForm = document.getElementById('statusEditForm');
    const statusSel = document.getElementById('statusSelect');
    const statusCloseBtn = statusModal.querySelector('.status-modal-close');
    const statusCancelBtn = statusModal.querySelector('.status-modal-cancel');
    const statusBackdrop = statusModal.querySelector('.status-modal-backdrop');

    function openStatusModal(url, currentStatus) {
      if (!statusForm || !statusSel) return;

      statusForm.action = url;
      const normalized = (currentStatus || 'normal').toLowerCase();
      const values = Array.from(statusSel.options).map(o => o.value);
      statusSel.value = values.includes(normalized) ? normalized : 'normal';

      statusModal.classList.remove('hidden');
      statusModal.style.display = 'flex';
    }

    function closeStatusModal() {
      statusModal.style.display = 'none';
      statusModal.classList.add('hidden');
      const note = document.getElementById('statusNote');
      if (note) note.value = '';
    }

    // Attach to buttons
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

    // ESC closes status modal as well
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && statusModal.style.display === 'flex') {
        closeStatusModal();
      }
    });
  }

  /* ========== DETAILS MODALS (PER-LISTING, e.g., EDIT BUILDING) ========== */

  function closeDetailsModal(modal) {
    modal.style.display = 'none';
    modal.classList.add('hidden');
  }

  // Open from "Edit" buttons
  document.querySelectorAll('.js-edit-details-btn').forEach(btn => {
    btn.addEventListener('click', function () {
      const modalId = this.dataset.modalId;
      if (!modalId) return;

      const modal = document.getElementById(modalId);
      if (!modal) return;

      modal.classList.remove('hidden');
      modal.style.display = 'flex';
    });
  });

  // Close via close / cancel / backdrop
  document.querySelectorAll('.details-modal').forEach(modal => {
    const closeBtn = modal.querySelector('.details-modal-close');
    const cancelBtn = modal.querySelector('.details-modal-cancel');
    const backdrop = modal.querySelector('.details-modal-backdrop');

    closeBtn?.addEventListener('click', () => closeDetailsModal(modal));
    cancelBtn?.addEventListener('click', () => closeDetailsModal(modal));
    backdrop?.addEventListener('click', () => closeDetailsModal(modal));
  });

  // ESC closes any open details modal
  document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape') return;
    document.querySelectorAll('.details-modal').forEach(modal => {
      if (modal.style.display === 'flex') {
        closeDetailsModal(modal);
      }
    });
  });
});
