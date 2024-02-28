<?php
@include_once __DIR__ . '/../dashboard/header-dashboard.php';
?>

<div class="contenedor-sm">

    <a href="/cambiar-password" class="enlace">Cambiar Password &rarr;</a>

    <?php
    @include_once __DIR__ . '/../templates/alertas.php';
    ?>


    <form action="/perfil" method="POST" class="formulario">

        <div class="campo">
            <label for="nombre">Nombre: </label>
            <input type="text" name="nombre" id="nombre" placeholder="Tu nombre" value="<?php echo $usuario->nombre; ?>">
        </div>

        <div class="campo">
            <label for="email">Email: </label>
            <input type="text" name="email" id="email" placeholder="Tu email" value="<?php echo $usuario->email; ?>">
        </div>

        <input type="submit" value="Gurdar Cambios">
    </form>
</div>

<?php
@include_once __DIR__ . '/../dashboard/footer-dashboard.php';
?>