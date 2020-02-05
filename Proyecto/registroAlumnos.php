<?php
include 'php/sessionManagerChecker.php';
?>

<!doctype html>
<html class="no-js" lang="">

<head>
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Registro De Alumnos</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
</head>

<body data-title="body_student_registration">

<?php include "php/inc/header.php"?>

    <section>
        <div class="contenido contenedor">
            <img class="center" src="img/principalIcon.png" alt="icono preparatoria Nayar">
            <h2>REGISTRO DE ALUMNOS</h2>
            <form action="#" id="form_student">                 
                <fieldset>
                    <div class="center"><input class="formInput moveInput" type="text" name="new_student_id" id="new_student_id" placeholder="Matricula" readonly></div>
                    <div class="center"><input class="formInput moveInput" type="text" name="student_name" id="student_name" autocomplete="on" placeholder="Nombre" ></div>
                    <div class="center"><input class="formInput moveInput" type="text" name="student_last_name" id="student_last_name" autocomplete="on" placeholder="Apellidos" ></div>
                    <div class="center"><input class="formInput moveInput" type="text" name="student_curp" id="student_curp" autocomplete="on" placeholder="CURP" ></div>
                    <div class="center"><input class="formInput moveInput" type="text" name="student_birthplace" id="student_birthplace" autocomplete="on" placeholder="Lugar de Nacimiento" ></div>
                    <div class="center"><div class="moveLabel"><label class="labelFecha" for="student_birthday">Fecha de Nacimiento: </label></div>
                    <input class="formInput moveInput" type="date" name="student_birthday" id="student_birthday" value="2000-01-01"></div>
                    <div class="center"><input class="formInput moveInput" type="text" name="student_age" id="student_age" placeholder="Edad" disabled ></div>
                    <div class="center"><input class="formInput moveInput" type="text" name="student_school_origin" id="student_school_origin" autocomplete="on" placeholder="Escuela de Procedencia" ></div>
                    <div class="center"><input class="formInput moveInput" type="tel" class="student_telephone" id="student_telephone" autocomplete="on" placeholder="Teléfono de Casa" ></div>
                    <div class="center"><input class="formInput moveInput" type="text" class="student_living_with" id="student_living_with" autocomplete="on" placeholder="Vive con" ></div>
                    <div class="center"><input class="formInput moveInput" type="text" class="student_neighborhood" id="student_neighborhood" autocomplete="on" placeholder="Domicilio" ></div>
                    <div class="center"><input class="formInput moveInput" type="text" class="student_mother_name" id="student_mother_name" autocomplete="on" placeholder="Nombre de la Madre" ></div>
                    <div class="center"><input class="formInput moveInput" type="tel" class="student_mother_cellphone" id="student_mother_cellphone" autocomplete="on" placeholder="Celular de la madre" ></div>
                    <div class="center"><input class="formInput moveInput" type="tel" class="student_mother_telephone" id="student_mother_telephone" autocomplete="on" placeholder="Teléfono de la Madre" ></div>
                    <div class="center"><input class="formInput moveInput" type="text" class="student_father_name" id="student_father_name" autocomplete="on" placeholder="Nombre del Padre" ></div>
                    <div class="center"><input class="formInput moveInput" type="tel" class="student_father_cellphone" id="student_father_cellphone" autocomplete="on" placeholder="Celular del Padre" ></div>
                    <div class="center"><input class="formInput moveInput" type="tel" class="student_father_telephone" id="student_father_telephone" autocomplete="on" placeholder="Teléfono del Padre" ></div>
                    <div class="center"><div class="moveLabel"><label class="labelEnfermedad" for="student_illness_question">¿Padece de Alguna Enfermedad?</label></div>
                    <input type="radio" class="student_illness_question_class checkButton" id="student_illness_question" name="student_illness_question" value="no"
                        checked> <span class="textFormatCheckbox">NO</span> &nbsp;&nbsp;&nbsp;
                    <input type="radio" class="student_illness_question_class checkButton" id="student_illness_question" name="student_illness_question" value="si"><span class="textFormatCheckbox">SI</span></div>
                    <div class="center"><input class="formInput hidden_input moveInput" type="text" name="student_illness_answer" id="student_illness_answer" autocomplete="on" placeholder="¿Cual?"></div><br>
                    <div class="center"><div class="moveLabel"><label class="labelAtencion" for="student_special_atention_question">¿Requiere Atención Especial?</label></div>
                    <input type="radio" class="student_special_atention_question_class checkButton" id="student_special_atention_question" name="student_special_atention_question"
                        value="no" checked><span class="textFormatCheckbox">NO</span>       &nbsp;&nbsp;&nbsp;
                    <input type="radio" class="student_special_atention_question_class checkButton" id="student_special_atention_question" name="student_special_atention_question"
                        value="si"><span class="textFormatCheckbox">SI</span></div>
                    <div class="center"><input class="formInput hidden_input moveInput" type="text" name="student_special_atention_answer" id="student_special_atention_answer"
                        autocomplete="on" placeholder="¿Cual"></div><br>
                    <div class="center"><div class="moveLabel"><label class="labelTutor" for="student_tutor_name">Nombre del Tutor: </label></div>
                    <input class="formInput moveInput" type="text" class="student_tutor_name moveInput" id="student_tutor_name" autocomplete="on" placeholder="Nombre del Tutor" ></div>
                    <div class="center"><div class="moveLabel"><label class="smallInputLabel" for="group">Grupo: </label></div></div>
                    <select class="formInput moveInput" name="student_group" id="student_group">
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                            <option value="E">E</option>
                        </select>
                        <div class="center"><div class="moveLabel"><label class="labelStatus" for="student_status">Estatus: </label></div>
                    <select class="formInput moveInput" class="student_status" name="student_status" id="student_status" autocomplete="on" placeholder="Estatus">
                        <option value="activo" selected>Activo</option>
                        <option value="suspendido">Suspendido</option>
                        <option value="baja">Baja</option>
                    </select></div>
                    <div class="moveLabel"><label>Alumno Regular:</label></div>
                    <input type="checkbox" value="RegularStudent" class="checkButton regular_student_check" id="regular_student_check" checked><span class="textFormatCheckbox">Opciones por Defecto</span><br><br>
                <div id="irregular_student_options">
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
                    <label class="smallInputLabel" for="school_year_student">Ciclo Escolar:</label>
                        <select class="formInputSmall" id="school_year_student" name="school_year_student">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="smallInputArrange">
                    <label class="smallInputLabel" for="school_generation_student">Generación:</label>
                        <select class="formInputSmall" id="school_generation_student" name="school_generation_student">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
                </div>
                        <input type="button" value="GUARDAR" class="button" id="button_save">
                        <input type="button" value="ACTUALIZAR" class="button update" id="button_update">
                        <input type="button" value="LIMPIAR" class="button clean" id="button_cleanup">
                </fieldset>
            </form>
        </div>
    </section>
    <section>
        <div class="contenido contenedor">
            <h3>Lista de Alumnos Registrados</h3>
            <input class="formInputSearch center" type="text" name="search_student_information" id="search_student_information" placeholder="Buscar por Matricula, nombre, apellidos, etc.">
            <i class="fas fa-search"></i>
            <div class="div_tabla table_height">
            <table id="students_list">
                <thead>
                    <tr>
                    <th>Acciones</th>
                    <th>Matricula</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>CURP</th>
                    <th>Lugar de Nacimiento</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Edad</th>
                    <th>Escuela de Procedencia</th>
                    <th>Teléfono de Casa</th>
                    <th>Vive con</th>
                    <th>Domicilio</th>
                    <th>Nombre de la madre</th>
                    <th>Celular de la Madre</th>
                    <th>Teléfono de la Madre</th>
                    <th>Nombre del Padre</th>
                    <th>Celular del Padre</th>
                    <th>Teléfono del Padre</th>
                    <th>¿Padece de Alguna Enfermedad?</th>
                    <th>¿Que Enfermedad?</th>
                    <th>¿Requiere Atención especial?</th>
                    <th>¿Que Atención?</th>
                    <th>Nombre del Tutor</th>
                    <th>Grupo</th>
                    <th>Status</th>
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