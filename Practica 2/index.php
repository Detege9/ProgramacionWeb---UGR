  <!--
      CREATE TABLE Usuarios (
      rutaimagen VARCHAR(120) NOT NULL,
      dni VARCHAR(9) NOT NULL,
      nombre VARCHAR(20) NOT NULL,
      apellidos VARCHAR(60) NOT NULL,
      password VARCHAR(40) NOT NULL,
      email VARCHAR(50) NOT NULL,
      PRIMARY KEY (dni) );
    -->

 <html lang='es'>

   <head>
       <meta charset='utf-8'>
       <title> GLibrary </title>
       <script>
         var myVar;

         function darBienvenida() {
           myVar = setTimeout(alert('¡<?php echo $_POST['nombre'];?>, bienvenid@ a GLibrary!'), 3000);
         }

       </script>
       <link rel="icon" type="image/png" href="imagenes/logo.png">
       <link rel = "stylesheet" type="text/css" href="index.css">
   </head>

  <?php

      class anadirUsuario {
        private $imagen;
        private $dni;
        private $nombre;
        private $apellidos;
        private $password;
        private $email;

        function __construct ($post) {

          if (!empty($post)) {

            if (isset($post['imagen']))
              $this->imagen = $post['imagen'];

            if (isset($post['dni']))
              $this->dni = $post['dni'];

            if (isset($post['nombre']))
              $this->nombre = $post['nombre'];

            if (isset($post['apellidos']))
              $this->apellidos = $post['apellidos'];

            if (isset($post['password']))
              $this->password = $post['password'];

            if (isset($post['email']))
              $this->email = $post['email'];
          }
        }

        function __destruct () {
        }

        public function getrutaImagen (){

          return($this->imagen);
        }

        public function getDNI (){

          return($this->dni);
        }

        public function getNombre (){

          return($this->nombre);
        }

        public function getApellidos (){

          return($this->apellidos);
        }

        public function getPassword () {

          return ($this->password);
        }

        public function getEmail (){

          return($this->email);
        }

        public function existeUsuario ($conexion){

          $dni = $this->getDNI();
          $existe = true;
          $consultaSQL = "SELECT * from Usuarios where dni= '$dni'";

          $resultados = $conexion->query($consultaSQL);

          if (!($resultados->rowCount()>0))
            $existe = false;

          return ($existe);

        }
    }

    /* Se crea una variable con el directorio donde se va a guardar la imagen.*/
    function generarRuta ($post){

      $directorio = "/home/x09076204/public_html/bookrecsysII/imagenes/";
      $nombreImagen = $post['imagen'];

      //move_uploaded_file($_FILES['foto']['tmp_name'],$directorio.$nombreImagen)

      return("imagenes/foto-usuario-2.jpeg");

    }

    /* Antes de llamar al constructor, paso todos los valores del $_POST a un array.
       En imagen se guarda el nombre de la imagen.*/
      $post = array ("imagen" => $_FILES['foto']['name'],
                     "dni" => $_POST['DNI'],
                     "nombre" => $_POST['nombre'],
                     "apellidos" => $_POST['apellidos'],
                     "password" => $_POST ['password'],
                     "email" => $_POST ['email']);

      /* La imagen la convertimos a ruta*/
      $post['imagen'] = generarRuta($post);

      //Se crear una instancia de la clase.
      $nuevoUsuario = new anadirUsuario ($post);

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
       if ($nuevoUsuario->existeUsuario($conexion)){

         //Como el DNI introducido ya existe, vamos a devolverle a la página de altausuario
         //mostrándole un mensaje de que el DNI ya existe. Para poder mandar los datos
         //para que el usuario no los vuelva a introducir, se pasan mediante cookies.
         setcookie("nombre",$nuevoUsuario->getNombre());
         setcookie("apellidos",$nuevoUsuario->getApellidos());
         setcookie("password", $_POST['password']);
         setcookie("email",$nuevoUsuario->getEmail());
         header("Location: altausuario.php?");
       }

       else {
         $consultaSQL = "INSERT INTO Usuarios VALUES (?,?,?,?,?,?)";
         $prepared = $conexion->prepare($consultaSQL);
         $prepared->execute([$nuevoUsuario->getrutaImagen(), $nuevoUsuario->getDNI(),
                             $nuevoUsuario->getNombre(), $nuevoUsuario->getApellidos(),
                             $nuevoUsuario->getPassword(), $nuevoUsuario->getEmail()]);
       }




  ?>

  <!-- En el body va al resto de la página web.
       Cuando se carga el body, se ejecuta el script para dar la bienvenida. -->
  <body onload="darBienvenida()">

    <header>

      <a href="index.html" class="img-cabecera">
          <img src="imagenes/logo.png" width=100% alt="foto-logo">
      </a>

      <h1 class="titulo-cabecera"> GLibrary </h1>

      <form action="index2-login.php" class="boton-cabecera" method="POST">

        <label for="usuario1" id="usuario2"> DNI </label>
        <br>
        <input type="text" name="usuario" id="usuario1" size="15" onblur="comprobarDNI()" required/>
        <input type = "checkbox" id = "recordarme" value = "" "¿Recordarme?" />
        <label for = "recordarme"> Recordarme </label>
        <br>

        <label for="password1" id="password2"> Contraseña </label>
        <br>
        <input type="password" name="password" id="password1" value="" size="15" onblur="comprobarPassword()" required/>
        <br>

        <input type = "submit" id = "login" value = "Login" onclick="encriptarPassword()" />
        <br>
        <a href="altausuario.html" id="registrate"> ¿No tienes cuenta? </a>

      </form>
      <hr>
    </header>

    <script>

      function encriptarPassword() {
        var password = document.getElementById("password1").value.trim();
        var passwordCodificada = btoa(password);
        document.getElementById("password1").value = passwordCodificada;
      }

      function comprobarDNI() {
        var dni = document.getElementById("usuario1");
        //Se crea una expresión regular para comprobar si está compuesto por
        //8 número y una letra.
        var expresionRegular = /^\d{8}[a-zA-Z]$/;
        if (!(expresionRegular.test(dni.value)))
          alert ("Formato no válido del DNI. Recuerda que debe ser 8 números y una letra")

      }


      function comprobarPassword(){
        var password = document.getElementById("password1");

        //Lo primero que se comprueba es que no sea vacío.
        if((password.value.length < 8)|| (password.value.length > 40)){
          alert("El campo contraseña debe tener más de 8 caracteres o menos de 40");
          password.style.borderColor = "red";
        }
      }


    </script>

    <section>
      <img id="imagen-relacionada" src="imagenes/imagen-relacionada.png" alt="libros">
    </section>

    <section id="mejoreslibros">
      <h2> Libros mejor valorados </h2>
        <article>
          <img src="imagenes/libro1.png" alt="libro-1">
          <p class="titulo-libro"> FAHRENHEIT 451</p>
          <p id="autor-libro-1"> RAY BRADBURY </p>
        </article>
        <br>

        <article>
          <img src="imagenes/libro2.png" alt="libro-2">
          <p class="titulo-libro"> EL SE&Ntilde;OR DE LOS ANILLOS: LA COMUNIDAD DEL ANILLO</p>
          <p class="autor-libro"> J.R.R. TOLKIEN</p>
        </article>
        <br>
        <article>
          <img src="imagenes/libro3.png" alt="libro-3">
          <p class="titulo-libro"> EL TEMOR DE UN HOMBRE SABIO</p>
          <p class="autor-libro"> PATRICK ROTHFUSS</p>
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
