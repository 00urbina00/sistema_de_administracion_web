<?php
    session_start();
    require "funciones/conecta.php";
    $con = conecta();

    if (!isset($_SESSION['id_cliente'])) {
        echo "No estás logueado."; // Mensaje de error si no hay sesión
        exit();
    }

    if (isset($_POST['id_producto']) && isset($_POST['cantidad'])) {
        $id_producto = $_POST['id_producto'];
        $cantidad = (int) $_POST['cantidad'];
        $id_cliente = $_SESSION['id_cliente'];

        // Verificar que el pedido exista
        $query_pedido = "SELECT id FROM pedidos WHERE id_cliente = $id_cliente AND status = 0 LIMIT 1";
        $result_pedido = $con->query($query_pedido);

        if ($result_pedido->num_rows > 0) {
            $pedido = $result_pedido->fetch_assoc();
            $id_pedido = $pedido['id'];

            // Actualizar la cantidad del producto en el carrito
            $query_actualizar = "
                UPDATE pedidos_productos
                SET cantidad = $cantidad
                WHERE id_pedido = $id_pedido AND id_producto = $id_producto
            ";

            if ($con->query($query_actualizar)) {
                $sql_cart = "SELECT SUM(pp.cantidad) AS total_productos 
                        FROM pedidos p 
                        JOIN pedidos_productos pp ON p.id = pp.id_pedido 
                        WHERE p.id_cliente = $id_cliente AND p.status = 0";
                $res_cart = $con->query($sql_cart);
                $total_productos = $res_cart->fetch_assoc()['total_productos'] ?? 0;
                $_SESSION['productCount'] = $total_productos;

                echo json_encode(['success' => true, 'productCount' => $total_productos]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar la cantidad']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'No hay pedido']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Datos faltantes']);
    }
?>
