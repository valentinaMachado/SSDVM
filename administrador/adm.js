// Seleccionar elementos
let profile = document.querySelector('.header .flex .profile');
let navbar = document.querySelector('.header .flex .navbar');

let menuBtn = document.querySelector('#menu-btn');

// Botón menú
if(menuBtn && navbar){

   menuBtn.onclick = () => {
      navbar.classList.toggle('active');

      if(profile){
         profile.classList.remove('active');
      }
   }

}

// Scroll cierra todo
window.onscroll = () => {

   if(profile){
      profile.classList.remove('active');
   }

   if(navbar){
      navbar.classList.remove('active');
   }

}


// Cambiar imagen producto
let subImages = document.querySelectorAll(
   '.update-product .image-container .sub-images img'
);

let mainImage = document.querySelector(
   '.update-product .image-container .main-image img'
);

if(subImages.length && mainImage){

   subImages.forEach(image => {

      image.onclick = () => {
         mainImage.src = image.getAttribute('src');
      }

   });

}