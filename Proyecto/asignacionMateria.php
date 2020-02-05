<?php
include 'php/sessionManagerChecker.php';
?>
<!doctype html>
<html class="no-js" lang="">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Asignacion De Materias</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
</head>

<body data-title="subject_asignation_body">

    <?php include "php/inc/header.php"?>
    
    <section>
        <div class="contenido contenedor">
            <img src="img/principalIcon.png" alt="icono preparatoria Nayar">
            <h2>ASIGNACION DE MATERIAS</h2>
            <form class="formulario">
                <fieldset>
                    <input id="input_search_teacher_id" class="formInputSearch" list="search_teacher_id" placeholder="Matricula Profesor"><i class="fas fa-search"></i>
                    <datalist id="search_teacher_id">
                    </datalist><br>
                    <p class="pName">Nombre: <span id="teachers_name">No Asignado</span></p><br>
                    <input id="input_search_subject_id" class="formInputSearch" list="search_subject_id"  placeholder="Clave Materia"><i class="fas fa-search"></i>
                    <datalist id="search_subject_id">
                    </datalist><br>
                    <p class="pName" for="subject_name_showed">Materia: <span id="subjects_name">No Asignado</span></p><br>
                    <label for="group_letter">Grupo: </label>                 
                    <select class="formInputSmall" class="group" name="teachers_group" id="teachers_group">
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                            <option value="E">E</option>
                        </select>&nbsp; &nbsp; &nbsp; &nbsp;
                    <label for="schedule">Horario: </label>
                    <input class="formInputSmall" type="text" class="schedule" id="schedule"><br>
                    <input type="button" value="GUARDAR" class="button" id="button_save">
                    <input type="reset" value="LIMPIAR" class="button clean" id="button_cleanup">
                </fieldset>
            </form>
            <h3>MATERIAS ASIGNADAS</h3>
            <div class="div_tabla table_height">
            <table id="teachers_subjects_table">
            <thead>
                <tr>
                    <th>Acciones</th>
                    <th>Clave Materia</th>
                    <th>Materia</th>
                    <th>Grupo</th>
                    <th>Horario</th>
                </tr>
                </thead>
                <tbody id="tbody_teacher_subjects">
                
                </tbody>
            </table>
        </div>
        </div>
    </section>
    <section>
        <div class="contenido contenedor">
            <h3>MATERIAS REGISTRADAS</h3>
            <div class="table_all_subjects">
            <table id="subjects_table">
                <thead>
                <tr>
                    <th>Acciones</th>
                    <th>Clave Materia</th>
                    <th>Materia</th>
                    <th>Orden</th>
                    <th>Semestre</th>
                    <th>Bachillerato</th>
                    <th>Tipo de Materia   </th>
                    <th>Creditos</th>
                </tr>
                <tr>
                    <form action="#" id="form_subjects">
                        <fieldset>
                    <td>
                    <button data-id="a" type="button" class="btn_add_subject add_icon btn">
                    <i class="fas fa-plus-circle"></i>
                    </button>
                    <button data-id="a" type="button" class="btn_clean_form_subject eraser_icon btn">
                    <i class="fas fa-eraser"></i>
                    </button>
                </td>
                        
                        <td><input type="text" class="tabla_entrada" id="new_subject_key"></td>
                        <td><input type="text" class="tabla_entrada" id="new_subject_name"></td>
                        <td> <select type="text" class="tabla_entrada" id="new_subject_position">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                    <option value="13">13</option>
                                    <option value="14">14</option>
                                    <option value="15">15</option>
                                </select>
                            </td>
                        <td><select type="text" class="tabla_entrada" id="new_subject_semester">
                            <option value="Primero">Primero</option>
                            <option value="Segundo">Segundo</option>
                            <option value="Tercero">Tercero</option>
                            <option value="Cuarto">Cuarto</option>
                            <option value="Quinto">Quinto</option>
                            <option value="Sexto">Sexto</option>
                            </select> 
                        </td>
                        
                        <td><Select type="text" class="tabla_entrada" id="new_subject_kind_subjects">
                            <option value="Tronco Común">Tronco Común</option>    
                            </select> 
                        </td>
                        <td> <select type="text" class="tabla_entrada" id="new_subject_kind">
                            <option value="Extra">Extra</option>
                            <option value="Formación para el Trabajo">Formación para el Trabajo</option>
                            <option value="Normal" selected>Normal</option>
                        </select>
                     </td>
                     <td> <select type="text" class="tabla_entrada" id="new_subject_credits">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                     </td>
                        </fieldset>    
                    </form>
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