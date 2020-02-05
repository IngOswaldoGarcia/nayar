<?php
include 'php/sessionManagerChecker.php';
?>
<!doctype html>
<html class="no-js" lang="">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Impresión De Claves De Acceso</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
</head>

<body data-title='pdf_key'>

    <?php include "php/inc/header.php"?>
    
    <section>
        <div class="contenido contenedor">
            <img src="img/principalIcon.png" alt="icono preparatoria Nayar">
            <h2>IMPRESIÓN DE CLAVES DE ACCESO</h2>
                <!--Por Grupo con todas las materias-->
                    <div class="group_key_card" id="group_key_card">
                    <form class="formulario" target="_blank" method="POST" action="php/pdf.php">
                        <fieldset>

                        <div class="doubleColumn">
                <div class="smallInputArrange">
                <label class="label_boleta_semestre_card" for="school_year_group_card">Ciclo Escolar: </label>
                <select class="formInputSmall" id="school_year_group_card" name="school_year_group_card">
                        </select>
                    </div>
                    <div class="smallInputArrange">
                    <label class="label_boleta_bachillerato" for="group_letter_card">Grupo: </label><br>
                    <select class="formInputSmall" class="group" name="group_letter_card" id="group_letter_card">
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                            <option value="E">E</option>
                        </select>
                    </div>  
                    </div>
                    <div class="doubleColumn">
                <div class="smallInputArrange">
                <label class="label_boleta_semestre_card" for="semester_group_card">Semestre: </label>
                <select type="text" class="formInputSmall" id="semester_group_card" name="semester_group_card">
                            <option value="Primero">Primero</option>
                            <option value="Segundo">Segundo</option>
                            <option value="Tercero">Tercero</option>
                            <option value="Cuarto">Cuarto</option>
                            <option value="Quinto">Quinto</option>
                            <option value="Sexto">Sexto</option>
                            </select> 
                    </div>
                    <div class="smallInputArrange">
                    <label class="label_boleta_bachillerato" for="kind_subjects_group_card">Bachillerato: </label>
                    <select class="formInputSmall" name="kind_subjects_group_card" id="kind_subjects_group_card">
                            <option value="Tronco Común">Tronco Común</option>
                        </select>
                    </div>  
                    <input type="hidden" id="report_card_kind" name="report_card_kind" value="students_key"> 
                    </div>
                        <input type="submit" value="CLAVE ESTUDIANTES" class="button button_key students_move" id="button_preview_students_key">
                    </fieldset>    
                    </form> 
                    <form class="formulario" target="_blank" method="POST" action="php/pdf.php">
                        <fieldset>
                        <input type="hidden" id="report_card_kind" name="report_card_kind" value="teachers_key"> 
                        <input type="submit" value="CLAVE PROFESORES" class="button button_key teachers_move" id="button_preview_teachers_key">
                        </fieldset>
                        </form>
                     </div>
        </div>
    </section>


    <script src="js/vendor/modernizr-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
    <script>
        window.jQuery || document.write('<script src="js/vendor/jquery-3.3.1.min.js"><\/script>')
    </script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
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