<?php
    session_start();
?>

<!DOCTYPE html>
    <head>
        <title>Home</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <style>
            .boton-volver {
                background-color: #084853;
                color: #fff;
                border: none;
                padding: 5px 8px;
                border-radius: 5px;
                cursor: pointer;
                text-decoration: none;
                font-size: 1em;
            }
        </style>
        <script src="js/jquery.js"></script>
        <script>
            function agregarAlCarrito(event, id_producto) {
                event.preventDefault(); // Prevenir el envío del formulario
                // Obtener la cantidad seleccionada por el usuario
                const cantidad = document.getElementById(`cantidad_${id_producto}`).value;
                const mensajeContenedor = document.getElementById(`mensaje_${id_producto}`);

                $.ajax({
                    url: 'agregar_al_carrito.php',
                    type: 'POST',
                    data: {
                        id_producto: id_producto,
                        cantidad: cantidad
                    },
                    success: function(response) {
                        console.log("Response as object:", response); // Verifica que sea un objeto válido
                        
                        if (response.success) {
                            // Actualiza el contador de productos si está definido
                            if (response.productCount !== undefined) {
                                $('#productCount').text(response.productCount);
                            }
                            // Mostrar mensaje de éxito
                            mensajeContenedor.style.color = 'green';
                            mensajeContenedor.textContent = '¡Agregado con éxito!';
                        } else {
                            // Mostrar mensaje de error
                            mensajeContenedor.style.color = 'red';
                            mensajeContenedor.textContent = response.message || 'Error al agregar el producto.';
                        }
                        // Ocultar el mensaje después de 5 segundos
                        setTimeout(() => {
                            mensajeContenedor.textContent = '';
                        }, 3000);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error en la solicitud AJAX:", status, error);
                        mensajeContenedor.style.color = 'red';
                        mensajeContenedor.textContent = 'Error en la conexión.';
                        // Ocultar el mensaje después de 5 segundos
                        setTimeout(() => {
                            mensajeContenedor.textContent = '';
                        }, 3000);                        
                    }
                });
            }
        </script>
    </head>
    <body>
        <header>
            <!-- Menú de navegación -->
            <?php include 'menu_navegacion.php'; ?>
        </header>
        <!-- Banner -->
        <section class="banner">
            <div class="banner_box">
                <?php include 'banner.php'; ?>
            </div>
        </section>
        <!-- Tabla de Productos -->
        <section class="productos">
            <div class="tabla-productos">
                <?php
                    require_once "funciones/conecta.php";
                    $con = conecta();
                
                    // Selecciona 6 productos aleatorios que no estén eliminados
                    $sql = "SELECT * FROM productos WHERE eliminado = 0 ORDER BY RAND() LIMIT 6";
                    $res = $con->query($sql);
                    $num = $res->num_rows;

                    while($producto = $res->fetch_assoc()){
                        echo "<div class='producto'>
                            <a href='detalle_producto.php?id=".$producto['id']."'>
                                <img src='archivos/".$producto['archivo']."' alt='".$producto['nombre']."'>
                            </a>
                            <h3>".$producto['nombre']."</h3>
                            <p>Código: ".$producto['codigo']."</p>
                            <p>Precio: $".$producto['costo']."</p>";
                        
                        // Mostrar el formulario solo si el usuario tiene una sesión iniciada
                        if (isset($_SESSION['id_cliente'])) {
                            echo "
                                <!-- Formulario para agregar producto con stock como límite -->
                                <form onsubmit='agregarAlCarrito(event, ".$producto['id'].")'>
                                    <label for='cantidad_".$producto['id']."'>Cantidad:</label>
                                    <input type='number' id='cantidad_".$producto['id']."' name='cantidad' value='1' min='1' max='".$producto['stock']."' style='width: 50px; text-align: center;'>
                                    <input type='hidden' name='id_producto' value='".$producto['id']."'>
                                    <button type='submit' class='boton-volver'>Agregar</button>
                                    <p id='mensaje_".$producto['id']."' style='margin-top: 5px; font-size: 0.9em;'></p>
                                </form>";

                        } else {
                            echo "<p><em>Inicia sesión para agregar al carrito</em></p>";
                        }
                
                        echo "</div>";
                    }
                ?>
            </div>
        </section>
        <!-- Footer -->
        <footer>
            <?php include 'pie_de_pagina.php'; ?>
        </footer>
    </body>
</html>
