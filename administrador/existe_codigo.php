<?php
    // Script de PHP 
    require "funciones/conecta.php";
    $con        = conecta();
    $codigo     = $_REQUEST['codigo'];
    $id         = $_REQUEST['id'];
    $codigo     = trim($codigo);

    if ($id != -1) {
        $sql = "SELECT COUNT(*) FROM productos WHERE codigo = '$codigo' AND id != $id";
    } else {
        $sql = "SELECT COUNT(*) FROM productos WHERE codigo = '$codigo'";
    }

    $res = $con->query($sql);

    $total = $res->fetch_array()[0];
    
    if ($res) {
        
        if ($total > 0) {
            echo 1;  // El codigo ya existe
        } else {
            echo 0;
        }
    } else {
        echo "Error en la consulta: " . $con->error;
    }
?>
