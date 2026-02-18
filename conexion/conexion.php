<?php
    $conn=new mysqli ("localhost","root","","vm_ssd","3308");
    if(!$conn){
        die("imposible al conectarse con el servidor");
    }
    if(@mysqli_connect_error()){
        die("imposible conectarse con la base de datos");
    }
return $conn;


?>
