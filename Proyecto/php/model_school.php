<?php
//-------------------------------------------------Loggin-----------------------------------------------------------
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'COMPROBAR_NUEVOS_CORREOS') {
        require_once 'db_connexion.php';
        try {
            $query = "SELECT visto FROM correo WHERE visto = 0";
            $rows = mysqli_query($conn, $query);
            $numRows = mysqli_num_rows($rows);

            if ($numRows > 0) {
                $response = array(
                    'message' => 'right'
                );
            } else {
                $response = array(
                    'message' => 'empty'
                );
            }
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);
    }}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'BUSCAR_USUARIO_LOGIN') {
        require_once 'db_connexion.php';
        $user_login = filter_var($_POST['user_login'], FILTER_SANITIZE_STRING);
        $password_login = filter_var($_POST['password_login'], FILTER_SANITIZE_STRING);
        $response = array();
        try {
            $query = "SELECT usuario_matricula, usuario_contrasena, usuario_tipo FROM usuarios WHERE (usuario_matricula = BINARY '$user_login') AND (usuario_contrasena = BINARY '$password_login')";
            $result = $conn->query($query);
            $row = mysqli_fetch_array($result);
            if ($row['usuario_matricula'] != '' || $row['usuario_matricula'] != null) {
                if ($row['usuario_tipo'] === 'manager') {
                    session_start();
                    $_SESSION['id'] = $row['usuario_matricula'];
                    $_SESSION['kind'] = $row['usuario_tipo'];
                    $_SESSION['login'] = true;
                    $response = array(
                        'message' => 'right',
                        '1' => $row['usuario_matricula'],
                        '2' => $row['usuario_contrasena'],
                        '3' => $row['usuario_tipo'],
                        'link' => 'index.php',
                    );
                } else if ($row['usuario_tipo'] === 'teacher') {
                    session_start();
                    $_SESSION['id'] = $row['usuario_matricula'];
                    $_SESSION['kind'] = $row['usuario_tipo'];
                    $_SESSION['login'] = true;
                    $response = array(
                        'message' => 'right',
                        '1' => $row['usuario_matricula'],
                        '2' => $row['usuario_contrasena'],
                        '3' => $row['usuario_tipo'],
                        'link' => 'profesores.php',
                    );
                } else if ($row['usuario_tipo'] === 'student') {
                    session_start();
                    $_SESSION['id'] = $row['usuario_matricula'];
                    $_SESSION['kind'] = $row['usuario_tipo'];
                    $_SESSION['login'] = true;
                    $response = array(
                        'message' => 'right',
                        '1' => $row['usuario_matricula'],
                        '2' => $row['usuario_contrasena'],
                        '3' => $row['usuario_tipo'],
                        'link' => 'Alumnos.php',
                    );
                }
            } else {
                $response = array(
                    'message' => 'empty',
                    '1' => $row['usuario_matricula'],
                    '2' => $row['usuario_contrasena'],
                    '3' => $row['usuario_tipo'],
                );
            }
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response, JSON_FORCE_OBJECT);
    }}
