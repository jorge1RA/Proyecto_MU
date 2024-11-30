// js/funciones.js

document.addEventListener("DOMContentLoaded", function () {
  console.log("JavaScript cargado");
  var metodoPagoSelect = document.getElementById("metodo_pago");
  var datosTarjetaDiv = document.getElementById("datos_tarjeta");

  metodoPagoSelect.addEventListener("change", function () {
    console.log("Método de pago cambiado a: " + this.value);
    if (this.value === "tarjeta") {
      datosTarjetaDiv.style.display = "block";
    } else {
      datosTarjetaDiv.style.display = "none";
    }
  });

  // Mostrar u ocultar al cargar la página
  if (metodoPagoSelect.value === "tarjeta") {
    datosTarjetaDiv.style.display = "block";
  } else {
    datosTarjetaDiv.style.display = "none";
  }
});
