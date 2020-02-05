(function () {
    'use strict';
    document.addEventListener('DOMContentLoaded', function () {

        //Scroll
        var windowHeight = $(window).height();
        var blackLayerEnabled = false;
        var bar = $('.bar').innerHeight();
        var barHeight = $('.navegacion').innerHeight();
        //tabla

        $(window).scroll(function(){
            var scroll = $(window).scrollTop();
            if(scroll > bar && blackLayerEnabled == false){
                $('.navegacion').addClass('fixed');
                $('body').css({'margin-top': barHeight+'px'});
            }else{
                $('.navegacion').removeClass('fixed');
                $('body').css({'margin-top': '0px'});
            }
        });

        function checkExistMails(){
            $.ajax({
                url: 'php/model_school.php', //This is the current doc
                type: 'POST',
                dataType: 'text', // add json datatype to get json
                data: { 'action': 'COMPROBAR_NUEVOS_CORREOS'},
                success: function (data) {
                    const result = JSON.parse(data);
                    if (result.message === 'right') {
                        mailNotification();
                        $('#notification_icon').show();
                        $('#notification_window_area').show();
                    } else if(result.message === 'empty'){
                        $('#notification_icon').css('display', 'none');
                        $('#notification_window_area').css('display', 'none');
                    }

                }
            });
        }

        function checkTableSize(){
            var tableHeight = $('.div_tabla').innerHeight();
            const recommendedTableHeight = 500;
            if(tableHeight >= recommendedTableHeight){
                $('.div_tabla').addClass('div_tabla_height');
            }

        }

        function mailNotification(){
            $('.administration_window').hover(vanishNotification, showNotification);
    
            function vanishNotification(){
                $('#notification_icon').css('display', 'none');
            }
            function showNotification(){
                $('#notification_icon').show();
            }
        }

        //loggin
        if ($('body').data('title') === 'login_body') {
            var user_login = document.querySelector('#user');
            var password_login = document.querySelector('#password');
            var error_div_login = document.querySelector('#error_div');
            var button_login = document.querySelector('#button_login');
            const show_pass = document.querySelector('#show_pass');
            let shown_pass = false;

            eventListeners();

            function eventListeners() {
                show_pass.addEventListener('click', showPassWord);
            }

            user_login.addEventListener('blur', function () {
                if (this.value == '') {
                    error_div_login.style.display = 'block';
                    error_div_login.innerHTML = "Este campo es obligatorio";
                    this.style.border = '1px solid red';
                    error_div_login.style.backgroundColor = 'red';
                    error_div_login.style.borderRadius = '5px';
                    error_div_login.style.color = 'white';
                    error_div_login.style.border = '1px solid red';
                }
            });
            user_login.addEventListener('click', function () {
                if (this.value == '') {
                    this.style.border = 'none';
                }
                if (this.value == '' && password_login.value != '') {
                    error_div_login.style.display = 'none';
                }
            });

            password_login.addEventListener('blur', function () {
                if (this.value == '') {
                    error_div_login.style.display = 'block';
                    error_div_login.innerHTML = "Este campo es obligatorio";
                    this.style.border = '1px solid red';
                    error_div_login.style.backgroundColor = 'red';
                    error_div_login.style.borderRadius = '5px';
                    error_div_login.style.color = 'white';
                    error_div_login.style.border = '1px solid red';
                }
            });

            password_login.addEventListener('click', function () {
                if (this.value == '') {
                    this.style.border = 'none';
                }
                if (this.value == '' && user_login.value != '') {
                    error_div_login.style.display = 'none';
                }
            });

            button_login.addEventListener('click', function () {
                if (user_login.value == '') {
                    error_div_login.style.display = 'block';
                    error_div_login.innerHTML = "Este campo es obligatorio";
                    user_login.style.border = '1px solid red';
                    error_div_login.style.backgroundColor = 'red';
                    error_div_login.style.borderRadius = '5px';
                    error_div_login.style.color = 'white';
                    error_div_login.style.border = '1px solid red';
                }
                if (password_login.value == '') {
                    error_div_login.style.display = 'block';
                    error_div_login.innerHTML = "Este campo es obligatorio";
                    password_login.style.border = '1px solid red';
                    error_div_login.style.backgroundColor = 'red';
                    error_div_login.style.borderRadius = '5px';
                    error_div_login.style.color = 'white';
                    error_div_login.style.border = '1px solid red';
                }
                if (user_login.value != '' && password_login != '') {
                    compareUser();
                }
            });

            $('body').keydown(function (e) {
                if (e.keyCode == 13) {
                    compareUser();
                }
            });

            function showPassWord(){
                if(shown_pass == false){
                    shown_pass = true;
                    $('#fa-eye').show();
                    $('#fa-eye-slash').hide();
                    $('#password').get(0).type = 'text';
                }else{
                    shown_pass = false;
                    $('#fa-eye').hide();
                    $('#fa-eye-slash').show();
                    $('#password').get(0).type = 'password';
                }
            }

            function compareUser() {
                const input_user_login = $('#user').val(), input_pass_login = $('#password').val();
                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': 'BUSCAR_USUARIO_LOGIN', 'user_login': input_user_login, 'password_login': input_pass_login },
                    success: function (data) {
                        const result = JSON.parse(data);
                        if (result.message == 'right') {
                            swal({
                                title: "¡LOGIN CORRECTO!",
                                text: "Redireccionando automaticamente",
                                icon: "success",
                                timer: 2000,
                                button: false
                            }).then(resultado => {
                                window.location.href = result.link;
                            })
                        } else if (result.message == 'empty') {
                            swal("¡ERROR!", "Uno o ambos campos son incorrectos", "error");
                        }else {
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                    }
                });
            }
        }

        //correo
        if ($('body').data('title') == 'error_report') {
            const name = document.querySelector('#name'),
                    mail = document.querySelector('#email'),
                    subject = document.querySelector('#subject'),
                    description = document.querySelector('#description'),
                    button_send = document.querySelector('#button_send'),
                    button_go_back = document.querySelector('#button_go_back');
            
                    eventListeners();

            function eventListeners(){
                button_send.addEventListener('click', validationSaveFunction);
                button_go_back.addEventListener('click', goBackPrincipalPage);
                description.addEventListener('input', countNumberCharacters);
            }
            function goBackPrincipalPage(){
                window.location.href = 'principal.php';
            }
            function countNumberCharacters(){
                const limit = 800; 
                if(description.value.length >= limit){
                    description.value=description.value.substring(0,limit);
                }
            }
            
            function validationSaveFunction(){
                if (name.value == '' || mail.value == '' || description.value == '' || subject.value == ''){
                    swal("¡ADVERTENCIA!", "Todos los campos son obligatorios", "warning");
                }else{
                    if(mail.value.indexOf('@') != -1){
                        sendDescriptionError();
                    }else{
                        swal("¡ADVERTENCIA!", "El correo insertado no es valido", "warning");
                    }
                }
                
            }
            function sendDescriptionError(){
                
                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': 'MANDAR_CORREO_ERROR', 'name' : name.value, 'mail' : mail.value, 'description' : description.value, 'subject':subject.value},
                    success: function (data) {
                        const result = JSON.parse(data);
                        if (result.message == 'right') {
                            swal({
                                title: "¡CORRECTO!",
                                text: "El administrador recibirá su petición y responderá lo mas pronto posible. Redireccionando automaticamente.",
                                icon: "success",
                                timer: 3000,
                                button: false
                            }).then(resultado => {
                                window.location.href = 'principal.php';
                            });
                        } else if(result.message == 'wrong'){
                            swal("¡ERROR!", "Hubo un problema con el envio del mensaje. Intentelo de nuevo.", "error");
                        }else {
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                    }
                });
            }
        }
        //concentrado de calificaciones
        if ($('body').data('title') == 'index') {
            checkExistMails();
        }
        //concentrado de calificaciones
        if ($('body').data('title') == 'body_concentrated_ratings') {
            
            
            const search_teacher_id = document.querySelector('#input_search_teacher_id'),
                save_button = document.querySelector('#save_button'),
                semester_student_grades = document.querySelector('#semester_student'),
                group = document.querySelector('#student_group'),
                school_cycle = document.querySelector('#school_year_student'),
                semesters = document.querySelector('#semester_student'),
                optional_classes = document.querySelector('#kind_student_subjects'),
                subject = document.querySelector('#student_subject'),
                cleanup_button = document.querySelector('#cleanup_button'),
                partial1 = document.querySelector('#partial1'),
                partial2 = document.querySelector('#partial2'),
                final_partial = document.querySelector('#final_partial'),
                all_partials = document.querySelector('#all_partials'),
                input_regularization_score = document.querySelector('#input_regularization_score'),
                button_cancel = document.querySelector('#button_cancel'),
                button_save = document.querySelector('#button_save');

            const numberColumns = 14;

            eventListeners();
            callTeacherID();
            getSchoolYearNGeneration();
            callAllAvalibleSubjects();
            checkExistMails();

            function eventListeners() {
                save_button.addEventListener('click', saveStudentGrades);
                search_teacher_id.addEventListener('input', gradesCallTeacherName);
                semester_student_grades.addEventListener('input', semesterStudentGradesSwitch);
                group.addEventListener('input', gradesCallTeacherName);
                school_cycle.addEventListener('input', callTeacherStudents);
                semesters.addEventListener('input', gradesCallTeacherName);
                optional_classes.addEventListener('input', gradesCallTeacherName);
                subject.addEventListener('input', callTeacherStudents);
                cleanup_button.addEventListener('click', cleanUpForm);
                partial1.addEventListener('click', partial1Switch);
                partial2.addEventListener('click', partial2Switch);
                final_partial.addEventListener('click', partialFinalSwitch);
                all_partials.addEventListener('click', allPartials);
                input_regularization_score.addEventListener('input', checkScore);
                button_cancel.addEventListener('click', hidePopUp);
                button_save.addEventListener('click', getBackValues);
                }

            function cleanUpForm() {
                document.querySelector('form').reset();
                $('#student_subject').html('');
                $('#teachers_name').text('');
                semesterStudentGradesSwitch();
                callTeacherStudents();
                callAllAvalibleSubjects();
                $('#teachers_name').html('No Asignado');
            }

            function partial1Switch() {
                const rows = $('#number_of_rows').text();
                let array = [];
                if ($('#partial1').is(':checked')) {
                    for (var i = 0; i < rows; i++) {
                        array[i] = [];
                        for (var j = 2; j < 4; j++) {
                            $('#col-' + i + '-' + j).prop('disabled', true).css('background-color', '#E6E6E6');
                        }
                    }
                }
                else {
                    for (var i = 0; i < rows; i++) {
                        array[i] = [];
                        for (var j = 2; j < 4; j++) {
                            $('#col-' + i + '-' + j).prop('disabled', false).css('background-color', '#FFFFFF');
                        }
                    }
                    $('#all_partials').prop('checked', false);
                }
            }

            function partial2Switch() {
                const rows = $('#number_of_rows').text();
                let array = [];
                if ($('#partial2').is(':checked')) {
                    for (var i = 0; i < rows; i++) {
                        array[i] = [];
                        for (var j = 4; j < 6; j++) {
                            $('#col-' + i + '-' + j).prop('disabled', true).css('background-color', '#E6E6E6');
                        }
                    }
                }
                else {
                    for (var i = 0; i < rows; i++) {
                        array[i] = [];
                        for (var j = 4; j < 6; j++) {
                            $('#col-' + i + '-' + j).prop('disabled', false).css('background-color', '#FFFFFF');
                        }
                    }
                    $('#all_partials').prop('checked', false);
                }
            }

            function partialFinalSwitch() {
                const rows = $('#number_of_rows').text();
                let array = [];
                if ($('#final_partial').is(':checked')) {
                    for (var i = 0; i < rows; i++) {
                        array[i] = [];
                        for (var j = 6; j < 8; j++) {
                            $('#col-' + i + '-' + j).prop('disabled', true).css('background-color', '#E6E6E6');
                        }
                    }
                }
                else {
                    for (var i = 0; i < rows; i++) {
                        array[i] = [];
                        for (var j = 6; j < 8; j++) {
                            $('#col-' + i + '-' + j).prop('disabled', false).css('background-color', '#FFFFFF');
                        }
                    }
                    $('#all_partials').prop('checked', false);
                }
            }

            function allPartials() {
                if ($('#all_partials').is(':checked')) {
                    $('#partial1').prop('checked', true);
                    $('#partial2').prop('checked', true);
                    $('#final_partial').prop('checked', true);
                    partial1Switch();
                    partial2Switch();
                    partialFinalSwitch();
                }
                else {
                    $('#partial1').prop('checked', false);
                    $('#partial2').prop('checked', false);
                    $('#final_partial').prop('checked', false);
                    partial1Switch();
                    partial2Switch();
                    partialFinalSwitch();
                }
            }


            function callTeacherID() {
                const action = 'LLAMAR_PROFESORES_ID';
                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': action },
                    success: function (data) {
                        const result = JSON.parse(data);
                        if(result.message == 'right'){
                            $('#search_teacher_id').html(result.content);
                        }else if(result.message == 'empty'){
                        }else {
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                    }
                });
            }
            function gradesCallTeacherName() {
                const action = 'LLAMAR_NOMBRE_PROFESOR',
                    search_teacher_id = document.querySelector('#input_search_teacher_id').value;
                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': action, 'teacher_id': search_teacher_id },
                    success: function (data) {
                            const result = JSON.parse(data);
                        if(result.message == 'right'){
                            callTeacherSubjects(result.content);
                        }else if(result.message == 'empty'){
                            callTeacherSubjects(result.content);
                        }else {
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                    }
                });

            }

            function semesterStudentGradesSwitch() {
                const semester = document.querySelector('#semester_student').value,
                    mySelect = document.querySelector('#kind_student_subjects'),
                    semester_group_select = document.querySelector('#student_group');
                
                $('#kind_student_subjects').html('');
                $('#student_group').html('');
                if (semester == 'Primero' || semester == 'Segundo' || semester == 'Tercero' || semester == 'Cuarto') {
                    var myOption1 = document.createElement("option");
                    myOption1.setAttribute("value", "Tronco Común");
                    myOption1.textContent = "Tronco Común";
                    mySelect.appendChild(myOption1);

                    var semester_option_A = document.createElement("option");
                    semester_option_A.setAttribute("value", "A");
                    semester_option_A.textContent = "A";
                    semester_group_select.appendChild(semester_option_A);
                    var semester_option_B = document.createElement("option");
                    semester_option_B.setAttribute("value", "B");
                    semester_option_B.textContent = "B";
                    semester_group_select.appendChild(semester_option_B);
                    var semester_option_C = document.createElement("option");
                    semester_option_C.setAttribute("value", "C");
                    semester_option_C.textContent = "C";
                    semester_group_select.appendChild(semester_option_C);
                    var semester_option_D = document.createElement("option");
                    semester_option_D.setAttribute("value", "D");
                    semester_option_D.textContent = "D";
                    semester_group_select.appendChild(semester_option_D);
                    var semester_option_E = document.createElement("option");
                    semester_option_E.setAttribute("value", "E");
                    semester_option_E.textContent = "E";
                    semester_group_select.appendChild(semester_option_E);

                } else if (semester == 'Quinto' || semester == 'Sexto') {
                    var myOption2 = document.createElement("option");
                    myOption2.setAttribute("value", "Económico - Administrativo");
                    myOption2.textContent = "Económico - Administrativo";
                    mySelect.appendChild(myOption2);
                    var myOption3 = document.createElement("option");
                    myOption3.setAttribute("value", "Físico - Matemático");
                    myOption3.textContent = "Físico - Matemático";
                    mySelect.appendChild(myOption3);
                    var myOption4 = document.createElement("option");
                    myOption4.setAttribute("value", "Humanidades Y Ciencias Sociales");
                    myOption4.textContent = "Humanidades Y Ciencias Sociales";
                    mySelect.appendChild(myOption4);
                    var myOption5 = document.createElement("option");
                    myOption5.setAttribute("value", "Químico - Biológico");
                    myOption5.textContent = "Químico - Biológico";
                    mySelect.appendChild(myOption5);
                    
                    var semester_option_A = document.createElement("option");
                    semester_option_A.setAttribute("value", "A");
                    semester_option_A.textContent = "A";
                    semester_group_select.appendChild(semester_option_A);
                }
            }

            function getSchoolYearNGeneration() {
                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': 'LLAMAR_FECHA', 'date': 'none', 'message': 'CICLO_ESCOLAR' },
                    success: function (data) {
                        const result = JSON.parse(data);
                        if(result.message == 'right'){
                            $('#school_year_student').html(result.content);
                        }else if(result.message == 'empty'){
                            swal("¡CUIDADO!", "No se encontraron datos relacionados a la consulta solicitada. Intentelo de nuevo.", "warning");
                        }else {
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                    }
                });
                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': 'LLAMAR_FECHA', 'date': 'none', 'message': 'GENERACION' },
                    success: function (data) {
                        const result = JSON.parse(data);
                        if(result.message == 'right'){
                            $('#school_generation_student').html(result.content);
                        }else if(result.message == 'empty'){
                            swal("¡CUIDADO!", "No se encontraron datos relacionados a la consulta solicitada. Intentelo de nuevo.", "warning");
                        }else {
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                    }
                });
            }

            function callTeacherStudents() {
                const action = 'LLAMAR_ESTUDIANTES_PROFESOR';
                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': action, 'group': group.value, 'school_cycle': school_cycle.value, 'semesters': semesters.value, 'optional_classes': optional_classes.value, 'subject': subject.value },
                    success: function (data) {
                        const result = JSON.parse(data);
                        if(result.message == 'right'){
                            $('#tipo_materia').val(result.tipo_materia);
                            $('#tbody_all_students_grades').html(result.students_row);
                            $('#number_of_rows').html(result.number_rows);
                            if (result.check_parcial_1 == '1') {
                                $('#partial1').prop('checked', true);
                            } else {
                                $('#partial1').prop('checked', false);
                            }
                            if (result.check_parcial_2 == '1') {
                                $('#partial2').prop('checked', true);
                            } else {
                                $('#partial2').prop('checked', false);
                            }
                            if (result.check_parcial_final == '1') {
                                $('#final_partial').prop('checked', true);
                            } else {
                                $('#final_partial').prop('checked', false);
                            }
                            if (result.check_parcial_final == '1' && result.check_parcial_1 == '1' && result.check_parcial_2 == '1') {
                                $('#all_partials').prop('checked', true);
                            } else {
                                $('#all_partials').prop('checked', false);
                            }
                            setEventListenersToStudents();
                        }else if(result.message == 'empty'){
                            $('#tbody_all_students_grades').html('');
                            $('#number_of_rows').html('0');
                            $('#partial1').prop('checked', false);
                            $('#partial2').prop('checked', false);
                            $('#final_partial').prop('checked', false);
                            $('#all_partials').prop('checked', false);
                        }else {
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                    }
                });
            }
            function setEventListenersToStudents() {
                const numberRows = $('#number_of_rows').text();
                let array = [];
                for (var i = 0; i < numberRows; i++) {
                    array[i] = [];
                    var increment = 2
                    for (var j = 2; j <= 6; j += increment) {
                            array[i][j] = $('#col-' + i + '-' + j + '');
                            $(array[i][j]).on('input', function () {
                                makeAverage($(this).attr('id'));
                            });
                        if(j == 7){
                            increment++;
                        }
                    }
                    for (var j = 9; j <= numberColumns ; j += 4) {
                        array[i][j] = $('#col-' + i + '-' + j);
                        $(array[i][j]).on('click', function () {
                            launchPopUp($(this).attr('data-id'));
                        });
                    }
                }
            }
            function makeAverage(id) {
                const positions = id.split('-'),
                    row = positions[1];
                let actualCase = $('#' + id);
                if (actualCase.val() > 10) {
                    swal("¡CUIDADO!", "El dato insertado no debe ser mayor a 10", "warning");
                } else if (actualCase.val() < 0) {
                    swal("¡CUIDADO!", "El dato insertado no debe ser menor a 0", "warning");
                } else {
                    if (!(actualCase.val() == '' || actualCase.val() == undefined || actualCase.val() == null)) {
                        let decimalSplitted = actualCase.val().toString().match(/^-?\d+(?:\.\d{0,1})?/)[0];;
                        actualCase.val(parseFloat(decimalSplitted));
                        let resultado = ($('#col-' + row + '-2').val() * 0.25) + ($('#col-' + row + '-4').val() * 0.25) + ($('#col-' + row + '-6').val() * 0.5);
                        if (resultado >= 6) {
                                $('#col-' + row + '-8').val(resultado.toFixed()).html(resultado.toFixed());
                        } else if (resultado < 6) {
                                $('#col-' + row + '-8').val(resultado).html(resultado);
                        }
                    }
                }
            }

            function launchPopUp(data_id){
                blackLayerEnabled = true;
                var control_data = data_id.split('x');
                
                $('.navegacion').removeClass('fixed');
                $('body').css({'margin-top': '0px'});
                $('.black_layer').show();
                $('.popup_regularization_grades').show();


                
                $('#input_regularization_score').val($('#col-'+control_data[0]).val());
                $('#input_regularization_date').val($('#col-'+control_data[1]).val());
                if($('#col-'+control_data[2]).val() == 0){
                    $('#score_status').val('disable');
                }else{
                    $('#score_status').val('enable');
                }
                $('#id_subject_grades_card').val(data_id);
            }

            function hidePopUp(){
                    blackLayerEnabled = false;
                    $('.black_layer').hide();
                    $('.popup_regularization_grades').hide();
                    $('.navegacion').addClass('fixed');
                    $('body').css({'margin-top': barHeight+'px'});
            }

            function checkScore(){

                var actualScore = $('#input_regularization_score');
                if (actualScore.val() > 10) {
                    swal("¡CUIDADO!", "El dato insertado no debe ser mayor a 10", "warning");
                    actualScore.val('');
                } else if (actualScore.val() < 0) {
                    swal("¡CUIDADO!", "El dato insertado no debe ser menor a 0", "warning");
                    actualScore.val('');
                } 
            }

            function getBackValues(){
                if($('#input_regularization_score').val() == ''){
                    swal("¡CUIDADO!", "Debe de asignarse una calificación adecuada para poder guardar", "warning");
                }else if($('#input_regularization_date').val() == ''){
                    swal("¡CUIDADO!", "Debe de asignarse una fecha para poder guardar", "warning");
                }else{
                
                let decimalSplitted = $('#input_regularization_score').val().toString().match(/^-?\d+(?:\.\d{0,1})?/)[0];;
                let informat = parseFloat(decimalSplitted);
                let total = 0;
                if(informat >= 6){
                    total = informat.toFixed();
                }else{
                    total = informat;
                }

                var control_data = $('#id_subject_grades_card').val().split('x');
                var score_position = control_data[0].split('-');
                var date_position = control_data[1].split('-');

                if((score_position[1] == 10 && date_position[1] == 11) && ($('#score_status').val() === 'enable')){
                    $('#col-'+score_position[0]+'-9').removeClass('status_cog_red');
                    $('#col-'+score_position[0]+'-9').addClass('status_cog_green');
                    $('#col-'+score_position[0]+'-10').val(total);
                }else if((score_position[1] == 10 && date_position[1] == 11) && ($('#score_status').val() === 'disable')){
                    $('#col-'+score_position[0]+'-9').removeClass('status_cog_green');
                    $('#col-'+score_position[0]+'-9').addClass('status_cog_red');
                    $('#col-'+score_position[0]+'-10').val(total);
                }if((score_position[1] == 14 && date_position[1] == 15) && ($('#score_status').val() === 'enable')){
                    $('#col-'+score_position[0]+'-13').removeClass('status_cog_red');
                    $('#col-'+score_position[0]+'-13').addClass('status_cog_green');
                    $('#col-'+score_position[0]+'-14').val(total);
                }else if((score_position[1] == 14 && date_position[1] == 15) && ($('#score_status').val() === 'disable')){
                    $('#col-'+score_position[0]+'-13').removeClass('status_cog_green');
                    $('#col-'+score_position[0]+'-13').addClass('status_cog_red');
                    $('#col-'+score_position[0]+'-14').val(total);
                }
                if($('#score_status').val() == 'enable'){
                    $('#col-'+control_data[2]).val(1);
                   }else{
                    $('#col-'+control_data[2]).val(0);
                   }
                
                $('#col-'+control_data[1]).val($('#input_regularization_date').val());
                hidePopUp();
                }
            }

            function callTeacherSubjects(data) {
                if (data == null || data == 'undefined' || data == '') {
                    callAllAvalibleSubjects();
                    $('#teachers_name').text('No Asignado');
                }
                else {
                    callTeachersAvalibleSubjects();
                    $('#teachers_name').text(data);
                    
                }
            }

            function callTeachersAvalibleSubjects() {
                const action = 'LLAMAR_MATERIAS_CLAVE_PROFESOR_CALIFICACIONES',
                teacher_id = document.querySelector('#input_search_teacher_id').value,
                student_group = document.querySelector('#student_group').value,
                semester = document.querySelector('#semester_student').value,
                kind_subjects = document.querySelector('#kind_student_subjects').value;
            $.ajax({
                url: 'php/model_school.php', //This is the current doc
                type: 'POST',
                dataType: 'text', // add json datatype to get json
                data: { 'action': action, 'teacher_id': teacher_id, 'semester': semester, 'kind_subjects': kind_subjects, 'student_group': student_group },
                success: function (data) {
                    const result = JSON.parse(data);
                    if(result.message == 'right'){
                        $('#student_subject').html(result.content);
                        callTeacherStudents();
                    }else if(result.message == 'empty'){
                        $('#student_subject').html('');
                        callTeacherStudents();
                        swal("¡CUIDADO!", "No se encontraron datos relacionados a la consulta solicitada. Intentelo de nuevo.", "warning");
                    }else {
                        swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                    }
                }
            });
            }

            function callAllAvalibleSubjects(){
                const action = 'LLAMAR_MATERIAS_SEMESTRE_CALIFICACIONES',
                    semester = document.querySelector('#semester_student').value,
                    kind_subjects = document.querySelector('#kind_student_subjects').value;
                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': action, 'semester': semester, 'kind_subjects': kind_subjects},
                    success: function (data) {
                    const result = JSON.parse(data);
                    if(result.message == 'right'){
                        $('#student_subject').html(result.content);
                        callTeacherStudents();
                    }else if(result.message == 'empty'){
                        swal("¡CUIDADO!", "No se encontraron datos relacionados a la consulta solicitada. Intentelo de nuevo.", "warning");
                    }else {
                        swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                    }
                    }
                });
            }

            function saveStudentGrades() {
                if($('#student_subject').val() == '' || $('#student_subject').val() == null || $('#student_subject').val() == undefined){
                    swal("¡ADVERTENCIA!", "Debe llenar todos los campos correctamente para continuar", "warning");
                }else{
                    const numberRows = $('#number_of_rows').text();
                    let array = [];
                    for (var i = 0; i < numberRows; i++) {
                        array[i] = [];
                        for (var j = 0; j < 8; j++) {
                            if ($('#col-' + i + '-' + j).val() == null || $('#col-' + i + '-' + j).val() == undefined) {
                                swal("¡CUIDADO!", "Uno o mas campos contiene datos erroneos", "warning");
                                return 0;
                            }
                        }
                        for (var j = 0; j < 20; j++) {
                            if (j == 8) {
                                array[i][j] = $('#col-' + i + '-' + j).text();
                            }else if (j == 9 && j == 13){

                            }
                            else if (j == 17) {
                                if ($('#partial1').is(':checked')) {
                                    array[i][j] = '1';
                                } else {
                                    array[i][j] = '0';
                                }
                            }
                            else if (j == 18) {
                                if ($('#partial2').is(':checked')) {
                                    array[i][j] = '1';
                                } else {
                                    array[i][j] = '0';
                                }
                            }
                            else if (j == 19) {
                                if ($('#final_partial').is(':checked')) {
                                    array[i][j] = '1';
                                } else {
                                    array[i][j] = '0';
                                }
                            } else {
                                array[i][j] = document.querySelector('#col-' + i + '-' + j).value;
                            }
                        }
                    }
                    if($('#teachers_name').text() == 'No Asignado'){
                        $.ajax({
                            url: 'php/model_school.php', //This is the current doc
                            type: 'POST',
                            dataType: 'text', // add json datatype to get json
                            data: { 'action': 'GUARDAR_CALIFICACIONES_SIN_PROFESOR', 'number_rows': numberRows, 'array': JSON.stringify(array), 'group': group.value, 'school_cycle': school_cycle.value, 'semesters': semesters.value, 'subject': subject.value },
                            success: function (data) {
                                const result = JSON.parse(data);
                                if (result.message === 'right') {
                                    swal("¡ÉXITO!", "Datos guardados correctamente", "success");
                                } else if(result.message == 'wrong'){
                                    swal("¡ERROR!", "Hubo un problema con el guardado de las calificaciones. Intentelo de nuevo.", "error");
                                }else {
                                    swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                                }
                            }
                        });
                    }else{
                        $.ajax({
                            url: 'php/model_school.php', //This is the current doc
                            type: 'POST',
                            dataType: 'text', // add json datatype to get json
                            data: { 'action': 'GUARDAR_CALIFICACIONES', 'number_rows': numberRows, 'array': JSON.stringify(array), 'search_teacher_id': search_teacher_id.value, 'group': group.value, 'school_cycle': school_cycle.value, 'semesters': semesters.value, 'subject': subject.value },
                            success: function (data) {
                                const result = JSON.parse(data);
                                if (result.message === 'right') {
                                    swal("¡ÉXITO!", "Datos guardados correctamente", "success");
                                } else if(result.message == 'wrong'){
                                    swal("¡ERROR!", "Hubo un problema con el guardado de las calificaciones. Intentelo de nuevo.", "error");
                                }else {
                                    swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                                }
                            }
                        });
                    }
                    
                }
                
            }
        }
        //Plataforma abierta
        if ($('body').data('title') == 'body_open_grades_platforms') {
            
            const DatesList = document.querySelector('#tbody_dates'),
            season_grades_start = document.querySelector('#season_grades_start'),
            season_grades_end = document.querySelector('#season_grades_end'),
            activity_season = document.querySelector('#activity_season'),
            semester_odd_start = document.querySelector('#semester_odd_start'),
            semester_odd_end = document.querySelector('#semester_odd_end'),
            semester_pair_start = document.querySelector('#semester_pair_start'),
            semester_pair_end = document.querySelector('#semester_pair_end'),
            school_year = document.querySelector('#school_year'),
            save_button = document.querySelector('#save_button');

            setSavedDates();
            eventListeners();
            getSchoolYear();
            getTodayDate();
            getDateList();
            checkExistMails();

            function eventListeners() {
                if (DatesList) {
                    DatesList.addEventListener('click', deleteDate);
                }
                season_grades_start.addEventListener('input', saveAvaibleSeasonDate);
                season_grades_end.addEventListener('input', saveAvaibleSeasonDate);
                activity_season.addEventListener('input', saveAvaibleSeasonDate);
                save_button.addEventListener('click', checkDateExists);
            }

            function setSavedDates(){
                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': 'COLOCAR_FECHAS_TEMPORADA_CALIFICACION'},
                    success: function (data) {
                        var result = JSON.parse(data);
                        if(result.message = 'right'){
                            season_grades_start.value = result.result_query[0];
                            season_grades_end.value = result.result_query[1];
                            activity_season.value = result.result_query[2];
                            checkSeasonDateStatus();
                        }else if(result.message == 'empty'){
                            swal("¡CUIDADO!", "No se encontraron datos relacionados a la consulta solicitada, intentelo de nuevo.", "warning");
                        }else {
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                    }
                }).done(function () {
                }).fail(function () {
                })
                    .always(function () {
                    });
            }

            function saveAvaibleSeasonDate(){
                $('#span_season_grades').html('Inactivo');
                $('#span_season_grades').css({'color':'red'});
                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': 'GUARDAR_FECHAS_TEMPORADA_CALIFICACION', 'startDate':  season_grades_start.value, 'endDate':season_grades_end.value, 'activity_season' : activity_season.value},
                    success: function (data) {
                        var result = JSON.parse(data);
                        if(result.message = 'right'){
                            checkSeasonDateStatus();
                        }else if(result.message == 'wrong'){
                            swal("¡ERROR!", "Hubo un problema con el guardado de las fechas. Intentelo de nuevo.", "error");
                        }else {
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                    }
                }).done(function () {
                }).fail(function () {
                })
                    .always(function () {
                    });
            }
            function checkSeasonDateStatus(){
                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': 'VERIFICAR_FECHAS_TEMPORADA_CALIFICACION', 'startDate':  season_grades_start.value, 'endDate':season_grades_end.value},
                    success: function (data) {
                        var result = JSON.parse(data);
                        if(result.message = 'right'){
                            if(result.date_status == 'true'){
                                $('#span_season_grades').html('Activo');
                                $('#span_season_grades').css({'color':'#31B404'});
                            }else{
                                $('#span_season_grades').html('Inactivo');
                                $('#span_season_grades').css({'color':'red'});
                            }
                        }else if(result.message == 'empty'){
                            swal("¡CUIDADO!", "No se encontraron datos relacionados a la consulta solicitada. Intentelo de nuevo.", "warning");
                        }else {
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                    }
            });
        }

            function getSchoolYear() {
                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': 'LLAMAR_FECHA', 'date': 'none', 'message': 'CICLO_ESCOLAR' },
                    success: function (data) {
                        const result = JSON.parse(data);
                        if(result.message == 'right'){
                            $('#school_year').html(result.content);
                        }else if(result.message == 'empty'){
                            swal("¡CUIDADO!", "No se encontraron datos relacionados a la consulta solicitada. Intentelo de nuevo.", "warning");
                        }else {
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                    }
                });
            }

            function getTodayDate(){
                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': 'OBTENER_FECHA_ACTUAL'},
                    success: function (data) {
                        var result = JSON.parse(data);
                        if(result.message = 'right'){
                            semester_odd_start.value = result.content;
                            semester_odd_end.value = result.content;
                            semester_pair_start.value = result.content;
                            semester_pair_end.value = result.content;
                        }else if(result.message == 'wrong'){
                            swal("¡ERROR!", "Hubo un problema al tratar de consultar la fecha. Intentelo de nuevo.", "error");
                        }else {
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                    }
            });
        }

        function checkDateExists(){
            $.ajax({
                url: 'php/model_school.php', //This is the current doc
                type: 'POST',
                dataType: 'text', // add json datatype to get json
                data: { 'action': 'COMPROBAR_EXISTENCIA_FECHA', 'date': school_year.value },
                success: function (data) {
                    const result = JSON.parse(data);
                    if (result.message == 'right') {

                        swal({
                            title: "¡ADVERTENCIA!",
                            text: "La fecha seleccionada ya ha sido agregada con anterioridad, ¿desea actualizarla?",
                            icon: "warning",
                            buttons: [
                                'No, cancelar',
                                'Si, deseo actualizarla'
                            ],
                            dangerMode: true,
                        }).then(function (isConfirm) {
                            if (isConfirm) {
                                readUpdateDate();
                            }
                        });
                    } else if (result.message == 'empty'){
                        saveDate();
                    }else{
                        swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                    }

                }
            });
        }

        function saveDate() {
            $.ajax({
                url: 'php/model_school.php', //This is the current doc
                type: 'POST',
                dataType: 'text', // add json datatype to get json
                data: { 'action': 'GUARDAR_CONTROL_FECHA', 'semester_odd_start' : semester_odd_start.value
                    , 'semester_odd_end' : semester_odd_end.value
                    , 'semester_pair_start' : semester_pair_start.value
                    , 'semester_pair_end' : semester_pair_end.value
                    , 'school_year' : school_year.value},
                success: function (data) {
                    var response = JSON.parse(data);
                    if(response.message == 'right'){
                        swal({
                            title: '¡CORRECO!',
                            text: 'Se ha guardado la fecha satisfactoriamente',
                            icon: 'success'
                        });
                        getDateList();
                    }else if(result.message == 'wrong'){
                        swal("¡ERROR!", "Hubo un problema al intenar guardar la fecha, intentelo de nuevo.", "error");
                        getDateList();
                    }else {
                        swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        getDateList();
                    }
                }
            });
        }

        function readUpdateDate() {
            $.ajax({
                url: 'php/model_school.php', //This is the current doc
                type: 'POST',
                dataType: 'text', // add json datatype to get json
                data: { 'action': 'ACTUALIZAR_CONTROL_FECHA', 'semester_odd_start' : semester_odd_start.value
                    , 'semester_odd_end' : semester_odd_end.value
                    , 'semester_pair_start' : semester_pair_start.value
                    , 'semester_pair_end' : semester_pair_end.value
                    , 'school_year' : school_year.value},
                success: function (data) {
                    var response = JSON.parse(data);
                    if(response.message == 'right'){
                        swal({
                            title: '¡CORRECO!',
                            text: 'Se ha guardado la fecha satisfactoriamente',
                            icon: 'success'
                        });
                        getDateList();
                    }else if(result.message == 'wrong'){
                        swal("¡ERROR!", "Hubo un problema al intenar actualizar la fecha, intentelo de nuevo.", "error");
                        getDateList();
                    }else {
                        swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        getDateList();
                    }

                }
            });
        }


        function deleteDate(e){
            e.preventDefault();

            if (e.target.parentElement.classList.contains('btn_delete_dates')) {
                swal({
                    title: "¡ADVERTENCIA!",
                    text: "¿Seguro que desea borrar la fecha seleccionada?",
                    icon: "warning",
                    buttons: [
                        'No, cancelar',
                        'Si, continuar'
                    ],
                    dangerMode: true,
                }).then(function (isConfirm) {
                    if (isConfirm) {
                        const id = e.target.parentElement.getAttribute('data-id');
                        $.ajax({
                            url: 'php/model_school.php', //This is the current doc
                            type: 'POST',
                            dataType: 'text', // add json datatype to get json
                            data: { 'action': 'BORRAR_FECHAS', 'id': id },
                            success: function (data) {
                                let result = JSON.parse(data);
                                if(result.message == 'right'){
                                    swal({
                                        title: '¡CORRECO!',
                                        text: 'Se ha la fecha la fecha satisfactoriamente',
                                        icon: 'success'
                                    });
                                    getDateList();
                                }else if(result.message == 'wrong'){
                                    swal({
                                        title: '¡ERROR!',
                                        text: 'Hubo un problema al intenar borrar la fecha. Intentelo de nuevo',
                                        icon: 'error'
                                    });
                                    getDateList();
                                }else{
                                    swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                                }
                            }
                        });
                    } else {
                    }
                });
            }
        }

        function getDateList(){
            $.ajax({
                url: 'php/model_school.php', //This is the current doc
                type: 'POST',
                dataType: 'text', // add json datatype to get json
                data: { 'action': 'OBTENER_LISTA_FECHAS'},
                success: function (data) {
                    var response = JSON.parse(data);
                    if(response.message == 'right'){
                        var table_content = '';
                        for(var i = 0; i < response.content.length; i++){
                            table_content += '<tr class="tr_position_center">'
                            +'<td><button data-id="'+response.content[i][0]+'" type="button" class="btn_delete_dates trash_icon td_center_content btn ">'+
                            '<i class="fas fa-trash-alt"></i></button></td>'
                            +'<td class="td_left_content">'+response.content[i][1]+'</td>'
                            +'<td class="td_left_content">'+convertDate(response.content[i][2])+'</td>'
                            +'<td class="td_left_content">'+convertDate(response.content[i][3])+'</td>'
                            +'<td class="td_left_content">'+convertDate(response.content[i][4])+'</td>'
                            +'<td class="td_left_content">'+convertDate(response.content[i][5])+'</td></tr>';
                        }
                        $('#tbody_dates').html(table_content);
                    }else if(result.message == 'empty'){
                        swal("¡CUIDADO!", "No se encontraron datos relacionados a la consulta solicitada. Intentelo de nuevo.", "warning");
                    }else {
                        swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                    }
                    
                }
            });
        }
        function convertDate(fecha){
            var date = moment(fecha);
            return date.locale('es').format('LL');
        }}

        //Reporte de registro de calificaciones
        if ($('body').data('title') === 'grades_registration_report') {
            checkExistMails();
            const search_teacher_id = document.querySelector('#input_search_teacher_id'),
                button_delete_registry = document.querySelector('#button_registries');

            eventListeners();
            callTeacherID();

            function eventListeners() {
                search_teacher_id.addEventListener('input', gradesCallTeacherName);
                button_delete_registry.addEventListener('click', callDeleteRegistries);
            }
            function callTeacherID() {
                const action = 'LLAMAR_PROFESORES_ID';
                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': action },
                    success: function (data) {
                        const result = JSON.parse(data);
                        if(result.message == 'right'){
                            $('#search_teacher_id').html(result.content);
                        }else if(result.message == 'empty'){
                        }else {
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                    }
                });
            }
            function gradesCallTeacherName() {
                const action = 'LLAMAR_NOMBRE_PROFESOR';
                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': action, 'teacher_id': search_teacher_id.value },
                    success: function (data) {
                        const result = JSON.parse(data);
                        if(result.message == 'right'){
                            $('#teachers_name').html(result.content);
                            callTeachersRegistries();
                        }else if(result.message == 'empty'){
                            $('#teachers_name').html('No Asignado');
                            callTeachersRegistries();
                        }else {
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                    }
                });

            }

            function callTeachersRegistries() {
                const action = 'LLAMAR_REGISTROS_PROFESOR';
                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': action, 'teacher_id': search_teacher_id.value },
                    success: function (data) {
                    const result = JSON.parse(data);
                    if(result.message == 'right'){
                        $('#tbody_report_grades_registry').html(result.content);
                    }else if(result.message == 'empty'){
                        $('#tbody_report_grades_registry').html('');
                    }else {
                        swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                    }
                    }
                });
            }

            function callDeleteRegistries(){
            if($('#teachers_name').text() == 'No Asignado' || $('#teachers_name').text() == ''){
                swal({
                    title: '¡ERROR!',
                    text: 'Debe ingresar un porfesor valido para borrar los registros.',
                    icon: 'error'
                });
            }else{
                deleteTeacherRegistry();
            }
        }

            function deleteTeacherRegistry() {

                swal({
                    title: "¡ADVERTENCIA!",
                    text: "¿desea todos los registros del profesor seleccionado?",
                    icon: "warning",
                    buttons: [
                        'No, cancelar',
                        'Si, deseo borrar todo'
                    ],
                    dangerMode: true,
                }).then(function (isConfirm) {
                    if (isConfirm) {
                        const action = 'BORRAR_REGISTROS_PROFESOR';
                        $.ajax({
                            url: 'php/model_school.php', //This is the current doc
                            type: 'POST',
                            dataType: 'text', // add json datatype to get json
                            data: { 'action': action, 'teacher_id': search_teacher_id.value },
                            success: function (data) {
                                const result = JSON.parse(data);
                                if (result.message == 'right') {
                                    swal({
                                        title: '¡CORRECO!',
                                        text: 'Se han borrado todos los registros satisfactoriamente',
                                        icon: 'success'
                                    });
                                    callTeachersRegistries();
                                } else if(result.message == 'wrong'){
                                    swal("¡ERROR!", "Hubo un problema con el borrado de los registros. Intentelo de nuevo.", "error");
                                }else {
                                    swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                                }
                            }
                        });
                    }
                });
            }
        }


        //Registro de alumnos

        if ($('body').data('title') === 'body_student_registration') {
            const StudentsList = document.querySelector('#students_list tbody'),
                buttonSave = document.querySelector('#button_save'),
                buttonUpdate = document.querySelector('#button_update'),
                buttonCleanupForm = document.querySelector('#button_cleanup'),
                inputSearching = document.querySelector('#search_student_information'),
                student_illness_answer_check = document.querySelector('#student_illness_answer'),
                student_special_atention_answer_check = document.querySelector('#student_special_atention_answer'),
                irregular_student_options = document.querySelector('#irregular_student_options'),
                regular_student_check = document.querySelector('#regular_student_check'),
                semester_student = document.querySelector('#semester_student'),
                updateAge = document.querySelector('#student_birthday');

            checkExistMails();
            eventListeners();
            llamadaTabla();
            getSchoolYearNGeneration();
            callStudentID();

            function eventListeners() {
                buttonSave.addEventListener('click', verifyStudentExist);
                buttonUpdate.addEventListener('click', verifyStudentUpdate);
                buttonCleanupForm.addEventListener('click', cleanForm);
                regular_student_check.addEventListener('click', regularStudentOptionSwitch);
                semester_student.addEventListener('input', semesterStudentSwitch);

                if (StudentsList) {
                    StudentsList.addEventListener('click', editDeleteStudent);
                }

                inputSearching.addEventListener('input', searchStudent);
                updateAge.addEventListener('input', getBirthday);

            }

            $('.student_illness_question_class').on('click', function () {
                if ($(this).val() == 'no') {
                    student_illness_answer_check.value = '';
                    student_illness_answer_check.style.display = 'none';
                } else {
                    student_illness_answer_check.style.display = 'block';
                    student_illness_answer_check.style.margin = '0 auto';
                }
            });
            $('.student_special_atention_question_class').on('click', function () {
                if ($(this).val() == 'no') {
                    student_special_atention_answer_check.value = '';
                    student_special_atention_answer_check.style.display = 'none';
                } else {
                    student_special_atention_answer_check.style.display = 'block';
                    student_special_atention_answer_check.style.margin = '0 auto';
                }
            });

            function regularStudentOptionSwitch() {
                if ($('.regular_student_check').is(':checked')) {
                    irregular_student_options.style.display = 'none';
                    $('#student_group').val('A');
                    $('#semester_student').val('Primero');
                    $('#kind_student_subjects').val('Tronco Común');
                    getSchoolYearNGeneration();
                }
                else {
                    irregular_student_options.style.display = 'block';
                    
                }
                semesterStudentSwitch();
            }

            function getSchoolYearNGeneration() {
                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': 'LLAMAR_FECHA', 'date': 'none', 'message': 'CICLO_ESCOLAR' },
                    success: function (data) {
                        const result = JSON.parse(data);
                        if(result.message == 'right'){
                            $('#school_year_student').html(result.content);
                        }else if(result.message == 'empty'){
                            swal("¡CUIDADO!", "No se encontraron datos relacionados a la consulta solicitada. Intentelo de nuevo.", "warning");
                        }else {
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                    }
                });
                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': 'LLAMAR_FECHA', 'date': 'none', 'message': 'GENERACION' },
                    success: function (data) {
                        const result = JSON.parse(data);
                        if(result.message == 'right'){
                            $('#school_generation_student').html(result.content);
                        }else if(result.message == 'empty'){
                            swal("¡CUIDADO!", "No se encontraron datos relacionados a la consulta solicitada. Intentelo de nuevo.", "warning");
                        }else {
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                    }
                });
            }

            function cleanForm() {
                document.querySelector('form').reset();
                callStudentID();
                student_illness_answer_check.value = '';
                student_illness_answer_check.style.display = 'none';
                student_special_atention_answer_check.value = '';
                student_special_atention_answer_check.style.display = 'none';
                $('.regular_student_check').prop('checked', true);
                regularStudentOptionSwitch();
                semesterStudentSwitch();
            }

            function semesterStudentSwitch() {
                const semester = document.querySelector('#semester_student').value,
                    mySelect = document.querySelector('#kind_student_subjects');
                $('#kind_student_subjects').html('');
                if (semester == 'Primero' || semester == 'Segundo' || semester == 'Tercero' || semester == 'Cuarto') {
                    var myOption1 = document.createElement("option");
                    myOption1.setAttribute("value", "Tronco Común");
                    myOption1.textContent = "Tronco Común";

                    mySelect.appendChild(myOption1);

                } else if (semester == 'Quinto' || semester == 'Sexto') {
                    var myOption2 = document.createElement("option");
                    myOption2.setAttribute("value", "Económico - Administrativo");
                    myOption2.textContent = "Económico - Administrativo";
                    mySelect.appendChild(myOption2);
                    var myOption3 = document.createElement("option");
                    myOption3.setAttribute("value", "Físico - Matemático");
                    myOption3.textContent = "Físico - Matemático";
                    mySelect.appendChild(myOption3);
                    var myOption4 = document.createElement("option");
                    myOption4.setAttribute("value", "Humanidades Y Ciencias Sociales");
                    myOption4.textContent = "Humanidades Y Ciencias Sociales";
                    mySelect.appendChild(myOption4);
                    var myOption5 = document.createElement("option");
                    myOption5.setAttribute("value", "Químico - Biológico");
                    myOption5.textContent = "Químico - Biológico";
                    mySelect.appendChild(myOption5);
                }
            }

            function verifyStudentExist(e){

                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': 'COMPROBAR_MATRICULA_ESTUDIANTE', 'id_student': $('#new_student_id').val()},
                    success: function (data) {
                        const result = JSON.parse(data);
                        if (result.message === 'right') {
                            swal({
                                title: "¡ADVERTENCIA!",
                                text: "Hay un registro con la misma matricula previamente guardado. ¿Desea sobreescribirlo?",
                                icon: "warning",
                                buttons: [
                                    'No, cancelar',
                                    'Si, continuar'
                                ],
                                dangerMode: true,
                            }).then(function (isConfirm) {
                                if (isConfirm) {
                                    readUpdateForm(e);
                                    
                                } else {

                                }
                            });
                        } else if(result.message === 'empty'){
                            readSaveForm(e);
                        }else{
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                    }
                });
            }

            function readSaveForm(e) {
                e.preventDefault();
                const new_student_id = document.querySelector('#new_student_id').value
                    , student_name = document.querySelector('#student_name').value
                    , student_last_name = document.querySelector('#student_last_name').value
                    , student_curp = document.querySelector('#student_curp').value
                    , student_birthplace = document.querySelector('#student_birthplace').value
                    , student_birthday = document.querySelector('#student_birthday').value
                    , student_age = document.querySelector('#student_age').value
                    , student_school_origin = document.querySelector('#student_school_origin').value
                    , student_telephone = document.querySelector('#student_telephone').value
                    , student_living_with = document.querySelector('#student_living_with').value
                    , student_neighborhood = document.querySelector('#student_neighborhood').value
                    , student_mother_name = document.querySelector('#student_mother_name').value
                    , student_mother_cellphone = document.querySelector('#student_mother_cellphone').value
                    , student_mother_telephone = document.querySelector('#student_mother_telephone').value
                    , student_father_name = document.querySelector('#student_father_name').value
                    , student_father_cellphone = document.querySelector('#student_father_cellphone').value
                    , student_father_telephone = document.querySelector('#student_father_telephone').value
                    , student_illness_question = document.querySelector('input[name="student_illness_question"]:checked').value
                    , student_illness_answer = document.querySelector('#student_illness_answer').value
                    , student_special_atention_question = document.querySelector('input[name="student_special_atention_question"]:checked').value
                    , student_special_atention_answer = document.querySelector('#student_special_atention_answer').value
                    , student_tutor_name = document.querySelector('#student_tutor_name').value
                    , student_status = document.querySelector('#student_status').value
                    , student_group = document.querySelector('#student_group').value
                    , semester_student = document.querySelector('#semester_student').value
                    , kind_student_subjects = document.querySelector('#kind_student_subjects').value
                    , school_year_student = document.querySelector('#school_year_student').value
                    , school_generation_student = document.querySelector('#school_generation_student').value
                    , save = "GUARDAR_ESTUDIANTE";

                if (new_student_id === '' ||
                    student_name === '' ||
                    student_last_name === '' ||
                    student_curp === '' ||
                    student_birthplace === '' ||
                    student_age === '' ||
                    student_school_origin === '' ||
                    student_telephone === '' ||
                    student_living_with === '' ||
                    student_neighborhood === '' ||
                    student_mother_name === '' ||
                    student_mother_cellphone === '' ||
                    student_mother_telephone === '' ||
                    student_father_name === '' ||
                    student_father_cellphone === '' ||
                    student_father_telephone === '' ||
                    student_tutor_name === '') {
                    swal("¡CUIDADO!", "Todos los campos deben llenarse correctamente", "warning");
                } else {
                    $.ajax({
                        url: 'php/model_school.php', //This is the current doc
                        type: 'POST',
                        dataType: 'text', // add json datatype to get json
                        data: {
                            'new_student_id': new_student_id,
                            'student_name': student_name,
                            'student_last_name': student_last_name,
                            'student_curp': student_curp,
                            'student_birthplace': student_birthplace,
                            'student_birthday': student_birthday,
                            'student_age': student_age,
                            'student_school_origin': student_school_origin,
                            'student_telephone': student_telephone,
                            'student_living_with': student_living_with,
                            'student_neighborhood': student_neighborhood,
                            'student_mother_name': student_mother_name,
                            'student_mother_cellphone': student_mother_cellphone,
                            'student_mother_telephone': student_mother_telephone,
                            'student_father_name': student_father_name,
                            'student_father_cellphone': student_father_cellphone,
                            'student_father_telephone': student_father_telephone,
                            'student_illness_question': student_illness_question,
                            'student_illness_answer': student_illness_answer,
                            'student_special_atention_question': student_special_atention_question,
                            'student_special_atention_answer': student_special_atention_answer,
                            'student_tutor_name': student_tutor_name,
                            'student_status': student_status,
                            'student_group': student_group,
                            'semester_student': semester_student,
                            'kind_student_subjects': kind_student_subjects,
                            'school_year_student': school_year_student,
                            'school_generation_student': school_generation_student,
                            'action': save
                        },
                        success: function (data) {
                            const result = JSON.parse(data);
                            if(result.response == 'true'){
                                swal("¡ADVERTENCIA!", "El grupo al cual intenta guardar a este alumno supera el limite actual de 25 alumnos por grupo", "warning");
                            }else{
                                if (result.message == 'right') {
                                    swal("¡EXITO!", "El registro se guardo correctamente", "success");
                                    cleanForm();
                                    llamadaTabla();
                                } else if (result.message == 'wrong'){
                                    swal("¡ERROR!", "El registro no se pudo efectuar, revise su conexión", "error");
                                }else{
                                    swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                                }
                            }
                        }
                    });
                }
            }

            function verifyStudentUpdate(e){
                const new_student_id = document.querySelector('#new_student_id').value
                    , student_name = document.querySelector('#student_name').value
                    , student_last_name = document.querySelector('#student_last_name').value
                    , student_curp = document.querySelector('#student_curp').value
                    , student_birthplace = document.querySelector('#student_birthplace').value
                    , student_age = document.querySelector('#student_age').value
                    , student_school_origin = document.querySelector('#student_school_origin').value
                    , student_telephone = document.querySelector('#student_telephone').value
                    , student_living_with = document.querySelector('#student_living_with').value
                    , student_neighborhood = document.querySelector('#student_neighborhood').value
                    , student_mother_name = document.querySelector('#student_mother_name').value
                    , student_mother_cellphone = document.querySelector('#student_mother_cellphone').value
                    , student_mother_telephone = document.querySelector('#student_mother_telephone').value
                    , student_father_name = document.querySelector('#student_father_name').value
                    , student_father_cellphone = document.querySelector('#student_father_cellphone').value
                    , student_father_telephone = document.querySelector('#student_father_telephone').value
                    , student_tutor_name = document.querySelector('#student_tutor_name').value;
                
                    if (new_student_id === '' ||
                    student_name === '' ||
                    student_last_name === '' ||
                    student_curp === '' ||
                    student_birthplace === '' ||
                    student_age === '' ||
                    student_school_origin === '' ||
                    student_telephone === '' ||
                    student_living_with === '' ||
                    student_neighborhood === '' ||
                    student_mother_name === '' ||
                    student_mother_cellphone === '' ||
                    student_mother_telephone === '' ||
                    student_father_name === '' ||
                    student_father_cellphone === '' ||
                    student_father_telephone === '' ||
                    student_tutor_name === '') {
                        swal("¡CUIDADO!", "Todos los campos deben llenarse correctamente", "warning");
                } else{
                    swal({
                        title: "¡ADVERTENCIA!",
                        text: "Se sobreescribiran los datos insertados del registro seleccionado. ¿Desea continuar?",
                        icon: "warning",
                        buttons: [
                            'No, cancelar',
                            'Si, continuar'
                        ],
                        dangerMode: true,
                    }).then(function (isConfirm) {
                        if (isConfirm) {
                            readUpdateForm(e);
                        } else {
    
                        }
                    });
                }
            }

            function readUpdateForm(e) {
                e.preventDefault();
                var regular_student = 'new_student';
                if($('.regular_student_check').is(':checked')){
                }else{
                    regular_student = 'added_student';
                }

                const new_student_id = document.querySelector('#new_student_id').value
                    , student_name = document.querySelector('#student_name').value
                    , student_last_name = document.querySelector('#student_last_name').value
                    , student_curp = document.querySelector('#student_curp').value
                    , student_birthplace = document.querySelector('#student_birthplace').value
                    , student_birthday = document.querySelector('#student_birthday').value
                    , student_age = document.querySelector('#student_age').value
                    , student_school_origin = document.querySelector('#student_school_origin').value
                    , student_telephone = document.querySelector('#student_telephone').value
                    , student_living_with = document.querySelector('#student_living_with').value
                    , student_neighborhood = document.querySelector('#student_neighborhood').value
                    , student_mother_name = document.querySelector('#student_mother_name').value
                    , student_mother_cellphone = document.querySelector('#student_mother_cellphone').value
                    , student_mother_telephone = document.querySelector('#student_mother_telephone').value
                    , student_father_name = document.querySelector('#student_father_name').value
                    , student_father_cellphone = document.querySelector('#student_father_cellphone').value
                    , student_father_telephone = document.querySelector('#student_father_telephone').value
                    , student_illness_question = document.querySelector('input[name="student_illness_question"]:checked').value
                    , student_illness_answer = document.querySelector('#student_illness_answer').value
                    , student_special_atention_question = document.querySelector('input[name="student_special_atention_question"]:checked').value
                    , student_special_atention_answer = document.querySelector('#student_special_atention_answer').value
                    , student_tutor_name = document.querySelector('#student_tutor_name').value
                    , student_group = document.querySelector('#student_group').value
                    , semester_student = document.querySelector('#semester_student').value
                    , kind_student_subjects = document.querySelector('#kind_student_subjects').value
                    , school_year_student = document.querySelector('#school_year_student').value
                    , school_generation_student = document.querySelector('#school_generation_student').value
                    , student_status = document.querySelector('#student_status').value
                    , regular_student_state = regular_student
                    , update = 'ACTUALIZAR_ESTUDIANTE';
                    
                    $.ajax({
                        url: 'php/model_school.php', //This is the current doc
                        type: 'POST',
                        dataType: 'text', // add json datatype to get json
                        data: {
                            'new_student_id': new_student_id,
                            'student_name': student_name,
                            'student_last_name': student_last_name,
                            'student_curp': student_curp,
                            'student_birthplace': student_birthplace,
                            'student_birthday': student_birthday,
                            'student_age': student_age,
                            'student_school_origin': student_school_origin,
                            'student_telephone': student_telephone,
                            'student_living_with': student_living_with,
                            'student_neighborhood': student_neighborhood,
                            'student_mother_name': student_mother_name,
                            'student_mother_cellphone': student_mother_cellphone,
                            'student_mother_telephone': student_mother_telephone,
                            'student_father_name': student_father_name,
                            'student_father_cellphone': student_father_cellphone,
                            'student_father_telephone': student_father_telephone,
                            'student_illness_question': student_illness_question,
                            'student_illness_answer': student_illness_answer,
                            'student_special_atention_question': student_special_atention_question,
                            'student_special_atention_answer': student_special_atention_answer,
                            'student_tutor_name': student_tutor_name,
                            'student_status': student_status,
                            'student_group': student_group,
                            'semester_student': semester_student,
                            'kind_student_subjects': kind_student_subjects,
                            'school_year_student': school_year_student,
                            'school_generation_student': school_generation_student,
                            'regular_student_state' : regular_student_state,
                            'action': update
                        },
                        success: function (data) {
                            const result = JSON.parse(data);
                            if(result.response == 'true'){
                                swal("¡ADVERTENCIA!", "El grupo al cual intenta guardar a este alumno supera el limite actual de 25 alumnos por grupo", "warning");
                            }else{
                            if(result.message == 'right'){
                                swal("¡CORRECTO!", "Alumno guardado correctamente", "success");
                                cleanForm();
                                llamadaTabla();
                            }else if(result.message == 'wrong'){
                                swal("¡ERROR!", "El alumno no se pudo guardar correctamente, revise su conexión", "error");
                            }else{
                                swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                            }
                        }
                        }
                    });
                
            }

            function editDeleteStudent(e) {
                e.preventDefault();
                if (e.target.parentElement.classList.contains('btn_edit_student')) {
                    const id = e.target.parentElement.getAttribute('data-id');
                        $.ajax({
                            url: 'php/model_school.php', //This is the current doc
                            type: 'POST',
                            dataType: 'text', // add json datatype to get json
                            data: { 'action': 'LLENAR_FORM_ESTUDIANTE', 'key': id },
                            success: function (data) {
                                var datos = JSON.parse(data);
                                if(datos.message == 'right'){
                                $('#regular_student_check').prop('checked', true);
                                $('body, html').animate({
                                    scrollTop: '0px'
                                }, 300);
                                
                                new_student_id.value = `${datos.student[0]}`;
                                student_name.value = `${datos.student[1]}`;
                                student_last_name.value = `${datos.student[2]}`;
                                student_curp.value = `${datos.student[3]}`;
                                student_birthplace.value = `${datos.student[4]}`;
                                student_birthday.value = `${datos.student[5]}`;
                                callDate(datos.student[5], 'fillForm');
                                student_school_origin.value = `${datos.student[7]}`;
                                student_telephone.value = `${datos.student[8]}`;
                                student_living_with.value = `${datos.student[9]}`;
                                student_neighborhood.value = `${datos.student[10]}`;
                                student_mother_name.value = `${datos.student[11]}`;
                                student_mother_cellphone.value = `${datos.student[12]}`;
                                student_mother_telephone.value = `${datos.student[13]}`;
                                student_father_name.value = `${datos.student[14]}`;
                                student_father_cellphone.value = `${datos.student[15]}`;
                                student_father_telephone.value = `${datos.student[16]}`;
                                if (datos.student[17] === 'no') {

                                    student_illness_question[0].checked = true;
                                    student_illness_answer.value = '';
                                    student_illness_answer.style.display = 'none';
                                } else {
                                    student_illness_question[1].checked = true;
                                    student_illness_answer.style.display = 'block';
                                    student_illness_answer.style.margin = '0 auto';
                                    student_illness_answer.value = `${datos.student[18]}`;
                                }
                                if (datos.student[19] === 'no') {
                                    student_special_atention_question[0].checked = true;
                                    student_special_atention_answer_check.value = '';
                                    student_special_atention_answer_check.style.display = 'none';
                                } else {
                                    student_special_atention_question[1].checked = true;
                                    student_special_atention_answer.style.display = 'block';
                                    student_special_atention_answer.style.margin = '0 auto';
                                    student_special_atention_answer.value = `${datos.student[20]}`;
                                }

                                student_tutor_name.value = `${datos.student[21]}`;
                                student_group.value = `${datos.student[22]}`;
                                student_status.value = `${datos.student[23]}`;
                                
                                if(datos.school == undefined || datos.school == '' || datos.school == null){
                                    regularStudentOptionSwitch();
                                }else{
                                    semester_student.value = `${datos.school[0]}`;
                                    kind_student_subjects.value = datos.school[1];
                                    semesterStudentSwitch();
                                    school_year_student.value = `${datos.school[2]}`;
                                    school_generation_student.value = `${datos.school[3]}`;
                                }
                                }else if(datos.message == 'right'){
                                    swal("¡CUIDADO!", "No se encontraron datos relacionados a la consulta solicitada. Intentelo de nuevo.", "warning");
                                }else{
                                    swal("¡ERROR!", "Error: " + datos.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                                }
                            }
                        });
                }
                if (e.target.parentElement.classList.contains('btn_delete_student')) {
                    swal({
                        title: "¡ADVERTENCIA!",
                        text: "¿Seguro que desea borrar al estudiante seleccionado?(Se borraran las materias y calificaciones del mismo actual)",
                        icon: "warning",
                        buttons: [
                            'No, cancelar',
                            'Si, continuar'
                        ],
                        dangerMode: true,
                    }).then(function (isConfirm) {
                        if (isConfirm) {

                            const id = e.target.parentElement.getAttribute('data-id');
                            const xhr = new XMLHttpRequest();
                            const infoStudent = new FormData();
                            infoStudent.append('id', id);
                            infoStudent.append('action', 'ELIMINAR_ESTUDIANTE');
                            xhr.open('POST', 'php/model_school.php', true);
                            xhr.onload = function () {
                                if (this.status === 200) {
                                    const result = JSON.parse(xhr.responseText);
                                    if(result.message == 'right'){
                                        swal("¡CORRECTO!", "Alumno eliminado satisfactoriamente", "success");
                                        cleanForm();
                                        llamadaTabla();
                                    }else if(result.message == 'empty'){
                                        swal("¡CUIDADO!", "No se encontraron datos relacionados a la consulta solicitada. Intentelo de nuevo.", "warning");
                                    }else{
                                        swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                                    }
                        }
                    }
                    xhr.send(infoStudent);
                        } else {

                        }
                    });
                }
            }
            /** Buscador de registros */
            function searchStudent(e) {
                const expresion = new RegExp(e.target.value, "i"),
                    rows = document.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    row.style.display = 'none';


                    if (row.childNodes[3].textContent.replace(/\s/g, " ").search(expresion) != -1 ||
                        row.childNodes[5].textContent.replace(/\s/g, " ").search(expresion) != -1 ||
                        row.childNodes[7].textContent.replace(/\s/g, " ").search(expresion) != -1 ||
                        row.childNodes[9].textContent.replace(/\s/g, " ").search(expresion) != -1 ||
                        row.childNodes[11].textContent.replace(/\s/g, " ").search(expresion) != -1 ||
                        row.childNodes[13].textContent.replace(/\s/g, " ").search(expresion) != -1 ||
                        row.childNodes[15].textContent.replace(/\s/g, " ").search(expresion) != -1 ||
                        row.childNodes[17].textContent.replace(/\s/g, " ").search(expresion) != -1 ||
                        row.childNodes[19].textContent.replace(/\s/g, " ").search(expresion) != -1 ||
                        row.childNodes[21].textContent.replace(/\s/g, " ").search(expresion) != -1 ||
                        row.childNodes[23].textContent.replace(/\s/g, " ").search(expresion) != -1 ||
                        row.childNodes[25].textContent.replace(/\s/g, " ").search(expresion) != -1 ||
                        row.childNodes[27].textContent.replace(/\s/g, " ").search(expresion) != -1 ||
                        row.childNodes[29].textContent.replace(/\s/g, " ").search(expresion) != -1 ||
                        row.childNodes[31].textContent.replace(/\s/g, " ").search(expresion) != -1 ||
                        row.childNodes[33].textContent.replace(/\s/g, " ").search(expresion) != -1 ||
                        row.childNodes[35].textContent.replace(/\s/g, " ").search(expresion) != -1 ||
                        row.childNodes[37].textContent.replace(/\s/g, " ").search(expresion) != -1 ||
                        row.childNodes[39].textContent.replace(/\s/g, " ").search(expresion) != -1 ||
                        row.childNodes[41].textContent.replace(/\s/g, " ").search(expresion) != -1 ||
                        row.childNodes[43].textContent.replace(/\s/g, " ").search(expresion) != -1 ||
                        row.childNodes[45].textContent.replace(/\s/g, " ").search(expresion) != -1 ||
                        row.childNodes[47].textContent.replace(/\s/g, " ").search(expresion) != -1) {
                        row.style.display = 'table-row';
                    }
                })
            }

            function callStudentID() {
                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': 'LLAMADA_MATRICULA_ESTUDIANTE' },
                    success: function (data) {
                        var datos = JSON.parse(data);
                        if(datos.message == 'right'){
                            $('#new_student_id').val(data.content);
                        }
                        else if(datos.message == 'empty'){
                            swal("¡CUIDADO!", "No se encontraron datos relacionados a la consulta solicitada. Intentelo de nuevo.", "warning");
                        }else {
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                        
                    }
                });
            }

            function llamadaTabla() {
                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': 'LLAMADA_LISTA_ESTUDIANTE' },
                    success: function (data) {
                        var result = JSON.parse(data);
                        if(result.message == 'right'){
                            $('#students_list tbody').html(result.content);
                        }else if(result.message == 'wrong'){
                            swal("¡ERROR!", "Hubo un problema con el envio del mensaje. Intentelo de nuevo.", "error");
                        }else {
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                    }
                }).done(function () {
                }).fail(function () {
                })
                    .always(function () {
                    });
            }

            function getBirthday() {
                var stringDate = document.querySelector('#student_birthday').value;
                callDate(stringDate, 'fillForm');
            }

            function callDate(birthday_date, kindQuery) {

                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': 'LLAMAR_FECHA', 'date': birthday_date, 'message': 'LLAMAR_FECHA' },
                    success: function (data) {
                        const result = JSON.parse(data);
                        if (kindQuery == 'fillForm') {
                        if(result.message == 'right'){
                            document.querySelector('#student_age').value = result.content;
                        }else if(result.message == 'empty'){
                            swal("¡CUIDADO!", "No se encontraron datos relacionados a la consulta solicitada. Intentelo de nuevo.", "warning");
                        }else {
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                        }
                    }
                });
            }
        }


        //carga de materias
        if ($('body').data('title') === 'load_subjects') {
            checkExistMails();
            const current_semester_student = document.querySelector('#current_semester_student'),
                current_kind_student_subjects = document.querySelector('#current_kind_student_subjects'),
                new_semester = document.querySelector('#new_semester'),
                new_kind_subjects = document.querySelector('#new_kind_subjects'),
                button_load_semester = document.querySelector('#button_load_semester'),
                all_students_check_button = document.querySelector('#all_students'),
                numberColumns = 7;
            let change_semester = false,
                similar_semester = false, 
                different_subject_kind = false, 
                different_subject_kind_n_semester = false,
                no_secuencial_semester = false;

            eventListeners();
            getSCurrentStudents();
            setNewSemesterStudents();

            function eventListeners() {
                current_semester_student.addEventListener('input', switchCurrentKindSubjects);
                current_kind_student_subjects.addEventListener('input', getSCurrentStudents);
                new_semester.addEventListener('input', switchNewKindSubjects);
                new_kind_subjects.addEventListener('input', setNewSemesterStudents);
                all_students_check_button.addEventListener('click', setAllListChecked);
                button_load_semester.addEventListener('click', updateSemester);
            }

            function switchCurrentKindSubjects() {
                const semester = document.querySelector('#current_semester_student').value,
                    mySelect = document.querySelector('#current_kind_student_subjects');
                $('#current_kind_student_subjects').html('');
                if (semester == 'Primero' || semester == 'Segundo' || semester == 'Tercero' || semester == 'Cuarto') {
                    var myOption1 = document.createElement("option");
                    myOption1.setAttribute("value", "Tronco Común");
                    myOption1.textContent = "Tronco Común";
                    mySelect.appendChild(myOption1);
                    getSCurrentStudents();
                    $('all_students').prop('checked', false);
                } else if (semester == 'Quinto' || semester == 'Sexto') {
                    var myOption2 = document.createElement("option");
                    myOption2.setAttribute("value", "Económico - Administrativo");
                    myOption2.textContent = "Económico - Administrativo";
                    mySelect.appendChild(myOption2);
                    var myOption3 = document.createElement("option");
                    myOption3.setAttribute("value", "Físico - Matemático");
                    myOption3.textContent = "Físico - Matemático";
                    mySelect.appendChild(myOption3);
                    var myOption4 = document.createElement("option");
                    myOption4.setAttribute("value", "Humanidades Y Ciencias Sociales");
                    myOption4.textContent = "Humanidades Y Ciencias Sociales";
                    mySelect.appendChild(myOption4);
                    var myOption5 = document.createElement("option");
                    myOption5.setAttribute("value", "Químico - Biológico");
                    myOption5.textContent = "Químico - Biológico";
                    mySelect.appendChild(myOption5);
                    getSCurrentStudents();
                    $('all_students').prop('checked', false);
                }
            }

            function switchNewKindSubjects() {
                const semester = document.querySelector('#new_semester').value,
                    mySelect = document.querySelector('#new_kind_subjects');
                $('#new_kind_subjects').html('');
                if (semester == 'Primero' || semester == 'Segundo' || semester == 'Tercero' || semester == 'Cuarto') {

                    var myOption1 = document.createElement("option");
                    myOption1.setAttribute("value", "Tronco Común");
                    myOption1.textContent = "Tronco Común";
                    mySelect.appendChild(myOption1);
                    setNewSemesterStudents();
                    $('all_students').prop('checked', false);

                } else if (semester == 'Quinto' || semester == 'Sexto') {
                    var myOption2 = document.createElement("option");
                    myOption2.setAttribute("value", "Económico - Administrativo");
                    myOption2.textContent = "Económico - Administrativo";
                    mySelect.appendChild(myOption2);
                    var myOption3 = document.createElement("option");
                    myOption3.setAttribute("value", "Físico - Matemático");
                    myOption3.textContent = "Físico - Matemático";
                    mySelect.appendChild(myOption3);
                    var myOption4 = document.createElement("option");
                    myOption4.setAttribute("value", "Humanidades Y Ciencias Sociales");
                    myOption4.textContent = "Humanidades Y Ciencias Sociales";
                    mySelect.appendChild(myOption4);
                    var myOption5 = document.createElement("option");
                    myOption5.setAttribute("value", "Químico - Biológico");
                    myOption5.textContent = "Químico - Biológico";
                    mySelect.appendChild(myOption5);
                    setNewSemesterStudents();
                    $('all_students').prop('checked', false);
                } else if (semester == 'Graduado') {
                    var myOption6 = document.createElement("option");
                    myOption6.setAttribute("value", "Graduado");
                    myOption6.textContent = "";
                    mySelect.appendChild(myOption6);
                    setNewSemesterStudents();
                    $('all_students').prop('checked', false);
                }

            }

            function getSCurrentStudents() {
                const action = 'LLAMAR_ESTUDIANTES_SEMESTRE';
                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': action, 'semesters': current_semester_student.value, 'optional_classes': current_kind_student_subjects.value },
                    success: function (data) {
                        const result = JSON.parse(data);
                        if (result.message == 'right'){
                            $('#tbody_students_by_semester').html(result.students_row);
                            $('#number_of_rows').html(result.number_rows);
                            setNewSemesterStudents();
                            change_semester = false;
                            similar_semester = false;
                            different_subject_kind = false; 
                            different_subject_kind_n_semester = false;
                            no_secuencial_semester = false;
                        }else if(result.message == 'empty'){
                            $('#tbody_students_by_semester').html('');
                            $('#number_of_rows').html(0);
                            //setNewSemesterStudents();
                            change_semester = false;
                            similar_semester = false;
                            different_subject_kind = false; 
                            different_subject_kind_n_semester = false;
                            no_secuencial_semester = false;
                        }else {
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                        
                    }
                });
            }
            function setNewSemesterStudents() {
                const numberRows = $('#number_of_rows').text();

                let array = [];
                for (var i = 0; i < numberRows; i++) {
                    array[i] = [];
                    for (var j = 0; j < 3; j++) {
                        array[i][j] = $('#col-' + i + '-' + j);
                        if (j == 0) {
                            $(array[i][j]).on('change', function () {
                                markCases($(this).attr('id'), i);
                            });
                        }
                        if (j == 1) {
                            $(array[i][j]).text($('#new_semester').val()).css('background-color', '#BFCC14');
                        }
                        else if (j == 2) {
                            $(array[i][j]).text($('#new_kind_subjects').val()).css('background-color', '#BFCC14');
                        }
                    }
                }
            }
            function markCases(check_button_id) {

                const positions = check_button_id.split('-'),
                    row = positions[1];
                const id = '#' + check_button_id;
                if ($(id).is(':checked')) {
                    for (var i = row; i < (row + 1); i++) {
                        for (var j = 3; j < numberColumns; j++) {
                            $('#col-' + i + '-' + j).css('background-color', '#FF9E00');
                        }
                    }
                } else {
                    for (var i = row; i < (row + 1); i++) {
                        for (var j = 3; j < numberColumns; j++) {
                            $('#col-' + i + '-' + j).css('background-color', '#FFFFFF');
                            $('#all_students').prop('checked', false);
                        }
                        break;
                    }
                }
            }

            function setAllListChecked() {
                const numberRows = $('#number_of_rows').text();
                if ($('#all_students').is(':checked')) {
                    for (var i = 0; i < numberRows; i++) {
                        $('#col-' + i + '-0').prop('checked', true);
                        markCases('col-' + i + '-0');
                    }
                } else {
                    for (var i = 0; i < numberRows; i++) {
                        $('#col-' + i + '-0').prop('checked', false);
                        markCases('col-' + i + '-0');
                    }
                }
            }

            function updateSemester() {
                const numberRows = $('#number_of_rows').text();
                let numberRowsChecked = 0;
                let array = [];
                for (var i = 0; i < numberRows; i++) {
                    array[numberRowsChecked] = [];
                    if ($('#col-' + i + '-0').is(':checked')) {
                        for (var j = 0; j < 7; j++) {
                            if (j > 0) {
                                array[numberRowsChecked][j] = $('#col-' + i + '-' + j).text();
                            }
                        }
                        numberRowsChecked++;
                    }
                }
                if (numberRowsChecked != 0) {
                    for (var i = 0; i < numberRowsChecked; i++) {
                        if (((array[i][1] === 'Primero' && array[i][5] != '') || 
                        (array[i][1] === 'Segundo' && array[i][5] != 'Primero') || 
                        (array[i][1] === 'Tercero' && array[i][5] != 'Segundo') || 
                        (array[i][1] === 'Cuarto' && array[i][5] != 'Tercero') || 
                        (array[i][1] === 'Quinto' && array[i][5] != 'Cuarto') || 
                        (array[i][1] === 'Sexto' && array[i][5] != 'Quinto')) && no_secuencial_semester == false) {
                            swal({
                                title: "¡ADVERTENCIA!",
                                text: "El semestre actual no es correspondiente al que quiere actualizar ¿Desea Continuar?",
                                icon: "warning",
                                buttons: [
                                    'No, cancelar',
                                    'Si, continuar'
                                ],
                                dangerMode: true,
                            }).then(function (isConfirm) {
                                if (isConfirm) {
                                    no_secuencial_semester = true;
                                    updateSemester();
                                }
                            });
                        }else{
                            no_secuencial_semester = true;
                        }
                        if ((array[i][1] === array[i][5] && array[i][2] === array[i][6]) && similar_semester == false) {
                            swal({
                                title: "¡ADVERTENCIA!",
                                text: "A algunos alumnos se les asignara el mismo semestre que tienen actualmente (Se borraran las calificaciones y materias anteriormente guardadas) ¿Desea Continuar?",
                                icon: "warning",
                                buttons: [
                                    'No, cancelar',
                                    'Si, continuar'
                                ],
                                dangerMode: true,
                            }).then(function (isConfirm) {
                                if (isConfirm) {
                                    similar_semester = true;
                                    updateSemester();
                                }
                            });
                        }else{
                            similar_semester = true;
                        }
                        
                        if ((((array[i][1] == 'Quinto' && array[i][5] == 'Quinto') && (array[i][2] != array[i][6])) 
                        || ((array[i][1] == 'Sexto' && array[i][5] == 'Sexto') && (array[i][2] != array[i][6]))) && different_subject_kind == false) {
                            swal({
                                title: "¡ADVERTENCIA!",
                                text: "Los bachilleratos no coinciden ¿Desea sobreescribir el bachillerato?(Se borraran las calificaciones y materias anteriormente guardadas)",
                                icon: "warning",
                                buttons: [
                                    'No, cancelar',
                                    'Si, continuar'
                                ],
                                dangerMode: true,
                            }).then(function (isConfirm) {
                                if (isConfirm) {
                                    different_subject_kind = true;
                                    updateSemester();
                                } 
                            });
                        }else{
                            different_subject_kind = true;
                        }
                        if (((array[i][1] == 'Sexto' && array[i][5] == 'Quinto') && (array[i][2] != array[i][6])) && different_subject_kind_n_semester == false) {
                            swal({
                                title: "¡ADVERTENCIA!",
                                text: "Para pasar de quinto semestre a sexto semestre, deben coincidir los bachilleratos ¿Desea sobreescribir el bachillerato?",
                                icon: "warning",
                                buttons: [
                                    'No, cancelar',
                                    'Si, continuar'
                                ],
                                dangerMode: true,
                            }).then(function (isConfirm) {
                                if (isConfirm) {
                                    different_subject_kind_n_semester = true;
                                    updateSemester();
                                } 
                            });
                        }else{
                            different_subject_kind_n_semester = true;
                        }
                        if (array[i][1] == 'Graduado' && array[i][5] != 'Sexto') {
                            swal("¡CUIDADO!", "No se puede pasar a 'Graduado' si el semestre anterior no es sexto semestre", "warning");
                        } else {
                            change_semester = true;
                        }
                    }
                    if(change_semester == true && similar_semester == true && different_subject_kind == true && different_subject_kind_n_semester == true && no_secuencial_semester == true){
                        updateSemesterAjax(numberRowsChecked, array);
                    }
                } else {
                    swal("¡CUIDADO!", "Seleccione al menos un estudiante de la lista para continuar", "warning");
                }
            }

            function updateSemesterAjax(numberRowsChecked, array){
                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': 'ACTUALIZAR_SEMESTRE', 'number_rows': numberRowsChecked, 'array': JSON.stringify(array) },
                    success: function (data) {
                        const result = JSON.parse(data);
                        if(result.extra_grade == 'right'){
                                    getSCurrentStudents();
                                    swal("¡ÉXITO!", "Semestre actualizado correctamente", "success");
                                    $('#all_students').prop('checked', false);
                                    setAllListChecked();
                        }else{
                            if(result.response == 'true'){
                                swal("¡ADVERTENCIA!", "El grupo "+result.group+ " del semestre "+ result.semester +" alcanzó su limite de 25 estudiantes. "+result.counter+" actualizados correctamente", "warning");
                                getSCurrentStudents();
                                $('#all_students').prop('checked', false);
                                setAllListChecked();
                            }else{
                                if (result.message == 'right') {
                                    getSCurrentStudents();
                                    swal("¡ÉXITO!", "Semestre actualizado correctamente", "success");
                                    $('#all_students').prop('checked', false);
                                    setAllListChecked();
                                } else if (result.message === 'wrong') {
                                    swal("¡ERROR!", "El semestre no se pudo actualizar. Intentelo de nuevo.", "error");
                                }else {
                                    swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                                }
                            }
                        }
                    }
                });
            }
        }

        //REGISTRO DE PROFESORES
        if ($('body').data('title') === 'registration_teachers_body') {
            
            const TeacherList = document.querySelector('#teachers_list tbody'),
                buttonSave = document.querySelector('#button_save'),
                buttonUpdate = document.querySelector('#button_update'),
                teacher_name = document.querySelector('#teacher_name'),
                teacher_name_input = document.querySelector('#teacher_name'),
                buttonCleanupForm = document.querySelector('#button_cleanup'),
                updateTeacherAge = document.querySelector('#teacher_birthday');

            checkExistMails();
            llamadaTabla();
            eventListeners();
            callTeacherID();

            function eventListeners() {
                if (TeacherList) {
                    TeacherList.addEventListener('click', editDeleteTeacher);
                }
                teacher_name_input.addEventListener('input', callTeacherID);
                buttonSave.addEventListener('click', checkTeacherExist);
                buttonUpdate.addEventListener('click', readUpdateForm);
                buttonCleanupForm.addEventListener('click', cleanForm);

                updateTeacherAge.addEventListener('input', getTeacherBirthday);
            }

            function cleanForm() {
                document.querySelector('form').reset();
                callTeacherID();
            }

            function checkTeacherExist(e) {
                let teacherID = document.querySelector('#new_teacher_id').value;

                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': 'COMPROBAR_MATRICULA_PROFESOR', 'teacherID': teacherID },
                    success: function (data) {
                        const result = JSON.parse(data);
                        if (result.message === 'right') {

                            swal({
                                title: "¡ADVERTENCIA!",
                                text: "Hay un maestro con esa matricula ya asignada, ¿desea sobreescribirlo?",
                                icon: "warning",
                                buttons: [
                                    'No, cancelar',
                                    'Si, deseo actualizarlo'
                                ],
                                dangerMode: true,
                            }).then(function (isConfirm) {
                                if (isConfirm) {
                                    readUpdateForm(e);
                                }
                            });
                        } else if (result.message === 'empty') {
                            readSaveForm(e);
                        }else{
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }

                    }
                });
            }

            function getTeacherBirthday() {
                var stringDate = document.querySelector('#teacher_birthday').value;
                callTeacherDate(stringDate, 'fillForm');
            }

            function callTeacherDate(birthday_date, kindQuery) {

                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': 'LLAMAR_FECHA', 'date': birthday_date, 'message': 'LLAMAR_FECHA' },
                    success: function (data) {
                        const result = JSON.parse(data);
                        if (kindQuery == 'fillForm') {
                            if(result.message == 'right'){
                                document.querySelector('#teacher_age').value = result.content;
                            }else if(result.message == 'empty'){
                                swal("¡CUIDADO!", "No se encontraron datos relacionados a la consulta solicitada. Intentelo de nuevo.", "warning");
                            }else {
                                swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                            }
                        }
                    }
                });
            }

            function readSaveForm(e) {
                e.preventDefault();
                const new_teacher_id = document.querySelector('#new_teacher_id').value,
                    teacher_name = document.querySelector('#teacher_name').value,
                    teacher_address = document.querySelector('#teacher_address').value,
                    teacher_cellphone = document.querySelector('#teacher_cellphone').value,
                    teacher_email = document.querySelector('#teacher_email').value,
                    teacher_career = document.querySelector('#teacher_career').value,
                    teacher_birthday = document.querySelector('#teacher_birthday').value,
                    teacher_age = document.querySelector('#teacher_age').value,
                    save = "GUARDAR_PROFESOR";

                if (new_teacher_id === '' ||
                    teacher_name === '' ||
                    teacher_address === '' ||
                    teacher_cellphone === '' ||
                    teacher_email === '' ||
                    teacher_career === ''||
                    teacher_age === ''
                ) {
                    swal("¡ADVERTENCIA!", "Uno o varios campos estan vacios", "warning");
                } else {
                    const infoTeacher = new FormData();
                    infoTeacher.append('new_teacher_id', new_teacher_id);
                    infoTeacher.append('teacher_name', teacher_name);
                    infoTeacher.append('teacher_address', teacher_address);
                    infoTeacher.append('teacher_cellphone', teacher_cellphone);
                    infoTeacher.append('teacher_email', teacher_email);
                    infoTeacher.append('teacher_career', teacher_career);
                    infoTeacher.append('teacher_birthday', teacher_birthday);
                    infoTeacher.append('teacher_age', teacher_age);
                    infoTeacher.append('action', save);
                    saveTeacher(infoTeacher);
                }
            }

            function saveTeacher(data) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'php/model_school.php', true);
                xhr.onload = function () {
                    if (this.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.message === 'right') {
                            swal("¡CORRECTO!", "Profesor guardado correctamente", "success");
                            llamadaTabla();
                            cleanForm();
                        }
                        else if (response.message === 'wrong'){
                            swal("¡ERROR!", "No se pudo guardar el registro del profesor. Por favor, revise su conexión", "error");
                        }else{
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                    }
                }
                xhr.send(data);
            }

            function readUpdateForm(e) {
                e.preventDefault();
                const new_teacher_id = document.querySelector('#new_teacher_id').value,
                    teacher_address = document.querySelector('#teacher_address').value,
                    teacher_cellphone = document.querySelector('#teacher_cellphone').value,
                    teacher_email = document.querySelector('#teacher_email').value,
                    teacher_career = document.querySelector('#teacher_career').value,
                    teacher_birthday = document.querySelector('#teacher_birthday').value,
                    teacher_age = document.querySelector('#teacher_age').value,
                    update = "ACTUALIZAR_PROFESOR";

                if (new_teacher_id === '' ||
                    teacher_name === '' ||
                    teacher_address === '' ||
                    teacher_cellphone === '' ||
                    teacher_email === '' ||
                    teacher_career === ''||
                    teacher_age === ''
                ) {
                    swal("¡ADVERTENCIA!", "Uno o varios campos estan vacios", "warning");
                } else {
                    const infoTeacher = new FormData();
                    infoTeacher.append('new_teacher_id', new_teacher_id);
                    infoTeacher.append('teacher_address', teacher_address);
                    infoTeacher.append('teacher_cellphone', teacher_cellphone);
                    infoTeacher.append('teacher_email', teacher_email);
                    infoTeacher.append('teacher_career', teacher_career);
                    infoTeacher.append('teacher_birthday', teacher_birthday);
                    infoTeacher.append('teacher_age', teacher_age);
                    infoTeacher.append('action', update);
                    updateTeacher(infoTeacher);
                }
            }

            function updateTeacher(data) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'php/model_school.php', true);
                xhr.onload = function () {
                    if (this.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        document.querySelector('form').reset();
                        
                        if (response.response === 'right') {
                            swal("¡CORRECTO!", "Profesor actualizado correctamente", "success");
                            llamadaTabla();
                            cleanForm();
                        }
                        else if (response.response === 'wrong'){
                            swal("¡ERROR!", "No se pudo guardar el registro del profesor. Intentelo de nuevo.", "error");
                        }else{
                            swal("¡ERROR!", "Error: " + response.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                    }
                }
                xhr.send(data);
            }

            function editDeleteTeacher(e) {
                e.preventDefault();
                if (e.target.parentElement.classList.contains('btn_edit_teacher')) {
                    const id = e.target.parentElement.getAttribute('data-id');

                        $.ajax({
                            url: 'php/model_school.php', //This is the current doc
                            type: 'POST',
                            dataType: 'text', // add json datatype to get json
                            data: { 'action': 'LLENAR_FORM_PROFESOR', 'key': id },
                            success: function (data) {
                                const result = JSON.parse(data);
                                if(result.message == 'right'){
                                    $('body, html').animate({
                                        scrollTop: '0px'
                                    }, 300);
                                    new_teacher_id.value = `${result.content[1]}`;
                                    teacher_name.value = `${result.content[2]}`;
                                    teacher_address.value = `${result.content[3]}`;
                                    teacher_cellphone.value = `${result.content[4]}`;
                                    teacher_email.value = `${result.content[5]}`;
                                    teacher_career.value = `${result.content[6]}`;
                                    teacher_birthday.value = `${result.content[7]}`;
                                    callTeacherDate(result.content[7], 'fillForm');
                                }else if(result.message == 'empty'){
                                    swal("¡CUIDADO!", "No se encontraron datos relacionados a la consulta solicitada. Intentelo de nuevo.", "warning");
                                }else {
                                    swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                                }
                            }
                        });
                }

                if (e.target.parentElement.classList.contains('btn_delete_teacher')) {

                    swal({
                        title: "¡ADVERTENCIA!",
                        text: "¿Seguro que desea borrar al profesor seleccionado?(Se borraran las materias asignadas y calificaciones del mismo)",
                        icon: "warning",
                        buttons: [
                            'No, cancelar',
                            'Si, continuar'
                        ],
                        dangerMode: true,
                    }).then(function (isConfirm) {
                        if (isConfirm) {

                            const id = e.target.parentElement.getAttribute('data-id');
                            const xhr = new XMLHttpRequest();
                            const infoTeacher = new FormData();
                            infoTeacher.append('id', id);
                            infoTeacher.append('action', 'ELIMINAR_PROFESOR');
                            xhr.open('POST', 'php/model_school.php', true);
                            xhr.onload = function () {
                                if (this.status === 200) {
                                    const result = JSON.parse(xhr.responseText);
                                    if (result.message == 'right') {
                                        swal("¡CORRECTO!", "El profesor ha sido borrado exitosamente", "success");
                                        llamadaTabla();
                                        cleanForm();
                                    }else if (result.message == 'wrong'){
                                        swal("¡ERROR!", "Hubo un problema al realizar el borrado. Por favor, intente de nuevo", "error");
                                        llamadaTabla();
                                        cleanForm();
                                    }else{
                                        swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                                    }
                                }
                            }
                            xhr.send(infoTeacher);
                        } else {

                        }
                    });
                }
            }


            function llamadaTabla() {
                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': 'LLAMADA_LISTA_PROFESOR' },
                    success: function (data) {
                        var response = JSON.parse(data);
                        if(response.message == 'right'){
                            $('#teachers_list tbody').html(response.content);
                        }else if(response.message == 'wrong'){
                            swal("¡CUIDADO!", "No se encontraron datos relacionados a la consulta solicitada. Intentelo de nuevo.", "warning");
                        }else{
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                    }
                });
            }
        }

        function callTeacherID() {
            const teacherNameInput = document.querySelector('#teacher_name').value;

            $.ajax({
                url: 'php/model_school.php', //This is the current doc
                type: 'POST',
                dataType: 'text', // add json datatype to get json
                data: { 'action': 'LLAMADA_PROFESOR_MATRICULA', 'teacherNameInput': teacherNameInput },
                success: function (data) {
                    var result = JSON.parse(data);
                    if(result.message == 'right'){
                        $('#new_teacher_id').val(result.content);
                    }
                    else if(result.message == 'empty'){
                        swal("¡CUIDADO!", "El servidor no está respondiendo. Recargue la pagina para continuar.", "warning");
                    }else {
                        swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                    }
                }
            });
        }

        //Asignacion de materias
        if ($('body').data('title') === 'subject_asignation_body') {
            
            const SubjectsList = document.querySelector('#subjects_table'),
                TeachersSubjectsTable = document.querySelector('#teachers_subjects_table'),
                subject_semester_switch = document.querySelector('#new_subject_semester'),
                search_teacher_id = document.querySelector('#input_search_teacher_id');
            const search_subject_id = document.querySelector('#input_search_subject_id');
            var button_save = document.querySelector('#button_save');

            checkExistMails();
            eventListeners();
            llamadaTabla();
            callTeacherID();
            callSubjectID();

            function eventListeners() {
                button_save.addEventListener('click', saveTeachersSubject);
                search_teacher_id.addEventListener('input', callTeachersName);
                search_subject_id.addEventListener('input', callSubjectName);
                subject_semester_switch.addEventListener('input', semesterStudentSwitch)
                document.querySelector('#search_teacher_id').addEventListener('input', function () {
                });
                if (SubjectsList) {
                    SubjectsList.addEventListener('click', checkSubjectExist);
                }
                if (TeachersSubjectsTable) {
                    TeachersSubjectsTable.addEventListener('click', deleteTeachersSubject);
                }
            }

            function cleanFormSubjects() {
                document.querySelector('#form_subjects').reset();
                semesterStudentSwitch();
            }

            function checkSubjectsTableSize(){
                var tableHeight = $('.table_all_subjects').innerHeight();
            const recommendedTableHeight = 800;
            if(tableHeight >= recommendedTableHeight){
                $('.table_all_subjects').addClass('table_all_subjects_height');
            }
            }

            function saveTeachersSubject() {
                let subject_key = $('#input_search_subject_id').val();
                const search_teacher_id = document.querySelector('#input_search_teacher_id').value,
                    teachers_name = document.querySelector('#teachers_name').value,
                    search_subject_id = document.querySelector('#input_search_subject_id').value,
                    subjects_name = document.querySelector('#subjects_name').value,
                    teachers_group = document.querySelector('#teachers_group').value,
                    schedule = document.querySelector('#schedule').value,
                    save = 'GUARDAR_MATERIA_PROFESOR';

                if (search_teacher_id == '' ||
                    teachers_name == '' ||
                    subjects_name == '' ||
                    schedule == '' ||
                    $('#teachers_name').text() == 'No Asignado' ||
                    $('#subjects_name').text() == 'No Asignado') {
                    swal("¡ADVERTENCIA!", "Es necesario llenar todos los campos adecuadamente para continuar", "warning");
                } else {

                    $.ajax({
                        url: 'php/model_school.php', //This is the current doc
                        type: 'POST',
                        dataType: 'text', // add json datatype to get json
                        data: { 'action': 'COMPROBAR_CLAVE_MATERIAS_PROFESOR', 'subject_key': subject_key, 'teachers_group' : teachers_group },
                        success: function (data) {
                            const result = JSON.parse(data);
                            if (result.message === 'right') {
                                swal("¡ADVERTENCIA!", "Hay una materia con la misma clave y grupo ya asignada", "warning");
                            } else {
                                $.ajax({
                                    url: 'php/model_school.php', //This is the current doc
                                    type: 'POST',
                                    dataType: 'text', // add json datatype to get json
                                    data: {
                                        'search_teacher_id': search_teacher_id,
                                        'search_subject_id': search_subject_id,
                                        'teachers_group': teachers_group,
                                        'schedule': schedule,
                                        'action': save
                                    },
                                    success: function (data) {
                                        const result = JSON.parse(data);
                                        if(result.message == 'right'){
                                            swal("¡CORRECTO!", "Materia guardada correctamente", "success");
                                            cleanFormSubjects();
                                            callTeachersSubjects();
                                        }else if(result.message == 'wrong'){
                                            swal("¡ERROR!", "La materia no se pudo guardar correctamente. Intente de nuevo", "error");
                                        }else{
                                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                                        }
                                    }
                                });
                            }
                        }
                    });
                }
            }

            function callTeacherID() {
                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': 'LLAMAR_PROFESORES_ID' },
                    success: function (data) {
                        const result = JSON.parse(data);
                        if(result.message == 'right'){
                            $('#search_teacher_id').html(result.content);
                        }else if(result.message == 'empty'){
                        }else {
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                        
                    }
                });
            }

            function callSubjectID() {
                const action = 'LLAMAR_MATERIA_ID';
                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': action },
                    success: function (data) {
                        const result = JSON.parse(data);
                        if(result.message == 'right'){
                            $('#search_subject_id').html(result.content);
                        }else if(result.message == 'empty'){
                        }else {
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                    }
                });
            }



            function callTeachersName() {
                const action = 'LLAMAR_NOMBRE_PROFESOR',
                    search_teacher_id = document.querySelector('#input_search_teacher_id').value;
                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': action, 'teacher_id': search_teacher_id },
                    success: function (data) {
                        const result = JSON.parse(data);
                        if(result.message == 'right'){
                            $('#teachers_name').html(result.content);
                            callTeachersSubjects();
                        }else if(result.message == 'empty'){
                            $('#teachers_name').html('No Asignado');
                            $('#input_search_subject_id').val('');
                            callSubjectName();
                            callTeachersSubjects();
                        }else {
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                    }
                });
            }

            function callSubjectName() {
                const action = 'LLAMAR_NOMBRE_MATERIA',
                    search_subject_id = document.querySelector('#input_search_subject_id').value;
                    var semester_group_select = document.querySelector('#teachers_group');
                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': action, 'subject_id': search_subject_id },
                    success: function (data) {
                        const result = JSON.parse(data);
                        if(result.message == 'right'){
                                if(result.subject_semester == 'Quinto' || result.subject_semester == 'Sexto'){
                                    $('#subjects_name').html(result.subject_name);
                                    var semester_option_A = document.createElement("option");
    
                                    $('#teachers_group').html('');
                                    semester_option_A.setAttribute("value", "A");
                                    semester_option_A.textContent = "A";
                                    semester_group_select.appendChild(semester_option_A);
                                }else{
                                    $('#subjects_name').html(result.subject_name);
                                    $('#teachers_group').html('');
                                    var semester_option_A = document.createElement("option");
                                    semester_option_A.setAttribute("value", "A");
                                    semester_option_A.textContent = "A";
                                    semester_group_select.appendChild(semester_option_A);
                                    var semester_option_B = document.createElement("option");
                                    semester_option_B.setAttribute("value", "B");
                                    semester_option_B.textContent = "B";
                                    semester_group_select.appendChild(semester_option_B);
                                    var semester_option_C = document.createElement("option");
                                    semester_option_C.setAttribute("value", "C");
                                    semester_option_C.textContent = "C";
                                    semester_group_select.appendChild(semester_option_C);
                                    var semester_option_D = document.createElement("option");
                                    semester_option_D.setAttribute("value", "D");
                                    semester_option_D.textContent = "D";
                                    semester_group_select.appendChild(semester_option_D);
                                    var semester_option_E = document.createElement("option");
                                    semester_option_E.setAttribute("value", "E");
                                    semester_option_E.textContent = "E";
                                    semester_group_select.appendChild(semester_option_E);
                                }
                        } else if(result.message == 'empty'){
                            $('#subjects_name').html('No Asignado');
                        }else {
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                        
                    }
                });
            }

            function deleteTeachersSubject(e){
                e.preventDefault();
                if (e.target.parentElement.classList.contains('btn_delete_subject')) {

                    swal({
                        title: "¡ADVERTENCIA!",
                        text: "¿Seguro que desea borrar la materia de este profesor?",
                        icon: "warning",
                        buttons: [
                            'No, cancelar',
                            'Si, continuar'
                        ],
                        dangerMode: true,
                    }).then(function (isConfirm) {
                        if (isConfirm) {
                            const id = e.target.parentElement.getAttribute('data-id');
                            const xhr = new XMLHttpRequest();
                            const infoTeacher = new FormData();
                            infoTeacher.append('id', id);
                            infoTeacher.append('action', 'ELIMINAR_MATERIA_PROFESOR');
                            xhr.open('POST', 'php/model_school.php', true);
                            xhr.onload = function () {
                                if (this.status === 200) {
                                    const result = JSON.parse(xhr.responseText);
                                    if (result.message == 'right') {
                                        swal("¡ÉXITO!", "Materia borrada correctamente", "success");
                                        callTeachersSubjects();
                                    } else if (result.message == 'wrong'){
                                        swal("¡ERROR!", "No se pudo realizar la peticion correctamente. Intentelo de nuevo.", "error");
                                        callTeachersSubjects();
                                    }else {
                                        swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                                    }
                                }
                            }
                            xhr.send(infoTeacher);
                        } else { }
                    });
                }
            }

            function callTeachersSubjects() {
                const action = 'LLAMAR_MATERIAS_PROFESOR',
                    teacher_id = document.querySelector('#input_search_teacher_id').value;
                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': action, 'teacher_id': teacher_id },
                    success: function (data) {
                        const result = JSON.parse(data);
                        if(result.message == 'right'){
                            $('#tbody_teacher_subjects').html(result.content);
                            checkTableSize();
                        }else if(result.message == 'empty'){
                            $('#tbody_teacher_subjects').html('');
                        }else{
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                        
                    }
                });
            }

            function checkSubjectExist(e) {
                e.preventDefault();
                if (e.target.parentElement.classList.contains('btn_add_subject')) {
                    const subject_key = document.querySelector('#new_subject_key').value,
                    new_subject_position = document.querySelector('#new_subject_position').value,
                    new_subject_semester = document.querySelector('#new_subject_semester').value,
                    new_subject_kind_subjects = document.querySelector('#new_subject_kind_subjects').value;

                    $.ajax({
                        url: 'php/model_school.php', //This is the current doc
                        type: 'POST',
                        dataType: 'text', // add json datatype to get json
                        data: { 'action': 'COMPROBAR_CLAVE_MATERIA', 'subject_key': subject_key, 'new_subject_position':  new_subject_position, 'new_subject_semester': new_subject_semester, 'new_subject_kind_subjects':new_subject_kind_subjects},
                        success: function (data) {
                            const result = JSON.parse(data);
                            if (result.response === 'true') {
                                swal("¡ADVERTENCIA!", "Hay una materia con la misma clave ya asignada", "warning");
                            } else if (result.response_order === 'true') {
                                swal("¡ADVERTENCIA!", "Hay una materia con el mismo numero de orden, semestre y bachillerato", "warning");
                            }
                            else if (result.response === 'false'){
                                accionSaveSubjects(e);
                            }else{
                                swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                            }

                        }
                    });
                }
                else if (e.target.parentElement.classList.contains('btn_clean_form_subject')) {
                    cleanFormSubjects();
                }
                else if (e.target.parentElement.classList.contains('btn_delete_subject')) {

                    swal({
                        title: "¡ADVERTENCIA!",
                        text: "¿Seguro que desea borrar la materia? Se borrara tambien de las listas de profesores y boletas de alumnos",
                        icon: "warning",
                        buttons: [
                            'No, cancelar',
                            'Si, continuar'
                        ],
                        dangerMode: true,
                    }).then(function (isConfirm) {
                        if (isConfirm) {
                            const id = e.target.parentElement.getAttribute('data-id');
                            const xhr = new XMLHttpRequest();
                            const infoTeacher = new FormData();
                            infoTeacher.append('id', id);
                            infoTeacher.append('action', 'ELIMINAR_MATERIA');
                            xhr.open('POST', 'php/model_school.php', true);
                            xhr.onload = function () {
                                if (this.status === 200) {
                                    const result = JSON.parse(xhr.responseText);
                                    if (result.message == 'right') {
                                        swal("¡ÉXITO!", "Materia borrada correctamente", "success");
                                        llamadaTabla();
                                        callSubjectID();
                                        callTeachersSubjects();
                                    } else if (result.message == 'wrong'){
                                        swal("¡ERROR!", "No se pudo realizar la peticion correctamente", "error");
                                    }else {
                                        swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                                    }
                                }
                            }
                            xhr.send(infoTeacher);
                        } else { }
                    });
                }
            }

            function accionSaveSubjects(e) {
                e.preventDefault();
                if (e.target.parentElement.classList.contains('btn_add_subject')) {
                    const new_subject_key = document.querySelector('#new_subject_key').value,
                        new_subject_name = document.querySelector('#new_subject_name').value,
                        new_subject_position = document.querySelector('#new_subject_position').value,
                        new_subject_semester = document.querySelector('#new_subject_semester').value,
                        new_subject_kind_subjects = document.querySelector('#new_subject_kind_subjects').value,
                        new_subject_kind = document.querySelector('#new_subject_kind').value,
                        new_subject_credits = document.querySelector('#new_subject_credits').value,
                        save = "GUARDAR_MATERIA";
                    if (new_subject_key === '' ||
                        new_subject_name === '' ||
                        new_subject_semester === '' ||
                        new_subject_kind_subjects == '') {
                        swal("¡ADVERTECIA!", "Uno o mas campos estan vacios", "warning");
                    } else {
                        const infoSubject = new FormData();
                        infoSubject.append('new_subject_key', new_subject_key);
                        infoSubject.append('new_subject_name', new_subject_name);
                        infoSubject.append('new_subject_position', new_subject_position);
                        infoSubject.append('new_subject_semester', new_subject_semester);
                        infoSubject.append('new_subject_kind_subjects', new_subject_kind_subjects);
                        infoSubject.append('new_subject_kind', new_subject_kind);
                        infoSubject.append('new_subject_credits', new_subject_credits);
                        infoSubject.append('action', save);
                        saveSubject(infoSubject);
                    }
                }
            }
            function saveSubject(data) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'php/model_school.php', true);
                xhr.onload = function () {
                    if (this.status === 200) {
                        const result = JSON.parse(xhr.responseText);
                        if (result.message == 'right') {
                            cleanFormSubjects();
                            llamadaTabla();
                            callSubjectID();
                            swal("¡ÉXITO!", "Materia guardada correctamente", "success");
                        } else if (result.message == 'wrong'){
                            swal("¡ERROR!", "No se pudo realizar la peticion correctamente. Revise su conexion a internet", "error");
                        }else{
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                    }
                }
                xhr.send(data);
            }

            function semesterStudentSwitch() {
                const semester = document.querySelector('#new_subject_semester').value,
                    mySelect = document.querySelector('#new_subject_kind_subjects');
                $('#new_subject_kind_subjects').html('');
                if (semester == 'Primero' || semester == 'Segundo' || semester == 'Tercero' || semester == 'Cuarto') {
                    var myOption1 = document.createElement("option");
                    myOption1.setAttribute("value", "Tronco Común");
                    myOption1.textContent = "Tronco Común";

                    mySelect.appendChild(myOption1);

                } else if (semester == 'Quinto' || semester == 'Sexto') {
                    var myOption2 = document.createElement("option");
                    myOption2.setAttribute("value", "Económico - Administrativo");
                    myOption2.textContent = "Económico - Administrativo";
                    mySelect.appendChild(myOption2);
                    var myOption3 = document.createElement("option");
                    myOption3.setAttribute("value", "Físico - Matemático");
                    myOption3.textContent = "Físico - Matemático";
                    mySelect.appendChild(myOption3);
                    var myOption4 = document.createElement("option");
                    myOption4.setAttribute("value", "Humanidades Y Ciencias Sociales");
                    myOption4.textContent = "Humanidades Y Ciencias Sociales";
                    mySelect.appendChild(myOption4);
                    var myOption5 = document.createElement("option");
                    myOption5.setAttribute("value", "Químico - Biológico");
                    myOption5.textContent = "Químico - Biológico";
                    mySelect.appendChild(myOption5);
                }
            }
            function llamadaTabla() {
                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': 'LLAMADA_LISTA_MATERIAS' },
                    success: function (data) {
                        const result = JSON.parse(data);
                        if (result.message == 'right') {
                            $('#subjects_table tbody').html(result.content);
                            checkSubjectsTableSize();
                        }else if(result.message == 'empty'){
                            swal("¡CUIDADO!", "No se encontraron datos relacionados a la consulta solicitada. Intentelo de nuevo.", "warning");
                        }else {
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                    }
                });
            }
        }




        //boletas_historial
        if ($('body').data('title') === 'card_grades_history') {
            
            var report_card_kind = document.querySelector('#report_card_kind');

            //Por estudiante
            var input_search_student_id_card = document.querySelector('#input_search_student_id_card'),
                div_car_per_student = document.querySelector('#per_student'),
                semester_student_card = document.querySelector('#semester_student_card'),
                individual_student_preview_card = document.querySelector('#individual_student_preview_card');

            //por grupo
            const div_car_per_group = document.querySelector('#per_group'),
                button_teachers_group_preview_card = document.querySelector('#button_teachers_group_preview_card'),
                input_search_teacher_id_card = document.querySelector('#input_search_teacher_id_card'),
                teachers_group_card = document.querySelector('#teachers_group_card'),
                semester_teacher_card = document.querySelector('#semester_teacher_card'),
                kind_subjects_teacher_card = document.querySelector('#kind_subjects_teacher_card'),
                teachers_subject_card = document.querySelector('#teachers_subject_card');

            //por grupo con todas las materias
            var div_car_per_group_subjects = document.querySelector('#per_group_subjects'),
                semester_group_card = document.querySelector('#semester_group_card');

            //Historial academico
            var div_student_historial = document.querySelector('#student_historial'),
                input_search_student_id_record = document.querySelector('#input_search_student_id_record');

            eventListeners();
            callStudentID();
            checkExistMails();
            semesterStudentSwitch();
            callStudentName();

            //Por Grupo
            callTeacherID();
            getSchoolYearTeachers();
            semesterTeacherSwitch();
            callTeacherName();

            //Por grupo + materias
            getSchoolYearGroups();
            semesterGroupsSwitch();

            //Historial Academico
            callStudentIDRecord();
            callStudentNameRecord();

            function eventListeners() {
                //Por Estudiante
                input_search_student_id_card.addEventListener('input', callStudentName);
                report_card_kind.addEventListener('click', kind_grades_report);
                semester_student_card.addEventListener('input', semesterStudentSwitch);
                individual_student_preview_card.addEventListener('click', checkAndLaunchStudentCard);

                //Por grupo
                input_search_teacher_id_card.addEventListener('input', callTeacherName);
                semester_teacher_card.addEventListener('input', semesterTeacherSwitch);
                teachers_group_card.addEventListener('input', callTeacherName);
                semester_teacher_card.addEventListener('input', callTeacherName);
                kind_subjects_teacher_card.addEventListener('input', callTeacherName);
                button_teachers_group_preview_card.addEventListener('click', checkAndLaunchTeacherCard);

                //Por grupo + materias
                semester_group_card.addEventListener('input', semesterGroupsSwitch);

                //Historial academico
                input_search_student_id_record.addEventListener('input', callStudentNameRecord);
                button_preview_record.addEventListener('click', checkAnsLaunchStoryCard);

            }

            function kind_grades_report() {
                if (report_card_kind.value === 'per_student') {
                    div_car_per_student.style.display = 'block';
                    div_car_per_group.style.display = 'none';
                    div_car_per_group_subjects.style.display = 'none';
                    div_student_historial.style.display = 'none';
                }
                else if (report_card_kind.value === 'per_group') {
                    div_car_per_student.style.display = 'none';
                    div_car_per_group.style.display = 'block';
                    div_car_per_group_subjects.style.display = 'none';
                    div_student_historial.style.display = 'none';
                    semesterTeacherSwitch();
                    callTeacherID();
                }
                else if (report_card_kind.value === 'per_group_subjects') {
                    div_car_per_student.style.display = 'none';
                    div_car_per_group.style.display = 'none';
                    div_car_per_group_subjects.style.display = 'block';
                    div_student_historial.style.display = 'none';
                }
                else if (report_card_kind.value === 'student_historial') {
                    div_car_per_student.style.display = 'none';
                    div_car_per_group.style.display = 'none';
                    div_car_per_group_subjects.style.display = 'none';
                    div_student_historial.style.display = 'block';
                }
            }
            //Por estudiante
            function semesterStudentSwitch() {
                
                const semester = document.querySelector('#semester_student_card').value,
                    mySelect = document.querySelector('#kind_student_subjects_card');
                $('#kind_student_subjects_card').html('');
                if (semester == 'Primero' || semester == 'Segundo' || semester == 'Tercero' || semester == 'Cuarto') {
                    var myOption1 = document.createElement("option");
                    myOption1.setAttribute("value", "Tronco Común");
                    myOption1.textContent = "Tronco Común";

                    mySelect.appendChild(myOption1);

                } else if (semester == 'Quinto' || semester == 'Sexto') {
                    var myOption2 = document.createElement("option");
                    myOption2.setAttribute("value", "Económico - Administrativo");
                    myOption2.textContent = "Económico - Administrativo";
                    mySelect.appendChild(myOption2);
                    var myOption3 = document.createElement("option");
                    myOption3.setAttribute("value", "Físico - Matemático");
                    myOption3.textContent = "Físico - Matemático";
                    mySelect.appendChild(myOption3);
                    var myOption4 = document.createElement("option");
                    myOption4.setAttribute("value", "Humanidades Y Ciencias Sociales");
                    myOption4.textContent = "Humanidades Y Ciencias Sociales";
                    mySelect.appendChild(myOption4);
                    var myOption5 = document.createElement("option");
                    myOption5.setAttribute("value", "Químico - Biológico");
                    myOption5.textContent = "Químico - Biológico";
                    mySelect.appendChild(myOption5);
                }
            }

            function callStudentID() {
                const action = 'LLAMAR_ESTUDIANTES_ID';
                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': action },
                    success: function (data) {
                        const result = JSON.parse(data);
                        if (result.message == 'right') {
                            $('#search_student_id_card').html(result.content);
                        }else if(result.message == 'empty'){
                            swal("¡CUIDADO!", "No se encontraron datos relacionados a la consulta solicitada. Intentelo de nuevo.", "warning");
                        }else {
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                    }
                });
            }

            function callStudentName() {
                const action = 'LLAMAR_NOMBRE_ESTUDIANTE',
                    search_student_id = input_search_student_id_card.value;
                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': action, 'student_id': search_student_id },
                    success: function (data) {
                        const result = JSON.parse(data);
                        if (result.message == 'right') {
                            $('#individual_student_name_card').text(result.content);
                        }else if(result.message == 'empty'){
                            $('#individual_student_name_card').text('No Asignado');
                        }else {
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                        
                    }
                });
            }

            function checkAndLaunchStudentCard(e){
                
                const individual_student_name = $('#individual_student_name_card').text();
                if(individual_student_name.trim() === '' || individual_student_name === 'No Asignado' || individual_student_name === null || individual_student_name === undefined){
                    e.preventDefault();
                    swal("¡ADVERTENCIA!", "Debe ingresar un alumno valido", "warning");
                }
            }

        // Por Grupo

        function semesterTeacherSwitch() {
                
            const semester = document.querySelector('#semester_teacher_card').value,
                mySelect = document.querySelector('#kind_subjects_teacher_card'),
                semester_group_select = document.querySelector('#teachers_group_card');
            $('#kind_subjects_teacher_card').html('');
            $('#teachers_group_card').html('');
            if (semester == 'Primero' || semester == 'Segundo' || semester == 'Tercero' || semester == 'Cuarto') {
                var myOption1 = document.createElement("option");
                myOption1.setAttribute("value", "Tronco Común");
                myOption1.textContent = "Tronco Común";
                mySelect.appendChild(myOption1);
                
                var semester_option_A = document.createElement("option");
                    semester_option_A.setAttribute("value", "A");
                    semester_option_A.textContent = "A";
                    semester_group_select.appendChild(semester_option_A);
                    var semester_option_B = document.createElement("option");
                    semester_option_B.setAttribute("value", "B");
                    semester_option_B.textContent = "B";
                    semester_group_select.appendChild(semester_option_B);
                    var semester_option_C = document.createElement("option");
                    semester_option_C.setAttribute("value", "C");
                    semester_option_C.textContent = "C";
                    semester_group_select.appendChild(semester_option_C);
                    var semester_option_D = document.createElement("option");
                    semester_option_D.setAttribute("value", "D");
                    semester_option_D.textContent = "D";
                    semester_group_select.appendChild(semester_option_D);
                    var semester_option_E = document.createElement("option");
                    semester_option_E.setAttribute("value", "E");
                    semester_option_E.textContent = "E";
                    semester_group_select.appendChild(semester_option_E);

            } else if (semester == 'Quinto' || semester == 'Sexto') {
                var myOption2 = document.createElement("option");
                myOption2.setAttribute("value", "Económico - Administrativo");
                myOption2.textContent = "Económico - Administrativo";
                mySelect.appendChild(myOption2);
                var myOption3 = document.createElement("option");
                myOption3.setAttribute("value", "Físico - Matemático");
                myOption3.textContent = "Físico - Matemático";
                mySelect.appendChild(myOption3);
                var myOption4 = document.createElement("option");
                myOption4.setAttribute("value", "Humanidades Y Ciencias Sociales");
                myOption4.textContent = "Humanidades Y Ciencias Sociales";
                mySelect.appendChild(myOption4);
                var myOption5 = document.createElement("option");
                myOption5.setAttribute("value", "Químico - Biológico");
                myOption5.textContent = "Químico - Biológico";
                mySelect.appendChild(myOption5);

                var semester_option_A = document.createElement("option");
                    semester_option_A.setAttribute("value", "A");
                    semester_option_A.textContent = "A";
                    semester_group_select.appendChild(semester_option_A);
            }
        }

        function callTeacherID() {
            const action = 'LLAMAR_PROFESORES_ID';
            $.ajax({
                url: 'php/model_school.php', //This is the current doc
                type: 'POST',
                dataType: 'text', // add json datatype to get json
                data: { 'action': action },
                success: function (data) {
                    const result = JSON.parse(data);
                        if(result.message == 'right'){
                            $('#search_teacher_id_card').html(result.content);
                        }else if(result.message == 'empty'){
                        }else {
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                }
            });
        }

        function callTeacherName() {
            const action = 'LLAMAR_NOMBRE_PROFESOR',
                input_teacher_id = input_search_teacher_id_card .value;
            $.ajax({
                url: 'php/model_school.php', //This is the current doc
                type: 'POST',
                dataType: 'text', // add json datatype to get json
                data: { 'action': action, 'teacher_id': input_teacher_id},
                success: function (data) {
                        const result = JSON.parse(data);
                        if(result.message == 'right'){
                                callTeacherSubjects(result.content);
                        }else if(result.message == 'empty'){
                            callTeacherSubjects(result.content);
                        }else {
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                }
            });
        }

        function callTeacherSubjects(data) {

            if (data === null || data === undefined || data.trim() === '') {
                $('#teachers_name_card').text('No Asignado');
                $('#teachers_subject_card').html('');
            }
            else {
                callTeachersAvalibleSubjects();
                $('#teachers_name_card').text(data);
            }
        }

        function callTeachersAvalibleSubjects() {
        $.ajax({
            url: 'php/model_school.php', //This is the current doc
            type: 'POST',
            dataType: 'text', // add json datatype to get json
            data: { 'action': 'LLAMAR_MATERIAS_CLAVE_PROFESOR_CALIFICACIONES', 'teacher_id': input_search_teacher_id_card.value, 'semester': semester_teacher_card.value, 'kind_subjects': kind_subjects_teacher_card.value, 'student_group': teachers_group_card.value },
            success: function (data) {
                const result = JSON.parse(data);
                if(result.message == 'right'){
                    $('#teachers_subject_card').html(result.content);
                }else if(result.message == 'empty'){
                    swal("¡CUIDADO!", "No se encontraron datos relacionados a la consulta solicitada. Intentelo de nuevo.", "warning");
                }else {
                    swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                }
                
            }
        });
        }

        function getSchoolYearTeachers() {
            $.ajax({
                url: 'php/model_school.php', //This is the current doc
                type: 'POST',
                dataType: 'text', // add json datatype to get json
                data: { 'action': 'LLAMAR_FECHA', 'date': 'none', 'message': 'CICLO_ESCOLAR' },
                success: function (data) {
                const result = JSON.parse(data);
                if(result.message == 'right'){
                    $('#school_year_teacher_card').html(result.content);
                }else if(result.message == 'empty'){
                    swal("¡CUIDADO!", "No se encontraron datos relacionados a la consulta solicitada. Intentelo de nuevo.", "warning");
                }else {
                    swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                }
                }
            });
        }

        function checkAndLaunchTeacherCard(e){
            const teachers_name_card = $('#teachers_name_card').text();
            if(teachers_name_card.trim() === '' || teachers_name_card === 'No Asignado' || teachers_name_card === null || teachers_name_card === undefined){
                e.preventDefault();
                swal("¡ADVERTENCIA!", "Debe ingresar a un profesor valido", "warning");
            }
            if(teachers_subject_card.value === ''){
                e.preventDefault();
                swal("¡ADVERTENCIA!", "Debe ingresar a una materia valida", "warning");
            }
        }

        //Por grupo + materias
        function semesterGroupsSwitch() {
                
            const semester = document.querySelector('#semester_group_card').value,
                mySelect = document.querySelector('#kind_subjects_group_card'),
                semester_group_select = document.querySelector('#group_letter_card');
            $('#kind_subjects_group_card').html('');
            $('#group_letter_card').html('');
            if (semester == 'Primero' || semester == 'Segundo' || semester == 'Tercero' || semester == 'Cuarto') {
                var myOption1 = document.createElement("option");
                myOption1.setAttribute("value", "Tronco Común");
                myOption1.textContent = "Tronco Común";
                mySelect.appendChild(myOption1);
                
                var semester_option_A = document.createElement("option");
                    semester_option_A.setAttribute("value", "A");
                    semester_option_A.textContent = "A";
                    semester_group_select.appendChild(semester_option_A);
                    var semester_option_B = document.createElement("option");
                    semester_option_B.setAttribute("value", "B");
                    semester_option_B.textContent = "B";
                    semester_group_select.appendChild(semester_option_B);
                    var semester_option_C = document.createElement("option");
                    semester_option_C.setAttribute("value", "C");
                    semester_option_C.textContent = "C";
                    semester_group_select.appendChild(semester_option_C);
                    var semester_option_D = document.createElement("option");
                    semester_option_D.setAttribute("value", "D");
                    semester_option_D.textContent = "D";
                    semester_group_select.appendChild(semester_option_D);
                    var semester_option_E = document.createElement("option");
                    semester_option_E.setAttribute("value", "E");
                    semester_option_E.textContent = "E";
                    semester_group_select.appendChild(semester_option_E);
            } else if (semester == 'Quinto' || semester == 'Sexto') {
                var myOption2 = document.createElement("option");
                myOption2.setAttribute("value", "Económico - Administrativo");
                myOption2.textContent = "Económico - Administrativo";
                mySelect.appendChild(myOption2);
                var myOption3 = document.createElement("option");
                myOption3.setAttribute("value", "Físico - Matemático");
                myOption3.textContent = "Físico - Matemático";
                mySelect.appendChild(myOption3);
                var myOption4 = document.createElement("option");
                myOption4.setAttribute("value", "Humanidades Y Ciencias Sociales");
                myOption4.textContent = "Humanidades Y Ciencias Sociales";
                mySelect.appendChild(myOption4);
                var myOption5 = document.createElement("option");
                myOption5.setAttribute("value", "Químico - Biológico");
                myOption5.textContent = "Químico - Biológico";
                mySelect.appendChild(myOption5);
                
                var semester_option_A = document.createElement("option");
                    semester_option_A.setAttribute("value", "A");
                    semester_option_A.textContent = "A";
                    semester_group_select.appendChild(semester_option_A);
            }
        }

        function getSchoolYearGroups() {
            $.ajax({
                url: 'php/model_school.php', //This is the current doc
                type: 'POST',
                dataType: 'text', // add json datatype to get json
                data: { 'action': 'LLAMAR_FECHA', 'date': 'none', 'message': 'CICLO_ESCOLAR' },
                success: function (data) { 
                    const result = JSON.parse(data);
                        if(result.message == 'right'){
                            $('#school_year_group_card').html(result.content);
                        }else if(result.message == 'empty'){
                            swal("¡CUIDADO!", "No se encontraron datos relacionados a la consulta solicitada. Intentelo de nuevo.", "warning");
                        }else {
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                }
            });
        }

        //Historial de calificaciones
        function callStudentIDRecord() {
            const action = 'LLAMAR_ESTUDIANTES_ID';
            $.ajax({
                url: 'php/model_school.php', //This is the current doc
                type: 'POST',
                dataType: 'text', // add json datatype to get json
                data: { 'action': action },
                success: function (data) {
                const result = JSON.parse(data);
                if(result.message == 'right'){
                    $('#search_student_id_record').html(result.content);
                }else if(result.message == 'empty'){
                    swal("¡CUIDADO!", "No se encontraron datos relacionados a la consulta solicitada. Intentelo de nuevo.", "warning");
                }else {
                    swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                }
                }
            });
        }

        function callStudentNameRecord() {
            const action = 'LLAMAR_NOMBRE_ESTUDIANTE',
                search_student_id = input_search_student_id_record.value;
            $.ajax({
                url: 'php/model_school.php', //This is the current doc
                type: 'POST',
                dataType: 'text', // add json datatype to get json
                data: { 'action': action, 'student_id': search_student_id },
                success: function (data) {
                    const result = JSON.parse(data);
                        if (result.message == 'right') {
                            $('#students_name_record').text(result.content);
                            callStudetStoryCard(search_student_id);
                        }else if(result.message == 'empty'){
                            $('#students_name_record').text('No Asignado');
                            $('#academic_historial_card').css('display', 'none');
                        }else {
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                }
            });
        }
        function checkAnsLaunchStoryCard(e){
            const students_name_record = $('#students_name_record').text();
            if(students_name_record.trim() === '' || students_name_record === 'No Asignado' || students_name_record === null || students_name_record === undefined){
                e.preventDefault();
                swal("¡ADVERTENCIA!", "Debe ingresar un alumno valido", "warning");
            }
        }

        function callStudetStoryCard(id_student){
            $.ajax({
                url: 'php/model_school.php', //This is the current doc
                type: 'POST',
                dataType: 'text', // add json datatype to get json
                data: { 'action': 'LLAMAR_CALIFICACIONES_ESTUDIANTE_HISTORIAL', 'student_id': id_student },
                success: function (data) {
                    const result = JSON.parse(data);
                if(result.message == 'right'){
                    if(data === null || data === undefined || data.trim() === ''){
                    }else{
                        for(var i = 1; i < 7;i++){
                            $('#tbody_semester_'+i).html('');
                        }
                        $('#academic_historial_card').css('display', 'block');
                        for(var i=0; i < result.content.length; i ++){
                            if(result.content[i].semestre === 'Primero'){
                                var average_changer = '';
                                $('#header_cicle_1_record').text('CICLO ESCOLAR ' + result.content[i].ciclo_escolar);
                                if(result.content[i].estado_parcial_1 === '1' && result.content[i].estado_parcial_2 === '1' && result.content[i].estado_parcial_final === '1' ){
                                    average_changer = result.content[i].promedio;
                                }else{
                                    average_changer = 'NO ASIGNADO';
                                }
                                var content_table_1 ='<tr><td class="td_left_content">'+(result.content[i].nombre_materia).toUpperCase()+'</td>'
                                + '<td class="td_center_content">'+(average_changer).toUpperCase()+'</td>'
                                + '<td class="td_center_content">'+(result.content[i].creditos).toUpperCase()+'</td></tr>';
                                $('#tbody_semester_1').append(content_table_1);
                            }
                        '1'
                        //2
                            if(result.content[i].semestre === 'Segundo'){
                                var average_changer = '';
                                if(result.content[i].estado_parcial_1 === '1' && result.content[i].estado_parcial_2 === '1' && result.content[i].estado_parcial_final === '1' ){
                                    average_changer = result.content[i].promedio;
                                }else{
                                    average_changer = 'NO ASIGNADO';
                                }
                                var content_table_2 ='<tr><td class="td_left_content">'+(result.content[i].nombre_materia).toUpperCase()+'</td>'
                                + '<td class="td_center_content">'+(average_changer).toUpperCase()+'</td>'
                                + '<td class="td_center_content">'+(result.content[i].creditos).toUpperCase()+'</td></tr>';
                                $('#tbody_semester_2').append(content_table_2);
                            }
                        //3
                            if(result.content[i].semestre === 'Tercero'){
                                var average_changer = '';
                                $('#header_cicle_2_record').text('CICLO ESCOLAR ' + result.content[i].ciclo_escolar);
                                if(result.content[i].estado_parcial_1 === '1' && result.content[i].estado_parcial_2 === '1' && result.content[i].estado_parcial_final === '1' ){
                                    average_changer = result.content[i].promedio;
                                }else{
                                    average_changer = 'NO ASIGNADO';
                                }
                                var content_table_3 ='<tr><td class="td_left_content">'+(result.content[i].nombre_materia).toUpperCase()+'</td>'
                                + '<td class="td_center_content">'+(average_changer).toUpperCase()+'</td>'
                                + '<td class="td_center_content">'+(result.content[i].creditos).toUpperCase()+'</td></tr>';
                                $('#tbody_semester_3').append(content_table_3);
                            }
                        //4
                            if(result.content[i].semestre === 'Cuarto'){
                                var average_changer = '';
                                if(result.content[i].estado_parcial_1 === '1' && result.content[i].estado_parcial_2 === '1' && result.content[i].estado_parcial_final === '1' ){
                                    average_changer = result.content[i].promedio;
                                }else{
                                    average_changer = 'NO ASIGNADO';
                                }
                                var content_table_4 ='<tr><td class="td_left_content">'+(result.content[i].nombre_materia).toUpperCase()+'</td>'
                                + '<td class="td_center_content">'+(average_changer).toUpperCase()+'</td>'
                                + '<td class="td_center_content">'+(result.content[i].creditos).toUpperCase()+'</td></tr>';
                                $('#tbody_semester_4').append(content_table_4);
                            }
                        //5
                            if(result.content[i].semestre === 'Quinto'){
                                var average_changer = '';
                                $('#header_cicle_3_record').text('CICLO ESCOLAR ' + result.content[i].ciclo_escolar);
                                if(result.content[i].estado_parcial_1 === '1' && result.content[i].estado_parcial_2 === '1' && result.content[i].estado_parcial_final === '1' ){
                                    average_changer = result.content[i].promedio;
                                }else{
                                    average_changer = 'NO ASIGNADO';
                                }
                                var content_table_5 ='<tr><td class="td_left_content">'+(result.content[i].nombre_materia).toUpperCase()+'</td>'
                                + '<td class="td_center_content">'+(average_changer).toUpperCase()+'</td>'
                                + '<td class="td_center_content">'+(result.content[i].creditos).toUpperCase()+'</td></tr>';
                                $('#tbody_semester_5').append(content_table_5);
                            }
                        //6
                            if(result.content[i].semestre === 'Sexto'){
                                var average_changer = '';
                                if(result.content[i].estado_parcial_1 === '1' && result.content[i].estado_parcial_2 === '1' && result.content[i].estado_parcial_final === '1' ){
                                    average_changer = result.content[i].promedio;
                                }else{
                                    average_changer = 'NO ASIGNADO';
                                }
                                var content_table_6 ='<tr><td class="td_left_content">'+(result.content[i].nombre_materia).toUpperCase()+'</td>'
                                + '<td class="td_center_content">'+(average_changer).toUpperCase()+'</td>'
                                + '<td class="td_center_content">'+(result.content[i].creditos).toUpperCase()+'</td></tr>';
                                $('#tbody_semester_6').append(content_table_6);
                            }
                        }
                    }
                }else if(result.message == 'empty'){
                    swal("¡CUIDADO!", "No se encontraron datos relacionados a la consulta solicitada. Intentelo de nuevo.", "warning");
                }else {
                    swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                }
                    
                }
            });
        }

        }
        //Administracion de cuentas
        if ($('body').data('title') === 'body_manage_accounts') {
            checkExistMails();
            var user_kind = document.querySelector('#kind_user');
            const user_id = document.querySelector('#user_id'),
            UsersList = document.querySelector('#tbody_users_loggin'),
            user_password = document.querySelector('#password_user'),
            button_get_new_pass = document.querySelector('#button_get_new_pass'),
            button_update = document.querySelector('#button_update'),
            button_cleanup = document.querySelector('#button_cleanup'),
            input_search = document.querySelector('#search_user_information'); 

            eventListeners();
            callUsers();
            cleanUpForm();

            function eventListeners(){
                button_get_new_pass.addEventListener('click', getNewPassword);
                button_save_user.addEventListener('click', validateUser);
                button_update.addEventListener('click', updateUser);
                button_cleanup.addEventListener('click', cleanUpForm);
                user_kind.addEventListener('input', changeStateKindUser);
                if (UsersList) {
                    UsersList.addEventListener('click', editDeleteUser);
                }
                input_search.addEventListener('input', searchUser);
            }

            $('#user_id').keydown(function(e) { 
                 if (e.keyCode == 32) {
                    swal("¡ADVERTENCIA!", "No se pueden introducir espacios en la matricula del usuario", "warning");
                     return false; 
                    } 
                });
            
            function cleanUpForm(){
                document.querySelector('form').reset();    
            }

            function changeStateKindUser(){
                if(user_kind.value != 'manager'){
                    user_id.disabled = true;
                    user_id.value = '';
                    user_password.value = '';
                }else{
                    user_id.value = '';
                    user_password.value = '';
                    user_id.disabled = false;
                }
            }

            function getNewPassword(){
                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': 'OBTENER_NUEVA_CONTRASENA'},
                    success: function (data) {
                        const result = JSON.parse(data);
                        if(result.message == 'right'){
                            $('#password_user').val(result.content);
                        }else if(result.message == 'empty'){
                            swal("¡CUIDADO!", "No se encontraron datos relacionados a la consulta solicitada. Intentelo de nuevo.", "warning");
                        }else {
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                    }
                });
            }

            function callUsers(){
                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': 'LLAMADA_LISTA_USUARIOS' },
                    success: function (data) {
                        const result = JSON.parse(data);
                        if(result.message == 'right'){
                            $('#tbody_users_loggin').html(result.result_list);
                        }else if(result.message == 'empty'){
                            swal("¡CUIDADO!", "No se encontraron datos relacionados a la consulta solicitada. Intentelo de nuevo.", "warning");
                        }else {
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                    }
                });
            }

            function  validateUser(){
                if(user_id.value == $('#session_owner').text()){
                    swal("¡ADVERTENCIA!", "El administrador que intenta guardar es el mismo que actualmente esta usando para esta sesión", "warning");
                }else{
                if(user_kind.value == '' || user_id.value == '' || user_password.value == ''){
                    swal("¡ADVERTENCIA!", "Uno o ambos campos estan vacios", "warning");
                }
                else if(user_kind.value != 'manager'){
                    swal("¡ADVERTENCIA!", "Solo se pueden crear administradores en esta area", "warning");
                }
                else{
                        $.ajax({
                            url: 'php/model_school.php', //This is the current doc
                            type: 'POST',
                            dataType: 'text', // add json datatype to get json
                            data: { 'action': 'COMPROBAR_MATRICULA_USUARIO', 'id_user': user_id.value},
                            success: function (data) {
                                const result = JSON.parse(data);
                                if (result.response === 'true') {
                                    swal({
                                        title: "¡ADVERTENCIA!",
                                        text: "Hay un registro con la misma matricula previamente guardado. ¿Desea sobreescribirlo?",
                                        icon: "warning",
                                        buttons: [
                                            'No, cancelar',
                                            'Si, continuar'
                                        ],
                                        dangerMode: true,
                                    }).then(function (isConfirm) {
                                        if (isConfirm) {
                                            updateUser();
                                            
                                        } else {
        
                                        }
                                    });
                                } else {
                                    saveUser();
                                }
                            }
                        });
                }
            }
            }

            function saveUser(){
                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: {
                        'user_id': user_id.value,
                        'user_password': user_password.value,
                        'user_kind': user_kind.value,
                        'action': 'GUARDAR_USUARIO_NUEVO'
                    },
                    success: function (data) {

                        const result = JSON.parse(data);
                        if (result.message == 'right') {
                            swal("¡EXITO!", "El registro se guardo correctamente", "success");
                            cleanUpForm();
                            callUsers();
                        } else if(result.message == 'wrong'){
                            swal("¡ERROR!", "Hubo un problema con el envio del mensaje. Intentelo de nuevo.", "error");
                        }else {
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                    }
                });
            }

            function updateUser(){
                if(user_id.value == $('#session_owner').text()){
                    swal("¡ADVERTENCIA!", "No se pueden realizar cambios al administrador con el que tiene la sesión activa", "warning");
                }else{
                if(user_kind.value == '' || user_id.value == '' || user_password.value == ''){
                    swal("¡ADVERTENCIA!", "Uno o ambos campos estan vacios", "warning");
                }
                else{
                    $.ajax({
                        url: 'php/model_school.php', //This is the current doc
                        type: 'POST',
                        dataType: 'text', // add json datatype to get json
                        data: { 'action': 'COMPROBAR_MATRICULA_USUARIO', 'id_user': user_id.value},
                        success: function (data) {
                            const result = JSON.parse(data);
                            if (result.response === 'true') {
                                $.ajax({
                                    url: 'php/model_school.php', //This is the current doc
                                    type: 'POST',
                                    dataType: 'text', // add json datatype to get json
                                    data: { 'action': 'ACTUALIZAR_CONTRASENA_USUARIO', 'id':user_id.value, 'password':user_password.value, 'kind':user_kind.value},
                                    success: function (data) {
                                        const result = JSON.parse(data);
                                        if(result.message == 'right'){
                                            swal("¡CORRECTO!", "Registro actualizado correctamente", "success");
                                            cleanUpForm();
                                            callUsers();
                                        }else if(result.message == 'wrong'){
                                            swal("¡ERROR!", "Hubo un problema con el envio del mensaje. Intentelo de nuevo.", "error");
                                        }else {
                                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                                        }
                                    }
                                });
                            } else {
                                swal("¡ERROR!", "El usuario que intenta actualizar no existe", "error");
                            }
                        }
                    });
                    
                }
            }
        }
               

            function editDeleteUser(e) {
                e.preventDefault();
                if(e.target.parentElement.getAttribute('data-id') == $('#session_owner').text()){
                    swal("¡ADVERTENCIA!", "No se pueden realizar cambios al administrador con el que tiene la sesión activa", "warning");
                }
                else{
                if (e.target.parentElement.classList.contains('btn_edit_user')) {
                    const id = e.target.parentElement.getAttribute('data-id');
                        $.ajax({
                            url: 'php/model_school.php', //This is the current doc
                            type: 'POST',
                            dataType: 'text', // add json datatype to get json
                            data: { 'action': 'LLENAR_FORM_USUARIO', 'id': id },
                            success: function (data) {
                                var result = JSON.parse(data);
                                if (result.message == 'right') {
                                    $('body, html').animate({
                                        scrollTop: '0px'
                                    }, 300);
                                    user_kind.value = `${result.result_query[2]}`;
                                    changeStateKindUser();
                                    user_id.value = `${result.result_query[0]}`;
                                    user_password.value = `${result.result_query[1]}`;
                                } else if(result.message == 'empty'){
                                    swal("¡CUIDADO!", "No se encontraron datos relacionados a la consulta solicitada. Intentelo de nuevo.", "warning");
                                }else {
                                    swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                                }
                            }
                        });
                }
                if (e.target.parentElement.classList.contains('btn_delete_user')) {
                    swal({
                        title: "¡ADVERTENCIA!",
                        text: "¿Seguro que desea borrar al usuario seleccionado?",
                        icon: "warning",
                        buttons: [
                            'No, cancelar',
                            'Si, continuar'
                        ],
                        dangerMode: true,
                    }).then(function (isConfirm) {
                        if (isConfirm) {
                            const id = e.target.parentElement.getAttribute('data-id');
                            const xhr = new XMLHttpRequest();
                            const infoStudent = new FormData();
                            infoStudent.append('id', id);
                            infoStudent.append('action', 'ELIMINAR_USUARIO');
                            xhr.open('POST', 'php/model_school.php', true);
                            xhr.onload = function () {
                                if (this.status === 200) {
                                    const result = JSON.parse(xhr.responseText);
                                    if(result.message == 'right'){
                                        swal("¡CORRECTO!", "Usuario eliminado correctamente", "success");
                                        callUsers();
                                        cleanUpForm();
                                    }else if(result.message == 'wrong'){
                                        swal("¡ERROR!", "El usuario no se pudo eliminar. Intente de nuevo", "error");
                                    }else {
                                        swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                                    }
                        }
                    }
                    xhr.send(infoStudent);
                        } else {
                        }
                    });
                }
            }
            }
            function searchUser(e) {
                const expresion = new RegExp(e.target.value, "i"),
                    rows = document.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    row.style.display = 'none';


                    if (row.childNodes[3].textContent.replace(/\s/g, " ").search(expresion) != -1 ||
                        row.childNodes[7].textContent.replace(/\s/g, " ").search(expresion) != -1) {
                        row.style.display = 'table-row';
                    }
                })
            }
        }
    //Reporte de errores (correos)
    if ($('body').data('title') === 'loggin_error_reports') {
        $('#notification_icon').css('display', 'none');
        $('#notification_window_area').css('display', 'none');
        const problem_name = document.querySelector('#problem_name'),
        problem_mail = document.querySelector('#problem_mail'),
        problem_subject = document.querySelector('#problem_subject'),
        problem_description = document.querySelector('#problem_description'),
        problem_answer = document.querySelector('#problem_answer'),
        button_send_mail = document.querySelector('#button_send_mail'),
        cleanup_button = document.querySelector('#cleanup_button'),
        MailsList = document.querySelector('#tbody_mails');

        eventListeners();
        callMailsList();
        cleanUpForm();

        function eventListeners(){

            button_send_mail.addEventListener('click', sendMail);
            cleanup_button.addEventListener('click', cleanUpForm);
            if (MailsList) {
                MailsList.addEventListener('click', editDeleteMails);
            }
        }

        function cleanUpForm(){
            document.querySelector('form').reset();    
        }

        function callMailsList(){
                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': 'LLAMADA_LISTA_CORREOS' },
                    success: function (data) {
                        const result = JSON.parse(data);
                        if(result.message == 'right'){
                            $('#tbody_mails').html(result.result_list);
                        }else if(result.message == 'empty'){
                            swal("¡CUIDADO!", "No se encontraron datos relacionados a la consulta solicitada. Intentelo de nuevo.", "warning");
                        }else {
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                    }
                });
        }

        function sendMail(){
            const companyMail = 'PREPARATORIA NAYAR (REPORTE DE PROBLEMAS)', subjectResponse = 'PROBLEMAS AL INICIO DE SESION EN LA PLATAFORMA DE LA PREPARATORIA NAYAR';
            if(problem_name.value == '' || problem_mail.value == '' || problem_subject.value == '' || problem_description.value == '' || problem_answer.value == ''){
                swal("¡ADVERTENCIA!", "Todos los campos deben estar correctamente llenados para continuar", "warning");
            }else{
                $.ajax({
                    url: 'php/model_school.php', //This is the current doc
                    type: 'POST',
                    dataType: 'text', // add json datatype to get json
                    data: { 'action': 'MANDAR_CORREO', 'problem_name':problem_name.value, 
                    'problem_mail': problem_mail.value, 'problem_subject': problem_subject.value, 
                    'problem_answer': problem_answer.value, 'mail_school' : companyMail,
                    'subjectResponse':subjectResponse},
                    success: function (data) {
                        const result = JSON.parse(data);
                        if(result.message == 'right'){
                            cleanUpForm();
                            swal("¡CORRECTO!", "El correo ha sido enviado satisfactoriamente", "success");
                        }else if(result.message == 'wrong'){
                            swal("¡ERROR!", "No se pudo realizar el envio del correo. Intente de nuevo", "error");
                        }else {
                            swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                        }
                    }
                });
            }
    }

        function editDeleteMails(e) {
            e.preventDefault();
            
            if (e.target.parentElement.classList.contains('btn_edit_mail')) {
                const id = e.target.parentElement.getAttribute('data-id');

                    $.ajax({
                        url: 'php/model_school.php', //This is the current doc
                        type: 'POST',
                        dataType: 'text', // add json datatype to get json
                        data: { 'action': 'LLENAR_FORM_CORREO', 'id': id },
                        success: function (data) {
                            var result = JSON.parse(data);
                            if(result.message == 'right'){
                                $('body, html').animate({
                                    scrollTop: '0px'
                                }, 300);
                                problem_name.value = `${result.content[3]}`;
                                problem_subject.value = `${result.content[4]}`;
                                problem_description.value = `${result.content[5]}`;
                                problem_mail.value = `${result.content[6]}`;
                                callMailsList();
                            }else if(result.message == 'empty'){
                                swal("¡CUIDADO!", "No se encontraron datos relacionados a la consulta solicitada. Intentelo de nuevo.", "warning");
                            }else {
                                swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                            }
                        }
                    });
            }
            

            if (e.target.parentElement.classList.contains('btn_delete_mail')) {

                swal({
                    title: "¡ADVERTENCIA!",
                    text: "¿Seguro que desea borrar el correo seleccionado?",
                    icon: "warning",
                    buttons: [
                        'No, cancelar',
                        'Si, continuar'
                    ],
                    dangerMode: true,
                }).then(function (isConfirm) {
                    if (isConfirm) {

                        const id = e.target.parentElement.getAttribute('data-id');
                        const xhr = new XMLHttpRequest();
                        const infoMail = new FormData();
                        infoMail.append('id', id);
                        infoMail.append('action', 'ELIMINAR_CORREO');
                        xhr.open('POST', 'php/model_school.php', true);
                        xhr.onload = function () {
                            if (this.status === 200) {
                                const result = JSON.parse(xhr.responseText);
                                if (result.response == 'right') {
                                    swal("¡CORRECTO!", "El correo ha sido borrado exitosamente", "success");
                                    cleanUpForm();
                                    callMailsList();
                                }else if(result.response == 'wrong'){
                                    swal("¡ERROR!", "Hubo un problema al realizar el borrado. Intentelo de nuevo.", "error");
                                }else{
                                    swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                                }
                            }
                        }
                        xhr.send(infoMail);
                    } else {

                    }
                });
            }
        }
}
//concentrado de calificaciones PROFESORES
if ($('body').data('title') == 'body_concentrated_ratings_teachers') {
    const save_button = document.querySelector('#save_button'),
        semester_student_grades = document.querySelector('#semester_student'),
        group = document.querySelector('#student_group'),
        school_cycle = document.querySelector('#school_year_student'),
        semesters = document.querySelector('#semester_student'),
        optional_classes = document.querySelector('#kind_student_subjects'),
        subject = document.querySelector('#student_subject'),
        cleanup_button = document.querySelector('#cleanup_button'),
        input_regularization_score = document.querySelector('#input_regularization_score'),
        button_cancel = document.querySelector('#button_cancel'),
        button_save = document.querySelector('#button_save');

    const numberColumns = 14;
    eventListeners();
    getSchoolYearNGeneration();
    checkSeasonDateStatus();

    function eventListeners() {
        save_button.addEventListener('click', saveStudentGrades);
        semester_student_grades.addEventListener('input', semesterStudentGradesSwitch);
        group.addEventListener('input', callTeacherSubjects);
        semesters.addEventListener('input', callTeacherSubjects);
        optional_classes.addEventListener('input', callTeacherSubjects);
        subject.addEventListener('input', callTeacherStudents);
        cleanup_button.addEventListener('click', cleanUpForm);
        input_regularization_score.addEventListener('input', checkScore);
        button_cancel.addEventListener('click', hidePopUp);
        button_save.addEventListener('click', getBackValues);
    }

    function cleanUpForm() {
        document.querySelector('form').reset();
        $('#student_subject').html('');
        $('#teachers_name').text('');
        semesterStudentGradesSwitch();
        callTeacherStudents();
        $('#teachers_name').html('No Asignado');
    }
    function checkSeasonDateStatus(){
        $.ajax({
            url: 'php/model_school.php', //This is the current doc
            type: 'POST',
            dataType: 'text', // add json datatype to get json
            data: { 'action': 'VERIFICAR_FECHAS_TEMPORADA_CALIFICACION_PROFESORES'},
            success: function (data) {
                var result = JSON.parse(data);
                if(result.message = 'right'){
                    if(result.date_status == 'true'){
                        $('.grades_screen_teachers').show();
                        $('#activity_season').val(result.activity);
                        callTeacherSubjects();
                    }else{
                        $('.principal_screen_teachers').show();
                        getCompleteName();
                    }
                }else if(result.message = 'empty'){
                    swal("¡CUIDADO!", "No se encontraron datos relacionados a la consulta solicitada. Intentelo de nuevo.", "warning");
                }else{
                    swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                }
            }
        });
    }

    function getCompleteName(){
        const id = $('#session_owner').text();
        $.ajax({
            url: 'php/model_school.php', //This is the current doc
            type: 'POST',
            dataType: 'text', // add json datatype to get json
            data: {'action' : 'NOMBRE_COMPLETO_PROFESOR', 'id': id},
            success: function (data) {
                var result = JSON.parse(data);
                if(result.message == 'right'){
                    $('#complete_teacher_name').text(result.result_query[0]);
                }else if(result.message == 'empty'){
                    swal("¡CUIDADO!", "No se encontraron datos relacionados a la consulta solicitada. Intentelo de nuevo.", "warning");
                }else {
                    swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                }
                
            }
        });
    }

    function semesterStudentGradesSwitch() {
        const semester = document.querySelector('#semester_student').value,
            mySelect = document.querySelector('#kind_student_subjects'),
            semester_group_select = document.querySelector('#student_group');
        $('#kind_student_subjects').html('');
        $('#student_group').html('');
        if (semester == 'Primero' || semester == 'Segundo' || semester == 'Tercero' || semester == 'Cuarto') {
            var myOption1 = document.createElement("option");
            myOption1.setAttribute("value", "Tronco Común");
            myOption1.textContent = "Tronco Común";
            mySelect.appendChild(myOption1);

            var semester_option_A = document.createElement("option");
                    semester_option_A.setAttribute("value", "A");
                    semester_option_A.textContent = "A";
                    semester_group_select.appendChild(semester_option_A);
                    var semester_option_B = document.createElement("option");
                    semester_option_B.setAttribute("value", "B");
                    semester_option_B.textContent = "B";
                    semester_group_select.appendChild(semester_option_B);
                    var semester_option_C = document.createElement("option");
                    semester_option_C.setAttribute("value", "C");
                    semester_option_C.textContent = "C";
                    semester_group_select.appendChild(semester_option_C);
                    var semester_option_D = document.createElement("option");
                    semester_option_D.setAttribute("value", "D");
                    semester_option_D.textContent = "D";
                    semester_group_select.appendChild(semester_option_D);
                    var semester_option_E = document.createElement("option");
                    semester_option_E.setAttribute("value", "E");
                    semester_option_E.textContent = "E";
                    semester_group_select.appendChild(semester_option_E);

        } else if (semester == 'Quinto' || semester == 'Sexto') {
            var myOption2 = document.createElement("option");
            myOption2.setAttribute("value", "Económico - Administrativo");
            myOption2.textContent = "Económico - Administrativo";
            mySelect.appendChild(myOption2);
            var myOption3 = document.createElement("option");
            myOption3.setAttribute("value", "Físico - Matemático");
            myOption3.textContent = "Físico - Matemático";
            mySelect.appendChild(myOption3);
            var myOption4 = document.createElement("option");
            myOption4.setAttribute("value", "Humanidades Y Ciencias Sociales");
            myOption4.textContent = "Humanidades Y Ciencias Sociales";
            mySelect.appendChild(myOption4);
            var myOption5 = document.createElement("option");
            myOption5.setAttribute("value", "Químico - Biológico");
            myOption5.textContent = "Químico - Biológico";
            mySelect.appendChild(myOption5);

            var semester_option_A = document.createElement("option");
            semester_option_A.setAttribute("value", "A");
            semester_option_A.textContent = "A";
            semester_group_select.appendChild(semester_option_A);
        }
    }

    function getSchoolYearNGeneration() {
        $.ajax({
            url: 'php/model_school.php', //This is the current doc
            type: 'POST',
            dataType: 'text', // add json datatype to get json
            data: { 'action': 'LLAMAR_FECHA', 'date': 'none', 'message': 'CICLO_ESCOLAR' },
            success: function (data) {
                var result = JSON.parse(data);
                if(result.message == 'right'){
                    $('#school_year_student').html(result.content);
                }else if(result.message == 'empty'){
                    swal("¡CUIDADO!", "No se encontraron datos relacionados a la consulta solicitada. Intentelo de nuevo.", "warning");
                }else {
                    swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                }
                
            }
        });
        $.ajax({
            url: 'php/model_school.php', //This is the current doc
            type: 'POST',
            dataType: 'text', // add json datatype to get json
            data: { 'action': 'LLAMAR_FECHA', 'date': 'none', 'message': 'GENERACION' },
            success: function (data) {
                var result = JSON.parse(data);
                if(result.message = 'right'){
                    $('#school_generation_student').html(result.content);
                }else if(result.message == 'empty'){
                    swal("¡CUIDADO!", "No se encontraron datos relacionados a la consulta solicitada. Intentelo de nuevo.", "warning");
                }else {
                    swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                }
            }
        });
    }

    function callTeacherStudents() {
        const action = 'LLAMAR_PROFESOR_ESTUDIANTES',
            search_teacher_id = $('#session_owner').text();
        $.ajax({
            url: 'php/model_school.php', //This is the current doc
            type: 'POST',
            dataType: 'text', // add json datatype to get json
            data: { 'action': action, 'teacher_id': search_teacher_id, 'group': group.value, 'semesters': semesters.value, 'optional_classes': optional_classes.value, 'subject': subject.value },
            success: function (data) {
                const result = JSON.parse(data);
                if(result.message == 'right'){
                    $('#tbody_all_students_grades').html(result.students_row);
                    $('#number_of_rows').html(result.number_rows);
                    setEventListenersToStudents();
                    validateSeason();
                }else if(result.message == 'empty'){
                    $('#tbody_all_students_grades').html('');
                    $('#number_of_rows').html(0);
                    setEventListenersToStudents();
                    validateSeason();
                }else {
                    swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                }
            }
        });
    }
    function setEventListenersToStudents() {
        const numberRows = $('#number_of_rows').text();
        let array = [];
        for (var i = 0; i < numberRows; i++) {
            array[i] = [];
            for (var j = 2; j <= 6; j += 2) {
                array[i][j] = $('#col-' + i + '-' + j + '');
                $(array[i][j]).on('input', function () {
                    makeAverage($(this).attr('id'));
                });
            }
            for (var j = 9; j <= numberColumns ; j += 4) {
                array[i][j] = $('#col-' + i + '-' + j);
                $(array[i][j]).on('click', function () {
                    launchPopUp($(this).attr('data-id'));
                });
            }
        }
    }

    function validateSeason(){
        const numberRows = $('#number_of_rows').text();
        if($('#activity_season').val() == 'par_1'){
            for (var i = 0; i < numberRows; i++) {
            for (var j = 4; j <= 13; j++) {
                if(j == 4 || j == 5 || j == 6 || j == 7 ){
                    $('#col-' + i + '-' + j + '').addClass('blocked_input');
                    $('#col-' + i + '-' + j + '').prop('disabled', true);
                }else if(j == 9 || j == 13){
                    $('#col-' + i + '-' + j + '').removeClass('status_cog_red');
                    $('#col-' + i + '-' + j + '').removeClass('status_cog_green');
                    $('#col-' + i + '-' + j + '').addClass('status_cog_gray');
                    $('#col-' + i + '-' + j + '').prop('disabled', true);
                }
                }
            }
        }else if($('#activity_season').val() == 'par_2'){
            for (var i = 0; i < numberRows; i++) {
            for (var j = 2; j <= 13; j++) {
                if(j == 2 || j == 3 || j == 6 || j == 7){
                    $('#col-' + i + '-' + j + '').addClass('blocked_input');
                    $('#col-' + i + '-' + j + '').prop('disabled', true);
                }else if(j == 9 || j == 13){
                    $('#col-' + i + '-' + j + '').removeClass('status_cog_red');
                    $('#col-' + i + '-' + j + '').removeClass('status_cog_green');
                    $('#col-' + i + '-' + j + '').addClass('status_cog_gray');
                    $('#col-' + i + '-' + j + '').prop('disabled', true);
                }
            }}
        }else if($('#activity_season').val() == 'par_final'){
            for (var i = 0; i < numberRows; i++) {
            for (var j = 2; j <= 13; j++) {
                if(j == 2 || j == 3 || j == 4 || j == 5){
                    $('#col-' + i + '-' + j + '').addClass('blocked_input');
                    $('#col-' + i + '-' + j + '').prop('disabled', true);
                }else if(j == 9 || j == 13){
                    $('#col-' + i + '-' + j + '').removeClass('status_cog_red');
                    $('#col-' + i + '-' + j + '').removeClass('status_cog_green');
                    $('#col-' + i + '-' + j + '').addClass('status_cog_gray');
                    $('#col-' + i + '-' + j + '').prop('disabled', true);
                }
                
                }
            }
        }else if($('#activity_season').val() == 'extra_test_both'){
            for (var i = 0; i < numberRows; i++) {
            for (var j = 2; j <= 13; j++) {
                if(j == 2 || j == 3 || j == 4 || j == 5 || j == 6 || j == 7){
                    $('#col-' + i + '-' + j + '').addClass('blocked_input');
                    $('#col-' + i + '-' + j + '').prop('disabled', true);
                }
                }
            }
        }else if($('#activity_season').val() == 'all'){
        }
    }

    function makeAverage(id) {
        const positions = id.split('-'),
            row = positions[1];
        let actualCase = $('#' + id);
        if (actualCase.val() > 10) {
            swal("¡CUIDADO!", "El dato insertado no debe ser mayor a 10", "warning");
            actualCase.val('');
        } else if (actualCase.val() < 0) {
            swal("¡CUIDADO!", "El dato insertado no debe ser menor a 0", "warning");
            actualCase.val('');
        } else {
            if (!(actualCase.val() == '' || actualCase.val() == undefined || actualCase.val() == null)) {
                let decimalSplitted = actualCase.val().toString().match(/^-?\d+(?:\.\d{0,1})?/)[0];;
                actualCase.val(parseFloat(decimalSplitted));
                let resultado = ($('#col-' + row + '-2').val() * 0.25) + ($('#col-' + row + '-4').val() * 0.25) + ($('#col-' + row + '-6').val() * 0.5);
                if (resultado >= 6) {
                        $('#col-' + row + '-8').val(resultado.toFixed()).html(resultado.toFixed());
                } else if (resultado < 6) {
                        $('#col-' + row + '-8').val(resultado).html(resultado);
                }
            }
        }
    }

    function launchPopUp(data_id){
        blackLayerEnabled = true;
        var control_data = data_id.split('x');
        
        $('.navegacion').removeClass('fixed');
        $('body').css({'margin-top': '0px'});
        $('.black_layer').show();
        $('.popup_regularization_grades').show();


        
        $('#input_regularization_score').val($('#col-'+control_data[0]).val());
        $('#input_regularization_date').val($('#col-'+control_data[1]).val());
        if($('#col-'+control_data[2]).val() == 0){
            $('#input_regularization_score').prop('disabled', false);
            $('#input_regularization_score').removeClass('blocked_input');
            $('#button_save').prop('disabled', false);
            $('#score_status').val(1);
        }else{
            $('#input_regularization_score').prop('disabled', true);
            $('#input_regularization_score').addClass('blocked_input');
            $('#button_save').prop('disabled', true);
            $('#button_save').addClass('status_button_pale_color');
            $('#score_status').val(0);
        }
        $('#id_subject_grades_card').val(data_id);
    }

    function hidePopUp(){
        blackLayerEnabled = false;
        $('.black_layer').hide();
        $('.popup_regularization_grades').hide();
        $('.navegacion').addClass('fixed');
        $('body').css({'margin-top': barHeight+'px'});
    }

    function checkScore(){

        var actualScore = $('#input_regularization_score');
        if (actualScore.val() > 10) {
            swal("¡CUIDADO!", "El dato insertado no debe ser mayor a 10", "warning");
            actualScore.val('');
        } else if (actualScore.val() < 0) {
            swal("¡CUIDADO!", "El dato insertado no debe ser menor a 0", "warning");
            actualScore.val('');
        } 
    }

    function getBackValues(){
        if($('#input_regularization_score').val() == ''){
            swal("¡CUIDADO!", "Debe de asignarse una calificación adecuada para poder guardar", "warning");
        }else if($('#input_regularization_date').val() == ''){
            swal("¡CUIDADO!", "Debe de asignarse una fecha para poder guardar", "warning");
        }else{
        
        let decimalSplitted = $('#input_regularization_score').val().toString().match(/^-?\d+(?:\.\d{0,1})?/)[0];;
        let informat = parseFloat(decimalSplitted);
        let total = 0;
        if(informat >= 6){
            total = informat.toFixed();
        }else{
            total = informat;
        }

        var control_data = $('#id_subject_grades_card').val().split('x');
        var score_position = control_data[0].split('-');
        var date_position = control_data[1].split('-');

        if((score_position[1] == 10 && date_position[1] == 11) && ($('#score_status').val() == 1)){
            $('#col-'+score_position[0]+'-9').removeClass('status_cog_red');
            $('#col-'+score_position[0]+'-9').addClass('status_cog_green');
            $('#col-'+score_position[0]+'-10').val(total);
        }else if((score_position[1] == 10 && date_position[1] == 11) && ($('#score_status').val() == 0)){
            $('#col-'+score_position[0]+'-9').removeClass('status_cog_green');
            $('#col-'+score_position[0]+'-9').addClass('status_cog_red');
            $('#col-'+score_position[0]+'-10').val(total);
        }if((score_position[1] == 14 && date_position[1] == 15) && ($('#score_status').val() == 1)){
            $('#col-'+score_position[0]+'-13').removeClass('status_cog_red');
            $('#col-'+score_position[0]+'-13').addClass('status_cog_green');
            $('#col-'+score_position[0]+'-14').val(total);
        }else if((score_position[1] == 14 && date_position[1] == 15) && ($('#score_status').val() == 0)){
            $('#col-'+score_position[0]+'-13').removeClass('status_cog_green');
            $('#col-'+score_position[0]+'-13').addClass('status_cog_red');
            $('#col-'+score_position[0]+'-14').val(total);
        }
        if($('#score_status').val() == 1){
            $('#col-'+control_data[2]).val(1);
           }else{
            $('#col-'+control_data[2]).val(0);
           }
        
        $('#col-'+control_data[1]).val($('#input_regularization_date').val());
        hidePopUp();
        }
    }

    function callTeacherSubjects() {
        const action = 'LLAMAR_MATERIAS_PROFESOR_CALIFICACIONES',
            teacher_id = $('#session_owner').text(),
            student_group = document.querySelector('#student_group').value,
            semester = document.querySelector('#semester_student').value,
            kind_subjects = document.querySelector('#kind_student_subjects').value;
        $.ajax({
            url: 'php/model_school.php', //This is the current doc
            type: 'POST',
            dataType: 'text', // add json datatype to get json
            data: { 'action': action, 'teacher_id': teacher_id, 'semester': semester, 'kind_subjects': kind_subjects, 'student_group': student_group },
            success: function (data) {
                var result = JSON.parse(data);
                if(result.message == 'right'){
                $('#student_subject').html(result.content);
                callTeacherStudents();
                }else if(result.message == 'empty'){
                    $('#student_subject').html('');
                    callTeacherStudents();
                }else {
                    swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                }
            }
        });
    }

    function saveStudentGrades() {
        const id_teacher = $('#session_owner').text();
        if(id_teacher == '' || id_teacher == null || id_teacher == undefined){
            swal("¡ADVERTENCIA!", "No su pueden guardar los campos con la cuenta con la que actualmente ha iniciado sesion, intentelo mas tarde o contacte con el administrador de la pagina", "warning");
        }else{
            const numberRows = $('#number_of_rows').text();
            let array = [];
            for (var i = 0; i < numberRows; i++) {
                array[i] = [];
                if($('#activity_season').val() == 'par_1'){
                for (var j = 2; j <= 3; j++) {
                    if ($('#col-' + i + '-' + j).val() == '' || $('#col-' + i + '-' + j).val() == null || $('#col-' + i + '-' + j).val() == undefined) {
                        swal("¡CUIDADO!", "Uno o varios campos estan vacios o con datos erroneos", "warning");
                        return 0;
                        }
                    }
                }else if($('#activity_season').val() == 'par_2'){
                    for (var j = 4; j <= 5; j++) {
                        if ($('#col-' + i + '-' + j).val() == '' || $('#col-' + i + '-' + j).val() == null || $('#col-' + i + '-' + j).val() == undefined) {
                            swal("¡CUIDADO!", "Uno o varios campos estan vacios o con datos erroneos", "warning");
                            return 0;
                            }
                        }
                }else if($('#activity_season').val() == 'par_final'){
                    for (var j = 6; j <= 7; j++) {
                        if ($('#col-' + i + '-' + j).val() == '' || $('#col-' + i + '-' + j).val() == null || $('#col-' + i + '-' + j).val() == undefined) {
                            swal("¡CUIDADO!", "Uno o varios campos estan vacios o con datos erroneos", "warning");
                            return 0;
                            }
                        }
                }else if($('#activity_season').val() == 'all'){
                    for (var j = 2; j <= 7; j++) {
                        if ($('#col-' + i + '-' + j).val() == '' || $('#col-' + i + '-' + j).val() == null || $('#col-' + i + '-' + j).val() == undefined) {
                            swal("¡CUIDADO!", "Uno o varios campos estan vacios o con datos erroneos", "warning");
                            return 0;
                            }
                        }
                }
                for (var j = 0; j < 17; j++) {
                    if (j == 8) {
                        array[i][j] = $('#col-' + i + '-' + j).text();
                    }else if(j == 9 && j == 13){

                    } else {
                        array[i][j] = document.querySelector('#col-' + i + '-' + j).value;
                }
            }
            }

            $.ajax({
                url: 'php/model_school.php', //This is the current doc
                type: 'POST',
                dataType: 'text', // add json datatype to get json
                data: { 'action': 'GUARDAR_CALIFICACIONES_PROFESOR', 'number_rows': numberRows, 'array': JSON.stringify(array), 'search_teacher_id': $('#session_owner').text(), 'group': group.value, 'school_cycle': school_cycle.value, 'semesters': semesters.value, 'subject': subject.value },
                success: function (data) {
                    const result = JSON.parse(data);
                    if (result.message === 'right') {
                        swal("¡ÉXITO!", "Datos guardados correctamente", "success");
                        callTeacherSubjects();
                    } else if(result.message == 'wrong'){
                        swal("¡ERROR!", "Hubo un problema con el guardado de los datos. Intentelo de nuevo.", "error");
                    }else {
                        swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                    }
                    
                }
            });
        }
        
    }
    }
    if ($('body').data('title') == 'body_ratings_students') {
        getCompleteName();
        function getCompleteName(){
            const id = $('#session_owner').text();
            $.ajax({
                url: 'php/model_school.php', //This is the current doc
                type: 'POST',
                dataType: 'text', // add json datatype to get json
                data: {'action' : 'NOMBRE_COMPLETO_ESTUDIANTE', 'id': id},
                success: function (data) {
                    var result = JSON.parse(data);
                    if(result.message == 'right'){
                        $('#complete_student_name').text(result.result_query);
                        getGrades();
                    }else if(result.message == 'empty'){
                        swal("¡CUIDADO!", "No se encontraron datos relacionados a la consulta solicitada. Intentelo de nuevo.", "warning");
                    }else {
                        swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                    }
                }
            });
        }

        function getGrades(){
            const id = $('#session_owner').text();
            $.ajax({
                url: 'php/model_school.php', //This is the current doc
                type: 'POST',
                dataType: 'text', // add json datatype to get json
                data: {'action' : 'NOMBRE_CALIFICACIONES_ESTUDIANTE', 'id': id},
                success: function (data) {
                    var result = JSON.parse(data);
                    if(result.message == 'right'){
                        if(result.result_query[0] == undefined || result.result_query[0] == null){

                        }else{
                        let data_for_page = '';
                        let semester = "", 
                                group = "", 
                                kind_subjects = "", 
                                school_year = "";
                        semester = result.result_query[0][4];
                        group = result.result_query[0][5];
                        kind_subjects = result.result_query[0][6];
                        school_year = result.result_query[0][7];
                        let total_average = 0,
                        total_average_count = 0;
                        data_for_page += "<div class=\"student_information\"> <p class=\"p_students_shape\"> <span class=\"bold_words\">Semestre:</span> <span class=\"non_bold_words\">"+semester+"<span> </p>"+
                        "<p class=\"p_students_shape\"> <span class=\"bold_words\">Grupo:</span> <span class=\"non_bold_words\">"+group+"<span> </p>"+
                        "<p class=\"p_students_shape\"> <span class=\"bold_words\">Bachillerato:</span> <span class=\"non_bold_words\">"+kind_subjects+"<span> </p>"+
                        "<p class=\"p_students_shape\"> <span class=\"bold_words\">Ciclo Escolar:</span> <span class=\"non_bold_words\">"+school_year+"<span> </p>"+
                        "</div>"+
                        "<p class=\"p_students_average\"> <span class=\"bold_words_average\">Promedio General:</span> <span id=\"total_average\" class=\"total_average\"><span> </p>"+
                        "<div class=\"all_subjects\">";
                        for(let number_subjects = 0; number_subjects < result.result_query.length; number_subjects++)
                        {
                            let 
                            key_subject = "",
                            name_subject = "",
                            kind_subject = "",
                            par_1 = "",
                            par_2 = "",
                            par_final = "",
                            average = "",
                            extra_1 = "",
                            extra_2 = "",
                            extra_1_container = "",
                            extra_2_container = "";
                            key_subject = result.result_query[number_subjects][9];
                            name_subject = result.result_query[number_subjects][24];
                            kind_subject = result.result_query[number_subjects][26];
                            if(result.result_query[number_subjects][16] == 0){par_1 = 'No Asignado';}else{par_1 = result.result_query[number_subjects][12];}
                            if(result.result_query[number_subjects][17] == 0){par_2 = 'No Asignado';}else{par_2 = result.result_query[number_subjects][13];}
                            if(result.result_query[number_subjects][18] == 0){par_final = 'No Asignado';}else{par_final = result.result_query[number_subjects][14];}
                            if(result.result_query[number_subjects][16] == 1 && result.result_query[number_subjects][17] == 1 && result.result_query[number_subjects][18] == 1){
                                if(kind_subject == 'Normal' || kind_subject == 'Formación para el Trabajo'){
                                    average = result.result_query[number_subjects][15];
                                    if(result.result_query[number_subjects][20] == 0 || result.result_query[number_subjects][22] == 0){
                                        total_average += parseFloat(average);
                                        total_average_count++;
                                    }
                                }else{
                                    if(result.result_query[number_subjects][15] >= 6){
                                        average = "Acreditada";
                                    }else{
                                        average = "No Acreditada";
                                    }
                                }
                            }else{
                                average = 'No Asignado'}
                            if((result.result_query[number_subjects][16] == 1 && result.result_query[number_subjects][17] == 1 && result.result_query[number_subjects][18] == 1) && result.result_query[number_subjects][20] == 1){
                                extra_1 = result.result_query[number_subjects][19];
                                if(kind_subject == 'Normal' || kind_subject == 'Formación para el Trabajo'){
                                    extra_1_container = "<p class=\"p_students_shape p_students_space\"> <span class=\"bold_words\">Extraordinario 1:</span> <span class=\"non_bold_words non_bold_extra\">"+extra_1+"<span> </p>";
                                    if(result.result_query[number_subjects][22] == 0){
                                        total_average += parseFloat(extra_1);
                                        total_average_count++;
                                    }
                                }else{
                                    if(extra_1 >= 6){
                                        extra_1_container = "<p class=\"p_students_shape p_students_space\"> <span class=\"bold_words\">Extraordinario 1:</span> <span class=\"non_bold_words non_bold_extra\">Acreditada<span> </p>";
                                    }else{
                                        extra_1_container = "<p class=\"p_students_shape p_students_space\"> <span class=\"bold_words\">Extraordinario 1:</span> <span class=\"non_bold_words non_bold_extra\">No Acreditada<span> </p>";
                                    }
                                }
                            }else{
                                extra_1_container = "";
                                }
                                
                            if((result.result_query[number_subjects][16] == 1 && result.result_query[number_subjects][17] == 1 && result.result_query[number_subjects][18] == 1) && result.result_query[number_subjects][20] == 1 && result.result_query[number_subjects][22] == 1){
                                extra_2 = result.result_query[number_subjects][21];
                                if(kind_subject == 'Normal' || kind_subject == 'Formación para el Trabajo'){
                                    extra_2_container = "<p class=\"p_students_shape p_students_space\"> <span class=\"bold_words\">Extraordinario 2:</span> <span class=\"non_bold_words non_bold_extra\">"+extra_2+"<span> </p>";
                                    total_average += parseFloat(extra_2);
                                    total_average_count++;
                                }else{
                                    if(extra_2 >= 6){
                                        extra_2_container = "<p class=\"p_students_shape p_students_space\"> <span class=\"bold_words\">Extraordinario 2:</span> <span class=\"non_bold_words non_bold_extra\">Acreditada<span> </p>";
                                    }else{
                                        extra_2_container = "<p class=\"p_students_shape p_students_space\"> <span class=\"bold_words\">Extraordinario 2:</span> <span class=\"non_bold_words non_bold_extra\">No Acreditada<span> </p>";
                                    }
                                }
                            }else{
                                extra_2_container = "";
                                }
                            data_for_page += "<div class=\"subject_content\">"+
                                
                                "<p class=\"p_students_shape_tittle\"> <span class=\"bold_words\">"+name_subject+"<span> </p>"+
                                "<p class=\"p_students_shape_center\"> <span class=\"bold_words\">Clave:</span> <span class=\"non_bold_words\">"+key_subject+"<span> </p>"+ 
                                "<p class=\"p_students_shape\"> <span class=\"bold_words\">Parcial 1 (25%):</span> <span class=\"non_bold_words\">"+par_1+"<span> </p>"+
                                "<p class=\"p_students_shape\"> <span class=\"bold_words\">Parcial 2 (25%):</span> <span class=\"non_bold_words\">"+par_2+"<span> </p>"+
                                "<p class=\"p_students_shape\"> <span class=\"bold_words\">Parcial Final (50%):</span> <span class=\"non_bold_words\">"+par_final+"<span> </p>"+
                                "<p class=\"p_students_shape\"> <span class=\"bold_words\">Promedio:</span> <span class=\"non_bold_words non_bold_average\">"+average+"<span> </p>"+
                                extra_1_container +
                                extra_2_container +
                            "</div>";
                        }
                        data_for_page += "</div>";
                        $('.subjects_area').html(data_for_page);
                        let final_total;
                        if(total_average_count <= 0){
                            final_total = 'No Asignado';
                            $('#total_average').text(final_total);
                        }else{
                            final_total = total_average/total_average_count;
                            $('#total_average').text(final_total.toFixed(2));
                        }
                    }
                    }else if(result.message == 'empty'){
                        swal("¡CUIDADO!", "No se encontraron datos relacionados a la consulta solicitada. Intentelo de nuevo.", "warning");
                    }else {
                        swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                    }
                }
            });
        }
    }
    if ($('body').data('title') == 'pdf_key') {
        var div_car_per_group_subjects = document.querySelector('#per_group_subjects'),
        semester_group_card = document.querySelector('#semester_group_card');

        eventListeners();
        checkExistMails();

         getSchoolYearGroups();
         semesterGroupsSwitch();
        
         function eventListeners() {
            semester_group_card.addEventListener('input', semesterGroupsSwitch);
        }

        function semesterGroupsSwitch() {
                
            const semester = document.querySelector('#semester_group_card').value,
                mySelect = document.querySelector('#kind_subjects_group_card'),
                semester_group_select = document.querySelector('#group_letter_card');
            $('#kind_subjects_group_card').html('');
            $('#group_letter_card').html('');
            if (semester == 'Primero' || semester == 'Segundo' || semester == 'Tercero' || semester == 'Cuarto') {
                var myOption1 = document.createElement("option");
                myOption1.setAttribute("value", "Tronco Común");
                myOption1.textContent = "Tronco Común";
                mySelect.appendChild(myOption1);
                
                var semester_option_A = document.createElement("option");
                    semester_option_A.setAttribute("value", "A");
                    semester_option_A.textContent = "A";
                    semester_group_select.appendChild(semester_option_A);
                    var semester_option_B = document.createElement("option");
                    semester_option_B.setAttribute("value", "B");
                    semester_option_B.textContent = "B";
                    semester_group_select.appendChild(semester_option_B);
                    var semester_option_C = document.createElement("option");
                    semester_option_C.setAttribute("value", "C");
                    semester_option_C.textContent = "C";
                    semester_group_select.appendChild(semester_option_C);
                    var semester_option_D = document.createElement("option");
                    semester_option_D.setAttribute("value", "D");
                    semester_option_D.textContent = "D";
                    semester_group_select.appendChild(semester_option_D);
                    var semester_option_E = document.createElement("option");
                    semester_option_E.setAttribute("value", "E");
                    semester_option_E.textContent = "E";
                    semester_group_select.appendChild(semester_option_E);

            } else if (semester == 'Quinto' || semester == 'Sexto') {
                var myOption2 = document.createElement("option");
                myOption2.setAttribute("value", "Económico - Administrativo");
                myOption2.textContent = "Económico - Administrativo";
                mySelect.appendChild(myOption2);
                var myOption3 = document.createElement("option");
                myOption3.setAttribute("value", "Físico - Matemático");
                myOption3.textContent = "Físico - Matemático";
                mySelect.appendChild(myOption3);
                var myOption4 = document.createElement("option");
                myOption4.setAttribute("value", "Humanidades Y Ciencias Sociales");
                myOption4.textContent = "Humanidades Y Ciencias Sociales";
                mySelect.appendChild(myOption4);
                var myOption5 = document.createElement("option");
                myOption5.setAttribute("value", "Químico - Biológico");
                myOption5.textContent = "Químico - Biológico";
                mySelect.appendChild(myOption5);
                
                var semester_option_A = document.createElement("option");
                semester_option_A.setAttribute("value", "A");
                semester_option_A.textContent = "A";
                semester_group_select.appendChild(semester_option_A);
            }
        }

        function getSchoolYearGroups() {
            $.ajax({
                url: 'php/model_school.php', //This is the current doc
                type: 'POST',
                dataType: 'text', // add json datatype to get json
                data: { 'action': 'LLAMAR_FECHA', 'date': 'none', 'message': 'CICLO_ESCOLAR' },
                success: function (data) {
                    var result = JSON.parse(data);
                    if(result.message == 'right'){
                        $('#school_year_group_card').html(result.content);
                    }else if(result.message == 'empty'){
                        swal("¡CUIDADO!", "No se encontraron datos relacionados a la consulta solicitada. Intentelo de nuevo.", "warning");
                    }else {
                        swal("¡ERROR!", "Error: " + result.message + " Intente de nuevo o contacte con el administrador de la página", "error");
                    }
                }
            });
        }
    }
    });
})();