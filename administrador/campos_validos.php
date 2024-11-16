<?php
    // Script de PHP 
    session_start();    // Iniciar la sesión
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
            // Crear la sesión y las variables de sesión
            $_SESSION['id_empleado'] = $fila['id'];
            $_SESSION['nombre'] = $fila['nombre']." ".$fila['apellidos'];
            $_SESSION['correo'] = $fila['correo'];
            echo 1;
        } else {                            // La contraseña es incorrecta
            echo 0;
        }
    } else {
        // No hay resultados (el correo no existe)
        echo -1;
    }
?>
