<div class="contenedor confirmar">

    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">

    <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

    <?php if($error) return; ?>
        <div class="acciones">
            <a href="/">Iniciar Sesión</a>
        </div>
    </div>

</div>