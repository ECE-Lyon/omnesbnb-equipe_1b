document.addEventListener("DOMContentLoaded", () => {
    const message = localStorage.getItem("popupMessage");
    if (message) {
        showPopup(message);
        localStorage.removeItem("popupMessage");
    }
});

function showPopup(message) {
    const popup = document.createElement("div");
    popup.className = "popup-message";
    popup.innerText = message;

    document.body.appendChild(popup);

    setTimeout(() => {
        popup.classList.add("visible");
    }, 100);

    setTimeout(() => {
        popup.classList.remove("visible");
        setTimeout(() => popup.remove(), 500);
    }, 4000);
}
