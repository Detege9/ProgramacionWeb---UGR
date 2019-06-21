<?php
    //Iniciar una nueva sesión o reanudar la existente.
    session_start();

    if (!(isset($_SESSION['DNI']))){
      header('Location: index.html');
      die() ;
    }
?>
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

      class modificarUsuario {
        private $nombre;
        private $esNombre;
        private $apellidos;
        private $esApellidos;
        private $password;
        private $esPassword;
        private $email;
        private $esEmail;

        function __construct ($post) {

          if (!empty($post)) {

            if (isset($post['nombre'])){
              $this->nombre = $post['nombre'];
              $esNombre = 1;
            }

            if (isset($post['apellidos'])){
              $this->apellidos = $post['apellidos'];
              $esApellidos = 1;
            }

            if (isset($post['password'])){
              $this->password = $post['password'];
              $esPassword = 1;
            }

            if (isset($post['email'])){
              $this->email = $post['email'];
              $esEmail = 1;
            }

          }

        }

        function __destruct () {
        }

        public function getNombre (){

          if (isset($this->nombre))
            return($this->nombre);
        }

        public function getApellidos (){

          if (isset($this->apellidos))
            return($this->apellidos);
        }

        public function getPassword (){

          if (isset($this->password))
            return($this->password);
        }

        public function getEmail (){

          if (isset($this->email))
            return($this->email);
        }



        /* Esta función va a comprobar si los campos introducidos estaban vacíos
           para poder crear una consulta correcta. Si el campo está vacío, se coge
           el dato que está presente en la variable $_SESSION.*/
        public function actualizarUsuario ($conexion){

          $nombre = $this->getNombre();
          $apellidos = $this->getApellidos();
          $password = $this->getPassword();
          $email = $this->getEmail();
          $dni = $_SESSION['DNI'];


          $consultaSQL = "UPDATE Usuarios SET nombre= '$nombre', apellidos='$apellidos',
                                              password='$password', email='$email' where dni='$dni'";

          $resultados = $conexion->query($consultaSQL);
          $_SESSION["NOMBRE"] = $nombre;
          $_SESSION["APELLIDOS"] = $apellidos;
          $_SESSION["EMAIL"] = $email;
          $_SESSION["PASSWORD"] = $password;

        }
      }


      /* Antes de llamar al constructor, paso todos los valores del $_POST a un array.*/
        $post = array ("dni" => $_POST['dni'],
                       "nombre" => $_POST['nombre'],
                       "apellidos" => $_POST['apellidos'],
                       "password" => $_POST['password'],
                       "email" => $_POST['email']);

        /* Encriptamos la contraseña para comprobar que es igual a la que hay guardada*/
        //$post = encriptarPassword($post);

        //Se crear una instancia de la clase.
        $modificarUsuario = new modificarUsuario ($post);

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



         /*Se llama a una función que actualiza los datos de un usuario.*/
         $modificarUsuario ->actualizarUsuario($conexion);

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
