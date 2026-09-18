document.querySelectorAll('[data-dropdown]').forEach((dropdown) => {
    const btn = dropdown.querySelector('[data-dropdown-btn]');
    const menu = dropdown.querySelector('[data-dropdown-menu]');
    const icon = dropdown.querySelector('[data-dropdown-icon]');
    const label = dropdown.querySelector('[data-dropdown-label]');

    btn.addEventListener('click', (e) => {
        e.stopPropagation();
        const isOpen = !menu.classList.contains('hidden');

        document.querySelectorAll('[data-dropdown-menu]').forEach((m) => m.classList.add('hidden'));
        document.querySelectorAll('[data-dropdown-icon]').forEach((i) => i.classList.remove('rotate-180'));
        document.querySelectorAll('[data-dropdown-btn]').forEach((b) => b.setAttribute('aria-expanded', 'false'));

        if (!isOpen) {
            menu.classList.remove('hidden');
            icon.classList.add('rotate-180');
            btn.setAttribute('aria-expanded', 'true');
        }
    });

    menu.querySelectorAll('a').forEach((item) => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            label.textContent = item.textContent.trim();
            menu.classList.add('hidden');
            icon.classList.remove('rotate-180');
            btn.setAttribute('aria-expanded', 'false');
        });
    });
});

document.addEventListener('click', () => {
    document.querySelectorAll('[data-dropdown-menu]').forEach((m) => m.classList.add('hidden'));
    document.querySelectorAll('[data-dropdown-icon]').forEach((i) => i.classList.remove('rotate-180'));
    document.querySelectorAll('[data-dropdown-btn]').forEach((b) => b.setAttribute('aria-expanded', 'false'));
});

const menuBtn = document.querySelector('[data-menu-btn]');
const navList = document.querySelector('[data-nav-list]');

if (menuBtn && navList) {
    menuBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        const isOpen = navList.classList.toggle('is-open');
        menuBtn.classList.toggle('is-open', isOpen);
        menuBtn.setAttribute('aria-expanded', String(isOpen));
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            navList.classList.remove('is-open');
            menuBtn.classList.remove('is-open');
            menuBtn.setAttribute('aria-expanded', 'false');
        }
    });
}
