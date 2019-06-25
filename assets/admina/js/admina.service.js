var table = '';
var selectClient = '';
var selectStatus = '';

$('.li-service').addClass('active');

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
        	'url'	: baseurl + 'service/datatable/',
            'type'	: 'GET',
            'dataSrc' : function(response){
            	var i = response.start;
            	var row = new Array();
            	if (response.result) {
            		for(var x in response.data){
                        var button = '<button id="'+ response.data[x].id +'" name="btn_edit" class="btn btn-info btn-xs btn-flat" title="Edit Data"><i class="fa fa-edit"></i></button> <button id="'+ response.data[x].id +'" name="btn_delete" class="btn btn-danger btn-xs btn-flat" title="Hapus Data"><i class="fa fa-trash"></i></button>';

	            		row.push({
	            			'no'            : i,
                            'no_reg'        : response.data[x].no_reg,
                            'nama_client'   : response.data[x].nama_client,
                            'kontak'        : response.data[x].kontak,
                            'merk_serie'    : response.data[x].merk_serie,
                            'keluhan'       : response.data[x].keluhan,
                            'status'        : response.data[x].status,
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
            { 'data' : 'no_reg' },
            { 'data' : 'nama_client' },
            { 'data' : 'kontak' },
            { 'data' : 'merk_serie' },
            { 'data' : 'keluhan' },
            { 'data' : 'status' },
        	{ 'data' : 'aksi' }
        ],

        'order' 	: [[ 1, 'DESC' ]],

		'columnDefs': [
    		{
    			'orderable'	: false,
    			'targets'	: [ 0, 7 ]
    		}
  		]
	});

    $.ajax({
        type: 'GET',
        url: baseurl + 'client/select/',
        dataType: 'json',
        success: function(response){
            if(response.result){
                $('select[name="id_client"]').append('<option value="0">- Pilih Client -</option>');
                for(var x in response.data){
                    $('select[name="id_client"]').append('<option value="'+ response.data[x].id +'">'+response.data[x].kode_client+' | '+response.data[x].nama_client+'</option>');
                }
            } else{
                $('select[name="id_client"]').append('<option value="0">- Pilih Client -</option>');
            }
        }
    });
    selectClient = $('select[name="id_client"]').select2();

    $.ajax({
        type: 'GET',
        url: baseurl + 'master-status/service/',
        dataType: 'json',
        success: function(response){
            if(response.result){
                for(var x in response.data){
                    $('select[name="id_status"]').append('<option value="'+ response.data[x].id +'">'+response.data[x].status+'</option>');
                }
            }
        }
    });
    selectStatus = $('select[name="id_status"]').select2();
});

function getNoRegistrasi()
{
    $.ajax({
        type: 'GET',
        url: baseurl + 'service/no-registrasi/',
        dataType: 'json',
        success: function(response){
            if(response.result){
                $('input[name="no_reg"]').val(response.kode);
            }
        }
    });
}

$('button[name="btn_add"]').click(function(){
	csrf();
	$('button[name="btn_save"]').attr('id', '0');
    $('input[name="merk_serie"]').val('');
    $('textarea[name="kelengkapan"]').val('');
    $('textarea[name="keluhan"]').val('');
    $('textarea[name="keterangan"]').val('');
    $(selectStatus).val('1').trigger('change');
    $(selectClient).val('0').trigger('change');
    getNoRegistrasi();

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
        url: baseurl + 'service/edit/'+ id +'/',
        dataType: 'json',
        success: function(response){
            if(response.result){
                var d = response.data;

                $(selectClient).find('option').each(function(){
                    if ($(this).val() == d.id_client) {
                        $(selectClient).val($(this).val()).trigger('change');
                    }
                });

                $(selectStatus).find('option').each(function(){
                    if ($(this).val() == d.id_status) {
                        $(selectStatus).val($(this).val()).trigger('change');
                    }
                });

                $('input[name="no_reg"]').val(d.no_reg);
                $('input[name="merk_serie"]').val(d.merk_serie);
                $('textarea[name="kelengkapan"]').val(d.kelengkapan);
                $('textarea[name="keluhan"]').val(d.keluhan);
                $('textarea[name="keterangan"]').val(d.keterangan);

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
        url: baseurl + 'service/delete/',
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
    $('#formData').find('input, textarea').each(function(){
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

    if ($('select[name="id_client"]').val() == 0) {
        $.notify({
            icon: 'glyphicon glyphicon-info-sign',
            message: 'Silakan pilih client terlebih dahulu.'
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
        return;
    }

    $.ajax({
        type: 'POST',
        url: baseurl + 'service/save/',
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
