<?php 
    require "funciones/conecta.php";
    $con = conecta();

    $nombre     = $_REQUEST['nombre'];
    $apellidos   = $_REQUEST['apellidos'];
    $correo     = $_REQUEST['correo'];
    $pass       = $_REQUEST['pass'];
    $rol        = $_REQUEST['rol'];

    $passEnc    = md5($pass);
    $archivo_n  = '';
    $archivo    = '';

    $sql = "INSERT INTO `empleados` (`nombre`, `apellidos`, `correo`, `pass`, `rol`, `archivo_n`, `archivo`, `eliminado`) 
        VALUES ('$nombre', '$apellidos', '$correo', '$passEnc', '$rol', '$archivo_n', '$archivo', '0');";
    $res = $con->query($sql);

    header("Location: empleados_lista.php");

?>