//-------------------------------------------------Error report---------------------------------------------------------------
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'MANDAR_CORREO_ERROR') {
        $response = array();
        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $mail = filter_var($_POST['mail'], FILTER_SANITIZE_STRING);
        $subject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
        $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
        $date = getActualDate();
        $time = getActualTime();
        require_once 'db_connexion.php';
        try{
            $query = "INSERT INTO correo (fecha, hora, nombre, asunto, descripcion, correo, visto) VALUES ('$date', '$time', '$name', '$subject', '$description', '$mail', 0)";
            $testing = $conn->query($query);
            if($conn->affected_rows >= 1){
                $response = array(
                    'message' => 'right'
                );
            }else{
                $response = array(
                    'message' => 'wrong'
                );
            }
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);
    }
}
//-------------------------------------------------Concentrado de calificaciones----------------------------------------------
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'COLOCAR_FECHAS_TEMPORADA_CALIFICACION') {
        require_once 'db_connexion.php';
        try {
            $query = "SELECT apertura_calificaciones, clausura_calificaciones, periodo_actividad FROM temporada_calificaciones  WHERE id_temporada_calificaciones = 0";
            $result = mysqli_query($conn, $query);
            if(mysqli_num_rows($result) >= 1){
                $response = array(
                'message' => 'right',
                'result_query' => mysqli_fetch_array($result)
                );
            }else{
                $response = array(
                    'message' => 'empty'
                );
            }
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);

    }}
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'GUARDAR_FECHAS_TEMPORADA_CALIFICACION') {
        $startDate = filter_var($_POST['startDate'], FILTER_SANITIZE_STRING);
        $endDate = filter_var($_POST['endDate'], FILTER_SANITIZE_STRING);
        $activity_season = filter_var($_POST['activity_season'], FILTER_SANITIZE_STRING);
        require_once 'db_connexion.php';
        try {
            $query = "UPDATE temporada_calificaciones SET apertura_calificaciones = '$startDate', clausura_calificaciones = '$endDate', periodo_actividad = '$activity_season' WHERE id_temporada_calificaciones = 0";
            $testing = $conn->query($query);
            if($conn->affected_rows >= 1){
                $response = array(
                'message' => 'right'
                );
            }else{
                $response = array(
                    'message' => 'wrong'
                );
            }
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);

    }}

    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'VERIFICAR_FECHAS_TEMPORADA_CALIFICACION') {
            $startDate = strtotime(filter_var($_POST['startDate'], FILTER_SANITIZE_STRING));
            $endDate = strtotime(filter_var($_POST['endDate'], FILTER_SANITIZE_STRING));
            $actualDate = strtotime(getActualDate());
            $dateStatus = '';
            try {
                if(($actualDate >= $startDate) && ($actualDate <= $endDate)) {
                   $dateStatus = 'true';
                } else {
                    $dateStatus = 'false';
                }
                $response = array(
                    'message' => 'right',
                    'date_status' => $dateStatus,
                    'fecha_actual' => $actualDate.' '.$startDate.' '.$endDate.' '.$dateStatus.' '
                );
            } catch (Exception $e) {
                $response = array(
                    'message' => $e->getMessage()
                );
            }
            echo json_encode($response);
    
        }}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'LLAMAR_ESTUDIANTES_PROFESOR') {
        $group = filter_var($_POST['group'], FILTER_SANITIZE_STRING);
        $school_cycle = filter_var($_POST['school_cycle'], FILTER_SANITIZE_STRING);
        $semesters = filter_var($_POST['semesters'], FILTER_SANITIZE_STRING);
        $optional_classes = filter_var($_POST['optional_classes'], FILTER_SANITIZE_STRING);
        $subject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
        $response = array();
        $studentsRow = "";
        require_once 'db_connexion.php';
        try {
            $query = "SELECT asignar_alumno.alumno_grupo,
        asignar_alumno.alumno_ciclo_escolar,
        asignar_alumno.alumno_semestre,
        asignar_alumno.alumno_bachillerato,
        asignar_alumno.alumno_matricula,
        asignar_alumno.id_asignar_alumno,
        boleta.id_boleta,
        boleta.materia_clave,
        boleta.alumno_matricula,
        boleta.id_semestre_alumno,
        boleta.parcial_1,
        boleta.faltas_1,
        boleta.parcial_2,
        boleta.faltas_2,
        boleta.final,
        boleta.faltas_final,
        boleta.promedio,
        boleta.estado_parcial_1,
        boleta.estado_parcial_2,
        boleta.estado_parcial_final,
        boleta.regularizacion_1,
        boleta.regu_1_fecha,
        boleta.regu_1_estado,
        boleta.regularizacion_2,
        boleta.regu_2_fecha,
        boleta.regu_2_estado,
        alumnos.alumno_matricula,
        alumnos.nombre,
        alumnos.apellidos,
        alumnos.estatus,
        materias.materia_clave,
        materias.tipo_materia
        FROM asignar_alumno
        INNER JOIN boleta
        INNER JOIN alumnos
        INNER JOIN materias
        /*INNER JOIN boleta bol ON asig.materia_clave = bol.materia_clave */
        /*INNER JOIN alumnos alum ON bol.alumno_matricula = alum.alumno_matricula*/
        WHERE asignar_alumno.alumno_grupo = '$group'
        AND
        asignar_alumno.alumno_ciclo_escolar = '$school_cycle'
        AND
        asignar_alumno.alumno_semestre = '$semesters'
        AND
        asignar_alumno.alumno_bachillerato = '$optional_classes'
        AND
        asignar_alumno.alumno_matricula = boleta.alumno_matricula
        AND
        boleta.materia_clave = '$subject'
        AND
        asignar_alumno.id_asignar_alumno = boleta.id_semestre_alumno
        AND
        asignar_alumno.alumno_matricula = alumnos.alumno_matricula
        AND
        materias.materia_clave = '$subject'
        AND 
        alumnos.estatus = 'activo'";
            $result = mysqli_query($conn, $query);
            $id_row = 0;
            $check_1_switch = 0;
            $check_2_switch = 0;
            $check_final_switch = 0;
            $data_column = 0;
            $kind_subject = '';
            while ($row = mysqli_fetch_array($result)) {
                $switcher_parcial_1_state = '';
                $switcher_parcial_2_state = '';
                $switcher_parcial_final_state = '';
                $switcher_parcial_1_class = '';
                $switcher_parcial_2_class = '';
                $switcher_parcial_final_class = '';
                $reg_1_status = '';
                $reg_2_status = '';
                if ($row['estado_parcial_1'] == 0) {
                    $switcher_parcial_1_state = '';
                    $switcher_parcial_1_class = '';
                } else {
                    $switcher_parcial_1_state = 'disabled';
                    $switcher_parcial_1_class = 'blocked_input';
                }
                if ($row['estado_parcial_2'] == 0) {
                    $switcher_parcial_2_state = '';
                    $switcher_parcial_2_class = '';
                } else {
                    $switcher_parcial_2_state = 'disabled ';
                    $switcher_parcial_2_class = 'blocked_input';
                }
                if ($row['estado_parcial_final'] == 0) {
                    $switcher_parcial_final_state = '';
                    $switcher_parcial_final_class = '';
                } else {
                    $switcher_parcial_final_state = 'disabled ';
                    $switcher_parcial_final_class = 'blocked_input';
                }
                if ($row['regu_1_estado'] == 0) {
                    $reg_1_status = 'status_cog_red';
                } else {
                    $reg_1_status = 'status_cog_green';
                }
                if ($row['regu_2_estado'] == 0) {
                    $reg_2_status = 'status_cog_red';
                } else {
                    $reg_2_status = 'status_cog_green';
                }
                if ($id_row == 0) {
                    $check_1_switch = $row['estado_parcial_1'];
                    $check_2_switch = $row['estado_parcial_2'];
                    $check_final_switch = $row['estado_parcial_final'];
                }
                $studentsRow .= "
                        <tr>

                            <td class=\"td_center_content\">" . $row['alumno_matricula'] . "<input id=\"col-" . $id_row . "-" . $data_column++ . "\" value=\"" . $row['alumno_matricula'] . "\" class=\"invissible\"></td>
                            <td class=\"td_left_content\">" . $row['nombre'] . " " . $row['apellidos'] . "<input id=\"col-" . $id_row . "-" . $data_column++ . "\" value=\"" . $row['materia_clave'] . "\" class=\"invissible\"></td>
                            <td class=\"celda_izquierda\"><input id=\"col-" . $id_row . "-" . $data_column++ . "\" type=\"number\" min=\"0\" max=\"10\" class=\"tabla_entrada_concentrado " . $switcher_parcial_1_class . "\" " . $switcher_parcial_1_state . " value=\"" . $row['parcial_1'] . "\" ></td>
                            <td class=\"celda_derecha\"><input id=\"col-" . $id_row . "-" . $data_column++ . "\" type=\"number\" min=\"0\" max=\"10\" class=\"tabla_entrada_concentrado " . $switcher_parcial_1_class . "\" " . $switcher_parcial_1_state . " value=\"" . $row['faltas_1'] . "\"></td>
                            <td class=\"celda_izquierda\"><input id=\"col-" . $id_row . "-" . $data_column++ . "\" type=\"number\" min=\"0\" max=\"10\" class=\"tabla_entrada_concentrado " . $switcher_parcial_2_class . "\" " . $switcher_parcial_2_state . " value=\"" . $row['parcial_2'] . "\"></td>
                            <td class=\"celda_derecha\"><input id=\"col-" . $id_row . "-" . $data_column++ . "\" type=\"number\" min=\"0\" max=\"10\" class=\"tabla_entrada_concentrado " . $switcher_parcial_2_class . "\" " . $switcher_parcial_2_state . " value=\"" . $row['faltas_2'] . "\"></td>
                            <td class=\"celda_izquierda\"><input id=\"col-" . $id_row . "-" . $data_column++ . "\" type=\"number\" min=\"0\" max=\"10\" class=\"tabla_entrada_concentrado " . $switcher_parcial_final_class . "\" " . $switcher_parcial_final_state . " value=\"" . $row['final'] . "\"></td>
                            <td class=\"celda_derecha\"><input id=\"col-" . $id_row . "-" . $data_column++ . "\" type=\"number\" min=\"0\" max=\"10\" class=\"tabla_entrada_concentrado " . $switcher_parcial_final_class . "\" " . $switcher_parcial_final_state . " value=\"" . $row['faltas_final'] . "\"></td>
                            <td class=\"td_center_content\"><span id=\"col-" . $id_row . "-" . $data_column++ . "\" class=\"concentrated_ratings_average\">" . $row['promedio'] . "</span></td>
                            <td class=\"celda_izquierda\"><button id=\"col-" . $id_row . "-" . $data_column++ . "\" data-id=\"" . $id_row . "-" . $data_column++ . 'x'. $id_row . "-" . $data_column++ . 'x'. $id_row . "-" . $data_column++ ."\" type=\"button\" class=\"".$reg_1_status." cog_icon btn\"><i class=\"fas fa-cog\"></i></button><input id=\"col-" . $id_row . "-" . ($data_column-3) . "\" min=\"0\" class=\"tabla_entrada_concentrado blocked_input extra_option_input\" disabled value=\"" . $row['regularizacion_1'] . "\"><input type=\"hidden\" id=\"col-" . $id_row . "-" . ($data_column-2) . "\" disabled value=\"" . $row['regu_1_fecha'] . "\"><input type=\"hidden\"  id=\"col-" . $id_row . "-" . ($data_column-1) . "\" disabled value=\"" . $row['regu_1_estado'] . "\"></td>
                            <td class=\"celda_derecha\"><button id=\"col-" . $id_row . "-" . $data_column++ . "\" data-id=\"" . $id_row . "-" . $data_column++ . 'x'. $id_row . "-" . $data_column++ . 'x'. $id_row . "-" . $data_column++ ."\" type=\"button\" class=\"".$reg_2_status." cog_icon btn\"><i class=\"fas fa-cog\"></i></button><input id=\"col-" . $id_row . "-" . ($data_column-3) . "\" min=\"0\" class=\"tabla_entrada_concentrado blocked_input extra_option_input\" disabled value=\"" . $row['regularizacion_2'] . "\"><input type=\"hidden\" id=\"col-" . $id_row . "-" . ($data_column-2) . "\" disabled value=\"" . $row['regu_2_fecha'] . "\"><input type=\"hidden\" id=\"col-" . $id_row . "-" . ($data_column-1) . "\" disabled value=\"" . $row['regu_2_estado'] . "\"></td>
                        </tr>
                    ";
                $id_row++;
                $data_column = 0;
                $kind_subject = $row['tipo_materia'];
            }
            if(mysqli_num_rows($result) >= 1){
                $response = array(
                'message' => 'right',
                'students_row' => $studentsRow,
                'number_rows' => $id_row,
                'tipo_materia' => $kind_subject,
                'check_parcial_1' => $check_1_switch,
                'check_parcial_2' => $check_2_switch,
                'check_parcial_final' => $check_final_switch
                );
            }else{
                $response = array(
                    'message' => 'empty'
                );
            }
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);
    }
}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'GUARDAR_CALIFICACIONES_SIN_PROFESOR') {
        $response = array();
        $array = $_POST['array'];
        $numberRows = filter_var($_POST['number_rows'], FILTER_SANITIZE_STRING);
        $group = filter_var($_POST['group'], FILTER_SANITIZE_STRING);
        $school_cycle = filter_var($_POST['school_cycle'], FILTER_SANITIZE_STRING);
        $semesters = filter_var($_POST['semesters'], FILTER_SANITIZE_STRING);
        $subject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
        $date = getActualDate();
        $time = getActualTime();
        $testing = 0;
        require_once 'db_connexion.php';
        try {
            $data = json_decode($array);

            for ($i = 0; $i < $numberRows; $i++) {
                $matriculaAlumno = 0;
                $materiaClave = 0;
                $parcial1 = 0;
                $faltas1 = 0;
                $parcial2 = 0;
                $faltas2 = 0;
                $parcialFinal = 0;
                $faltasFinal = 0;
                $promedio = 0;
                $estado_parcial_1 = 0;
                $estado_parcial_2 = 0;
                $estado_parcial_final = 0;
                $regu_1 = 0;
                $regu_1_fecha = '';
                $regu_1_estado = 0;
                $regu_2 = 0;
                $regu_2_fecha = '';
                $regu_2_estado = 0;

                for ($j = 0; $j < 20; $j++) {

                    if ($j == 0) {$matriculaAlumno = $data[$i][$j];} 
                    else if ($j == 1) {$materiaClave = $data[$i][$j];} 
                    else if ($j == 2) {$parcial1 = $data[$i][$j];} 
                    else if ($j == 3) {$faltas1 = $data[$i][$j];} 
                    else if ($j == 4) {$parcial2 = $data[$i][$j];} 
                    else if ($j == 5) {$faltas2 = $data[$i][$j];} 
                    else if ($j == 6) {$parcialFinal = $data[$i][$j];} 
                    else if ($j == 7) {$faltasFinal = $data[$i][$j];} 
                    else if ($j == 8) {$promedio = $data[$i][$j];} 
                    else if ($j == 10) {$regu_1 = $data[$i][$j];} 
                    else if ($j == 11) {$regu_1_fecha = $data[$i][$j];} 
                    else if ($j == 12) {$regu_1_estado = $data[$i][$j];}
                    else if ($j == 14) {$regu_2 = $data[$i][$j];} 
                    else if ($j == 15) {$regu_2_fecha = $data[$i][$j];} 
                    else if ($j == 16) {$regu_2_estado = $data[$i][$j];}
                    else if ($j == 17) {$estado_parcial_1 = $data[$i][$j];} 
                    else if ($j == 18) {$estado_parcial_2 = $data[$i][$j];} 
                    else if ($j == 19) {$estado_parcial_final = $data[$i][$j];}
                }

                $query = "UPDATE boleta SET
                    parcial_1 = '$parcial1',
                    faltas_1 = '$faltas1',
                    parcial_2 = '$parcial2',
                    faltas_2 = '$faltas2',
                    final = '$parcialFinal',
                    faltas_final = '$faltasFinal',
                    promedio = '$promedio',
                    estado_parcial_1 = '$estado_parcial_1',
                    estado_parcial_2 = '$estado_parcial_2',
                    estado_parcial_final = '$estado_parcial_final',
                    regularizacion_1 = '$regu_1',
                    regu_1_fecha = '$regu_1_fecha',
                    regu_1_estado = '$regu_1_estado',
                    regularizacion_2 = '$regu_2',
                    regu_2_fecha = '$regu_2_fecha',
                    regu_2_estado = '$regu_2_estado'
                    WHERE (alumno_matricula = '$matriculaAlumno') AND (materia_clave = '$materiaClave')";
            mysqli_query($conn, $query);
            if($conn->affected_rows >= 1){
                $response = array(
                    'message' => 'right'
                );
            }else{
                $response = array(
                    'message' => 'wrong'
                );
            }
            }
            //$testing =  mysqli_query($conn, $query);
            
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);
    }
}
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'GUARDAR_CALIFICACIONES') {
        $response = array();
        $array = filter_var($_POST['array']);
        $numberRows = filter_var($_POST['number_rows'], FILTER_SANITIZE_STRING);
        $teacher_id = filter_var($_POST['search_teacher_id'], FILTER_SANITIZE_STRING);
        $group = filter_var($_POST['group'], FILTER_SANITIZE_STRING);
        $school_cycle = filter_var($_POST['school_cycle'], FILTER_SANITIZE_STRING);
        $semesters = filter_var($_POST['semesters'], FILTER_SANITIZE_STRING);
        $subject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
        $date = getActualDate();
        $time = getActualTime();
        $testing = 0;
        require_once 'db_connexion.php';
        try {
            $data = json_decode($array);

            for ($i = 0; $i < $numberRows; $i++) {
                $matriculaAlumno = 0;
                $materiaClave = 0;
                $parcial1 = 0;
                $faltas1 = 0;
                $parcial2 = 0;
                $faltas2 = 0;
                $parcialFinal = 0;
                $faltasFinal = 0;
                $promedio = 0;
                $estado_parcial_1 = 0;
                $estado_parcial_2 = 0;
                $estado_parcial_final = 0;
                $regu_1 = 0;
                $regu_1_fecha = '';
                $regu_1_estado = 0;
                $regu_2 = 0;
                $regu_2_fecha = '';
                $regu_2_estado = 0;

                for ($j = 0; $j < 20; $j++) {

                    if ($j == 0) {$matriculaAlumno = $data[$i][$j];} 
                    else if ($j == 1) {$materiaClave = $data[$i][$j];} 
                    else if ($j == 2) {$parcial1 = $data[$i][$j];} 
                    else if ($j == 3) {$faltas1 = $data[$i][$j];} 
                    else if ($j == 4) {$parcial2 = $data[$i][$j];} 
                    else if ($j == 5) {$faltas2 = $data[$i][$j];} 
                    else if ($j == 6) {$parcialFinal = $data[$i][$j];} 
                    else if ($j == 7) {$faltasFinal = $data[$i][$j];} 
                    else if ($j == 8) {$promedio = $data[$i][$j];} 
                    else if ($j == 10) {$regu_1 = $data[$i][$j];} 
                    else if ($j == 11) {$regu_1_fecha = $data[$i][$j];} 
                    else if ($j == 12) {$regu_1_estado = $data[$i][$j];}
                    else if ($j == 14) {$regu_2 = $data[$i][$j];} 
                    else if ($j == 15) {$regu_2_fecha = $data[$i][$j];} 
                    else if ($j == 16) {$regu_2_estado = $data[$i][$j];}
                    else if ($j == 17) {$estado_parcial_1 = $data[$i][$j];} 
                    else if ($j == 18) {$estado_parcial_2 = $data[$i][$j];} 
                    else if ($j == 19) {$estado_parcial_final = $data[$i][$j];}
                }

                $query = "UPDATE boleta SET
                    profesor_matricula = '$teacher_id',
                    parcial_1 = '$parcial1',
                    faltas_1 = '$faltas1',
                    parcial_2 = '$parcial2',
                    faltas_2 = '$faltas2',
                    final = '$parcialFinal',
                    faltas_final = '$faltasFinal',
                    promedio = '$promedio',
                    estado_parcial_1 = '$estado_parcial_1',
                    estado_parcial_2 = '$estado_parcial_2',
                    estado_parcial_final = '$estado_parcial_final',
                    regularizacion_1 = '$regu_1',
                    regu_1_fecha = '$regu_1_fecha',
                    regu_1_estado = '$regu_1_estado',
                    regularizacion_2 = '$regu_2',
                    regu_2_fecha = '$regu_2_fecha',
                    regu_2_estado = '$regu_2_estado'
                    WHERE (alumno_matricula = '$matriculaAlumno') AND (materia_clave = '$materiaClave')";
                mysqli_query($conn, $query);
                if($conn->affected_rows >= 1){
                    $response = array(
                        'message' => 'right'
                    );
                }else{
                    $response = array(
                        'message' => 'wrong'
                    );
                }
            }
            $query = "INSERT INTO control_registro_calificaciones (profesor_matricula, fecha, hora, materia, ciclo_escolar, semestre, grupo)VALUES('$teacher_id', '$date','$time','$subject','$school_cycle','$semesters','$group')";
            mysqli_query($conn, $query);
            
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);
    }
}
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'LLAMAR_MATERIAS_CLAVE_PROFESOR_CALIFICACIONES') {
        $teacher_id = filter_var($_POST['teacher_id'], FILTER_SANITIZE_STRING);
        $semester = filter_var($_POST['semester'], FILTER_SANITIZE_STRING);
        $kind_subjects = filter_var($_POST['kind_subjects'], FILTER_SANITIZE_STRING);
        $student_group = filter_var($_POST['student_group'], FILTER_SANITIZE_STRING);
        $response = array();
        $text_result = "";
        require_once 'db_connexion.php';
        try {
            $query = "SELECT asignacion_materia.profesor_matricula,
        asignacion_materia.materia_clave,
        asignacion_materia.grupo,
        materias.materia_clave,
        materias.materia_nombre,
        materias.materia_semestre,
        materias.materia_bachillerato,
        materias.materia_orden,
        materias.tipo_materia
        FROM asignacion_materia
        INNER JOIN materias
        WHERE asignacion_materia.profesor_matricula = '$teacher_id'
        AND asignacion_materia.grupo = '$student_group'
        AND asignacion_materia.materia_clave = materias.materia_clave
        AND materias.materia_semestre = '$semester'
        AND materias.materia_bachillerato = '$kind_subjects' ORDER BY materia_orden";
            $result = $conn->query($query);
            while ($row = mysqli_fetch_assoc($result)) {
                $text_result .= "
                    <option value='".$row['materia_clave']."'>".$row['materia_nombre'] ." - (" .$row['materia_clave'] . ")</option>
                    ";
            }
            if(mysqli_num_rows($result) >= 1){
                $response = array(
                    'message' => 'right',
                    'content' => $text_result
                );
            }else{
                $response = array(
                    'message' => 'empty'
                );
            }
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);
    }
}
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'LLAMAR_MATERIAS_SEMESTRE_CALIFICACIONES') {
        $semester = filter_var($_POST['semester'], FILTER_SANITIZE_STRING);
        $kind_subjects = filter_var($_POST['kind_subjects'], FILTER_SANITIZE_STRING);
        $response = array();
        $text_content = '';
        require_once 'db_connexion.php';
        try {
            $query = "SELECT materia_clave,
        materia_nombre,
        materia_semestre,
        materia_bachillerato,
        materia_orden,
        tipo_materia
        FROM materias
        WHERE (materia_semestre = '$semester')
        AND (materia_bachillerato = '$kind_subjects') ORDER BY materia_orden";
            $result = $conn->query($query);
            while ($row = mysqli_fetch_assoc($result)) {
                $text_content .= "
                    <option value='".$row['materia_clave']."'>".$row['materia_nombre'] ." - (" .$row['materia_clave'] . ")</option>
                    ";
            }
            if(mysqli_num_rows($result) >= 1){
                $response = array(
                    'message' => 'right',
                    'content' => $text_content
                );
            }else{
                $response = array(
                    'message' => 'empty'
                );
            }
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);
    }
}
//-------------------------------------------------Reporte de registro de calificaciones----------------------------------------------------------------
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'LLAMAR_REGISTROS_PROFESOR') {
        $teacher_id = filter_var($_POST['teacher_id'], FILTER_SANITIZE_STRING);
        $response = array();
        $text_content = '';
        require_once 'db_connexion.php';
        try {
            $query = "SELECT control_registro_calificaciones.profesor_matricula,
        control_registro_calificaciones.fecha,
        control_registro_calificaciones.hora,
        control_registro_calificaciones.materia,
        control_registro_calificaciones.ciclo_escolar,
        control_registro_calificaciones.semestre,
        control_registro_calificaciones.grupo,
        materias.materia_clave,
        materias.materia_nombre
        FROM control_registro_calificaciones
        INNER JOIN materias
        WHERE control_registro_calificaciones.profesor_matricula = '$teacher_id'
        AND
        control_registro_calificaciones.materia = materias.materia_clave";
            $result = $conn->query($query);
            while ($row = mysqli_fetch_assoc($result)) {
                $text_content .= "
                    <tr>
                        <td class=\"td_left_content\">" . $row['materia_nombre'] . "</td>
                        <td class=\"td_center_content\">" . $row['semestre'] . "</td>
                        <td class=\"td_center_content\">" . $row['grupo'] . "</td>
                        <td class=\"td_center_content\">" . $row['ciclo_escolar'] . "</td>
                        <td class=\"td_center_content\">" . $row['fecha'] . "</td>
                        <td class=\"td_center_content\">" . $row['hora'] . "</td>
                    </tr>
                    ";
            }
            if(mysqli_num_rows($result) >= 1){
                $response = array(
                    'message' => 'right',
                    'content' => $text_content
                );
            }else{
                $response = array(
                    'message' => 'empty'
                );
            }
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);
    }
}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'BORRAR_REGISTROS_PROFESOR') {
        $teacher_id  = filter_var($_POST['teacher_id'], FILTER_SANITIZE_STRING);
        $response = array();
        require_once 'db_connexion.php';
        try {
            $query = "DELETE FROM control_registro_calificaciones
            WHERE profesor_matricula = '$teacher_id'";
            $testing = $conn->query($query);
            
            if($conn->affected_rows >= 1){
                $response = array(
                    'message' => 'right'
                );
            }else{
                $response = array(
                    'message' => 'wrong'
                );
            }
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);
    }
}
//-------------------------------------------------cONTROL DE PLATAFORMA Y FECHAS_------------------------------
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'OBTENER_FECHA_ACTUAL') {
        $response = array();
        $text_content = '';
        
        try {
            $text_content = getActualDate();
            if($text_content != ''){
                $response = array(
                    'message' => 'right',
                    'content' => $text_content
                );
            }else{
                $response = array(
                    'message' => 'wrong'
                );
            }
            
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);
    }}

    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'COMPROBAR_EXISTENCIA_FECHA') {
            require_once 'db_connexion.php';
            $date = filter_var($_POST['date'], FILTER_SANITIZE_STRING);
            try {
                $query = "SELECT ciclo_escolar FROM control_fechas WHERE ciclo_escolar = '$date'";
                $rows = mysqli_query($conn, $query);
                $numRows = mysqli_num_rows($rows);
    
                if ($numRows > 0) {
                    $response = array(
                        'message' => 'right'
                    );
                } else {
                    $response = array(
                        'message' => 'empty'
                    );
                }
                $conn->close();
            } catch (Exception $e) {
                $response = array(
                    'message' => $e->getMessage(),
                );
            }
            echo json_encode($response);
    
        }}

        if (isset($_POST['action'])) {
            if (filter_var($_POST['action'], FILTER_SANITIZE_STRING) == 'GUARDAR_CONTROL_FECHA') {
                require_once 'db_connexion.php';
                $semester_odd_start = filter_var($_POST['semester_odd_start'], FILTER_SANITIZE_STRING);
                $semester_odd_end = filter_var($_POST['semester_odd_end'], FILTER_SANITIZE_STRING);
                $semester_pair_start = filter_var($_POST['semester_pair_start'], FILTER_SANITIZE_STRING);
                $semester_pair_end = filter_var($_POST['semester_pair_end'], FILTER_SANITIZE_STRING);
                $school_year = filter_var($_POST['school_year'], FILTER_SANITIZE_STRING);
                try {
                    $query = "INSERT INTO control_fechas 
                    (ciclo_escolar, 
                    semestre_non_inicio,
                    semestre_non_fin,
                    semestre_par_inicio,
                    semestre_par_fin) VALUES
                    ('$school_year',
                    '$semester_odd_start',
                    '$semester_odd_end',
                    '$semester_pair_start',
                    '$semester_pair_end')";
                    $testing = $conn->query($query);
                    if($conn->affected_rows >= 1){
                        $response = array(
                            'message' => 'right'
                        );
                    }else{
                        $response = array(
                            'message' => 'wrong'
                        );
                    }
                    $conn->close();
                } catch (Exception $e) {
                    $response = array(
                        'message' => $e->getMessage(),
                    );
                }
                echo json_encode($response);
            }}

            if (isset($_POST['action'])) {
                if ($_POST['action'] == 'ACTUALIZAR_CONTROL_FECHA') {
                    require_once 'db_connexion.php';
                    $semester_odd_start = filter_var($_POST['semester_odd_start'], FILTER_SANITIZE_STRING);
                    $semester_odd_end = filter_var($_POST['semester_odd_end'], FILTER_SANITIZE_STRING);
                    $semester_pair_start = filter_var($_POST['semester_pair_start'], FILTER_SANITIZE_STRING);
                    $semester_pair_end = filter_var($_POST['semester_pair_end'], FILTER_SANITIZE_STRING);
                    $school_year = filter_var($_POST['school_year'], FILTER_SANITIZE_STRING);
                    try {
                        $query = "UPDATE control_fechas SET 
                        semestre_non_inicio = '$semester_odd_start',
                        semestre_non_fin = '$semester_odd_end',
                        semestre_par_inicio = '$semester_pair_start',
                        semestre_par_fin = '$semester_pair_end'
                        WHERE ciclo_escolar = '$school_year'";
                        $testing = $conn->query($query);
                        if($conn->affected_rows >= 1){
                            $response = array(
                                'message' => 'right'
                            );
                        }else{
                            $response = array(
                                'message' => 'wrong'
                            );
                        }
                        $conn->close();
                    } catch (Exception $e) {
                        $response = array(
                            'message' => $e->getMessage(),
                        );
                    }
                    echo json_encode($response);
                }}
                if (isset($_POST['action'])) {
                    if ($_POST['action'] == 'OBTENER_LISTA_FECHAS') {
                        $response = array();
                        $text_content = array();
                        require_once 'db_connexion.php';
                        try {
                            $query = "SELECT id_control_fechas,
                            ciclo_escolar,
                            semestre_non_inicio,
                            semestre_non_fin,
                            semestre_par_inicio,
                            semestre_par_fin
                            FROM control_fechas ORDER BY ciclo_escolar ASC";
                            $result = mysqli_query($conn, $query);
                            while($row = mysqli_fetch_array($result)){
                                $text_content[] = $row;
                            }
                            if(mysqli_num_rows($result) >= 1){
                                $response = array(
                                'message' => 'right',
                                'content' => $text_content
                                );
                            }else{
                                $response = array(
                                    'message' => 'empty'
                                );
                            }
                            $conn->close();
                        } catch (Exception $e) {
                            $response = array(
                                'message' => $e->getMessage(),
                            );
                        }
                        echo json_encode($response);
                    }}
                    if (isset($_POST['action'])) {
                        if ($_POST['action'] == 'BORRAR_FECHAS') {
                            require_once 'db_connexion.php';
                            $id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
                            try {
                                $stmt = $conn->prepare("DELETE FROM control_fechas WHERE id_control_fechas = ?");
                                $stmt->bind_param("s", $id);
                                $stmt->execute();
                                if($stmt->affected_rows >= 1){
                                    $response = array(
                                        'message' => 'right'
                                    );
                                }else{
                                    $response = array(
                                        'message' => 'wrong'
                                    );
                                }
                                $stmt->close();
                                $conn->close();
                            } catch (Exception $e) {
                                $response = array(
                                    'message' => $e->getMessage(),
                                );
                            }
                            echo json_encode($response);
                        }}
