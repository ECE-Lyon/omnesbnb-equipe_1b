$(document).ready (function (){

    document.getElementById('menuToggle').addEventListener('click', function() {
        const menu = document.getElementById('dropdownMenu');
        menu.classList.toggle('visible');
    });

});