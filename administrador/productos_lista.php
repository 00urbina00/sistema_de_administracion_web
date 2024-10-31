<?php 
    session_start();
    if (!isset($_SESSION['id'])) {
        header('Location: index.php');
    }
    
    require "funciones/conecta.php";
    $con = conecta();
    $sql = "SELECT * FROM productos WHERE eliminado = 0";
    $res = $con->query($sql);
    $num = $res->num_rows;
?>
<!DOCTYPE html>
    <head>
        <title>Listado de Productos</title>
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
                max-width: 100%; 
                max-height: 100%; 
                height: auto; 
                margin: 0 auto;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                overflow-y: auto;
                margin-top: 20px; 
                text-align: center;
            }

            .detalle-contenedor h2 {
                font-size: 20px;
                margin-bottom: 10px;
                color: #084853;
                text-align: center;
            }
        </style>
        <script src="js/jquery.js"></script>
            <script>
                function elimina_producto($id, element){
                    if(!confirm('Estás seguro de borrar el producto '+$id+'?')) return;
                    $.ajax({
                        url     :'elimina_producto.php',
                        type    :'post',
                        dataType:'text',
                        data    :'id='+$id, 
                        success :function(res){
                            console.log(res);
                            if(res == 1){
                                alert('Producto eliminado correctamente!');
                                $(element).closest('.fila').remove();
                            }else{
                                alert('Error al eliminar el producto.');
                            }
                        },  
                        error: function(){
                            alert('Error archivo no encontrado...');
                        }
                    });
                        
                }
                function ver_detalle_producto(id){
                    $.ajax({
                        url     :'consulta_producto.php',
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
                                // Redirigimos a la página de edición, pasando la información por la URL
                                window.location.href = 'ver_detalle_producto.php?id=' + encodeURIComponent(id) + 
                                                        '&nombre=' + encodeURIComponent(res.nombre) +
                                                        '&codigo=' + encodeURIComponent(res.codigo) +
                                                        '&descripcion=' + encodeURIComponent(res.descripcion) + 
                                                        '&costo=' + encodeURIComponent(res.costo)  +
                                                        '&stock=' + encodeURIComponent(res.stock) +
                                                        '&archivo=' + encodeURIComponent(res.archivo);
                            }
                        },  
                        error: function() {
                            alert('Error archivo no encontrado...');
                        }
                    });
                }
                function edita_producto(id){
                    $.ajax({
                        url     :'consulta_producto.php',
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
                                window.location.href = 'productos_modifica.php?id=' + encodeURIComponent(id) + 
                                                        '&nombre=' + encodeURIComponent(res.nombre) +
                                                        '&codigo=' + encodeURIComponent(res.codigo) +
                                                        '&descripcion=' + encodeURIComponent(res.descripcion) + 
                                                        '&costo=' + encodeURIComponent(res.costo)  +
                                                        '&stock=' + encodeURIComponent(res.stock) + 
                                                        '&archivo=' + encodeURIComponent(res.archivo);
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
            <h1 style="margin-bottom: 40px;">Listado de productos (<?php echo $num; ?>)</h1>
            <div class="botones">
                <a href="productos_alta.php">Dar de alta</a>
            </div>
            <div class="tabla">
                <div class="fila fila_header">
                    <div class="columna" style="text-align: center;">ID</div>
                    <div class="columna" style="text-align: center;">Nombre</div>
                    <div class="columna" style="text-align: center;">Descripción</div>
                    <div class="columna" style="text-align: center;">Costo</div>
                    <div class="columna" style="text-align: center;">stock</div>
                    <div class="columna" style="text-align: center;">Ver detalle</div>
                    <div class="columna" style="text-align: center;">Editar</div>
                    <div class="columna" style="text-align: center;">Eliminar</div>
                </div>
                <?php 
                while($row = $res->fetch_array()){
                    $id = $row["id"];
                    $nombre = $row["nombre"];
                    $descripcion = $row["descripcion"];
                    $costo = $row["costo"];
                    $stock = $row["stock"];
                    echo "<div class='fila'>
                            <div class='columna' style=\"text-align: center;\">$id</div>
                            <div class='columna' style=\"text-align: center;\">$nombre</div>
                            <div class='columna' style=\"text-align: center;\">$descripcion</div>
                            <div class='columna' style=\"text-align: center;\">\$$costo</div>
                            <div class='columna' style=\"text-align: center;\">$stock</div>
                            <div class='columna' style=\"text-align: center;\">
                                <button class='boton_accion-detalles' onclick='ver_detalle_producto($id)'>Ver detalle</button>
                            </div>
                            <div class='columna' style=\"text-align: center;\">
                                <button class='boton_accion-editar' onclick='edita_producto($id)'>Editar</button>
                            </div>
                            <div class='columna' style=\"text-align: center;\">
                                <button class='boton_accion' onclick='elimina_producto($id, this)'>Eliminar</button>
                            </div>
                        </div>";
                }
                ?>
            </div>
        </div>
    </body>
</html>
