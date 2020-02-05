<?php
include 'php/sessionManagerChecker.php';
?>
<!doctype html>
<html class="no-js" lang="">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Apertura de Calificaciones</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
</head>

<body data-title="body_open_grades_platforms">
    
    <?php include "php/inc/header.php"?>


<section>
    <div class="contenido contenedor">
        <img src="img/principalIcon.png" alt="icono preparatoria Nayar">
        <h2>CONTRÓL DE PLATAFORMA Y FECHAS</h2>
        <div class="concentrado_co">
                    <div class="season_grades">
                    <p id="season_grades_date">Temporada de Calificaciones</p>
                    <div class="doubleColumn">
                        <div class="smallInputArrange">
                        <label class="smallInputLabel" for="season_grades_start"> Desde:  </label>
                        <input class="formInputSmall" type="date" name="season_grades_start" id="season_grades_start" value="2000-01-01">
                        </div>
                        <div class="smallInputArrange">
                        <label class="smallInputLabel" for="season_grades_end"> Hasta:  </label>
                        <input class="formInputSmall" type="date" name="season_grades_end" id="season_grades_end" value="2000-01-01">
                        </div>
                    </div>
                <div class="doubleColumn">
                    <div class="smallInputArrange">
                        <label class="smallInputLabel" for="activity_season">Periodo de Actividad:</label>
                                <select class="formInputSmall" id="activity_season" name="activity_season">
                                    <option selected="true" disabled="disabled">Seleccione una Opción</option>
                                    <option value="par_1">Parcial 1</option>
                                    <option value="par_2">Parcial 2</option>
                                    <option value="par_final">Parcial Final</option>
                                    <option value="extra_test_both">Examen 1 y 2 de Regularización</option>
                                    <option value="all">Todos</option>
                                </select>
                    </div>  
                </div>
                    <p>Estado: <span id="span_season_grades">Inactivo</span></p>
                </div>
    </div>
    <div class="date_control">
    <form class="formulario">
            <fieldset>
                <h3>Contról de Fechas</h3>
                <div class="doubleColumn">
                <div class="smallInputArrange">
                    <label class="smallInputLabel" for="school_year">Ciclo Escolar:</label>
                        <select class="formInputSmall" id="school_year" name="school_year">
                            <option value=""></option>
                        </select>
                    </div>  
                </div>
                <p class="date_title_arrange">Semestres Nones</p>
                <div class="doubleColumn">
                <div class="smallInputArrange">
                    <label class="smallInputLabel" for="semester_odd_start">Inicio:</label>
                    <input class="formInputSmall" type="date" name="semester_odd_start" id="semester_odd_start" value="2000-01-01">
                    </div>  
                    <div class="smallInputArrange">
                    <label class="smallInputLabel" for="semester_none_end">Fin:</label>
                    <input class="formInputSmall" type="date" name="semester_odd_end" id="semester_odd_end" value="2000-01-01">
                    </div>
                </div>
                <p class="date_title_arrange">Semestres Pares</p>
                <div class="doubleColumn">
                <div class="smallInputArrange">
                    <label class="smallInputLabel" for="semester_pair_start">Inicio:</label>
                    <input class="formInputSmall" type="date" name="semester_pair_start" id="semester_pair_start" value="2000-01-01">
                    </div>  
                    <div class="smallInputArrange">
                    <label class="smallInputLabel" for="school_year_student">Fin:</label>
                    <input class="formInputSmall" type="date" name="semester_pair_start" id="semester_pair_end" value="2000-01-01">
                    </div>
                </div>
                <input type="button" value="GUARDAR" class="button concentrado" id="save_button">
            </fieldset>
        </form>
    </div>
    <div class="div_tabla table_height">
                <table id="small_table">
                <thead>
                    <tr>
                        <th>Acciones</th>
                        <th>Ciclo Escolar</th>
                        <th COLSPAN="2">Semestres Nones</th>
                        <th COLSPAN="2">Semestres Pares</th>
                    </tr>
                    </thead>
                    <tr class="tr_position_center">
                        <td class="td_align_centerNColor" COLSPAN="2"></td>
                        <td class="arrange_tr_double td_align_centerNColor">Inicio</td>
                        <td class="arrange_tr_double td_align_centerNColor">Fin</td>
                        <td class="arrange_tr_double td_align_centerNColor">Inicio</td>
                        <td class="arrange_tr_double td_align_centerNColor">Fin</td>
                    </tr>
                    <tbody id="tbody_dates">
                    
                    </tbody>
                </table>
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
    <script src="js/moment.js"></script>

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
