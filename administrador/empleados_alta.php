<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <script src="js/jquery.js"></script>
        <script>
            function valida_correo() {
                return new Promise((resolve, reject) => {
                    var correo = document.user.correo.value;
                    $.ajax({
                        url: 'existe_correo.php',
                        type: 'post',
                        dataType: 'text',
                        data: 'correo=' + correo,
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
            async function validarFormulario(event) {
                // Funcion de javascript
                event.preventDefault();

                var nombre = document.user.nombre.value.trim();
                var apellido = document.user.apellido.value.trim();
                var correo = document.user.correo.value.trim();
                var pass = document.user.pass.value.trim();
                var rol = document.user.rol.value;

                // Validar campos vacíos
                if (nombre === "" || apellido === "" || correo === "" || pass === "" || rol === "0") {
                    $('#mensaje').show();
                    $('#mensaje').html('Faltan campos por llenar');
                    setTimeout(function() {
                        $('#mensaje').html('');
                        $('#mensaje').hide();
                    }, 5000);
                    return false;
                }

                // Validar correo con AJAX
                const correoValido = await valida_correo();
                if (!correoValido) {
                    return false; // No enviar formulario si el correo ya existe
                }

                // Enviar formulario
                document.user.method = 'post';
                document.user.action = 'empleados_salva.php';
                document.user.submit();
            }
        </script>
        <title>Formulario</title>
    </head>
    <body>
        <h1>Alta de empleados</h1> 
        <div text-align="center">
            <!-- Formulario -->
            <form name="user" id="formulario" onsubmit="validarFormulario(event);">
                <input type="text" name="nombre" id="name" placeholder="Escribe tu nombre"/> 
                <input type="text" name="apellido" id="last_name" placeholder="Escribe tu apellido"/> 
                <div style="position: relative;">
                    <input onblur="valida_correo();" type="text" name="correo" id="mail" value="@udg.mx"/> 
                    <div style="color: red; display: none;" id="correoError"></div> <!-- Contenedor para el mensaje de error -->
                </div>
                <input type="password" name="pass" id="pass" placeholder="Escribe tu password"/> 
                <select name="rol" id="rol">
                    <option value="0">Seleccionar</option>
                    <option value="1">Gerente</option>
                    <option value="2">Ejecutivo</option>
                </select>
                <input type="submit" value="Enviar"/>
                <div class="link-centrado" id="mensaje"></div>
            </form>
            <div class="link-centrado">
                <a href="empleados_lista.php">Regresar al listado</a>
            </div>
        </div>
    </body>
</html>
