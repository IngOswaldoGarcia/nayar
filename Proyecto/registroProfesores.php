<?php
include 'php/sessionManagerChecker.php';
?>
<!doctype html>
<html class="no-js" lang="">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Registro De Profesores</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">


</head>

<body data-title = "registration_teachers_body">

        <?php include "php/inc/header.php"?>
        
        <section>
            <div class="contenido contenedor">
                    <img src="img/principalIcon.png" alt="icono preparatoria Nayar">
                    <h2>REGISTRO DE PROFESORES</h2>
                <form class="formulario">
                    <fieldset>
                        <input class="formInput" type="text" name="new_teacher_id" id="new_teacher_id" autocomplete="on" placeholder="Matricula" readonly><br>
                        <input class="formInput" type="text" name="teacher_name" id="teacher_name" autocomplete="on" placeholder="Nombre Completo" ><br>
                        <input class="formInput" type="text" name="teacher_address" id="teacher_address" autocomplete="on" placeholder="Dirección" ><br>
                        <input class="formInput" type="tel" name="teacher_cellphone" id="teacher_cellphone" autocomplete="on" placeholder="Celular" ><br>
                        <input class="formInput" type="email" name="teacher_email" id="teacher_email" placeholder="Correo Electronico" ><br>
                        <input class="formInput" type="text" name="teacher_career" id="teacher_career" autocomplete="on" placeholder="Profesion" ><br>
                        <div class="center"><div class="moveLabel"><label class="labelFecha" for="teacher_birthday">Fecha de Nacimiento: </label></div>
                        <input class="formInput moveInput" type="date" name="teacher_birthday" id="teacher_birthday" value="2000-01-01"></div>
                        <div class="center"><input class="formInput moveInput" type="text" name="teacher_age" id="teacher_age" placeholder="Edad" disabled ></div>
                        <input type="button" value="GUARDAR" class="button" id="button_save">
                        <input type="button" value="ACTUALIZAR" class="button update" id="button_update">
                        <input type="button" value="LIMPIAR" class="button clean" id="button_cleanup">
                    </fieldset>
                </form>
                <h3>Maestros Registrados</h3>
                <div class="div_tabla table_height">
                <table id="teachers_list">
                    <thead>
                    <tr>
                        <th>Acciones</th>
                        <th>Matricula Profesor</th>
                        <th>Nombre</th>
                        <th>Direccion</th>
                        <th>Celular</th>
                        <th>Email</th>
                        <th>Profesion</th>
                        <th>Fecha de Nacimiento</th>
                        <th>Edad</th>
                    </tr>
                    </thead>
                    <tbody>
                    
                    </tbody>
                </table>
            </div>
            </div>
        </section>

    <script src="js/vendor/modernizr-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        window.jQuery || document.write('<script src="js/vendor/jquery-3.3.1.min.js"><\/script>')
    </script>
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>

    <!-- Google Analytics: change UA-XXXXX-Y to be your site's ID. -->
    <script>
        window.ga = function () {
            ga.q.push(arguments)
        };
        ga.q = [];
        ga.l = +new Date;
        ga('create', 'UA-XXXXX-Y', 'auto');
        ga('send', 'pageview')
    </script>
    <script src="https://www.google-analytics.com/analytics.js" async defer></script>
</body>

</html>