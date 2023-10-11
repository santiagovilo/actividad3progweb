<?php

// Comprueba si se ha establecido la variable 'dir' en la URL
if (isset($_GET['dir'])) {
    $dir = $_GET['dir'];
    $msg = null;
    
    // Comprueba si se ha enviado el formulario para crear un nuevo archivo
    if (isset($_POST['createn'])) {

        // Comprueba si se han enviado los campos necesarios para crear el archivo
        if (isset($_POST['namen']) && isset($_POST['contentn']) && isset($_POST['dir'])) {
            $name = $_POST['namen'];
            $dir = $_POST['dir'];
            $content = $_POST['contentn'];
            $direct = "files/$dir/$name.txt";
            $msg = '';
            try {

                // Comprueba si ya existe un archivo con el mismo nombre
                if (file_exists($direct)) {
                    $msg = "Ya existe un archivo con el nombre <b>$name</b>";
                } else {

                    // Crea el archivo y escribe el contenido en el
                    $nota = fopen($direct, 'a');
                    fputs($nota, $content);
                    fclose($nota);

                    // Redirecciona a la pagina del directorio
                    header('Location: directorio.php?dir=' . $dir);
                }
            } catch (Exception $exp) {
                echo 'Excepción capturada: ',  $exp->getMessage(), "\n\n";
            }
        }
    }

    // Limpia las variables POST despues de procesar el formulario
    unset($_POST['createn']);
    unset($_POST['namen']);
} else {
    header("Location: index.php"); // Si no se ha establecido la variable 'dir' en la URL, redirecciona a la pagina de inicio
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <!-- En este bloque se enlaza con un archivo CSS de Bootstrap, un archivo personalizado de CSS, se establece un titulo a la pagina y enlaza a un icono para mostrar en la pestaña del navegador -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <title><?php echo $dir ?></title>
    <link rel="shortcut icon" href="img/logo.png" />
</head>

<body>
    <nav class="navbar navbar-dark bg-black">
        <div class="container">
            <a class="navbar-brand" href="index.php"><img style="width: 35px;" src="./assets/img/left-arrow.png" alt=""> Inicio</a> 
        </div>
    </nav>
    <br>
    <div class="container">
        <p><b><a href="index.php">/</a> <?php echo $dir ?></a></b></p> <!-- Esta linea muestra un enlace que representa la ruta actual del directorio -->
        <hr>
        <p>
            <!-- Esta parte muestra un parrafo que contiene un boton para crear un nuevo archivo -->
            <button class="btn btn-outline-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" style="background-color: #51d1f6; color: white; font-weight: bold;">
                CREAR NUEVO ARCHIVO
            </button>
        </p>
        <div class="collapse" id="collapseExample">
            <form action="directorio.php?dir=<?php echo $dir ?>" method="post"> <!-- Esta linea crea un formulario que envia los datos a un archivo llamado "directorio.php" con la ruta del directorio actual como parametro -->
                
                <!-- Esta linea crea un area de texto donde se puede escribir el contenido del nuevo archivo -->
                <textarea placeholder="Escribe algo..." name="contentn" style="height: 500px;" class="form-control bg-dark" id="" cols="30" rows="10"></textarea>
                <br>
                <input type="hidden" name="dir" value="<?php echo $dir ?>"> <!-- Esta linea crea un campo oculto que envia la ruta del directorio actual como un valor oculto en el formulario -->
                <div class="input-group mb-3"> <!-- Este contenedor envuelve un campo de entrada de texto y un boton para crear el archivo -->
                    <input autocomplete="off" type="text" name="namen" class="form-control" placeholder="Ej: Nota" aria-describedby="button-addon2"> <!--  Esta linea crea un campo de entrada de texto donde se puede ingresar el nombre del nuevo archivo -->
                    <button type="submit" name="createn" value="create" class="btn btn-outline-secondary" id="button-addon2" style="background-color: #5f605c; color: white; font-weight: bold;">Crear</button> <!--  Esta linea crea un boton que envia el formulario para crear el archivo -->
                </div>
            </form>
            <br>
        </div>
        <!-- Se agrega una leyenda a la tabla con el texto "Archivos" y aplica un estilo de color al texto y definen las columnas de encabezado de la tabla-->
        <p><?php echo $msg ?></p> 
        <br>
        <div class="row row-cols-4 w-75 mx-auto">
            <table class="table table-borderless caption-top table-active table-hover">
                <caption style="color: aliceblue;">Archivos</caption> 
                <thead>
                    <tr>
                        <th scope="col">Nombre de archivo</th>
                        <th scope="col">Contenido</th>
                        <th scope="col">Fecha de Modif.</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Tamaño</th>
                    </tr>
                </thead>
                <tbody>

                    <?php

                    // Obtiene la lista de archivos en el directorio

                    $directorio = "./files/" . $dir;
                    $direc  = scandir($directorio);

                    // Comprueba si hay archivos en el directorio
                    if (count($direc) > 2) {
                        foreach ($direc as $valor) {
                            if ('.' !== $valor && '..' !== $valor) {

                                $file = "./files/" . $dir . '/' . $valor;

                                if (filesize($file) > 0) {
                                    $contents = file_get_contents($file, FILE_USE_INCLUDE_PATH);
                                } else {
                                    $contents = 'No hay contenido aun';
                                }
                                    // Muestra la informacion del archivo en una tabla
                    ?>
                                <tr>
                                    <td><i class="fa-sharp fa-solid fa-note-sticky"></i> <a href="nota.php?note=<?php echo $valor ?>&dir=<?php echo $dir ?>" class="card-link"><?php echo rtrim($valor) ?></a></td>
                                    <td><?php
                                        if (filesize($file) <= 29) {
                                            echo '<p class="card-text"><i>' . substr($contents, 0, 28) . '</i></p>';
                                        } else {
                                            echo '<p class="card-text"><i>' . substr($contents, 0, 28) . '...</i></p>';
                                        }
                                        ?>
                                    </td>
                                    <td><?php date_default_timezone_set('America/Caracas'); echo date("d-m-Y H:i:s", filemtime($file)); ?></td>
                                    <td><?php echo filetype($file) ?></td>
                                    <td><?php echo filesize($file) ?> bytes</td>
                                    
                                </tr>

                    <?php


                            }
                        }
                    }

                    ?>

        </div>

    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

</body>

</html>