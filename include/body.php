<body>

    <!--Título-->
    <div class="container my-3">
        <div class="container text-center">
            <h1>Examen 1 Trimestre DWES</h1>
        </div>
    </div>

    <!--Formulario login de Bootstrap-->
    <div class="container my-3">
        <div class="formulario">
            <form action="" method="POST"><!--Enviamos los datos-->
                <div class="mb-3">
                    <!--Usuario-->
                    <label for="usuario" class="form-label"> Usuario</label>
                    <div class="d-flex col">
                        <!--Insertamos el nombre usuario aterior si hubiese-->
                        <input class="form-control" name="usuario" type="text" placeholder="Nombre usuario" value="<?php echo "nombre"; //$usuarioInput; 
                                                                                                                    ?>" aria-label="default input example">
                        <!--<img class="border rounded bg-body-secondary" src="img/user_login.png" width="40px" height="40px" />-->
                    </div>
                    <?php /*if (empty($_POST['usuario'])) {
                    echo $msgresultadoCampo;
                }*/ ?> <!-- Mensaje de resultado campos vacíos-->
                </div>

                <!--Password-->
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="d-flex col">
                        <input class="form-control" name="password" type="password">
                        <!--<img class="border rounded bg-body-secondary" src="img/contrasena_login.png" width="40px" height="40px" />-->
                    </div>
                    <?php /*if (empty($_POST['password'])) {
                    echo $msgresultadoCampo;
                } */ ?> <!-- Mensaje de resultado campos vacíos-->
                </div>
                <button name="btningresar" type="submit" class="btn btn-primary">INICIAR SESION</button>
            </form>
        </div>
    </div>
    
</body>