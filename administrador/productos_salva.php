<?php 
    require "funciones/conecta.php";
    $con = conecta();

    $nombre         = $_REQUEST['nombre'];
    $codigo         = $_REQUEST['codigo'];
    $descripcion    = $_REQUEST['descripcion'];
    $costo          = $_REQUEST['costo'];
    $stock          = $_REQUEST['stock'];

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

    $sql = "INSERT INTO `productos` (`nombre`, `codigo`, `descripcion`, `costo`, `stock`, `archivo_n`, `archivo`) 
        VALUES ('$nombre', '$codigo', '$descripcion', '$costo', '$stock', '$archivo_n', '$archivo');";
    $res = $con->query($sql);

    header("Location: productos_lista.php");

?>
