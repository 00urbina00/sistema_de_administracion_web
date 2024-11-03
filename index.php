<?php
    // Si la sesión está iniciada, redirigir a bienvenido.php
    session_start();
    if (isset($_SESSION['id'])) {
        // header('Location: bienvenido.php');
    }
?>
<!DOCTYPE html>
    <head>
        <title>Home</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <style>
            .detalle-contenedor {
                height: 400px;
                width: 600px;
                margin: 0 auto;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                margin-bottom: 100px;   
                text-align: center;
            }

            /* Centrar título y reducir márgenes */
            .detalle-contenedor h2 {
                margin-bottom: 100px;
                color: #084853;
                text-align: center;
            }
        </style>
    </head>
    <header>
        <div class="logo">LOGO</div>
        <nav>
            <a href="#">Home</a>
            <a href="#">Productos</a>
            <a href="#">Contacto</a>
            <a href="#">Carrito(0)</a>
        </nav>
    </header>

    <!-- Banner -->
    <section class="banner">
        <?php include 'banner.php'; ?>
    </section>

    <!-- Footer -->
    <footer>
        <p>mipagina.com | todos los derechos reservados | <a href="#">Políticas de Privacidad</a> | <a href="#">Términos y Condiciones</a></p>
    </footer>
</body>
</html>
