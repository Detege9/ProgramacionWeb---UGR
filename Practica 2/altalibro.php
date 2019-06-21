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
      <link rel = "stylesheet" type="text/css" href="altalibro.css">
  </head>


  <body>


    <header>

      <a href="index2.php" class="img-cabecera">
          <img src="imagenes/logo.png" width="100%" alt="Imagen logo">
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

    <br><br><br>

    <script>

      function comprobarISBN() {
        var isbn = document.getElementById("ISBN-libro");

        //Se crea una expresión regular para comprobar si está compuesto por
        //trece números.
        var expresionRegular = /^([0-9])*$/;
        if ((expresionRegular.test(isbn.value)) && (isbn.value.length == 13)){
          isbn.style.borderColor = "green";

        }

        else {
          alert ("Formato no válido de ISBN. Recuerda que el ISBN de un libro son 13 números");
          isbn.style.borderColor = "red";
        }
      }

      function comprobarTitulo() {
        var titulo = document.getElementById("titulo-libro");

        //Lo primero que se comprueba es que no sea vacío.
        if(titulo.value.length == 0){
          alert("El campo título es obligatorio");
          titulo.style.borderColor = "red";
        }

        else {
          //Se comprueba que no sea más largo que 50 caracteres.
          if ((titulo.value.length <= 50) && (titulo.value.length >= 10)){
            titulo.style.borderColor = "green";
          }

          else {
            alert ("El título no puede exceder los 50 caracteres y debe ser mayor a 10 caracteres");
            titulo.style.borderColor = "red";
          }
        }


      }

      function comprobarAutor() {
        var autor = document.getElementById("autor-libro");

        //Lo primero que se comprueba es que no sea vacío.
        if(autor.value.length == 0){
          alert("El campo autor es obligatorio");
          autor.style.borderColor = "red";
        }

        else {
          //Se comprueba que no sea más largo que 50 caracteres.
          if ((autor.value.length <= 50)){
            autor.style.borderColor = "green";
          }

          else {
            alert ("El autor no puede exceder los 50 caracteres");
            autor.style.borderColor = "red";
          }
        }
      }

      function comprobarEditorial() {
        var editorial = document.getElementById("editorial-libro");

        //Lo primero que se comprueba es que no sea vacío.
        if(editorial.value.length == 0){
          alert("El campo editorial es obligatorio");
          editorial.style.borderColor = "red";
        }

        else {
          //Se comprueba que no sea más largo que 30 caracteres.
          if ((editorial.value.length <= 30)){
            editorial.style.borderColor = "green";
          }

          else {
            alert ("La editorial no puede exceder los 30 caracteres");
            editorial.style.borderColor = "red";
          }
        }
      }

      function comprobarAnyo() {
        var anyo= document.getElementById("anyo-libro");

        //Lo primero que se comprueba es que no sea vacío.
        if(anyo.value.length == 0){
          alert("El campo año es obligatorio");
          anyo.style.borderColor = "red";
        }

        else {
          //Se va a comprobar que tenga, como mínimo 8 caracteres y que sean iguales.
          if ((anyo.value>= 0) && (anyo.value <= 2019))
            anyo.style.borderColor = "green";
          else {
            alert ("El año debe ser posterior al año 0 e inferior al año 2019");
            anyo.style.borderColor = "red";
          }
        }
      }

      function comprobarEdicion() {
        var edicion = document.getElementById("edicion-libro");

        //Lo primero que se comprueba es que no sea vacío.
        if(edicion.value.length == 0){
          alert("El campo edición es obligatorio");
          edicion.style.borderColor = "red";
        }

        else {
          //Se comprueba que no sea más largo que 30 caracteres.
          if ((edicion.value.length <= 30)){
            edicion.style.borderColor = "green";
          }

          else {
            alert ("La edición no puede ser mayor a 30 caracteres");
            edicion.style.borderColor = "red";
          }
        }
      }

      function contarDescripcion(){
          var caracteres = document.getElementById("descripcion-libro").value.length;
          document.getElementById("mi-descripcion").innerHTML = caracteres;
        }


      function contarOpinion(){
        var caracteres = document.getElementById("opinion-libro").value.length;
        document.getElementById("mi-opinion").innerHTML = caracteres;
      }


</script>



    </script>

    <section id="registro-libro">
        <h2> A&Ntilde;ADE TU LIBRO </h2>

        <article>

          <img id="chica-anadir" src="imagenes/chica-leyendo.jpg" alt="chica-leyendo"/>
          <form action="nuevoLibro.php" method="POST" enctype = "multipart/form-data">
              <label class="anadir-libro2" for="imagen-libro"> PORTADA </label>
              <input type="file" name="foto" id="imagen-libro"/>
              <br>
              <label class="anadir-libro2" for="ISBN-libro"> ISBN </label>
              <input type="text" name="ISBN" id="ISBN-libro" onblur="comprobarISBN()" required/>
              <br>
              <label class="anadir-libro2" for="titulo-libro"> TITULO </label>
              <input type="text" name="titulo" id="titulo-libro" onblur="comprobarTitulo()" required/>
              <br>
              <label class="anadir-libro2" for="autor-libro"> AUTOR </label>
              <input type="text" name="autor" id="autor-libro" onblur="comprobarAutor()" required/>


              <datalist id="listaEditorial">
                  <option value="CABALLO DE TROYA">
                  <option value="AGUILAR">
                  <option value="PLANETA">
                  <option value="ANAGRAMA">
                  <option value="SEIX BARRAL">
              </datalist><br>

              <label class="anadir-libro2" for="editorial-libro"> EDITORIAL </label>
              <input type="text" name="editorial" id="editorial-libro" list="listaEditorial" onblur="comprobarEditorial()" required/>

              <br>
              <label class="anadir-libro2" for="anyo-libro"> A&Ntilde;O </label>
              <input type="number" name="anyo" id="anyo-libro" onblur="comprobarAnyo()" required/>
              <br>
              <label class="anadir-libro2" for="edicion-libro"> EDICION </label>
              <input type="text" name="edicion" id="edicion-libro" onblur="comprobarEdicion()" required/>



            <br><br>

            <!-- DESCRIPCIÓN. -->

            <fieldset>
              <legend>
                DESCRIPCI&Oacute;N
              </legend>

              <label class="anadir-libro" for="descripcion-libro">
                <textarea name="descripcion" id="descripcion-libro" rows="4" cols="75" maxlength="300" onkeyup="contarDescripcion()" required></textarea>
                <span id="mi-descripcion">0</span>
                <span>/300</span>
              </label>
            </fieldset>

            <br>

            <!-- OPINIÓN -->

            <fieldset>
              <legend>
                OPINI&Oacute;N
              </legend>

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
            <!-- BOTÓN -->

            <input type = "submit"  id="enviar"  value = "ENVIAR"/>
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
