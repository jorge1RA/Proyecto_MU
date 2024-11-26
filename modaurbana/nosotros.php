<?php

/**
 * Incluye el esqueleto de la cabecera de la página.
 * 
 * Esto añade la parte superior de la página, incluyendo la barra de navegación y el título del sitio.
 * 
 * @return void
 */
include_once 'includes/templates/header.php';

?>

<?php
/**
 * nosotros.php
 * 
 * Página "Sobre Nosotros" - ModaUrbana.
 * Proporciona información sobre la misión, la historia y los objetivos de la tienda de moda.
 * La página describe el enfoque innovador de reutilización de prendas de vestir para promover una moda sostenible en un futuro.
 * 
 * @category Información
 * @package  ModaUrbana
 * @author   Jorge Romero Ariza
 */
?>

<!-- 
Contenedor principal con margen superior.
Este contenedor me permite mostrar toda la información sobre ModaUrbana.
-->
<div class="container mt-4">
    <br>
    <h2>Sobre Nosotros</h2>
    <div class="row">
        <div class="col-md-6">

            <!-- 
            Información sobre nuestra historia, filosofía y objetivos en ModaUrbana.
            Estos párrafos proporcionan detalles sobre el enfoque de la tienda, misión y la importancia de una moda y produción de fabricación sostenible.
            -->
            <br>
            <p>Bienvenido a <strong>ModaUrbana</strong>, tu destino en línea para la moda más actual y vanguardista.
                Fundada en 2024, nuestra misión es ofrecerte prendas y accesorios de alta calidad que reflejen las últimas tendencias urbanas.</p>

            <p>En ModaUrbana, hemos creado una tienda en línea con una propuesta innovadora para transformar la forma en que compramos ropa.
                Nuestra idea es comprar y rescatar ropa desechada y recolectada en entidades de Medio Ambiente,
                u otras organizaciones que recogen ropa que ya no se usa, pero está en buen estado.
                A partir de esta materia prima,
                realizamos un proceso de tratamiento que incluye una limpieza y desinfección como selección de materiales.</p>

            <p>Posteriormente, nuestro equipo se encarga de rediseñar estas prendas,
                aprovechando las telas y materiales que se encuentran en mejor estado.
                Este enfoque permite dar una segunda oportunidad a materiales que de otra forma acabarían siendo desechos y contribuye
                a la disminución de la contaminación derivada de la industria textil, una de las más contaminantes del mundo.</p>

            <p>Nuestro objetivo es evitar el consumismo excesivo que contribuye al deterioro del planeta,
                y en su lugar, promover una moda circular y consciente. Queremos crear prendas únicas,
                con diseños exclusivos, que no solo sean estéticamente atractivos, sino también significativos,
                porque cada prenda representa un paso hacia una forma más responsable de vivir y de consumir.</p>

            <p>¡Gracias por elegirnos y ser parte de nuestra comunidad de amantes de la moda urbana!</p>
        </div>
        <div class="col-md-6">

            <!-- 
            Imagen:
           Se añade una imagen que viste y acompaña a la información visual de información de nosotros.
           La clase "img-fluid" asegura adaptabilidad a cualquier pantalla, "rounded" proporciona bordes redondeados, y el estilo "height:100%;" ocupa todo el espacio disponible.
            -->
            <img src="/modaurbana/assets/img/" alt="Equipo de ModaUrbana" class="img-fluid rounded" style="height:100%;">


        </div>
    </div>

</div>

<!-- 
Incluye el esqueleto del pie de la página.
Esto añade el pie de página con la información adicional del sitio.
-->
<?php include_once 'includes/templates/footer.php'; ?>