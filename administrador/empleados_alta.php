<html>
    <head>
        <style>
            #mensaje {
                color: #F00;
                font-size: 16px;
            }
        </style>
        <script>
            function miAlerta(nombre, apellido, calificacion){
                alert('Bienvenido ' + nombre + " " + apellido + "!\n" + "tu calificación es: "+ calificacion);
            }
            function enviar(){
                var correo = document.user.correo.value;
                alert(correo);
            }

            function validarFormulario() {
                var nombre = document.user.nombre.value;
                var apellido = document.user.apellido.value;
                var correo = document.user.correo.value.trim();
                var pass = document.user.pass.value.trim();
                var rol = document.user.rol.value;

                if (nombre === "" || apellido === "" || correo === "" || pass === "" || rol === "0") {
                    alert("Faltan campos por llenar");
                } else {
                    document.user.method = 'post';
                    document.user.action = 'empleados_salva.php';
                    document.user.submit();
                }
            }
            function sale(){
                alert("Sale del campo");
                $('#mensaje').show();
                $('#mensaje').html('Sale del campo');
                setTimeout("$('#mensaje').html('');", 1500);
            }
        </script>
        <title>Formulario</title>
        <div>Alta de empleados</div> 
        <br>
        <a href="empleados_lista.php">Volver</a>
        <br><br>
        
    </head>
    <body>
        <form name="user" method="post" action="empleados_salva.php">
            <input type="text" name="nombre" id="name" placeholder="Escribe tu nombre"/> <br>
            <input type="text" name="apellido" id="last_name" placeholder="Escribe tu apellido"/> <br>
            <input onblur="sale();" type="text" name="correo" id="mail" value="@udg.mx"/> <br>
            <input type="password" name="pass" id="pass" placeholder="Escribe tu password"/> <br>
            <select name="rol" id="rol">
                <option value="0">Seleccionar</option>
                <option value="1">Gerente</option>
                <option value="2">Ejecutivo</option>
            </select>
            <br>
            <input onclick="validarFormulario();" type="submit" value="Salvar"/>
        </form>
    </body>
</html>
