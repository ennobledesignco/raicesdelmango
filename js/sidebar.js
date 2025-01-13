document.querySelectorAll('.sidebar ul > li > a').forEach(menu => {
    menu.addEventListener('click', function (e) {
        const submenu = this.nextElementSibling;
        if (submenu && submenu.classList.contains('submenu')) {
            e.preventDefault();
            submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
        }
    });
});