//-------------------------------------------------Estudiante----------------------------------------------------------------
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'COMPROBAR_MATRICULA_ESTUDIANTE') {
        require_once 'db_connexion.php';
        $id = filter_var($_POST['id_student'], FILTER_SANITIZE_STRING);
        try {
            $query = "SELECT alumno_matricula FROM alumnos WHERE alumno_matricula = '$id'";
            $rows = mysqli_query($conn, $query);
            //$numRows = mysqli_num_rows($rows);

            if(mysqli_num_rows($rows) >= 1){
                $response = array(
                'message' => 'right'
                );
            }else{
                $response = array(
                    'message' => 'empty'
                );
            }
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);

    }}

$semesters = array('Primero', 'Segundo', 'Tercero', 'Cuarto', 'Quinto', 'Sexto');
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'GUARDAR_ESTUDIANTE') {
        require_once 'db_connexion.php';

        $new_student_id = filter_var($_POST['new_student_id'], FILTER_SANITIZE_STRING);
        $student_name = filter_var($_POST['student_name'], FILTER_SANITIZE_STRING);
        $student_last_name = filter_var($_POST['student_last_name'], FILTER_SANITIZE_STRING);
        $student_curp = filter_var($_POST['student_curp'], FILTER_SANITIZE_STRING);
        $student_birthplace = filter_var($_POST['student_birthplace'], FILTER_SANITIZE_STRING);
        $student_birthday = filter_var($_POST['student_birthday'], FILTER_SANITIZE_STRING);
        $student_age = filter_var($_POST['student_age'], FILTER_SANITIZE_STRING);
        $student_school_origin = filter_var($_POST['student_school_origin'], FILTER_SANITIZE_STRING);
        $student_telephone = filter_var($_POST['student_telephone'], FILTER_SANITIZE_STRING);
        $student_living_with = filter_var($_POST['student_living_with'], FILTER_SANITIZE_STRING);
        $student_neighborhood = filter_var($_POST['student_neighborhood'], FILTER_SANITIZE_STRING);
        $student_mother_name = filter_var($_POST['student_mother_name'], FILTER_SANITIZE_STRING);
        $student_mother_cellphone = filter_var($_POST['student_mother_cellphone'], FILTER_SANITIZE_STRING);
        $student_mother_telephone = filter_var($_POST['student_mother_telephone'], FILTER_SANITIZE_STRING);
        $student_father_name = filter_var($_POST['student_father_name'], FILTER_SANITIZE_STRING);
        $student_father_cellphone = filter_var($_POST['student_father_cellphone'], FILTER_SANITIZE_STRING);
        $student_father_telephone = filter_var($_POST['student_father_telephone'], FILTER_SANITIZE_STRING);
        $student_illness_question = filter_var($_POST['student_illness_question'], FILTER_SANITIZE_STRING);
        $student_illness_answer = filter_var($_POST['student_illness_answer'], FILTER_SANITIZE_STRING);
        $student_special_atention_question = filter_var($_POST['student_special_atention_question'], FILTER_SANITIZE_STRING);
        $student_special_atention_answer = filter_var($_POST['student_special_atention_answer'], FILTER_SANITIZE_STRING);
        $student_tutor_name = filter_var($_POST['student_tutor_name'], FILTER_SANITIZE_STRING);
        $student_status = filter_var($_POST['student_status'], FILTER_SANITIZE_STRING);
        $student_group = filter_var($_POST['student_group'], FILTER_SANITIZE_STRING);

        $semester_student = filter_var($_POST['semester_student'], FILTER_SANITIZE_STRING);
        $kind_student_subjects = filter_var($_POST['kind_student_subjects'], FILTER_SANITIZE_STRING);
        $school_year_student = filter_var($_POST['school_year_student'], FILTER_SANITIZE_STRING);
        $school_generation_student = filter_var($_POST['school_generation_student'], FILTER_SANITIZE_STRING);


        try {

            $query = "SELECT alumnos.alumno_matricula,
            alumnos.estatus,
            asignar_alumno.alumno_matricula,
            asignar_alumno.alumno_semestre, 
            asignar_alumno.alumno_grupo, 
            asignar_alumno.alumno_bachillerato, 
            asignar_alumno.alumno_ciclo_escolar,
            asignar_alumno.semestre_activo
            FROM alumnos 
            INNER JOIN asignar_alumno
            WHERE alumnos.estatus = 'activo'
            AND alumnos.alumno_matricula = asignar_alumno.alumno_matricula
            AND asignar_alumno.alumno_semestre = '$semester_student'
            AND asignar_alumno.alumno_grupo = '$student_group'
            AND asignar_alumno.alumno_bachillerato = '$kind_student_subjects'
            AND asignar_alumno.alumno_ciclo_escolar = '$school_year_student'
            AND asignar_alumno.semestre_activo = 1";
            $rows = mysqli_query($conn, $query);
            $numRows = mysqli_num_rows($rows);

            if ($numRows >= 25) {
                $response = array(
                    'response' => 'true'
                );
            } else {
                
                $query = "INSERT INTO alumnos (
                    alumno_matricula,
                    nombre,
                    apellidos,
                    curp,
                    lugar_nacimiento,
                    fecha_nacimiento,
                    edad,
                    escuela_procedencia,
                    telefono_casa,
                    vive_con,
                    domicilio,
                    nombre_madre,
                    cel_madre,
                    tel_madre,
                    nombre_padre,
                    cel_padre,
                    tel_padre,
                    tiene_enfermedad,
                    enfermedad,
                    tiene_atencion_especial,
                    atencion_especial,
                    tutor,
                    grupo,
                    estatus) VALUES ('$new_student_id',
                                        '$student_name',
                                        '$student_last_name',
                                        '$student_curp',
                                        '$student_birthplace',
                                        '$student_birthday',
                                        '$student_age',
                                        '$student_school_origin',
                                        '$student_telephone',
                                        '$student_living_with',
                                        '$student_neighborhood',
                                        '$student_mother_name',
                                        '$student_mother_cellphone',
                                        '$student_mother_telephone',
                                        '$student_father_name',
                                        '$student_father_cellphone',
                                        '$student_father_telephone',
                                        '$student_illness_question',
                                        '$student_illness_answer',
                                        '$student_special_atention_question',
                                        '$student_special_atention_answer',
                                        '$student_tutor_name',
                                        '$student_group',
                                        '$student_status'); ";
                    //Tipo de Usuario
                    $passSize = 8;
                    $password = substr(md5(microtime()), 1, $passSize);
                    $userKind = 'student';
                    $query .= "INSERT INTO usuarios (usuario_matricula, usuario_contrasena, usuario_tipo) VALUES ('$new_student_id', '$password', '$userKind'); ";
                    
                    $semester_count = 0;
                    for($semester_position = 0; $semester_position < 6; $semester_position++){
                        if($semester_student === $semesters[$semester_position]){
                            break;
                        }
                        $semester_count++;
                    }
        
                    $year_decrement = 0;
                    for($semester_loop = $semester_count; $semester_loop >= 0; $semester_loop--){
                        $activate_semester = '0';
                        $kind_subjects = '';
                        if($semester_loop === $semester_count){
                            $activate_semester = '1';
                        }else{
                            $activate_semester = '0';
                        }
        
                        if($semester_loop === 5 || $semester_loop === 4){
                            $kind_subjects = $kind_student_subjects;
                        }else{
                            $kind_subjects = 'Tronco Común';
                        }
        
                        $schoolYears = explode(' - ', $school_year_student);
                            $schoolYears[0] = (int) $schoolYears[0] - $year_decrement;
                            $schoolYears[1] = (int) $schoolYears[1] - $year_decrement;
                            $totalYears = $schoolYears[0] . ' - ' . $schoolYears[1];
        
                        $insert_asignation = "INSERT INTO asignar_alumno (
                            alumno_matricula,
                            alumno_semestre,
                            alumno_grupo,
                            alumno_bachillerato,
                            alumno_ciclo_escolar,
                            alumno_generacion,
                            semestre_activo) VALUES ('$new_student_id',
                                                '$semesters[$semester_loop]',
                                                '$student_group',
                                                '$kind_subjects',
                                                '$totalYears',
                                                '$school_generation_student',
                                                '$activate_semester')";
                        $conn->query($insert_asignation);
        
                        $get_id_asignation = "SELECT id_asignar_alumno FROM asignar_alumno WHERE (alumno_matricula ='$new_student_id') AND (alumno_semestre = '$semesters[$semester_loop]')";
                        $result_id_asignation = $conn->query($get_id_asignation);
                        while ($row = mysqli_fetch_assoc($result_id_asignation)) {
                            $id_asignation = $row['id_asignar_alumno'];
                            $get_subjects = "SELECT materia_clave FROM materias WHERE (materia_semestre = '$semesters[$semester_loop]') AND (materia_bachillerato = '$kind_subjects')";
                            $result_subjects = $conn->query($get_subjects);
                            while ($row_2 = mysqli_fetch_assoc($result_subjects)) {
                                $subject_key = $row_2['materia_clave'];
                                $query .= "INSERT INTO boleta (materia_clave, alumno_matricula, id_semestre_alumno, promedio) VALUES ('$subject_key', '$new_student_id', '$id_asignation', 'No Asignado'); ";
                            }
                        }
                        if($semesters[$semester_loop] == 'Primero' || $semesters[$semester_loop] == 'Tercero' || $semesters[$semester_loop] == 'Quinto'){
                            $year_decrement++;
                        }
                    }
                    $testing = $conn->multi_query($query);
                    if($conn->affected_rows >= 1){
                        $response = array(
                            'message' => 'right'
                        );
                    }else{
                        $response = array(
                            'message' => 'wrong'
                        );
                    }
                    $conn->close();
        }
            
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response, JSON_FORCE_OBJECT);
    }}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'ACTUALIZAR_ESTUDIANTE') {

        require_once 'db_connexion.php';

        $new_student_id = filter_var($_POST['new_student_id'], FILTER_SANITIZE_STRING);
        $student_name = filter_var($_POST['student_name'], FILTER_SANITIZE_STRING);
        $student_last_name = filter_var($_POST['student_last_name'], FILTER_SANITIZE_STRING);
        $student_curp = filter_var($_POST['student_curp'], FILTER_SANITIZE_STRING);
        $student_birthplace = filter_var($_POST['student_birthplace'], FILTER_SANITIZE_STRING);
        $student_birthday = filter_var($_POST['student_birthday'], FILTER_SANITIZE_STRING);
        $student_age = filter_var($_POST['student_age'], FILTER_SANITIZE_STRING);
        $student_school_origin = filter_var($_POST['student_school_origin'], FILTER_SANITIZE_STRING);
        $student_telephone = filter_var($_POST['student_telephone'], FILTER_SANITIZE_STRING);
        $student_living_with = filter_var($_POST['student_living_with'], FILTER_SANITIZE_STRING);
        $student_neighborhood = filter_var($_POST['student_neighborhood'], FILTER_SANITIZE_STRING);
        $student_mother_name = filter_var($_POST['student_mother_name'], FILTER_SANITIZE_STRING);
        $student_mother_cellphone = filter_var($_POST['student_mother_cellphone'], FILTER_SANITIZE_STRING);
        $student_mother_telephone = filter_var($_POST['student_mother_telephone'], FILTER_SANITIZE_STRING);
        $student_father_name = filter_var($_POST['student_father_name'], FILTER_SANITIZE_STRING);
        $student_father_cellphone = filter_var($_POST['student_father_cellphone'], FILTER_SANITIZE_STRING);
        $student_father_telephone = filter_var($_POST['student_father_telephone'], FILTER_SANITIZE_STRING);
        $student_illness_question = filter_var($_POST['student_illness_question'], FILTER_SANITIZE_STRING);
        $student_illness_answer = filter_var($_POST['student_illness_answer'], FILTER_SANITIZE_STRING);
        $student_special_atention_question = filter_var($_POST['student_special_atention_question'], FILTER_SANITIZE_STRING);
        $student_special_atention_answer = filter_var($_POST['student_special_atention_answer'], FILTER_SANITIZE_STRING);
        $student_tutor_name = filter_var($_POST['student_tutor_name'], FILTER_SANITIZE_STRING);
        $student_status = filter_var($_POST['student_status'], FILTER_SANITIZE_STRING);
        $student_group = filter_var($_POST['student_group'], FILTER_SANITIZE_STRING);

        $semester_student = filter_var($_POST['semester_student'], FILTER_SANITIZE_STRING);
        $kind_student_subjects = filter_var($_POST['kind_student_subjects'], FILTER_SANITIZE_STRING);
        $school_year_student = filter_var($_POST['school_year_student'], FILTER_SANITIZE_STRING);
        $school_generation_student = filter_var($_POST['school_generation_student'], FILTER_SANITIZE_STRING);

        $regular_student_state = filter_var($_POST['regular_student_state'], FILTER_SANITIZE_STRING);

        if($regular_student_state != 'new_student'){
            try {
                $query = "SELECT alumnos.alumno_matricula,
            alumnos.estatus,
            asignar_alumno.alumno_matricula,
            asignar_alumno.alumno_semestre, 
            asignar_alumno.alumno_grupo, 
            asignar_alumno.alumno_bachillerato, 
            asignar_alumno.alumno_ciclo_escolar,
            asignar_alumno.semestre_activo
            FROM alumnos 
            INNER JOIN asignar_alumno
            WHERE alumnos.estatus = 'activo'
            AND alumnos.alumno_matricula = asignar_alumno.alumno_matricula
            AND asignar_alumno.alumno_semestre = '$semester_student'
            AND asignar_alumno.alumno_grupo = '$student_group'
            AND asignar_alumno.alumno_bachillerato = '$kind_student_subjects'
            AND asignar_alumno.alumno_ciclo_escolar = '$school_year_student'
            AND asignar_alumno.semestre_activo = 1";
            $rows = mysqli_query($conn, $query);
            $numRows = mysqli_num_rows($rows);

            if ($numRows >= 25) {
                $response = array(
                    'response' => 'true'
                );
            } else {
                $query = "UPDATE alumnos SET
             nombre = '$student_name',
             apellidos = '$student_last_name',
             curp = '$student_curp',
             lugar_nacimiento = '$student_birthplace',
             fecha_nacimiento = '$student_birthday',
             edad = '$student_age',
             escuela_procedencia =  '$student_school_origin',
             telefono_casa = '$student_telephone',
             vive_con = '$student_living_with',
             domicilio = '$student_neighborhood',
             nombre_madre = '$student_mother_name',
             cel_madre = '$student_mother_cellphone',
             tel_madre = '$student_mother_telephone',
             nombre_padre = '$student_father_name',
             cel_padre = '$student_father_cellphone',
             tel_padre = '$student_father_telephone',
             tiene_enfermedad = '$student_illness_question',
             enfermedad = '$student_illness_answer',
             tiene_atencion_especial = '$student_special_atention_question',
             atencion_especial = '$student_special_atention_answer',
             tutor = '$student_tutor_name',
             grupo = '$student_group',
             estatus = '$student_status' WHERE alumno_matricula = '$new_student_id'; ";
    
                $delete_academic_historial = "DELETE FROM boleta WHERE alumno_matricula = '$new_student_id'";
                $conn->query($delete_academic_historial);
                $delete_academic_historial_2 = "DELETE FROM asignar_alumno WHERE alumno_matricula = '$new_student_id'";
                $conn->query($delete_academic_historial_2);
    
                $semester_count = 0;
            for($semester_position = 0; $semester_position < 6; $semester_position++){
                if($semester_student === $semesters[$semester_position]){
                    break;
                }
                $semester_count++;
            }

                $year_decrement = 0;
            for($semester_loop = $semester_count; $semester_loop >= 0; $semester_loop--){
                $activate_semester = '0';
                $kind_subjects = '';
                if($semester_loop === $semester_count){
                    $activate_semester = '1';
                }else{
                    $activate_semester = '0';
                }

                if($semester_loop === 5 || $semester_loop === 4){
                    $kind_subjects = $kind_student_subjects;
                }else{
                    $kind_subjects = 'Tronco Común';
                }

                $schoolYears = explode(' - ', $school_year_student);
                    $schoolYears[0] = (int) $schoolYears[0] - $year_decrement;
                    $schoolYears[1] = (int) $schoolYears[1] - $year_decrement;
                    $totalYears = $schoolYears[0] . ' - ' . $schoolYears[1];

                $insert_asignation = "INSERT INTO asignar_alumno (
                    alumno_matricula,
                    alumno_semestre,
                    alumno_grupo,
                    alumno_bachillerato,
                    alumno_ciclo_escolar,
                    alumno_generacion,
                    semestre_activo) VALUES ('$new_student_id',
                                        '$semesters[$semester_loop]',
                                        '$student_group',
                                        '$kind_subjects',
                                        '$totalYears',
                                        '$school_generation_student',
                                        '$activate_semester')";
                $conn->query($insert_asignation);

                $get_id_asignation = "SELECT id_asignar_alumno FROM asignar_alumno WHERE (alumno_matricula ='$new_student_id') AND (alumno_semestre = '$semesters[$semester_loop]')";
                $result_id_asignation = $conn->query($get_id_asignation);
                while ($row = mysqli_fetch_assoc($result_id_asignation)) {
                    $id_asignation = $row['id_asignar_alumno'];
                    $get_subjects = "SELECT materia_clave FROM materias WHERE (materia_semestre = '$semesters[$semester_loop]') AND (materia_bachillerato = '$kind_subjects')";
                    $result_subjects = $conn->query($get_subjects);
                    while ($row_2 = mysqli_fetch_assoc($result_subjects)) {
                        $subject_key = $row_2['materia_clave'];
                        $query .= "INSERT INTO boleta (materia_clave, alumno_matricula, id_semestre_alumno, promedio) VALUES ('$subject_key', '$new_student_id', '$id_asignation', 'No Asignado'); ";
                    }
                }
                if($semesters[$semester_loop] == 'Primero' || $semesters[$semester_loop] == 'Tercero' || $semesters[$semester_loop] == 'Quinto'){
                    $year_decrement++;
                }
            }
                $testing = $conn->multi_query($query);
                if($conn->affected_rows >= 1){
                    $response = array(
                        'message' => 'right'
                    );
                }else{
                    $response = array(
                        'message' => 'wrong'
                    );
                }
                $conn->close();
            }
            } catch (Exception $e) {
                $response = array(
                    'message' => $e->getMessage()
                );
            }
        }else{
            try {
                $query = "UPDATE alumnos SET
             nombre = '$student_name',
             apellidos = '$student_last_name',
             curp = '$student_curp',
             lugar_nacimiento = '$student_birthplace',
             fecha_nacimiento = '$student_birthday',
             edad = '$student_age',
             escuela_procedencia =  '$student_school_origin',
             telefono_casa = '$student_telephone',
             vive_con = '$student_living_with',
             domicilio = '$student_neighborhood',
             nombre_madre = '$student_mother_name',
             cel_madre = '$student_mother_cellphone',
             tel_madre = '$student_mother_telephone',
             nombre_padre = '$student_father_name',
             cel_padre = '$student_father_cellphone',
             tel_padre = '$student_father_telephone',
             tiene_enfermedad = '$student_illness_question',
             enfermedad = '$student_illness_answer',
             tiene_atencion_especial = '$student_special_atention_question',
             atencion_especial = '$student_special_atention_answer',
             tutor = '$student_tutor_name',
             grupo = '$student_group',
             estatus = '$student_status' WHERE alumno_matricula = '$new_student_id'";

            $testing = $conn->query($query);
            if($conn->affected_rows >= 1){
                $response = array(
                    'message' => 'right'
                );
            }else{
                $response = array(
                    'message' => 'wrong'
                );
            }
            $conn->close();
        }catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage()
            );
        }
    } 
        echo json_encode($response);
    }
}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'LLENAR_FORM_ESTUDIANTE') {
        require_once 'db_connexion.php';
        $id = filter_var($_POST['key'], FILTER_SANITIZE_STRING);
        try {

            $sql = "SELECT alumno_matricula,
        nombre,
        apellidos,
        curp,
        lugar_nacimiento,
        fecha_nacimiento,
        edad,
        escuela_procedencia,
        telefono_casa,
        vive_con,
        domicilio,
        nombre_madre,
        cel_madre,
        tel_madre,
        nombre_padre,
        cel_padre,
        tel_padre,
        tiene_enfermedad,
        enfermedad,
        tiene_atencion_especial,
        atencion_especial,
        tutor,
        grupo,
        estatus
        FROM alumnos
        WHERE alumno_matricula = '$id'";
        $result = mysqli_query($conn, $sql);
        $response = mysqli_fetch_array($result);
        $sql_2="SELECT alumno_semestre,
        alumno_bachillerato, 
        alumno_ciclo_escolar, 
        alumno_generacion
        FROM asignar_alumno 
        WHERE
        alumno_matricula = '$id' 
        AND 
        semestre_activo = 1";
        $result_2 = mysqli_query($conn, $sql_2);
        $response_2 = mysqli_fetch_array($result_2);
            
                if(mysqli_num_rows($result) >= 1){
                    $content = array(
                        'message' => 'right',
                        'student' => $response,
                        'school' => $response_2);
                }else{
                    $content = array(
                        'message' => 'empty'
                    );
                }
        $conn->close();                
        } catch (Exception $e) {
            $content = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($content, JSON_UNESCAPED_UNICODE);
    }}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'ELIMINAR_ESTUDIANTE') {
        require_once 'db_connexion.php';
        $id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
        try {
            $stmt = $conn->prepare("DELETE FROM alumnos WHERE alumno_matricula = ?");
            $stmt->bind_param("s", $id);
            $stmt->execute();
            if($stmt->affected_rows >= 1){
                $response = array(
                    'message' => 'right'
                );
            }else{
                $response = array(
                    'message' => 'wrong'
                );
            }
            $stmt->close();
            $delete_academic_historial = "DELETE FROM boleta WHERE alumno_matricula = '$id'";
            $conn->query($delete_academic_historial);
            $delete_academic_historial = "DELETE FROM asignar_alumno WHERE alumno_matricula = '$id'";
            $conn->query($delete_academic_historial);
            $delete_loggin = "DELETE FROM usuarios WHERE usuario_matricula = '$id'";
            $testing = $conn->query($delete_loggin);
            if($conn->affected_rows >= 1){
                $response = array(
                    'message' => 'right'
                );
            }else{
                $response = array(
                    'message' => 'wrong'
                );
            }
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);
    }}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'LLAMADA_LISTA_ESTUDIANTE') {
        $response = array();
        $text_content = '';
        require_once 'db_connexion.php';
        try {

            $query = "SELECT * FROM alumnos";
            $result = $conn->query($query);
            while ($row = mysqli_fetch_assoc($result)) {
                $text_content .= "
            <tr>
            <td class=\"td_center_content\">
                <button data-id=\"" . $row['alumno_matricula'] . "\" type=\"button\" class=\"btn_edit_student pen_icon btn\">
                <i class=\"fas fa-pen-square\"></i>
                </button>
                <button data-id=\"" . $row['alumno_matricula'] . "\" type=\"button\" class=\"btn_delete_student trash_icon btn \">
                <i class=\"fas fa-trash-alt\"></i>
                </button>
            </td>
            <td class=\"td_center_content\">" . $row['alumno_matricula'] . "</td>
            <td class=\"td_left_content\">" . $row['nombre'] . "</td>
            <td class=\"td_left_content\">" . $row['apellidos'] . "</td>
            <td class=\"td_left_content\">" . $row['curp'] . "</td>
            <td class=\"td_left_content\">" . $row['lugar_nacimiento'] . "</td>
            <td class=\"td_center_content\">" . $row['fecha_nacimiento'] . "</td>
            <td class=\"td_center_content\">" . studentAge($row['fecha_nacimiento']) . "</td>
            <td class=\"td_left_content\">" . $row['escuela_procedencia'] . "</td>
            <td class=\"td_left_content\">" . $row['telefono_casa'] . "</td>
            <td class=\"td_left_content\">" . $row['vive_con'] . "</td>
            <td class=\"td_left_content\">" . $row['domicilio'] . "</td>
            <td class=\"td_left_content\">" . $row['nombre_madre'] . "</td>
            <td class=\"td_left_content\">" . $row['cel_madre'] . "</td>
            <td class=\"td_left_content\">" . $row['tel_madre'] . "</td>
            <td class=\"td_left_content\">" . $row['nombre_padre'] . "</td>
            <td class=\"td_left_content\">" . $row['cel_padre'] . "</td>
            <td class=\"td_left_content\">" . $row['tel_padre'] . "</td>
            <td class=\"td_center_content\">" . $row['tiene_enfermedad'] . "</td>
            <td class=\"td_left_content\">" . $row['enfermedad'] . "</td>
            <td class=\"td_center_content\">" . $row['tiene_atencion_especial'] . "</td>
            <td class=\"td_left_content\">" . $row['atencion_especial'] . "</td>
            <td class=\"td_left_content\">" . $row['tutor'] . "</td>
            <td class=\"td_left_content\">" . $row['grupo'] . "</td>
            <td class=\"td_left_content\">" . $row['estatus'] . "</td>
        </tr>
            ";
            }
            if(mysqli_num_rows($result) >= 1){
                $response = array(
                    'message' => 'right',
                    'content' => $text_content
                );
            }else{
                $response = array(
                    'message' => 'empty'
                );
            }
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);
    }
}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'LLAMADA_MATRICULA_ESTUDIANTE') {
        $letters = array('A', 'B', 'C', 'D', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'V', 'W', 'X', 'Y', 'Z');
        $response = array();
        $text_content = '';
        try {
            require_once 'db_connexion.php';
            $query = "SELECT alumno_matricula FROM alumnos ORDER BY alumno_matricula DESC LIMIT 1";
            $result = $conn->query($query);
            $row = mysqli_fetch_assoc($result);
            $splittedRow = str_split($row['alumno_matricula'], 2);
            $splitYear = str_split(getYear(), 2);
            if ($row['alumno_matricula'] == '' || $row['alumno_matricula'] == null || $splitYear[1] != $splittedRow[0]) {
                $counter = '001';
            } else {
                $splittedID = str_split($row['alumno_matricula'], 5);
                $splittedID[1]++;
                $countWordSize = strlen($splittedID[1]);
                if ($countWordSize == 1) {$counter = '00' . $splittedID[1];} else if ($countWordSize == 2) {$counter = '0' . $splittedID[1];} else if ($countWordSize == 3) {$counter = $splittedID[1];}
            }
            $addState = $splitYear[1] . '16';
            $addLetter = $addState . $letters[0];
            $text_content .= $addLetter . $counter;
            if(mysqli_num_rows($result) >= 1){
                $response = array(
                'message' => 'right',
                'content'=> $text_content
                );
            }else{
                $response = array(
                    'message' => 'empty'
                );
            }
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);
    }}

