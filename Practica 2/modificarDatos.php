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
      <link rel = "stylesheet" type="text/css" href="modificarDatos.css">
  </head>


  <body>

    <script>

      function contarOpinion(){
        var caracteres = document.getElementById("opinion-libro").value.length;
        document.getElementById("mi-opinion").innerHTML = caracteres;
      }

    </script>

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

         $consultaSQL ="SELECT * from Libros where isbn='$isbn'";
         $resultados = $conexion->query($consultaSQL);

         foreach ($resultados as $fila) {

           echo '
             <h2>'. strtoupper($fila['titulo']).'</h2>
             <article>
               <img id="imagen-libro1" src="'.$fila['rutaimagen'].'" alt="libro-1">
               <p class="titulo-libro"> <b> TITULO:</b> ' .$fila['titulo']. '</p>
               <p class="autor-libro"> <b> AUTOR:</b> ' .$fila['autor']. '</p>
               <p class="autor-libro"> <b> EDITORIAL:</b> ' .$fila['editorial']. '</p>
               <p class="autor-libro"> <b> A&Ntilde;O:</b> ' .$fila['anyo']. '</p>
               <p class="autor-libro"> <b> EDICION:</b> ' .$fila['edicion']. '</p>


            <br><br>';
        }
      ?>
      <form action="actualizarOpinionLibro.php" method="POST">
        <!-- OPINIÓN -->
          <fieldset>
            <legend>
              OPINI&Oacute;N
            </legend>

            <!-- Se manda de forma hidden el ISBN del libro. -->
            <input type="hidden" name="isbn" value="<?php echo $isbn;?>"/>

            <label class="anadir-libro" for="opinion-libro">
              <textarea name="opinion" id="opinion-libro" rows="4" cols="75" maxlength="350" onkeyup="contarOpinion()" required></textarea>
              <span id="mi-opinion">0</span>
              <span>/350</span>
            </label>

          </fieldset>

          <br>
        <!-- VALORACIÓN -->

          <fieldset>
            <legend>
              VALORACI&Oacute;N
            </legend>

            <p class="clasificacion">
              <input type="radio" id="valoracion1" value="5" name="valoracion"/>
              <label class="estrella" for="valoracion1"> ★  </label>


              <input type="radio" id="valoracion2" value="4" name="valoracion"/>
              <label class="estrella" for="valoracion2"> ★  </label>


              <input type="radio" id="valoracion3" value="3" name="valoracion"/>
              <label class="estrella" for="valoracion3"> ★  </label>


              <input type="radio" id="valoracion4" value="2" name="valoracion"/>
              <label class="estrella" for="valoracion4"> ★  </label>


              <input type="radio" id="valoracion5" value="1" name="valoracion">
              <label class="estrella" for="valoracion5"> ★  </label>
            </p>

          </fieldset>

          <br>


          <input type = "submit" id="enviar" value = "ENVIAR" />
        </form>
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
