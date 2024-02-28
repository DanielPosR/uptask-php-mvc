<div class="contenedor reestablecer">

    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crear nuevo password</p>

        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

        <?php if($error) return; ?>

        <form method="POST" class="formulario">

            <div class="campo">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" placeholder="Tu Nuevo Password">
            </div>

            <input type="submit" value="Guardar Password" class="boton">
        </form>
    </div>
</div>