function studentAge($edad)
{
    date_default_timezone_set('America/Mexico_City');
    $date = new DateTime('NOW');
    list($year, $month, $day) = explode("-", $edad);
    $year_difference = $date->format('Y') - $year;
    $month_difference = $date->format('m') - $month;
    $day_difference = $date->format('d') - $day;
    if ($day_difference < 0 || $month_difference < 0) {
        $year_difference--;
    }

    return $year_difference;
}
function getYear()
{
    date_default_timezone_set('America/Mexico_City');
    $date = new DateTime('NOW');
    $year = $date->format('Y');
    return $year;
}
function getActualDate()
{
    date_default_timezone_set('America/Mexico_City');
    $date = new DateTime('NOW');
    $fecha = $date->format('Y-m-d');
    return $fecha;
}
function getActualTime()
{
    date_default_timezone_set('America/Mexico_City');
    $date = new DateTime('NOW');
    $hora = $date->format('H:i:s');
    return $hora;
}
function getActualCicle(){
    date_default_timezone_set('America/Mexico_City');
    $date = new DateTime('NOW');
    $year = $date->format('Y') - 1;
                if ($date->format('m') >= 8) {
                    $year++;
                }
                return $year;
}
//--------------------------------------------------------Carga de Materias------------------------------------------
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'LLAMAR_ESTUDIANTES_SEMESTRE') {
        $semesters = filter_var($_POST['semesters'], FILTER_SANITIZE_STRING);
        $optional_classes = filter_var($_POST['optional_classes'], FILTER_SANITIZE_STRING);
        $response = "";
        $studentsRow = "";
        require_once 'db_connexion.php';
        try {
            $query = "SELECT asignar_alumno.alumno_matricula,
        asignar_alumno.alumno_semestre,
        asignar_alumno.alumno_bachillerato,
        asignar_alumno.semestre_activo,
        alumnos.alumno_matricula,
        alumnos.nombre,
        alumnos.apellidos,
        alumnos.estatus
        FROM asignar_alumno
        INNER JOIN alumnos
        WHERE asignar_alumno.alumno_semestre = '$semesters'
        AND
        asignar_alumno.alumno_bachillerato = '$optional_classes'
        AND
        asignar_alumno.semestre_activo = 1
        AND
        asignar_alumno.alumno_matricula = alumnos.alumno_matricula
        AND 
        alumnos.estatus = 'activo'
        ORDER BY asignar_alumno.alumno_matricula ASC";
            $result = mysqli_query($conn, $query);
            $check_1_switch = 0;
            $check_2_switch = 0;
            $check_final_switch = 0;
            $id_row = 0;
            $data_column = 0;
            while ($row = mysqli_fetch_array($result)) {
                $studentsRow .= "
                        <tr>
                        <td class=\"td_center_content\" ><input id=\"col-" . $id_row . "-" . $data_column++ . "\" type=\"checkbox\" class=\"checkButton\"></td>
                            <td class=\"td_center_content\" id=\"col-" . $id_row . "-" . $data_column++ . "\" class=\"loadSubjectsSpan\" value=\"\"></td>
                            <td class=\"td_center_content\" id=\"col-" . $id_row . "-" . $data_column++ . "\" class=\"loadSubjectsSpan\" value=\"\"></td>
                            <td class=\"td_center_content\" id=\"col-" . $id_row . "-" . $data_column++ . "\" value=\"" . $row['alumno_matricula'] . "\">" . $row['alumno_matricula'] . "</td>
                            <td class=\"td_left_content\" id=\"col-" . $id_row . "-" . $data_column++ . "\" >" . $row['nombre'] . " " . $row['apellidos'] . "</td>
                            <td class=\"td_left_content\" id=\"col-" . $id_row . "-" . $data_column++ . "\" >" . $row['alumno_semestre'] . "</td>
                            <td class=\"td_left_content\" id=\"col-" . $id_row . "-" . $data_column++ . "\" >" . $row['alumno_bachillerato'] . "</td>
                        </tr>
                    ";
                $id_row++;
                $data_column = 0;
            }
            if(mysqli_num_rows($result) >= 1){
                $response = array(
                    'message' => 'right',
                    'students_row' => $studentsRow,
                    'number_rows' => $id_row
                );
            }else{
                $response = array(
                    'message' => 'empty'
                );
            }
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);
    }
}
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'ACTUALIZAR_SEMESTRE') {
        $number_rows = filter_var($_POST['number_rows'], FILTER_SANITIZE_STRING);
        $array = filter_var($_POST['array']);

        require_once 'db_connexion.php';
        try {
            $data = json_decode($array);
            $independentVariable = array();
            $students_updated = 0;
            $response = array();
            for ($i = 0; $i < $number_rows; $i++) {
                $matriculaAlumno = '';
                $semestreActual = '';
                $bachilleratoActual = '';
                $nuevoSemestre = '';
                $nuevoBachillerato = '';

                for ($j = 1; $j < 7; $j++) {
                    if ($j == 1) {$nuevoSemestre = $data[$i][$j];} else if ($j == 2) {$nuevoBachillerato = $data[$i][$j];} else if ($j == 3) {$matriculaAlumno = $data[$i][$j];} else if ($j == 5) {$semestreActual = $data[$i][$j];} else if ($j == 6) {$bachilleratoActual = $data[$i][$j];}
                }

                if ($nuevoSemestre == 'Graduado' && $semestreActual == 'Sexto') {
                    $query = "UPDATE asignar_alumno SET
                    semestre_activo = 0
                    WHERE (alumno_matricula = '$matriculaAlumno')";
                    $testing = mysqli_query($conn, $query);
                    if($conn->affected_rows >= 1){
                        $response = array(
                            'extra_grade' => 'right'
                        );
                    }else{
                        $response = array(
                            'extra_grade' => 'wrong'
                        );
                    }
                }else{
                    $query_get_student_group = "SELECT grupo FROM alumnos WHERE alumno_matricula = '$matriculaAlumno'";
                    $result_student_group = $conn->query($query_get_student_group);
                    $row = mysqli_fetch_assoc($result_student_group);
                    $student_current_group = $row['grupo'];
                    $testing = 0;
                        $query = "SELECT alumnos.alumno_matricula,
                            alumnos.estatus,
                            asignar_alumno.alumno_matricula,
                            asignar_alumno.alumno_semestre, 
                            asignar_alumno.alumno_grupo, 
                            asignar_alumno.alumno_bachillerato, 
                            asignar_alumno.alumno_ciclo_escolar,
                            asignar_alumno.semestre_activo
                            FROM alumnos 
                            INNER JOIN asignar_alumno
                            WHERE alumnos.estatus = 'activo'
                            AND alumnos.alumno_matricula = asignar_alumno.alumno_matricula
                            AND asignar_alumno.alumno_semestre = '$nuevoSemestre'
                            AND asignar_alumno.alumno_grupo = '$student_current_group'
                            AND asignar_alumno.alumno_bachillerato = '$nuevoBachillerato'
                            AND asignar_alumno.semestre_activo = 1";
                            $rows = mysqli_query($conn, $query);
                            $numRows = mysqli_num_rows($rows);
    
                            if ($numRows >= 25) {
                                $response = array(
                                    'response' => 'true',
                                    'counter' => $students_updated,
                                    'group' => $student_current_group,
                                    'semester' => $nuevoSemestre
                                );
                            } else {
                                $current_date = getActualCicle();
                                $activate_semester = '1';
                                $school_year = '';
                                $school_cicle = '';
                                $school_year = $current_date. ' - '.((int)$current_date+1);
                                
                                $query_search_student_cicle = "SELECT alumno_generacion
                                    FROM asignar_alumno
                                        WHERE (alumno_semestre = 'Primero')";
                                $student_cicle_query = $conn->query($query_search_student_cicle);
                                $row_cicle = mysqli_fetch_assoc($student_cicle_query);
                                $school_cicle = $row_cicle['alumno_generacion'];
                                $query_search_asignation = "SELECT id_asignar_alumno,
                                    alumno_matricula, alumno_semestre
                                    FROM asignar_alumno
                                        WHERE (alumno_matricula = '$matriculaAlumno')
                                        AND (alumno_semestre = '$nuevoSemestre')";
                                $result = $conn->query($query_search_asignation);
                                $row = mysqli_fetch_assoc($result);
                                $idAsignarAlumno = $row['id_asignar_alumno'];
                                $query_delete_report = "DELETE FROM boleta
                                    WHERE (alumno_matricula = '$matriculaAlumno')
                                        AND (id_semestre_alumno = '$idAsignarAlumno')";
                                $conn->query($query_delete_report);
                                $query_delete_asignation = "DELETE FROM asignar_alumno
                                        WHERE (alumno_matricula = '$matriculaAlumno')
                                        AND (alumno_semestre = '$nuevoSemestre')";
                                $conn->query($query_delete_asignation);
                                $query_search_student = "SELECT grupo 
                                    FROM alumnos 
                                        WHERE (alumno_matricula = '$matriculaAlumno')";
                                $result_students = $conn->query($query_search_student);
                                $row_2 = mysqli_fetch_assoc($result_students);
                                if($nuevoSemestre == 'Quinto' || $nuevoSemestre == 'Sexto'){
                                    $student_group = 'A'; 
                                }else{
                                    $student_group = $row_2['grupo']; 
                                }
                                $insert_asignation = "INSERT INTO asignar_alumno (
                                    alumno_matricula,
                                    alumno_semestre,
                                    alumno_grupo,
                                    alumno_bachillerato,
                                    alumno_ciclo_escolar,
                                    alumno_generacion,
                                    semestre_activo) VALUES ('$matriculaAlumno',
                                                        '$nuevoSemestre',
                                                        '$student_group',
                                                        '$nuevoBachillerato',
                                                        '$school_year',
                                                        '$school_cicle',
                                                        '$activate_semester')";
                                $testing = $conn->query($insert_asignation);
                                if($conn->affected_rows >= 1){
                                    $response = array(
                                        'extra_grade' => 'wrong',
                                        'response' => 'false',
                                        'message' => 'right',
                                        'respuesta: ' => $independentVariable,
                                        'counter' => $students_updated
                                    );
                                }else{
                                    $response = array(
                                        'extra_grade' => 'wrong',
                                        'response' => 'false',
                                        'message' => 'wrong'
                                    );
                                }
                                $get_id_asignation = "SELECT id_asignar_alumno FROM asignar_alumno WHERE (alumno_matricula ='$matriculaAlumno') AND (alumno_semestre = '$nuevoSemestre')";
                                $result_id_asignation = $conn->query($get_id_asignation);
                                while ($row = mysqli_fetch_assoc($result_id_asignation)) {
                                    $id_asignation = $row['id_asignar_alumno'];
                                    $get_subjects = "SELECT materia_clave FROM materias WHERE (materia_semestre = '$nuevoSemestre') AND (materia_bachillerato = '$nuevoBachillerato')";
                                    $result_subjects = $conn->query($get_subjects);
                                    while ($row_2 = mysqli_fetch_assoc($result_subjects)) {
                                        $subject_key = $row_2['materia_clave'];
                                        $query = "INSERT INTO boleta (materia_clave, alumno_matricula, id_semestre_alumno, promedio) VALUES ('$subject_key', '$matriculaAlumno', '$id_asignation', 'No Asignado'); ";
                                        $conn->query($query);
                                    }
                                }
                            $query = "UPDATE asignar_alumno SET
                                semestre_activo = 0
                                WHERE (alumno_matricula = '$matriculaAlumno')
                                AND (alumno_semestre = '$semestreActual')
                                AND (alumno_bachillerato = '$bachilleratoActual'); ";
                            $conn->query($query);

                            $query = "UPDATE asignar_alumno SET
                                semestre_activo = 1
                                WHERE (alumno_matricula = '$matriculaAlumno')
                                AND (alumno_semestre = '$nuevoSemestre')
                                AND (alumno_bachillerato = '$nuevoBachillerato');";
                            $conn->query($query); 
                            $students_updated++;
                            }          
                }
            }
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);
    }
}
//--------------------------------------------------------Profesor---------------------------------------------------
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'GUARDAR_PROFESOR') {
        require_once 'db_connexion.php';

        $new_teacher_id = filter_var($_POST['new_teacher_id'], FILTER_SANITIZE_STRING);
        $teacher_name = filter_var($_POST['teacher_name'], FILTER_SANITIZE_STRING);
        $teacher_address = filter_var($_POST['teacher_address'], FILTER_SANITIZE_STRING);
        $teacher_cellphone = filter_var($_POST['teacher_cellphone'], FILTER_SANITIZE_STRING);
        $teacher_email = filter_var($_POST['teacher_email'], FILTER_SANITIZE_STRING);
        $teacher_career = filter_var($_POST['teacher_career'], FILTER_SANITIZE_STRING);
        $teacher_birthday = filter_var($_POST['teacher_birthday'], FILTER_SANITIZE_STRING);
        $teacher_age = filter_var($_POST['teacher_age'], FILTER_SANITIZE_STRING);

        try {
            $stmt = $conn->prepare("INSERT INTO maestros (
            profesor_matricula,
            profesor_nombre,
            profesor_direccion,
            profesor_celular,
            profesor_email,
            profesor_carrera,
            profesor_fecha_nacimiento,
            profesor_edad)
            VALUES (?,?,?,?,?,?,?,?)");
            $stmt->bind_param("ssssssss",
                $new_teacher_id,
                $teacher_name,
                $teacher_address,
                $teacher_cellphone,
                $teacher_email,
                $teacher_career,
                $teacher_birthday,
                $teacher_age);
            $stmt->execute();
            if($stmt->affected_rows >= 1){
                $response = array(
                    'message' => 'right'
                );
            }else{
                $response = array(
                    'message' => 'wrong'
                );
            }
            $passSize = 8;
            $password = substr(md5(microtime()), 1, $passSize);
            $userKind = 'teacher';
            $query = "INSERT INTO usuarios (usuario_matricula, usuario_contrasena, usuario_tipo) VALUES ('$new_teacher_id', '$password', '$userKind'); ";
            $conn->query($query);
            $stmt->close();
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);
    }}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'LLAMADA_LISTA_PROFESOR') {
        $response = array();
        $text_content = '';
        require_once 'db_connexion.php';
        try {

            $query = "SELECT * FROM maestros";
            $result = $conn->query($query);
            while ($row = mysqli_fetch_assoc($result)) {
                $text_content .= "
                <tr>
                <td class=\"td_center_content\">
                    <button data-id=\"" . $row['profesor_matricula'] . "\" type=\"button\" class=\"btn_edit_teacher pen_icon btn\">
                    <i class=\"fas fa-pen-square\"></i>
                    </button>
                    <button data-id=\"" . $row['profesor_matricula'] . "\" type=\"button\" class=\"btn_delete_teacher trash_icon btn \">
                    <i class=\"fas fa-trash-alt\"></i>
                    </button>
                </td>
                <td class=\"td_center_content\">" . $row['profesor_matricula'] . "</td>
                <td class=\"td_left_content\">" . $row['profesor_nombre'] . "</td>
                <td class=\"td_left_content\">" . $row['profesor_direccion'] . "</td>
                <td class=\"td_left_content\">" . $row['profesor_celular'] . "</td>
                <td class=\"td_left_content\">" . $row['profesor_email'] . "</td>
                <td class=\"td_left_content\">" . $row['profesor_carrera'] . "</td>
                <td class=\"td_left_content\">" . $row['profesor_fecha_nacimiento'] . "</td>
                <td class=\"td_left_content\">" . studentAge($row['profesor_fecha_nacimiento']) . "</td>
            </tr>
                ";
            }
            if(mysqli_num_rows($result) >= 1){
                $response = array(
                'message' => 'right',
                'content' => $text_content
                );
            }else{
                $response = array(
                    'message' => 'empty'
                );
            }
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);
    }
}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'ACTUALIZAR_PROFESOR') {

        require_once 'db_connexion.php';

        $new_teacher_id = filter_var($_POST['new_teacher_id'], FILTER_SANITIZE_STRING);
        $teacher_address = filter_var($_POST['teacher_address'], FILTER_SANITIZE_STRING);
        $teacher_cellphone = filter_var($_POST['teacher_cellphone'], FILTER_SANITIZE_STRING);
        $teacher_email = filter_var($_POST['teacher_email'], FILTER_SANITIZE_STRING);
        $teacher_career = filter_var($_POST['teacher_career'], FILTER_SANITIZE_STRING);
        $teacher_birthday = filter_var($_POST['teacher_birthday'], FILTER_SANITIZE_STRING);
        $teacher_age = filter_var($_POST['teacher_age'], FILTER_SANITIZE_STRING);
        $response = array();
        try {
            $stmt = $conn->prepare("UPDATE maestros SET
                 profesor_direccion = ?,
                 profesor_celular = ?,
                 profesor_email = ?,
                 profesor_carrera = ?,
                 profesor_fecha_nacimiento = ?,
                 profesor_edad = ?
                 WHERE profesor_matricula = ?");
            $stmt->bind_param("sssssss",
                $teacher_address,
                $teacher_cellphone,
                $teacher_email,
                $teacher_career,
                $teacher_birthday,
                $teacher_age,
                $new_teacher_id);
            $stmt->execute();
            if ($stmt->affected_rows >= 1) {
                $response = array(
                    'response' => 'right',
                );
            } else {
                $respuesta = array(
                    'response' => 'wrong',
                );
            }
            $stmt->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);
    }
}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'LLENAR_FORM_PROFESOR') {
        require_once 'db_connexion.php';
        $id = filter_var($_POST['key'], FILTER_SANITIZE_STRING);
        $response = array();
        try {

            $sql = "SELECT * FROM maestros WHERE profesor_matricula = '$id'";
            $result = mysqli_query($conn, $sql);
            $text_content = mysqli_fetch_array($result);
            if(mysqli_num_rows($result) >= 1){
                $response = array(
                'message' => 'right',
                'content' => $text_content
                );
            }else{
                $response = array(
                    'message' => 'empty'
                );
            }
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);
    }}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'ELIMINAR_PROFESOR') {
        require_once 'db_connexion.php';
        $id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
        try {
            $stmt = $conn->prepare("DELETE FROM maestros WHERE profesor_matricula = ?");
            $stmt->bind_param("s", $id);
            $stmt->execute();
            if($stmt->affected_rows >= 1){
                $response = array(
                    'message' => 'right'
                );
            }else{
                $response = array(
                    'message' => 'wrong'
                );
            }
            $query = "DELETE FROM asignacion_materia WHERE profesor_matricula = '$id'";
            $conn->query($query);
            $query = "DELETE FROM control_registro_calificaciones WHERE profesor_matricula = '$id'";
            $conn->query($query);
            $testing = $query = "DELETE FROM usuarios WHERE usuario_matricula = '$id'";
            $conn->query($query);
            $stmt->close();
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);

    }}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'LLAMADA_PROFESOR_MATRICULA') {

        $teacherNameInput = filter_var($_POST['teacherNameInput'], FILTER_SANITIZE_STRING);

        $response = array();
        $text_content = '';
        try {
            require_once 'db_connexion.php';
            $query = "SELECT profesor_matricula FROM maestros ORDER BY profesor_matricula DESC LIMIT 1";
            $result = $conn->query($query);
            $row = mysqli_fetch_assoc($result);
            $splittedRow = str_split($row['profesor_matricula'], 2);
            $splitYear = str_split(getYear(), 2);
            if ($row['profesor_matricula'] == '' || $row['profesor_matricula'] == null || $splitYear[1] != $splittedRow[0]) {
                $counter = '01';
            } else {
                $splittedID = str_split($row['profesor_matricula'], 2);
                $splittedID[1]++;
                $countWordSize = strlen($splittedID[1]);
                if ($countWordSize == 1) {$counter = '0' . $splittedID[1];} else if ($countWordSize == 2) {$counter = $splittedID[1];}
            }
            $nameAcronym = "";
            $nameArranged = "";
            $nameComplete = explode(" ", $teacherNameInput);
            for ($i = 0; $i < (count($nameComplete) - 2); $i++) {
                $nameAcronym .= substr($nameComplete[$i], 0, 1);
            }
            if (count($nameComplete) >= 2) {
                $nameAcronym = substr($nameComplete[(count($nameComplete) - 2)], 0, 1) . substr($nameComplete[(count($nameComplete) - 1)], 0, 1) . $nameAcronym;
            } else {
                $nameAcronym = '';
            }
            $addState = $splitYear[1];
            $text_content .= $addState . $counter . $nameAcronym;
            if(mysqli_num_rows($result) >= 1){
                $response = array(
                'message' => 'right',
                'content' => $text_content
                );
            }else{
                $response = array(
                    'message' => 'empty'
                );
            }
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);

    }}
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'COMPROBAR_MATRICULA_PROFESOR') {
        require_once 'db_connexion.php';
        $id = filter_var($_POST['teacherID'], FILTER_SANITIZE_STRING);
        try {
            $query = "SELECT profesor_matricula FROM maestros WHERE profesor_matricula = '$id'";
            $rows = mysqli_query($conn, $query);
            $numRows = mysqli_num_rows($rows);

            if ($numRows > 0) {
                $response = array(
                    'message' => 'right',
                    'numero: ' => $numRows,
                );
            } else {
                $response = array(
                    'message' => 'empty',
                    'numero: ' => $numRows,
                );
            }
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);

    }}
