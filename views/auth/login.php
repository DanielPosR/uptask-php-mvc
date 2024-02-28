<div class="contenedor login">

    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Iniciar Sesión</p>

        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

        <form action="/" method="POST" class="formulario" novalidate>
            <div class="campo">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" placeholder="Tu email">
            </div>

            <div class="campo">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" placeholder="Tu Password">
            </div>

            <input type="submit" value="Iniciar Sesión" class="boton">
        </form>

        <div class="acciones">
            <a href="/crear">¿No tienes cuenta? Crea una.</a>
            <a href="/olvide">Olvide mi password.</a>
        </div>

    </div>
</div>