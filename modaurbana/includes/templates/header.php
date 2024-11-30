<?php
// header.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>ModaUrbana</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="/modaurbana/assets/css/estilos.css">
</head>

<body>
    <div id="contenido" class="d-flex flex-column">
        <!-- Barra de navegación -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="/modaurbana/index.php">
                <img src="/modaurbana/assets/img/Logo_Tienda.png" alt="ModaUrbana" height="40">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Contenedor del menú -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Menú alineado a la izquierda -->
                <ul class="navbar-nav mr-auto">
                    <!-- Enlace a Inicio -->
                    <li class="nav-item">
                        <a class="nav-link" href="/modaurbana/index.php">Inicio</a>
                    </li>
                    <!-- Enlace a Tienda -->
                    <li class="nav-item">
                        <a class="nav-link" href="/modaurbana/pages/products/tienda.php">Tienda</a>
                    </li>
                    <!-- Enlace a Nosotros -->
                    <li class="nav-item">
                        <a class="nav-link" href="/modaurbana/nosotros.php">Nosotros</a>
                    </li>
                    <!-- Enlace a Contacto -->
                    <li class="nav-item">
                        <a class="nav-link" href="/modaurbana/pages/contact/contacto.php">Contacto</a>
                    </li>
                </ul>
                <!-- Menú alineado a la derecha -->
                <ul class="navbar-nav">
                    <!-- Mis Pedidos -->
                    <?php if (isset($_SESSION['usuario_id']) && $_SESSION['usuario_rol'] != 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/modaurbana/admin/pedidos/mis_pedidos.php">Mis Pedidos</a>
                        </li>
                    <?php endif; ?>

                    <!-- Opciones del Administrador -->
                    <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] == 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/modaurbana/admin/productos/index.php">Gestionar Productos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/modaurbana/admin/pedidos/index.php">Gestionar Pedidos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/modaurbana/admin/contactos/ver_contactos.php">
                                <i class="fas fa-envelope"></i>
                                <span class="badge badge-danger">3</span> <!-- Donde '3' es el número de mensajes no leídos -->
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Enlace al Carrito -->
                    <li class="nav-item">
                        <a class="nav-link" href="/modaurbana/pages/cart/carrito.php">
                            <i class="fas fa-shopping-cart mr-2"></i>
                            <span class="badge badge-info"><?php echo isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0; ?></span>
                        </a>
                    </li>


                    <!-- Opciones de Usuario -->
                    <?php if (isset($_SESSION['usuario_id'])): ?>
                        <!-- Enlace Mi Perfil con Ícono -->
                        <li class="nav-item">
                            <a class="nav-link" href="/modaurbana/admin/perfil/perfil.php">
                                <i class="fas fa-user"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/modaurbana/pages/login/logout.php">Cerrar Sesión</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/modaurbana/pages/login/login.php">Iniciar Sesión</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/modaurbana/pages/user/registro.php">Registrarse</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </div>
</body>

</html>