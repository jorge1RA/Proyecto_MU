<?php

/**
 * header.php
 * 
 * Cabecera que se incluye en todas las páginas para proporcionar una barra de navegación.
 * 
 * Muestra opciones específicas según el rol del usuario (usuario regular o administrador).
 * Incluye también un contador dinámico de mensajes no leídos para el administrador.
 *
 * @category Administración
 * @package  ModaUrbana
 * @author   Jorge Romero Ariza
 */


/**
 * Inicia la sesión si aún no ha sido iniciada.
 */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


/**
 * Incluir la conexión a la base de datos de forma segura.
 */
include_once __DIR__ . '/../conexion.php';

?>



<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>ModaUrbana</title>
    <!-- 
    Bootstrap CSS 
    -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <!-- 
    Font Awesome CSS 
    -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <!-- 
    Estilos personalizados 
    -->
    <link rel="stylesheet" href="/modaurbana/assets/css/estilos.css">
</head>

<body>
    <!-- 
    Contenedor principal. 
    -->
    <div id="contenido" class="d-flex flex-column">

        <!-- 
        Barra de navegación. 
        -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="/modaurbana/index.php">
                <!-- 
                Logo incrustado en barra de navegación. 
                -->
                <img src="/modaurbana/assets/img/Logo_Tienda.png" alt="ModaUrbana" height="40">
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- 
            Contenedor del menú. 
            -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- 
                Menú alineado a la izquierda - Inicio, Tienda, Nosotros, Contacto.
                -->

                <ul class="navbar-nav mr-auto">
                    <!-- 
                    Enlace a Inicio. 
                    -->
                    <li class="nav-item">
                        <a class="nav-link" href="/modaurbana/index.php">Inicio</a>
                    </li>

                    <!-- 
                    Enlace a Tienda. 
                    -->
                    <li class="nav-item">
                        <a class="nav-link" href="/modaurbana/pages/products/tienda.php">Tienda</a>
                    </li>

                    <!-- 
                    Enlace a Nosotros. 
                    -->
                    <li class="nav-item">
                        <a class="nav-link" href="/modaurbana/nosotros.php">Nosotros</a>
                    </li>

                    <!-- 
                    Enlace a Contacto. 
                    -->
                    <li class="nav-item">
                        <a class="nav-link" href="/modaurbana/pages/contact/contacto.php">Contacto</a>
                    </li>
                </ul>

                <!-- 
               Menú alineado a la derecha - Mis Pedidos, (Admin: Gestionar Productos, Gestionar Pedidos, Mensaje de Contactos), Carrito, Perfil, Iniciar Sesión, Cerrar Sesión.
                -->
                <ul class="navbar-nav">
                    <!-- 
                    Enlace al Carrito. 
                    -->
                    <li class="nav-item">
                        <a class="nav-link" href="/modaurbana/pages/cart/carrito.php">
                            <i class="fas fa-shopping-cart mr-2"></i>
                            <span class="badge badge-info"><?php echo isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0; ?></span>
                        </a>
                    </li>

                    <!-- 
                    Opciones de Usuario.
                    -->
                    <?php if (isset($_SESSION['usuario_id'])): ?>

                        <!-- 
                        Enlace Mi Perfil con Ícono. 
                        -->
                        <li class="nav-item">
                            <a class="nav-link" href="/modaurbana/admin/perfil/perfil.php">
                                <i class="fas fa-user"></i>
                            </a>
                        </li>

                        <!-- 
                        Cerrar Sesión. 
                        -->
                        <li class="nav-item">
                            <a class="nav-link" href="/modaurbana/pages/login/logout.php">Cerrar Sesión</a>
                        </li>

                    <?php else: ?>

                        <!-- 
                        Iniciar Sesión. 
                        -->
                        <li class="nav-item">
                            <a class="nav-link" href="/modaurbana/pages/login/login.php">Iniciar Sesión</a>
                        </li>

                        <!-- 
                        Registrarse. 
                        -->
                        <li class="nav-item">
                            <a class="nav-link" href="/modaurbana/pages/user/registro.php">Registrarse</a>
                        </li>

                    <?php endif; ?>

                </ul>
            </div>

        </nav>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

    <!-- Bootstrap JavaScript Bundle (incluye Popper.js) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>

</body>

</html>