<!--
    CREATE TABLE Usuarios (
    rutaimagen VARCHAR(60) NOT NULL,
    dni VARCHAR(9) NOT NULL,
    nombre VARCHAR(20) NOT NULL,
    apellidos VARCHAR(60) NOT NULL,
    password VARCHAR(80) NOT NULL,
    email VARCHAR(90) NOT NULL,
    PRIMARY KEY (dni) );
  -->

<html lang='es'>

 <head>
     <meta charset='utf-8'>
     <title> GLibrary </title>
     <script>
       var myVar;

       function darBienvenida() {
         myVar = setTimeout(alert('Usuario o contraseña erróneas.'), 5000);
       }

     </script>
     <link rel="icon" type="image/png" href="imagenes/logo.png">
     <link rel = "stylesheet" type="text/css" href="index.css">
 </head>

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
      <input type="text" name="usuario" id="usuario1" value=<?php echo $_COOKIE['usuario']; ?> onblur="comprobarDNI()"size="15" required/>
      <input type = "checkbox" id = "recordarme" value = "¿Recordarme?" />
      <label for = "recordarme"> Recordarme </label>
      <br>

      <label for="password1" id="password2"> Contraseña </label>
      <br>
      <input type="password" name="password" value=<?php echo $_COOKIE['password']; ?> id="password1" onblur="comprobarPassword()"size="15" required/>
      <br>

      <input type = "submit" id = "login" value = "Login" onclick="encriptarPassword()"/>
      <br>
      <a href="altausuario.html" id="registrate"> ¿No tienes cuenta? </a>

    </form>
    <hr>
  </header>

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

  <?php

    //Se borran las cookies.
    setcookie("usuario");
    setcookie("password");

   ?>

  <footer>
    <hr>
    <a href="mailto:danielterol@correo.ugr.es"> Contacto </a>
    -
    <a href="como_se_hizo.pdf"> C&oacute;mo se hizo </a>
    <hr>
  </footer>



</body>
</html>
