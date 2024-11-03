<?php 
    require "funciones/conecta.php";
    $con = conecta();

    $nombre         = $_REQUEST['nombre'];

    // Procesar nombres el archivo
    $file_name      = $_FILES['archivo']['name'];
    $file_tmp       = $_FILES['archivo']['tmp_name'];

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

    $sql = "INSERT INTO `promociones` (`nombre`, `archivo`) 
        VALUES ('$nombre', '$archivo');";
    $res = $con->query($sql);

    header("Location: promociones_lista.php");

?>
