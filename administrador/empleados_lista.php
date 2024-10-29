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
        <script src="js/jquery.js"></script>
            <script>
                function elimina_empleado($id){
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
                                location.reload();
                            }else{
                                alert('Error al eliminar empleado.');
                            }
                        },  
                        error: function(){
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
                        <div class='columna'>$void</div>
                        <div class='columna'>$void</div>
                        <div class='columna'>
                            <button class='boton_accion' onclick='elimina_empleado($id)'>Eliminar</button>
                        </div>
                    </div>";
            }
            ?>
        </div>
    </body>
</html>
