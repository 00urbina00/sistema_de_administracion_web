<?php 
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

        <h1>Listado de empleados (<?php echo $num; ?>)</h1>

        <div class="botones">
            <a href="empleados_alta.php">Dar de alta</a>
        </div>

        <div class="tabla">
            <div class="fila fila_header">
                <div class="columna">ID</div>
                <div class="columna">Nombre</div>
                <div class="columna">Apellidos</div>
                <div class="columna">Correo</div>
                <div class="columna">Rol</div>
                <div class="columna">Ver detalle</div>
                <div class="columna">Editar</div>
                <div class="columna">Eliminar</div>
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
                        <div class='columna'>$id</div>
                        <div class='columna'>$nombre</div>
                        <div class='columna'>$apellidos</div>
                        <div class='columna'>$correo</div>
                        <div class='columna'>$rol</div>
                        <div class='columna'>
                            <button class='boton_accion-detalles' onclick='ver_detalle($id)'>Ver detalle</button>
                        </div>
                        <div class='columna'>
                            <button class='boton_accion-editar' onclick='edita_empleado($id)'>Editar</button>
                        </div>
                        <div class='columna'>
                            <button class='boton_accion' onclick='elimina_empleado($id, this)'>Eliminar</button>
                        </div>
                    </div>";
            }
            ?>
        </div>
    </body>
</html>
