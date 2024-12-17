<?php

/**
 * Script que muestra en una tabla los valores enviados por el usuario a través 
 * del formulario utilizando el método POST
 */

// Definimos e inicializamos el array de errores
// Definimos e inicializamos el array de errores y las variables asociadas a cada campo
$errores = [];
$nuevonombre = "";
$nuevocategoria = "";
$nuevopvp = "";
$nuevostock = "";
$nuevoobservaciones = "";
$nuevaimagen = "";


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
    if (isset($_POST["actualizar"]) && (count($errores) == 0)) {
        return '<div class="alert alert-success" style="margin-top:5px;"> Procedemos a actualizar el Producto en la Base de Datos</div>';
    }
}

// Visualización de las variables obtenidas mediante el formulario en modal
/*function valoresfrm()
{
    global $nuevotitulo, $nuevoisbn, $fechaFormateada, $nuevoeditorial, $nuevodescripcion, $nuevoprecio, $nuevoautor, $nuevoestado, $nuevaimagen;

    echo "<h4>Datos del Libro <b>" . $nuevotitulo . "</b> obtenidos mediante el formulario para actualizar</h4><br/>";
    echo "<strong>ISBN: </strong>" . $nuevoisbn . "<br/>";
    echo "<strong>Título: </strong>" . $nuevotitulo . "<br/>";
    echo "<strong>Fecha Publicación: </strong>" . $fechaFormateada  . "<br/>";
    echo "<strong>Editorial: </strong>" . $nuevoeditorial . "<br/>";
    echo "<strong>Descripción: </strong>" . $nuevodescripcion . "<br/>";
    echo "<strong>Precio: </strong>" . $nuevoprecio . "€ <br/>";
    echo "<strong>Autor: </strong>" . $nuevoautor . "<br/>";
    echo "<strong>Estado: </strong>" . $nuevoestado . "<br/>";
    echo "<strong>Foto(ruta): </strong>" . $_FILES["portada"]["tmp_name"] . "<br/>";
    echo "<strong>Foto: </strong>" . $nuevaimagen . "<br/>";
}*/

// Comprobamos los campos
if (isset($_POST["actualizar"])) { //Si se pulsa en Añadir Libro

    //Validamos los campos introducidos

    //Campo Nombre
    if (
        !empty($_POST["nombre"]) &&
        (strlen($_POST["nombre"]) < 30)
    ) {
        //Satinizamos
        $nuevonombre = htmlspecialchars(trim($_POST['nombre']));
        //echo  "Título: <b>" . $titulo . "</b><br/>";
    } else {
        $errores["nombre"] = "No puede estar vacío/No puede contener más de 30 caracteres";
    }

    //Campo Categoría
    if (
        !empty($_POST["categoria"]) &&
        (strlen($_POST["categoria"]) < 20)
    ) {
        //Satinizamos
        $nuevocategoria = htmlspecialchars(trim($_POST['categoria']));
        //echo  "Editorial: <b>" . $editorial . "</b><br/>";
    } else {
        $errores["categoria"] = "No puede estar vacío/No puede contener más de 20 caracteres";
    }

    //Campo Observaciones
    if (
        !empty($_POST["observaciones"])
    ) {
        //Satinizamos
        $nuevoobservaciones = $_POST["observaciones"];
        $nuevoobservaciones = trim($nuevoobservaciones); // Eliminamos espacios en blanco
        $nuevoobservaciones = htmlspecialchars($nuevoobservaciones); //Caracteres especiales a HTML
        $nuevoobservaciones = stripslashes($nuevoobservaciones); //Elimina barras invertidas
        //echo  "Descripción: <b>" . $descripcion . "</b><br/>";
    } else {
        $errores["observaciones"] = "No puede estar vacío";
    }

    //Campo PVP
    if (
        !empty($_POST["pvp"] && is_numeric($_POST["pvp"]) && floatval($_POST["pvp"]) >= 0)
    ) {
        //Satinizamos
        $nuevopvp = floatval($_POST['pvp']);
        //echo  "Precio: <b>" . $precio . "</b><br/>";
    } else {
        $errores["pvp"] = "Error en el precio";
    }

    //Campo STOCK
    if (
        isset($_POST["stock"]) && is_numeric($_POST["stock"]) && $_POST["stock"] >= 0
    ) {
        // Sanitizamos el valor de stock
        $nuevostock = intval ($_POST['stock']);
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
