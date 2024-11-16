<?php 
    session_start();
    if (!isset($_SESSION['id_empleado'])) {
        header('Location: index.php');
    }
?>
<!DOCTYPE html>
<!DOCTYPE html>
    <head>
        <title>Detalles del Empleado</title>
        <link rel="stylesheet" type="text/css" href="css/style.css?v=<?php echo time(); ?>">
        <style>
            .botones {
                display: flex;
                justify-content: center;
                margin-bottom: 20px;
            }
            .botones a {
                background-color: #026778;
                transition: background-color 0.3s;
            }

            .botones a:hover {
                background-color: #034854;
            }
        </style>
    </head>
    <body>
        <!-- Incluir el menú -->
        <?php include 'menu_navegacion.php'; ?>

        <div class="detalle-contenedor">
            <h1>Detalle del empleado</h1>
            <?php
            // Obtener los datos desde la URL usando GET
            $nombre = $_GET['nombre'];
            $apellidos = $_GET['apellidos'];
            $correo = $_GET['correo'];
            $tmp_rol = $_GET['rol'];
            $rol = ($tmp_rol == 1) ? 'Gerente' : 'Ejecutivo';
            $foto = $_GET['archivo'];
            if ($foto == '') {
                $foto = 'default.jpg';
            }
            ?>
            <div class="detalle-empleado">
                <img src="archivos/<?php echo $foto; ?>" alt="Foto de perfil" class="foto-perfil">
                <h2><?php echo $nombre . " " . $apellidos; ?></h2>
            </div>
            <div class="botones">
                <a href="empleados_lista.php">Regresar</a>
            </div>
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
        </div>
    </body>
</html>