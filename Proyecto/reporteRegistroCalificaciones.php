<?php
include 'php/sessionManagerChecker.php';
?>
<!doctype html>
<html class="no-js" lang="">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Reporte De Registro De Materias</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

</head>

<body data-title="grades_registration_report">

    <?php include "php/inc/header.php"?>
    
    <section>
        <div class="contenido contenedor">
            <img src="img/principalIcon.png" alt="icono preparatoria Nayar">
            <h2>REPORTE DE REGISTRO DE CALIFICACIONES</h2>
            <form class="formulario">
                <fieldset>
                    <input type="text" class="formInputSearch" list="search_teacher_id" name="input_search_teacher_id" id="input_search_teacher_id" autocomplete="on" placeholder="Matricula Profesor">
                    <datalist id="search_teacher_id">
                    </datalist>
                    <i class="fas fa-search"></i>
                    <p class="pName">Profesor: <span id="teachers_name" >No Asignado</span></p>
                    <input type="button" value="ELIMINAR REGISTROS" class="button_registros" id="button_registries">
                    <div class="div_tabla table_height">
                    <table class="registro_cal_tabla">
                       <thead>
                            <tr>
                                <th>Materia</th>
                                <th class="reporte_semestre">Semestre</th>
                                <th class="reporte_semestre">Grupo</th>
                                <th>Ciclo Escolar</th>
                                <th class="reporte_fecha">Fecha</th>
                                <th>Horario</th>
                                
                            </tr>
                            </thead>
                        <tbody id="tbody_report_grades_registry">
                        </tbody>
                    </table>
                </div>
                </fieldset>
            </form>
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