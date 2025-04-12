<?php

session_start();

include ("../Models/Users.php");
$usuario = $_POST['usuario'];
$password = $_POST['password'];

$validar_login = mysqli_query($conexion, "SELECT * FROM usuarios WHERE NombreUsua = '$usuario'
and contrasena = '$password'");

if (mysqli_num_rows($validar_login) > 0){
    $_SESSION['usuario'] = $usuario;
    header("location: ../dashboard.php");
    exit;
}else{
    echo '
    <script>
    alert("Usuario No Existe, Por Favor verifique datos introducidos");
    window.location ="../index.php";
    </script>'
    ;
    exit;
}

?>