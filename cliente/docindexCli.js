const navLinks = document.querySelectorAll(".menudenavegacion .linknav");
const menuOpenButton = document.querySelector("#menu-open-button");
const menuCloseButton = document.querySelector("#menu-close-button");

menuOpenButton.addEventListener("click", () => {
 //activar o desactivar la visibilidad movil
    document.body.classList.toggle("show-mobile-menu");
});

//cerrar el menú cuando se hace clic en el botón cerrar
menuCloseButton.addEventListener("click", () => menuOpenButton.click());

//cerrar el menú cuando se hace clic en los links de navegacion//
navLinks.forEach(link  => {
    link.addEventListener("click", ()  => menuOpenButton.click());
});

const swiper = new Swiper(".slider-wrapper", {
    
    loop:true, 
    grabCursor: true,
    spaceBetween: 25,

    /* paginacion*/
    pagination: {
        el: ".swiper-pagination", 
        clickable: true,
        dynamicBullets: true,
    },

    /*flechas de navegación*/
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    /*puntos de interrupción responsivos*/
    breakpoints: {
        0: {
            slidesPerView: 1
        },
        768:{
            slidesPerView: 2
        },
        1024:{
            slidesPerView: 3
        }
    }
});


