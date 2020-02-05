<?php
    function getStudents() {
        include 'db_connexion.php';
        try{
            return $conn->query("SELECT matricula, nombre, apellidos, curp, lugar_nacimiento, fecha_nacimiento, edad, escuela_procedencia, telefono_casa, vive_con, domicilio, nombre_madre, cel_madre, tel_madre, nombre_padre, cel_padre, tel_padre, tiene_enfermedad, enfermedad, tiene_atencion_especial, atencion_especial, tutor, estatus FROM alumnos");
        } catch(Exception $e) {
            echo "Error!!" . $e->getMessage() . "<br>";
            return false;
        }
    }