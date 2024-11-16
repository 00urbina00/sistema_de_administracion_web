<?php
session_start();
require "funciones/conecta.php";
$con = conecta();

if (!isset($_SESSION['id_cliente'])) {
    header('Location: index.php');
    exit();
}

$id_cliente = $_SESSION['id_cliente'];
$query_pedido = "SELECT id FROM pedidos WHERE id_cliente = $id_cliente AND status = 0 LIMIT 1";
$result_pedido = $con->query($query_pedido);

if ($result_pedido->num_rows > 0) {
    $pedido = $result_pedido->fetch_assoc();
    $id_pedido = $pedido['id'];

    // Consulta para obtener los productos en el pedido actual
    $query_productos = "
        SELECT p.nombre, pp.cantidad, pp.precio, (pp.cantidad * pp.precio) AS subtotal
        FROM pedidos_productos pp
        JOIN productos p ON pp.id_producto = p.id
        WHERE pp.id_pedido = $id_pedido
    ";
    $res = $con->query($query_productos);
} else {
    $res = false; // No hay pedido activo
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen del Carrito</title>
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
    </style>
</head>
<body>
    <header>
        <!-- Menú de navegación -->
        <?php include 'menu_navegacion.php'; ?>
    </header>

    <!-- Barra de progreso -->
    <div class="progress-bar-container">
        <div class="progress-step active"></div>
        <div class="progress-step active"></div>
    </div>

    <!-- Sección del Carrito -->
    <section class="carrito">
        <h2>Resumen del Carrito</h2>

        <?php if ($res && $res->num_rows > 0): ?>
            <table class="tabla-carrito">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Costo</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    while ($producto = $res->fetch_assoc()):
                        $total += $producto['subtotal'];
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                            <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                            <td><?php echo $producto['cantidad']; ?></td>
                            <td>$<?php echo number_format($producto['subtotal'], 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="total">Total: $<?php echo number_format($total, 2); ?></div>
            <div class="action-buttons">
                <a href="carrito_1.php"><button class="btn-continuar">Regresar</button></a>
                <a href="finalizar_pedido.php"><button class="btn-continuar">Finalizar</button></a>
            </div>
        <?php else: ?>
            <p class="sin-productos">Pedido realizado con éxito!</p>
            <div class="action-buttons">
                <a href="index.php"><button class="btn-continuar">Volver al inicio</button></a>
            </div>
        <?php endif; ?>
    </section>

    <!-- Footer siempre al fondo -->
    <footer>
        <?php include 'pie_de_pagina.php'; ?>
    </footer>
</body>
</html>
