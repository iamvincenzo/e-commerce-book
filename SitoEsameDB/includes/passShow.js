$(document).ready(function() {
    
    $("#show_hide_password a").on('click', function(event) {
        
        event.preventDefault();
        
        if($('#show_hide_password input').attr("type") == "text"){
            $('#show_hide_password input').attr('type', 'password');
            $('#show_hide_password i').addClass( "bi-eye-slash" );
            $('#show_hide_password i').removeClass( "bi-eye" );
        }
        
        else if($('#show_hide_password input').attr("type") == "password"){
            $('#show_hide_password input').attr('type', 'text');
            $('#show_hide_password i').removeClass( "bi-eye-slash" );
            $('#show_hide_password i').addClass( "bi-eye" );
        }
    });

    $("#show_hide_password1 a").on('click', function(event) {
        
        event.preventDefault();
        
        if($('#show_hide_password1 input').attr("type") == "text"){
            $('#show_hide_password1 input').attr('type', 'password');
            $('#show_hide_password1 i').addClass( "bi-eye-slash" );
            $('#show_hide_password1 i').removeClass( "bi-eye" );
        }
        
        else if($('#show_hide_password1 input').attr("type") == "password"){
            $('#show_hide_password1 input').attr('type', 'text');
            $('#show_hide_password1 i').removeClass( "bi-eye-slash" );
            $('#show_hide_password1 i').addClass( "bi-eye" );
        }
    });
});