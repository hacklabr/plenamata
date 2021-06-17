document.addEventListener("DOMContentLoaded", function() {
    document.querySelector('button.toggle-menu').addEventListener('click', function() {
        this.classList.toggle('active');
        this.parentNode.classList.toggle('active');
        this.closest('.main-header').classList.toggle('active');
    })
})