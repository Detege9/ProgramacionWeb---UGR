<!-- Especifica el tipo de documento. -->
<!doctype html>

<!-- Podemos ayudar al navegador especificando el idioma en el que está escrita
     nuestra página web mediante 'lang'.-->
<html lang='es'>

  <head>
      <meta charset='utf-8'>
      <title> GLibrary </title>
      <link rel="icon" type="image/png" href="imagenes/logo.png">
      <link rel = "stylesheet" type="text/css" href="altausuario2.css">
  </head>


  <body>

    <header>

      <a href="index.html" class="img-cabecera">
          <img src="imagenes/logo.png" width=100% alt="Logo">
      </a>

      <h1 class="titulo-cabecera"> GLibrary </h1>


      <hr>
    </header>

    <script>

      function encriptarPassword() {
        var password = document.getElementById("contrasenia-usuario").value.trim();
        var passwordCodificada = btoa(password);
        document.getElementById("contrasenia-usuario").value = passwordCodificada;
      }

      function comprobarDNI() {
        var dni = document.getElementById("dni-usuario");
        //Se crea una expresión regular para comprobar si está compuesto por
        //8 número y una letra.
        var expresionRegular = /^\d{8}[a-zA-Z]$/;
        if (expresionRegular.test(dni.value)){
          dni.style.borderColor = "green";

        }

        else {
          alert ("Formato no válido del DNI. Recuerda que debe ser 8 números y una letra");
          dni.style.borderColor = "red";
        }
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


    </script>

    <section id="registro-libro">
      <h2> REG&Iacute;STRATE </h2>
      <article>
        <form action="index.php" method="POST" enctype = "multipart/form-data">
            <fieldset>
              <legend>
                DATOS
              </legend>

              <label class="foto-usuario" for="imagen-usuario"> FOTO:  </label>
              <input type="file" name="foto" id="imagen-usuario"/>
              <br>
              <label class="datos-personales" for="dni-usuario"> DNI: </label>
              <input type="text" name="DNI" id="dni-usuario" onblur="comprobarDNI()" required/>
              <span> El DNI introducido ya pertenece a otro usuario. </span>
              <br>
              <label class="datos-personales" for="nombre-usuario"> NOMBRE: </label>
              <input type="text" name="nombre" id="nombre-usuario" value = <?php echo $_COOKIE["nombre"]?> onblur="comprobarNombre()" required/>
              <br>
              <label class="datos-personales" for="apellidos-usuario"> APELLIDOS: </label>
              <input type="text" name= "apellidos" id="apellidos-usuario" value = <?php echo $_COOKIE["apellidos"]?> onblur="comprobarApellidos()" required/>

              <br>
              <label class="datos-personales" for="contrasenia-usuario"> CONTRASE&Ntilde;A: </label>
              <input type="password" name= "password" id="contrasenia-usuario" value = <?php echo $_COOKIE["password"]?>  required/>
              <br>
              <label class="datos-personales" for="contrasenia-usuario2"> CONFIRMAR CONTRASE&Ntilde;A: </label>
              <input type="password" id="contrasenia-usuario2" value = <?php echo $_COOKIE["password"]?> onblur="comprobarPassword()" required/>
              <br>
              <label class="datos-personales" for="email-usuario"> CORREO ELECTR&Oacute;NICO: </label>
              <input type="email" name="email" id="email-usuario" value = <?php echo $_COOKIE["email"]?> onblur="comprobarEmail()" required/>
            </fieldset>

            <br>
          <input type = "submit" id="enviar" value = "REGISTRARSE" onclick="encriptarPassword()"/>
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
