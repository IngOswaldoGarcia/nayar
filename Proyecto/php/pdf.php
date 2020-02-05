<?php
require('../fpdf181/fpdf.php');


$report_card_kind = filter_var($_POST['report_card_kind'], FILTER_SANITIZE_STRING);

    if($report_card_kind === 'per_student'){

        $input_search_student_id_card = filter_var($_POST['input_search_student_id_card'], FILTER_SANITIZE_STRING);
        $semester_student_card = filter_var($_POST['semester_student_card'], FILTER_SANITIZE_STRING);
        $kind_student_subjects_card = filter_var($_POST['kind_student_subjects_card'], FILTER_SANITIZE_STRING);

        $name = '';
        $last_name = '';
        $school_year = '';
        $subjects_count = 0;
        $average = 0;
        $subjects_list = '';
        $subjects_total = 0;
        $subjects_summary = 0;

        require_once 'db_connexion.php';

        try {
            $sql = "SELECT alumnos.alumno_matricula,
        alumnos.nombre,
        alumnos.apellidos,
        alumnos.estatus,
        asignar_alumno.id_asignar_alumno,
        asignar_alumno.alumno_grupo,
        asignar_alumno.alumno_semestre,
        asignar_alumno.alumno_bachillerato,
        asignar_alumno.alumno_ciclo_escolar,
        asignar_alumno.alumno_generacion,
        boleta.id_semestre_alumno,
        boleta.materia_clave,
        boleta.alumno_matricula,
        boleta.parcial_1,
        boleta.faltas_1,
        boleta.parcial_2,
        boleta.faltas_2,
        boleta.final,
        boleta.faltas_final,
        boleta.estado_parcial_1,
        boleta.estado_parcial_2,
        boleta.estado_parcial_final,
        boleta.promedio,
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
        WHERE alumnos.alumno_matricula = '$input_search_student_id_card'
        AND
        asignar_alumno.alumno_matricula = '$input_search_student_id_card'
        AND
        asignar_alumno.alumno_semestre = '$semester_student_card'
        AND
        asignar_alumno.alumno_bachillerato = '$kind_student_subjects_card'
        AND
        asignar_alumno.id_asignar_alumno = boleta.id_semestre_alumno
        AND
        boleta.materia_clave = materias.materia_clave
        AND
        alumnos.estatus != 'baja'
        ORDER BY materias.materia_orden ASC";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                    $assigned_score = '';
                    $name = $row['nombre'];
                    $last_name = $row['apellidos'];
                    $school_year = $row['alumno_ciclo_escolar'];
                    $parcial_1 = 'NA';
                    $parcial_2 = 'NA';
                    $parcial_final = 'NA';
                    $regularizacion_1 = '--';
                    $regularizacion_2 = '--';
                    if((int)$row['estado_parcial_1'] == 1){
                        $parcial_1 = $row['parcial_1'];
                    }
                    if((int)$row['estado_parcial_2'] == 1){
                        $parcial_2 = $row['parcial_2'];
                    }
                    if((int)$row['estado_parcial_final'] == 1){
                        $parcial_final = $row['final'];
                    }
                    if((int)$row['regu_1_estado'] == 1){
                        $regularizacion_1 = $row['regularizacion_1'];
                    }
                    if((int)$row['regu_2_estado'] == 1){
                        $regularizacion_2 = $row['regularizacion_2'];
                    }
                    if($row['tipo_materia'] == 'Extra' && (int)$row['regu_1_estado'] == 1){
                        if((float)$row['regularizacion_1'] >= 6){
                            $regularizacion_1 = 'Ac.';
                        }else{
                            $regularizacion_1 = 'No Ac.';
                        }
                    }
                    if($row['tipo_materia'] == 'Extra' && (int)$row['regu_2_estado'] == 1){
                        if((float)$row['regularizacion_2'] >= 6){
                            $regularizacion_2 = 'Ac.';
                        }else{
                            $regularizacion_2 = 'No Ac.';
                        }
                    }
                    if(($row['tipo_materia'] != 'Extra' && (int)$row['estado_parcial_1'] == 1 && (int)$row['estado_parcial_2'] == 1 && (int)$row['estado_parcial_final'] == 1) && (int)$row['regu_1_estado'] == 0 && (int)$row['regu_2_estado'] == 0){
                        $assigned_score = (float)$row['promedio'];
                        $subjects_count++;
                        $subjects_summary += $assigned_score;
                    }else if(($row['tipo_materia'] != 'Extra' && (int)$row['estado_parcial_1'] == 1 && (int)$row['estado_parcial_2'] == 1 && (int)$row['estado_parcial_final'] == 1) && (int)$row['regu_1_estado'] == 1 && (int)$row['regu_2_estado'] == 0){
                        $assigned_score = (float)$row['promedio'];
                        $subjects_count++;
                        $subjects_summary += (float)$row['regularizacion_1'];
                    }else if(($row['tipo_materia'] != 'Extra' && (int)$row['estado_parcial_1'] == 1 && (int)$row['estado_parcial_2'] == 1 && (int)$row['estado_parcial_final'] == 1) && ((int)$row['regu_1_estado'] == 1 || (int)$row['regu_1_estado'] != 1) && (int)$row['regu_2_estado'] == 1){
                        $assigned_score = (float)$row['promedio'];
                        $subjects_count++;
                        $subjects_summary += (float)$row['regularizacion_2'];
                    }else if($row['tipo_materia'] == 'Extra' && (int)$row['estado_parcial_1'] == 1 && (int)$row['estado_parcial_2'] == 1 && (int)$row['estado_parcial_final'] == 1){
                        if((float)$row['promedio'] >= 6){
                            $assigned_score = 'Acreditada';
                        }else{
                            $assigned_score = 'No Acreditada';
                        }
                    }else{
                        $assigned_score = 'No Asignado';
                     }
                     $subjects_list .= $row['materia_nombre'].';'.$parcial_1.';'
                     .$row['faltas_1'].';'.$parcial_2.';'.$row['faltas_2'].';'
                     .$parcial_final.';'.$row['faltas_final'].';'.$assigned_score.';'.$regularizacion_1.';'.$regularizacion_2.';';
            }
            if($subjects_count == 0){
                $average = 0;
            }else{
                $average = bcdiv(($subjects_summary / $subjects_count), '1', 2);
            }
            
            $conn->close();
        } catch (Exception $e) {
                echo $e->getMessage();
        }

        class PDF extends FPDF
        {
            function LoadData($lists)
                {
                    $group = explode(';',trim($lists));
                    return $group;
                }

            function FancyTable($header, $data)
            {
                // Colors, line width and bold font
                $this->SetFillColor(255,255,255);
                $this->SetTextColor(0);
                $this->AddFont('trebuc','B','trebuc.php');
                $this->SetFont('trebuc','B',8);
                // Header
                $w = array(55, 15, 15, 15, 15, 15, 15, 15, 15, 15);
                for($i=0;$i<count($header);$i++){
                    if($i < 1){
                        $this->Cell($w[$i],10,utf8_decode($header[$i]),0,0,'L',true);
                    }else{
                        $this->Cell($w[$i],10,utf8_decode($header[$i]),0,0,'C',true);
                    }
                }
                $this->Ln();
                $this->SetFillColor(224,235,255);
                $this->SetTextColor(0);
                $this->SetFont('','');
                $fill = false;
                $counter = 0;
                if(count($data) != 1){
                for($i = 0; $i < (count($data)/11); $i++)
                {
                    $this->Cell($w[0],12,utf8_decode($data[$counter++]),0,0,'L',$fill);
                    $this->Cell($w[1],12,utf8_decode($data[$counter++]),0,0,'C',$fill);
                    $this->Cell($w[2],12,utf8_decode($data[$counter++]),0,0,'C',$fill);
                    $this->Cell($w[3],12,utf8_decode($data[$counter++]),0,0,'C',$fill);
                    $this->Cell($w[4],12,utf8_decode($data[$counter++]),0,0,'C',$fill);
                    $this->Cell($w[5],12,utf8_decode($data[$counter++]),0,0,'C',$fill);
                    $this->Cell($w[6],12,utf8_decode($data[$counter++]),0,0,'C',$fill);
                    $this->Cell($w[7],12,utf8_decode($data[$counter++]),0,0,'C',$fill);
                    $this->Cell($w[8],12,utf8_decode($data[$counter++]),0,0,'C',$fill);
                    $this->Cell($w[9],12,utf8_decode($data[$counter++]),0,0,'C',$fill);
                    $this->Ln();
                    $fill = !$fill;
                }
            }
                // Closing line
                $this->Cell(array_sum($w),0,'',0, 1,'','T');
            }

            function Footer()
                {
                    // Page footer
                    $this->SetY(-25);
                    $this->AddFont('trebuc','','trebuc.php');
                    $this->SetFont('trebuc','',10);
                    $this->Cell(190,5,'________________________________________',0,1,'C');
                    $this->Cell(190,5,utf8_decode('I.C. CATALINA BETZABÉ MIRANDA DOMÍNGUEZ'),0,1,'C');
                    $this->Cell(190,5,utf8_decode('DIRECTORA DEL PLANTEL'),0,1,'C');
                    $this->Cell(0,0,utf8_decode(getActualDate()),0,0,'L');
                    $this->Cell(0,0,utf8_decode('Página '.$this->PageNo()),0,0,'R');
                }
        }
        // Instanciation of inherited class
        $pdf = new PDF('P','mm', 'A4');
        $header = array('MATERIA', '1° PARCIAL', 'FALTAS', '2° PARCIAL', 'FALTAS', 'FINAL', 'FALTAS', 'PROMEDIO', 'EE', 'ER');
        $data = $pdf->LoadData($subjects_list);
        $pdf->AddFont('trebucbd','B','trebucbd.php');
        $pdf->SetFont('trebucbd','B',8);
        $pdf->AddPage();
        $pdf->Image('../img/originalLogo.png',10,6,15,0,'');
                $pdf->AddFont('trebucbd','B','trebucbd.php');
                $pdf->AddFont('trebuc','','trebuc.php');
                $pdf->SetFont('trebucbd','B',7);
                $pdf->Cell(190,3,utf8_decode('PREPARATORIA PARTICULAR'),0,1,'C');
                $pdf->Cell(190,3,utf8_decode('"INSTITUTO DE CIENCIAS Y LETRAS NAYAR"'),0,0,'C');
                $pdf->Cell(190,3,utf8_decode('SECRETARIA  DE EDUCACIÓN EN EL ESTADO'),0,0,'C');
                $pdf->Cell(-10,3,utf8_decode('DIRECCIÓN DE EDUCACIÓN MEDIA SUPERIOR'),0,1,'C');
                $pdf->Cell(190,3,utf8_decode('CLAVE: 16PBH3684F     ACUERDO NO. BACH091102'),0,1,'C');
                $pdf->Cell(190,3,utf8_decode('GALEANA No. 20    CP 60050    TELEFONO: 01(452)523 1927'),0,1,'C');
                $pdf->Cell(190,-3,utf8_decode('____________________________________________________________________________________________________'),0,'B','C');
            $pdf->AddFont('trebuc','','trebuc.php');
            $pdf->SetFont('trebuc','',10);
            $pdf->Cell(95,4,utf8_decode('CICLO ESCOLAR    '),0,0,'R');
            $pdf->Cell(95,4,utf8_decode('    '.$school_year),0,1,'L');
            
            $pdf->AddFont('trebucbd','B','trebucbd.php');
            $pdf->SetFont('trebucbd','B',9);
            $pdf->Cell(37,20,utf8_decode('NOMBRE DEL ALUMNO: '),0,0);
            $pdf->SetFont('trebuc','',9);
            $pdf->Cell(0,20,utf8_decode($name.' '.$last_name),0,1,'L');
            $pdf->SetFont('trebucbd','B',9);
            $pdf->Cell(19,0,utf8_decode('SEMESTRE: '),0,0);
            $pdf->SetFont('trebuc','',9);
            $pdf->Cell(0,0,utf8_decode($semester_student_card),0,1,'L');
            $pdf->SetFont('trebucbd','B',9);
            $pdf->Cell(26,20,utf8_decode('BACHILLERATO: '),0,0);
            $pdf->SetFont('trebuc','',9);
            $pdf->Cell(0,20,utf8_decode($kind_student_subjects_card),0,1,'L');
            $pdf->FancyTable($header,$data);
            $pdf->Cell(185,15,utf8_decode('PROMEDIO GENERAL: '),0,0,'R');
            $pdf->Cell(5,15,utf8_decode($average),0,1,'C');
        $pdf->Output();
   
    }else if($report_card_kind === 'per_group'){


        $input_search_teacher_id_card = filter_var($_POST['input_search_teacher_id_card'], FILTER_SANITIZE_STRING);
        $school_year_teacher_card = filter_var($_POST['school_year_teacher_card'], FILTER_SANITIZE_STRING);
        $teachers_group_card = filter_var($_POST['teachers_group_card'], FILTER_SANITIZE_STRING);
        $semester_teacher_card = filter_var($_POST['semester_teacher_card'], FILTER_SANITIZE_STRING);
        $kind_subjects_teacher_card = filter_var($_POST['kind_subjects_teacher_card'], FILTER_SANITIZE_STRING);
        $teachers_subject_card = filter_var($_POST['teachers_subject_card'], FILTER_SANITIZE_STRING);

        $name_teacher = '';
        $name_subject = '';
        $students_count = 0;
        $average = 0;
        $students_list = '';
        $students_total = 0;

        require_once 'db_connexion.php';

        try {
            $sql = "SELECT maestros.profesor_matricula,
            maestros.profesor_nombre,
            asignar_alumno.id_asignar_alumno,
            asignar_alumno.alumno_matricula,
            asignar_alumno.alumno_semestre,
            asignar_alumno.alumno_grupo,
            asignar_alumno.alumno_bachillerato,
            asignar_alumno.alumno_ciclo_escolar,
            alumnos.alumno_matricula,
            alumnos.nombre,
            alumnos.apellidos,
            alumnos.estatus,
            boleta.profesor_matricula,
            boleta.id_semestre_alumno,
            boleta.materia_clave,
            boleta.alumno_matricula,
            boleta.parcial_1,
            boleta.faltas_1,
            boleta.parcial_2,
            boleta.faltas_2,
            boleta.final,
            boleta.faltas_final,
            boleta.estado_parcial_1,
            boleta.estado_parcial_2,
            boleta.estado_parcial_final,
            boleta.promedio,
            boleta.regularizacion_1,
            boleta.regu_1_estado,
            boleta.regularizacion_2,
            boleta.regu_2_estado,
            materias.materia_clave,
            materias.materia_nombre,
            materias.tipo_materia
        FROM maestros
        INNER JOIN alumnos
        INNER JOIN asignar_alumno
        INNER JOIN boleta
        INNER JOIN materias
        WHERE maestros.profesor_matricula = '$input_search_teacher_id_card'
        AND
        asignar_alumno.alumno_semestre = '$semester_teacher_card'
        AND
        asignar_alumno.alumno_grupo = '$teachers_group_card'
        AND
        asignar_alumno.alumno_bachillerato = '$kind_subjects_teacher_card'
        AND
        asignar_alumno.alumno_ciclo_escolar = '$school_year_teacher_card'
        AND
        asignar_alumno.alumno_matricula = boleta.alumno_matricula
        AND
        boleta.profesor_matricula = '$input_search_teacher_id_card'
        AND
        boleta.materia_clave = '$teachers_subject_card'
        AND
        boleta.alumno_matricula = alumnos.alumno_matricula
        AND
        boleta.materia_clave = materias.materia_clave 
        AND
        alumnos.estatus != 'baja'
        ORDER BY alumnos.apellidos ASC";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {

                    $assigned_score = '';
                    $name_teacher = $row['profesor_nombre'];
                    $name_subject = $row['materia_nombre'];
                    $parcial_1 = 'NA';
                    $parcial_2 = 'NA';
                    $parcial_final = 'NA';
                    $regularizacion_1 = '--';
                    $regularizacion_2 = '--';
                    if((int)$row['estado_parcial_1'] == 1){
                        $parcial_1 = $row['parcial_1'];
                    }
                    if((int)$row['estado_parcial_2'] == 1){
                        $parcial_2 = $row['parcial_2'];
                    }
                    if((int)$row['estado_parcial_final'] == 1){
                        $parcial_final = $row['final'];
                    }
                    if((int)$row['regu_1_estado'] == 1){
                        $regularizacion_1 = $row['regularizacion_1'];
                    }
                    if((int)$row['regu_2_estado'] == 1){
                        $regularizacion_2 = $row['regularizacion_2'];
                    }
                    if($row['tipo_materia'] == 'Extra' && (int)$row['regu_1_estado'] == 1){
                        if((float)$row['regularizacion_1'] >= 6){
                            $regularizacion_1 = 'Ac.';
                        }else{
                            $regularizacion_1 = 'No Ac.';
                        }
                    }
                    if($row['tipo_materia'] == 'Extra' && (int)$row['regu_2_estado'] == 1){
                        if((float)$row['regularizacion_2'] >= 6){
                            $regularizacion_2 = 'Ac.';
                        }else{
                            $regularizacion_2 = 'No Ac.';
                        }
                    }
                     if($row['tipo_materia'] != 'Extra' && (int)$row['estado_parcial_1'] == 1 && (int)$row['estado_parcial_2'] == 1 && (int)$row['estado_parcial_final'] == 1){
                        $assigned_score = (float)$row['promedio'];
                        $students_count++;
                        $students_total++;
                        $average = bcdiv((($average + (float)$row['promedio']) / $students_count), '1', 2);
                     }else if(($row['tipo_materia'] != 'Extra' && (int)$row['estado_parcial_1'] == 1 && (int)$row['estado_parcial_2'] == 1 && (int)$row['estado_parcial_final'] == 1) && (int)$row['regu_1_estado'] == 1 && (int)$row['regu_2_estado'] == 0){
                        $assigned_score = (float)$row['promedio'];
                        $students_count++;
                        $students_total++;
                        $average = bcdiv((($average + (float)$row['regularizacion_1']) / $students_count), '1', 2);
                    }else if(($row['tipo_materia'] != 'Extra' && (int)$row['estado_parcial_1'] == 1 && (int)$row['estado_parcial_2'] == 1 && (int)$row['estado_parcial_final'] == 1) && ((int)$row['regu_1_estado'] == 1 || (int)$row['regu_1_estado'] != 1) && (int)$row['regu_2_estado'] == 1){
                        $assigned_score = (float)$row['promedio'];
                        $students_count++;
                        $students_total++;
                        $average = bcdiv((($average + (float)$row['regularizacion_2']) / $students_count), '1', 2);
                    }else if($row['tipo_materia'] == 'Extra' && (int)$row['estado_parcial_1'] == 1 && (int)$row['estado_parcial_2'] == 1 && (int)$row['estado_parcial_final'] == 1){
                        if((float)$row['promedio'] >= 6){
                            $assigned_score = 'Acreditada';
                        }else{
                            $assigned_score = 'No Acreditada';
                        }
                     }else{
                        $assigned_score = 'No Asignado';
                        $students_total++;
                     }
                     $students_list .= $row['alumno_matricula'].';'.$row['nombre'].';'.$row['apellidos'].';'
                     .$parcial_1.';'.$row['faltas_1'].';'.$parcial_2.';'.$row['faltas_2'].';'
                     .$parcial_final.';'.$row['faltas_final'].';'.$assigned_score.';'.$regularizacion_1.';'.$regularizacion_2.';';
                
            }
            
            $conn->close();
        } catch (Exception $e) {
                echo $e->getMessage();
        }

        class PDF extends FPDF
        {

            
            function LoadData($lists)
                {
                    global $students_total;
                        $group = explode(';',trim($lists));
                    //echo implode(',',$group);
                    
                    return $group;
                }

            function FancyTable($header, $data)
            {
                // Colors, line width and bold font
                $this->SetFillColor(255,255,255);
                $this->SetTextColor(0);
                $this->AddFont('trebuc','','trebuc.php');
                $this->SetFont('trebuc','',7);
                // Header
                $w = array(15, 30, 30, 16, 11, 16, 11, 15, 11, 16, 10, 10);
                for($i=0;$i<count($header);$i++){
                    if($i < 1){
                        $this->Cell($w[$i],10,utf8_decode($header[$i]),0,0,'L',true);
                    }else{
                        $this->Cell($w[$i],10,utf8_decode($header[$i]),0,0,'C',true);
                    }
                    
                }
                    
                $this->Ln();
                // Color and font restoration
                $this->SetFillColor(224,235,255);
                $this->SetTextColor(0);
                $this->SetFont('','');
                // Data
                $fill = false;
                $counter = 0;

                if(count($data) != 1){
                    for($i = 0; $i < (count($data)/13); $i++)
                    {
                        $this->Cell($w[0],7,utf8_decode($data[$counter++]),0,0,'L',$fill);
                        //echo $data[$counter];
                        $this->Cell($w[1],7,utf8_decode($data[$counter++]),0,0,'L',$fill);
                        $this->Cell($w[2],7,utf8_decode($data[$counter++]),0,0,'L',$fill);
                        $this->Cell($w[3],7,utf8_decode($data[$counter++]),0,0,'C',$fill);
                        $this->Cell($w[4],7,utf8_decode($data[$counter++]),0,0,'C',$fill);
                        $this->Cell($w[5],7,utf8_decode($data[$counter++]),0,0,'C',$fill);
                        $this->Cell($w[6],7,utf8_decode($data[$counter++]),0,0,'C',$fill);
                        $this->Cell($w[7],7,utf8_decode($data[$counter++]),0,0,'C',$fill);
                        $this->Cell($w[8],7,utf8_decode($data[$counter++]),0,0,'C',$fill);
                        $this->Cell($w[9],7,utf8_decode($data[$counter++]),0,0,'C',$fill);
                        $this->Cell($w[10],7,utf8_decode($data[$counter++]),0,0,'C',$fill);
                        $this->Cell($w[11],7,utf8_decode($data[$counter++]),0,0,'C',$fill);
                        $this->Ln();
                        $fill = !$fill;
                    }
                }
                
                // Closing line
                $this->Cell(array_sum($w),0,'',0, 1,'','T');
            }

            function Footer()
                {
                    // Page footer
                    $this->SetY(-30);
                    $this->AddFont('trebuc','','trebuc.php');
                    $this->SetFont('trebuc','',10);
                    $this->Cell(190,10,'',0,1,'C');
                    $this->Cell(190,5,'________________________________________',0,1,'C');
                    $this->Cell(190,5,utf8_decode('NOMBRE Y FIRMA'),0,1,'C');
                    $this->Cell(0,0,utf8_decode(getActualDate()),0,0,'L');
                    $this->Cell(0,0,utf8_decode('Página '.$this->PageNo()),0,0,'R');
                }
            
        }
        // Instanciation of inherited class
        $pdf = new PDF('P','mm', array(210,297));
        $header = array('MATRICULA','NOMBRE','APELLIDOS', '1° PARCIAL', 'FALTAS', '2° PARCIAL', 'FALTAS', 'FINAL', 'FALTAS', 'PROMEDIO', 'EE', 'ER');
        $data = $pdf->LoadData($students_list);
        $pdf->AddFont('trebucbd','B','trebucbd.php');
        $pdf->SetFont('trebucbd','B',9);
        $pdf->AddPage();
        $pdf->Image('../img/originalLogo.png',10,6,15,0,'');
                $pdf->AddFont('trebucbd','B','trebucbd.php');
                $pdf->AddFont('trebuc','','trebuc.php');
                $pdf->SetFont('trebucbd','B',7);
                $pdf->Cell(190,3,utf8_decode('PREPARATORIA PARTICULAR'),0,1,'C');
                $pdf->Cell(190,3,utf8_decode('"INSTITUTO DE CIENCIAS Y LETRAS NAYAR"'),0,0,'C');
                $pdf->Cell(190,3,utf8_decode('SECRETARIA  DE EDUCACIÓN EN EL ESTADO'),0,0,'C');
                $pdf->Cell(-10,3,utf8_decode('DIRECCIÓN DE EDUCACIÓN MEDIA SUPERIOR'),0,1,'C');
                $pdf->Cell(190,3,utf8_decode('CLAVE: 16PBH3684F     ACUERDO NO. BACH091102'),0,1,'C');
                $pdf->Cell(190,3,utf8_decode('GALEANA No. 20    CP 60050    TELEFONO: 01(452)523 1927'),0,1,'C');
                $pdf->Cell(190,-3,utf8_decode('____________________________________________________________________________________________________'),0,'B','C');
                $pdf->Ln(5);
            $pdf->AddFont('trebuc','','trebuc.php');
            $pdf->SetFont('trebucbd','B',9);
            $pdf->Cell(190,4,utf8_decode('BOLETA DE CALIFICACIONES'),0,1,'C');
            $pdf->Cell(95,9,utf8_decode('CICLO ESCOLAR     '),0,0,'R');
            $pdf->Cell(95,9,utf8_decode('     '.$school_year_teacher_card),0,1,'L');
            $pdf->SetFont('trebucbd','B',9);
            $pdf->Cell(22,20,utf8_decode('PROFESOR(A): '),0,0);
            $pdf->SetFont('trebuc','',9);
            $pdf->Cell(60,20,utf8_decode($name_teacher),0,0,'L');
            $pdf->SetFont('trebucbd','B',9);
            $pdf->Cell(15,20,utf8_decode('MATERIA: '),0,0);
            $pdf->SetFont('trebuc','',9);
            $pdf->Cell(75,20,utf8_decode($name_subject),0,0,'L');
            $pdf->SetFont('trebucbd','B',9);
            $pdf->Cell(12,20,utf8_decode('GRUPO: '),0,0);
            $pdf->SetFont('trebuc','',9);
            $pdf->Cell(50,20,utf8_decode($teachers_group_card),0,1,'L');
            $pdf->FancyTable($header,$data);
        $pdf->Output();
    }
    else if($report_card_kind === 'per_group_subjects'){
        $school_year_group_card = filter_var($_POST['school_year_group_card'], FILTER_SANITIZE_STRING);
        $group_letter_card = filter_var($_POST['group_letter_card'], FILTER_SANITIZE_STRING);
        $semester_group_card = filter_var($_POST['semester_group_card'], FILTER_SANITIZE_STRING);
        $kind_subjects_group_card = filter_var($_POST['kind_subjects_group_card'], FILTER_SANITIZE_STRING);

        $name_teacher = '';
        $name_subject = '';
        $subjects_count = 0;
        $student_number = 0;
        $average = '';
        $students_list = '';
        $subjects_total = 0;
        $subjects = array('NOMBRE');
        $student_switch = '';
        $last_score = '';

        require_once 'db_connexion.php';

        try {
            $sql = "SELECT asignar_alumno.id_asignar_alumno,
            asignar_alumno.alumno_matricula,
            asignar_alumno.alumno_semestre,
            asignar_alumno.alumno_grupo,
            asignar_alumno.alumno_bachillerato,
            asignar_alumno.alumno_ciclo_escolar,
            alumnos.alumno_matricula,
            alumnos.nombre,
            alumnos.apellidos,
            boleta.id_semestre_alumno,
            boleta.materia_clave,
            boleta.alumno_matricula,
            boleta.estado_parcial_1,
            boleta.estado_parcial_2,
            boleta.estado_parcial_final,
            boleta.promedio,
            materias.materia_clave,
            materias.materia_nombre,
            materias.tipo_materia,
            materias.materia_orden
        FROM alumnos
        INNER JOIN asignar_alumno
        INNER JOIN boleta
        INNER JOIN materias
        WHERE
        asignar_alumno.alumno_semestre = '$semester_group_card'
        AND
        asignar_alumno.alumno_grupo = '$group_letter_card'
        AND
        asignar_alumno.alumno_bachillerato = '$kind_subjects_group_card'
        AND
        asignar_alumno.alumno_ciclo_escolar = '$school_year_group_card'
        AND
        asignar_alumno.id_asignar_alumno = id_semestre_alumno
        AND
        asignar_alumno.alumno_matricula = boleta.alumno_matricula
        AND
        boleta.alumno_matricula = alumnos.alumno_matricula
        AND
        boleta.materia_clave = materias.materia_clave ORDER BY alumnos.alumno_matricula, materias.materia_orden ASC";
            $result = mysqli_query($conn, $sql);
            $x = 0;
            while ($row = mysqli_fetch_assoc($result)) {
               // if($row['tipo_materia'] === 'Extra'){

                if(in_array($row['materia_nombre'],$subjects)){

                }else{
                    $subjects[] = $row['materia_nombre'];
                }
                if($row['alumno_matricula'] != $student_switch){
                    if($subjects_count != 0){
                        //$students_list .= 'promedio: '.$average.' divisor: '.$subjects_count;
                            $students_list .= bcdiv(($average / $subjects_count), '1', 2).';';
                                
                    }else if($subjects_count == 0 && $students_list != ''){
                        $students_list .= 'NA;';
                    }
                    $average = 0;
                    $subjects_count = 0;
                    $student_switch = $row['alumno_matricula'];
                    $assigned_score = '';
                    $student_number++;
                    if((int)$row['estado_parcial_1'] == 1 && (int)$row['estado_parcial_2'] == 1 && (int)$row['estado_parcial_final'] == 1){
                       $assigned_score = (float)$row['promedio'];
                       $subjects_count++;
                       $average += (float)$row['promedio'];
                    }else{
                       $assigned_score = 'NA';
                    }
                    $students_list .= $row['nombre'].';'.$row['apellidos'].';'
                    .$assigned_score.';';
                }else{
                    if((int)$row['estado_parcial_1'] == 1 && (int)$row['estado_parcial_2'] == 1 && (int)$row['estado_parcial_final'] == 1){
                        $assigned_score = (float)$row['promedio'];
                        $subjects_count++;
                        $average += (float)$row['promedio'];
                     }else{
                        $assigned_score = 'NA';
                     }
                     $students_list .= $assigned_score.';';
                }
                //}else{
                    //$last_score = $average;
                }
                if($subjects_count != 0){
                        $students_list .= bcdiv(($average / $subjects_count), '1', 1).';';
                }else if($subjects_count == 0 && $students_list != ''){
                    $students_list .= 'NA;';
                }
                
                    
            $conn->close();
        } catch (Exception $e) {
                echo $e->getMessage();
        }

        class PDF extends FPDF
        {

            function Footer()
                {
                    // Page footer
                    $this->SetY(-30);
                    $this->AddFont('trebuc','','trebuc.php');
                    $this->SetFont('trebuc','',10);
                    $this->Cell(190,15,'',0,1,'C');
                    $this->Cell(0,0,utf8_decode(getActualDate()),0,0,'L');
                    $this->Cell(0,0,utf8_decode('Página '.$this->PageNo()),0,0,'R');
                }


            var $angle=0;

            function Rotate($angle,$x=-1,$y=-1)
            {
                if($x==-1)
                    $x=$this->x;
                if($y==-1)
                    $y=$this->y;
                if($this->angle!=0)
                    $this->_out('Q');
                $this->angle=$angle;
                if($angle!=0)
                {
                    $angle*=M_PI/180;
                    $c=cos($angle);
                    $s=sin($angle);
                    $cx=$x*$this->k;
                    $cy=($this->h-$y)*$this->k;
                    $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
                }
            }

            function _endpage()
            {
                if($this->angle!=0)
                {
                    $this->angle=0;
                    $this->_out('Q');
                }
                parent::_endpage();
            }

            function RotatedText($x,$y,$txt,$angle)
                {
                    //Text rotated around its origin
                    $this->Rotate($angle,$x,$y);
                    $this->Text($x,$y,$txt);
                    $this->Rotate(0);
                }

                function LoadData($lists)
                {
                        
                        $group = explode(';',trim($lists));
                        $cleaned_list = array_filter($group, "strlen");
                    //echo implode(',',$group);

                    return $cleaned_list;
                }

            function FancyTable($header, $data)
            {
                // Colors, line width and bold font
                $this->SetFillColor(255,255,255);
                $this->SetTextColor(0);
                //$this->SetDrawColor(128,0,0);
                //$this->SetLineWidth(.3);
                $this->AddFont('trebuc','','trebuc.php');
                $this->SetFont('trebuc','',9);
                //echo implode(',',$data);
                // Header
                $w = array('70');
                $size = 68;
                for($i = 0; $i <(count($header)- 1);$i++){
                    $w[] = 10;
                    $w[] = 8;
                }
                $header[] = 'PROM. GRAL.';

                $w[] = 18;
                for($i=0;$i<count($header);$i++)
                    //$this->Cell($w[$i],10,utf8_decode($header[$i]),0,0,'C',true);
                    if($i === 0){
                        $this->Cell(70,5,utf8_decode($header[$i]),1,0,'C',true);
                    }else if($i == ((count($header) - 1))){
                        //$this->Cell(8,-63,utf8_decode(''),1,0,'C',true);
                        $this->Cell(18,-63,utf8_decode(''),1,0,'C',true);
                        $this->Cell(-0.1,5,utf8_decode($this->RotatedText($size+=22,71,utf8_decode($header[$i]),90)),1,0,'C',true);
                    }else{
                       // $this->RotatedText($size+=20,100,utf8_decode($header[$i]),90);
                       $this->Cell(10,-63,utf8_decode(''),1,0,'C',true);
                        $this->Cell(8,5,utf8_decode($this->RotatedText($size+=18,71,utf8_decode($header[$i]),90)),1,0,'C',true);
                        //$this->Cell(8,5,utf8_decode(''),1,0,'C',true);
                    }
                    
                $this->Ln();
                // Color and font restoration
                $this->SetFillColor(224,235,255);
                $this->SetTextColor(0);
                $this->SetFont('','');
                // Data
                $fill = false;
            //echo $data[60];
                $counter = 0;
                global $student_number;
                if(count($data) != 0){
                    for($i = 0; $i < $student_number;$i++)
                    {
                        for($j = 0; $j < ((count($header) * 2) - 3); $j++){
                            if($j == 0){
                                $this->Cell($w[$j],7,utf8_decode($data[$counter++].' '.$data[$counter++]),1,0,'L',$fill);
                            }else{
                                $this->Cell($w[$j],7,utf8_decode($data[$counter++]),1,0,'C',$fill);
                                $this->Cell($w[++$j],7,'',1,0,'C',$fill);
                                if($j == (count($header) * 2) - 4){
                                $this->Cell($w[++$j],7,utf8_decode($data[$counter++]),1,0,'C',$fill);
                                }
                            }
                        }
                        $this->Ln();
                            $fill = !$fill;
                }
                }
                // Closing line
                $this->Cell(array_sum($w),0,'',0, 1,'','T');
            
        }
    }
        // Instanciation of inherited class
        $pdf = new PDF('L','mm', 'A4');
        $data = $pdf->LoadData($students_list);
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->AddFont('trebucbd','B','trebucbd.php');
        $pdf->SetFont('trebucbd','B',8);
        $pdf->Image('../img/originalLogo.png',15,8,25,0,'');
            $pdf->SetFont('trebucbd','B',10);
            $pdf->Cell(25,55,utf8_decode('PROMEDIO GENERAL '),0,1);
            $pdf->SetFont('trebucbd','B',9);
            $pdf->Cell(17,-44,utf8_decode('SEMESTRE: '),0,0);
            $pdf->AddFont('trebuc','','trebuc.php');
            $pdf->SetFont('trebuc','',9);
            $pdf->Cell(75,-44,utf8_decode($semester_group_card),0,1,'L');
            $pdf->SetFont('trebucbd','B',9);
            $pdf->Cell(12,55,utf8_decode('GRUPO: '),0,0);
            $pdf->SetFont('trebuc','',9);
            $pdf->Cell(50,55,utf8_decode($group_letter_card),0,1,'L');
            $pdf->SetFont('trebucbd','B',9);
            $pdf->Cell(24,-44,utf8_decode('BACHILLERATO: '),0,0);
            $pdf->SetFont('trebuc','',9);
            $pdf->Cell(50,-44,utf8_decode($kind_subjects_group_card),0,1,'L');
            $pdf->SetFont('trebucbd','B',9);
            $pdf->Cell(11,55,utf8_decode('CICLO: '),0,0);
            $pdf->SetFont('trebuc','',9);
            $pdf->Cell(50,55,utf8_decode($school_year_group_card),0,1,'L');
            $pdf->SetFont('trebuc','',9);
            $pdf->Cell(50,-15,utf8_decode(''),0,1,'L');
            $pdf->FancyTable($subjects,$data);
        $pdf->Output();
    }
    else if($report_card_kind === 'student_historial'){
        $input_search_student_id_record = filter_var($_POST['input_search_student_id_record'], FILTER_SANITIZE_STRING);
        $kind_student_historial_record = filter_var($_POST['kind_student_historial_record'], FILTER_SANITIZE_STRING);

        if($kind_student_historial_record === 'student_sep_historial'){
           
        $name = '';
        $curp = '';
        $last_name = '';
        $school_year = '';
        $subjects_count = 0;
        $average = 0;
        $subjects_list = '';
        $subjects_total = 0;
        $subjects_summary = 0;
        $semester_student = '';
        $training_to_work = 0;
        $semester_count = 0;
        $semester_date = array();

        require_once 'db_connexion.php';

        try {
            $sql = "SELECT alumnos.alumno_matricula,
        alumnos.nombre,
        alumnos.apellidos,
        alumnos.curp,
        alumnos.estatus,
        asignar_alumno.id_asignar_alumno,
        asignar_alumno.alumno_ciclo_escolar,
        boleta.id_semestre_alumno,
        boleta.materia_clave,
        boleta.alumno_matricula,
        boleta.estado_parcial_1,
        boleta.estado_parcial_2,
        boleta.estado_parcial_final,
        boleta.promedio,
        boleta.regularizacion_1,
        boleta.regu_1_fecha,
        boleta.regu_1_estado,
        boleta.regularizacion_2,
        boleta.regu_2_fecha,
        boleta.regu_2_estado,
        materias.materia_clave,
        materias.materia_nombre,
        materias.materia_orden,
        materias.materia_semestre,
        materias.materia_bachillerato,
        materias.tipo_materia,
        control_fechas.ciclo_escolar,
        control_fechas.semestre_non_inicio,
        control_fechas.semestre_non_fin,
        control_fechas.semestre_par_inicio,
        control_fechas.semestre_par_fin
        FROM alumnos
        INNER JOIN asignar_alumno
        INNER JOIN boleta
        INNER JOIN materias
        INNER JOIN control_fechas
        WHERE alumnos.alumno_matricula = '$input_search_student_id_record'
        AND
        asignar_alumno.alumno_matricula = '$input_search_student_id_record'
        AND
        asignar_alumno.id_asignar_alumno = boleta.id_semestre_alumno
        AND
        boleta.materia_clave = materias.materia_clave 
        AND
        asignar_alumno.alumno_ciclo_escolar = control_fechas.ciclo_escolar 
        AND 
        materias.tipo_materia != 'Extra'
        AND
        alumnos.estatus != 'baja'
        ORDER BY FIELD (materia_semestre, 'Primero', 'Segundo', 'Tercero', 'Cuarto', 'Quinto', 'Sexto') ASC, materia_bachillerato, materia_orden";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {

                    $assigned_score = '';
                    $name = $row['nombre'].' '.$row['apellidos'];
                    $curp = $row['curp'];
                    if($semester_student != $row['materia_semestre']){
                        $semester_student = $row['materia_semestre'];
                        $subjects_list .= $semester_student.';';
                        $training_to_work = 0;
                        $semester_count++;
                        if($semester_student == 'Primero' || $semester_student == 'Tercero' || $semester_student == 'Quinto'){
                            array_push($semester_date, $row['semestre_non_inicio']);
                            array_push($semester_date, $row['semestre_non_fin']);
                        }else if($semester_student == 'Segundo' || $semester_student == 'Cuarto' || $semester_student == 'Sexto'){
                            array_push($semester_date, $row['semestre_par_inicio']);
                            array_push($semester_date, $row['semestre_par_fin']);
                        }
                    }

                     if(($row['tipo_materia'] == 'Normal' && (int)$row['estado_parcial_1'] == 1 && (int)$row['estado_parcial_2'] == 1 && (int)$row['estado_parcial_final'] == 1) && (int)$row['regu_1_estado'] != 1 && (int)$row['regu_2_estado'] != 1){
                        $assigned_score = (float)$row['promedio'];
                        $subjects_count++;
                        $subjects_total++;
                        $subjects_list .= $row['materia_nombre'].';'.$assigned_score.';;';
                        $subjects_summary += $assigned_score;
                     }else if($row['tipo_materia'] == 'Normal' && ((int)$row['estado_parcial_1'] != 1 || (int)$row['estado_parcial_2'] != 1 || (int)$row['estado_parcial_final'] != 1)){
                        $assigned_score = 'NA';
                        $subjects_total++;
                        $subjects_list .= $row['materia_nombre'].';'.$assigned_score.';;';
                     }else if(($row['tipo_materia'] == 'Formación para el Trabajo' && (int)$row['estado_parcial_1'] == 1 && (int)$row['estado_parcial_2'] == 1 && (int)$row['estado_parcial_final'] == 1) && (int)$row['regu_1_estado'] != 1 && (int)$row['regu_2_estado'] != 1){
                        if($training_to_work == 0){
                            $subjects_list .= 'FORMACIÓN PARA EL TRABAJO'.';--;';
                            $training_to_work = 1;
                        }
                        $assigned_score = (float)$row['promedio'];
                        $subjects_count++;
                        $subjects_total++;
                        $subjects_list .= $row['materia_nombre'].';'.$assigned_score.';;';
                        $subjects_summary += $assigned_score;
                     }else if($row['tipo_materia'] == 'Formación para el Trabajo' && ((int)$row['estado_parcial_1'] != 1 || (int)$row['estado_parcial_2'] != 1 || (int)$row['estado_parcial_final'] != 1)){
                        if($training_to_work == 0){
                            $subjects_list .= 'FORMACIÓN PARA EL TRABAJO'.';--;';
                            $training_to_work = 1;
                        }
                        $assigned_score = 'NA';
                        $subjects_total++;
                        $subjects_list .= $row['materia_nombre'].';'.$assigned_score.';;';
                     }else if(($row['tipo_materia'] == 'Normal' && (int)$row['estado_parcial_1'] == 1 && (int)$row['estado_parcial_2'] == 1 && (int)$row['estado_parcial_final'] == 1) && (int)$row['regu_1_estado'] == 1 && (int)$row['regu_2_estado'] != 1){
                        $assigned_score = (float)$row['regularizacion_1'];
                        $subjects_count++;
                        $subjects_total++;
                        $subjects_list .= $row['materia_nombre'].';'.(float)$row['promedio'].';'.(float)$row['regularizacion_1'].' EE '.$row['regu_1_fecha'].';';
                        $subjects_summary += $assigned_score;
                     }else if(($row['tipo_materia'] == 'Formación para el Trabajo' && (int)$row['estado_parcial_1'] == 1 && (int)$row['estado_parcial_2'] == 1 && (int)$row['estado_parcial_final'] == 1) && (int)$row['regu_1_estado'] == 1 && (int)$row['regu_2_estado'] != 1){
                        if($training_to_work == 0){
                            $subjects_list .= 'FORMACIÓN PARA EL TRABAJO'.';--;';
                            $training_to_work = 1;
                        }
                        $assigned_score = (float)$row['regularizacion_1'];
                        $subjects_count++;
                        $subjects_total++;
                        $subjects_list .= $row['materia_nombre'].';'.(float)$row['promedio'].';'.(float)$row['regularizacion_1'].' EE '.$row['regu_1_fecha'].';';
                        $subjects_summary += $assigned_score;
                     }else if(($row['tipo_materia'] == 'Normal' && (int)$row['estado_parcial_1'] == 1 && (int)$row['estado_parcial_2'] == 1 && (int)$row['estado_parcial_final'] == 1) && ((int)$row['regu_1_estado'] == 1 || (int)$row['regu_1_estado'] != 1) && (int)$row['regu_2_estado'] == 1){
                        $assigned_score = (float)$row['regularizacion_2'];
                        $subjects_count++;
                        $subjects_total++;
                        $subjects_list .= $row['materia_nombre'].';'.(float)$row['promedio'].';'.(float)$row['regularizacion_2'].' ER '.$row['regu_2_fecha'].';';
                        $subjects_summary += $assigned_score;
                     }else if(($row['tipo_materia'] == 'Formación para el Trabajo' && (int)$row['estado_parcial_1'] == 1 && (int)$row['estado_parcial_2'] == 1 && (int)$row['estado_parcial_final'] == 1) && ((int)$row['regu_1_estado'] == 1 || (int)$row['regu_1_estado'] != 1) && (int)$row['regu_2_estado'] == 1){
                        if($training_to_work == 0){
                            $subjects_list .= 'FORMACIÓN PARA EL TRABAJO'.';--;';
                            $training_to_work = 1;
                        }
                        $assigned_score = (float)$row['regularizacion_2'];
                        $subjects_count++;
                        $subjects_total++;
                        $subjects_list .= $row['materia_nombre'].';'.(float)$row['promedio'].';'.(float)$row['regularizacion_2'].' ER '.$row['regu_2_fecha'].';';
                        $subjects_summary += $assigned_score;
                     }
                     
    
               // }
                
            }
            
            if ($subjects_count != 0){
                $average = bcdiv(($subjects_summary / $subjects_count), '1', 2);
            }
            
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'error' => $e->getMessage(),
            );
        }

            class PDF extends FPDF
            {

                function LoadData($lists)
                {
                    global $subjects_total;
                    // Read file lines

                    //for($i = 0; $i <= $subjects_total; $i++){
                        $grades = explode(';',trim($lists));
                        
                    //}
                        
                    return $grades;

                }

                function FancyTable($header, $data)
            {
                // Colors, line width and bold font
                $this->SetFillColor(255,255,255);
                $this->SetTextColor(0);
                //$this->SetDrawColor(128,0,0);
                //$this->SetLineWidth(.3);
                $this->AddFont('trebuc','B','trebuc.php');
                $this->SetFont('trebuc','B',9);

                
                // Header
                //$w = array(70, 20, 50, 50);
                $w = array(70, 70, 50);
                
                //$this->Ln();
                // Color and font restoration
                $this->SetFillColor(224,235,255);
                $this->SetTextColor(0);
                $this->SetFont('','');
                // Data
                $fill = false;
                $counter = 0;
                $counter_date = 0;
                global $semester_count;
                global $semester_date;
                for($Z = 0; $Z < 20; $Z++)
                {
                    array_push($data, '');
                }
                if(count($data) != 0){
                for($i = 0; $i < $semester_count; $i++){
                        $this->Cell($w[0],5,utf8_decode('NOMBRE DE LAS MATERIAS'),1,0,'C');
                        $this->Cell($w[1],5,utf8_decode('EVALUACIONES'),1,0,'C');
                        $this->Cell($w[2],5,utf8_decode('FECHA DE INICIO'),1,1,'C');

                        $this->Cell($w[0],5,utf8_decode('SEMESTRE '.strtoupper($data[$counter++])),1,0,'C');
                        $this->Cell(20,5,utf8_decode('PROM. SEM.'),1,0,'C');
                        $this->Cell(50,5,utf8_decode('EXAM. REGULARIZACIÓN'),1,0,'C');
                        $this->Cell($w[2],5,utf8_decode(transformDate($semester_date[$counter_date++])),1,1,'C');
                    for($j = 0; $j < 10; $j++)
                    {
                            if($data[$counter] == 'Primero' || $data[$counter] == 'Segundo'
                            || $data[$counter] == 'Tercero' || $data[$counter] == 'Cuarto'
                            || $data[$counter] == 'Quinto' || $data[$counter] == 'Sexto'){
                                $this->Cell($w[0],5,utf8_decode(''),1,0,'L');
                                $this->Cell(20,5,utf8_decode(''),1,0,'C');
                                $this->Cell(50,5,utf8_decode(''),1,0,'C');
                                $this->Cell($w[2],5,utf8_decode(''),'R',1,'C');
                            }else{
                            
                                if($data[$counter] == 'FORMACIÓN PARA EL TRABAJO'){
                                    $this->AddFont('trebuc','B','trebuc.php');
                                    $this->SetFont('trebuc','B',7);
                                    $this->Cell($w[0],5,utf8_decode($data[$counter++]),1,0,'C');
                                    $this->AddFont('trebuc','B','trebuc.php');
                                    $this->SetFont('trebuc','B',9);
                                    $this->Cell(20,5,utf8_decode($data[$counter++]),1,0,'C');
                                    $this->Cell(50,5,utf8_decode(''),1,0,'C');
                                    $this->Cell($w[2],5,utf8_decode(''),'R',1,'C');
                                }
                                else if($j == 0){
                                    $this->Cell($w[0],5,utf8_decode($data[$counter++]),1,0,'L');
                                    $this->Cell(20,5,utf8_decode($data[$counter++]),1,0,'C');
                                    $this->Cell(50,5,utf8_decode($data[$counter++]),1,0,'C');
                                    $this->Cell($w[2],5,utf8_decode('FECHA DE TERMINO'),1,1,'C');
                                }else if($j == 1){
                                    $this->Cell($w[0],5,utf8_decode($data[$counter++]),1,0,'L');
                                    $this->Cell(20,5,utf8_decode($data[$counter++]),1,0,'C');
                                    $this->Cell(50,5,utf8_decode($data[$counter++]),1,0,'C');
                                    $this->Cell($w[2],5,utf8_decode(transformDate($semester_date[$counter_date++])),1,1,'C');
                                }else if($j == 2){
                                    $this->Cell($w[0],5,utf8_decode($data[$counter++]),1,0,'L');
                                    $this->Cell(20,5,utf8_decode($data[$counter++]),1,0,'C');
                                    $this->Cell(50,5,utf8_decode($data[$counter++]),1,0,'C');
                                    $this->Cell($w[2],5,utf8_decode('SELLO'),'RLT',1,'C');
                                }else{
                                    $this->Cell($w[0],5,utf8_decode($data[$counter++]),1,0,'L');
                                    $this->Cell(20,5,utf8_decode($data[$counter++]),1,0,'C');
                                    $this->Cell(50,5,utf8_decode($data[$counter++]),1,0,'C');
                                    $this->Cell($w[2],5,utf8_decode(''),'LR',1,'C');
                                }
                            }
                    }
                    $this->Cell(190,0,utf8_decode(''),'T',1,'C');
                    if($i == 2){
                        
                        $this->AddPage();
                        $this->Ln(40);
                    }else{
                        $this->Ln(7);
                    }
                }
            }
                // Closing line
                $this->Cell(array_sum($w),0,'',0, 1,'','T');
            }
            
            function Header()
            {

                global $name;
                global $curp;
                global $semester_date;
                
                if($this->PageNo() === 1){
                global $input_search_student_id_record;
                // Logo
                //$this->Image('logo.png',10,6,30);
                // Arial bold 15
                $this->Image('../img/originalLogo.png',10,6,15,0,'');
                $this->AddFont('trebucbd','B','trebucbd.php');
                $this->AddFont('trebuc','','trebuc.php');
                $this->SetFont('trebucbd','B',7);
                $this->Cell(190,3,utf8_decode('PREPARATORIA PARTICULAR'),0,1,'C');
                $this->Cell(190,3,utf8_decode('"INSTITUTO DE CIENCIAS Y LETRAS NAYAR"'),0,0,'C');
                $this->Cell(190,3,utf8_decode('SECRETARIA  DE EDUCACIÓN EN EL ESTADO'),0,0,'C');
                $this->Cell(-10,3,utf8_decode('DIRECCIÓN DE EDUCACIÓN MEDIA SUPERIOR'),0,1,'C');
                $this->Cell(190,3,utf8_decode('CLAVE: 16PBH3684F     ACUERDO NO. BACH091102'),0,1,'C');
                $this->Cell(190,3,utf8_decode('GALEANA No. 20    CP 60050    TELEFONO: 01(452)523 1927'),0,1,'C');
                $this->Cell(190,-3,utf8_decode('____________________________________________________________________________________________________'),0,'B','C');
                $this->Ln(5);
                $this->AddFont('trebuc','','trebuc.php');
                $this->SetFont('trebuc','',9);
                $this->Cell(50,5,utf8_decode(''),0,0,'C');
                $this->Cell(90,5,utf8_decode('NOMBRE DEL ALUMNO'),1,0,'L');
                $this->Cell(50,5,utf8_decode('No. DE CONTROL'),1,1,'C');
                $this->Cell(50,5,utf8_decode(''),0,0,'C');
                $this->Cell(90,5,utf8_decode($name),1,0,'C');
                $this->Cell(50,5,utf8_decode($input_search_student_id_record),1,1,'C');
                $this->Cell(50,5,utf8_decode(''),0,0,'C');
                $this->Cell(70,5,utf8_decode('CURP: '.$curp),1,0,'C');
                $this->Cell(70,5,utf8_decode('FECHA DE INICIO: '.transformDate($semester_date[0])),1,1,'C');
                $this->Ln(5);
                $this->Cell(50,5,utf8_decode(''),0,0,'C');
                $this->Cell(140,5,utf8_decode('OBSERVACIONES:'),1,1,'L');
                $this->Cell(50,5,utf8_decode(''),0,0,'C');
                $this->Cell(140,5,utf8_decode(''),1,1,'C');
                $this->Cell(50,5,utf8_decode('FOTO'),0,0,'C');
                $this->Cell(140,5,utf8_decode(''),1,1,'C');
                $this->Ln(10);

            }
        }
    
            // Page footer
            function Footer()
            {
                // Position at 1.5 cm from bottom
                $this->SetY(-15);
                // Arial italic 8
                $this->AddFont('trebuc','','trebuc.php');
                $this->SetFont('trebuc','',9);
                // Page number
                $this->Cell(0,10,'pagina '.$this->PageNo().' de {nb}',0,0,'C');
            }
            }
            // Instanciation of inherited class
            $pdf = new PDF('P','mm', 'A4');
            $header = array('NOMBRE DE LAS MATERIAS', 'EVALUACIONES', 'FECHA DE INICIO');
            $data = $pdf->LoadData($subjects_list);
            $pdf->AliasNbPages();
            $pdf->AddPage();
            $pdf->AddFont('trebuc','','trebuc.php');
                $pdf->SetFont('trebuc','',9);
                $pdf->FancyTable($header,$data);
                $pdf->Cell(150,10,utf8_decode('PROMEDIO GENERAL'),0,0, 'R');
                $pdf->Cell(10,10,utf8_decode(''),0,0);
                $pdf->Cell(20,10,utf8_decode($average),1,0, 'C');
                $pdf->Cell(10,10,utf8_decode(''),0,0);
                $pdf->Output();

        }else if($kind_student_historial_record === 'student_general_historial'){

        $input_search_student_id_record = filter_var($_POST['input_search_student_id_record'], FILTER_SANITIZE_STRING);
        $kind_student_historial_record = filter_var($_POST['kind_student_historial_record'], FILTER_SANITIZE_STRING);
           
        $name = '';
        $curp = '';
        $last_name = '';
        $school_year = '';
        $subjects_count = 0;
        $average = 0;
        $subjects_list = '';
        $subjects_total = 0;
        $subjects_summary = 0;
        $semester_student = '';
        $training_to_work = 0;
        $school_cicle = array();

        $listA = '';
        $listB = '';

        $limitA = 0;
        $limitB = 0;


        require_once 'db_connexion.php';

        try {
            $sql = "SELECT alumnos.alumno_matricula,
        alumnos.nombre,
        alumnos.apellidos,
        alumnos.curp,
        alumnos.estatus,
        asignar_alumno.id_asignar_alumno,
        asignar_alumno.alumno_ciclo_escolar,
        boleta.id_semestre_alumno,
        boleta.materia_clave,
        boleta.alumno_matricula,
        boleta.estado_parcial_1,
        boleta.estado_parcial_2,
        boleta.estado_parcial_final,
        boleta.promedio,
        boleta.regularizacion_1,
        boleta.regu_1_estado,
        boleta.regularizacion_2,
        boleta.regu_2_estado,
        materias.materia_clave,
        materias.materia_nombre,
        materias.materia_orden,
        materias.materia_semestre,
        materias.materia_bachillerato,
        materias.tipo_materia,
        materias.creditos
        FROM alumnos
        INNER JOIN asignar_alumno
        INNER JOIN boleta
        INNER JOIN materias
        WHERE alumnos.alumno_matricula = '$input_search_student_id_record'
        AND
        asignar_alumno.alumno_matricula = '$input_search_student_id_record'
        AND
        asignar_alumno.id_asignar_alumno = boleta.id_semestre_alumno
        AND
        boleta.materia_clave = materias.materia_clave 
        AND
        materias.tipo_materia != 'Extra'
        AND
        alumnos.estatus != 'baja'
        ORDER BY FIELD (materia_semestre, 'Primero', 'Segundo', 'Tercero', 'Cuarto', 'Quinto', 'Sexto') ASC, materia_bachillerato, materia_orden";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
               // if($row['tipo_materia'] === 'Extra'){

                //}else{
                    $assigned_score = '';
                    $name = $row['nombre'].' '.$row['apellidos'];
                    $curp = $row['curp'];

                    if($semester_student != $row['materia_semestre']){
                        $semester_student = $row['materia_semestre'];
                        $subjects_list .= $semester_student.';';
                        $training_to_work = 0;
                        if($semester_student == 'Primero' || $semester_student == 'Tercero' || $semester_student == 'Quinto'){
                             array_push($school_cicle, $row['alumno_ciclo_escolar']);
                             $limitA++;
                             
                        }else if($semester_student == 'Segundo' || $semester_student == 'Cuarto' || $semester_student == 'Sexto'){
                            $limitB++;
                        }
                    }

                     if(($row['tipo_materia'] == 'Normal' && (int)$row['estado_parcial_1'] == 1 && (int)$row['estado_parcial_2'] == 1 && (int)$row['estado_parcial_final'] == 1) && (int)$row['regu_1_estado'] != 1 && (int)$row['regu_2_estado'] != 1){
                        $assigned_score = (float)$row['promedio'];
                        $subjects_count++;
                        $subjects_total++;
                        $subjects_list .= $row['materia_nombre'].';'.$assigned_score.';'.$row['creditos'].';';
                        $subjects_summary += $assigned_score;
                     }else if($row['tipo_materia'] == 'Normal' && ((int)$row['estado_parcial_1'] != 1 || (int)$row['estado_parcial_2'] != 1 || (int)$row['estado_parcial_final'] != 1)){
                        $assigned_score = 'NA';
                        $subjects_total++;
                        $subjects_list .= $row['materia_nombre'].';'.$assigned_score.';'.$row['creditos'].';';
                     }else if(($row['tipo_materia'] == 'Formación para el Trabajo' && (int)$row['estado_parcial_1'] == 1 && (int)$row['estado_parcial_2'] == 1 && (int)$row['estado_parcial_final'] == 1) && (int)$row['regu_1_estado'] != 1 && (int)$row['regu_2_estado'] != 1){
                        if($training_to_work == 0){
                            $subjects_list .= 'FORMACIÓN PARA EL TRABAJO'.';--;';
                            $training_to_work = 1;
                        }
                        $assigned_score = (float)$row['promedio'];
                        $subjects_count++;
                        $subjects_total++;
                        $subjects_list .= $row['materia_nombre'].';'.$assigned_score.';'.$row['creditos'].';';
                        $subjects_summary += $assigned_score;
                     }else if($row['tipo_materia'] == 'Formación para el Trabajo' && ((int)$row['estado_parcial_1'] != 1 || (int)$row['estado_parcial_2'] != 1 || (int)$row['estado_parcial_final'] != 1)){
                        if($training_to_work == 0){
                            $subjects_list .= 'FORMACIÓN PARA EL TRABAJO'.';--;';
                            $training_to_work = 1;
                        }
                        $assigned_score = 'NA';
                        $subjects_total++;
                        $subjects_list .= $row['materia_nombre'].';'.$assigned_score.';'.$row['creditos'].';';
                     }else if(($row['tipo_materia'] == 'Normal' && (int)$row['estado_parcial_1'] == 1 && (int)$row['estado_parcial_2'] == 1 && (int)$row['estado_parcial_final'] == 1) && (int)$row['regu_1_estado'] == 1 && (int)$row['regu_2_estado'] != 1){
                        $assigned_score = (float)$row['regularizacion_1'];
                        $subjects_count++;
                        $subjects_total++;
                        $subjects_list .= $row['materia_nombre'].';'.(float)$row['regularizacion_1'].';'.$row['creditos'].';';
                        $subjects_summary += $assigned_score;
                     }else if(($row['tipo_materia'] == 'Formación para el Trabajo' && (int)$row['estado_parcial_1'] == 1 && (int)$row['estado_parcial_2'] == 1 && (int)$row['estado_parcial_final'] == 1) && (int)$row['regu_1_estado'] == 1 && (int)$row['regu_2_estado'] != 1){
                        if($training_to_work == 0){
                            $subjects_list .= 'FORMACIÓN PARA EL TRABAJO'.';--;';
                            $training_to_work = 1;
                        }
                        $assigned_score = (float)$row['regularizacion_1'];
                        $subjects_count++;
                        $subjects_total++;
                        $subjects_list .= $row['materia_nombre'].(float)$row['regularizacion_1'].';'.$row['creditos'].';';
                        $subjects_summary += $assigned_score;
                     }else if(($row['tipo_materia'] == 'Normal' && (int)$row['estado_parcial_1'] == 1 && (int)$row['estado_parcial_2'] == 1 && (int)$row['estado_parcial_final'] == 1) && ((int)$row['regu_1_estado'] == 1 || (int)$row['regu_1_estado'] != 1) && (int)$row['regu_2_estado'] == 1){
                        $assigned_score = (float)$row['regularizacion_2'];
                        $subjects_count++;
                        $subjects_total++;
                        $subjects_list .= $row['materia_nombre'].';'.(float)$row['regularizacion_2'].';'.$row['creditos'].';';
                        $subjects_summary += $assigned_score;
                     }else if(($row['tipo_materia'] == 'Formación para el Trabajo' && (int)$row['estado_parcial_1'] == 1 && (int)$row['estado_parcial_2'] == 1 && (int)$row['estado_parcial_final'] == 1) && ((int)$row['regu_1_estado'] == 1 || (int)$row['regu_1_estado'] != 1) && (int)$row['regu_2_estado'] == 1){
                        if($training_to_work == 0){
                            $subjects_list .= 'FORMACIÓN PARA EL TRABAJO'.';--;';
                            $training_to_work = 1;
                        }
                        $assigned_score = (float)$row['regularizacion_2'];
                        $subjects_count++;
                        $subjects_total++;
                        $subjects_list .= $row['materia_nombre'].';'.(float)$row['regularizacion_2'].';'.$row['creditos'].';';
                        $subjects_summary += $assigned_score;
                     }
                
            }
            
            if ($subjects_count != 0){
                $average = bcdiv(($subjects_summary / $subjects_count), '1', 2);
            }
            
            $conn->close();
        } catch (Exception $e) {
            $response = array(
                'error' => $e->getMessage(),
            );
        }

            class PDF extends FPDF
            {

                function LoadData($lists)
                {
                    global $subjects_total;
                    $listA = '';
                    $listB = '';
                    $controller = FALSE;
                    $principal_array = array();
                    $extra_var = '';
                        $grades = explode(';',trim($lists));
                        foreach($grades as $subjects){
                            if($subjects == 'Primero' || $subjects == 'Tercero' || $subjects == 'Quinto'){
                                $controller = TRUE;
                            }else if($subjects == 'Segundo' || $subjects == 'Cuarto' || $subjects == 'Sexto'){
                                $controller = FALSE;
                            }
                            if($controller == TRUE){
                                $listA .= $subjects.';';
                            }else{
                                $listB .= $subjects.';';
                            }
                        }
                        //echo $listA;
                        $listSplitA = explode(';',trim($listA));
                        $listSplitB = explode(';',trim($listB));
                        $list_array_A = array_filter($listSplitA);
                        $list_array_B = array_filter($listSplitB);
                            $principal_array[0] = explode(';',trim($listA));
                            $principal_array[1] = explode(';',trim($listB));
                            $final_array = array_filter($principal_array);
                    return $final_array;
                }

                function FancyTable($data)
            {
                // Colors, line width and bold font
                $this->SetFillColor(255,255,255);
                $this->SetTextColor(0);
                //$this->SetDrawColor(128,0,0);
                //$this->SetLineWidth(.3);
                $this->AddFont('trebuc','B','trebuc.php');
                $this->SetFont('trebuc','B',9);

                
                // Header
                //$w = array(70, 20, 50, 50);
                $w = array(70, 70, 50);
                
                //$this->Ln();
                // Color and font restoration
                $this->SetFillColor(224,235,255);
                $this->SetTextColor(0);
                $this->SetFont('','');
                // Data
                $fill = false;
                $counter = 0;
                for($fill = 0; $fill <8; $fill++){
                    array_push($data[0], '');
                    array_push($data[1], '');
                }
                
                global $school_cicle;
                if(count($data[0]) != 0){
                $counter = 0;
                global $limitA;
                global $limitB;
                $this->AddFont('trebucbd','B','trebucbd.php');
                $this->SetFont('trebucbd','B',12);
                $this->Cell(190,0,utf8_decode('HISTORIAL ACADEMICO'),0,1,'C');
                $this->Ln(8);
                for($i = 0; $i < $limitA; $i++){
                        $this->SetFont('trebucbd','B',10);
                        $this->Cell(190,0,utf8_decode('CICLO ESCOLAR '.$school_cicle[$i]),0,1,'C');
                        $this->Ln(2);
                        $this->SetFont('trebuc','',9);
                        $this->Cell(95,5,utf8_decode('SEMESTRE '.strtoupper($data[0][$counter++])),1,1,'C');
                        $this->Cell(60,5,utf8_decode('ASIGNATURA'),1,0,'C');
                        $this->Cell(20,5,utf8_decode('CALIFICACIÓN'),1,0,'C');
                        $this->Cell(15,5,utf8_decode('CREDITOS'),1,1,'C');
                        if($i == 0){
                            for($j = 0; $j < 10; $j++)
                            {
                                if($data[0][$counter] == 'Primero' || $data[0][$counter] == 'Tercero' 
                                || $data[0][$counter] == 'Quinto'){
                                    break;
                                }else{
        
                                        if($data[0][$counter] == 'FORMACIÓN PARA EL TRABAJO'){
                                            $this->AddFont('trebuc','B','trebuc.php');
                                            $this->SetFont('trebuc','B',7);
                                            $this->Cell(60,5,utf8_decode($data[0][$counter++]),1,0,'C');
                                            $this->AddFont('trebuc','B','trebuc.php');
                                            $this->SetFont('trebuc','B',9);
                                            $this->Cell(35,5,utf8_decode($data[0][$counter++]),1,1,'C');
                                        }else {
                                            $this->AddFont('trebuc','B','trebuc.php');
                                            $this->SetFont('trebuc','B',9);
                                            $this->Cell(60,5,utf8_decode($data[0][$counter++]),1,0,'L');
                                            $this->Cell(20,5,utf8_decode($data[0][$counter++]),1,0,'C');
                                            $this->Cell(15,5,utf8_decode($data[0][$counter++]),1,1,'C');
                                        }    
                                }
                            }
                        }else{
                            for($j = 0; $j < 10; $j++)
                            {
                                if($data[0][$counter] == 'Primero' || $data[0][$counter] == 'Tercero' 
                                || $data[0][$counter] == 'Quinto'){
                                    break;
                                }else{
        
                                        if($data[0][$counter] == 'FORMACIÓN PARA EL TRABAJO'){
                                            $this->AddFont('trebuc','B','trebuc.php');
                                            $this->SetFont('trebuc','B',7);
                                            $this->Cell(60,5,utf8_decode($data[0][$counter++]),1,0,'C');
                                            $this->AddFont('trebuc','B','trebuc.php');
                                            $this->SetFont('trebuc','B',9);
                                            $this->Cell(35,5,utf8_decode($data[0][$counter++]),1,1,'C');
                                        }else {
                                            $this->AddFont('trebuc','B','trebuc.php');
                                            $this->SetFont('trebuc','B',9);
                                            $this->Cell(60,5,utf8_decode($data[0][$counter++]),1,0,'L');
                                            $this->Cell(20,5,utf8_decode($data[0][$counter++]),1,0,'C');
                                            $this->Cell(15,5,utf8_decode($data[0][$counter++]),1,1,'C');
                                        }    
                                }
                            }
                        }
                    
                    $this->Ln(10);
                }
            }
            if(count($data[1]) != 0){
                $counter = 0;
                $this->SetY(57);
                
                for($i = 0; $i < $limitB; $i++){
                    $this->Ln(2);
                    $this->SetX(107);
                    
                        $this->Cell(95,5,utf8_decode('SEMESTRE '.strtoupper($data[1][$counter++])),1,1,'C');
                        $this->SetX(107);
                        $this->Cell(60,5,utf8_decode('ASIGNATURA'),1,0,'C');
                        $this->Cell(20,5,utf8_decode('CALIFICACIÓN'),1,0,'C');
                        $this->Cell(15,5,utf8_decode('CREDITOS'),1,1,'C');
                        
                        if($i == 0){
                            for($j = 0; $j < 10; $j++)
                            {
                                if($data[1][$counter] == 'Segundo' || $data[1][$counter] == 'Cuarto' 
                                || $data[1][$counter] == 'Sexto'){
                                    break;
                                }else{
        
                                        if($data[1][$counter] == 'FORMACIÓN PARA EL TRABAJO'){
                                            $this->SetX(107);
                                            $this->AddFont('trebuc','B','trebuc.php');
                                            $this->SetFont('trebuc','B',7);
                                            $this->Cell(60,5,utf8_decode($data[1][$counter++]),1,0,'C');
                                            $this->AddFont('trebuc','B','trebuc.php');
                                            $this->SetFont('trebuc','B',9);
                                            $this->Cell(35,5,utf8_decode($data[1][$counter++]),1,1,'C');
                                        }else {
                                            $this->SetX(107);
                                            $this->AddFont('trebuc','B','trebuc.php');
                                            $this->SetFont('trebuc','B',9);
                                            $this->Cell(60,5,utf8_decode($data[1][$counter++]),1,0,'L');
                                            $this->Cell(20,5,utf8_decode($data[1][$counter++]),1,0,'C');
                                            $this->Cell(15,5,utf8_decode($data[1][$counter++]),1,1,'C');
                                        }    
                                }
                            
                            }
                        }else{
                            for($j = 0; $j < 10; $j++)
                            {
                                if($data[1][$counter] == 'Segundo' || $data[1][$counter] == 'Cuarto' 
                                || $data[1][$counter] == 'Sexto'){
                                    break;
                                }else{
        
                                        if($data[1][$counter] == 'FORMACIÓN PARA EL TRABAJO'){
                                            $this->SetX(107);
                                            $this->AddFont('trebuc','B','trebuc.php');
                                            $this->SetFont('trebuc','B',7);
                                            $this->Cell(60,5,utf8_decode($data[1][$counter++]),1,0,'C');
                                            $this->AddFont('trebuc','B','trebuc.php');
                                            $this->SetFont('trebuc','B',9);
                                            $this->Cell(35,5,utf8_decode($data[1][$counter++]),1,1,'C');
                                        }else {
                                            $this->SetX(107);
                                            $this->AddFont('trebuc','B','trebuc.php');
                                            $this->SetFont('trebuc','B',9);
                                            $this->Cell(60,5,utf8_decode($data[1][$counter++]),1,0,'L');
                                            $this->Cell(20,5,utf8_decode($data[1][$counter++]),1,0,'C');
                                            $this->Cell(15,5,utf8_decode($data[1][$counter++]),1,1,'C');
                                        }    
                                    //}
                                    
                                }
                            
                            }
                        }
                    
                    $this->Ln(10);
                }
            }
                // Closing line
                $this->Cell(array_sum($w),0,'',0, 1,'','T');
            }
            
            // Page header
            function Header()
            {
                if($this->PageNo() === 1){
                global $input_search_student_id_record;
                global $name;
                global $curp;
                // Logo
                // Arial bold 15
                //$this->AddFont('trebuc','','trebuc.php');
                $this->Image('../img/originalLogo.png',10,6,15,0,'');
                $this->AddFont('trebucbd','B','trebucbd.php');
                $this->AddFont('trebuc','','trebuc.php');
                
                $this->SetFont('trebucbd','B',7);
                $this->Cell(190,3,utf8_decode('PREPARATORIA PARTICULAR'),0,1,'C');
                $this->Cell(190,3,utf8_decode('"INSTITUTO DE CIENCIAS Y LETRAS NAYAR"'),0,0,'C');
                $this->Cell(190,3,utf8_decode('SECRETARIA  DE EDUCACIÓN EN EL ESTADO'),0,0,'C');
                $this->Cell(-10,3,utf8_decode('DIRECCIÓN DE EDUCACIÓN MEDIA SUPERIOR'),0,1,'C');
                $this->Cell(190,3,utf8_decode('CLAVE: 16PBH3684F     ACUERDO NO. BACH091102'),0,1,'C');
                $this->Cell(190,3,utf8_decode('GALEANA No. 20    CP 60050    TELEFONO: 01(452)523 1927'),0,1,'C');
                $this->Cell(190,-3,utf8_decode('____________________________________________________________________________________________________'),0,'B','C');
                $this->Ln(5);
                $this->SetFont('trebucbd','B',8);
                $this->Cell(13,3,utf8_decode('NOMBRE:'),0,0,'L');
                $this->SetFont('trebuc','',8);
                $this->Cell(177,3,utf8_decode($name),0,1,'L');
                $this->SetFont('trebucbd','B',8);
                $this->Cell(9,3,utf8_decode('CURP:'),0,0,'L');
                $this->SetFont('trebuc','',8);
                $this->Cell(181,3,utf8_decode($curp),0,1,'L');
                $this->SetFont('trebucbd','B',8);
                $this->Cell(24,3,utf8_decode('No. DE CONTROL:'),0,0,'L');
                $this->SetFont('trebuc','',8);
                $this->Cell(166,3,utf8_decode($input_search_student_id_record),0,1,'L');
                $this->SetFont('trebucbd','B',8);
                $this->Cell(31,3,utf8_decode('FECHA DE EXPEDICIÓN:'),0,0,'L');
                $this->SetFont('trebuc','',8);
                $this->Cell(159,3,utf8_decode(getActualDate()),0,1,'L');
                $this->Ln(10);
            }
        }
    
            // Page footer
            function Footer()
            {
                // Position at 1.5 cm from bottom
                $this->SetY(-30);
                    $this->AddFont('trebuc','','trebuc.php');
                    $this->SetFont('trebuc','',10);
                    $this->Cell(190,10,'',0,1,'C');
                    $this->Cell(190,5,'________________________________________',0,1,'C');
                    $this->Cell(190,5,utf8_decode('I.C. CATALINA BETZABÉ MIRANDA DOMÍNGUEZ'),0,1,'C');
                    $this->Cell(190,5,utf8_decode('DIRECTORA DEL PLANTEL'),0,1,'C');
            }
            }
    
            // Instanciation of inherited class
            $pdf = new PDF('P','mm', array(210,297));
            $data = $pdf->LoadData($subjects_list);
            $pdf->AliasNbPages();
            $pdf->AddPage();
            $pdf->AddFont('trebucbd','B','trebucbd.php');
            $pdf->SetFont('trebucbd','B',8);
            $pdf->FancyTable($data);
            $pdf->AddFont('trebucbd','B','trebucbd.php');
            $pdf->SetFont('trebucbd','B',9);
            $pdf->Cell(190,20,utf8_decode('PROMEDIO GENERAL: '.$average),0,0,'L');
            $pdf->Output();
        }    
        
    }else if($report_card_kind === 'students_key'){
        $school_year_group_card = filter_var($_POST['school_year_group_card'], FILTER_SANITIZE_STRING);
        $group_letter_card = filter_var($_POST['group_letter_card'], FILTER_SANITIZE_STRING);
        $semester_group_card = filter_var($_POST['semester_group_card'], FILTER_SANITIZE_STRING);
        $kind_subjects_group_card = filter_var($_POST['kind_subjects_group_card'], FILTER_SANITIZE_STRING);


        $students_list = '';
        $subjects = array('NOMBRE');

        require_once 'db_connexion.php';

        try {
            $sql = "SELECT alumnos.alumno_matricula,
            alumnos.nombre,
            alumnos.apellidos,
            alumnos.estatus,
            usuarios.usuario_matricula,
            usuarios.usuario_contrasena,
            usuarios.usuario_tipo,
            asignar_alumno.id_asignar_alumno,
            asignar_alumno.alumno_matricula,
            asignar_alumno.alumno_semestre,
            asignar_alumno.alumno_grupo,
            asignar_alumno.alumno_bachillerato,
            asignar_alumno.alumno_ciclo_escolar
            FROM alumnos
            INNER JOIN usuarios
            INNER JOIN asignar_alumno
            WHERE
            alumnos.alumno_matricula = asignar_alumno.alumno_matricula
            AND
            asignar_alumno.alumno_semestre = '$semester_group_card'
            AND
            asignar_alumno.alumno_grupo = '$group_letter_card'
            AND
            asignar_alumno.alumno_bachillerato = '$kind_subjects_group_card'
            AND
            asignar_alumno.alumno_ciclo_escolar = '$school_year_group_card'
            AND
            alumnos.alumno_matricula = usuarios.usuario_matricula
            AND
            usuarios.usuario_tipo = 'student' 
            AND 
            alumnos.estatus != 'baja'
            ORDER BY alumnos.alumno_matricula ASC";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                $students_list .= $row['usuario_matricula'].';'.$row['usuario_contrasena'].';'.$row['apellidos'].' '.$row['nombre'].';';
                }
            $conn->close();
        } catch (Exception $e) {
                echo $e->getMessage();
        }

        class PDF extends FPDF
        {

            function Footer()
                {
                    // Page footer
                    $this->SetY(-30);
                    $this->AddFont('trebuc','','trebuc.php');
                    $this->SetFont('trebuc','',10);
                    $this->Cell(190,15,'',0,1,'C');
                    $this->Cell(0,0,utf8_decode(getActualDate()),0,0,'L');
                    $this->Cell(0,0,utf8_decode('Página '.$this->PageNo()),0,0,'R');
                }

                function LoadData($lists)
                {
                        
                        $group = explode(';',trim($lists));
                        $cleaned_list = array_filter($group, "strlen");
                    //echo implode(',',$group);

                    return $cleaned_list;
                }

                function FancyTable($header, $data)
                {
                    // Colors, line width and bold font
                    $this->SetFillColor(255,255,255);
                    $this->SetTextColor(0);
                    //$this->SetDrawColor(128,0,0);
                    //$this->SetLineWidth(.3);
                    $this->AddFont('trebuc','B','trebuc.php');
                    $this->SetFont('trebuc','B',10);
    
                    
                    // Header
                    $this->Cell(30,10,utf8_decode('Clave/Matricula'),1,0,'C',true);
                    $this->Cell(30,10,utf8_decode('Contraseña'),1,0,'C',true);
                    $this->Cell(130,10,utf8_decode('Nombre'),1,0,'C',true);
                    $this->Ln();
                    // Color and font restoration
                    $this->SetFillColor(224,235,255);
                    $this->SetTextColor(0);
                    $this->SetFont('','');
                    // Data
                    $fill = false;
                    $counter = 0;
                    if(count($data) != 1){
                    for($i = 0; $i < (count($data) / 3); $i++)
                    {
                        $this->Cell(30,9,utf8_decode($data[$counter++]),0,0,'C',$fill);
                        $this->Cell(30,9,utf8_decode($data[$counter++]),0,0,'C',$fill);
                        $this->Cell(130,9,utf8_decode($data[$counter++]),0,0,'L',$fill);
                        $this->Ln();
                        $fill = !$fill;
                    }
                }
                    // Closing line
                    //$this->Cell(array_sum($w),0,'',0, 1,'','T');
                }
    }
        $pdf = new PDF('P','mm', 'A4');
        $data = $pdf->LoadData($students_list);
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->AddFont('trebucbd','B','trebucbd.php');
        $pdf->SetFont('trebucbd','B',8);
        $pdf->Image('../img/originalLogo.png',15,8,25,0,'');
            $pdf->SetFont('trebucbd','B',10);
            $pdf->Cell(25,55,utf8_decode('CLAVES DE ACCESO Y CONTRASEÑAS POR GRUPO'),0,1);
            $pdf->SetFont('trebucbd','B',9);
            $pdf->Cell(17,-44,utf8_decode('SEMESTRE: '),0,0);
            $pdf->AddFont('trebuc','','trebuc.php');
            $pdf->SetFont('trebuc','',9);
            $pdf->Cell(75,-44,utf8_decode($semester_group_card),0,1,'L');
            $pdf->SetFont('trebucbd','B',9);
            $pdf->Cell(12,55,utf8_decode('GRUPO: '),0,0);
            $pdf->SetFont('trebuc','',9);
            $pdf->Cell(50,55,utf8_decode($group_letter_card),0,1,'L');
            $pdf->SetFont('trebucbd','B',9);
            $pdf->Cell(24,-44,utf8_decode('BACHILLERATO: '),0,0);
            $pdf->SetFont('trebuc','',9);
            $pdf->Cell(50,-44,utf8_decode($kind_subjects_group_card),0,1,'L');
            $pdf->SetFont('trebucbd','B',9);
            $pdf->Cell(11,55,utf8_decode('CICLO: '),0,0);
            $pdf->SetFont('trebuc','',9);
            $pdf->Cell(50,55,utf8_decode($school_year_group_card),0,1,'L');
            $pdf->SetFont('trebuc','',9);
            $pdf->Cell(50,-15,utf8_decode(''),0,1,'L');
            $pdf->FancyTable($subjects,$data);
        $pdf->Output();
    }else if($report_card_kind === 'teachers_key'){

        $teachers_list = '';
        $subjects = array('NOMBRE');

        require_once 'db_connexion.php';

        try {
            $sql = "SELECT maestros.profesor_matricula,
            maestros.profesor_nombre,
            usuarios.usuario_matricula,
            usuarios.usuario_contrasena,
            usuarios.usuario_tipo
            FROM maestros
            INNER JOIN usuarios
            WHERE
            maestros.profesor_matricula = usuarios.usuario_matricula
            AND
            usuarios.usuario_tipo = 'teacher' ORDER BY maestros.profesor_matricula ASC";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                $teachers_list .= $row['usuario_matricula'].';'.$row['usuario_contrasena'].';'.$row['profesor_nombre'].';';
                }
            $conn->close();
        } catch (Exception $e) {
                echo $e->getMessage();
        }

        class PDF extends FPDF
        {

            function Footer()
                {
                    // Page footer
                    $this->SetY(-30);
                    $this->AddFont('trebuc','','trebuc.php');
                    $this->SetFont('trebuc','',10);
                    $this->Cell(190,15,'',0,1,'C');
                    $this->Cell(0,0,utf8_decode(getActualDate()),0,0,'L');
                    $this->Cell(0,0,utf8_decode('Página '.$this->PageNo()),0,0,'R');
                }

                function LoadData($lists)
                {
                        
                        $group = explode(';',trim($lists));
                        $cleaned_list = array_filter($group, "strlen");
                    //echo implode(',',$group);

                    return $cleaned_list;
                }

                function FancyTable($header, $data)
                {
                    // Colors, line width and bold font
                    $this->SetFillColor(255,255,255);
                    $this->SetTextColor(0);
                    //$this->SetDrawColor(128,0,0);
                    //$this->SetLineWidth(.3);
                    $this->AddFont('trebuc','B','trebuc.php');
                    $this->SetFont('trebuc','B',10);
    
                    
                    // Header
                    $this->Cell(30,10,utf8_decode('Clave/Matricula'),1,0,'C',true);
                    $this->Cell(30,10,utf8_decode('Contraseña'),1,0,'C',true);
                    $this->Cell(130,10,utf8_decode('Nombre'),1,0,'C',true);
                    $this->Ln();
                    // Color and font restoration
                    $this->SetFillColor(224,235,255);
                    $this->SetTextColor(0);
                    $this->SetFont('','');
                    // Data
                    $fill = false;
                    $counter = 0;
                    if(count($data) != 1){
                    for($i = 0; $i < (count($data) / 3); $i++)
                    {
                        $this->Cell(30,9,utf8_decode($data[$counter++]),0,0,'C',$fill);
                        $this->Cell(30,9,utf8_decode($data[$counter++]),0,0,'C',$fill);
                        $this->Cell(130,9,utf8_decode($data[$counter++]),0,0,'L',$fill);
                        $this->Ln();
                        $fill = !$fill;
                    }
                }
                    // Closing line
                    //$this->Cell(array_sum($w),0,'',0, 1,'','T');
                }
    }
        $pdf = new PDF('P','mm', 'A4');
        $data = $pdf->LoadData($teachers_list);
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->AddFont('trebucbd','B','trebucbd.php');
        $pdf->SetFont('trebucbd','B',8);
        $pdf->Image('../img/originalLogo.png',15,8,25,0,'');
            $pdf->SetFont('trebucbd','B',10);
            $pdf->Cell(25,55,utf8_decode('CLAVES DE ACCESO Y CONTRASEÑAS PARA PROFESORES'),0,1);
            $pdf->Cell(50,-15,utf8_decode(''),0,1,'L');
            $pdf->AddFont('trebuc','','trebuc.php');
            $pdf->FancyTable($subjects,$data);
        $pdf->Output();
    }

    function getActualDate(){
        date_default_timezone_set('America/Mexico_City');
        $date = new DateTime('NOW');
        $year = $date->format('Y');
        $month = $date->format('m');
        $day = $date->format('d');
        setlocale(LC_ALL,"es_ES");
        return strftime('%A, ').$day.' de '.strftime('%B del ').$year;
    }
    function transformDate($get_date){
        $num = date("j", strtotime($get_date));
        $anno = date("Y", strtotime($get_date));
        $mes = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
        $mes = $mes[(date('m', strtotime($get_date))*1)-1];
        return $num.' de '.$mes.' del '.$anno;
    }   