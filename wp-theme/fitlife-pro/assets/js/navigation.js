/**
 * FitLife Pro accessible navigation script
 */
document.addEventListener('DOMContentLoaded', () => {
    // Mobile navigation toggle
    const toggleButton = document.querySelector('.mobile-nav-toggle');
    const primaryNav = document.querySelector('.primary-nav');

    if (toggleButton && primaryNav) {
        toggleButton.addEventListener('click', () => {
            const isExpanded = toggleButton.getAttribute('aria-expanded') === 'true';
            toggleButton.setAttribute('aria-expanded', !isExpanded);
            primaryNav.classList.toggle('active');
        });
    }

    // Keyboard and hover accessibility for dropdown submenus
    const menuItems = document.querySelectorAll('.primary-nav-ul > li.menu-item-has-children');

    menuItems.forEach(item => {
        const link = item.querySelector('a');
        const button = item.querySelector('.dropdown-toggle-btn');
        const subMenu = item.querySelector('.sub-menu');

        const toggleDropdown = (show) => {
            if (show) {
                link.setAttribute('aria-expanded', 'true');
                if (button) button.setAttribute('aria-expanded', 'true');
                subMenu.classList.add('is-open');
            } else {
                link.setAttribute('aria-expanded', 'false');
                if (button) button.setAttribute('aria-expanded', 'false');
                subMenu.classList.remove('is-open');
            }
        };

        // Hover handling
        item.addEventListener('mouseenter', () => toggleDropdown(true));
        item.addEventListener('mouseleave', () => toggleDropdown(false));

        // Keyboard navigation inside dropdowns
        item.addEventListener('focuswithin', () => toggleDropdown(true));
        
        // Listen to Escape key to close the menu
        item.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                toggleDropdown(false);
                link.focus();
            }
        });

        // Toggle submenu with screen reader buttons
        if (button) {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const isExpanded = button.getAttribute('aria-expanded') === 'true';
                toggleDropdown(!isExpanded);
            });
        }
    });

    // Handle skip-to-content focus targeting
    const skipLink = document.querySelector('.skip-link');
    if (skipLink) {
        skipLink.addEventListener('click', (e) => {
            const targetId = skipLink.getAttribute('href');
            const target = document.querySelector(targetId);
            if (target) {
                target.setAttribute('tabindex', '-1');
                target.focus();
            }
        });
    }
});
