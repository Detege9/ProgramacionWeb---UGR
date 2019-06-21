<!--
    CREATE TABLE Libros (
    rutaimagen VARCHAR(60) NOT NULL,
    isbn VARCHAR(13) NOT NULL,
    dni VARCHAR(9) REFERENCES Usuarios(dni),
    titulo VARCHAR(50) NOT NULL,
    autor VARCHAR(50) NOT NULL,
    editorial VARCHAR(30) NOT NULL,
    anyo SMALLINT UNSIGNED NOT NULL,
    edicion VARCHAR(30) NOT NULL,
    descripcion VARCHAR(300) NOT NULL,
    opinion VARCHAR(350) NOT NULL,
    valoracion TINYINT UNSIGNED NOT NULL,
    PRIMARY KEY (isbn, dni) );
  -->
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
      <link rel = "stylesheet" type="text/css" href="mislibros.css">
  </head>


  <body>

    <header>

      <a href="index2.php" class="img-cabecera">
          <img src="imagenes/logo.png" width=100% alt="foto-logo">
      </a>

      <h1 class="titulo-cabecera"> GLibrary </h1>

      <img src="<?php echo $_SESSION['RUTAIMAGEN'];?>" class="conectado" alt="foto-usuario"/>
      <p class="conectado"> <?php echo $_SESSION['NOMBRE']. " ". $_SESSION['APELLIDOS'];?> </p>

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

    <?php
      /* Se va a realizar la conexión a la base de datos para poder mostrar
         los libros que haya introducido el usuario o un usuario en cuestión */
         $dsn = "mysql:host=localhost;dbname=db09076204_pw1819;charset=utf8";
         $usuario= "x09076204";
         $password= "09076204";

         try {
            $conexion = new PDO( $dsn, $usuario, $password );
            $conexion->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
         } catch ( PDOException $e ) { echo "Conexión fallida: " . $e->getMessage(); }
    ?>

    <section id="mejoreslibros">
      <h2> Libros leidos </h2>

      <?php
        //Se busca los libros introducidos por el usuario.
        $dni = $_SESSION["DNI"];
        $consultaSQL = "SELECT * from Libros where dni= '$dni'";
        $resultados = $conexion->query($consultaSQL);

        if ($resultados->rowCount() == 0) {
          echo '<article id="nohaylibro">
                  <p id="nolibro"> No has le&iacute;do ning&uacute;n libro todav&iacute;a </p>
                </article>';
        }

        else {
          foreach ($resultados as $fila){
            $isbn = $fila['isbn'];
            echo '<article class="libro2-3">
                    <img src="'.$fila['rutaimagen'].'">
                    <p class="titulo-libro"> <a href="libroleido.php?i='.$isbn.'">'.$fila['titulo'].' </a></p>
                    <p class="autor-libro">'.$fila['autor'].'</p>
                  </article>
                  <br>';

          }
        }

      ?>
    </section>

    <section>
      <h2> &Uacute;ltimos libros a&ntilde;adidos. </h2>
      <article id="ultimos-libros-anadidos">

      <?php
        //Se busca los libros introducidos por algún usuario
        $dni = $_SESSION["DNI"];
        $consultaSQL = "SELECT * from Libros where dni != '$dni'";
        $resultados = $conexion->query($consultaSQL);

        if ($resultados->rowCount() == 0) {
          echo '<p id="nolibro"> Nadie ha a&ntilde;adido ning&uacute;n libro todav&iacute;a </p>';
        }

        else {

          echo '<nav>
                  <ul>';
          foreach ($resultados as $fila){
            $isbn = $fila['isbn'];
            echo '<li> <a href="libro.php?i='.$isbn.'">'.$fila['titulo'].' </a></li>
                  <br>';

          }

          echo '  </ul>
                </nav>';
        }

      ?>

        <br>
        <a id="agregar-libro" href="altalibro.php"> ¿No encuentras un libro? ¡Agr&eacute;galo t&uacute; mismo! </a>
      </article>

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
