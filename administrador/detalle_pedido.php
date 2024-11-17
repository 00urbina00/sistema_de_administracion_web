<?php
    session_start();
    if (!isset($_SESSION['id_cliente'])) {
        header('Location: index.php');
        exit();
    }

    require "funciones/conecta.php";
    $con = conecta();

    if (!isset($_GET['id_pedido'])) {
        header('Location: pedidos.php');
        exit();
    }
    
    $id_pedido = intval($_GET['id_pedido']);
    
    // Consulta para obtener los productos del pedido
    $query_productos = "
        SELECT p.nombre, pp.cantidad, pp.precio, (pp.cantidad * pp.precio) AS subtotal
        FROM pedidos_productos pp
        JOIN productos p ON pp.id_producto = p.id
        WHERE pp.id_pedido = $id_pedido
    ";
    $res = $con->query($query_productos);
?>

<!DOCTYPE html>
<head>
    <title>Detalle del Pedido</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        /* Estructura general */
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        .action-buttons {
            text-align: center;
            margin-top: 20px;
        }

        .sin-productos {
            font-size: 18px;
            font-weight: bold;
            color: #888;
            background-color: #f8f8f8;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 20px auto;
            text-align: center;
        }

        /* Contenedor del carrito */
        .carrito {
            flex: 1;
            width: 90%;
            max-width: 90%;
            margin: 30px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }

        /* Título y tabla del carrito */
        .carrito h2 {
            font-size: 2em;
            color: #084853;
            text-align: center;
            margin-bottom: 20px;
        }

        .tabla-carrito {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .tabla-carrito th, .tabla-carrito td {
            padding: 10px;
            border: 1px solid #ccc;
        }

        .tabla-carrito th {
            background-color: #084853;
            color: #fff;
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

        .carrito .total {
            text-align: right;
            font-weight: bold;
            font-size: 1.2em;
            margin-top: 20px;
        }

        

    </style>
</head>
<body>
    <header>
        <!-- Menú de navegación -->
        <?php include 'menu_navegacion.php'; ?>
    </header>
    <!-- Detalles del Pedido -->
    <section class="carrito">
        <h2>Detalle del Pedido - # <?php echo $id_pedido; ?></h2>

        <?php if ($res && $res->num_rows > 0): ?>
            <table class="tabla-carrito">
                <thead>
                    <tr>
                        <th style="text-align: center;">Producto</th>
                        <th style="text-align: center;">Costo</th>
                        <th style="text-align: center;">Cantidad</th>
                        <th style="text-align: center;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $total = 0;
                        while ($producto = $res->fetch_assoc()):
                            $total += $producto['subtotal'];
                    ?>
                        <tr>
                            <td style="text-align: center;"><?php echo htmlspecialchars($producto['nombre']); ?></td>
                            <td style="text-align: center;">$<?php echo number_format($producto['precio'], 2); ?></td>
                            <td style="text-align: center;"><?php echo $producto['cantidad']; ?></td>
                            <td style="text-align: center;">$<?php echo number_format($producto['subtotal'], 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="total">Total: $<?php echo number_format($total, 2); ?></div>
            <div class="action-buttons">
                <!-- Botón para regresar al carrito o al menú anterior -->
                <a href="pedidos.php"><button class="btn-continuar">Regresar</button></a>
            </div>
        <?php else: ?>
            <p class="sin-productos">No hay productos a mostrar.</p>
            <div class="action-buttons">
                <a href="pedidos.php"><button class="btn-continuar">Regresar</button></a>
            </div>
        <?php endif; ?>
    </section>

</body>
</html>
