var table = '';
var selectLevel = '';

$('.li-master').addClass('menu-open');
$('.li-master .treeview-menu').css('display', 'block');
$('.li-master-pengguna').addClass('active');

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
	table = $('#dataTable').DataTable({
		'processing'	: true,
        'serverSide'	: true,

        'ajax' : {
        	'url'	: baseurl + 'master-pengguna/datatable/',
            'type'	: 'GET',
            'dataSrc' : function(response){
            	var i = response.start;
            	var row = new Array();
            	if (response.result) {
            		for(var x in response.data){
                        var button = '';
                        if (response.data[x].id_level_pengguna == 3) {
                            button = '<button id="'+ response.data[x].id +'" name="btn_edit" class="btn btn-info btn-xs btn-flat" title="Edit Data"><i class="fa fa-edit"></i></button>';
                        } else{
                            button = '<button id="'+ response.data[x].id +'" name="btn_edit" class="btn btn-info btn-xs btn-flat" title="Edit Data"><i class="fa fa-edit"></i></button> <button id="'+ response.data[x].id +'" name="btn_delete" class="btn btn-danger btn-xs btn-flat" title="Hapus Data"><i class="fa fa-trash"></i></button>';
                        }

	            		row.push({
	            			'no'            : i,
                            'username'      : response.data[x].username,
                            'display_name'  : response.data[x].display_name,
                            'kontak'        : response.data[x].kontak,
                            'nama_level'    : response.data[x].nama_level,
	            			'aksi'	        : button
	            		});
	            		i = i + 1;
	            	}

	            	response.data = row;
            		return row;
            	} else{
            		response.draw = 0;
            		return [];
            	}
            }
        },

        'columns' : [
        	{ 'data' : 'no' },
            { 'data' : 'username' },
            { 'data' : 'display_name' },
            { 'data' : 'kontak' },
            { 'data' : 'nama_level' },
        	{ 'data' : 'aksi' }
        ],

        'order' 	: [[ 1, 'ASC' ]],

		'columnDefs': [
    		{
    			'orderable'	: false,
    			'targets'	: [ 0, 5 ]
    		}
  		]
	});

    $.ajax({
        type: 'GET',
        url: baseurl + 'master-pengguna/select-level/',
        dataType: 'json',
        success: function(response){
            if(response.result){
                for(var x in response.data){
                    $('select[name="id_level_pengguna"]').append('<option value="'+ response.data[x].id +'">'+response.data[x].nama_level+'</option>');
                }
            }
        }
    });
    selectLevel = $('select[name="id_level_pengguna"]').select2();
});

$('button[name="btn_add"]').click(function(){
	csrf();
	$('button[name="btn_save"]').attr('id', '0');
    $('input[name="username"]').val('');
    $('input[name="password"]').val('');
    $('input[name="repeat_password"]').val('');
    $('input[name="display_name"]').val('');
    $('input[name="email"]').val('');
    $('input[name="kontak"]').val('');
    $('textarea[name="alamat"]').val('');
    $(selectLevel).val('2').trigger('change');

    $('#formTitle').text('Tambah Data');

	$('#table').hide();
	setTimeout(function(){
		$('#form').fadeIn()
	}, 100);
});

$('#dataTable').on('click', 'button[name="btn_edit"]', function(){
	csrf();
	var id = $(this).attr('id');

    $.ajax({
        type: 'GET',
        url: baseurl + 'master-pengguna/edit/'+ id +'/',
        dataType: 'json',
        success: function(response){
            if(response.result){
                var d = response.data;

                $(selectLevel).find('option').each(function(){
                    if ($(this).val() == d.id_level_pengguna) {
                        $(selectLevel).val($(this).val()).trigger('change');
                    }
                });

                $('input[name="username"]').val(d.username);
                $('input[name="password"]').val('');
                $('input[name="repeat_password"]').val('');
                $('input[name="display_name"]').val(d.display_name);
                $('input[name="email"]').val(d.email);
                $('input[name="kontak"]').val(d.kontak);
                $('textarea[name="alamat"]').val(d.alamat);

                $('button[name="btn_save"]').attr('id', id);
                $('#formTitle').text('Edit Data');

                csrf();
                $('#table').hide();
                setTimeout(function(){
                    $('#form').fadeIn()
                }, 100);
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
});

$('#dataTable').on('click', 'button[name="btn_delete"]', function(){
	if (!confirm('Apakah anda yakin?')) {
		return;
	}

	var id = $(this).attr('id');

	$.ajax({
        type: 'POST',
        url: baseurl + 'master-pengguna/delete/',
        data: {
        	'id': id,
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
                table.ajax.reload(null, false);
				csrf();
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
});

$('button[name="btn_cancel"]').click(function(){
	$('#form').hide();
	setTimeout(function(){
		$('#table').fadeIn();
	}, 100);
});

$('button[name="btn_save"]').click(function(){
    var id = $(this).attr('id');
	$(this).attr('disabled', 'disabled');
    var missing = false;
    $('#formData').find('input').each(function(){
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

    var pwd = $('input[name="password"]').val();
    var rpwd = $('input[name="repeat_password"]').val();
    if (id == 0) {
        if (pwd.length < 1 || pwd != rpwd) {
            $.notify({
                icon: 'glyphicon glyphicon-info-sign',
                message: 'Kolom password tidak boleh kosong & harus sama.'
            }, {
                type: 'warning',
                delay: 1000,
                timer: 500,
                placement: {
                    from: 'top',
                    align: 'center'
                }
            });
            $('input[name="password"]').val('');
            $('input[name="repeat_password"]').val('');
            $('input[name="password"]').focus();
            return;
        }
    } else{
        if (pwd.length > 0) {
            if (pwd != rpwd) {
                $.notify({
                    icon: 'glyphicon glyphicon-info-sign',
                    message: 'Password tidak sama, silakan periksa kembali.'
                }, {
                    type: 'warning',
                    delay: 1000,
                    timer: 500,
                    placement: {
                        from: 'top',
                        align: 'center'
                    }
                });
                $('input[name="password"]').val('');
                $('input[name="repeat_password"]').val('');
                $('input[name="password"]').focus();
                return;
            }
        }
    }

    $.ajax({
        type: 'POST',
        url: baseurl + 'master-pengguna/save/',
        data: {
        	'id': $(this).attr('id'),
        	'form': $('#formData').serialize(),
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
				csrf();
                table.ajax.reload(null, false);
                $('#form').hide();
				setTimeout(function(){
					$('#table').fadeIn();
				}, 100);
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
});
