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
