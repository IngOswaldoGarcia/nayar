<?php
session_start();
if(isset($_GET['close_session'])){
  $_SESSION = array();
}
?>
<!doctype html>
<html class="no-js" lang="">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Login Instituto De Ciencias Y Letras "Nayar"</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, shrink-to-fit=no">
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
  <link rel="stylesheet" link href="https://fonts.googleapis.com/css?family=Oswald:400,700|PT+Sans:400,700" rel="stylesheet">
</head>

<body class="login_body" data-title="login_body">

  <div class="login_contenedor clearfix">
    <img src="img/principalIcon.png" alt="logo praparatoria Nayar" class="principal_icon_1">
    <h2>INICIO DE SESIÓN</h2>
    <form class="login">
      <fieldset>
        <i class="fas fa-user"></i>
        <input type="text" name="user" id="user" autocomplete="on" placeholder="Usuario">
        <br>
        <i class="fas fa-key"></i>
        <input type="password" name="password" id="password" autocomplete="on" placeholder="Contraseña">
        <div class="pass_function">
        <input type="button" name="show_pass" id="show_pass" class="container_button"><i id="fa-eye" class="fas fa-eye"></i><i id="fa-eye-slash" class="fas fa-eye-slash"></i>
        </div>
        
        <br>
        <a href="correo.php">¿Algún problema? Ponte en contacto con el administrador</a>
        <br>
        <input type="button" value="ENTRAR" class="button_login" id="button_login">
        
        <div id="error_div">

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
</body>

</html>
