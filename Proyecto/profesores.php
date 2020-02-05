<?php
include 'php/sessionTeachersChecker.php';
?>
<!doctype html>
<html class="no-js" lang="">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Concentrado De Calificaciones</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
</head>

<body data-title="body_concentrated_ratings_teachers">
<div class="black_layer clearfix">
<div class="popup_regularization_grades">
            <p>Calificación del Examen de Regularización</p>
            <input type="hidden"id="id_subject_grades_card" class="formInputSmall" >
            <input id="input_regularization_score" type="number" class="formInputSmall" list="" placeholder="Calificación">
            <input type="date" id="input_regularization_date" class="formInputSmall">
            <input type="hidden" class="formInputSmall" name="score_status" id="score_status">
            <div  class="arrange_grades_buttons_teachers">
            <input type="button" value="GUARDAR" class="button button_save" id="button_save">
            <input type="button" value="CANCELAR" class="button button_cancel" id="button_cancel">
            </div>
        </div>
 </div>
<section>
    <div class="contenido contenedor">
        
        <div class="principal_screen_teachers">
        <main>
            <h3>BIENVENIDO: </h3>
            <p id="complete_teacher_name"></p>
            <br>
            <img src="img/principalIcon.png" alt="logo praparatoria Nayar">
        </main>
        </div>
        <div class="grades_screen_teachers">
        <img src="img/principalIcon.png" alt="icono preparatoria Nayar">
        <h2>CONCENTRADO DE CALIFICACIONES</h2>
        <div class="concentrado_co">
        <form class="formulario">
            <fieldset>
                <div class="doubleColumn">
                    <div class="smallInputArrange">
                    <label class="smallInputLabel" for="semester_student">Semestre: </label>
                    <select class="formInputSmall" name="semester_student" id="semester_student">
                        <option value="Primero">Primero</option>
                        <option value="Segundo">Segundo</option>
                        <option value="Tercero">Tercero</option>
                        <option value="Cuarto">Cuarto</option>
                        <option value="Quinto">Quinto</option>
                        <option value="Sexto">Sexto</option>
                    </select>
                    </div>
                    <div class="smallInputArrange">
                    <label class="smallInputLabel" for="kind_student_subjects">Bachillerato: </label>
                    <select class="formInputSmall" name="kind_student_subjects" id="kind_student_subjects">
                        <option value="Tronco Común">Tronco Común</option>    
                    </select><br>
                    </div>
                    </div>
                    <div class="doubleColumn">
                <div class="smallInputArrange">
                    <label class="smallInputLabel" for="group">Grupo: </label>
                    <select class="formInputSmall" name="student_group" id="student_group">
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                            <option value="E">E</option>
                        </select><br>
                    </div>
                    <div class="smallInputArrange">
                        <label class="smallInputLabel" for="student_subject">Materia: </label>
                        <select class="formInputSmall" name="student_subject" id="student_subject">
                            </select>
                        </div>
                    </div>
                <div class="doubleColumn">
                    <div class="smallInputArrange">
                    <label class="smallInputLabel" for="school_year_student">Ciclo Escolar:</label>
                        <select class="formInputSmall" id="school_year_student" name="school_year_student" disabled="true">
                            <option value=""></option>
                        </select>
                    </div>
                        
                        <div class="smallInputArrange">
                        <input type="hidden" class="formInputSmall" id="activity_season" name="activity_season">
                        </div>
                    </div>
            </fieldset>
            <fieldset>
                <h3>Registro de calificaciones</h3>
                <input type="button" value="GUARDAR" class="button concentrado" id="save_button">
                <input type="button" value="LIMPIAR" class="button clean concentrado" id="cleanup_button">
                <div class="div_tabla table_height">
                <table>
                <p> Total de Alumnos: <span id="number_of_rows" name="number_of_rows">0</span> </p>
                <thead>
                    <tr>
                        <th>Matricula</th>
                        <th>Nombre</th>
                        <th>Parcial 1</th>
                        <th>Faltas</th>
                        <th>Parcial 2</th>
                        <th>Faltas</th>
                        <th>Final</th>
                        <th>Faltas</th>
                        <th>Promedio</th>
                        <th>Examen Reg. 1</th>
                        <th>Examen Reg. 2</th>
                    </tr>
                    </thead>
                    <tbody id="tbody_all_students_grades">
                    
                    </tbody>
                </table>
                <div id="respuesta_cal"></div>
            </div>
            </fieldset>
        </form>
        </div>
    </div>
        <div class="periodo_calificaciones">

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