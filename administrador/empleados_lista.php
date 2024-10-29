<?php 
    session_start();
    
    require "funciones/conecta.php";
    $con = conecta();
    $sql = "SELECT * FROM empleados WHERE eliminado = 0";
    $res = $con->query($sql);
    $num = $res->num_rows;
?>
<!DOCTYPE html>
    <head>
        <title>Listado de Empleados</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <style>
            .fila .boton_accion-detalles {
                padding: 5px 10px;
                background-color: #28a745;
                color: #FFFFFF;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            .fila .boton_accion-detalles:hover {
                background-color: #218a39; 
            }
            .fila .boton_accion-editar {
                padding: 5px 10px;
                background-color: #e3c70b;
                color: #FFFFFF;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            .fila .boton_accion-editar:hover {
                background-color: #d4c45a; 
            }
            .detalle-contenedor {
                max-width: 100%; /* Ajusta el ancho máximo al 40% de la pantalla */
                max-height: 100%; /* Limita la altura al 80% de la vista */
                height: auto; /* Se ajusta automáticamente */
                margin: 0 auto;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
        </style>
        <script src="js/jquery.js"></script>
            <script>
                function elimina_empleado($id, element){
                    if(!confirm('Estás seguro de borrar al empleado '+$id+'?')) return;
                    $.ajax({
                        url     :'elimina_empleado.php',
                        type    :'post',
                        dataType:'text',
                        data    :'id='+$id, 
                        success :function(res){
                            console.log(res);
                            if(res == 1){
                                alert('Empleado eliminado correctamente!');
                                $(element).closest('.fila').remove();
                            }else{
                                alert('Error al eliminar empleado.');
                            }
                        },  
                        error: function(){
                            alert('Error archivo no encontrado...');
                        }
                    });
                        
                }
                function ver_detalle(id){
                    $.ajax({
                        url     :'consulta_empleado.php',
                        type    :'post',
                        dataType:'json',
                        data    : { 'id': id }, 
                        success : function(res) {
                            if (res.error) {
                                $('#mensaje').show();
                                $('#mensaje').html(res.error);
                                setTimeout(function() {
                                    $('#mensaje').html('');
                                    $('#mensaje').hide();
                                }, 5000);
                            } else {
                                // Redirigimos a la página de detalles, pasando la información por la URL
                                window.location.href = 'ver_detalle.php?nombre=' + res.nombre + 
                                                    '&apellidos=' + res.apellidos +
                                                    '&correo=' + res.correo + 
                                                    '&rol=' + res.rol + 
                                                    '&archivo=' + encodeURIComponent(res.archivo);
                            }
                        },  
                        error: function() {
                            alert('Error archivo no encontrado...');
                        }
                    });

                }
                function edita_empleado(id){
                    $.ajax({
                        url     :'consulta_empleado.php',
                        type    :'post',
                        dataType:'json',
                        data    : { 'id': id }, 
                        success : function(res) {
                            console.log(res);
                            if (res.error) {
                                $('#mensaje').show();
                                $('#mensaje').html(res.error);
                                setTimeout(function() {
                                    $('#mensaje').html('');
                                    $('#mensaje').hide();
                                }, 5000);
                            } else {
                                // Redirigimos a la página de edición, pasando la información por la URL
                                window.location.href = 'empleados_modifica.php?id=' + encodeURIComponent(id) + 
                                                        '&nombre=' + encodeURIComponent(res.nombre) +
                                                        '&apellidos=' + encodeURIComponent(res.apellidos) +
                                                        '&correo=' + encodeURIComponent(res.correo) + 
                                                        '&rol=' + encodeURIComponent(res.rol);
                            }
                        },  
                        error: function() {
                            alert('Error archivo no encontrado...');
                        }
                    });

                }
            </script>
    </head>
    <body>
        <!-- Incluir el menú -->
        <?php include 'menu_navegacion.php'; ?>
        <div class="detalle-contenedor">
            <h1 style="margin-bottom: 40px;">Listado de empleados (<?php echo $num; ?>)</h1>
            <div class="botones">
                <a href="empleados_alta.php">Dar de alta</a>
            </div>

            <div class="tabla">
                <div class="fila fila_header">
                    <div class="columna" style="text-align: center;">ID</div>
                    <div class="columna" style="text-align: center;">Nombre</div>
                    <div class="columna" style="text-align: center;">Apellidos</div>
                    <div class="columna" style="text-align: center;">Correo</div>
                    <div class="columna" style="text-align: center;">Rol</div>
                    <div class="columna" style="text-align: center;">Ver detalle</div>
                    <div class="columna" style="text-align: center;">Editar</div>
                    <div class="columna" style="text-align: center;">Eliminar</div>
                </div>
                <?php 
                while($row = $res->fetch_array()){
                    $id = $row["id"];
                    $nombre = $row["nombre"];
                    $apellidos = $row["apellidos"];
                    $correo = $row["correo"];
                    $rol = $row["rol"] == 1 ? "Gerente" : "Ejecutivo";
                    $void = "";
                    echo "<div class='fila'>
                            <div class='columna' style=\"text-align: center;\">$id</div>
                            <div class='columna' style=\"text-align: center;\">$nombre</div>
                            <div class='columna' style=\"text-align: center;\">$apellidos</div>
                            <div class='columna' style=\"text-align: center;\">$correo</div>
                            <div class='columna' style=\"text-align: center;\">$rol</div>
                            <div class='columna' style=\"text-align: center;\">
                                <button class='boton_accion-detalles' onclick='ver_detalle($id)'>Ver detalle</button>
                            </div>
                            <div class='columna' style=\"text-align: center;\">
                                <button class='boton_accion-editar' onclick='edita_empleado($id)'>Editar</button>
                            </div>
                            <div class='columna' style=\"text-align: center;\">
                                <button class='boton_accion' onclick='elimina_empleado($id, this)'>Eliminar</button>
                            </div>
                        </div>";
                }
                ?>
            </div>
        </div>
    </body>
</html>
