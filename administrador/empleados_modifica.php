<?php 
    session_start();
    if (!isset($_SESSION['id'])) {
        header('Location: index.php');
    }
?>
<html>
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
            function valida_correo(id) {
                return new Promise((resolve, reject) => {
                    var correo = document.user.correo.value;
                    $.ajax({
                        url: 'existe_correo.php',
                        type: 'post',
                        dataType: 'text',
                        data: {
                            correo: correo,
                            id: id
                        },
                        success: function(res) {
                            console.log(res);
                            if (res == 1) {
                                $('#correoError').show();
                                $('#correoError').html('El correo '+correo + ' ya existe.');
                                setTimeout(function() {
                                    $('#correoError').html('');
                                    $('#correoError').hide();
                                }, 5000);
                                resolve(false); // El correo ya existe
                            } else {
                                $('#correoError').hide();
                                resolve(true); // El correo no existe
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

                var nombre = document.user.nombre.value.trim();
                var apellidos = document.user.apellidos.value.trim();
                var correo = document.user.correo.value.trim();
                var pass = document.user.pass.value.trim();
                var rol = document.user.rol.value;

                // Validar campos vacíos
                if (nombre === "" || apellidos === "" || correo === "" || rol === "0") {
                    $('#mensaje').show();
                    $('#mensaje').html('Faltan campos por llenar');
                    setTimeout(function() {
                        $('#mensaje').html('');
                        $('#mensaje').hide();
                    }, 5000);
                    return false;
                }

                // Validar correo con AJAX
                const correoValido = await valida_correo(id);
                if (!correoValido) {
                    return false; // No enviar formulario si el correo ya existe
                }

                // Enviar formulario
                document.user.method = 'post';
                document.user.action = 'empleados_actualiza.php';
                document.user.submit();
            }
        </script>
        <title>Edición de empleados</title>
    </head>
    <body>
        <!-- Incluir el menú -->
        <?php include 'menu_navegacion.php'; ?>
        <div class="detalle-contenedor">
            <h1 style="margin-bottom: 30px;">Edición de empleados</h1> 
            <!-- Formulario -->
            <?php
                // Obtener los datos desde la URL usando GET
                $id = $_GET['id'];
                $nombre = $_GET['nombre'];
                $apellidos = $_GET['apellidos'];
                $correo = $_GET['correo'];
                $tmp_rol = $_GET['rol'];
                $rol = ($tmp_rol == 1) ? 'Gerente' : 'Ejecutivo';
            ?>
            <form name="user" id="formulario" enctype="multipart/form-data" onsubmit="validarFormulario(event, <?php echo $id; ?>);">
                <input type="hidden" name="id" value="<?php echo $id; ?>"> <!-- Campo oculto para enviar el id también -->
                <input type="text" name="nombre" id="name" value="<?php echo $nombre; ?>"/> 
                <input type="text" name="apellidos" id="last_name" value="<?php echo $apellidos; ?>"/> 
                <div style="position: relative;">
                    <input onblur="valida_correo(<?php echo $id; ?>);" type="text" name="correo" id="mail" value="<?php echo $correo; ?>"/> 
                    <div style="color: red; display: none;" id="correoError"></div> <!-- Contenedor para el mensaje de error -->
                </div>
                <input type="password" name="pass" id="pass" placeholder="Escribe tu password"/> 
                <select name="rol" id="rol">
                    <option value="0">Seleccionar</option>
                    <option value="1" <?php if ($tmp_rol == 1) echo 'selected'; ?>>Gerente</option>
                    <option value="2" <?php if ($tmp_rol == 2) echo 'selected'; ?>>Ejecutivo</option>
                </select>
                <div class="archivo-contenedor">
                    <input type="file" id="archivo" name="archivo">
                </div>
                <input type="submit" value="Enviar"/>
                <div class="link-centrado" id="mensaje"></div>
            </form>
            <div class="boton_regresar">
                <a href="empleados_lista.php">Regresar</a>
            </div>
        </div>
    </body>
</html>
