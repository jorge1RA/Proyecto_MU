/**
 *
 * funciones.js
 * wwwwwwwwwwwwwwwwww
 * Jorge Romero Ariza
 *
 */

/**
 * Código para manejar la visibilidad de los datos de tarjeta según el método de pago seleccionado.
 *
 * Al cargar la página, este script muestra u oculta los campos de datos de tarjeta
 * en función del método de pago seleccionado, y gestiona cambios posteriores.
 */
document.addEventListener("DOMContentLoaded", function () {
  console.log("JavaScript cargado");

  /**
   * Referencia al selector de método de pago y al contenedor de datos de tarjeta.
   */
  var metodoPagoSelect = document.getElementById("metodo_pago");
  var datosTarjetaDiv = document.getElementById("datos_tarjeta");

  /**
   * Maneja el cambio del método de pago.
   */
  metodoPagoSelect.addEventListener("change", function () {
    console.log("Método de pago cambiado a: " + this.value);
    datosTarjetaDiv.style.display = this.value === "tarjeta" ? "block" : "none";
  });

  /**
   * Muestra u oculta los datos de tarjeta al cargar la página.
   */
  datosTarjetaDiv.style.display =
    metodoPagoSelect.value === "tarjeta" ? "block" : "none";
});
