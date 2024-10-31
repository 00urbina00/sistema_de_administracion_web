<?php 
    require "funciones/conecta.php";
    $con = conecta();

    $id             = $_REQUEST['id'];
    $nombre         = $_REQUEST['nombre'];
    $codigo         = $_REQUEST['codigo'];
    $descripcion    = $_REQUEST['descripcion'];
    $costo          = $_REQUEST['costo'];
    $stock          = $_REQUEST['stock'];

    $sql = "UPDATE productos SET nombre='$nombre', codigo='$codigo', descripcion='$descripcion', costo='$costo', stock='$stock'";

    // Verificar si se ha subido un archivo nuevo
    if (!empty($_FILES['archivo']['name'])) {
        // Procesar los nombres
        $file_name = $_FILES['archivo']['name'];
        $file_tmp = $_FILES['archivo']['tmp_name'];

        $arreglo = explode(".", $file_name);
        $ext = end($arreglo);

        $dir = "archivos/";

        $file_enc = md5_file($file_tmp);
        $fileName = "$file_enc.$ext";

        if (copy($file_tmp, $dir.$fileName)) {
            // Si la copia es exitosa, actualiza la consulta
            $sql .= ", archivo_n='$file_name', archivo='$fileName'";
        } else {
            // Si la copia falla, mostrar mensaje de error
            die("Error al guardar el archivo");
        }
    }

    // Finalizar la consulta SQL
    $sql .= " WHERE id='$id'";

    // Ejecutar la consulta SQL
    $res = $con->query($sql);

    header("Location: productos_lista.php");
?>
