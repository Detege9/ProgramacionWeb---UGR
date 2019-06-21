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

      <?php
        class anadirLibro {
          private $rutaimagen;
          private $isbn;
          private $dni;
          private $titulo;
          private $autor;
          private $editorial;
          private $anyo;
          private $edicion;
          private $descripcion;
          private $opinion;
          private $valoracion;


        function __construct ($post) {

          $this->dni  = $_SESSION['DNI'];
          if (!empty($post)) {

            if (isset($post['imagen']))
              $this->imagen = $post['imagen'];

            if (isset($post['isbn']))
              $this->isbn = $post['isbn'];

            if (isset($post['titulo']))
              $this->titulo = $post['titulo'];

            if (isset($post['autor']))
              $this->autor = $post['autor'];

            if (isset($post['editorial']))
              $this->editorial = $post['editorial'];

            if (isset($post['anyo']))
              $this->anyo = $post['anyo'];

            if (isset($post['edicion']))
              $this->edicion = $post['edicion'];

            if (isset($post['descripcion']))
              $this->descripcion= $post['descripcion'];

            if (isset($post['opinion']))
              $this->opinion = $post['opinion'];

            if (isset($post['valoracion']))
              $this->valoracion= $post['valoracion'];
          }
        }

        function __destruct () {
        }

        public function getrutaImagen (){

          return($this->imagen);
        }

        public function getISBN (){

          return($this->isbn);
        }

        public function getDNI (){

          return($this->dni);
        }

        public function getTitulo (){

          return($this->titulo);
        }

        public function getAutor (){

          return($this->autor);
        }

        public function getEditorial () {

          return ($this->editorial);
        }

        public function getAnyo (){

          return($this->anyo);
        }

        public function getEdicion (){

          return($this->edicion);
        }

        public function getDescripcion (){

          return($this->descripcion);
        }

        public function getOpinion (){

          return($this->opinion);
        }

        public function getValoracion (){

          return($this->valoracion);
        }


        public function existeISBN ($conexion){

          $isbn = $this->getISBN();
          $consultaSQL = "SELECT * from Libros where isbn= '$isbn'";

          $resultados = $conexion->query($consultaSQL);

          if ($resultados->rowCount()>0){
            $existe = 1;
          }

          else
            $existe = 0;

          return ($existe);

        }

      }

      /* Se crea una variable con el directorio donde se va a guardar la imagen.*/
      function generarRuta ($post){

        $directorio = "/home/x09076204/public_html/bookrecsysII/imagenes/";
        $nombreImagen = $post['imagen'];

        //move_uploaded_file($_FILES['foto']['tmp_name'],$directorio.$nombreImagen)

        return("imagenes/libro2.png");

      }


      /* Antes de llamar al constructor, paso todos los valores del $_POST a un array.*/
        $post = array ("imagen" => $_FILES['foto']['name'],
                       "isbn" => $_POST['ISBN'],
                       "titulo" => $_POST['titulo'],
                       "autor" => $_POST['autor'],
                       "editorial" => $_POST ['editorial'],
                       "anyo" => $_POST ['anyo'],
                       "edicion" => $_POST ['edicion'],
                       "descripcion" => $_POST ['descripcion'],
                       "opinion" => $_POST ['opinion'],
                       "valoracion" => $_POST['valoracion']);

        /* La imagen la convertimos a ruta*/
        $post['imagen'] = generarRuta($post);

        //Se crear una instancia de la clase.
        $nuevoLibro = new anadirLibro ($post);

      /* Una vez se tiene la clase comprobación creada, creamos la instancia
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
         if ($nuevoLibro->existeISBN($conexion)){

           //Como el ISBN introducido ya existe, vamos a devolverle a la página de altalibro
           //mostrándole un mensaje de que el DNI ya existe. Para poder mandar los datos
           //para que el usuario no los vuelva a introducir, se pasan mediante cookies.
           setcookie("titulo",$_POST['titulo']);
           setcookie("autor",$nuevoLibro->getAutor());
           setcookie("editorial", $nuevoLibro->getEditorial());
           setcookie("anyo",$nuevoLibro->getAnyo());
           setcookie("edicion",$nuevoLibro->getEdicion());
           setcookie("descripcion",$nuevoLibro->getDescripcion());
           setcookie("opinion",$nuevoLibro->getOpinion());
           setcookie("valoracion",$nuevoLibro->getValoracion());
           header("Location: altalibro-2.php?");
         }

         else {
           $consultaSQL = "INSERT INTO Libros VALUES (?,?,?,?,?,?,?,?,?,?,?)";
           $prepared = $conexion->prepare($consultaSQL);
           $prepared->execute([$nuevoLibro->getrutaImagen(), $nuevoLibro->getISBN(),
                               $nuevoLibro->getDNI(), $nuevoLibro->getTitulo(),
                               $nuevoLibro->getAutor(), $nuevoLibro->getEditorial(),
                               $nuevoLibro->getAnyo(), $nuevoLibro->getEdicion(),
                               $nuevoLibro->getDescripcion(), $nuevoLibro->getOpinion(),
                               $nuevoLibro->getValoracion()]);
         }


      ?>

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
    <br>
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
          $i = 1;
          foreach ($resultados as $fila){
            echo '<li> <a href="libro'.$i.'".html">'.$fila['titulo'].' </a></li>
                  <br>';
            $i++;
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
