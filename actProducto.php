<!--Actualizar datos del Producto-->
<?php require 'include/header.php'; ?>
<?php require_once 'config.php'; ?>
<?php require 'valactProducto.php'; ?>
<?php require 'verificarCampo.php'; ?>
<?php if (isset($_GET['codprod'])) {
    //almacenamos el codigo del producto
    $codproducto = $_GET['codprod'];
    $nombreprod = $_GET['nombre'];
}
$msgresultado = "";
$msgresultadoIsbn = "";
$msgresultadoTitulo = "";

//Variables actualizar
$valnombre = "";
$valcategoria = "";
$valpvp = "";
$valstock = "";
$valobservaciones = "";
$valimagen = "";

//-----------------------------Si se pulsa en actualizar---------------------------------------------
if (isset($_POST["actualizar"]) && (count($errores) == 0)) {
    //Si el título ya existe
    $nombre = $_POST['nombre'];

    //Si el producto es el mismo nombre de otro codprod
    if (verificarCampoProd($conexion, 'nombre', 'libros', $nombre, $codproducto)) {
        $msgresultadoTitulo = '<div class="alert alert-danger">' .
            "El nombre del producto ya existe!! :)" . '</div>';
    }

    // Si el nombre del producto no existe
    if (
        !verificarCampoProd($conexion, 'nombre', 'libros', $nombre, $codproducto)
    ) {

        // Guardamos los datos para la insercción en la Base de Datos
        $nuevonombre = $_POST['nombre'];
        $nombre = $nuevonombre;
        $nuevocategoria = $_POST['categoria'];
        $nuevopvp = $_POST['pvp'];
        $nuevostock = $_POST['stock'];
        $nuevoobservaciones =  $_POST['observaciones'];
        $nuevoobservaciones = strip_tags($nuevoobservaciones); //Eliminamos las etiquetas

        //Insertamos imagem¡n
        $nuevaimagen = "";

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
                    $portada = "La foto de portada del libro nos ha llegado<br/>";
                } else {
                    $imagenCargada = false;
                    $errores["portada"] = "Error al cargar la foto";
                }
            }
        } else {
            $errores["portada"] = "Error en portada, imagen vacía o no recibida";
        }


        //Asignamos la nueva imagen
        $nuevaimagen = $imagen;

        // Mostramos una ventana modal con los datos del libro introducido al clicar un botón
        //require 'modal/modalActualizarLibro.php';


        //Si no hay errores insertamos el libro en la Base de Datos
        try { // Definimos la consulta
            $sql = "UPDATE productos SET nombre=:nombre, categoria=:categoria, pvp=:pvp, stock=:stock, Observaciones=:Observaciones,imagen=:imagen WHERE codprod=:codprod";

            //Preparamos
            $query = $conexion->prepare($sql);

            //Ejecutamos con los valores obtenidos
            $query->execute([
                'codprod' => $codproducto,
                'nombre' => $nuevonombre,
                'categoria' => $nuevocategoria,
                'pvp' => $nuevopvp,
                'stock' => $nuevostock,
                'Observaciones' => $nuevoobservaciones,
                'imagen' => $nuevaimagen,

            ]);

            // Supervisamos si se ha realizado correctamente
            if ($query) {
                // Registramos en la tabla logs el registro del admin
                /*registrarActividad($conexion, "actualizacion", "libro actualizado por " . $nombre);*/
                $msgresultado = '<div class="alert alert-success">' .
                    "El producto se actualizó correctamente en la Base de Datos!! :)" . '</div>';
            } else {
                $msgresultado = '<div class="alert alert-danger">' .
                    "Datos de la actualización del prodcuto erróneos!! :( (" . $ex->getMessage() . ')</div>';
                //die();   
            }
        } catch (PDOException $ex) {
            $msgresultado = '<div class="alert alert-danger">' .
                "El producto no pudo registrarse en la Base de Datos!! :( (" . $ex->getMessage() . ')</div>'; //die(); 
        }
    }

    //Damos valores a los campos
    $valnombre = $nuevonombre;
    $valcategoria = $nuevocategoria;
    $valp = $nuevop;
    $valstock = $nuevostock;
    $valimagen = $nuevoprecio;
    $valportada = $nuevaimagen;
} else {
    //----------------Si no se pulsa en actualizar nos traemos los datos--------------------------------
    if (isset($_GET['codprod']) && (is_numeric($_GET['codprod']))) { //Si tenemos el id y es número

        //Almacenamos el id
        $id = $_GET['codprod'];

        //Nos traemos los datos de la BD
        try {

            //Conectamos en la BD y lo guardamos
            $query = "SELECT * FROM productos WHERE codprod=:id";
            $resultado = $conexion->prepare($query);
            $resultado->execute(['id' => $id]);

            //Si hay datos en la consulta
            if ($resultado) {
                $msgresultado = '<div class="alert alert-success mx-2">' . "La consulta se realizó correctamente(existe el idEjemplar)!!".'</div>';

                //Insertamos los datos traidos de la bd
                $fila = $resultado->fetch(PDO::FETCH_ASSOC);
                //Guardamos en las variables
                $valnombre = $fila['nombre'];
                $valcategoria = $fila['categoria'];
                $valpvp = $fila['pvp'];
                $valstock = $fila['stock'];
                $valobservaciones = $fila['Observaciones'];
                $valimagen = $fila['imagen'];
            }
        } catch (PDOException $ex) {
            $msgresultado = '<div class="alert alert-danger w-100 mx-2">' . "Fallo al realizar al consulta a la Base de Datos!!" . $ex->getMessage().'</div>';
            //die();
        }
    }
}

