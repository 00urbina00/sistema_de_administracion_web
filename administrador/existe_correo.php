<?php
    // Script de PHP 
    require "funciones/conecta.php";
    $con    = conecta();
    $correo     = $_REQUEST['correo'];
    $id    = $_REQUEST['id'];
    $correo = trim($correo);

    if ($id != -1) {
        $sql = "SELECT COUNT(*) FROM empleados WHERE correo = '$correo' AND id != $id";
    } else {
        $sql = "SELECT COUNT(*) FROM empleados WHERE correo = '$correo'";
    }

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
