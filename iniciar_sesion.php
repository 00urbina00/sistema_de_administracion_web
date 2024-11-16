<?php
    // Si la sesión está iniciada, redirigir a bienvenido.php
    session_start();
    if (isset($_SESSION['id_cliente'])) {
        header('Location: index.php');
    }
?>
<!DOCTYPE html>
    <head>
        <title>Login</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <style>

            h1 {
                text-align: center;
                font-size: 24px;
                margin-top: 20px;
            }


            form {
                background-color: #f9f9f9;
                padding: 20px;
                border-radius: 8px;
                max-width: 400px;
                margin: 0 auto;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            input[type="text"], input[type="password"], select, textarea {
                width: 100%;
                padding: 10px;
                margin: 10px 0 20px 0;
                display: inline-block;
                border: 1px solid #cccccc;
                border-radius: 4px;
                box-sizing: border-box;
                font-size: 16px;
                font-family: 'Helvetica', sans-serif;
            }

            input[type="submit"]  {
                background-color: #007bff;
                color: #ffffff;
                padding: 10px 20px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 16px;
                width: 100%;
            }

            input[type="submit"]:hover {
                background-color: #0056b3;
            }

            .detalle-contenedor {
                background-color: #f9f9f9;
                padding: 20px;
                border-radius: 8px;
                max-width: 40%; /* Ajusta el ancho máximo al 40% de la pantalla */
                height: auto; /* Se ajusta automáticamente */
                margin: 0 auto;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                max-height: 80vh; /* Limita la altura al 80% de la vista */
                overflow-y: auto; /* Añade scroll interno si el contenido sobrepasa la altura */
                margin-top: 20px;   /* Espacio entre el contenedor y el título */
                text-align: center;
            }

            /* Centrar título y reducir márgenes */
            .detalle-contenedor h2 {
                font-size: 20px;
                margin-bottom: 10px;
                color: #084853;
                text-align: center;
            }

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

            #mensaje {
                color: #FF0000;
                font-size: 16px;
                margin-bottom: 20px;
                display: none;
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
                            window.location.href = 'index.php';
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
            <div style="text-align: center;">
                    <a href="index.php"><button class="boton-volver">Volver</button></a>
            </div>
        </div>
    </body>
</html>
