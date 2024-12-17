<?php

/**
 * Script que muestra en una tabla los valores enviados por el usuario a través 
 * del formulario utilizando el método POST
 */

// Definimos e inicializamos el array de errores
// Definimos e inicializamos el array de errores y las variables asociadas a cada campo
$errores = [];
$nombre = "";
$categoria = "";
$pvp = "";
$stock = "";
$portada = "";
$observaciones = "";
$msgresultado = "";

// Función que muestra el mensaje de error bajo el campo que no ha superado
// el proceso de validación
function mostrar_error($errores, $campo)
{
    $alert = "";
    if (isset($errores[$campo]) && (!empty($campo))) {
        $alert = '<div class="alert alert-danger" style="margin-top:5px;">' . $errores[$campo] . '</div>';
    }
    return $alert;
}

// Verificamos si todos los campos han sido validados
function validez($errores)
{
    //En caso de no haber errores
    if (isset($_POST["anadirProducto"]) && (count($errores) == 0)) {
        return '<div class="alert alert-success" style="margin-top:5px;"> Procedemos a insertar el producto en la Base de Datos</div>';
    }
}

// Visualización de las variables obtenidas mediante el formulario en modal
/*function valoresfrm() {
    global $nombre,$categoria,$pvp,$observaciones,$observaciones,$portada,$stock;
   
    echo "<h4>Datos del producto <b>". $nombre ."</b> obtenidos mediante el formulario</h4><br/>";
    echo "<strong>Nombre: </strong>" . $nombre . "<br/>";
    echo "<strong>Categoría: </strong>" . $categoria . "<br/>";
    echo "<strong>PVP: </strong>" . $pvp . "<br/>";
    echo "<strong>Stock: </strong>" . $stock . "<br/>";
    echo "<strong>Observaciones: </strong>" . $observaciones . "<br/>";
    echo "<strong>Portada(ruta): </strong>" . $_FILES["portada"]["tmp_name"] . "<br/>";
    echo "<strong>Portada: </strong>" . $portada . "<br/>";
  }*/

// Comprobamos los campos
if (isset($_POST["anadirProducto"])) { //Si se pulsa en Añadir Producto
    //Validamos los campos introducidos

    //Campo Nombre
    if (
        !empty($_POST["nombre"]) &&
        (strlen($_POST["nombre"]) < 30 && 
        (!preg_match("/[0-9]/", $_POST["nombre"])))
    ) {
        //Satinizamos
        $nombre = htmlspecialchars(trim($_POST['nombre']));
        //echo  "Título: <b>" . $titulo . "</b><br/>";
    } else {
        $errores["nombre"] = "No puede estar vacío/No puede contener más de 30 caracteres";
    }

    //Campo Categoría
    if (
        !empty($_POST["categoria"]) &&
        (strlen($_POST["categoria"]) < 20 &&
        (!preg_match("/[0-9]/", $_POST["categoria"])))
    ) {
        //Satinizamos
        $categoria = htmlspecialchars(trim($_POST['categoria']));
        //echo  "Editorial: <b>" . $editorial . "</b><br/>";
    } else {
        $errores["categoria"] = "No puede estar vacío/No puede contener más de 20 caracteres";
    }

    //Campo Observaciones
    if (
        !empty($_POST["observaciones"])
    ) {
        //Satinizamos
        $observaciones = $_POST["observaciones"];
        $observaciones = trim($observaciones); // Eliminamos espacios en blanco
        $observaciones = htmlspecialchars($observaciones); //Caracteres especiales a HTML
        $observaciones = stripslashes($observaciones); //Elimina barras invertidas
        //echo  "Descripción: <b>" . $descripcion . "</b><br/>";
    } else {
        $errores["observaciones"] = "No puede estar vacío";
    }

    //Campo PVP
    if (
        !empty($_POST["pvp"] && is_numeric($_POST["pvp"]) && floatval($_POST["pvp"]) >= 0)
    ) {
        //Satinizamos
        $pvp = floatval($_POST['pvp']);
        //echo  "Precio: <b>" . $precio . "</b><br/>";
    } else {
        $errores["pvp"] = "Error en el precio";
    }

    //Campo STOCK
    if (
        isset($_POST["stock"]) && is_numeric($_POST["stock"]) && ($_POST["stock"] >= 0)
    ) {
        // Sanitizamos el valor de stock
        $stock = intval ($_POST['stock']);
        // echo "Stock: <b>" . $stock . "</b><br/>";
    } else {
        $errores["stock"] = "Error en el stock: debe ser un número entero positivo.";
    }


    //Campo Imagen
    if (!isset($_FILES["portada"]) || empty($_FILES["portada"]["tmp_name"])) {
        $errores["portada"] = "Seleccione una imagen válida";
    } else {
        $portada = "La portada nos ha llegado<br/>";
    }
}
