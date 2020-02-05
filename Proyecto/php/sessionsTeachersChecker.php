<?php
include 'php/sessions.php';
include 'php/top_bar.php';
if($_SESSION['kind'] != 'teacher'){
    header('Location:principal.php');
    exit();
}
?>