//-------------------------------------------------Materias-------------------------------------------
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'GUARDAR_MATERIA_PROFESOR') {
        require_once 'db_connexion.php';

        $search_teacher_id = filter_var($_POST['search_teacher_id'], FILTER_SANITIZE_STRING);
        $search_subject_id = filter_var($_POST['search_subject_id'], FILTER_SANITIZE_STRING);
        $teachers_group = filter_var($_POST['teachers_group'], FILTER_SANITIZE_STRING);
        $schedule = filter_var($_POST['schedule'], FILTER_SANITIZE_STRING);
        try {
            $query = "INSERT INTO asignacion_materia (
                profesor_matricula,
                materia_clave,
                grupo,
                horario) VALUES ('$search_teacher_id',
                                    '$search_subject_id',
                                    '$teachers_group',
                                    '$schedule')";
            $testing = $conn->query($query);
            if($conn->affected_rows >= 1){
                $response = array(
                    'message' => 'right'
                );
            }else{
                $response = array(
                    'message' => 'wrong'
                );
            }
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);
    }}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'LLAMAR_PROFESORES_ID') {
        $response = array();
        $text_content = '';
        require_once 'db_connexion.php';
        try {
            $query = "SELECT profesor_matricula, profesor_nombre FROM maestros";
            $result = $conn->query($query);
            while ($row = mysqli_fetch_assoc($result)) {
                $text_content .= "
                    <option value='" . $row['profesor_matricula'] . "'>" . $row['profesor_matricula'] . " - " . $row['profesor_nombre'] . "</option>
                    ";
            }
            if(mysqli_num_rows($result) >= 1){
                $response = array(
                'message' => 'right',
                'content' => $text_content
                );
            }else{
                $response = array(
                    'message' => 'empty'
                );
            }
                $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);
    }
}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'LLAMAR_MATERIA_ID') {
        $response = array();
        $text_content = '';
        require_once 'db_connexion.php';
        try {
            $query = "SELECT materia_clave, materia_nombre FROM materias";
            $result = $conn->query($query);
            while ($row = mysqli_fetch_assoc($result)) {
                $text_content .= "
                        <option value='" . $row['materia_clave'] . "'>" . $row['materia_clave'] . " - " . $row['materia_nombre'] . "</option>
                        ";
            }
            if(mysqli_num_rows($result) >= 1){
                $response = array(
                'message' => 'right',
                'content' => $text_content
                );
            }else{
                $response = array(
                    'message' => 'empty'
                );
            }
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);
    }
}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'LLAMAR_NOMBRE_PROFESOR') {
        $teacher_id = filter_var($_POST['teacher_id'], FILTER_SANITIZE_STRING);
        $response = array();
        $text_content = '';
        require_once 'db_connexion.php';
        try {
            $sql = "SELECT profesor_nombre FROM maestros WHERE profesor_matricula = '$teacher_id'";
            $result = $conn->query($sql);
            while ($row = mysqli_fetch_assoc($result)) {
                $text_content = $row['profesor_nombre'];
            }
            if(mysqli_num_rows($result) >= 1){
                $response = array(
                'message' => 'right',
                'content' => $text_content
                );
            }else{
                $response = array(
                    'message' => 'empty'
                );
            }
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);
    }
}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'LLAMAR_NOMBRE_MATERIA') {
        $subject_id = filter_var($_POST['subject_id'], FILTER_SANITIZE_STRING);
        $response = array();
        require_once 'db_connexion.php';
        try {
            $query = "SELECT materia_nombre, materia_semestre FROM materias WHERE materia_clave = '$subject_id'";
            $result = $conn->query($query);
            $row = mysqli_fetch_assoc($result);
                if(mysqli_num_rows($result) >= 1){
                    $response = array(
                        'message' => 'right',
                        'subject_name' => $row['materia_nombre'],
                        'subject_semester' => $row['materia_semestre']
                    );
                }else{
                    $response = array(
                        'message' => 'empty'
                    );
                }
                $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);
    }
}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'LLAMAR_MATERIAS_PROFESOR') {
        $teacher_id = filter_var($_POST['teacher_id'], FILTER_SANITIZE_STRING);
        $response = array();
        $text_content = '';
        require_once 'db_connexion.php';
        try {
            $query = "SELECT id_asignacion_materia,
                    materia_clave,
                    grupo,
                    horario
                    FROM asignacion_materia
                    WHERE profesor_matricula = '$teacher_id'";
            $result = $conn->query($query);
            while ($row = mysqli_fetch_assoc($result)) {
                $text_content .= "
                        <tr>
                        <td class=\"td_center_content\">
                            <button data-id=\"" . $row['id_asignacion_materia'] . "\" type=\"button\" class=\"btn_delete_subject trash_icon btn \">
                            <i class=\"fas fa-trash-alt\"></i>
                            </button>
                        </td>
                        <td class=\"td_left_content\">" . $row['materia_clave'] . "</td>
                        <td class=\"td_left_content\">" . getSubject($row['materia_clave'], $conn) . "</td>

                        <td class=\"td_center_content\">" . $row['grupo'] . "</td>
                        <td class=\"td_left_content\">" . $row['horario'] . "</td>
                    </tr>
                        ";
            }
            if(mysqli_num_rows($result) >= 1){
                $response = array(
                'message' => 'right',
                'content' => $text_content
                );
            }else{
                $response = array(
                    'message' => 'empty'
                );
            }
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);
    }
}

