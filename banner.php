<?php
 require "funciones/conecta.php";
 $con    = conecta();

$sql = "SELECT * FROM promociones ORDER BY RAND() LIMIT 1";
$res = $con->query($sql);

if ($res->num_rows > 0) {
    $banner = $res->fetch_assoc();
    echo "<h1>" . $banner['nombre'] . "</h1>";
    echo "<img src='ruta_a_tus_imagenes/" . $banner['archivo'] . "' alt='" . $banner['nombre'] . "'>";
} else {
    echo "<p>No hay promociones disponibles.</p>";
}
$con->close();
?>
