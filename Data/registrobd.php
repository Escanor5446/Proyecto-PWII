<?php


include ("../Models/Users.php");
$NombreUsua= $_POST ['Usuario'];
$correo= $_POST ['correo'];
$contrasena= $_POST ['contrasena'];
$fechaNac= $_POST ['fecha'];
$TipoUsua= $_POST ['tipo_usuario'];
$nombreImagen = ''; 

if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $nombreImagen = mysqli_real_escape_string($conexion, $_FILES['imagen']['name']);
    $carpetaDestino = 'Imagenes/';
    $rutaDestino = $carpetaDestino . $nombreImagen;
    if (!is_dir($carpetaDestino)) {
        mkdir($carpetaDestino, 0755, true);
    }
    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
    } else {
        echo "Error al mover la imagen al servidor.";
        $nombreImagen = ''; 
    }
} elseif (isset($_FILES['imagen']) && $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE) {
    echo "Error al subir la imagen. Código: " . $_FILES['imagen']['error'];
    $nombreImagen = ''; 
}

$query = "INSERT INTO usuarios(NombreUsua,correo,contrasena,fechaNac,TipoUsua,Imagen)
          VALUES('$NombreUsua','$correo','$contrasena','$fechaNac','$TipoUsua','$nombreImagen')";

//NO repetir usuario.

$verificar_correo = mysqli_query($conexion, "SELECT * FROM usuarios WHERE correo = '$correo' ");
if(mysqli_num_rows($verificar_correo) > 0){
    echo 'este correo ya existe';
    exit();
}

$verificar_usuario = mysqli_query($conexion, "SELECT * FROM usuarios WHERE NombreUsua = '$NombreUsua' ");
if(mysqli_num_rows($verificar_usuario) > 0){
    echo 'este usuario ya existe';
    exit();
}

$ejecutar=mysqli_query($conexion, $query);

if ($ejecutar) {
    echo "Usuario registrado exitosamente.";
} else {
    echo "Error al registrar el usuario: " . mysqli_error($conexion);
}

mysqli_close($conexion); 
?>

