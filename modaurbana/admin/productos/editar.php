<?php
session_start();

// Verificar si el usuario es administrador - Pendiente de probar funcionamiento y habilitar
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 'admin') {
    header('Location:/modaurbana/admin/productos/index.php');
    exit();
}

// Simulación de obtención como prueba
$producto = [
    'nombre' => 'Producto....',
    'descripcion' => 'Descripción....',
    'precio' => 99.99,
    'imagen' => 'assetes/img/Chaqueta_Negra.jpg',
    'categoria' => 'Categoría Ejemplo'
];

include_once '../../includes/templates/header.php';
?>

<div class="container mt-4">
    <h2>Editar Producto</h2>

    <form method="POST" action="#">
        <div class="form-group">
            <label>Nombre del Producto</label>
            <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
        </div>

        <div class="form-group">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control" rows="5" required><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>
        </div>

        <div class="form-group">
            <label>Precio</label>
            <input type="number" step="0.01" name="precio" class="form-control" value="<?php echo htmlspecialchars($producto['precio']); ?>" required>
        </div>

        <div class="form-group">
            <label>Imagen Actual</label><br>
            <img src="/modaurbana/assets/img/<?php echo $producto['imagen']; ?>" alt="Imagen Ejemplo" width="150">
        </div>

        <div class="form-group">
            <label>Cambiar Imagen (opcional)</label>
            <input type="file" name="imagen" class="form-control-file">
        </div>

        <div class="form-group">
            <label>Categoría</label>
            <input type="text" name="categoria" class="form-control" value="<?php echo htmlspecialchars($producto['categoria']); ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="/modaurbana/admin/productos/index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php include '../../includes/templates/footer.php'; ?>
