<?php
    require "funciones/conecta.php";
    $con    = conecta();
    $id     = $_REQUEST['id'];
    $sql = "SELECT nombre, apellidos, correo, rol FROM empleados WHERE id='$id'";
    $res = $con->query($sql);

    if ($res && $res->num_rows > 0) {
        $fila = $res->fetch_assoc();
        // Devolver los datos en formato JSON
        echo json_encode($fila);
    } else {
        echo json_encode(['error' => 'Empleado no encontrado']);
    }
?>
