<?php 
    session_start();
    if (!isset($_SESSION['id_empleado'])) {
        header('Location: index.php');
    }
    
    require "funciones/conecta.php";
    $con = conecta();
    $sql = "SELECT * FROM pedidos WHERE status = 1"; // Consultar solo pedidos cerrados
    $res = $con->query($sql);
    $num = $res->num_rows;
?>
<!DOCTYPE html>
    <head>
        <title>Listado de Productos</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <style>
            .fila .boton_accion-detalles {
                padding: 5px 10px;
                background-color: #28a745;
                color: #FFFFFF;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            .fila .boton_accion-detalles:hover {
                background-color: #218a39; 
            }
            .fila .boton_accion-editar {
                padding: 5px 10px;
                background-color: #e3c70b;
                color: #FFFFFF;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            .fila .boton_accion-editar:hover {
                background-color: #d4c45a; 
            }
            .detalle-contenedor {
                max-width: 100%; 
                max-height: 100%; 
                height: auto; 
                margin: 0 auto;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                overflow-y: auto;
                margin-top: 20px; 
                text-align: center;
            }

            .detalle-contenedor h2 {
                font-size: 20px;
                margin-bottom: 10px;
                color: #084853;
                text-align: center;
            }

            /* Botones */
            .carrito .btn-eliminar, .carrito .btn-continuar {
                background-color: #084853;
                color: #fff;
                border: none;
                padding: 8px 12px;
                border-radius: 5px;
                cursor: pointer;
            }

            .carrito .btn-eliminar:hover, .carrito .btn-continuar:hover {
                background-color: #063a41;
            }

            .action-buttons {
                text-align: center;
                margin-top: 20px;
            }

            .action-buttons a .btn-continuar {
                display: inline-block; /* Asegura el formato de botón */
                text-align: center;
                background-color: #084853;
                color: #fff;
                border: none;
                padding: 8px 12px;
                border-radius: 5px;
                cursor: pointer;
            }

            .action-buttons a .btn-continuar:hover {
                background-color: #063a41;
            }

            .fila .boton_accion {
                padding: 5px 10px;
                background-color: #084853;
                color: #FFFFFF;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            .fila .boton_accion:hover {
                background-color: #063a41;
            }
        </style>
    </head>
    <body>
        <!-- Incluir el menú -->
        <?php include 'menu_navegacion.php'; ?>
        <div class="detalle-contenedor">
            <h1 style="margin-bottom: 40px;">Listado de Pedidos Cerrados (<?php echo $num; ?>)</h1>
            <div class="tabla">
                <div class="fila fila_header">
                    <div class="columna" style="text-align: center;">ID Pedido</div>
                    <div class="columna" style="text-align: center;">ID Cliente</div>
                    <div class="columna" style="text-align: center;">Fecha</div>
                    <div class="columna" style="text-align: center;">Ver Detalle</div>
                </div>
                <?php 
                while($row = $res->fetch_array()){
                    $id = $row["id"];
                    $id_cliente = $row["id_cliente"];
                    $fecha = $row["fecha"];
                    echo '<div class="fila">
                        <div class="columna" style="text-align: center;">' . $id . '</div>
                        <div class="columna" style="text-align: center;">' . $id_cliente . '</div>
                        <div class="columna" style="text-align: center;">' . $fecha . '</div>
                        <div class="columna" style="text-align: center;">
                            <a href="detalle_pedido.php?id_pedido=' . $id . '">
                                <button class="boton_accion">Ver detalle</button>
                            </a>
                        </div>
                    </div>';
                }
                ?>
            </div>
        </div>
</body>
</html>