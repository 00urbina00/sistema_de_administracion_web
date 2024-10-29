<?php
    // Script de PHP 
    require "funciones/conecta.php";
    $con        = conecta();
    $usuario     = $_REQUEST['usuario'];
    $pass       = $_REQUEST['pass'];

    $sql = "SELECT * FROM empleados WHERE correo = '$usuario' AND eliminado = 0";

    $res = $con->query($sql);

    if ($res && $res->num_rows > 0) {
        $passEnc = md5($pass);
        $fila = $res->fetch_assoc();

        if ($fila['pass'] == $passEnc ) {    // La contraseña es correcta
            echo 1;
        } else {                            // La contraseña es incorrecta
            echo 0;
        }
    } else {
        // No hay resultados (el correo no existe)
        echo -1;
    }
?>
