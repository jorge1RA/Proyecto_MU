Tienda ModaUrbana

Bienvenido a la documentación de Tienda ModaUrbana. Este proyecto es una aplicación web desarrollada en PHP con una base de datos MySQL. La tienda está diseñada para ofrecer una experiencia fácil y cómoda para comprar ropa y accesorios de moda. A continuación, se presenta una descripción detallada del proyecto y sus funcionalidades principales.

📜 Descripción del Proyecto

La Tienda ModaUrbana es una tienda virtual que permite a los usuarios navegar por una variedad de productos de moda, agregarlos a un carrito de compras, y gestionar sus pedidos de manera eficiente. Los usuarios pueden registrarse, autenticarse, y gestionar su información personal y direcciones de envío. El administrador de la tienda puede añadir, modificar, o eliminar productos a través de un sistema CRUD.

En ModaUrbana, nuestra misión es transformar prendas desechadas en moda sostenible y moderna. Trabajamos con materiales recuperados de organizaciones como entidades de reciclaje, seleccionando telas de calidad y rediseñándolas para crear ropa única y amigable con el medio ambiente. Nuestro propósito es reducir el impacto ambiental generado por la industria del consumismo y ofrecer una segunda vida a textiles en buen estado, contribuyendo a un futuro más responsable y creativo.

🚀 Tecnologías Utilizadas

Lenguaje de Programación: PHP 7+

Base de Datos: MySQL

Servidor Web: Apache (incluido en XAMPP)

HTML/CSS: Para el diseño de la interfaz de usuario

Plantillas: Reutilización de secciones comunes del sitio web, como encabezados y pies de página (ubicados en includes/templates/). El esqueleto está desarrollado con HTML y los estilos están implementados con el framework CSS Bootstrap para asegurar un diseño moderno y responsivo.

🗓️ Estructura del Proyecto

El proyecto está organizado de la siguiente manera:

/htdocs/modaurbana: Carpeta principal del proyecto.

index.php: Punto de entrada de la aplicación web.

nosotros.php, politica_privacidad.php, terminos_condiciones.php: Páginas de información general sobre la tienda, políticas y términos legales.

/admin: Sección administrativa del sitio.

contactos/: Administración de contactos y mensajes recibidos.

pedidos/: Administración y edición del estado de pedidos.

perfil/: Edición del perfil del administrador.

productos/: CRUD de productos y variantes.

/pages: Páginas de usuarios.

cart/: Páginas relacionadas con el carrito de compras.

contact/: Formulario de contacto para los usuarios.

login/: Autenticación de usuarios.

products/: Visualización y compra de productos.

user/: Registro y gestión de usuarios.

/assets: Archivos de recursos.

css/estilos.css: Contiene los estilos del sitio web.

js/funciones.js: Define la lógica del lado del cliente.

img/: Directorio con todas las imágenes usadas en el sitio.

/includes: Archivos de conexión y utilidades.

conexion.php: Establece la conexión con la base de datos MySQL.

email/: Archivos relacionados con la configuración y envío de correos electrónicos.

PHPMailer/: Librería para el envío de correos.

templates/: Archivos comunes como header.php y footer.php.

🛠️ Instalación y Configuración

Para instalar y ejecutar la Tienda ModaUrbana en tu entorno local, sigue los siguientes pasos:

Requisitos Previos:

XAMPP o cualquier otro servidor que soporte PHP y MySQL.

Navegador web actualizado.

Clonar el Proyecto:

Copia la carpeta modaurbana dentro del directorio htdocs de XAMPP.

Configuración de la Base de Datos:

Inicia XAMPP y abre phpMyAdmin.

Crea una base de datos llamada modaurbana_db.

Importa el archivo modaurbana_db.sql que contiene la estructura y los datos iniciales de la tienda.

Configuración del Archivo de Conexión:

Abre el archivo conexion.php y asegúrate de que las credenciales de la base de datos (host, usuario, contraseña, nombre de la base de datos) sean correctas:

$host = "localhost";
$usuario = "root";
$password = "";
$base_datos = "modaurbana_db";

Iniciar el Servidor:

Abre el panel de control de XAMPP y activa Apache y MySQL.

En el navegador, visita http://localhost/modaurbana.

📈 Funcionalidades Principales

1. Registro y Autenticación de Usuarios

Los usuarios pueden registrarse en el sitio proporcionando sus datos personales.

La autenticación se realiza mediante un formulario de inicio de sesión.

Los usuarios pueden actualizar su información personal y direcciones.

2. Catálogo de Productos

Navegación por una variedad de productos de moda, organizados en categorías.

Detalles de cada producto, incluyendo nombre, precio, descripción e imágenes.

3. Carrito de Compras

Los usuarios pueden agregar productos al carrito de compras y ajustar las cantidades.

Visualización del resumen del pedido, incluyendo subtotales y total.

4. Gestor de Pedidos

Los usuarios pueden confirmar pedidos y ver el estado de sus órdenes.

El administrador puede gestionar los pedidos para aprobación y envío.

5. CRUD de Productos (Para Administradores)

El administrador de la tienda puede añadir, modificar, eliminar y ver los productos desde el panel administrativo.

🛣️ Contribuir

Si deseas contribuir a este proyecto, sigue estos pasos:

Haz un fork del proyecto.

Crea una nueva rama (git checkout -b feature/nueva-funcionalidad).

Realiza tus cambios y haz un commit (git commit -m 'Añadir nueva funcionalidad').

Haz un push a la rama (git push origin feature/nueva-funcionalidad).

Abre un Pull Request.

🛡️ Seguridad

Asegúrate de que el archivo conexion.php no esté expuesto públicamente sin protección.

Utiliza contraseñas seguras y evita dejar credenciales sensibles en el código fuente.

Considera implementar protección contra SQL Injection y Cross-Site Scripting (XSS).

🌐 Enlaces de Interés

Documentación de PHP

Documentación de MySQL

Descargar XAMPP

📋 Licencia

Este proyecto está bajo la licencia MIT. Puedes hacer uso del código bajo las condiciones especificadas en el archivo LICENSE. Si deseas saber más sobre esta licencia, cómo funciona, y cómo puedes utilizarla, pincha aquí.
