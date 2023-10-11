<?php
error_reporting(0); 

// Comprueba si se han recibido los parametros 'dir' y 'note' a traves de la URL
if (isset($_GET['dir']) && isset($_GET['note'])) {
    $dir = $_GET['dir']; // Almacena el valor del parametro 'dir'
    $note = $_GET['note']; // Almacena el valor del parametro 'note'
} else {
    header("Location: index.php"); // Redirige al archivo index.php si no se recibieron los parametros
}

$file = "files/" . $dir . '/' . $note; // Ruta del archivo a editar
$filed = $note . '&dir=' . $dir; // Parametros para redirigir despues de guardar la nota

$file_contents = file_get_contents($file); // Lee el contenido del archivo

// Comprueba si se ha enviado el formulario de guardar
if (($_POST['save'])) {
    file_put_contents($file, $_POST['valor-nota']); // Guarda el contenido del formulario en el archivo
    header('Location: nota.php?note=' . $filed); // Redirige a la pagina de la nota despues de guardar
}

// Comprueba si se ha enviado el formulario de eliminar
if (isset($_POST['delete'])) {
    if (file_exists($file)) {
        unlink($file); // Elimina el archivo
        header("Location: index.php"); // Redirige al archivo index.php despues de eliminar
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Estos enlaces link importan la hoja de estilos CSS de Bootstrap y de un archivo CSS dentro del mismo directorio -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">

    <!-- Establecemos el titulo de la pagina utilizando el valor de la variable $note que se procesara en el lado del servidor -->
    <title><?php echo $note ?></title>

    <!-- Este enlace <link> especifica el icono de acceso directo que se mostrara en la pestaña del navegador -->
    <link rel="shortcut icon" href="img/logo.png" />
</head>

<body>
    <!-- Este bloque de codigo representa una barra de navegacion utilizando las clases de Bootstrap para dar estilo -->
    <nav class="navbar navbar-dark bg-black">
        <div class="container">
            <a class="navbar-brand" href="directorio.php?dir=<?php echo $dir ?>"><img style="width: 35px;" src="./assets/img/left-arrow.png" alt=""> Volver a <?php echo $dir ?></a>
        </div>
    </nav>

    <!-- Este bloque de codigo contiene dos formularios. El primer formulario tiene un area de texto y un boton de envio para guardar el contenido. El segundo formulario tiene campos ocultos y un boton de envio para eliminar el contenido -->
    <div style="text-align: center;">
    <form method="post" action="">
        <textarea name="valor-nota" style="height: 500px;" class="form-control bg-dark" id="" cols="30" rows="10"><?php echo $file_contents; ?></textarea>
        <input class="btn btn-secondary" type="submit" name="save" value="Guardar">
    </form>

    <br>

    <form method="post" action="">
        <input type="hidden" name="dir" value="<?php echo $dir ?>">
        <input type="hidden" name="note" value="<?php echo $note ?>">
        <input class="btn btn-danger" type="submit" name="delete" value="Eliminar">
    </form>
    </div>
</body>

</html>