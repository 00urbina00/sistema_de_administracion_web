<?php
    // Script de PHP 
    session_start();    // Iniciar la sesión
    require "funciones/conecta.php";
    $con        = conecta();
    $usuario     = $_REQUEST['usuario'];
    $pass       = $_REQUEST['pass'];

    $sql = "SELECT * FROM clientes WHERE correo = '$usuario' AND eliminado = 0";

    $res = $con->query($sql);

    if ($res && $res->num_rows > 0) {
        $passEnc = md5($pass);
        $fila = $res->fetch_assoc();

        if ($fila['pass'] == $passEnc ) {    // La contraseña es correcta
            // Crear la sesión y las variables de sesión
            $_SESSION['id_cliente'] = $fila['id'];
            $_SESSION['nombre'] = $fila['nombre']." ".$fila['apellidos'];
            $_SESSION['correo'] = $fila['correo'];

            // Consultar el total de productos en el carrito
            $id_cliente = $fila['id'];
            $sql_cart = "SELECT SUM(pp.cantidad) AS total_productos 
                        FROM pedidos p 
                        JOIN pedidos_productos pp ON p.id = pp.id_pedido 
                        WHERE p.id_cliente = $id_cliente AND p.status = 0";
            $res_cart = $con->query($sql_cart);
            $total_productos = $res_cart->fetch_assoc()['total_productos'] ?? 0;
            $_SESSION['productCount'] = $total_productos;

            echo 1;
        } else {    // La contraseña es incorrecta
            echo 0;
        }
    } else {
        // No hay resultados (el correo no existe)
        echo -1;
    }
?>
