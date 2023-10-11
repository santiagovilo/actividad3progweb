<?php

$msg = null;

// Comprueba si se ha enviado el formulario para crear una carpeta y si se ha proporcionado un nombre 
if (isset($_POST['create']) && isset($_POST['folder'])) {
    $name = $_POST['folder'];
    $directorio = "files/$name";

    // Comprueba si el directorio no existe y lo crea
    try { 
        if (!(is_dir($directorio))) {
            mkdir($directorio);
            $msg = 'Directorio creado.';
        } else {
            $msg = 'El directorio ya existe.';
        }
    } catch (Exception $e) {
        echo 'Error: ',  $e->getMessage(), "\n\n";
    }
}

// Comprueba si se ha enviado el formulario para eliminar una carpeta
if (isset($_POST['delete_folder'])) {
    $folderToDelete = $_POST['delete_folder'];
    $directoryToDelete = "files/$folderToDelete";

    // Comprueba si el directorio existe y lo elimina junto con su contenido de forma recursiva
    try {
        if (is_dir($directoryToDelete)) {
            
            $success = deleteDirectory($directoryToDelete);

            if ($success) {
                $msg = 'Directorio eliminado.';
            } else {
                $msg = 'Error al eliminar el directorio.';
            }
        } else {
            $msg = 'El directorio no existe.';
        }
    } catch (Exception $e) {
        echo 'Error: ',  $e->getMessage(), "\n\n";
    }
}

// Limpia las variables del formulario
unset($_POST['create']);
unset($_POST['folder']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bloc de notas playero</title> <!-- Esta linea establece el titulo de la pagina -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous"> <!-- Esta linea enlaza la hoja de estilo CSS de Bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Esta linea enlaza una hoja de estilo personalizada llamada "styles.css" -->
    <link rel="shortcut icon" href="img/logo.png" /> <!-- Esta linea establece el icono de acceso directo de la pagina -->
    <style>
        .cuadro-color {
            background-color: #FFFFFF; /* Se crea un cuadro que contendra el titulo de la pagina*/
            padding: 10px;
        }
    </style>
</head>

<body>
    <div class="image">
        <img src="img/logo.png"> <!-- Esta linea muestra la imagen "logo.png" ubicada en la carpeta "img" -->
    </div>
    <nav class="nav justify-content-center">
        <div class="cuadro-color">
            <h1>Bloc de notas playero</h1>
        </div>
    </nav>
    <hr>
    <div class="row row-cols-4 w-75 mx-auto"> <!-- Este div contiene una fila con 4 columnas y esta centrado horizontalmente -->
        <p>
            <!-- Esta parte contiene un boton para crear una nueva carpeta -->
            <button class="btn btn-outline-dark" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" style="background-color: #51d1f6; color: white; font-weight: bold;">
                CREAR NUEVA CARPETA
            </button>
        </p>
    </div>
    <div class="row row-cols-4 w-75 mx-auto">
        <div class="collapse" id="collapseExample">
            <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post">
                <div class="input-group mb-3">
                    <input autocomplete="off" type="text" name="folder" class="form-control" placeholder="Ej: Nota" aria-describedby="button-addon2">
                    <button type="submit" name="create" class="btn btn-outline-secondary" type="button" id="button-addon2" style="background-color: #5f605c; color: white; font-weight: bold;">Crear</button> <!-- Este boton envia el formulario para crear una nueva carpeta -->
                </div>
            </form>
        </div>
    </div>

    <div class="row row-cols-4 w-75 mx-auto">
        <strong id="negrita"><?php echo $msg ?></strong><br>
        <table class="table table-borderless caption-top table-active table-hover">
            <thead>
                <tr>
                    <!-- Esta parte muestra los encabezados de las columnas -->
                    <th scope="col">Carpeta</th>
                    <th scope="col">Fecha de Modif.</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>

                <?php
                try {
                    // Obtiene la lista de directorios en la carpeta "files"
                    $dir = 'files';
                    $dirs  = scandir($dir);
                    
                    // Itera sobre los directorios y muestra la información en la tabla
                    foreach ($dirs as $direc) {
                        if ('.' !== $direc && '..' !== $direc) {

                ?>
                            <tr>
                                <td><i class="fa-solid fa-folder-closed"></i> <a href="directorio.php?dir=<?php echo $direc ?>" class="card-link"><?php echo $direc ?></a></td>
                                <td><?php date_default_timezone_set('America/Caracas');
                                    echo date("d-m-Y H:i:s", filemtime($dir . '/' . $direc)); ?></td>
                                <td>Carpeta de Archivos</td>
                                <td>
                                    <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post">
                                        <input type="hidden" name="delete_folder" value="<?php echo $direc ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                </td>
                            </tr>

                <?php
                        }
                    }
                } catch (Exception $e) {
                    echo 'Se ha encontrado un error: ',  $e->getMessage(), "\n\n";
                }

                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

</body>

</html>

<?php

// Funcion para eliminar un directorio y su contenido de forma recursiva
function deleteDirectory($directory)
{
    if (!file_exists($directory)) {
        return false;
    }

    if (!is_dir($directory)) {
        return unlink($directory);
    }

    foreach (scandir($directory) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!deleteDirectory($directory . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }

    return rmdir($directory);
}
?>
