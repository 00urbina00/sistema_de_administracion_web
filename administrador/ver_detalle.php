<!DOCTYPE html>
<html>
    <head>
        <title>Detalles del Empleado</title>
        <link rel="stylesheet" type="text/css" href="css/style.css?v=<?php echo time(); ?>">
        <style>
            .botones {
                display: flex;
                justify-content: center;
                margin-top: 20px;
            }
        </style>
    </head>
    <body>

        <h1>Detalle del empleado</h1>

        <div class="detalle-contenedor">
            <?php
            // Obtener los datos desde la URL usando GET
            $nombre = $_GET['nombre'];
            $apellidos = $_GET['apellidos'];
            $correo = $_GET['correo'];
            $tmp_rol = $_GET['rol'];
            $rol = ($tmp_rol == 1) ? 'Gerente' : 'Ejecutivo';
            ?>

            <h2><?php echo $nombre . " " . $apellidos; ?></h2>

            <div class="tabla-detalle">
                <div class="fila-detalle">
                    <div class="columna-titulo">Nombre</div>
                    <div class="columna-valor"><?php echo $nombre; ?></div>
                </div>
                <div class="fila-detalle">
                    <div class="columna-titulo">Apellidos</div>
                    <div class="columna-valor"><?php echo $apellidos; ?></div>
                </div>
                <div class="fila-detalle">
                    <div class="columna-titulo">Correo</div>
                    <div class="columna-valor"><?php echo $correo; ?></div>
                </div>
                <div class="fila-detalle">
                    <div class="columna-titulo">Rol</div>
                    <div class="columna-valor"><?php echo $rol; ?></div>
                </div>
            </div>

            <div class="botones">
                <a href="empleados_lista.php">Volver al listado</a>
            </div>
        </div>

    </body>
</html>