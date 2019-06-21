<!-- Especifica el tipo de documento. -->
<!doctype html>

<!-- Podemos ayudar al navegador especificando el idioma en el que está escrita
     nuestra página web mediante 'lang'.-->
<html lang='es'>

  <head>
      <meta charset='utf-8'>
      <title> GLibrary </title>
      <link rel="icon" type="image/png" href="imagenes/logo.png">
      <link rel = "stylesheet" type="text/css" href="index2.css">
  </head>


    <?php

      class loguearUsuario {
        private $usuario;
        private $password;

        function __construct ($post) {

          if (!empty($post)) {

            if (isset($post['usuario']))
              $this->usuario = $post['usuario'];

            if (isset($post['password']))
              $this->password = $post['password'];
          }

        }

        function __destruct () {
        }

        public function getUsuario (){

          return($this->usuario);
        }

        public function getPassword (){

          return($this->password);
        }



        public function existeUsuario ($conexion){

          $usuario = $this->getUsuario();
          $password = $this->getPassword();
          $consultaSQL = "SELECT * from Usuarios where dni= '$usuario' AND password='$password'";

          $resultados = $conexion->query($consultaSQL);

          if ($resultados->rowCount()>0){
            $existe = 1;
          }

          else
            $existe = 0;

          return ($existe);

        }
      }

      function buscarNombreApellidos ($conexion){

        $dni = $_SESSION['DNI'];
        $consultaSQL = "SELECT rutaimagen, nombre, apellidos, email, password from Usuarios where dni = '$dni'";
        $resultados = $conexion->query($consultaSQL);

        foreach ($resultados as $fila){
          $rutaimagen = $fila['rutaimagen'];
          $nombre = $fila['nombre'];
          $apellidos = $fila['apellidos'];
          $email = $fila['email'];
          $password= $fila['password'];
        }

        $_SESSION["RUTAIMAGEN"] = $rutaimagen;
        $_SESSION["NOMBRE"] = $nombre;
        $_SESSION["APELLIDOS"] = $apellidos;
        $_SESSION["EMAIL"] = $email;
        $_SESSION["PASSWORD"] = $password;
      }

      /* Antes de llamar al constructor, paso todos los valores del $_POST a un array.*/
        $post = array ("usuario" => $_POST['usuario'],
                       "password" => $_POST['password']);

        /* Encriptamos la contraseña para comprobar que es igual a la que hay guardada*/
        //$post = encriptarPassword($post);

        //Se crear una instancia de la clase.
        $comprobarUsuario = new loguearUsuario ($post);

      /* Una vez se tiene la clase para loguear creada, creamos la instancia
         para poder acceder a la base de datos */
         $dsn = "mysql:host=localhost;dbname=db09076204_pw1819;charset=utf8";
         $usuario= "x09076204";
         $password= "09076204";

         /*Se realiza la conexión a la base de datos */
         try {
            $conexion = new PDO( $dsn, $usuario, $password );
            $conexion->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
         } catch ( PDOException $e ) { echo "Conexión fallida: " . $e->getMessage(); }


         /*Se comprueba si existe un DNI como el introducido, si no existe, se añade a la base de datos*/
         if (!($comprobarUsuario->existeUsuario($conexion))){

           //Como el DNI o la password introducida no coinciden, vamos a devolverle para que vuelva a introducir
           //los datos.
           setcookie("usuario",$comprobarUsuario->getUsuario());
           setcookie("password", $_POST['password']);
           header("Location: loguearUsuario.php?");
         }


        //Se crea la sesión.
        session_start();

        //Tenemos el DNI y vamos a realizar una función para conseguir
        //los datos del usuario que nos hace falta para
        //mostrar, de forma correcta, la página web.
        $_SESSION["DNI"] = $_POST['usuario'];
        buscarNombreApellidos($conexion);


    ?>

  <body>

    <header>

      <a href="index2.php" class="img-cabecera">
          <img src="imagenes/logo.png" width=100% alt="foto-logo">
      </a>

      <h1 class="titulo-cabecera"> GLibrary </h1>

      <img src="<?php echo $_SESSION['RUTAIMAGEN'];?>" class="conectado" alt="foto-usuario"/>
      <p class="conectado"> <?php echo $_SESSION['NOMBRE']." ".$_SESSION['APELLIDOS']; ?>  </p>

      <a href="logout.php" id="desconectar"> Desconectar </a>

      <hr>

      <!-- Se crea una barra de navegación. -->
      <nav>

        <!-- Lista sin enumerar. -->
        <ul id="listado">
          <li class="secciones"> <a class="links" href="mislibros.php"> MIS LIBROS </a></li>
          <li class="secciones"> <a class="links" href="foro.html"> FORO </a> </li>
          <li class="secciones"> <a class="links" href="datospersonales.php"> MIS DATOS </a> </li>
          <li class="secciones"> <a class="links" href="recomendaciones.html"> MIS RECOMENDACIONES </a> </li>
        </ul>
      </nav>

    </header>
    <br><br><br>


    <section>
      <img id="imagen-relacionada" src="imagenes/imagen-relacionada.png" alt="foto-relacionada">
    </section>

    <section id="mejoreslibros">
      <h2> Libros mejor valorados </h2>

      <?php
        //Se busca los tres libros mejor valorados
        $consultaSQL = "SELECT * from Libros ORDER BY valoracion DESC";
        $resultados = $conexion->query($consultaSQL);

        if ($resultados->rowCount() == 0) {
          echo '<article id="nohaylibro">
                  <p id="nolibro"> No has le&iacute;do ning&uacute;n libro todav&iacute;a </p>
                </article>';
        }

        else {
          $i = 0;
          foreach ($resultados as $fila){
            $i++;
            $isbn = $fila['isbn'];
            echo '<article class="libro2-3">
                    <img src="'.$fila['rutaimagen'].'">
                    <p class="titulo-libro"> <a href="libro.php?i='.$isbn.'">'.$fila['titulo'].' </a></p>
                    <p class="autor-libro">'.$fila['autor'].'</p>
                  </article>
                  <br>';
            if ($i == 3)
              break;

          }
        }

      ?>
    </section>

    <footer>
      <hr>
      <a href="mailto:danielterol@correo.ugr.es"> Contacto </a>
      -
      <a href="como_se_hizo.pdf"> C&oacute;mo se hizo </a>
      <hr>
    </footer>



  </body>
</html>
