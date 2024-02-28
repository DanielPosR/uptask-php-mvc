<?php
@include_once __DIR__ . '/../dashboard/header-dashboard.php';
?>

<div class="contenedor-sm">
<a href="/perfil" class="enlace">&larr; Volver al Perfil</a>

    <?php
    @include_once __DIR__ . '/../templates/alertas.php';
    ?>



    <form action="/cambiar-password" method="POST" class="formulario">

        <div class="campo">
            <label for="password_actual">Password Actual: </label>
            <input type="password" name="password_actual" id="password_actual" placeholder="Tu password actual">
        </div>

        <div class="campo">
            <label for="password_nuevo">Nuevo Password: </label>
            <input type="password" name="password_nuevo" id="password_nuevo" placeholder="Nuevo password">
        </div>

        <input type="submit" value="Gurdar Cambios">
    </form>
</div>

<?php
@include_once __DIR__ . '/../dashboard/footer-dashboard.php';
?>