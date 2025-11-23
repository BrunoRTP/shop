const menu = document.getElementById("botonMenu");
const sideMenu = document.getElementById("menuLateral");
const closeMenu = document.getElementById("cerrarMenu");
const menuOverlay = document.getElementById("menuOverlay");

menu.addEventListener("click", () => {
    sideMenu.classList.toggle("active");
    menuOverlay.classList.toggle("active");
})

closeMenu.addEventListener("click", () => {
    sideMenu.classList.toggle("active");
    menuOverlay.classList.toggle("active");
})

menuOverlay.addEventListener("click", () => {
    sideMenu.classList.toggle("active");
    menuOverlay.classList.toggle("active");
})