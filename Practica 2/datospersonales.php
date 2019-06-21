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
      <link rel = "stylesheet" type="text/css" href="datospersonales.css">
  </head>

  <body onload="ponerVerdeDNI()">

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

    <?php
       /* Se crea la conexión con la BD*/
       $dsn = "mysql:host=localhost;dbname=db09076204_pw1819;charset=utf8";
       $usuario= "x09076204";
       $password= "09076204";

       /*Se realiza la conexión a la base de datos */
       try {
          $conexion = new PDO( $dsn, $usuario, $password );
          $conexion->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
       } catch ( PDOException $e ) { echo "Conexión fallida: " . $e->getMessage(); }

       $dni = $_SESSION['DNI'];
       $consultaSQL = "SELECT titulo FROM Libros where dni='$dni'";
       $resultados = $conexion->query($consultaSQL);
       $libros = array();
       foreach ($resultados as $fila){
         array_push($libros,$fila['titulo']);
       }

    ?>

    <script>

      function encriptarPassword() {
        var password = document.getElementById("contrasenia-usuario").value.trim();
        var passwordCodificada = btoa(password);
        document.getElementById("contrasenia-usuario").value = passwordCodificada;
      }

      function ponerVerdeDNI(){
        var dni = document.getElementById('dni-usuario');
        dni.style.borderColor = "green";
      }

      function comprobarNombre() {
        var nombre = document.getElementById("nombre-usuario");
        //Se comprueba que no sea más largo que 20 caracteres.
        if ((nombre.value.length <= 20) && (nombre.value.length >=5)){
          nombre.style.borderColor = "green";
        }

        else {
          alert ("El nombre no puede exceder los 20 caracteres y debe ser mayor que 5 caracteres");
          nombre.style.borderColor = "red";
        }
      }

      function comprobarApellidos() {
        var apellidos = document.getElementById("apellidos-usuario");
        //Se comprueba que no sea más largo que 60 caracteres.
        if ((apellidos.value.length <= 60) && (apellidos.value.length >=10)){
          apellidos.style.borderColor = "green";
        }

        else {
          alert ("Los apellidos no pueden exceder los 60 caracteres y deben ser mayor a 10 carácteres");
          apellidos.style.borderColor = "red";
        }
      }

      function comprobarPassword() {
        var password1= document.getElementById("contrasenia-usuario");
        var password2= document.getElementById("contrasenia-usuario2");

        //Se va a comprobar que tenga, como mínimo 8 caracteres y que sean iguales.
        if (password1.value == password2.value){
          if ((password1.value.length < 8) || (password1.value.length > 40)) {
            alert("Las contraseñas tienen que ser mayor a 8 caracteres por su seguridad o menor a 40 caracteres");
            password1.style.borderColor = "red";
            password2.style.borderColor = "red";
          }
          if ((password1.value.length >= 8) && (password1.value.length <= 40)) {
            password1.style.borderColor = "green";
            password2.style.borderColor = "green";
          }
        }

        else {
          alert ("Las contraseñas no coinciden");
          password1.style.borderColor = "red";
          password2.style.borderColor = "red";
        }
      }

      function comprobarEmail() {
        var email = document.getElementById("email-usuario");
        //Se realiza una expresión regular que compruebe que el email es válido.
        var expresionRegular = /^(?:[^<>()[\].,;:\s@"]+(\.[^<>()[\].,;:\s@"]+)*|"[^\n"]+")@(?:[^<>()[\].,;:\s@"]+\.)+[^<>()[\]\.,;:\s@"]{2,63}$/i;
        if ((expresionRegular.test(email.value) && email.value.length<=50)){
          email.style.borderColor = "green";
        }

        else {
          alert ("El email no tiene un formato correcto o sobrepasa los 50 caracteres.");
          email.style.borderColor = "red";
        }
      }

      function mostrarLibros(){
        var libros=<?php echo json_encode($libros);?>;
        var text="";
        var i;
        for (i = 0; i < libros.length; i++) {
          text += libros[i] + "\n";
        }

        if(text== "")
          alert("No has leído ningún libro todavía");
        else
          alert(text);
      }

    </script>

    <section id="registro-libro">
      <h2> CAMBIA TUS DATOS </h2>
      <article>
        <form action="index2-modificarDatos.php" method="POST">
            <fieldset>
              <legend>
                DATOS
              </legend>

              <label class="datos-personales"> FOTO: </label>
              <img src="<?php echo $_SESSION['RUTAIMAGEN'];?>" onmouseover="mostrarLibros()" class="foto-usuario"/>

              <label class="datos-personales" for="dni-usuario"> DNI: </label>
              <input type="text" name="dni" id="dni-usuario" value=<?php echo $_SESSION['DNI'];?> required readonly/>
              <br>
              <label class="datos-personales" for="nombre-usuario"> NOMBRE: </label>
              <input type="text" name="nombre" id="nombre-usuario" placeholder=<?php echo $_SESSION['NOMBRE'];?> onblur="comprobarNombre()"required/>
              <br>
              <label class="datos-personales" for="apellidos-usuario"> APELLIDOS: </label>
              <input type="text" name="apellidos" id="apellidos-usuario" placeholder=<?php echo $_SESSION['APELLIDOS'];?> onblur="comprobarApellidos()"required/>

              <br>
              <label class="datos-personales" for="contrasenia-usuario"> CONTRASE&Ntilde;A: </label>
              <input type="password" name="password" id="contrasenia-usuario" placeholder="******" required/>
              <br>
              <label class="datos-personales" for="contrasenia-usuario2"> CONFIRMAR CONTRASE&Ntilde;A: </label>
              <input type="password" id="contrasenia-usuario2" placeholder="******" onblur="comprobarPassword()"required/>
              <br>
              <label class="datos-personales" for="email-usuario"> CORREO ELECTR&Oacute;NICO: </label>
              <input type="email" name="email" id="email-usuario" placeholder=<?php echo $_SESSION['EMAIL'];?> onblur="comprobarEmail()"required/>
            </fieldset>

            <br>

          <input type = "submit" id="enviar" value = "MODIFICAR DATOS" onclick="encriptarPassword()"/>
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
