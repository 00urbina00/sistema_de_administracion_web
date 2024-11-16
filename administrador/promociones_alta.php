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
                var archivo = document.productos.archivo.files[0];

                // Validar campos vacíos
                if (nombre === "" || archivo === undefined) {
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
                document.productos.action = 'promociones_salva.php';
                document.productos.submit();
            }
        </script>
        <title>Alta de promociones</title>
    </head>
    <body>
        <!-- Incluir el menú -->
        <?php include 'menu_navegacion.php'; ?>
        <div class="detalle-contenedor">
            <h1 style="margin-bottom: 30px;">Alta de promociones</h1> 
            <!-- Formulario -->
            <form name="productos" id="formulario" enctype="multipart/form-data" method="post" action="empleados_registra.php" onsubmit="validarFormulario(event);">
                <input type="text" name="nombre" id="name" placeholder="Escribe el nombre de la promoción" autocomplete="on"/> 
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
