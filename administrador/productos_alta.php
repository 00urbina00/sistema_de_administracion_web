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
            function valida_codigo() {
                return new Promise((resolve, reject) => {
                    var codigo = document.productos.codigo.value;
                    var id = -1; // No modificar los campos
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
                                $('#correoError').html('El codigo '+ codigo + ' ya existe.');
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
            async function validarFormulario(event) {
                // Funcion de javascript
                event.preventDefault();

                var nombre = document.productos.nombre.value.trim();
                var codigo = document.productos.codigo.value.trim();
                var descripcion = document.productos.descripcion.value.trim();
                var costo = document.productos.costo.value.trim();
                var stock = document.productos.stock.value.trim();

                var archivo = document.productos.archivo.files[0];

                // Validar campos vacíos
                if (nombre === "" || codigo === "" || descripcion === "" || costo === "" || stock === "" || archivo === undefined) {
                    $('#mensaje').show();
                    $('#mensaje').html('Faltan campos por llenar');
                    setTimeout(function() {
                        $('#mensaje').html('');
                        $('#mensaje').hide();
                    }, 5000);
                    return false;
                }

                // Validar correo con AJAX
                const codigoValido = await valida_codigo();
                if (!codigoValido) {
                    return false; // No enviar formulario si el correo ya existe
                }

                // Enviar formulario
                document.productos.method = 'post';
                document.productos.action = 'productos_salva.php';
                document.productos.submit();
            }
        </script>
        <title>Alta de productos</title>
    </head>
    <body>
        <!-- Incluir el menú -->
        <?php include 'menu_navegacion.php'; ?>
        <div class="detalle-contenedor">
            <h1 style="margin-bottom: 30px;">Alta de productos</h1> 
            <!-- Formulario -->
            <form name="productos" id="formulario" enctype="multipart/form-data" method="post" action="empleados_registra.php" onsubmit="validarFormulario(event);">
                <input type="text" name="nombre" id="name" placeholder="Escribe el nombre del producto" autocomplete="on"/> 
                <div style="position: relative;">
                    <input onblur="valida_codigo();" type="text" name="codigo" id="codigo" placeholder="Escribe el código del producto"/> 
                    <div style="color: red; display: none;" id="correoError"></div> <!-- Contenedor para el mensaje de error -->
                </div>
                <textarea name="descripcion" id="descripcion" class="large-textarea"placeholder="Agrega una descripción al producto" autocomplete="off"></textarea>
                <!-- input type="text" name="descripcion" id="descripcion" placeholder="Agrega una descripción al producto"/ --> 
                <input type="text" name="costo" id="costo" placeholder="Escribe el costo del producto"/>
                <input type="text" name="stock" id="stock" placeholder="Existencia del producto"/>
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