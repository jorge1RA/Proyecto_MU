<?php
// eliminar_variante.php - Eliminar una variante específica de un producto

include '../../includes/conexion.php';
session_start();

// Verifica si el usuario es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 'admin') {
    header('Location: /modaurbana/pages/login/login.php');
    exit();
}

// Verifica si se ha proporcionado un ID de variante
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $variante_id = intval($_GET['id']);

    // Elimina la variante del producto
    $sql_eliminar_variante = "DELETE FROM producto_variantes WHERE id = ?";
    $stmt_variante = $conexion->prepare($sql_eliminar_variante);
    $stmt_variante->bind_param("i", $variante_id);
    if ($stmt_variante->execute()) {
        header('Location: /modaurbana/admin/productos/index.php?mensaje=Variante eliminada correctamente.');
        exit();
    } else {
        $error = "Error al eliminar la variante: " . $conexion->error;
        header('Location: /modaurbana/admin/productos/index.php?error=' . urlencode($error));
        exit();
    }
} else {
    header('Location: /modaurbana/admin/productos/index.php?error=' . urlencode("ID de variante no válido."));
    exit();
}
