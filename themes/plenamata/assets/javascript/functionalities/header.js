document.addEventListener("DOMContentLoaded", function() {
    document.querySelector('button.toggle-menu').addEventListener('click', function() {
        this.classList.toggle('active');
        this.parentNode.classList.toggle('active');
        this.closest('.main-header').classList.toggle('active');
    })

    const header = document.querySelector('.main-header');

    document.addEventListener('scroll', function(e) {
        const top = window.scrollY;

        if(top >  78) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }

    });
})