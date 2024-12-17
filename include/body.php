<!--Generamos la consulta para traernos los datos de la base datos-->
<!--Productos (codprod, nombre, categoria, pvp, stock, imagen, observaciones)-->
<?php require 'delproducto.php'; ?>
<?php

try {
    // Escribimos la consulta
    $sql = "SELECT * FROM productos";

    // Preparamos la consulta
    $resultado = $conexion->prepare($sql);
    // Ejecutamos la consulta
    $resultado->execute();

    //Si hay datos en la consulta
    if ($resultado) {
        $msgresultado = '<div class="alert alert-success mx-2">' . "La consulta se realizó correctamente!!";
    } //o no
} catch (PDOException $ex) {
    $msgresultado = '<div class="alert alert-danger w-100 mx-2">' . "Fallo al realizar al consulta a la Base de Datos!!";
    die();
} ?>

<!--Código HTML-->
<!--Mostramos los pisbles errores de conexión-->
<?php //echo ($msgresultado) 
?>

<body>
    <!--Título-->
    <div class="container my-3">
        <div class="container text-center">
            <h1>Tarea Presencial 2 DWES</h1>
        </div>
    </div>
    <div class="container">
    <!--Agregar Producto-->
    <a class="btn btn-info mx-5" href="agregarProducto.php">Agregar Producto</a>
    <a class="btn btn-info mx-5" href="generarPdf.php">Generar Lista Prodcuto PDF</a>
    </div>
    <!--Listar Productos-->
    <div class="container my-3">
        <!--Tabla Resultados-->
        <table class="table table-striped">
            <thead>
                <tr>
                    <!--<th>IdEjemplar</th>-->
                    <!--<th>codProducto</th>-->
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>PVP</th>
                    <th>Stock</th>
                    <th>Imagen</th>
                    <th>Observaciones</th>
                    <th class="text-center mx-auto" colspan="3">Operaciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <!--Recogemos resultados Recorremos Array-->
                    <?php while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) { //var_dump($fila)?>
                        <!--<td><?= $fila['codprod'] ?></td>-->
                        <?php $codp = $fila['codprod']; ?>
                        <td><?= $fila['nombre'] ?></td>
                        <?php $nombrep = $fila['nombre']; ?>
                        <td><?= $fila['categoria'] ?></td>
                        <?php $categoriap = $fila['categoria']; ?>
                        <td><?= $fila['pvp'] ?></td>
                        <td><?= $fila['stock'] ?></td>
                        <td><?php if ($fila['imagen'] != null) {
                                echo '<img src="' . $fila['imagen'] . '" alt="imagen cargada" width="70" height="80">';
                            } ?> </td>
                        <td><?= $fila['Observaciones'] ?></td>
                        <td><!--Ver Producto-->
                        <a class="btn btn-primary" href="actProducto.php?codprod=<?php $fila['codprod']?>">Ver Producto</a></td>
                        <td><!--Editar Producto-->
                        <a class="btn btn-success" href="actProducto.php?codprod=<?php echo $fila['codprod']?>&nombre=<?php echo $fila['nombre']?>">Editar Producto</a></td>
                        <td><!--Eliminar Producto-->
                        <td> <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalEliminar"
                                            data-id="<?php echo htmlspecialchars($codp) ?>"
                                            data-nombre="<?php echo htmlspecialchars($nombrep); ?>"
                                            data-categoria="<?php echo htmlspecialchars($categoriap); ?>">
                                            Eliminar
                                        </button></td></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>