<?php
include 'php/sessionManagerChecker.php';
?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Administración de Cuentas</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <link rel="apple-touch-icon" href="icon.png">
    <!-- Place favicon.ico in the root directory -->

    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/"
        crossorigin="anonymous">
</head>

<body data-title='body_manage_accounts'>

    <?php include "php/inc/header.php"?>
    
    <section>
        <div class="contenido contenedor">
            <img src="img/principalIcon.png" alt="icono preparatoria Nayar">
            <h2>ADMINISTRACIÓN DE CUENTAS</h2>
            <div class="manage_accounts">
                <form  class="formulario">
                    <fieldset>
                    <div class="doubleColumn">
                    <div class="smallInputArrange">
                        <label class="smallInputLabel" for="user_id">Matrícula de Usuario: </label><br>
                        <input class="formInputSmall" class="user_id" name="user_id" id="user_id">
                        </div>
                        <div class="smallInputArrange">
                        <label class="smallInputLabel" for="kind_user">Tipo de usuario: </label><br>
                        <select  class="formInputSmall" class="kind_user" name="kind_user" id="kind_user">
                            <option value="manager">Administrador</option>
                            <option value="teacher">Profesor</option>
                            <option value="student">Alumno</option>
                        </select>
                        </div>
                    </div>
                    <div class="doubleColumn">
                    <div class="smallInputArrange">
                        <label class="smallInputLabel" for="password_user">Contraseña: </label><br>
                        <input class="formInputSmall" class="password_user" name="password_user" id="password_user" disabled="true">
                        </div>
                        <div class="smallInputArrange">
                        <input type="button" value="GENERAR" class="button pass_generator" id="button_get_new_pass">
                        </div>
                    </div>
                        <input type="button" value="GUARDAR" class="button save" id="button_save_user">
                        <input type="button" value="ACTUALIZAR" class="button update" id="button_update">
                        <input type="button" value="LIMPIAR" class="button clean" id="button_cleanup">
                    </fieldset>
                </form>
                <input class="formInputSearch center" type="text" name="search_user_information" id="search_user_information" placeholder="Buscar por Matricula o Tipo">
                <i class="fas fa-search"></i>
                <div class='div_tabla table_height'>
                    <table>
                        <thead>
                            <tr>
                                <th>Acciones</th>
                                <th>Matrícula</th>
                                <th>Contraseña</th>
                                <th>Tipo de Usario</th>
                            </tr>
                            <tr>
                            </tr>
                        </thead>
                            <tbody id="tbody_users_loggin">
                            
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