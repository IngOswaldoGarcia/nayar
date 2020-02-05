<!doctype html>
<html class="no-js" lang="">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Reporta Tu Problema</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, shrink-to-fit=no">
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
  <link rel="stylesheet" link href="https://fonts.googleapis.com/css?family=Oswald:400,700|PT+Sans:400,700" rel="stylesheet">
</head>

<body class="mail_body" data-title="error_report">

  <div class="mail_contenedor clearfix">
    <img src="img/principalIcon.png" alt="logo praparatoria Nayar" class="principal_icon">
    <h2>PROBLEMAS DE INICIO DE SESIÓN</h2>
    <form class="login">
      <fieldset>
        <input type="text" name="name" class="input_mail" id="name" autocomplete="on" placeholder="Nombre Completo">
        <br>
        <input type="email" name="email" class="input_mail" id="email" autocomplete="on" placeholder="Correo Electronico de Contacto">
        <br>
        <input type="text" name="subject" class="input_mail" id="subject" autocomplete="on" placeholder="Asunto">
        <br>
        <textarea class="text_area input_mail" name="description" id="description" autocomplete="on" placeholder="Detalle su Problema Aquí. (Máximo 800 caracteres)"></textarea>
        <div class="double_button">
        <input type="button" value="ENVIAR" class="button_mail  button send" id="button_send">
        <input type="button" value="REGRESAR" class="button_mail  button go_back" id="button_go_back">
        </div>
        
        
      </fieldset>
    </form>
  </div>

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