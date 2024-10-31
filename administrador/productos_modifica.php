<?php 
    session_start();
    if (!isset($_SESSION['id'])) {
        header('Location: index.php');
    }
?>
<!DOCTYPE html>
    <head>
    <style>
            .boton_regresar {
                display: flex;
                justify-content: center;
                margin-top: 20px;
            }
            .boton_regresar a {
                margin-right: 15px;
                text-decoration: none;
                padding: 10px 15px;
                background-color: #026778;
                color: #FFFFFF;
                border-radius: 5px;
                transition: background-color 0.3s;
            }
            .boton_regresar a:hover {
                background-color: #034854;
            }
            .detalle-contenedor {
                margin-top: 20px;
            }
        </style>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <script src="js/jquery.js"></script>
        <script>
            function valida_codigo(id) {
                return new Promise((resolve, reject) => {
                    var codigo = document.productos.codigo.value;
                    $.ajax({
                        url: 'existe_codigo.php',
                        type: 'post',
                        dataType: 'text',
                        data: {
                            codigo: codigo,
                            id: id
                        },
                        success: function(res) {
                            console.log(res);
                            if (res == 1) {
                                $('#correoError').show();
                                $('#correoError').html('El código '+ codigo + ' ya existe.');
                                setTimeout(function() {
                                    $('#correoError').html('');
                                    $('#correoError').hide();
                                }, 5000);
                                resolve(false); // El codigo ya existe
                            } else {
                                $('#correoError').hide();
                                resolve(true); // El codigo no existe
                            }
                        },
                        error: function() {
                            $('#correoError').hide();
                            reject();
                        }
                    });
                });
            }
            async function validarFormulario(event, id) {
                // Funcion de javascript
                event.preventDefault();

                var nombre = document.productos.nombre.value.trim();
                var codigo = document.productos.codigo.value.trim();
                var descripcion = document.productos.descripcion.value.trim();
                var costo = document.productos.costo.value.trim();
                var stock = document.productos.stock.value.trim();

                // Validar campos vacíos
                if (nombre === "" || codigo === "" || descripcion === "" || costo === "" || stock === "") {
                    $('#mensaje').show();
                    $('#mensaje').html('Faltan campos por llenar');
                    setTimeout(function() {
                        $('#mensaje').html('');
                        $('#mensaje').hide();
                    }, 5000);
                    return false;
                }

                // Validar correo con AJAX
                const codigoValido = await valida_codigo(id);
                if (!codigoValido) {
                    return false; // No enviar formulario si el codigo ya existe
                }

                // Enviar formulario
                document.productos.method = 'post';
                document.productos.action = 'productos_actualiza.php';
                document.productos.submit();
            }
        </script>
        <title>Edición de productos</title>
    </head>
    <body>
        <!-- Incluir el menú -->
        <?php include 'menu_navegacion.php'; ?>
        <div class="detalle-contenedor">
            <h1 style="margin-bottom: 30px;">Edición de productos</h1> 
            <!-- Formulario -->
            <?php
                // Obtener los datos desde la URL usando GET
                $id = $_GET['id'];
                $nombre = $_GET['nombre'];
                $codigo = $_GET['codigo'];
                $descripcion = $_GET['descripcion'];
                $costo = $_GET['costo'];
                $stock = $_GET['stock'];
            ?>
            <form name="productos" id="formulario" enctype="multipart/form-data" onsubmit="validarFormulario(event, <?php echo $id; ?>);">
                <input type="hidden" name="id" value="<?php echo $id; ?>"> <!-- Campo oculto para enviar el id también -->
                <input type="text" name="nombre" id="name" value="<?php echo $nombre; ?>"/> 
                <div style="position: relative;">
                    <input onblur="valida_codigo(<?php echo $id; ?>);" type="text" name="codigo" id="codigo" value="<?php echo $codigo; ?>"/> 
                    <div style="color: red; display: none;" id="correoError"></div> <!-- Contenedor para el mensaje de error -->
                </div>
                <textarea name="descripcion" id="descripcion" class="large-textarea"><?php echo $descripcion; ?></textarea>
                <!-- input type="text" name="descripcion" id="descripcion" placeholder="Agrega una descripción al producto"/ --> 
                <input type="text" name="costo" id="costo" value="<?php echo $costo; ?>"/>
                <input type="text" name="stock" id="stock" value="<?php echo $stock; ?>"/>
                <div class="archivo-contenedor">
                    <input type="file" id="archivo" name="archivo">
                </div>
                <input type="submit" value="Enviar"/>
                <div class="link-centrado" id="mensaje"></div>
            </form>
            <div class="boton_regresar">
                <a href="productos_lista.php">Regresar</a>
            </div>
        </div>
    </body>
</html>
