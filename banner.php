<?php
   require_once "funciones/conecta.php";
   if (!isset($con)) $con = conecta();

    $sql = "SELECT * FROM promociones WHERE eliminado = 0 ORDER BY RAND() LIMIT 1";
    $res = $con->query($sql);

    if ($res->num_rows > 0) {
        $banner = $res->fetch_assoc();
        echo "<img class='banner_img' src='archivos/" . $banner['archivo'] . "' alt='" . $banner['nombre'] . "'>";
    } else {
        echo "<p>No hay promociones disponibles.</p>";
    }
    $con->close();
?>
