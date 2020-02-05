<?php
include 'php/sessionManagerChecker.php';
?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Reporte de Errores en el Loggin</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/"
        crossorigin="anonymous">
    <link rel="stylesheet" href="css/main.css">
</head>

<body data-title='loggin_error_reports'>

    <?php include "php/inc/header.php"?>
    
    <section>
        <div class="contenido contenedor">
            <img src="img/principalIcon.png" alt="icono preparatoria Nayar">
            <h2>REPORTE DE ERRORES EN INICIO DE SESIÓN</h2>
            <div class="carga_materias">
                <form  class="formulario">
                    <fieldset>
                    <div class="center"><input class="formInputMailReport moveInput" type="text" id="problem_name" autocomplete="on" placeholder="Nombre" disabled="false"></div>
                    <div class="center"><input class="formInputMailReport moveInput" type="text" id="problem_mail" autocomplete="on" placeholder="Correo" disabled="false"></div>
                    <div class="center"><input class="formInputMailReport moveInput" type="text" id="problem_subject" autocomplete="on" placeholder="Asunto" disabled="false"></div>
                    <div class="center"><textarea class="textAreaInput" name="description" id="problem_description" autocomplete="on" placeholder="Descripción" disabled="false"></textarea></div>
                    <div class="center"><div class="moveLabel"><label class="labelTutor" for="problem_answer">Respuesta: </label></div>
                    <textarea class="textAreaAnswer" type="text" id="problem_answer" autocomplete="on"></textarea></div>
                        <input type="button" value="ENVIAR" class="button save" id="button_send_mail">
                        <input type="button" value="LIMPIAR" class="button clean concentrado" id="cleanup_button">
                    </fieldset>
                </form>
                <div class='div_tabla table_height'>
                    <table>
                        <thead>
                            <tr>
                                <th>Acciones</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Nombre</th>
                                <th>Asunto</th>
                                <th>Correo</th>
                            </tr>
                        </thead>
                            <tbody id="tbody_mails">
                            
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