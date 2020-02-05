<?php

function authenticated_user(){
    if(!user_review()){
        header('Location:principal.php');
        exit();
    }
}
function user_review(){
return isset($_SESSION['id']);
}
session_start();
authenticated_user(); 