<?php 
    require "funciones/conecta.php";
    $con    = conecta();
    $numero     = $_REQUEST['numero'];
    $sql = "UPDATE empleados SET eliminado=1 WHERE id=$numero";
    
    $res = $con->query($sql);

    header("Location: empleados_lista.php");
?>
