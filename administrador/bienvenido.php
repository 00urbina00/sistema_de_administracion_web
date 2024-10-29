<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
    <head>
        <title>Página de Bienvenida</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
        <div style="text-align:center;">
            <table border="1" width="100%" style="margin: 0 auto; background-color: #084853">
                <tr height="30">
                    <td colspan="2" align="center" style="font-size: 20px;"><a href="bienvenida.php" style="text-decoration: none; color: #FFFFFF">Inicio</a></td>
                    <td colspan="2" align="center" style="font-size: 20px;"><a href="bienvenida.php" style="text-decoration: none; color: #FFFFFF">Empleados</a></td>
                    <td colspan="2" align="center" style="font-size: 20px;"><a href="bienvenida.php" style="text-decoration: none; color: #FFFFFF">Productos</a></td>
                    <td colspan="2" align="center" style="font-size: 20px;"><a href="bienvenida.php" style="text-decoration: none; color: #FFFFFF">Promociones</a></td>
                    <td colspan="2" align="center" style="font-size: 20px;"><a href="bienvenida.php" style="text-decoration: none; color: #FFFFFF">Pedidos</a></td>
                    <td colspan="2" align="center" style="font-size: 20px;"><a href="bienvenida.php" style="text-decoration: none; color: #FFFFFF">Bienvenido <?php echo $_SESSION['nombre']; ?></td>
                    <td colspan="2" align="center" style="font-size: 20px;"><a href="bienvenida.php" style="text-decoration: none; color: #FFFFFF">Cerrar sesión</td>
                </tr>
            </table>
        </div>
        <h1>Bienvenido, <?php echo $_SESSION['nombre']; ?>!</h1>
    </body>
</html>