function getSubject($subject_id, $conn)
{
    try {
        $query = "SELECT materia_nombre FROM materias WHERE materia_clave = '$subject_id'";
        $result = $conn->query($query);
        $row = mysqli_fetch_assoc($result);
        $response = $row['materia_nombre'];

    } catch (Exception $e) {
        $response = 'error'.$e->getMessage();

    }
    return $response;
}
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'ELIMINAR_MATERIA_PROFESOR') {
        require_once 'db_connexion.php';
        $id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
        try {
            $stmt = $conn->prepare("DELETE FROM asignacion_materia WHERE id_asignacion_materia = ?");
            $stmt->bind_param("s", $id);
            $stmt->execute();
            if ($stmt->affected_rows >= 1) {
                $response = array(
                    'message' => 'right'
                );
            }else{
                $response = array(
                    'message' => 'wrong'
                );
            }
            $stmt->close();
            $conn->close();
            
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);
    }}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'COMPROBAR_CLAVE_MATERIAS_PROFESOR') {
        require_once 'db_connexion.php';
        $subject_key = filter_var($_POST['subject_key'], FILTER_SANITIZE_STRING);
        $teachers_group = filter_var($_POST['teachers_group'], FILTER_SANITIZE_STRING);
        try {
            $query = "SELECT materia_clave FROM asignacion_materia 
            WHERE 
            (materia_clave = '$subject_key')
            AND
            (grupo = '$teachers_group')";
            $rows = mysqli_query($conn, $query);
            $numRows = mysqli_num_rows($rows);

            if ($numRows > 0) {
                $response = array(
                    'message' => 'right'
                );
            } else {
                $response = array(
                    'message' => 'empty'
                );
            }
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);

    }}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'COMPROBAR_CLAVE_MATERIA') {
        require_once 'db_connexion.php';
        $subject_key = filter_var($_POST['subject_key'], FILTER_SANITIZE_STRING);
        $new_subject_position = filter_var($_POST['new_subject_position'], FILTER_SANITIZE_STRING);
        $new_subject_semester = filter_var($_POST['new_subject_semester'], FILTER_SANITIZE_STRING);
        $new_subject_kind_subjects = filter_var($_POST['new_subject_kind_subjects'], FILTER_SANITIZE_STRING);
        try {
            $query = "SELECT materia_clave FROM materias WHERE materia_clave = '$subject_key'";
            $rows = mysqli_query($conn, $query);
            $numRows = mysqli_num_rows($rows);
            $query_order = "SELECT materia_orden, materia_semestre, materia_bachillerato 
            FROM materias 
            WHERE (materia_orden = '$new_subject_position')
            AND (materia_semestre = '$new_subject_semester')
            AND (materia_bachillerato = '$new_subject_kind_subjects')";
            $rows_order = mysqli_query($conn, $query_order);
            $numRows_order = mysqli_num_rows($rows_order);

            if ($numRows > 0) {
                $response = array(
                    'response' => 'true'
                );
            }else if ($numRows_order > 0) {
                $response = array(
                    'response_order' => 'true'
                );
            } else {
                $response = array(
                    'response' => 'false'
                );
            }
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'response' => $e->getMessage(),
            );
        }
        echo json_encode($response);

    }}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'GUARDAR_MATERIA') {
        require_once 'db_connexion.php';

        $new_subject_key = filter_var($_POST['new_subject_key'], FILTER_SANITIZE_STRING);
        $new_subject_name = filter_var($_POST['new_subject_name'], FILTER_SANITIZE_STRING);
        $new_subject_position = filter_var($_POST['new_subject_position'], FILTER_SANITIZE_STRING);
        $new_subject_semester = filter_var($_POST['new_subject_semester'], FILTER_SANITIZE_STRING);
        $new_subject_kind_subjects = filter_var($_POST['new_subject_kind_subjects'], FILTER_SANITIZE_STRING);
        $new_subject_kind = filter_var($_POST['new_subject_kind'], FILTER_SANITIZE_STRING);
        $new_subject_credits = filter_var($_POST['new_subject_credits'], FILTER_SANITIZE_STRING);

        try {
            $stmt = $conn->prepare("INSERT INTO materias (
                materia_clave,
                materia_nombre,
                materia_orden,
                materia_semestre,
                materia_bachillerato,
                tipo_materia,
                creditos)
                VALUES (?,?,?,?,?,?,?)");
            $stmt->bind_param("ssssssi",
                $new_subject_key,
                $new_subject_name,
                $new_subject_position,
                $new_subject_semester,
                $new_subject_kind_subjects,
                $new_subject_kind,
                $new_subject_credits);
            $stmt->execute();
            if ($stmt->affected_rows >= 1) {
                $response = array(
                    'message' => 'right',
                );
            }else{
                $response = array(
                    'message' => 'wrong'
                );
            }
            $stmt->close();
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);
    }}
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'ELIMINAR_MATERIA') {
        require_once 'db_connexion.php';
        $id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
        try {
            $stmt = $conn->prepare("DELETE FROM materias WHERE materia_clave = ?");
            $stmt->bind_param("s", $id);
            $stmt->execute();
            if($stmt->affected_rows >= 1){
                                    $response = array(
                                        'message' => 'right'
                                    );
                                }else{
                                    $response = array(
                                        'message' => 'wrong'
                                    );
                                }
            $query = "DELETE FROM asignacion_materia WHERE materia_clave = '$id'";
            $conn->query($query);
            $testing = $query = "DELETE FROM boleta WHERE materia_clave = '$id'";
            $conn->query($query);
            $stmt->close();
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);

    }}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'LLAMADA_LISTA_MATERIAS') {
        $response = array();
        $text_content = '';
        require_once 'db_connexion.php';
        try {

            $query = "SELECT * FROM materias ORDER BY FIELD (materia_semestre, 'Primero', 'Segundo', 'Tercero', 'Cuarto', 'Quinto', 'Sexto') ASC, materia_bachillerato, materia_orden";
            $result = $conn->query($query);
            while ($row = mysqli_fetch_assoc($result)) {
                $text_content .= "
                    <tr>
                    <td class=\"td_center_content\">
                        <button data-id=\"" . $row['materia_clave'] . "\" type=\"button\" class=\"btn_delete_subject trash_icon btn \">
                        <i class=\"fas fa-trash-alt\"></i>
                        </button>
                    </td>
                    <td class=\"td_left_content\">" . $row['materia_clave'] . "</td>
                    <td class=\"td_left_content\">" . $row['materia_nombre'] . "</td>
                    <td class=\"td_center_content\">" . $row['materia_orden'] . "</td>
                    <td class=\"td_left_content\">" . $row['materia_semestre'] . "</td>
                    <td class=\"td_left_content\">" . $row['materia_bachillerato'] . "</td>
                    <td class=\"td_left_content\">" . $row['tipo_materia'] . "</td>
                    <td class=\"td_left_content\">" . $row['creditos'] . "</td>
                </tr>
                    ";
            }
            if(mysqli_num_rows($result) >= 1){
                $response = array(
                'message' => 'right',
                'content' => $text_content
                );
            }else{
                $response = array(
                    'message' => 'empty'
                );
            }
                $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);
    }
}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'LLAMAR_FECHA') {
        $response = array();
        $text_content = '';
        $student_birthday_date = filter_var($_POST['date'], FILTER_SANITIZE_STRING);
        $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);
        try {
            date_default_timezone_set('America/Mexico_City');
            $date = new DateTime('NOW');

            if ($message == 'CICLO_ESCOLAR') {
                $year = $date->format('Y') - 1;
                if ($date->format('m') >= 8) {
                    $year++;
                }
                for ($firstDate = ($year - 15); $firstDate <= ($year + 15); $firstDate++) {
                    if ($firstDate == $year) {
                        $text_content .= "
                                <option selected=\"true\" value=\"" . $firstDate . " - " . ($firstDate + 1) . "\">" . $firstDate . " - " . ($firstDate + 1) . "</option>
                                ";
                    } else {
                        $text_content .= "
                                <option value=\"" . $firstDate . " - " . ($firstDate + 1) . "\">" . $firstDate . " - " . ($firstDate + 1) . "</option>
                                ";
                    }
                }

            } else if ($message == 'GENERACION') {
                $year = $date->format('Y') - 1;
                if ($date->format('m') >= 8) {
                    $year++;
                }
                for ($firstDate = ($year - 15); $firstDate <= ($year + 15); $firstDate++) {
                    if ($firstDate == $year) {
                        $text_content .= "
                                <option selected=\"true\" value=\"" . $firstDate . " - " . ($firstDate + 3) . "\">" . $firstDate . " - " . ($firstDate + 3) . "</option>
                                ";
                    } else {
                        $text_content .= "
                                <option value=\"" . $firstDate . " - " . ($firstDate + 3) . "\">" . $firstDate . " - " . ($firstDate + 3) . "</option>
                                ";
                    }
                }
            } else if ($message == 'LLAMAR_FECHA') {
                list($year, $month, $day) = explode("-", $student_birthday_date);
                $year_diference = $date->format('Y') - $year;
                $month_diference = $date->format('m') - $month;
                $day_diference = $date->format('d') - $day;
                if ($day_diference < 0 || $month_diference < 0) {
                    $year_diference--;
                }

                $text_content = $year_diference;
            }
                $response = array(
                'message' => 'right',
                'content' => $text_content
                );
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);
    }
}

