<?php
    session_start();
    require "funciones/conecta.php";
    $con = conecta();

    if (!isset($_SESSION['id_cliente'])) {
        header('Location: login.php');
        exit();
    }

    if (isset($_POST['id_producto'])) {
        $id_producto = $_POST['id_producto'];
        $id_cliente = $_SESSION['id_cliente'];

        // Verificar que el pedido exista
        $query_pedido = "SELECT id FROM pedidos WHERE id_cliente = $id_cliente AND status = 0 LIMIT 1";
        $result_pedido = $con->query($query_pedido);

        if ($result_pedido->num_rows > 0) {
            $pedido = $result_pedido->fetch_assoc();
            $id_pedido = $pedido['id'];

            // Eliminar el producto del carrito
            $query_eliminar = "
                DELETE FROM pedidos_productos
                WHERE id_pedido = $id_pedido AND id_producto = $id_producto
            ";
            if ($con->query($query_eliminar)) {
                $sql_cart = "SELECT SUM(pp.cantidad) AS total_productos 
                        FROM pedidos p 
                        JOIN pedidos_productos pp ON p.id = pp.id_pedido 
                        WHERE p.id_cliente = $id_cliente AND p.status = 0";
                $res_cart = $con->query($sql_cart);
                $total_productos = $res_cart->fetch_assoc()['total_productos'] ?? 0;
                $_SESSION['productCount'] = $total_productos;
                
                header('Location: carrito_1.php'); // Redirigir al carrito después de eliminar
                exit();
            } else {
                echo "Error al eliminar el producto";
            }
        }
    }
?>
