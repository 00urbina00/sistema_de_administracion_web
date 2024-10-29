<?php 
    require "funciones/conecta.php";
    $con = conecta();

    $nombre     = $_REQUEST['nombre'];
    $apellidos   = $_REQUEST['apellidos'];
    $correo     = $_REQUEST['correo'];
    $pass       = $_REQUEST['pass'];
    $rol        = $_REQUEST['rol'];

    $passEnc    = md5($pass);

    // Procesar nombres el archivo
    $file_name = $_FILES['archivo']['name'];
    $file_tmp = $_FILES['archivo']['tmp_name'];

    // Agregar extension
    $arreglo = explode(".", $file_name);
    $ext = end($arreglo);

    // Carpeta para guardar archivos
    $dir = "archivos/";

    // Obtener nombre
    $file_enc = md5_file($file_tmp);
    $fileName = "$file_enc.$ext";

    copy($file_tmp, $dir.$fileName);

    $archivo_n  = $file_name;
    $archivo    = $fileName;

    $sql = "INSERT INTO `empleados` (`nombre`, `apellidos`, `correo`, `pass`, `rol`, `archivo_n`, `archivo`, `eliminado`) 
        VALUES ('$nombre', '$apellidos', '$correo', '$passEnc', '$rol', '$archivo_n', '$archivo', '0');";
    $res = $con->query($sql);

    header("Location: empleados_lista.php");

?>