//---------------------------------------------------BOLETA-------------------------------------------------

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'LLAMAR_ESTUDIANTES_ID') {
        $response = array();
        $text_content = '';
        require_once 'db_connexion.php';
        try {
            $query = "SELECT alumno_matricula, nombre, apellidos, estatus FROM alumnos WHERE estatus != 'baja'";
            $result = $conn->query($query);
            while ($row = mysqli_fetch_assoc($result)) {
                $text_content .= "
                    <option value='" . $row['alumno_matricula'] . "'>" . $row['nombre'] . " " . $row['apellidos'] . "</option>
                    ";
            }
            if(mysqli_num_rows($result) >= 1){
                $response = array(
                'message' => 'right',
                'content' => $text_content
                );
            }else{
                $response = array(
                    'message' => 'empty'
                );
            }
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);
    }
}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'LLAMAR_NOMBRE_ESTUDIANTE') {
        $student_id = filter_var($_POST['student_id'], FILTER_SANITIZE_STRING);
        $response = array();
        $text_content = '';
        require_once 'db_connexion.php';
        try {
            $sql = "SELECT nombre, apellidos, estatus FROM alumnos WHERE alumno_matricula = '$student_id' AND estatus != 'baja'";
            $result = $conn->query($sql);
            $row = mysqli_fetch_assoc($result);
            $text_content = $row['nombre'].' '.$row['apellidos'];
            if(mysqli_num_rows($result) >= 1){
                $response = array(
                'message' => 'right',
                'content' => $text_content
                );
            }else{
                $response = array(
                    'message' => 'empty'
                );
            }
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);
    }
}
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'LLAMAR_CALIFICACIONES_ESTUDIANTE_HISTORIAL') {
        $student_id  = filter_var($_POST['student_id'], FILTER_SANITIZE_STRING);
        $response = array();
        $array_content = array();
        require_once 'db_connexion.php';
        try {
            $sql = "SELECT asignar_alumno.id_asignar_alumno,
            asignar_alumno.alumno_matricula,
            asignar_alumno.alumno_semestre,
            asignar_alumno.alumno_ciclo_escolar,
            boleta.materia_clave,
            boleta.alumno_matricula,
            boleta.id_semestre_alumno,
            boleta.promedio,
            boleta.estado_parcial_1,
            boleta.estado_parcial_2,
            boleta.estado_parcial_final,
            materias.materia_clave,
            materias.materia_nombre,
            materias.materia_orden,
            materias.materia_semestre,
            materias.creditos
            FROM asignar_alumno
            INNER JOIN boleta
            INNER JOIN materias
            WHERE asignar_alumno.alumno_matricula = '$student_id'
            AND asignar_alumno.alumno_matricula = boleta.alumno_matricula 
            AND asignar_alumno.id_asignar_alumno = boleta.id_semestre_alumno
            AND boleta.materia_clave = materias.materia_clave
            AND asignar_alumno.alumno_semestre = materias.materia_semestre
            ORDER BY FIELD (asignar_alumno.alumno_semestre,'Primero', 'Segundo', 'Tercero', 'Cuarto', 'Quinto', 'Sexto') 
            ASC, materias.materia_orden";
            $result = $conn->query($sql);
            while ($row = mysqli_fetch_assoc($result)) {
                $array_content[] = array(
                    'semestre' => $row['alumno_semestre'],
                    'nombre_materia' => $row['materia_nombre'],
                    'ciclo_escolar' => $row['alumno_ciclo_escolar'],
                    'promedio' => $row['promedio'],
                    'estado_parcial_1' => $row['estado_parcial_1'],
                    'estado_parcial_2' => $row['estado_parcial_2'],
                    'estado_parcial_final' => $row['estado_parcial_final'],
                    'orden_materia' => $row['materia_orden'],
                    'creditos' => $row['creditos']
                );
            }
            if(mysqli_num_rows($result) >= 1){
                $response = array(
                'message' => 'right',
                'content' => $array_content
                );
            }else{
                $response = array(
                    'message' => 'empty'
                );
            }
            $conn->close();
        } catch (Exception $e) {
            $response= array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);
    }
}
//---------------------------------------------------ADMINISTACION DE CUENTAS-------------------------------------------------

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'OBTENER_NUEVA_CONTRASENA') {
        $response = array();
        try {
            $passSize = 8;
            $password = substr(md5(microtime()), 1, $passSize);
                $response = array(
                'message' => 'right',
                'content' => $password
                );
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response, JSON_FORCE_OBJECT);
    }
}
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'LLAMADA_LISTA_USUARIOS') {
        $response = "";
        $loop_result = "";
        require_once 'db_connexion.php';
        try {

            $query = "SELECT * FROM usuarios ORDER BY FIELD (usuario_tipo, 'manager', 'teacher', 'student') ASC";
            $result = $conn->query($query);
            while ($row = mysqli_fetch_assoc($result)) {
                if($row['usuario_tipo'] == 'manager'){
                    $loop_result .= "
                    <tr>
                    <td class=\"td_center_content\">
                        <button data-id=\"" . $row['usuario_matricula'] . "\" type=\"button\" class=\"btn_edit_user pen_icon btn\">
                        <i class=\"fas fa-pen-square\"></i>
                        </button>
                        <button data-id=\"" . $row['usuario_matricula'] . "\" type=\"button\" class=\"btn_delete_user trash_icon btn \">
                        <i class=\"fas fa-trash-alt\"></i>
                        </button>
                    </td>
                    <td class=\"td_center_content\">" . $row['usuario_matricula'] . "</td>
                    <td class=\"td_center_content\">" . $row['usuario_contrasena'] . "</td>
                    <td class=\"td_center_content\">" . $row['usuario_tipo'] . "</td>
                </tr>
                    ";
                }else{
                    $loop_result .= "
                    <tr>
                    <td class=\"td_center_content\">
                        <button data-id=\"" . $row['usuario_matricula'] . "\" type=\"button\" class=\"btn_edit_user pen_icon btn\">
                        <i class=\"fas fa-pen-square\"></i>
                        </button>
                    </td>
                    <td class=\"td_center_content\">" . $row['usuario_matricula'] . "</td>
                    <td class=\"td_center_content\">" . $row['usuario_contrasena'] . "</td>
                    <td class=\"td_center_content\">" . $row['usuario_tipo'] . "</td>
                </tr>
                    ";
                }
                
            }
            if(mysqli_num_rows($result) >= 1){
                $response = array(
                    'message' => 'right',
                    'result_list' => $loop_result
                );
            }else{
                $response = array(
                    'message' => 'empty'
                );
            }
                $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response, JSON_FORCE_OBJECT);
    }
}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'COMPROBAR_MATRICULA_USUARIO') {
        require_once 'db_connexion.php';
        $id = filter_var($_POST['id_user'], FILTER_SANITIZE_STRING);
        try {
            $query = "SELECT usuario_matricula FROM usuarios WHERE usuario_matricula = '$id'";
            $rows = mysqli_query($conn, $query);
            $numRows = mysqli_num_rows($rows);

            if ($numRows > 0) {
                $response = array(
                    'response' => 'true'
                );
            } else {
                $response = array(
                    'response' => 'false'
                );
            }
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'response' => $e->getMessage(),
            );
        }
        echo json_encode($response);

    }}
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'GUARDAR_USUARIO_NUEVO') {
            require_once 'db_connexion.php';
    
            $usuario_matricula = filter_var($_POST['user_id'], FILTER_SANITIZE_STRING);
            $usuario_contrasena = filter_var($_POST['user_password'], FILTER_SANITIZE_STRING);
            $usuario_tipo = filter_var($_POST['user_kind'], FILTER_SANITIZE_STRING);
    
            try {
                $query = "INSERT INTO usuarios (
                usuario_matricula,
                usuario_contrasena,
                usuario_tipo) VALUES ('$usuario_matricula',
                                    '$usuario_contrasena',
                                    '$usuario_tipo')";
    
                $testing = $conn->query($query);
                if($conn->affected_rows >= 1){
                    $response = array(
                        'message' => 'right'
                    );
                }else{
                    $response = array(
                        'message' => 'wrong'
                    );
                }
                $conn->close();
            } catch (Exception $e) {
                $response = array(
                    'message' => $e->getMessage(),
                );
            }
            echo json_encode($response, JSON_FORCE_OBJECT);
        }}
        if (isset($_POST['action'])) {
            if ($_POST['action'] == 'LLENAR_FORM_USUARIO') {
                require_once 'db_connexion.php';
                $id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
                try {
                    $query = "SELECT usuario_matricula,
                        usuario_contrasena,
                        usuario_tipo
                        FROM usuarios WHERE usuario_matricula = '$id'";
                    $result = mysqli_query($conn, $query);
                    if(mysqli_num_rows($result) >= 1){
                        $response = array(
                        'message' => 'right',
                        'result_query' => mysqli_fetch_array($result)
                        );
                    }else{
                        $response = array(
                            'message' => 'empty'
                        );
                    }
                    $conn->close();
                } catch (Exception $e) {
                    $response = array(
                        'message' => $e->getMessage(),
                    );
                }
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
            }}
            if (isset($_POST['action'])) {
                if ($_POST['action'] == 'ELIMINAR_USUARIO') {
                    require_once 'db_connexion.php';
                    $id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
                    try {
                        $stmt = $conn->prepare("DELETE FROM usuarios WHERE usuario_matricula = ?");
                        $stmt->bind_param("s", $id);
                        $stmt->execute();
                        if($stmt->affected_rows >= 1){
                                    $response = array(
                                        'message' => 'right'
                                    );
                                }else{
                                    $response = array(
                                        'message' => 'wrong'
                                    );
                                }
                        $stmt->close();
                        $conn->close();
                    } catch (Exception $e) {
                        $response = array(
                            'message' => $e->getMessage(),
                        );
                    }
                    echo json_encode($response);
            
                }}
                if (isset($_POST['action'])) {
                    if ($_POST['action'] == 'ACTUALIZAR_CONTRASENA_USUARIO') {
                        $id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
                        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
                        $kind = filter_var($_POST['kind'], FILTER_SANITIZE_STRING);
                        require_once 'db_connexion.php';
                        try {
                            $query = "UPDATE usuarios SET
                                        usuario_contrasena = '$password'
                                        WHERE (usuario_matricula = '$id')
                                        AND
                                        (usuario_tipo = '$kind')";
                                $testing = $conn->query($query);
                                if($conn->affected_rows >= 1){
                                    $response = array(
                                        'message' => 'right'
                                    );
                                }else{
                                    $response = array(
                                        'message' => 'wrong'
                                    );
                                }
                                $conn->close();
                        } catch (Exception $e) {
                            $response = array(
                                'message' => $e->getMessage(),
                            );
                        }
                        echo json_encode($response);
                    }
                }

//-----------------------------------------------------------------VENTANA DE REPORTE DE ERRORES--------------------------------------

                if (isset($_POST['action'])) {
                    if ($_POST['action'] == 'LLAMADA_LISTA_CORREOS') {
                        $response = array();
                        $loop_result = "";
                        $checked = "";
                        require_once 'db_connexion.php';
                        try {
                
                            $query = "SELECT * FROM correo";
                            $result = $conn->query($query);
                            while ($row = mysqli_fetch_assoc($result)) {
                                if($row['visto'] == 0){
                                    $checked = 'style="background-color: rgb(255,255,255)"';
                                }else{
                                    $checked = 'style="background-color: rgb(236, 236, 236)"';
                                }
                                $loop_result .= "
                            <tr>
                            <td ".$checked.">
                                <button data-id=\"" . $row['id_correo'] . "\" type=\"button\" class=\"btn_edit_mail pen_icon btn \">
                                <i class=\"fas fa-pen-square\"></i>
                                </button>
                                <button data-id=\"" . $row['id_correo'] . "\" type=\"button\" class=\"btn_delete_mail trash_icon btn \">
                                <i class=\"fas fa-trash-alt\"></i>
                                </button>
                            </td>
                            <td ".$checked.">" . $row['fecha'] . "</td>
                            <td ".$checked.">" . $row['hora'] . "</td>
                            <td ".$checked.">" . $row['nombre'] . "</td>
                            <td ".$checked.">" . $row['asunto'] . "</td>
                            <td ".$checked.">" . $row['correo'] . "</td>
                        </tr>
                            ";
                            }
                            if(mysqli_num_rows($result) >= 1){
                                $response = array(
                                'message' => 'right',
                                'result_list' => $loop_result
                                );
                            }else{
                                $response = array(
                                    'message' => 'empty'
                                );
                            }
                            $conn->close();
                        } catch (Exception $e) {
                            $response = array(
                                'message' => $e->getMessage(),
                            );
                        }
                        echo json_encode($response);
                    }
                }
                if (isset($_POST['action'])) {
                    if ($_POST['action'] == 'MANDAR_CORREO') {
                        require_once 'db_connexion.php';
                        $problem_name = filter_var($_POST['problem_name'], FILTER_SANITIZE_STRING);
                        $problem_mail = filter_var($_POST['problem_mail'], FILTER_SANITIZE_STRING);
                        $problem_subject = filter_var($_POST['problem_subject'], FILTER_SANITIZE_STRING);
                        $problem_answer = filter_var($_POST['problem_answer'], FILTER_SANITIZE_STRING);
                        $mail_school = filter_var($_POST['mail_school'], FILTER_SANITIZE_STRING);
                        $subjectResponse = filter_var($_POST['subjectResponse'], FILTER_SANITIZE_STRING);
                        $response="";
                        try {
                            $headers  = "MIME-Version: 1.0\r\n";
                            $headers .= "Content-type: text/plain; charset=charset=UTF-8\r\n";
                            $headers .= "From: $mail_school\r\n";
                            $message = "SOLICITUD: $problem_subject\r\n"
                            ."NOMBRE: $problem_name\r\n"
                            ."RECOMENDACIÓN: $problem_answer\r\n";
                            $mail = @mail($problem_mail, $subjectResponse, $message, $headers);
                            if($mail){
                                $response = array(
                                    'message' => 'right',
                                    'recibe' => $problem_mail,
                                    'asunto' => $subjectResponse,
                                    'mensaje' => $message,
                                    'headers' => $headers
                                );
                            }else{
                                $response = array(
                                    'message' => 'wrong'
                                );
                            }
                        } catch (Exception $e) {
                            $response = array(
                                'message' => $e->getMessage(),
                            );
                        }
                        echo json_encode($response);
                    }
                }
                if (isset($_POST['action'])) {
                    if ($_POST['action'] == 'LLENAR_FORM_CORREO') {
                        require_once 'db_connexion.php';
                        $id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
                        $response = array();
                        $test_content = '';
                        try {
                
                            $sql = "SELECT * FROM correo WHERE id_correo = '$id'";
                            $result = mysqli_query($conn, $sql);
                            $sql = "UPDATE correo SET visto = 1 WHERE id_correo = '$id'";
                            $conn->query($sql);
                            if(mysqli_num_rows($result) >= 1){
                                $response = array(
                                'message' => 'right',
                                'content' => mysqli_fetch_array($result)
                                );
                            }else{
                                $response = array(
                                    'message' => 'empty'
                                );
                            }
                            $conn->close();
                        } catch (Exception $e) {
                            $response = array(
                                'message' => $e->getMessage(),
                            );
                        }
                        echo json_encode($response);
                    }}
                    if (isset($_POST['action'])) {
                        if ($_POST['action'] == 'ELIMINAR_CORREO') {
                            require_once 'db_connexion.php';
                            $id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
                            try {
                                $stmt = $conn->prepare("DELETE FROM correo WHERE id_correo = ?");
                                $stmt->bind_param("s", $id);
                                $stmt->execute();
                                if($stmt->affected_rows >= 1){
                                    $response = array(
                                        'message' => 'right'
                                    );
                                }else{
                                    $response = array(
                                        'message' => 'wrong'
                                    );
                                }
                                $stmt->close();
                            } catch (Exception $e) {
                                $response = array(
                                    'message' => $e->getMessage(),
                                );
                            }
                            echo json_encode($response);
                    
                        }}

