document.addEventListener("DOMContentLoaded", function() {
    document.querySelector('button.toggle-menu').addEventListener('click', function() {
        this.classList.toggle('active');
        this.parentNode.classList.toggle('active');
        this.closest('.main-header').classList.toggle('active');
    })

    const header = document.querySelector('.main-header');

    const handler = (scroll, header) => {
        if(scroll >  78) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    }

    handler(window.scrollY, header);

    document.addEventListener('scroll', function(e) {
        const top = window.scrollY;
        handler(top, header);
    });
})