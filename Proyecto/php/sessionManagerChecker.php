<?php
include 'php/sessions.php';
include 'php/top_bar.php';
if($_SESSION['kind'] != 'manager'){
    header('Location:principal.php');
    exit();
}
?>