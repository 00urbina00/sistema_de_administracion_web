<?php
session_start();

require "funciones/conecta.php";
$con = conecta();

header('Content-Type: application/json'); // Asegura que la salida sea JSON

if (isset($_SESSION['id_cliente']) && isset($_POST['id_producto']) && isset($_POST['cantidad'])) {
    $id_cliente = $_SESSION['id_cliente'];
    $id_producto = (int)$_POST['id_producto'];
    $cantidad = (int)$_POST['cantidad'];

    // Paso 1: Verificar si existe un pedido abierto para el cliente
    $sql_pedido_abierto = "SELECT id FROM pedidos WHERE id_cliente = $id_cliente AND status = 0";
    $res_pedido = $con->query($sql_pedido_abierto);

    if (!$res_pedido) {
        echo json_encode(['success' => false, 'message' => "Error en la consulta de pedido abierto: " . $con->error]);
        exit;
    }

    if ($res_pedido->num_rows > 0) {
        $pedido_abierto = $res_pedido->fetch_assoc();
        $id_pedido = $pedido_abierto['id'];
    } else {
        $sql_nuevo_pedido = "INSERT INTO pedidos (id_cliente, fecha, status) VALUES ($id_cliente, NOW(), 0)";
        if (!$con->query($sql_nuevo_pedido)) {
            echo json_encode(['success' => false, 'message' => "Error al crear un nuevo pedido: " . $con->error]);
            exit;
        }
        $id_pedido = $con->insert_id;
    }

    // Paso 2: Agregar o actualizar el producto en pedidos_productos
    $sql_producto_en_pedido = "SELECT id, cantidad FROM pedidos_productos WHERE id_pedido = $id_pedido AND id_producto = $id_producto";
    $res_producto = $con->query($sql_producto_en_pedido);

    if (!$res_producto) {
        echo json_encode(['success' => false, 'message' => "Error en la consulta de producto en pedido: " . $con->error]);
        exit;
    }

    if ($res_producto->num_rows > 0) {
        $producto_pedido = $res_producto->fetch_assoc();
        $nueva_cantidad = $producto_pedido['cantidad'] + $cantidad;
        $sql_update_cantidad = "UPDATE pedidos_productos SET cantidad = $nueva_cantidad WHERE id = " . $producto_pedido['id'];
        if (!$con->query($sql_update_cantidad)) {
            echo json_encode(['success' => false, 'message' => "Error al actualizar cantidad del producto en el pedido: " . $con->error]);
            exit;
        }
    } else {
        $sql_insert_producto = "INSERT INTO pedidos_productos (id_pedido, id_producto, cantidad, precio) 
                                VALUES ($id_pedido, $id_producto, $cantidad, (SELECT costo FROM productos WHERE id = $id_producto))";
        if (!$con->query($sql_insert_producto)) {
            echo json_encode(['success' => false, 'message' => "Error al insertar nuevo producto en pedidos_productos: " . $con->error]);
            exit;
        }
    }

    // Obtener el total de productos en el pedido abierto
    $sql_count = "SELECT SUM(cantidad) AS total FROM pedidos_productos WHERE id_pedido = $id_pedido";
    $res_count = $con->query($sql_count);
    $total_productos = $res_count->fetch_assoc()['total'] ?? 0;

    // Actualizar la variable de sesión
    $_SESSION['productCount'] = $total_productos;

    // Responder con JSON
    echo json_encode([
        'success' => true,
        'productCount' => $total_productos,
    ]);
    exit;
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Información incompleta o usuario no autenticado.',
    ]);
    exit;
}
