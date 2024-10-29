<html>
    <head>
        <script>
            function miAlerta(nombre, apellido, calificacion){
                alert('Bienvenido ' + nombre + " " + apellido + "!\n" + "tu calificación es: "+ calificacion);
            }
            function enviar(){
                var correo = document.user.correo.value;
                alert(correo);
            }

            function validarFormulario() {
                var identificador = document.user.identificador.value;

                if (identificador === "") {
                    alert("Faltan campos por llenar");
                } else {
                    document.user.method = 'post';
                    document.user.action = 'empleados_elimina.php';
                    document.user.submit();
                }
            }
        </script>
        <title>Formulario</title>
        <div>Baja de empleados</div> 
        <br>
        <a href="empleados_lista.php">Volver</a>
        <br><br>
        
    </head>
    <body>
        <form name="user" method="post" action="empleados_salva.php">
            <input type="text" name="identificador" id="identify" placeholder="Escribe el id"/> <br>
            <br>
            <input onclick="validarFormulario();" type="submit" value="Borrar"/>
        </form>
    </body>
</html>
