<?php

define('DB_USER','root');
define('DB_PASSWORD','');
define('DB_HOST','localhost');
define('DB_NAME','control_escolar');

/*
define('DB_USER','id9271212_nayar');
define('DB_PASSWORD','nayarnayar');
define('DB_HOST','localhost');
define('DB_NAME','id9271212_nayar_db');
*/
$conn = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
$conn->set_charset("utf8");
//echo $conn->ping();