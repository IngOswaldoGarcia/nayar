<?php
include 'php/sessionManagerChecker.php';
?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Carga De Materias</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/"
        crossorigin="anonymous">
</head>

<body data-title='load_subjects'>

    <?php include "php/inc/header.php"?>
    
    <section>
        <div class="contenido contenedor">
            <img src="img/principalIcon.png" alt="icono preparatoria Nayar">
            <h2>CARGA DE MATERIAS</h2>
            <div class="carga_materias">
                <form  class="formulario">
                    <fieldset>
                    <div class="doubleColumn">
                    <div class="smallInputArrange">
                        <label class="smallInputLabel" for="current_semester_student">Semestre: </label><br>
                        <select class="formInputSmall" class="current_semester_student" name="current_ semester_student" id="current_semester_student">
                            <option value="Primero">Primero</option>
                            <option value="Segundo">Segundo</option>
                            <option value="Tercero">Tercero</option>
                            <option value="Cuarto">Cuarto</option>
                            <option value="Quinto">Quinto</option>
                            <option value="Sexto">Sexto</option>
                            
                        </select><br>
                        </div>
                        <div class="smallInputArrange">
                        <label class="smallInputLabel" for="current_kind_student_subjects">Bachillerato: </label><br>
                        <select  class="formInputSmall" class="current_kind_student_subjects" name="current_kind_student_subjects" id="current_kind_student_subjects">
                            <option value="Tronco Común">Tronco Común</option>
                        </select>
                        </div>
                    </div>
                        <input type="button" value="CARGAR MATERIAS" class="button carga" id="button_load_semester">
                    </fieldset>
                </form>
                <div class='div_tabla table_height'>
                    <table>
                    <p> Total de Alumnos: <span id="number_of_rows" name="number_of_rows">0</span> </p>
                        <thead>
                            <tr>
                                <th>Seleccionar Alumno</th>
                                <th>Nuevo Semestre</th>
                                <th>Nuevo Bachillerato</th>
                                <th>Matricula Alumno</th>
                                <th>Nombre</th>
                                <th>Semestre Actual</th>
                                <th>Bachillerato Actual</th>
                                
                                
                            </tr>
                            <tr class="tr_align_centerNColor">
                            <td>TODOS <input type="checkbox" class="checkButton" name="all_students" id="all_students"></td>
                                <td><select type="text" class="tabla_entrada" id="new_semester">
                                        <option value="Primero">Primero</option>
                                        <option value="Segundo">Segundo</option>
                                        <option value="Tercero">Tercero</option>
                                        <option value="Cuarto">Cuarto</option>
                                        <option value="Quinto">Quinto</option>
                                        <option value="Sexto">Sexto</option>
                                        <option value="Graduado">Graduado</option>
                            </select> 
                                </td>
                                <td>
                                <Select type="text" class="tabla_entrada" id="new_kind_subjects">
                                    <option value="Tronco Común">Tronco Común</option>    
                                </select> 
                                </td>
                                
                                <td COLSPAN="4">Seleccionar los parametros a los que desea actualizar</td>
                                
                            </tr>
                        </thead>
                            <tbody id="tbody_students_by_semester">
                            
                            </tbody>
                    </table>
                </div>
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