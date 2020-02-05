<?php
    if(isset($_POST['send'])){
        if(!empty($_POST['problem_name']) && !empty($_POST['problem_mail']) && !empty($_POST['problem_subject']) && !empty($_POST['problem_answer'])){
            $problem_name = filter_var($_POST['problem_name'], FILTER_SANITIZE_STRING);
            $problem_mail = filter_var($_POST['problem_mail'], FILTER_SANITIZE_STRING);
            $problem_subject = filter_var($_POST['problem_subject'], FILTER_SANITIZE_STRING);
            $problem_answer = filter_var($_POST['problem_answer'], FILTER_SANITIZE_STRING);
            $mail_school = 'PREPARATORIA NAYAR (REPORTE DE PROBLEMAS)';
            $subjectResponse = 'PROBLEMAS AL INICIO DE SESIÓN EN LA PLATAFORMA DE LA PREPARATORIA NAYAR';
            $response="";
                //para el envío en formato HTML
                $headers  = "MIME-Version: 1.0\r\n";
                $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
                //dirección del remitente
                $headers .= "From: ".$mail_school."\r\n";
                $headers .= "X-Mailer: PHP/".phpversion();
                //Una Dirección de respuesta, si queremos que sea distinta que la del remitente
                //$headers .= "Reply-To: ".$Email."\r\n";
                $message ="SOLICITUD DE ASISTENCIA: ".$problem_subject."\r\n";
                $message .="NOMBRE DEL SOLICITANTE: ".$problem_name."\r\n";
                $message .="RESPUESTA: ".$problem_answer."\r\n";
                $mail = mail($problem_mail,$subjectResponse,$message,$headers);
                if($mail){
                    echo $problem_mail;
                    echo $subjectResponse;
                    echo $message;
                    echo $headers;
                    /*echo '<script>
	                    alert("Datos guardados correctamente");
		                header("Location: reporteLoggin.php");
		                </script>';*/
                }
        }
    }
        