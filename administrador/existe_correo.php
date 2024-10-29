<?php
    // Script de PHP 
    require "funciones/conecta.php";
    $con    = conecta();
    $correo     = $_REQUEST['correo'];

    $sql = "SELECT COUNT(*) FROM empleados WHERE correo = '$correo'";

    $res = $con->query($sql);

    $total = $res->fetch_array()[0];
    
    if ($res) {
        
        if ($total > 0) {
            echo 1;  // El correo ya existe
        } else {
            echo 0;
        }
    } else {
        echo "Error en la consulta: " . $con->error;
    }
?>
