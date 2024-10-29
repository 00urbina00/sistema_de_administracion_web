<?php
    $numero = $_REQUEST['numero'];
    $ban = 0;

    if($numero >= 60){
        $ban = 1;
    }

    echo $ban;
?>