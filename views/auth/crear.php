<div class="contenedor crear">

    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>


    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crear tu cuenta en UpTask</p>

        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

        <form action="/crear" method="POST" class="formulario">

            <div class="campo">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" placeholder="Tu nombre" value="<?php echo $usuario->nombre; ?>">
            </div>

            <div class="campo">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" placeholder="Tu email" value="<?php echo $usuario->email; ?>">
            </div>

            <div class="campo">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" placeholder="Tu Password">
            </div>

            <div class="campo">
                <label for="password2">Repetir password:</label>
                <input type="password" name="password2" id="password2" placeholder="Repite tu Password">
            </div>

            <input type="submit" value="Crear Cuenta" class="boton">
        </form>

        <div class="acciones">
            <a href="/">¿Ya tienes cuenta? Inicia sesión.</a>
            <a href="/olvide">Olvide mi password.</a>
        </div>

    </div>
</div>