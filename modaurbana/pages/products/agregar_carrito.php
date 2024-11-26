<?php
// agregar_carrito.php



include '../../includes/conexion.php';
session_start();

// Verifica que la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obteniene y sanitiza los datos del formulario
    $producto_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $talla = isset($_POST['talla']) ? trim($_POST['talla']) : '';
    $color = isset($_POST['color']) ? trim($_POST['color']) : '';
    $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1;

    // Valida que el ID del producto es válido
    if ($producto_id <= 0) {
        $_SESSION['error'] = "ID de producto inválido.";
        header('Location: /modaurbana/pages/products/tienda.php');
        exit();
    }

    // Obtiene los detalles del producto
    $stmt = $conexion->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->bind_param("i", $producto_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $producto = $resultado->fetch_assoc();
    $stmt->close();

    if (!$producto) {
        $_SESSION['error'] = "Producto no encontrado.";
        header('Location: /modaurbana/pages/products/tienda.php');
        exit();
    }

    // Verifica el stock disponible
    if ($cantidad > intval($producto['stock'])) {
        $_SESSION['error'] = "La cantidad solicitada excede el stock disponible.";
        header('Location: /modaurbana/pages/products/productos.php?id=' . $producto_id);
        exit();
    }

    // Verifica si el producto tiene tallas
    if (!empty($producto['tallas'])) {
        if (empty($talla)) {
            $_SESSION['error'] = "Por favor, selecciona una talla.";
            header('Location: /modaurbana/pages/products/productos.php?id=' . $producto_id);
            exit();
        }

        // Valida que la talla seleccionada es válida
        $tallas_disponibles = array_map('trim', explode(',', $producto['tallas']));
        if (!in_array($talla, $tallas_disponibles)) {
            $_SESSION['error'] = "Talla inválida seleccionada.";
            header('Location: /modaurbana/pages/products/productos.php?id=' . $producto_id);
            exit();
        }
    } else {
        $talla = 'N/A'; // Asigna un valor predeterminado si no hay colores
    }

    // Verifica si el producto tiene colores
    if (!empty($producto['colores'])) {
        if (empty($color)) {
            $_SESSION['error'] = "Por favor, selecciona un color.";
            header('Location: /modaurbana/pages/products/productos.php?id=' . $producto_id);
            exit();
        }

        // Valida que el color seleccionado es válido
        $colores_disponibles = array_map('trim', explode(',', $producto['colores']));
        if (!in_array($color, $colores_disponibles)) {
            $_SESSION['error'] = "Color inválido seleccionado.";
            header('Location: /modaurbana/pages/products/productos.php?id=' . $producto_id);
            exit();
        }
    } else {
        $color = 'N/A'; // Asigna un valor predeterminado si no hay colores
    }

    // Crea una clave única para la variante
    $clave = $producto_id;

    if (!empty($producto['tallas']) && !empty($producto['colores'])) {
        $clave .= '_' . $talla . '_' . $color;
    } elseif (!empty($producto['tallas'])) {
        $clave .= '_' . $talla;
    } elseif (!empty($producto['colores'])) {
        $clave .= '_' . $color;
    } else {
        $clave .= '_N/A';
    }

    // Inicializa el carrito si no existe
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    // Si el producto con la misma variante ya está en el carrito, actualizar la cantidad
    if (isset($_SESSION['carrito'][$clave])) {
        $nueva_cantidad = $_SESSION['carrito'][$clave]['cantidad'] + $cantidad;
        if ($nueva_cantidad > intval($producto['stock'])) {
            $_SESSION['error'] = "La cantidad total en el carrito excede el stock disponible.";
        } else {
            $_SESSION['carrito'][$clave]['cantidad'] = $nueva_cantidad;
            $_SESSION['success'] = "Cantidad actualizada en el carrito.";
        }
    } else {
        // Añade el producto al carrito
        $_SESSION['carrito'][$clave] = [
            'id' => $producto['id'],
            'nombre' => $producto['nombre'],
            'precio' => $producto['precio'],
            'talla' => $talla,
            'color' => $color,
            'cantidad' => $cantidad,
            'imagen' => $producto['imagen']
        ];
        $_SESSION['success'] = "Producto añadido al carrito.";
    }

    header('Location: /modaurbana/pages/cart/carrito.php');
    exit();
} else {
    // Si no es una solicitud POST, redirige al inicio
    header('Location: /modaurbana/pages/products/tienda.php');
    exit();
}
