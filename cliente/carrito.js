
let carrito = JSON.parse(localStorage.getItem("carrito")) || [];

function agregarAlCarrito(nombre, precio, imagen, id_producto) {
  const existente = carrito.find(p => p.nombre === nombre);
  if (existente) {
    existente.cantidad++;
  } else {
    carrito.push({ nombre, precio, imagen, cantidad: 1, id_producto });
  }
  localStorage.setItem("carrito", JSON.stringify(carrito));
  renderizarCarrito();


  fetch('carrito.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      ID_Producto: id_producto,
      Nombre: nombre,
      Precio: precio,
      Cantidad: 1
    })
  })
  .then(res => res.json())
  .then(data => {
    if (!data.success) {
      console.error("❌ Error BD:", data.message);
    } else {
      console.log(" ✅ Guardado correctamente");
    }
  })  
  .catch(e => console.error("❌ Error Fetch:", e));
}
  


function eliminarDelCarrito(index) {
  carrito.splice(index, 1);
  localStorage.setItem("carrito", JSON.stringify(carrito));
  renderizarCarrito();
}

function renderizarCarrito() {
  const cont = document.querySelector(".shopping-cart");
  cont.innerHTML = "";
  carrito = JSON.parse(localStorage.getItem("carrito")) || [];
  let total = 0;

  carrito.forEach((item, i) => {
    total += item.precio * item.cantidad;
    cont.innerHTML += `
      <div class="box">
        <i class="fas fa-trash" onclick="eliminarDelCarrito(${i})"></i>
        <img src="${item.imagen}">
        <div class="contenido">
          <h3>${item.nombre}</h3>
          <span class="precio">$${item.precio.toLocaleString()} - </span>
          <span class="cantidad">cant: ${item.cantidad}</span>
        </div>
      </div>
    `;
  });

  if (carrito.length > 0) {
    cont.innerHTML += `<div class="total">Total: $${total.toLocaleString()}</div>`;
    cont.innerHTML += `<a href="ordenCli.html" class="btn">ORDENAR</a>`;
  }
}

document.addEventListener("DOMContentLoaded", renderizarCarrito);