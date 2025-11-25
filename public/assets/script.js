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


