Fase_10
-------
En esta actualización, se ha añadido un script en el index.php - pedidos (de forma similar a index.php - productos) para crear un menú desplegable denominado "selecciona un producto". Su funcionalidad se activa en cuanto selecciono un pedido en el desplegable "filtro-pedidos". El objetivo es llevarme directamente a la fila correspondiente a ese pedido en la tabla, evitando tener que buscarlo manualmente.

Además, se ha modificado el index.php - INICIO para mostrar únicamente los productos en oferta. Esto se logra mediante la consulta:

' $sql = "SELECT * FROM productos WHERE descuento > 0 ORDER BY descuento DESC, nombre ASC"; '
Así, en la página principal solo se visualizarán los productos que tengan descuento activo. También, dentro de la tarjeta que muestra el producto, se ha añadido el precio original tachado, el nuevo precio con el descuento aplicado y el porcentaje de descuento.

Estos cambios también influyen en carrito.php y en mis_pedido.php al visualizar los detalles de los pedidos, ya que ahora se reflejarán los precios modificados por el descuento en todo el proceso de compra.


