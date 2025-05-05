import './bootstrap';

// Mobile Menu
const menu_btn = document.querySelectorAll('[data-menu-btn]');
menu_btn.forEach(btn => btn.addEventListener('click', menuEvent));

function menuEvent(e) {
    const btn = e.target.closest('[data-menu-btn]');
    const menu = document.querySelector(`[data-${btn.dataset.menuTarget}]`);
    const open = document.querySelector('.open');

    if (open !== null && open !== btn) {
        const target = document.querySelector(`[data-${open.dataset.menuTarget}]`)

        open.classList.toggle('open');
        target.classList.toggle('-translate-y-[40rem]');
    }

    btn.classList.toggle('open');
    menu.classList.toggle('-translate-y-[40rem]');
}
