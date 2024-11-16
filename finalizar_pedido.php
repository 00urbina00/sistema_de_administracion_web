<?php
session_start();
require "funciones/conecta.php";
$con = conecta();

if (!isset($_SESSION['id_cliente'])) {
    header('Location: index.php');
    exit();
}

$id_cliente = $_SESSION['id_cliente'];

// Actualizar el estado del pedido
$query = "UPDATE pedidos SET status = 1 WHERE id_cliente = $id_cliente AND status = 0";
if ($con->query($query)) {
    $_SESSION['productCount'] = 0;
    header('Location: carrito_2.php');
    exit();
} else {
    echo "Error al finalizar el pedido.";
}
?>
