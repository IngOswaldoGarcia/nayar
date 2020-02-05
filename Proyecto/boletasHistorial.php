<?php
include 'php/sessionManagerChecker.php';
?>
<!doctype html>
<html class="no-js" lang="">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Boletas, Actas E Historial</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
</head>

<body data-title='card_grades_history'>

    <?php include "php/inc/header.php"?>
    
    <section>
        <div class="contenido contenedor">
            <img src="img/principalIcon.png" alt="icono preparatoria Nayar">
            <h2>BOLETAS DE CALIFICACIONES</h2>
            <label for="report_card_kind">Tipo de Boleta:</label><br>
            <select class="formInput" class="report_card_kind" id="report_card_kind" name="report_card_kind">
                <option selected="true" disabled="disabled">Seleccione una Opción</option>
                <option value="per_student">Boleta del Alumno</option>
                <option value="per_group">Boleta por Grupo</option>
                <option value="per_group_subjects">Boleta por Grupo con Todas las Materias</option>
                <option value="student_historial">Historial Academico</option>
            </select><br>
            <!--Por Alumno-->
            <div class="per_student" id="per_student">
            <form class="formulario" target="_blank" method="POST" action="php/pdf.php">
                <fieldset>
                    
                        <input id="input_search_student_id_card" name="input_search_student_id_card" class="formInputSearch" list="search_student_id_card"  placeholder="Matricula Alumno"><i
                            class="fas fa-search"></i>
                        <datalist id="search_student_id_card">
                        </datalist>
                        <p class="pName">Nombre: <span id="individual_student_name_card" name="individual_student_name_card">No Asignado</span></p><br>
                        <div class="doubleColumn">
                <div class="smallInputArrange">
                    <label class="label_boleta_semestre_card" for="semester_student">Semestre: </label>
                    <select class="formInputSmall" name="semester_student_card" id="semester_student_card">
                            <option value="Primero">Primero</option>
                            <option value="Segundo">Segundo</option>
                            <option value="Tercero">Tercero</option>
                            <option value="Cuarto">Cuarto</option>
                            <option value="Quinto">Quinto</option>
                            <option value="Sexto">Sexto</option>
                        </select>
                    </div>
                    <div class="smallInputArrange">
                    <label class="label_boleta_bachillerato" for="kind_student_subjects_card">Bachillerato: </label><br>
                    <select class="formInputSmall" name="kind_student_subjects_card" id="kind_student_subjects_card">
                            <option value="Tronco Común">Tronco Común</option>    
                        </select>
                    </div> 
                        <input type="hidden" id="report_card_kind" name="report_card_kind" value="per_student"> 
                    </div>
                        <input type="submit" value="VISTA PREVIA" class="button" id="individual_student_preview_card">
                    
                </fieldset>    
            </form> 
            </div>
            <!--Por Grupo-->
            <div class="per_group" id="per_group">
            <form class="formulario" target="_blank" method="POST" action="php/pdf.php">
                <fieldset>
                    
                        <input class="formInputSearch" list="search_teacher_id_card" id='input_search_teacher_id_card' name='input_search_teacher_id_card' placeholder="Matricula Profesor"><i
                            class="fas fa-search"></i>
                        <datalist id="search_teacher_id_card">
                        </datalist>
                        <p class="pName">Nombre: <span id="teachers_name_card">No Asignado</span></p>
                        <div class="doubleColumn">
                <div class="smallInputArrange">
                <label class="label_boleta_semestre_card" for="school_year_teacher_card">Ciclo Escolar: </label>
                <select class="formInputSmall" id="school_year_teacher_card" name="school_year_teacher_card">
                        </select>
                    </div>
                    <div class="smallInputArrange">
                    <label class="label_boleta_bachillerato" for="group">Grupo: </label>
                    <select class="formInputSmall" name="teachers_group_card" id="teachers_group_card">
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
                <label class="label_boleta_semestre_card" for="kind_subjects_teacher_card">Semestre: </label>
                <select class="formInputSmall" name="semester_teacher_card" id="semester_teacher_card">
                        <option value="Primero">Primero</option>
                            <option value="Segundo">Segundo</option>
                            <option value="Tercero">Tercero</option>
                            <option value="Cuarto">Cuarto</option>
                            <option value="Quinto">Quinto</option>
                            <option value="Sexto">Sexto</option>
                        </select>
                    </div>
                    <div class="smallInputArrange">
                    <label class="label_boleta_bachillerato" for="group">Bachillerato:: </label>
                    <select class="formInputSmall" name="kind_subjects_teacher_card" id="kind_subjects_teacher_card">
                            <option value="Tronco Común">Tronco Común</option>
                        </select>
                    </div>  
                    </div>
                    <div class="doubleColumn">
                <div class="smallInputArrange">
                <label class="label_boleta_semestre_card" for="teachers_subject_card">Materia: </label>
                        <select class="formInputSmall" name="teachers_subject_card" id="teachers_subject_card">
                        </select>
                    </div>
                    <div class="smallInputArrange">
                    
                    </div>  
                    <input type="hidden" id="report_card_kind" name="report_card_kind" value="per_group"> 
                    </div>
                        <input type="submit" value="VISTA PREVIA" class="button" id="button_teachers_group_preview_card">
                    </fieldset>    
                    </form> 
                     </div>
                <!--Por Grupo con todas las materias-->
                    <div class="per_group_subjects" id="per_group_subjects">
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
                    <input type="hidden" id="report_card_kind" name="report_card_kind" value="per_group_subjects"> 
                    </div>
                        <input type="submit" value="VISTA PREVIA" class="button" id="button_preview_grades_card">
                    </fieldset>    
                    </form> 
                     </div>
                     <!--Por Historial escolar del alumno-->
                    <div class="student_historial clearfix" id="student_historial">
                    <form class="formulario" target="_blank" method="POST" action="php/pdf.php">
                        <fieldset>
                        <input class="formInputSearch" list="search_student_id_record" id="input_search_student_id_record" name="input_search_student_id_record" placeholder="Matricula Alumno"><i
                            class="fas fa-search"></i>
                        <datalist id="search_student_id_record">
                        </datalist>
                        <p class="pName">Nombre: <span id="students_name_record">No Asignado</span></p>
                        <label for="kind_student_historial">Tipo de Historial Academico: </label><br>
                        <select class="formInput class" for="kind_student_historial_record" name="kind_student_historial_record" id="kind_student_historial_record">
                            <option value="student_sep_historial">Historial Academico (SEP)</option>
                            <option value="student_general_historial">Historial Academico General</option>
                        </select><br>
                        <input type="hidden" id="report_card_kind" name="report_card_kind" value="student_historial"> 
                        <input type="submit" value="VISTA PREVIA" class="button" id="button_preview_record">

                        <div id="academic_historial_card">
                        <h3>Historial Academico</h3>
                        <div class="table_space">
                            <p id="header_cicle_1_record"></p>
                            <div class="table_arrange">
                            <table id="semestre_tabla" class="clearfix">
                                <thead id="thead_semester_1">
                                <tr style="background-color: rgb(168, 206, 255)">
                                    <th COLSPAN="3">PRIMER SEMESTRE</th>
                                </tr>
                                <tr>
                                    <td class="td_center_content">ASIGNATURA</td>
                                    <td class="td_center_content">CALIFICACION</td>
                                    <td class="td_center_content">CREDITOS</td>
                                </tr>
                                </thead>
                                <tbody id="tbody_semester_1">
                                <tbody>
                            </table>
                            <table id="semestre_tabla" class="clearfix">
                            <thead id="thead_semester_2">
                                <tr>
                                    <th COLSPAN="3">SEGUNDO SEMESTRE</th>
                                </tr>
                                <tr style="background-color: rgb(168, 206, 255)">
                                    <td class="td_center_content">ASIGNATURA</td>
                                    <td class="td_center_content">CALIFICACION</td>
                                    <td class="td_center_content">CREDITOS</td>
                                </tr>
                                </thead>
                                <tbody id="tbody_semester_2">
                                <tbody>
                            </table>
                            </div>
                        </div>
                        <div class="table_space">
                            <p id="header_cicle_2_record"></p>
                            <div class="table_arrange">
                            <table id="semestre_tabla" class="clearfix">
                            <thead id="thead_semester_3">
                                <tr>
                                    <th COLSPAN="3">TERCER SEMESTRE</th>
                                </tr>
                                <tr style="background-color: rgb(168, 206, 255)">
                                    <td class="td_center_content">ASIGNATURA</td>
                                    <td class="td_center_content">CALIFICACION</td>
                                    <td class="td_center_content">CREDITOS</td>
                                </tr>
                                </thead>
                                <tbody id="tbody_semester_3">
                                <tbody>
                            </table>
                            <table id="semestre_tabla" class="clearfix">
                            <thead id="thead_semester_4">
                                <tr>
                                    <th COLSPAN="3">CUARTO SEMESTRE</th>
                                </tr>
                                <tr style="background-color: rgb(168, 206, 255)">
                                    <td class="td_center_content">ASIGNATURA</td>
                                    <td class="td_center_content">CALIFICACION</td>
                                    <td class="td_center_content">CREDITOS</td>
                                </tr>
                                </thead>
                                <tbody id="tbody_semester_4">
                                <tbody>
                            </table>
                            </div>
                        </div>
                        <div class="table_space">
                            <p id="header_cicle_3_record"></p>
                            <div class="table_arrange">
                            <table id="semestre_tabla" class="clearfix">
                            <thead id="thead_semester_5">
                                <tr>
                                    <th COLSPAN="3">QUINTO SEMESTRE</th>
                                </tr>
                                <tr style="background-color: rgb(168, 206, 255)">
                                    <td class="td_center_content">ASIGNATURA</td>
                                    <td class="td_center_content">CALIFICACION</td>
                                    <td class="td_center_content">CREDITOS</td>
                                </tr>
                                </thead>
                                <tbody id="tbody_semester_5">
                                <tbody>
                            </table>
                            <table id="semestre_tabla">
                            <thead id="thead_semester_6">
                                <tr>
                                    <th COLSPAN="3">SEXTO SEMESTRE</th>
                                </tr>
                                <tr style="background-color: rgb(168, 206, 255)">
                                    <td class="td_center_content">ASIGNATURA</td>
                                    <td class="td_center_content">CALIFICACION</td>
                                    <td class="td_center_content">CREDITOS</td>
                                </tr>
                                </thead>
                                <tbody id="tbody_semester_6">
                                <tbody>
                            </table>
                            </div>
                        </div>
                        </div>
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