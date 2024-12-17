<!--Agregar Libro-->
<?php require 'include/header.php'; ?>
<?php require_once 'config.php'; ?>
<?php require 'valActualizarProducto.php';?>
<?php require 'verificarCampo.php'; ?>

<body>
    <!--Validar Formulario Agregar Producto-->
    <div class="d-flex flex-row mb-3 justify-content-evenly">
        <!--Botón Atras-->
        <a href="index.php"><img src="images/flechaAtras.png" alt="atras" width="40" height="40"></a>
        <h1>Agregar Producto</h1>
        <a class="navbar-brand" href="index.php"><img src="images/exit.png" alt="salir" width="40" height="40"></a>
    </div>
    <?php //echo $msgresultado 
    ?>
    <!--Formulario Agregar Producto-->
    <div class="container">
        <!--Mostramos los posibles errores en los campos-->
        <?php echo validez($errores);

        //Definimos la variable a null ya que todavia no se ha cargado imagen
        $imagen = null;

        //Si no hay errores imprimimos los valores almacenados
        if (isset($_POST["anadirProducto"]) && (count($errores) == 0)) {

            //Comprobamos que no exista el título
            $nombre = $_POST['nombre'];
            echo $nombre;

            //Si el título ya existe
            if (verificarCampo($conexion, 'nombre', 'productos', $nombre)) {
                echo $nombre;
                $msgresultado = '<div class="alert alert-danger">' .
                    "El título del producto  ya existe!! :)" . '</div>';
            } else {
                // Guardamos los datos para la insercción en la Base de Datos
                $nombre = $_POST['nombre'];
                $categoria = $_POST['categoria'];
                $pvp = floatval($_POST['pvp']);
                $stock = intval($_POST['stock']);
                $observaciones = strip_tags($_POST['observaciones']);

                //Tratamos la imagen -Definimos su variable a null
                //En caso de almacenar la img en la BD
                $imagen = NULL;

                //Comprobamos que el campo tmp_name tiene una valor asignado
                //Y que hemos recibido la img correctamente
                if (isset($_FILES['portada']) && (!empty($_FILES['portada']['tmp_name']))) {
                    //Comprobamos si existe el directorio img(si no, lo creamos)
                    if (!is_dir("images")) {
                        $imgDire = "directorio mal";
                        $dir = mkdir("images", 0777, true);
                    } else { //Si no, ponemos directorio a true
                        $dir = true;
                        $imgDire = "directorio bien";
                    }

                    //Verificamos que la carpeta de img existe y movemos el fichero a ella
                    if ($dir) {
                        //Aseguramos nombre único
                        $nombreImg = time() . "-" . $_FILES['portada']['name'];

                        //Movemos el archivo a nuestra carpeta
                        $moverImg = move_uploaded_file($_FILES['portada']['tmp_name'], "images/" . $nombreImg);

                        // Definimos el nombre (ruta) de la imagen
                        $imagen = $nombreImg;

                        //Verificamos la carga si se ha realizado correctamente
                        if ($moverImg) { //En caso de que se haya movido bien
                            $imagenCargada = true;
                            $portada = "La portada nos ha llegado<br/>";
                        } else {
                            $imagenCargada = false;
                            $errores["portada"] = "Error al cargar la imagen";
                        }
                    }
                } else {
                    $errores["portada"] = "Error en portada, imagen vacía o no recibida";
                }

                // Mostramos una ventana modal con los datos del libro introducido al clicar un botón
                //require 'modal/modalAgregarLibro.php';

                //Si no hay errores insertamos el producto en la Base de Datos
                try { // Definimos la consulta
                    $sql = "INSERT INTO productos (nombre,categoria,pvp,stock,imagen,Observaciones) VALUES (:nombre,:categoria,:pvp,:stock,:imagen,:Observaciones)";

                    //Preparamos
                    $query = $conexion->prepare($sql);

                    //Ejecutamos con los valores obtenidos
                    $query->execute([
                        'nombre' => $nombre,
                        'categoria' => $categoria,
                        'pvp' => $pvp,
                        'stock' => $stock,
                        'imagen' => $imagen,
                        'Observaciones' => $observaciones
                    ]);

                    // Supervisamos si se ha realizado correctamente
                    if ($query) {
                        // Registramos en la tabla logs alta producto del admin
                        /*registrarActividad($conexion, "alta", "libro " . $titulo . " dado de alta por " . $nombre);*/
                        $msgresultado = '<div class="alert alert-success">' .
                            "El producto se registró correctamente en la Base de Datos!! :)" . '</div>';
                    } else {
                        $msgresultado = '<div class="alert alert-danger">' .
                            "Datos de registro del producto erróneos!! :( (" . $ex->getMessage() . ')</div>';
                        //die();   
                    }
                } catch (PDOException $ex) {
                    $msgresultado = '<div class="alert alert-danger">' .
                        "El producto no pudo registrarse en la Base de Datos!! :( (" . $ex->getMessage() . ')</div>'; //die(); 
                }
            }
        }
        ?>
    </div>
    <?php echo $msgresultado ?>
    <div class="formulario">
        <form action="" method="POST" enctype="multipart/form-data">

            <!--Nombre-->
            <div class="form-group mb-3">
                <label for="nombre" class="form-label">Nombre</label>

                <!--Mostramos el registro guardado anteriormente en caso de haber uno-->
                <input type="text" name="nombre" id="nombre" class="form-control"
                    <?php if (isset($_POST["nombre"])) {
                        echo "value='{$_POST["nombre"]}'";
                    } ?>>
                <?php echo  mostrar_error($errores, "nombre");
                ?>
            </div>

            <!--Categoría-->
            <div class=" form-group mb-3">
                <label for="categoria" class="form-label">Categoría</label>
                <!--Mostramos el registro guardado anteriormente en caso de haber uno-->
                <input type="text" name="categoria" id="categoria" class="form-control"
                    <?php if (isset($_POST["categoria"])) {
                        echo "value='{$_POST["categoria"]}'";
                    } ?>>
                <?php echo  mostrar_error($errores, "categoria");
                ?>
            </div>

            <!--PVP-->
            <div class="form-group mb-3">
                <label for="pvp" class="form-label">PVP</label>
                <!--Mostramos el registro guardado anteriormente en caso de haber uno-->
                <input type="number" step="0.01" name="pvp" id="pvp" class="form-control"
                    <?php if (isset($_POST["pvp"])) {
                        echo "value='{$_POST["pvp"]}'";
                    } ?>>

                <?php echo  mostrar_error($errores, "pvp");
                ?>
            </div>

            <!-- Stock -->
            <div class="form-group mb-3">
                <label for="stock" class="form-label">Stock</label>
                <!-- Mostramos el registro guardado anteriormente en caso de haber uno -->
                <input type="number" step="1" name="stock" id="stock" class="form-control"
                    <?php if (isset($_POST["stock"])) {
                        echo "value='{$_POST["stock"]}'";
                    } ?>>

                <?php echo mostrar_error($errores, "stock");
                ?>
            </div>

            <!--Imagen-->
            <div class="form-group mb-3">
                <label for="portada" class="form-label">Imagen</label>
                <input type="file" name="portada" class="form-control">
                <!--Si la imagen no es null mostramos la ultima imagen cargada-->
                <?php if ($imagen != null) {
                    echo '<div class="mt-2">';
                    echo '<label>Última imagen cargada:</label><br>';
                    echo '<img src="images/' . $imagen . '" alt="imagen cargada" width="70" height="80">';
                    echo '</div>';
                } ?>
                <?php echo  mostrar_error($errores, "imagen");
                ?>
            </div>
            <!--Observaciones-->
            <div class="form-group mb-3">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea name="observaciones" class="form-control" id="observaciones"> <?php if (isset($_POST["observaciones"])) {
                                                                                            echo $_POST["observaciones"];
                                                                                        } ?> </textarea>
                <?php echo  mostrar_error($errores, "observaciones");
                ?>
            </div>
            <!-- Inicializar CKEditor -->
            <script>
                CKEDITOR.replace('observaciones');
            </script>
            <!--Btn Añadir Libro-->
            <button type="submit" name="anadirProducto" class="btn btn-success">Añadir Producto</button>
        </form>
    </div>
</body>
<?php require 'include/footer.php'; ?>