<?php 
    session_start();
    if (!isset($_SESSION['id_empleado'])) {
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

                // Validar campos vacíos
                if (nombre === "") {
                    $('#mensaje').show();
                    $('#mensaje').html('Faltan campos por llenar');
                    setTimeout(function() {
                        $('#mensaje').html('');
                        $('#mensaje').hide();
                    }, 5000);
                    return false;
                }

                // Enviar formulario
                document.productos.method = 'post';
                document.productos.action = 'promociones_actualiza.php';
                document.productos.submit();
            }
        </script>
        <title>Edición de promociones</title>
    </head>
    <body>
        <!-- Incluir el menú -->
        <?php include 'menu_navegacion.php'; ?>
        <div class="detalle-contenedor">
            <h1 style="margin-bottom: 30px;">Edición de promociones</h1> 
            <!-- Formulario -->
            <?php
                // Obtener los datos desde la URL usando GET
                $id = $_GET['id'];
                $nombre = $_GET['nombre'];
            ?>
            <form name="productos" id="formulario" enctype="multipart/form-data" onsubmit="validarFormulario(event, <?php echo $id; ?>);">
                <input type="hidden" name="id" value="<?php echo $id; ?>"> <!-- Campo oculto para enviar el id también -->
                <input type="text" name="nombre" id="name" value="<?php echo $nombre; ?>"/> 
                <div class="archivo-contenedor">
                    <input type="file" id="archivo" name="archivo">
                </div>
                <input type="submit" value="Enviar"/>
                <div class="link-centrado" id="mensaje"></div>
            </form>
            <div class="boton_regresar">
                <a href="promociones_lista.php">Regresar</a>
            </div>
        </div>
    </body>
</html>
