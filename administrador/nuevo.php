<html>
    <head>
        <title>Ajax / Jquery</title>
        <style>
            #mensaje {
                color: #F00;
                font-size: 16px;
            }
        </style>
        <script src="js/jquery.js"></script>
        <script>
            function enviaAjax(){
                var numero = $('#numero').val();
                if (numero === "" || numero <= 0) {
                    $('#mensaje').html('Faltan campos por llenar');
                    setTimeout("$('#mensaje').html('');", 1500);
                } else {
                    $.ajax({
                        url     :'respuesta.php',
                        type    :'post',
                        dataType:'text',
                        data    :'numero='+numero, 
                        success :function(res){
                            console.log(res);
                            if (res==1){
                                $('#mensaje').html('Aprobado');
                            }else{
                                $('#mensaje').html('Reprobado');
                            }
                            setTimeout("$('#mensaje').html('');", 1500);
                        },  
                        error: function(){
                            alert('Error archivo no encontrado...');
                        }
                    });
                    
                }
            }
        </script>
        <a href="empleados_lista.php">Volver</a>
        <br><br>
    </head>
    <body>
        <input type="text" name="numero" id="numero"/><br>
        <a href="javascript:void(0);"onclick="enviaAjax();">
            Enviar con ajax
        </a><br>
        <div id="mensaje"></div>
    </body>
</html>