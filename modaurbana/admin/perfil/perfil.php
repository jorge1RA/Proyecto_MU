<?php
//perfil.php


// Iniciar sesión y conexión a la base de datos
include '../../includes/conexion.php';
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header('Location: /modaurbana/pages/login/login.php');
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Obtener la información del usuario
$sql_usuario = "SELECT * FROM usuarios WHERE id = '$usuario_id'";
$resultado_usuario = mysqli_query($conexion, $sql_usuario);

if (!$resultado_usuario || mysqli_num_rows($resultado_usuario) == 0) {
    echo "Usuario no encontrado.";
    exit();
}

$usuario = mysqli_fetch_assoc($resultado_usuario);

include '../../includes/templates/header.php';
?>

<div class="container mt-4">
    <h2 class="mb-4">Mi Perfil</h2>
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5>Información Personal</h5>
        </div>
        <div class="card-body">
            <img src="/modaurbana/assets/img/<?php echo htmlspecialchars($usuario['imagen']); ?>" alt="Imagen de Perfil" class="img-thumbnail mb-3" width="150">
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($usuario['nombre']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
            <p><strong>Rol:</strong> <?php echo ucfirst(htmlspecialchars($usuario['rol'])); ?></p>


            <a href="/modaurbana/admin/perfil/editar_perfil.php" class="btn btn-primary mt-3">Editar Perfil</a>
        </div>
    </div>
</div>
<?php include '../../includes/templates/footer.php'; ?>