//-----------------------------------------------------------------CONCENTRADO DE CALIFICACIONES PROFESOR--------------------------------------
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'VERIFICAR_FECHAS_TEMPORADA_CALIFICACION_PROFESORES') {
        require_once 'db_connexion.php';
        try {

            $query="SELECT apertura_calificaciones, clausura_calificaciones, periodo_actividad FROM temporada_calificaciones WHERE id_temporada_calificaciones = 0";
            $result = $conn->query($query);
            $row = mysqli_fetch_assoc($result);
            $startDate = strtotime($row['apertura_calificaciones']);
            $endDate = strtotime($row['clausura_calificaciones']);
            $activity = $row['periodo_actividad'];
            $actualDate = strtotime(getActualDate());
            $dateStatus = '';
            if(($actualDate >= $startDate) && ($actualDate <= $endDate)) {
               $dateStatus = 'true';
            } else {
                $dateStatus = 'false';
            }
            if(mysqli_num_rows($result) >= 1){
                $response = array(
                'message' => 'right',
                'date_status' => $dateStatus,
                'activity' => $activity
                );
            }else{
                $response = array(
                    'message' => 'empty'
                );
            }
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage()
            );
        }
        echo json_encode($response);

    }}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'LLAMAR_MATERIAS_PROFESOR_CALIFICACIONES') {
        $teacher_id = filter_var($_POST['teacher_id'], FILTER_SANITIZE_STRING);
        $semester = filter_var($_POST['semester'], FILTER_SANITIZE_STRING);
        $kind_subjects = filter_var($_POST['kind_subjects'], FILTER_SANITIZE_STRING);
        $student_group = filter_var($_POST['student_group'], FILTER_SANITIZE_STRING);
        $response = array();
        $text_content = '';
        require_once 'db_connexion.php';
        try {
            $query = "SELECT asignacion_materia.profesor_matricula,
        asignacion_materia.materia_clave,
        asignacion_materia.grupo,
        materias.materia_clave,
        materias.materia_nombre,
        materias.materia_semestre,
        materias.materia_bachillerato,
        materias.tipo_materia
        FROM asignacion_materia
        INNER JOIN materias
        WHERE asignacion_materia.profesor_matricula = '$teacher_id'
        AND asignacion_materia.grupo = '$student_group'
        AND asignacion_materia.materia_clave = materias.materia_clave
        AND materias.materia_semestre = '$semester'
        AND materias.materia_bachillerato = '$kind_subjects'";
            $result = $conn->query($query);
            while ($row = mysqli_fetch_assoc($result)) {
                $text_content .= "
                    <option value='".$row['materia_clave']."'>".$row['materia_nombre'] ." - (" .$row['materia_clave'] . ")</option>
                    ";
            }
            if(mysqli_num_rows($result) >= 1){
                $response = array(
                'message' => 'right',
                'content' => $text_content
                );
            }else{
                $response = array(
                    'message' => 'empty'
                );
            }
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);
    }
}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'LLAMAR_PROFESOR_ESTUDIANTES') {
        $group = filter_var($_POST['group'], FILTER_SANITIZE_STRING);
        $semesters = filter_var($_POST['semesters'], FILTER_SANITIZE_STRING);
        $optional_classes = filter_var($_POST['optional_classes'], FILTER_SANITIZE_STRING);
        $subject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
        $response = array();
        $studentsRow = "";
        require_once 'db_connexion.php';
        try {
            $query = "SELECT asignar_alumno.alumno_grupo,
        asignar_alumno.semestre_activo,
        asignar_alumno.alumno_semestre,
        asignar_alumno.alumno_bachillerato,
        asignar_alumno.alumno_matricula,
        asignar_alumno.id_asignar_alumno,
        boleta.materia_clave,
        boleta.alumno_matricula,
        boleta.id_semestre_alumno,
        boleta.parcial_1,
        boleta.faltas_1,
        boleta.parcial_2,
        boleta.faltas_2,
        boleta.final,
        boleta.faltas_final,
        boleta.promedio,
        boleta.estado_parcial_1,
        boleta.estado_parcial_2,
        boleta.estado_parcial_final,
        boleta.regularizacion_1,
        boleta.regu_1_fecha,
        boleta.regu_1_estado,
        boleta.regularizacion_2,
        boleta.regu_2_fecha,
        boleta.regu_2_estado,
        alumnos.alumno_matricula,
        alumnos.nombre,
        alumnos.apellidos,
        alumnos.estatus,
        materias.materia_clave,
        materias.tipo_materia
        FROM asignar_alumno
        INNER JOIN boleta
        INNER JOIN alumnos
        INNER JOIN materias
        WHERE asignar_alumno.alumno_grupo = '$group'
        AND
        asignar_alumno.semestre_activo = 1
        AND
        asignar_alumno.alumno_semestre = '$semesters'
        AND
        asignar_alumno.alumno_bachillerato = '$optional_classes'
        AND
        asignar_alumno.alumno_matricula = boleta.alumno_matricula
        AND
        boleta.materia_clave = '$subject'
        AND
        asignar_alumno.id_asignar_alumno = boleta.id_semestre_alumno
        AND
        asignar_alumno.alumno_matricula = alumnos.alumno_matricula
        AND
        materias.materia_clave = '$subject'
        AND
        alumnos.estatus = 'activo'";
            $result = mysqli_query($conn, $query);
            $id_row = 0;
            $check_1_switch = 0;
            $check_2_switch = 0;
            $check_final_switch = 0;
            $data_column = 0;
            $kind_subject = '';
            while ($row = mysqli_fetch_array($result)) {
                $switcher_parcial_1_state = '';
                $switcher_parcial_2_state = '';
                $switcher_parcial_final_state = '';
                $switcher_parcial_1_class = '';
                $switcher_parcial_2_class = '';
                $switcher_parcial_final_class = '';
                $reg_1_status = '';
                $reg_2_status = '';
                if ($row['estado_parcial_1'] == 0) {
                    $switcher_parcial_1_state = '';
                    $switcher_parcial_1_class = '';
                } else {
                    $switcher_parcial_1_state = 'disabled';
                    $switcher_parcial_1_class = 'blocked_input';
                }
                if ($row['estado_parcial_2'] == 0) {
                    $switcher_parcial_2_state = '';
                    $switcher_parcial_2_class = '';
                } else {
                    $switcher_parcial_2_state = 'disabled ';
                    $switcher_parcial_2_class = 'blocked_input';
                }
                if ($row['estado_parcial_final'] == 0) {
                    $switcher_parcial_final_state = '';
                    $switcher_parcial_final_class = '';
                } else {
                    $switcher_parcial_final_state = 'disabled ';
                    $switcher_parcial_final_class = 'blocked_input';
                }
                if ($row['regu_1_estado'] == 0) {
                    $reg_1_status = 'status_cog_red';
                } else {
                    $reg_1_status = 'status_cog_green';
                }
                if ($row['regu_2_estado'] == 0) {
                    $reg_2_status = 'status_cog_red';
                } else {
                    $reg_2_status = 'status_cog_green';
                }
                if ($id_row == 0) {
                    $check_1_switch = $row['estado_parcial_1'];
                    $check_2_switch = $row['estado_parcial_2'];
                    $check_final_switch = $row['estado_parcial_final'];
                }
                $studentsRow .= "
                        <tr>

                            <td class=\"td_center_content\">" . $row['alumno_matricula'] . "<input id=\"col-" . $id_row . "-" . $data_column++ . "\" value=\"" . $row['alumno_matricula'] . "\" class=\"invissible\"></td>
                            <td class=\"td_left_content\">" . $row['nombre'] . " " . $row['apellidos'] . "<input id=\"col-" . $id_row . "-" . $data_column++ . "\" value=\"" . $row['materia_clave'] . "\" class=\"invissible\"></td>
                            <td class=\"celda_izquierda\"><input id=\"col-" . $id_row . "-" . $data_column++ . "\" type=\"number\" min=\"0\" max=\"10\" class=\"tabla_entrada_concentrado " . $switcher_parcial_1_class . "\" " . $switcher_parcial_1_state . " value=\"" . $row['parcial_1'] . "\" ></td>
                            <td class=\"celda_derecha\"><input id=\"col-" . $id_row . "-" . $data_column++ . "\" type=\"number\" min=\"0\" max=\"10\" class=\"tabla_entrada_concentrado " . $switcher_parcial_1_class . "\" " . $switcher_parcial_1_state . " value=\"" . $row['faltas_1'] . "\"></td>
                            <td class=\"celda_izquierda\"><input id=\"col-" . $id_row . "-" . $data_column++ . "\" type=\"number\" min=\"0\" max=\"10\" class=\"tabla_entrada_concentrado " . $switcher_parcial_2_class . "\" class=\"tabla_entrada_concentrado\"  " . $switcher_parcial_2_state . " value=\"" . $row['parcial_2'] . "\"></td>
                            <td class=\"celda_derecha\"><input id=\"col-" . $id_row . "-" . $data_column++ . "\" type=\"number\" min=\"0\" max=\"10\" class=\"tabla_entrada_concentrado " . $switcher_parcial_2_class . "\" " . $switcher_parcial_2_state . " value=\"" . $row['faltas_2'] . "\"></td>
                            <td class=\"celda_izquierda\"><input id=\"col-" . $id_row . "-" . $data_column++ . "\" type=\"number\" min=\"0\" max=\"10\" class=\"tabla_entrada_concentrado " . $switcher_parcial_final_class . "\" " . $switcher_parcial_final_state . " value=\"" . $row['final'] . "\"></td>
                            <td class=\"celda_derecha\"><input id=\"col-" . $id_row . "-" . $data_column++ . "\" type=\"number\" min=\"0\" max=\"10\" class=\"tabla_entrada_concentrado " . $switcher_parcial_final_class . "\" " . $switcher_parcial_final_state . " value=\"" . $row['faltas_final'] . "\"></td>
                            <td class=\"td_center_content\"><span id=\"col-" . $id_row . "-" . $data_column++ . "\" class=\"concentrated_ratings_average\">" . $row['promedio'] . "</span></td>
                            <td class=\"celda_izquierda\"><button id=\"col-" . $id_row . "-" . $data_column++ . "\" data-id=\"" . $id_row . "-" . $data_column++ . 'x'. $id_row . "-" . $data_column++ . 'x'. $id_row . "-" . $data_column++ ."\" type=\"button\" class=\"".$reg_1_status." cog_icon btn\"><i class=\"fas fa-cog\"></i></button><input id=\"col-" . $id_row . "-" . ($data_column-3) . "\" min=\"0\" class=\"tabla_entrada_concentrado blocked_input extra_option_input\" disabled value=\"" . $row['regularizacion_1'] . "\"><input type=\"hidden\" id=\"col-" . $id_row . "-" . ($data_column-2) . "\" disabled value=\"" . $row['regu_1_fecha'] . "\"><input type=\"hidden\"  id=\"col-" . $id_row . "-" . ($data_column-1) . "\" disabled value=\"" . $row['regu_1_estado'] . "\"></td>
                            <td class=\"celda_derecha\"><button id=\"col-" . $id_row . "-" . $data_column++ . "\" data-id=\"" . $id_row . "-" . $data_column++ . 'x'. $id_row . "-" . $data_column++ . 'x'. $id_row . "-" . $data_column++ ."\" type=\"button\" class=\"".$reg_2_status." cog_icon btn\"><i class=\"fas fa-cog\"></i></button><input id=\"col-" . $id_row . "-" . ($data_column-3) . "\" min=\"0\" class=\"tabla_entrada_concentrado blocked_input extra_option_input\" disabled value=\"" . $row['regularizacion_2'] . "\"><input type=\"hidden\" id=\"col-" . $id_row . "-" . ($data_column-2) . "\" disabled value=\"" . $row['regu_2_fecha'] . "\"><input type=\"hidden\" id=\"col-" . $id_row . "-" . ($data_column-1) . "\" disabled value=\"" . $row['regu_2_estado'] . "\"></td>
                        </tr>
                    ";
                $id_row++;
                $data_column = 0;
                $kind_subject = $row['tipo_materia'];
            }
            if(mysqli_num_rows($result) >= 1){
                $response = array(
                    'message' => 'right',
                    'students_row' => $studentsRow,
                    'number_rows' => $id_row
                );
            }else{
                $response = array(
                    'message' => 'empty'
                );
            }
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);
    }
}
///////////////////////////////////////////Revisar
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'GUARDAR_CALIFICACIONES_PROFESOR') {
        $response = array();
        $array = filter_var($_POST['array']);
        $numberRows = filter_var($_POST['number_rows'], FILTER_SANITIZE_STRING);
        $teacher_id = filter_var($_POST['search_teacher_id'], FILTER_SANITIZE_STRING);
        $group = filter_var($_POST['group'], FILTER_SANITIZE_STRING);
        $school_cycle = filter_var($_POST['school_cycle'], FILTER_SANITIZE_STRING);
        $semesters = filter_var($_POST['semesters'], FILTER_SANITIZE_STRING);
        $subject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
        $date = getActualDate();
        $time = getActualTime();
        $testing = 0;
        require_once 'db_connexion.php';
        try {
            $data = json_decode($array);

            for ($i = 0; $i < $numberRows; $i++) {
                $matriculaAlumno = 0;
                $materiaClave = 0;
                $parcial1 = 0;
                $faltas1 = 0;
                $parcial2 = 0;
                $faltas2 = 0;
                $parcialFinal = 0;
                $faltasFinal = 0;
                $promedio = 0;
                $estado_parcial_1 = 0;
                $estado_parcial_2 = 0;
                $estado_parcial_final = 0;
                $regu_1 = 0;
                $regu_1_fecha = '';
                $regu_1_estado = 0;
                $regu_2 = 0;
                $regu_2_fecha = '';
                $regu_2_estado = 0;

                for ($j = 0; $j < 20; $j++) {

                    if ($j == 0) {$matriculaAlumno = $data[$i][$j];} 
                    else if ($j == 1) {$materiaClave = $data[$i][$j];} 
                    else if ($j == 2) {$parcial1 = $data[$i][$j];} 
                    else if ($j == 3) {$faltas1 = $data[$i][$j];} 
                    else if ($j == 4) {$parcial2 = $data[$i][$j];} 
                    else if ($j == 5) {$faltas2 = $data[$i][$j];} 
                    else if ($j == 6) {$parcialFinal = $data[$i][$j];} 
                    else if ($j == 7) {$faltasFinal = $data[$i][$j];} 
                    else if ($j == 8) {$promedio = $data[$i][$j];} 
                    else if ($j == 10) {$regu_1 = $data[$i][$j];} 
                    else if ($j == 11) {$regu_1_fecha = $data[$i][$j];} 
                    else if ($j == 12) {$regu_1_estado = $data[$i][$j];}
                    else if ($j == 14) {$regu_2 = $data[$i][$j];} 
                    else if ($j == 15) {$regu_2_fecha = $data[$i][$j];} 
                    else if ($j == 16) {$regu_2_estado = $data[$i][$j];}
                    else if ($j == 17) {
                        if($parcial1 != '' ){
                        $estado_parcial_1 = 1;
                        }
                    } 
                    else if ($j == 18) {
                        if($parcial2 != ''){
                        $estado_parcial_2 = 1;
                        }
                    } 
                    else if ($j == 19) {
                        if($parcialFinal != ''){
                        $estado_parcial_final = 1;
                        }
                    }
                }

                $query = "UPDATE boleta SET
                    profesor_matricula = '$teacher_id',
                    parcial_1 = '$parcial1',
                    faltas_1 = '$faltas1',
                    parcial_2 = '$parcial2',
                    faltas_2 = '$faltas2',
                    final = '$parcialFinal',
                    faltas_final = '$faltasFinal',
                    promedio = '$promedio',
                    estado_parcial_1 = '$estado_parcial_1',
                    estado_parcial_2 = '$estado_parcial_2',
                    estado_parcial_final = '$estado_parcial_final',
                    regularizacion_1 = '$regu_1',
                    regu_1_fecha = '$regu_1_fecha',
                    regu_1_estado = '$regu_1_estado',
                    regularizacion_2 = '$regu_2',
                    regu_2_fecha = '$regu_2_fecha',
                    regu_2_estado = '$regu_2_estado'
                    WHERE (alumno_matricula = '$matriculaAlumno') AND (materia_clave = '$materiaClave')";
                $testing = mysqli_query($conn, $query);

            }
            $query = "INSERT INTO control_registro_calificaciones (profesor_matricula, fecha, hora, materia, ciclo_escolar, semestre, grupo)VALUES('$teacher_id', '$date','$time','$subject','$school_cycle','$semesters','$group')";
            mysqli_query($conn, $query);
            if($conn->affected_rows >= 1){
                $response = array(
                    'message' => 'right'
                );
            }else{
                $response = array(
                    'message' => 'wrong'
                );
            }
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response);
    }
}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'NOMBRE_COMPLETO_PROFESOR') {
        require_once 'db_connexion.php';
        $id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
        try {
            $query = "SELECT profesor_nombre
                FROM maestros WHERE profesor_matricula = '$id'";
            $result = mysqli_query($conn, $query);
            if(mysqli_num_rows($result) >= 1){
                $response = array(
                'message' => 'right',
                'result_query' => mysqli_fetch_array($result)
                );
            }else{
                $response = array(
                    'message' => 'empty'
                );
            }
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }}

//----------------------------------------------------Estudiantes Calificaciones---------------------------------
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'NOMBRE_COMPLETO_ESTUDIANTE') {
        require_once 'db_connexion.php';
        $id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
        try {
            $query = "SELECT nombre, apellidos
                FROM alumnos WHERE alumno_matricula = '$id'";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_array($result);
            $name = $row['nombre'].' '.$row['apellidos'];
            if(mysqli_num_rows($result) >= 1){
                $response = array(
                'message' => 'right',
                'result_query' => $name
                );
            }else{
                $response = array(
                    'message' => 'empty'
                );
            }
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'message' => $e->getMessage(),
            );
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }}

    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'NOMBRE_CALIFICACIONES_ESTUDIANTE') {
            $data = array();
            require_once 'db_connexion.php';
            $id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
            try {
                $query = "SELECT alumnos.alumno_matricula, 
                alumnos.estatus,
                asignar_alumno.id_asignar_alumno,
                asignar_alumno.alumno_matricula,
                asignar_alumno.alumno_semestre,
                asignar_alumno.alumno_grupo,
                asignar_alumno.alumno_bachillerato,
                asignar_alumno.alumno_ciclo_escolar,
                asignar_alumno.semestre_activo,
                boleta.materia_clave,
                boleta.alumno_matricula,
                boleta.id_semestre_alumno,
                boleta.parcial_1,
                boleta.parcial_2,
                boleta.final,
                boleta.promedio,
                boleta.estado_parcial_1,
                boleta.estado_parcial_2,
                boleta.estado_parcial_final,
                boleta.regularizacion_1,
                boleta.regu_1_estado,
                boleta.regularizacion_2,
                boleta.regu_2_estado,
                materias.materia_clave,
                materias.materia_nombre,
                materias.materia_orden,
                materias.tipo_materia
                FROM alumnos 
                INNER JOIN asignar_alumno
                INNER JOIN boleta
                INNER JOIN materias
                WHERE alumnos.alumno_matricula = '$id'
                AND alumnos.estatus = 'activo'
                AND alumnos.alumno_matricula = asignar_alumno.alumno_matricula
                AND asignar_alumno.semestre_activo = 1
                AND asignar_alumno.id_asignar_alumno = boleta.id_semestre_alumno
                AND asignar_alumno.alumno_matricula = boleta.alumno_matricula
                AND boleta.materia_clave = materias.materia_clave ORDER BY materias.materia_orden ASC";
                $result = mysqli_query($conn, $query);
                while ($row = mysqli_fetch_array($result)) {
                    $data[] = $row;
                }
                if(mysqli_num_rows($result) >= 1){
                    $response = array(
                    'message' => 'right',
                    'result_query' => $data
                    );
                }else{
                    $response = array(
                        'message' => 'empty'
                    );
                }
                $conn->close();
            } catch (Exception $e) {
                $response = array(
                    'message' => $e->getMessage(),
                );
            }
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        }}