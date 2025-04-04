document.getElementById('current-year').textContent = new Date().getFullYear();

const mobileMenuButton = document.getElementById('mobile-menu-button');
const mobileMenuIcon = document.getElementById('mobile-menu-icon');
const mobileMenu = document.getElementById('mobile-menu');

mobileMenuButton.addEventListener('click', () => {
  const isOpen = mobileMenu.classList.toggle('hidden');
  mobileMenuIcon.innerHTML = isOpen ? 
    '<line x1="4" x2="20" y1="12" y2="12"></line><line x1="4" x2="20" y1="6" y2="6"></line><line x1="4" x2="20" y1="18" y2="18"></line>' : 
    '<path d="M18 6 6 18"></path><path d="m6 6 12 12"></path>';
});

const userDropdownTrigger = document.getElementById('user-dropdown-trigger');
const userDropdownMenu = document.getElementById('user-dropdown-menu');

userDropdownTrigger.addEventListener('click', () => {
  userDropdownMenu.classList.toggle('hidden');
});

document.addEventListener('click', (event) => {
  const container = document.getElementById('user-dropdown-container');
  if (!container.contains(event.target)) {
    userDropdownMenu.classList.add('hidden');
  }
});

const sortDropdownTrigger = document.getElementById('sort-dropdown-trigger');
const sortDropdownMenu = document.getElementById('sort-dropdown-menu');

sortDropdownTrigger.addEventListener('click', () => {
  sortDropdownMenu.classList.toggle('hidden');
});

document.addEventListener('click', (event) => {
  const container = document.getElementById('sort-dropdown-container');
  if (container && !container.contains(event.target)) {
    sortDropdownMenu.classList.add('hidden');
  }
});

const sortDropdownItems = document.querySelectorAll('#sort-dropdown-menu a');
sortDropdownItems.forEach(item => {
  item.addEventListener('click', (e) => {
    e.preventDefault();
    const value = item.getAttribute('data-value');
    const text = item.textContent;
    sortDropdownTrigger.querySelector('span').textContent = text;
    sortDropdownMenu.classList.add('hidden');

  });
});

const filtersToggle = document.getElementById('filters-toggle');
const filtersPanel = document.getElementById('filters-panel');

filtersToggle.addEventListener('click', () => {
  filtersPanel.classList.toggle('hidden');
});

const tabs = document.querySelectorAll('#tabs button');
const tabContents = document.querySelectorAll('[id^="tab-content-"]');

tabs.forEach(tab => {
  tab.addEventListener('click', () => {
    tabs.forEach(t => {
      t.classList.remove('border-primary', 'text-primary');
      t.classList.add('border-transparent', 'hover:text-primary', 'hover:border-primary/40');
    });
    
    tab.classList.add('border-primary', 'text-primary');
    tab.classList.remove('border-transparent', 'hover:text-primary', 'hover:border-primary/40');
    
    tabContents.forEach(content => {
      content.classList.add('hidden');
    });
    
    const tabId = tab.getAttribute('data-tab');
    document.getElementById(`tab-content-${tabId}`).classList.remove('hidden');
  });
});