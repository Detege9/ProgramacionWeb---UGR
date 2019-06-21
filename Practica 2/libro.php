<!--
    CREATE TABLE Valoraciones (
    isbn VARCHAR(13) REFERENCES Libros(isbn),
    dni VARCHAR(9) REFERENCES Usuarios(dni),
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
      <link rel = "stylesheet" type="text/css" href="libro.css">
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

    <section>

      <?php

      function esCreadordelLibro($dni, $isbn, $conexion){

        $consultaSQL = "SELECT * FROM Libros WHERE isbn='$isbn' AND dni='$dni'";
        $resultados = $conexion->query($consultaSQL);
        $esCreador = true;

        if (!($resultados->rowCount()>0))
          $esCreador = false;

        return ($esCreador);
      }
      $isbn = $_GET['i'];

       /*Se realiza la conexión de la base de datos para sacar todos los datos
         del libro */
       $dsn = "mysql:host=localhost;dbname=db09076204_pw1819;charset=utf8";
       $usuario= "x09076204";
       $password= "09076204";

       /*Se realiza la conexión a la base de datos */
       try {
          $conexion = new PDO( $dsn, $usuario, $password );
          $conexion->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
       } catch ( PDOException $e ) { echo "Conexión fallida: " . $e->getMessage(); }


       if (esCreadordelLibro($_SESSION['DNI'], $isbn, $conexion))
         header('Location:libroleido.php?i='.$isbn);

       $consultaSQL ="SELECT * from Libros where isbn='$isbn'";


       $resultados = $conexion->query($consultaSQL);

       //Aquí se van a almacenar la información que dio el creador a la hora
       //de dar el alta del libro ya que estará en la tabla Libros.
       $opinion_creador;
       $valoracion_creador;

       foreach ($resultados as $fila){

         $opinion_creador = $fila['opinion'];
         $valoracion_creador = $fila['valoracion'];
         echo'
          <h2>'. strtoupper($fila['titulo']).'</h2>
          <article>
            <img id="imagen-libro1" src="'.$fila['rutaimagen'].'" alt="libro-1">
            <p class="titulo-libro"> <b> TITULO:</b> ' .$fila['titulo']. '</p>
            <p class="autor-libro"> <b> AUTOR:</b> ' .$fila['autor']. '</p>
            <p class="autor-libro"> <b> EDITORIAL:</b> ' .$fila['editorial']. '</p>
            <p class="autor-libro"> <b> A&Ntilde;O:</b> ' .$fila['anyo']. '</p>
            <p class="autor-libro"> <b> EDICION:</b> ' .$fila['edicion']. '</p>
          </article>
          <br><br>

          <article>

            <fieldset>
              <legend>
                DESCRIPCI&Oacute;N
              </legend>

              <p>'.$fila['descripcion'].'</p>
            </fieldset>
          </article>';


      }

     ?>
      <br>

      <?php
        $valoraciones = array();
        array_push($valoraciones, $valoracion_creador);
        $consultaSQL = "SELECT valoracion FROM Valoraciones WHERE isbn='$isbn'";
        $resultados = $conexion->query($consultaSQL);

        //Establezco esta igualdad aquí ya que si no hubiera valoraciones en Valoraciones,
        //no crearía la variable $valoracion_media. Por tanto, el for empieza en 1 en vez de 0.
        $valoracion_media = $valoracion_creador;

        foreach($resultados as $fila){
          array_push($valoraciones, $fila['valoracion']);
        }

        //Se calcula la valoración media.
        for ($i=1; $i < count($valoraciones); ++$i){
          $valoracion_media += $valoraciones[$i];
        }


        echo '
          <article>
            VALORACI&Oacute;N MEDIA:' .($valoracion_media)/(count($valoraciones)).'
          </article>';
      ?>
      <hr>

      <br>
      <article>

        <fieldset>
          <legend>
            OPINIONES
          </legend>

          <table border="1" width="500">

            <?php
              $consultaSQL = "SELECT opinion FROM Valoraciones WHERE isbn='$isbn'";
              $opiniones = array();
              array_push($opiniones, $opinion_creador);
              $resultados = $conexion->query($consultaSQL);
              foreach ($resultados as $fila){
                array_push($opiniones, $fila['opinion']);
              }
            ?>
            <tbody>

              <?php
                $columnas = 1;
                $pintar = true;
                //Se pintan tantas filas como haga falta hasta que
                //el número de opiniones sea igual al de columnas que hemos
                //pintado.
                for($i=0; $pintar;++$i){
                  echo '<tr>';

                    if(($columnas%4)==0){
                      echo '<th>'.$opiniones[$columnas-1].'</th>';
                      ++$columnas;
                    }

                    for($columnas; ((($columnas)%4)!=0) && ($columnas <= count($opiniones)); ++$columnas)
                      echo '<th>'.$opiniones[$columnas-1].'</th>';

                    if(($columnas-1) == count($opiniones))
                      $pintar = false;

                  echo '</tr>';
                }
              ?>

            </tbody>
          </table>


        </fieldset>
      </article>
      <br>

      <?php
      $url = 'valorarLibro.php?i='.$isbn;
      echo '
        <span id="enviar"><a href="'.$url.'"> ¿Valorar libro? </a></span>';
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
