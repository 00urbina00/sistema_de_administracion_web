<?php
    session_start();
    require_once "funciones/conecta.php";
    $con = conecta();

    // Obtén el ID del producto desde la URL y asegúrate de que sea seguro
    $id_producto = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    // Consulta para obtener los detalles del producto no eliminado
    $stmt = $con->prepare("SELECT * FROM productos WHERE id = ? AND eliminado = 0");
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    $res = $stmt->get_result();
    $producto = $res->fetch_assoc();

    if (!$producto) {
        header("Location: productos.php?error=ProductoNoEncontrado");
        exit;
    }

    // Consulta para obtener productos similares no eliminados de forma aleatoria
    $stmt_similares = $con->prepare("SELECT * FROM productos WHERE eliminado = 0 AND id != ? ORDER BY RAND() LIMIT 4");
    $stmt_similares->bind_param("i", $id_producto);
    $stmt_similares->execute();
    $res_similares = $stmt_similares->get_result();
?>

<!DOCTYPE html>
    <head>
        <title>Detalle del producto</title>
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
        <!-- Sección de Detalle de Producto -->
        <h2 style="font-size: 1.2em; text-align: center;">Detalle del producto</h2>
        <?php if($producto): ?>
            <div class="detalle-contenedor" style="max-width: 70%; width: 100%;">
                <div>
                    <img src="archivos/<?php echo $producto['archivo']; ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" style="width: 100%; max-width: 200px;">
                </div>
                <div style="text-align: center;">
                    <a href="productos.php"><button class="boton-volver">Regresar</button></a>
                </div>
                <div class="tabla-detalle">
                    <div class="fila-detalle">
                        <div class="columna-titulo">Nombre</div>
                        <div class="columna-valor"><?php echo $producto['nombre']; ?></div>
                    </div>
                    <div class="fila-detalle">
                        <div class="columna-titulo">Código</div>
                        <div class="columna-valor"><?php echo $producto['codigo']; ?></div>
                    </div>
                    <div class="fila-detalle">
                        <div class="columna-titulo">Descripción</div>
                        <div class="columna-valor"><?php echo $producto['descripcion']; ?></div>
                    </div>
                    <div class="fila-detalle">
                        <div class="columna-titulo">Costo</div>
                        <div class="columna-valor">$<?php echo $producto['costo']; ?></div>
                    </div>
                    <div class="fila-detalle">
                        <div class="columna-titulo">Existencias</div>
                        <div class="columna-valor"><?php echo $producto['stock']; ?></div>
                    </div>
                </div>

                <!-- Verificar si el usuario ha iniciado sesión para mostrar el formulario de agregar al carrito -->
                <?php if(isset($_SESSION['id_cliente'])): ?>
                    <form onsubmit="agregarAlCarrito(event, <?php echo $producto['id']; ?>)" style="padding: 10px;">
                        <label for="cantidad_<?php echo $producto['id']; ?>">Cantidad:</label>
                        <input type="number" id="cantidad_<?php echo $producto['id']; ?>" name="cantidad" value="1" min="1" max="<?php echo $producto['stock']; ?>" style="width: 50px; text-align: center;">
                        <input type="hidden" name="id_producto" value="<?php echo $producto['id']; ?>">
                        <button type="submit" class="boton-volver">Agregar al Carrito</button>
                        <p id="mensaje_<?php echo $producto['id']; ?>" style="margin-top: 5px; font-size: 0.9em;"></p>
                    </form>
                <?php else: ?>
                    <p>Inicia sesión para agregar productos al carrito.</p>
                <?php endif; ?>

                <div style="background-color: #f9f9f9; padding: 20px; border: 1px solid #dddddd;">
                    <h3 style="margin: 15px;">Productos Similares</h3>
                    <div class="similares">
                        <?php while ($similar = $res_similares->fetch_assoc()): ?>
                            <div class="producto-similar">
                                <a href="detalle_producto.php?id=<?php echo $similar['id']; ?>">
                                    <img src="archivos/<?php echo $similar['archivo']; ?>" alt="<?php echo $similar['nombre']; ?>" style="width: 100%; max-width: 150px;">
                                </a>
                                <h4><?php echo htmlspecialchars($similar['nombre']); ?></h4>
                                <p>Precio: $<?php echo $similar['costo']; ?></p>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <p>El producto no existe o no está disponible.</p>
        <?php endif; ?>
        <!-- Footer -->
        <footer>
            <?php include 'pie_de_pagina.php'; ?>
        </footer>
    </body>
</html>
