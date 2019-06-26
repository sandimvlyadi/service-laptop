var tableStockLaptop = '';
var tableStockSparePart = '';

$('.li-dashboard').addClass('active');

$(document).ready(function(){
	tableStockLaptop = $('#dataTableLaptop').DataTable({
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
	            		row.push({
	            			'no'            : i,
                            'merk_serie'    : response.data[x].merk_serie,
                            'spesifikasi'   : response.data[x].spesifikasi,
                            'kondisi'       : response.data[x].persentase_kondisi + ' % ' + response.data[x].nama_kondisi,
                            'harga'         : response.data[x].harga,
                            'status'        : response.data[x].status
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
            { 'data' : 'status' }
        ],

        'order' 	: [[ 1, 'ASC' ]],

		'columnDefs': [
    		{
    			'orderable'	: false,
    			'targets'	: [ 0 ]
    		}
  		]
	});

    tableStockSparePart = $('#dataTableSparePart').DataTable({
        'processing'    : true,
        'serverSide'    : true,

        'ajax' : {
            'url'   : baseurl + 'stock-spare-part/datatable/',
            'type'  : 'GET',
            'dataSrc' : function(response){
                var i = response.start;
                var row = new Array();
                if (response.result) {
                    for(var x in response.data){
                        row.push({
                            'no'                : i,
                            'nama_spare_part'   : response.data[x].nama_spare_part,
                            'total'             : response.data[x].total
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
            { 'data' : 'total' }
        ],

        'order'     : [[ 1, 'ASC' ]],

        'columnDefs': [
            {
                'orderable' : false,
                'targets'   : [ 0 ]
            }
        ]
    });
});