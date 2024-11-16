<?php
    session_start();
    if (!isset($_SESSION['id_empleado'])) {
        header('Location: index.php');
    }
?>

<!DOCTYPE html>
    <head>
        <title>Página de Bienvenida</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
        <!-- Incluir el menú -->
        <?php include 'menu_navegacion.php'; ?>
        <h1>Bienvenido, <?php echo $_SESSION['nombre']; ?>!</h1>
    </body>
</html>


