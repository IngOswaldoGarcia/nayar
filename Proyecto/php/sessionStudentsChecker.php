<?php
include 'php/sessions.php';
include 'php/top_bar.php';
if($_SESSION['kind'] != 'student'){
    header('Location:principal.php');
    exit();
}
?>