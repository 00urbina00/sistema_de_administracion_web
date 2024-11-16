<?php
    session_start();
    if (!isset($_SESSION['id_cliente'])) {
        // header('Location: index.php');
    }

    require_once "funciones/conecta.php";
    $con = conecta();

    // Variables para la paginación
    $productosPorPagina = 12; // 3 columnas x 4 filas
    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $offset = ($pagina - 1) * $productosPorPagina;

    // Consulta de productos no eliminados con límite y offset para paginación
    $sql = "SELECT * FROM productos WHERE eliminado = 0 LIMIT $productosPorPagina OFFSET $offset";
    $res = $con->query($sql);

    // Contar el total de productos no eliminados para calcular el número total de páginas
    $totalProductos = $con->query("SELECT COUNT(*) as total FROM productos WHERE eliminado = 0")->fetch_assoc()['total'];
    $totalPaginas = ceil($totalProductos / $productosPorPagina);
?>
<!DOCTYPE html>
    <head>
        <title>Productos</title>
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
                    data: { id_producto: id_producto, cantidad: cantidad },
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
        <!-- Sección de Productos -->
        <section class="productos">
            <h2 style="font-size: 1.2em; text-align: center;">Productos</h2>
            <div class="contenedor-productos">
                <div class="tabla-productos">
                    <?php
                        while ($producto = $res->fetch_assoc()) {
                            echo "
                                <div class='producto'>
                                    <a href='detalle_producto.php?id=" . $producto['id'] . "'>
                                        <img src='archivos/" . $producto['archivo'] . "' alt='" . $producto['nombre'] . "'>
                                    </a>
                                    <h3>" . $producto['nombre'] . "</h3>
                                    <p>Código: " . $producto['codigo'] . "</p>
                                    <p>Precio: $" . $producto['costo'] . "</p>";

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
                        $con->close();
                    ?>
                </div>
            </div>
            <!-- Paginación -->
            <div class="paginacion">
                    <?php
                        if($pagina > 1){
                            echo "<a href='productos.php?pagina=" . ($pagina - 1) . "'>Anterior</a>";
                        }
                        for($i = 1; $i <= $totalPaginas; $i++){
                            if ($i == $pagina) {
                                echo "<span class='pagina-actual'>$i</span>";
                            } else {
                                echo "<a href='productos.php?pagina=$i'>$i</a>";
                            }
                        }

                        if($pagina < $totalPaginas){
                            echo "<a href='productos.php?pagina=" . ($pagina + 1) . "'>Siguiente</a>";
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
