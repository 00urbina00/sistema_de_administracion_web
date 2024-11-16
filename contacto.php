<?php
    session_start();
    if (!isset($_SESSION['id_cliente'])) {
        // header('Location: index.php');
    }
?>
<!DOCTYPE html>
    <head>
        <title>Contacto</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <style>
            /* Sección de Contacto */
            body, html {
                height: 100%;
                margin: 0;
                display: flex;
                flex-direction: column;
            }
        </style>
    </head>
    <body>
        <header>
            <!-- Menú de navegación -->
            <?php include 'menu_navegacion.php'; ?>
        </header>
        <!-- Sección de Contacto -->
        <section class="contacto">
            <h2>Contáctanos</h2>
            <form action="recibe.php" method="POST">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>

                <label for="correo">Correo:</label>
                <input type="email" id="correo" name="correo" required>

                <label for="comentario">Comentario:</label>
                <textarea id="comentario" name="comentario" rows="4" required></textarea>
                
                <button type="submit">Enviar</button>
            </form>
        </section>
        <div style="flex: 1;"></div>

        <!-- Footer -->
        <footer>
            <?php include 'pie_de_pagina.php'; ?>
        </footer>
    </body>
</html>
