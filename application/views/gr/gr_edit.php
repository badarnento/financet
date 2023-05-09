<div class="row">
  <div class="white-box boxshadow">
    <div class="row">
    	<form class="form-horizontal">
    	<div class="col-sm-6 col-md-5">
        	<div class="form-group m-b-10">
          	<label class="col-sm-5 col-md-4 control-label text-left">Directorate <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="directorat" class="control-label"><?= $directorat ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Division <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="division" class="control-label"><?= $division ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Unit <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="unit" class="control-label"><?= $unit ?></label>
            </div>
          </div>
        	<div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Po Number <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="po_number" class="control-label"><?= $po_number ?></label>
            </div>
          </div>
        </div>

        <div class="col-sm-6 col-md-5">
          <div class="form-group m-b-10">
            <label for="GR_date" class="col-sm-5 col-md-4 control-label text-left">GR Date <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-6">
				<div class="input-group">
					<input type="text" class="form-control mydatepicker" id="gr_date" placeholder="dd-mm-yyyy" value="<?= $gr_date ?>">
					<span class="input-group-addon"><i class="icon-calender"></i></span>
				</div>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Contract Identification <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="contract" class="control-label"><?= $contract ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Budget Type <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="budget_type" class="control-label"><?= $budget_type ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Project Ownership <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="project" class="control-label"><?= $project ?></label>
            </div>
          </div>
        </div>
    	</form>
    </div>
</div>
</div>

<div class="row">
  <div class="white-box boxshadow">
    <div class="row">
    	<div class="col-md-12">
  			<table id="table_data" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small hover w-full hover" style="font-size:11px !important;">
            <thead>
              <tr>
				<th class="text-center">No</th>
				<th class="text-center" style="display: none">GR Line ID</th>
	          	<th class="text-center">Item Name</th>
	          	<th class="text-center">Item Description</th>
	          	<th class="text-center">Quantity</th>
	          	<th class="text-center">UoM</th>
	          	<th class="text-center">Unit Price</th>
	          	<th class="text-center">Total Price</th>
	          	<th class="text-center">Asset Type</th>
	          	<th class="text-center">Serial Number</th>
	          	<th class="text-center">Merek</th>
	          	<th class="text-center">Umur Manfaat <a href="<?= base_url('assets/img/umur_manfaat.JPG') ?>" id="umur_manfaat_info" target="blank"><i class="fa fa-info-circle"></i></a></th>
	          	<th class="text-center">Invoice Date</th>
	          	<th class="text-center">Receipt Date</th>
              </tr>
            </thead>
  		    </table>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="white-box border-radius-5 small">
      	<form class="form-horizontal" id="form-submitter">
	      	<div class="row">
	      		<div class="col-sm-6 col-sm-offset-6">
			        <div class="form-group pull-right">
			        	<button type="button" id="save_data" class="btn btn-info border-radius-5 m-10 w-150p"><i class="fa fa-save"></i> Save </button>
			        </div>
	      		</div>
	      	</div>
      	</form>
  </div>
</div>

