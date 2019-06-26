var table = '';
var tableDetail = $('#dataTableDetail').DataTable();
var selectKondisi = '';
var selectStatus = '';
var selectSatuan = '';
var selectTypeMonitor = '';
var idSparePart = 0;

$('.li-stock').addClass('menu-open');
$('.li-stock .treeview-menu').css('display', 'block');
$('.li-stock-spare-part').addClass('active');

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
        	'url'	: baseurl + 'stock-spare-part/datatable/',
            'type'	: 'GET',
            'dataSrc' : function(response){
            	var i = response.start;
            	var row = new Array();
            	if (response.result) {
            		for(var x in response.data){
                        var button = '<button id="'+ response.data[x].id +'" name="btn_view" class="btn btn-info btn-xs btn-flat" title="Lihat Data"><i class="fa fa-eye"></i> Lihat Detail</button>';

	            		row.push({
	            			'no'                : i,
                            'nama_spare_part'   : response.data[x].nama_spare_part,
                            'total'             : response.data[x].total,
	            			'aksi'              : button
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
            { 'data' : 'nama_spare_part' },
            { 'data' : 'total' },
        	{ 'data' : 'aksi' }
        ],

        'order' 	: [[ 1, 'ASC' ]],

		'columnDefs': [
    		{
    			'orderable'	: false,
    			'targets'	: [ 0, 3 ]
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
        url: baseurl + 'master-status/stock-spare-part/',
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

    $.ajax({
        type: 'GET',
        url: baseurl + 'master-type-monitor/select/',
        dataType: 'json',
        success: function(response){
            if(response.result){
                $('select[name="id_type_monitor"]').append('<option value="0">- Pilih Type Monitor -</option>');
                for(var x in response.data){
                    $('select[name="id_type_monitor"]').append('<option value="'+ response.data[x].id +'">'+response.data[x].nama_type+'</option>');
                }
            } else{
                $('select[name="id_type_monitor"]').append('<option value="0">- Pilih Type Monitor -</option>');
            }
        }
    });
    selectTypeMonitor = $('select[name="id_type_monitor"]').select2();

    $.ajax({
        type: 'GET',
        url: baseurl + 'master-satuan/select/',
        dataType: 'json',
        success: function(response){
            if(response.result){
                $('select[name="id_satuan"]').append('<option value="0">- Pilih Satuan -</option>');
                for(var x in response.data){
                    $('select[name="id_satuan"]').append('<option value="'+ response.data[x].id +'">'+response.data[x].nama_satuan+'</option>');
                }
            } else{
                $('select[name="id_satuan"]').append('<option value="0">- Pilih Satuan -</option>');
            }
        }
    });
    selectSatuan = $('select[name="id_satuan"]').select2();
});

$('#dataTable').on('click', 'button[name="btn_view"]', function(){
    var id = $(this).attr('id');
    idSparePart = id;

    tableDetail.destroy();
    tableDetail = $('#dataTableDetail').DataTable({
        'processing'    : true,
        'serverSide'    : true,

        'ajax' : {
            'url'   : baseurl + 'stock-spare-part/datatable/' + id,
            'type'  : 'GET',
            'dataSrc' : function(response){
                var i = response.start;
                var row = new Array();
                if (response.result) {
                    var namaSparePart = '';
                    for(var x in response.data){
                        var button = '<button id="'+ response.data[x].id +'" name="btn_edit" class="btn btn-info btn-xs btn-flat" title="Lihat Data"><i class="fa fa-edit"></i></button> <button id="'+ response.data[x].id +'" name="btn_delete" class="btn btn-danger btn-xs btn-flat" title="Hapus Data"><i class="fa fa-trash"></i></button>';

                        row.push({
                            'no'                : i,
                            'nama_spare_part'   : response.data[x].nama_spare_part,
                            'merk_serie'        : response.data[x].merk_serie,
                            'spesifikasi'       : response.data[x].spesifikasi,
                            'harga_beli'        : response.data[x].harga_beli,
                            'harga_jual'        : response.data[x].harga_jual,
                            'jml_stock'         : response.data[x].jml_stock,
                            'aksi'              : button
                        });
                        i = i + 1;

                        namaSparePart = response.data[x].nama_spare_part;
                    }

                    $('.content-header h1').text('Stock ' + namaSparePart);

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
            { 'data' : 'nama_spare_part' },
            { 'data' : 'merk_serie' },
            { 'data' : 'spesifikasi' },
            { 'data' : 'harga_beli' },
            { 'data' : 'harga_jual' },
            { 'data' : 'jml_stock' },
            { 'data' : 'aksi' }
        ],

        'order'     : [[ 1, 'ASC' ]],

        'columnDefs': [
            {
                'orderable' : false,
                'targets'   : [ 0, 7 ]
            }
        ]
    });

    $('button[name="btn_back"]').show();
    $('button[name="btn_add"]').show();

    $('.table-main').hide();
    $('.table-detail').show();
});

$('button[name="btn_back"]').click(function(){
    $('.content-header h1').text('Stock Spare Part');

    $('button[name="btn_back"]').hide();
    $('button[name="btn_add"]').hide();

    $('.table-main').show();
    $('.table-detail').hide();
});

function fieldHandler(id)
{
    $('input[name="kapasitas"]').prop('required', false);
    $('input[name="batre_for"]').prop('required', false);
    $('input[name="adaptor_for"]').prop('required', false);
    $('input[name="chassing_for"]').prop('required', false);
    $('input[name="keyboard_for"]').prop('required', false);

    switch(id){
        case '1': // mainboard
            $('.fieldKapasitas').hide();
            $('.fieldSatuan').hide();
            $('.fieldTypeMonitor').hide();
            $('.fieldBatreFor').hide();
            $('.fieldAdaptorFor').hide();
            $('.fieldChassingFor').hide();
            $('.fieldKeyboardFor').hide();
            break;
        case '2': // harddisk
            $('input[name="kapasitas"]').prop('required', true);
            $('.fieldKapasitas').show();
            $('.fieldSatuan').show();
            $('.fieldTypeMonitor').hide();
            $('.fieldBatreFor').hide();
            $('.fieldAdaptorFor').hide();
            $('.fieldChassingFor').hide();
            $('.fieldKeyboardFor').hide();
            break;
        case '3': // ram
            $('input[name="kapasitas"]').prop('required', true);
            $('.fieldKapasitas').show();
            $('.fieldSatuan').show();
            $('.fieldTypeMonitor').hide();
            $('.fieldBatreFor').hide();
            $('.fieldAdaptorFor').hide();
            $('.fieldChassingFor').hide();
            $('.fieldKeyboardFor').hide();
            break;
        case '4': // monitor
            $('.fieldKapasitas').hide();
            $('.fieldSatuan').hide();
            $('.fieldTypeMonitor').show();
            $('.fieldBatreFor').hide();
            $('.fieldAdaptorFor').hide();
            $('.fieldChassingFor').hide();
            $('.fieldKeyboardFor').hide();
            break;
        case '5': // batre
            $('input[name="batre_for"]').prop('required', true);
            $('.fieldKapasitas').hide();
            $('.fieldSatuan').hide();
            $('.fieldTypeMonitor').hide();
            $('.fieldBatreFor').show();
            $('.fieldAdaptorFor').hide();
            $('.fieldChassingFor').hide();
            $('.fieldKeyboardFor').hide();
            break;
        case '6': // adaptor
            $('input[name="adaptor_for"]').prop('required', true);
            $('.fieldKapasitas').hide();
            $('.fieldSatuan').hide();
            $('.fieldTypeMonitor').hide();
            $('.fieldBatreFor').hide();
            $('.fieldAdaptorFor').show();
            $('.fieldChassingFor').hide();
            $('.fieldKeyboardFor').hide();
            break;
        case '7': // chassing
            $('input[name="chassing_for"]').prop('required', true);
            $('.fieldKapasitas').hide();
            $('.fieldSatuan').hide();
            $('.fieldTypeMonitor').hide();
            $('.fieldBatreFor').hide();
            $('.fieldAdaptorFor').hide();
            $('.fieldChassingFor').show();
            $('.fieldKeyboardFor').hide();
            break;
        case '8': // keyboard
            $('input[name="keyboard_for"]').prop('required', true);
            $('.fieldKapasitas').hide();
            $('.fieldSatuan').hide();
            $('.fieldTypeMonitor').hide();
            $('.fieldBatreFor').hide();
            $('.fieldAdaptorFor').hide();
            $('.fieldChassingFor').hide();
            $('.fieldKeyboardFor').show();
            break;
        case '9': // processor
            $('.fieldKapasitas').hide();
            $('.fieldSatuan').hide();
            $('.fieldTypeMonitor').hide();
            $('.fieldBatreFor').hide();
            $('.fieldAdaptorFor').hide();
            $('.fieldChassingFor').hide();
            $('.fieldKeyboardFor').hide();
            break;
        default: // default
            $('.fieldKapasitas').hide();
            $('.fieldSatuan').hide();
            $('.fieldTypeMonitor').hide();
            $('.fieldBatreFor').hide();
            $('.fieldAdaptorFor').hide();
            $('.fieldChassingFor').hide();
            $('.fieldKeyboardFor').hide();
            break;
    }
}

$('button[name="btn_add"]').click(function(){
	csrf();
	$('button[name="btn_save"]').attr('id', '0');
    $('input[name="id_spare_part"]').val(idSparePart);
    $('input[name="merk_serie"]').val('');
    $('textarea[name="spesifikasi"]').val('');
    $('input[name="harga_beli"]').val('');
    $('input[name="harga_jual"]').val('');
    $('input[name="kapasitas"]').val('');
    $('input[name="jml_stock"]').val('');
    $('input[name="batre_for"]').val('');
    $('input[name="adaptor_for"]').val('');
    $('input[name="chassing_for"]').val('');
    $('input[name="keyboard_for"]').val('');
    $(selectKondisi).val('1').trigger('change');
    $(selectStatus).val('14').trigger('change');
    $(selectSatuan).val('0').trigger('change');
    $(selectTypeMonitor).val('0').trigger('change');
    fieldHandler(idSparePart);

    $('#formTitle').text('Tambah Data');

	$('#table').hide();
	setTimeout(function(){
		$('#form').fadeIn()
	}, 100);
});

$('#dataTableDetail').on('click', 'button[name="btn_edit"]', function(){
	csrf();
	var id = $(this).attr('id');

    $.ajax({
        type: 'GET',
        url: baseurl + 'stock-spare-part/edit/'+ id +'/',
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

                $(selectTypeMonitor).find('option').each(function(){
                    if ($(this).val() == d.id_type_monitor) {
                        $(selectTypeMonitor).val($(this).val()).trigger('change');
                    }
                });

                $(selectSatuan).find('option').each(function(){
                    if ($(this).val() == d.id_satuan) {
                        $(selectSatuan).val($(this).val()).trigger('change');
                    }
                });

                $('input[name="id_spare_part"]').val(idSparePart);
                $('input[name="merk_serie"]').val(d.merk_serie);
                $('textarea[name="spesifikasi"]').val(d.spesifikasi);
                $('input[name="harga_beli"]').val(d.harga_beli);
                $('input[name="harga_jual"]').val(d.harga_jual);
                $('input[name="kapasitas"]').val(d.kapasitas);
                $('input[name="jml_stock"]').val(d.jml_stock);
                $('input[name="batre_for"]').val(d.batre_for);
                $('input[name="adaptor_for"]').val(d.adaptor_for);
                $('input[name="chassing_for"]').val(d.chassing_for);
                $('input[name="keyboard_for"]').val(d.keyboard_for);
                fieldHandler(idSparePart);

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

$('#dataTableDetail').on('click', 'button[name="btn_delete"]', function(){
	if (!confirm('Apakah anda yakin?')) {
		return;
	}

	var id = $(this).attr('id');

	$.ajax({
        type: 'POST',
        url: baseurl + 'stock-spare-part/delete/',
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
                tableDetail.ajax.reload(null, false);
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
        url: baseurl + 'stock-spare-part/save/',
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
                tableDetail.ajax.reload(null, false);
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
