<?php
    require "funciones/conecta.php";
    $con    = conecta();
    $id     = $_REQUEST['id'];
    $sql = "UPDATE empleados SET eliminado=1 WHERE id=$id";
    $res = $con->query($sql);
    
    if ($res) {
        echo 1;
    } else {
        echo 0;
    }
?>
