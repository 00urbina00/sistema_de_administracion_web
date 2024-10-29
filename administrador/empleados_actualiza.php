<?php 
    require "funciones/conecta.php";
    $con = conecta();

    $id         = $_REQUEST['id'];  // Asegúrate de obtener el id del empleado
    $nombre     = $_REQUEST['nombre'];
    $apellidos  = $_REQUEST['apellidos'];
    $correo     = $_REQUEST['correo'];
    $pass       = $_REQUEST['pass'];
    $rol        = $_REQUEST['rol'];

    $passEnc    = md5($pass);

    if ($pass == '') {
        $sql = "UPDATE empleados SET nombre='$nombre', apellidos='$apellidos', correo='$correo', rol='$rol' WHERE id='$id'";
    } else {
        $sql = "UPDATE empleados SET nombre='$nombre', apellidos='$apellidos', correo='$correo', pass='$passEnc', rol='$rol' WHERE id='$id'";
    }

    $res = $con->query($sql);

    header("Location: empleados_lista.php");
?>