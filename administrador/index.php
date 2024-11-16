<?php
    // Si la sesión está iniciada, redirigir a bienvenido.php
    session_start();
    if (isset($_SESSION['id_empleado'])) {
        header('Location: bienvenido.php');
    }
?>
<!DOCTYPE html>
    <head>
        <title>Login</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <style>
            .detalle-contenedor {
                height: 400px;
                width: 600px;
                margin: 0 auto;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                margin-bottom: 100px;   
                text-align: center;
            }

            /* Centrar título y reducir márgenes */
            .detalle-contenedor h2 {
                margin-bottom: 100px;
                color: #084853;
                text-align: center;
            }
        </style>
        <script src="js/jquery.js"></script>
        <script>
            function valida_campos(event){
                event.preventDefault();

                var usuario = document.login.usuario.value;
                var pass = document.login.pass.value;

                // Validar campos vacíos
                if (usuario === "" || pass === "") {
                    $('#mensaje').show();
                    $('#mensaje').html('Faltan campos por llenar');
                    setTimeout(function() {
                        $('#mensaje').html('');
                        $('#mensaje').hide();
                    }, 5000);
                    return false;
                }

                $.ajax({
                    url: 'campos_validos.php',
                    type: 'post',
                    dataType: 'text',
                    data: {
                        'usuario': usuario,
                        'pass': pass
                    },
                    success: function(res) {
                        console.log(res);
                        if (res == 1) {
                            // Redirigir a bienvenido.php si la validación es correcta
                            window.location.href = 'bienvenido.php';
                        } else if (res == 0) {
                            $('#mensaje').show();
                            $('#mensaje').html('Usuario o contraseña incorrectos.');
                            setTimeout(function() {
                                $('#mensaje').html('');
                                $('#mensaje').hide();
                            }, 5000);
                        } else if (res == -1) {
                            $('#mensaje').show();
                            $('#mensaje').html('El correo no existe.');
                            setTimeout(function() {
                                $('#mensaje').html('');
                                $('#mensaje').hide();
                            }, 5000);
                        }
                    },
                    error: function() {
                        alert('Error, archivo no encontrado...');
                    }
                });
                        
            }
        </script>
    </head>
    <body>
        <!-- Formulario de login -->
        <div class="detalle-contenedor">
            <h1 style="margin-bottom: 30px;">Login de Usuarios</h1>
            <form name="login" onsubmit="valida_campos(event);">
                <!-- Input de usuario -->
                <label for="usuario">Usuario:</label>
                <input type="text" id="usuario" name="usuario" placeholder="Ingresa tu usuario">
                
                <!-- Input de contraseña -->
                <label for="pass">Contraseña:</label>
                <input type="password" id="pass" name="pass" placeholder="Ingresa tu contraseña">
                <div id="mensaje"></div>
                
                <!-- Botón de enviar -->
                <input class='boton_accion'  type="submit" value="Iniciar sesión">
            </form>
        </div>
    </body>
</html>
