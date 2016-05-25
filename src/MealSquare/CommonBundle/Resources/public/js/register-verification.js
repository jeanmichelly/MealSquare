$(document).ready(function() {
    /*USERNAME*/
    $('#sonata_user_registration_form_username').keyup(function() {
        var username = $('#sonata_user_registration_form_username').val();
        var pswd = $("#sonata_user_registration_form_plainPassword_first").val();
        var confirm_pswd = $("#sonata_user_registration_form_plainPassword_second").val();

        if ( pswd.length > 7 && pswd.match(/[A-z]/) && pswd.match(/[A-Z]/) && pswd.match(/\d/) && pswd == confirm_pswd && checkEmail() && username.length >= 5 ){
            $("input[type=submit]").prop("disabled", false);
            $("input[type=submit]").fadeTo(0, 1);
        } else{
            $("input[type=submit]" ).prop("disabled", true);
            $("input[type=submit]").fadeTo(0, 0.65);
        }

        if ( username.length >= 5 ) {
            $('#letter_2').removeClass('invalid').addClass('valid');
        }else{
            $('#letter_2').removeClass('valid').addClass('invalid');
        }

    }).focus(function() {
        $('#username_info').show();
    }).blur(function() {
        $('#username_info').hide();
    });

    /*EMAIL*/
    function checkEmail() {
        var email = $("#sonata_user_registration_form_email");
        var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

        return filter.test(email.val());
    }
    $('#sonata_user_registration_form_email').keyup(function() {
        var username = $('#sonata_user_registration_form_username').val();
        var pswd = $("#sonata_user_registration_form_plainPassword_first").val();
        var confirm_pswd = $("#sonata_user_registration_form_plainPassword_second").val();

        if ( pswd.length > 7 && pswd.match(/[A-z]/) && pswd.match(/[A-Z]/) && pswd.match(/\d/) && pswd == confirm_pswd && checkEmail() && username.length >= 5 ){
            $("input[type=submit]").prop("disabled", false);
            $("input[type=submit]").fadeTo(0, 1);
        } else{
            $("input[type=submit]" ).prop("disabled", true);
            $("input[type=submit]").fadeTo(0, 0.65);
        }

        if( checkEmail() ){
            $('#email').removeClass('invalid').addClass('valid');
        } else {
            $('#email').removeClass('valid').addClass('invalid');
        }

    }).focus(function() {
        $('#validation_email_info').show();
    }).blur(function() {
        $('#validation_email_info').hide();
    });

    /*PASSWORD*/
    $('#sonata_user_registration_form_plainPassword_first').keyup(function() {
        var username = $('#sonata_user_registration_form_username').val();
        var pswd = $(this).val();
        var confirm_pswd = $("#sonata_user_registration_form_plainPassword_second").val();

        //validate the length
        if ( pswd.length < 8 ) {
            $('#length').removeClass('valid').addClass('invalid');
        } else {
            $('#length').removeClass('invalid').addClass('valid');
        }
        //validate letter
        if ( pswd.match(/[A-z]/) ) {
            $('#letter').removeClass('invalid').addClass('valid');
        } else {
            $('#letter').removeClass('valid').addClass('invalid');
        }

        //validate capital letter
        if ( pswd.match(/[A-Z]/) ) {
            $('#capital').removeClass('invalid').addClass('valid');
        } else {
            $('#capital').removeClass('valid').addClass('invalid');
        }

        //validate number
        if ( pswd.match(/\d/) ) {
            $('#number').removeClass('invalid').addClass('valid');
        } else {
            $('#number').removeClass('valid').addClass('invalid');
        }

        if ( pswd.length > 7 && pswd.match(/[A-z]/) && pswd.match(/[A-Z]/) && pswd.match(/\d/) && pswd == confirm_pswd && checkEmail() && username.length >= 5 ){
            $("input[type=submit]").prop("disabled", false);
            $("input[type=submit]").fadeTo(0, 1);
        } else{
            $("input[type=submit]" ).prop("disabled", true);
            $("input[type=submit]").fadeTo(0, 0.65);
        }

        if($("#sonata_user_registration_form_plainPassword_first").val() == $("#sonata_user_registration_form_plainPassword_second").val()){
            $('#match').removeClass('invalid').addClass('valid');
        }else{
            $('#match').removeClass('valid').addClass('invalid');
        }

    }).focus(function() {
        $('#pswd_info').show();
    }).blur(function() {
        $('#pswd_info').hide();
    });

    /*CONFIRM PASSWORD*/
    $('#sonata_user_registration_form_plainPassword_second').keyup(function() {
        var username = $('#sonata_user_registration_form_username').val();
        var pswd = $("#sonata_user_registration_form_plainPassword_first").val();
        var confirm_pswd = $("#sonata_user_registration_form_plainPassword_second").val();

        if ( pswd.length > 7 && pswd.match(/[A-z]/) && pswd.match(/[A-Z]/) && pswd.match(/\d/) && pswd == confirm_pswd && checkEmail() && username.length >= 5 ){
            $("input[type=submit]").prop("disabled", false);
            $("input[type=submit]").fadeTo(0, 1);
        } else{
            $("input[type=submit]" ).prop("disabled", true);
            $("input[type=submit]").fadeTo(0, 0.65);
        }

        if($("#sonata_user_registration_form_plainPassword_first").val() == $("#sonata_user_registration_form_plainPassword_second").val()){
            $('#match').removeClass('invalid').addClass('valid');
        }else{
            $('#match').removeClass('valid').addClass('invalid');
        }

    }).focus(function() {
        $('#confirm_pswd_info').show();
    }).blur(function() {
        $('#confirm_pswd_info').hide();
    });
});