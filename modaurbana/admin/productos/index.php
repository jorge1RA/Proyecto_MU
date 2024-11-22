<?php

/**
 * index.php - gestión de productos
 *
 * Página principal para la gestión de productos y sus variantes.
 *
 * Permite al administrador visualizar, editar y eliminar productos y variantes.
 *
 * @category Administración
 * @package  ModaUrbana
 * @author   Jorge Romero Ariza
 * 
 */


/**
 * Inicia sesión y conecta con la base de datos.
 */
session_start();


/**
 * Verifica si el usuario es administrador, si no, redirige al login.
 */
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 'admin') {
    header('Location: /modaurbana/pages/login/login.php');
    exit();
}


/**
 * Incluye el archivo de conexión a la base de datos.
 */
include_once '../../includes/conexion.php';


/**
 * Obtiene todos los productos de la base de datos.
 */
$sql = "SELECT * FROM productos";
$resultado_productos = mysqli_query($conexion, $sql);


/**
 * Incluye el esqueleto de la cabecera de la página.
 */
include_once '../../includes/templates/header.php';
?>

<!-- 
Contenedor principal con margen superior. 
-->
<div class="container mt-4">
    <h2>Gestión de Productos</h2>
    <a href="/modaurbana/admin/productos/crear.php" class="btn btn-success mb-3">Agregar Producto</a>

    <!-- 
    Condición para verificar el número de filas resultante de la consulta, si es mayor de cero, hay 1 o más producto. 
    -->
    <?php if (mysqli_num_rows($resultado_productos) > 0): ?>
        <table class="table table-bordered">

            <thead class="table">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Color</th>
                    <th>Talla</th>
                    <th>Stock</th>
                    <th>Acciones Producto</th>
                    <th>Acciones Variante</th>
                </tr>
            </thead>

            <tbody>

                <!-- 
                Itera sobre cada producto. 
                -->
                <?php while ($producto = mysqli_fetch_assoc($resultado_productos)): ?>

                    <?php
                    /**
                     * Obtiene las variantes del producto
                     */
                    $producto_id = $producto['id'];
                    $sql_variantes = "SELECT * FROM producto_variantes WHERE producto_id = ?";
                    $stmt_variantes = $conexion->prepare($sql_variantes);
                    $stmt_variantes->bind_param("i", $producto_id);
                    $stmt_variantes->execute();
                    $resultado_variantes = $stmt_variantes->get_result();
                    $num_variantes = $resultado_variantes->num_rows;
                    ?>

                    <!-- 
                    Sí el producto tiene variantes, las muestra. 
                    -->
                    <?php if ($num_variantes > 0): ?>
                        <?php $primera_fila = true; ?>
                        <!-- 
                        Bucle para cada variante del producto. 
                        -->
                        <?php while ($variante = $resultado_variantes->fetch_assoc()): ?>

                            <tr>
                                <?php if ($primera_fila): ?>
                                    <!-- 
                                    Sí es la primera fila, muestra los datos del producto (ID, Nombre, Precio) con rowspan para abarcar todas las variantes. 
                                    -->
                                    <td rowspan="<?php echo $num_variantes; ?>" class="align-middle"><?php echo htmlspecialchars($producto['id']); ?></td>
                                    <td rowspan="<?php echo $num_variantes; ?>" class="align-middle"><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                    <td rowspan="<?php echo $num_variantes; ?>" class="align-middle"><?php echo number_format($producto['precio'], 2); ?>€</td>
                                <?php endif; ?>

                                <!-- 
                                Muestra los detalles específicos de cada variante (Color, Talla, Stock). 
                                -->
                                <td><?php echo htmlspecialchars($variante['color']); ?></td>
                                <td><?php echo htmlspecialchars($variante['talla']); ?></td>
                                <td><?php echo htmlspecialchars($variante['stock']); ?></td>

                                <?php if ($primera_fila): ?>
                                    <!-- 
                                    Sí es la primera fila, muestra las acciones para editar o eliminar el producto, 
                                    con rowspan para abarcar todas las variantes. 
                                    -->
                                    <td rowspan="<?php echo $num_variantes; ?>" class="align-middle text-center">
                                        <a href="/modaurbana/admin/productos/editar.php?id=<?php echo $producto['id']; ?>" class="btn btn-primary btn-sm mb-1">Editar</a>
                                        <a href="/modaurbana/admin/productos/eliminar_producto.php?id=<?php echo $producto['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este producto y todas sus variantes?');">Eliminar</a>
                                    </td>
                                <?php endif; ?>

                                <!-- 
                                Muestra el botón para eliminar la variante específica. 
                                -->
                                <td class="text-center">
                                    <a href="/modaurbana/admin/productos/eliminar_variante.php?id=<?php echo $variante['id']; ?>" class="btn btn-warning btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta variante?');">Eliminar</a>
                                </td>
                            </tr>

                            <!-- 
                            Cambia la variable para indicar que ya no es la primera fila. 
                            -->
                            <?php $primera_fila = false; ?>
                            <!-- 
                            Fin del Bucle. 
                            -->
                        <?php endwhile; ?>
                    <?php else: ?>

                        <!-- 
                        Si no tiene variantes, muestra solo el producto. 
                        -->
                        <tr>
                            <td><?php echo htmlspecialchars($producto['id']); ?></td>
                            <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                            <td><?php echo number_format($producto['precio'], 2); ?>€</td>
                            <td colspan="3" class="text-center">No hay variantes disponibles.</td>
                            <td class="text-center">

                                <a href="/modaurbana/admin/productos/editar.php?id=<?php echo $producto['id']; ?>" class="btn btn-primary btn-sm mb-1">Editar</a>
                                <a href="/modaurbana/admin/productos/eliminar_producto.php?id=<?php echo $producto['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este producto?');">Eliminar</a>
                            </td>
                            <td></td>

                        </tr>

                    <?php endif; ?>
                    <!-- 
                    Fin del Bucle. 
                    -->
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- 
        Si no hay productos, muestra un mensaje. 
        -->
    <?php else: ?>
        <p>No hay productos disponibles.</p>
    <?php endif; ?>
</div>

<!-- 
Incluye el esqueleto del pie de la página. 
-->
<?php include_once '../../includes/templates/footer.php'; ?>