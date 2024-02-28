<?php @include_once __DIR__ . '/../dashboard/header-dashboard.php'; ?>

<?php if (count($proyectos) === 0) { ?>

    <p class="no-proyectos">No Hay Proyectos Para Mostrar - <a href="/crear-proyecto">Crear Proyecto Nuevo</a></p>

<?php } else { ?>

    <ul class="listado-proyectos">
        <?php foreach ($proyectos as $proyecto) { ?>


            <li class="proyecto">

                <img class="eliminar" src="/build/img/eliminar.svg" id="<?php echo $proyecto->id; ?>">

                <a href="/proyecto?id=<?php echo $proyecto->url; ?>">
                    <?php echo $proyecto->proyecto; ?>
                </a>
            </li>



        <?php } ?>
    </ul>

<?php } ?>

<?php @include_once __DIR__ . '/../dashboard/footer-dashboard.php'; ?>

<?php
$script = '
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/build/js/proyectos.js"></script>
    ';
?>