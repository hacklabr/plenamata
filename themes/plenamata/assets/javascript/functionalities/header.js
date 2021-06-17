document.addEventListener("DOMContentLoaded", function() {
    document.querySelector('button.toggle-menu').addEventListener('click', function() {
        this.classList.toggle('active');
    })
})