?>

<!--Validar Formulario Actualizar Usuario-->
<div class="d-flex flex-row mb-3 justify-content-evenly">

    <!--Botón Atras-->
    <a href="index.php"></a>
    <!--Botón Título-->
    <h1>Actualizar Producto -<?php echo $nombreprod?></h1>
    <a class="navbar-brand" href="index.php"><img src="images/exit.png" alt="salir" width="40" height="40"></a>
</div>

<!-- Modal para actualizar Libro Utilizamos la clase agregarLibro para estilos-->
<div class="container formulario">

    <!--Formulario Actualizar Libro-->
    <form action="" method="POST" enctype="multipart/form-data">

        <!--Campos-->
        <!--NOMBRE-->
        <div class="form-group mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <!--Mostramos el registro guardado anteriormente en caso de haber uno-->
            <input type="text" name="nombre" id="nombre" class="form-control" value="<?php
                                                                                        echo $valnombre; ?>">
            <?php echo  mostrar_error($errores, "nombre"); ?>
        </div>

        <!--CATEGORIA-->
        <div class="form-group mb-3">
            <label for="categoria" class="form-label">Categoría</label>

            <!--Mostramos el registro guardado anteriormente en caso de haber uno-->
            <input type="text" name="categoria" id="titulo" class="form-control" value="<?php
                                                                                        echo $valcategoria; ?>">
            <?php echo  mostrar_error($errores, "categoria"); ?>
        </div>

        <!--Observaciones-->
        <div class="form-group mb-3">
            <label for="observaciones" class="form-label">Obervaciones</label>
            <textarea name="observaciones" class="form-control" id="observaciones">
                <?php
                echo htmlspecialchars($valobservaciones); ?></textarea>
            <?php echo  mostrar_error($errores, "observaciones"); ?>
        </div>
        <!-- Inicializar CKEditor -->
        <script>
            CKEDITOR.replace('observaciones');
        </script>

        <!--stock-->
        <div class="form-group mb-3">
            <label for="stock" class="form-label">STOCK</label>
            <!--Mostramos el registro guardado anteriormente en caso de haber uno-->
            <input type="number" step="0.01" name="pvp" id="stock" class="form-control"
                value="<?php
                        echo $valstock; ?>">

            <?php echo  mostrar_error($errores, "stock"); ?>
        </div>

        <!--PVP-->
        <div class="form-group mb-3">
            <label for="pvp" class="form-label">PVP</label>
            <!--Mostramos el registro guardado anteriormente en caso de haber uno-->
            <input type="number" step="0.01" name="pvp" id="pvp" class="form-control"
                value="<?php
                        echo $valpvp; ?>">

            <?php echo  mostrar_error($errores, "pvp"); ?>
        </div>

        <!--Imagen-->
        <div class="form-group mb-3">
            <?php if ($valimagen != null) { ?>
                <img src="images/<?php echo $valimagen; ?>"
                    width="60" /></br>
            <?php } ?>
            <label for="portada" class="form-label">Actualizar Imagen</label></br>
            <input type="file" name="portada" class="form-control"><br />
            <?php echo  mostrar_error($errores, "portada"); ?>
        </div>


        <!--Btn Actualizar Producto-->
        <button type="submit" name="actualizar" class="btn btn-primary">Actualizar Producto</button>
    </form>
</div>
<?php require 'include/footer.php'; ?>
</body>

</html>