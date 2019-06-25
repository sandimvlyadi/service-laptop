var table = '';
var selectKondisi = '';
var selectStatus = '';

$('.li-stock').addClass('menu-open');
$('.li-stock .treeview-menu').css('display', 'block');
$('.li-stock-laptop').addClass('active');

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
        	'url'	: baseurl + 'stock-laptop/datatable/',
            'type'	: 'GET',
            'dataSrc' : function(response){
            	var i = response.start;
            	var row = new Array();
            	if (response.result) {
            		for(var x in response.data){
                        var button = '<button id="'+ response.data[x].id +'" name="btn_edit" class="btn btn-info btn-xs btn-flat" title="Edit Data"><i class="fa fa-edit"></i></button> <button id="'+ response.data[x].id +'" name="btn_delete" class="btn btn-danger btn-xs btn-flat" title="Hapus Data"><i class="fa fa-trash"></i></button>';

	            		row.push({
	            			'no'            : i,
                            'merk_serie'    : response.data[x].merk_serie,
                            'spesifikasi'   : response.data[x].spesifikasi,
                            'kondisi'       : response.data[x].persentase_kondisi + ' % ' + response.data[x].nama_kondisi,
                            'harga'         : response.data[x].harga,
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
            { 'data' : 'merk_serie' },
            { 'data' : 'spesifikasi' },
            { 'data' : 'kondisi' },
            { 'data' : 'harga' },
            { 'data' : 'status' },
        	{ 'data' : 'aksi' }
        ],

        'order' 	: [[ 1, 'ASC' ]],

		'columnDefs': [
    		{
    			'orderable'	: false,
    			'targets'	: [ 0, 6 ]
    		}
  		]
	});

    $.ajax({
        type: 'GET',
        url: baseurl + 'master-kondisi/select/',
        dataType: 'json',
        success: function(response){
            if(response.result){
                for(var x in response.data){
                    $('select[name="id_kondisi"]').append('<option value="'+ response.data[x].id +'">'+response.data[x].nama_kondisi+'</option>');
                }
            }
        }
    });
    selectKondisi = $('select[name="id_kondisi"]').select2();

    $.ajax({
        type: 'GET',
        url: baseurl + 'master-status/stock-laptop/',
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

$('button[name="btn_add"]').click(function(){
	csrf();
	$('button[name="btn_save"]').attr('id', '0');
    $('input[name="merk_serie"]').val('');
    $('textarea[name="spesifikasi"]').val('');
    $('input[name="persentase_kondisi"]').val('');
    $('input[name="harga"]').val('');
    $('textarea[name="keterangan"]').val('');
    $(selectKondisi).val('1').trigger('change');
    $(selectStatus).val('13').trigger('change');

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
        url: baseurl + 'stock-laptop/edit/'+ id +'/',
        dataType: 'json',
        success: function(response){
            if(response.result){
                var d = response.data;

                $(selectKondisi).find('option').each(function(){
                    if ($(this).val() == d.id_kondisi) {
                        $(selectKondisi).val($(this).val()).trigger('change');
                    }
                });

                $(selectStatus).find('option').each(function(){
                    if ($(this).val() == d.id_status) {
                        $(selectStatus).val($(this).val()).trigger('change');
                    }
                });

                $('input[name="merk_serie"]').val(d.merk_serie);
                $('textarea[name="spesifikasi"]').val(d.spesifikasi);
                $('input[name="persentase_kondisi"]').val(d.persentase_kondisi);
                $('input[name="harga"]').val(d.harga);
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
        url: baseurl + 'stock-laptop/delete/',
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

    $.ajax({
        type: 'POST',
        url: baseurl + 'stock-laptop/save/',
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
