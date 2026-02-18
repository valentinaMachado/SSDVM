const contenedor = document.querySelector(".contenedor");
const btnSignIn = document.getElementById("btn-sign-in");
const btnSignUp = document.getElementById("btn-sign-up");

btnSignIn.addEventListener("click",() =>{
    contenedor.classList.remove("toggle");

});

btnSignUp.addEventListener("click",() =>{
    contenedor.classList.add("toggle");

});



