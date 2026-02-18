document.addEventListener("DOMContentLoaded", function(){

    let shoppingCart = document.querySelector(".shopping-cart");
    let cartBtn = document.querySelector("#cart-btn");

    let barradenavegacion = document.querySelector(".barradenavegacion");
    let menuBtn = document.querySelector("#menu-btn");


    // CARRITO
    if(cartBtn){
        cartBtn.addEventListener("click", function(){
            shoppingCart.classList.toggle("active");
            barradenavegacion.classList.remove("active");
        });
    }


    // MENU
    if(menuBtn){
        menuBtn.addEventListener("click", function(){
            barradenavegacion.classList.toggle("active");
            shoppingCart.classList.remove("active");
        });
    }


    // SCROLL
    window.addEventListener("scroll", function(){
        shoppingCart.classList.remove("active");
        barradenavegacion.classList.remove("active");
    });


    // SWIPER COMIDAS
    new Swiper(".comidasslider", {
        loop:true,
        spaceBetween: 20,
        autoplay:{
            delay:7500,
            disableOnInteraction: false,
        },
        centeredSlides: true, 
        breakpoints: {
            0: { slidesPerView: 1 },
            768:{ slidesPerView: 2 },
            1020:{ slidesPerView: 3 },
        },
    });


    // SWIPER BEBIDAS
    new Swiper(".bebidasslider", {
        loop:true,
        spaceBetween: 20,
        autoplay:{
            delay:7500,
            disableOnInteraction: false,
        },
        centeredSlides: true, 
        breakpoints: {
            0: { slidesPerView: 1 },
            768:{ slidesPerView: 2 },
            1020:{ slidesPerView: 3 },
        },
    });

});