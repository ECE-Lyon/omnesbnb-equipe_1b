
function toggleFilters() {
    const filterSection = document.getElementById("filtres");
    filterSection.classList.toggle("hidden");
}

document.getElementById('menuToggle').addEventListener('click', function() {
    const menu = document.getElementById('dropdownMenu');
    menu.classList.toggle('visible');
});