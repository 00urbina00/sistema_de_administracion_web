<?php 
    session_start();
    if (!isset($_SESSION['id_empleado'])) {
        header('Location: index.php');
    }
?>
<!DOCTYPE html>
<!DOCTYPE html>
    <head>
        <title>Detalles del producto</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
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
            <h1>Detalle del producto</h1>
            <?php
            // Obtener los datos desde la URL usando GET
            $id = $_GET["id"];
            $nombre = $_GET["nombre"];
            $codigo = $_GET["codigo"];
            $descripcion = $_GET["descripcion"];
            $costo = $_GET["costo"];
            $stock = $_GET["stock"];

            $foto = $_GET['archivo'];
            if ($foto == '') {
                $foto = 'default.jpg';
            }
            ?>
            <div class="detalle-empleado">
                <img src="archivos/<?php echo $foto; ?>" alt="Foto de perfil" class="foto-perfil">
                <h2><?php echo $nombre?></h2>
            </div>
            <div class="botones">
                <a href="productos_lista.php">Regresar</a>
            </div>
            <div class="tabla-detalle">
                <div class="fila-detalle">
                    <div class="columna-titulo">Nombre</div>
                    <div class="columna-valor"><?php echo $nombre; ?></div>
                </div>
                <div class="fila-detalle">
                    <div class="columna-titulo">Código</div>
                    <div class="columna-valor"><?php echo $codigo; ?></div>
                </div>
                <div class="fila-detalle">
                    <div class="columna-titulo">Descripción</div>
                    <div class="columna-valor"><?php echo $descripcion; ?></div>
                </div>
                <div class="fila-detalle">
                    <div class="columna-titulo">Costo</div>
                    <div class="columna-valor">$<?php echo $costo; ?></div>
                </div>
                <div class="fila-detalle">
                    <div class="columna-titulo">Existencias</div>
                    <div class="columna-valor"><?php echo $stock; ?></div>
                </div>
            </div>
        </div>
    </body>
</html>