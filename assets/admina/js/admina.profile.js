$('#formDataPengguna .display-picture, #formDataPengguna p').on('click', function(){
	$('input[name="file_display_picture"]').trigger('click');
});

function csrf()
{
	$.ajax({
        type: 'GET',
        url: baseurl + 'csrf/get/',
        dataType: 'json',
        success: function(response){
            if(response.result){
                var csrf = response.csrf;
				$('input[name="'+ csrf.name +'"]').val(csrf.hash);
            }
        }
    });
}

$(document).ready(function(){
	csrf();
});

$('input[name="file_display_picture"]').on('change', function(){
	if ($(this).val() != '') {
		var fd = $(this).prop('files')[0];
		var fu = new FormData();
		fu.append('file', fd);
		fu.append('csrf_token', $('input[id="csrf"]').val());

		$.ajax({
	        type: 'POST',
	        url: baseurl + 'upload/display-picture/',
	        data: fu,
	        dataType: 'json',
            contentType: false,
            processData: false,
	        success: function(response){
	            if(response.result){
	            	$.notify({
	                    icon: "glyphicon glyphicon-ok",
	                    message: response.msg
	                }, {
	                    type: 'success',
	                    delay: 3000,
	                    timer: 1000,
	                    placement: {
	                        from: 'top',
	                        align: 'center'
	                    }
	                });
					var data = response.data;
					var img = baseurl + 'uploads/img/' + data.file_name;
					$('.display-picture').attr('src', img);
	            } else{
	                $.notify({
	                    icon: "glyphicon glyphicon-info-sign",
	                    message: response.msg
	                }, {
	                    type: 'danger',
	                    delay: 3000,
	                    timer: 1000,
	                    placement: {
	                        from: 'top',
	                        align: 'center'
	                    }
	                });
	            }

	            csrf();
	        }
	    });
	}
});

$('button[name="btn_save_data"]').click(function(){
	$(this).attr('disabled', 'disabled');
    var missing = false;
    $('#formDataPengguna').find('input').each(function(){
        if($(this).prop('required')){
            if($(this).val() == ''){
                var placeholder = $(this).attr('placeholder');
                $.notify({
                    icon: 'glyphicon glyphicon-info-sign',
                    message: 'Kolom ' + placeholder +' tidak boleh kosong.'
                }, {
                    type: 'warning',
                    delay: 1000,
                    timer: 500,
                    placement: {
                        from: 'top',
                        align: 'center'
                    }
                });
                $(this).focus();
                missing = true;
                return false;
            }
        }
    });

    $(this).removeAttr('disabled');
    if(missing){
        return;
    }

    $.ajax({
        type: 'POST',
        url: baseurl + 'profile/save-data/',
        data: {
        	'form': $('#formDataPengguna').serialize(),
			'csrf_token': $('input[id="csrf"]').val()
        },
        dataType: 'json',
        success: function(response){
            if(response.result){
            	$.notify({
                    icon: "glyphicon glyphicon-ok",
                    message: response.msg
                }, {
                    type: 'success',
                    delay: 3000,
                    timer: 1000,
                    placement: {
                        from: 'top',
                        align: 'center'
                    }
                });
            } else{
                $.notify({
                    icon: "glyphicon glyphicon-info-sign",
                    message: response.msg
                }, {
                    type: 'danger',
                    delay: 3000,
                    timer: 1000,
                    placement: {
                        from: 'top',
                        align: 'center'
                    }
                });
            }
        }
    });

    csrf();
});

$('button[name="btn_save_password"]').click(function(){
	$(this).attr('disabled', 'disabled');
    var missing = false;
    $('#formDataPassword').find('input').each(function(){
        if($(this).prop('required')){
            if($(this).val() == ''){
                var placeholder = $(this).attr('placeholder');
                $.notify({
                    icon: 'glyphicon glyphicon-info-sign',
                    message: 'Kolom ' + placeholder +' tidak boleh kosong.'
                }, {
                    type: 'warning',
                    delay: 1000,
                    timer: 500,
                    placement: {
                        from: 'top',
                        align: 'center'
                    }
                });
                $(this).focus();
                missing = true;
                return false;
            }
        }
    });

    $(this).removeAttr('disabled');
    if(missing){
        return;
    }

    var pwd = $('input[name="password_baru"]').val();
    var rpwd = $('input[name="password_ulang"]').val();
    if (pwd != rpwd) {
    	$.notify({
            icon: 'glyphicon glyphicon-info-sign',
            message: 'Password baru anda tidak sesuai.'
        }, {
            type: 'warning',
            delay: 1000,
            timer: 500,
            placement: {
                from: 'top',
                align: 'center'
            }
        });

    	$('input[name="password_lama"]').val('');
        $('input[name="password_baru"]').val('');
        $('input[name="password_ulang"]').val('');
        $('input[name="password_lama"]').focus();
        return;
    }

    $.ajax({
        type: 'POST',
        url: baseurl + 'profile/save-password/',
        data: {
        	'form': $('#formDataPassword').serialize(),
			'csrf_token': $('input[id="csrf"]').val()
        },
        dataType: 'json',
        success: function(response){
            if(response.result){
            	$.notify({
                    icon: "glyphicon glyphicon-ok",
                    message: response.msg
                }, {
                    type: 'success',
                    delay: 3000,
                    timer: 1000,
                    placement: {
                        from: 'top',
                        align: 'center'
                    }
                });
            } else{
                $.notify({
                    icon: "glyphicon glyphicon-info-sign",
                    message: response.msg
                }, {
                    type: 'danger',
                    delay: 3000,
                    timer: 1000,
                    placement: {
                        from: 'top',
                        align: 'center'
                    }
                });
            }
        }
    });

    $('input[name="password_lama"]').val('');
    $('input[name="password_baru"]').val('');
    $('input[name="password_ulang"]').val('');
    $('input[name="password_lama"]').focus();
    csrf();
});