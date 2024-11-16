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

        // Consulta para obtener los productos en el carrito actual
        $query_productos = "
            SELECT p.id, p.nombre, p.stock, pp.cantidad, pp.precio, (pp.cantidad * pp.precio) AS subtotal
            FROM pedidos_productos pp
            JOIN productos p ON pp.id_producto = p.id
            WHERE pp.id_pedido = $id_pedido
        ";
        $res = $con->query($query_productos);
    }else{
        $res = $result_pedido; // No hay pedido activo
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Carrito de Compras</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <style>
            /* Estructura general */
            body, html {
                height: 100%;
                margin: 0;
                display: flex;
                flex-direction: column;
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
        <script src="js/jquery.js"></script>
        <script>
            function actualizarCantidad(id_producto, cantidad) {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        url: 'actualizar_cantidad.php',
                        type: 'POST',
                        data: {
                            id_producto: id_producto,
                            cantidad: cantidad
                        },
                        success: function(res) {
                            const data = JSON.parse(res);
                            
                            if (data.success) {
                                resolve(data);  // Resolvemos con el objeto que contiene el productCount
                            } else {
                                reject(data.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log("Error en la solicitud AJAX:", status, error);
                            reject("Error AJAX: " + xhr.responseText);
                        }
                    });
                });
            }

            async function onBlurActualizar(id_producto, cantidad) {
                try {
                    const success = await actualizarCantidad(id_producto, cantidad);
                    if (success) {
                        // Usar el nuevo contador recibido del servidor
                        const nuevoContador = success.productCount;  // Se obtiene desde la respuesta JSON
                        $('#productCount').text(nuevoContador); // Actualizamos el contador en el menú
                        console.log("Success");
                    }
                } catch (error) {
                    console.error("Error");
                }
            }

        </script>
    </head>
    <body>
        <header>
            <!-- Menú de navegación -->
            <?php include 'menu_navegacion.php'; ?>
        </header>

        <!-- Barra de progreso -->
        <div class="progress-bar-container">
            <div class="progress-step active"></div>
            <div class="progress-step"></div>
        </div>

        <!-- Sección del Carrito -->
        <!-- Sección del Carrito -->
        <section class="carrito">
            <h2>Carrito</h2>
            
            <?php if ($res->num_rows > 0): ?>
                <!-- Si hay productos en el carrito -->
                <table class="tabla-carrito" style="text-align: center;">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Costo</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $total = 0;
                        while ($producto = $res->fetch_assoc()) {
                            echo "
                                <tr>
                                    <td>" . htmlspecialchars($producto['nombre']) . "</td>
                                    <td>$" . number_format($producto['precio'], 2) . "</td>
                                    <td>
                                        <input type='number' value='" . $producto['cantidad'] . "' min='1' max='".$producto['stock']."' onblur='onBlurActualizar(" . $producto['id'] . ", this.value)' />
                                    </td>
                                    <td>$" . number_format($producto['subtotal'], 2) . "</td>
                                    <td>
                                        <form action='eliminar_producto.php' method='POST' style='display:inline;'>
                                            <input type='hidden' name='id_producto' value='" . $producto['id'] . "'>
                                            <button type='submit' class='btn-eliminar'>Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            ";
                            $total += $producto['subtotal'];
                        }
                    ?>
                    </tbody>
                </table>

                <div class="total">Total: $<?php echo number_format($total, 2); ?></div>

                <div style="text-align: center; margin-top: 20px;">
                    <a href="carrito_2.php"><button class="btn-continuar">Continuar</button></a>
                </div>
            <?php else: ?>
                <!-- Si no hay productos en el carrito -->
                <p class="sin-productos">Aún no hay productos en el carrito</p>
            <?php endif; ?>
        </section>

        <!-- Footer siempre al fondo -->
        <footer>
            <?php include 'pie_de_pagina.php'; ?>
        </footer>
    </body>
</html>