<script>
  $(document).ready(function(){

	const ID_GR       = '<?= $id_gr ?>';
	const directorat  = <?= $gr_directorat ?>;
	const division    = <?= $gr_division ?>;
	const unit        = <?= $gr_unit ?>;
	
	let url = baseURL + 'general-receipt/api/load_data_gr_edit';

	let counter        = 1;
	let pr_line_data   = {};

	let buttonAdd = '<button type="button" id="addRow-detail" class="btn btn-success btn-sm m-l-5 mr-5"><i class="fa fa-plus"></i> Add New </button>';
		buttonAdd += '<button type="button" id="deleteRow-detail" class="btn btn-danger btn-sm m-l-5 mr-5" disabled><i class="fa fa-trash"></i> Delete </button>';

	Pace.track(function(){
	    $('#table_data').DataTable({
	      "serverSide"      : true,
	      "processing"      : true,
	      "ajax"            : {
									"url"  : url,
									"type" : "POST",
									"dataType": "json",
									"data"    : function ( d ) {
													d.gr_header_id = ID_GR;
					                            }
			                    },
	      "language"        : {
	                            "emptyTable"  : "<span class='label label-danger'>Data Not Found!</span>",
	                            "infoEmpty"   : "Empty Data",
	                            "processing"  : '<img src="' + imageLoadingSmall + '" alt="" />',
	                            "search"      : "_INPUT_"
	                          },
	      "columns"         : [
								{"data": "no", "width": "10px", "class": "text-center" },
								{"data": "gr_line_id", "width": "180px", "class": "p-5 hidden" },
								{"data": "item_name", "width": "180px", "class": "p-5" },
								{"data": "item_description", "width": "120px", "class": "p-5" },
								{"data": "quantity", "width": "120px", "class": "p-5 text-center"},
								{"data": "uom", "width": "120px", "class": "p-5 text-center"},
								{"data": "unit_price", "width": "120px", "class": "p-5 text-right"},
								{"data": "total_price", "width": "120px", "class": "p-5 text-right"},
								{"data": "asset_type", "width": "120px", "class": "p-5 text-right"},
								{"data": "serial_number", "width": "120px", "class": "p-5 text-left"},
								{"data": "merek", "width": "120px", "class": "p-5 text-left"},
								{"data": "umur_manfaat", "width": "120px", "class": "p-5 text-left"},
								{"data": "invoice_date", "width": "120px", "class": "p-5 text-center"},
								{"data": "receipt_date", "width": "120px", "class": "p-5 text-center"},
				    		],
        "drawCallback": function ( settings ) {
        },
        "pageLength"      : 100,
	      "ordering"        : false,
	      "scrollY"         : 480,
	      "scrollCollapse"  : true,
	      "scrollX"         : true,
	      "autoWidth"       : true,
	      "bAutoWidth"      : false
	    });
	});

	let table = $('#table_data').DataTable();

	$('#table_data_length').html('<h4>PR Lines</h4>');
	$('#table_data_paginate').remove();

	$('#table_data tbody').on('input change blur', 'tr td input.quantity', function () {
		if($(this).val().trim().length === 0){
			$(this).val(0);
		}

		index       = $(this).data('id');
		qty_int     = parseInt( $("#quantity-"+index).val() );
		price_int   = parseInt( $("#unit_price-"+index).val().replace(/\./g, '') );
		nominal_val = (qty_int > 0 && price_int > 0) ? qty_int * price_int : 0;

		$("#total_price-"+index).val(formatNumber(nominal_val));

    });

    $('#table_data tbody').on('focus', 'tr td input.date_period', function () {
    	$(this).datepicker({
			format: 'dd-mm-yyyy',
			todayHighlight:'TRUE',
			autoclose: true,
			minDate:0,
	    });
    });

	$("#save_data").on('click', function () {

		let total_data   = table.data().count();
		let data_lines  = [];

		table.rows().eq(0).each( function (index) {

		j = index+1;
		data = table.row( index ).data();

				gr_line_id = data.gr_line_id;
				quantity         = $("#quantity-"+j).val();
				item_price       = $("#unit_price-"+j).val();
				total_price      = $("#total_price-"+j).val();
				asset_type       = $("#asset_type-"+j).val();
				serial_number    = $("#serial_number-"+j).val();
				merek            = $("#merek-"+j).val();
				umur_manfaat     = $("#umur_manfaat-"+j).val();
				invoice_date     = $("#invoice_date-"+j).val();
				receipt_date     = $("#receipt_date-"+j).val();
				

				if(invoice_date != "" && receipt_date != ""){
		    		data_lines.push({'gr_line_id' : gr_line_id,'quantity' : quantity,'item_price' : parseInt(item_price.replace(/\./g, '')), 'total_price' : parseInt(total_price.replace(/\./g, '')),'asset_type' : asset_type, 'serial_number' : serial_number, 'merek' : merek, 'umur_manfaat' : umur_manfaat, 'invoice_date' : invoice_date, 'receipt_date' : receipt_date});
				}
			});

		    data = {
						id_gr : ID_GR,
						directorat : directorat,
						division : division,
						unit : unit,
						data_line : data_lines
		    		}

		    		console.log(data);

		    $.ajax({
		        url   : baseURL + 'general-receipt/api/save_gr_edit',
		        type  : "POST",
		        data  : data,
		        dataType: "json",
		        beforeSend  : function(){
                          customLoading('show');
                        },
		        success : function(result){
		        	console.log(result)
		        	if(result.status == true){
		        		customNotif('Success', "GR Updated", 'success');
		        		setTimeout(function(){
		        			customLoading('hide');
		        			$(location).attr('href', baseURL + 'general-receipt')s;
		        		}, 500);
		        	}
		        	else{
		        		customLoading('hide');
		        		customNotif('Error', result.messages, 'error');
		        	}
		        }
		    });
		
	});



  });
</script>