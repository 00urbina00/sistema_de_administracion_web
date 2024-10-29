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
        <style>
            body {
                margin: 20px;
            }
            .botones a {
                margin-right: 15px;
                text-decoration: none;
                padding: 10px 15px;
                background-color: #007bff;
                color: white;
                border-radius: 5px;
                transition: background-color 0.3s;
            }
            .botones a:hover {
                background-color: #0056b3;
            }
            .tabla {
                display: flex;
                flex-direction: column;
                width: 100%;
                margin-top: 20px;
            }
            .fila {
                display: flex;
                flex-direction: row;
                border: 1px solid #ddd;
            }
            .fila_header {
                background-color: #084853;
                color: white;
            }
            .columna {
                flex: 1;
                padding: 12px;
                text-align: left;
                border-right: 1px solid #ddd;
            }
            .fila .boton_accion {
                padding: 5px 10px;
                background-color: #d61600;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }
            .fila .boton_accion:hover {
                background-color: #af1200;
            }
        </style>
        <script src="js/jquery.js"></script>
            <script>
                function elimina_empleado($id, element){
                    if(!confirm('Estás seguro de borrar al empleado '+$id+'?')) return;
                    $.ajax({
                        url     :'respuesta.php',
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
            </script>
    </head>
    <body>

        <h1>Listado de empleados (<?php echo $num; ?>)</h1>

        <div class="botones">
            <a href="empleados_alta.php">Crear nuevo</a>
            <a href="nuevo.php">Eliminar uno</a>
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
                            <button class='boton_accion' onclick='elimina_empleado($id, this)'>Eliminar</button>
                        </div>
                    </div>";
            }
            ?>
        </div>
    </body>
</html>
