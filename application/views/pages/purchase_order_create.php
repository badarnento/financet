<div class="row">
  <div class="white-box boxshadow">
	<form class="form-horizontal">
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group m-b-10">
		            <label for="pr_number" class="col-sm-3 control-label">PR Number <span class="pull-right">:</span></label>
		            <div class="col-sm-9 col-md-6">
		              <input type="text" class="form-control" id="pr_number" value="<?= $pr_number ?>" readonly>
		            </div>
		        </div>
		        <div class="form-group m-b-10">
		            <label for="pr_name" class="col-sm-3 control-label">PR Name <span class="pull-right">:</span></label>
		            <div class="col-sm-9 col-md-6">
		              <input type="text" class="form-control" id="pr_name" value="<?= $pr_name ?>" readonly>
		            </div>
		        </div>
		        <div class="form-group m-b-10">
		            <label for="currency" class="col-sm-3 control-label">Currency <span class="pull-right">:</span></label>
		            <div class="col-sm-9 col-md-6">
		            	<input type="text" class="form-control" id="currency" readonly value="<?= strtoupper($pr_currency) ?>">
		            </div>
		        </div>
		        <div class="form-group m-b-10">
		            <label for="amount" class="col-sm-3 control-label">Total Amount <span class="pull-right">:</span></label>
		            <div class="col-sm-9 col-md-6">
		            	<input type="text" class="form-control" id="amount" readonly value="<?= $pr_amount ?>">
		            </div>
		        </div>
		        <div class="form-group m-b-10">
		            <label for="directorat" class="col-sm-3 control-label">Directorat <span class="pull-right">:</span></label>
		            <div class="col-sm-9 col-md-6">
		              <input type="text" class="form-control" id="directorat" readonly value="<?= get_directorat($pr_directorat) ?>">
		            </div>
		        </div>
		        <div class="form-group m-b-10">
		            <label for="division" class="col-sm-3 control-label">Division <span class="pull-right">:</span></label>
		            <div class="col-sm-9 col-md-6">
		              <input type="text" class="form-control" id="division" readonly value="<?= get_division($pr_division) ?>">
		            </div>
		        </div>
		        <div class="form-group m-b-10">
		            <label for="unit" class="col-sm-3 control-label">Unit <span class="pull-right">:</span></label>
		            <div class="col-sm-9 col-md-6">
		              <input type="text" class="form-control" id="unit" readonly value="<?= get_unit($pr_unit) ?>">
		            </div>
		        </div>
			</div>
			<div class="col-sm-6">
		        <div class="form-group m-b-10">
		          	<label for="pr_date" class="col-sm-3 control-label">PR Date <span class="pull-right">:</span></label>
		            <div class="col-sm-9 col-md-6">
		            	<div class="input-group">
		            		<input type="text" class="form-control" id="pr_date" value="<?= $pr_date ?>" readonly>
		            	</div>
		            </div>
		        </div>
		        <div class="form-group m-b-10">
		            <label for="po_type" class="col-sm-3 control-label">PO Type <span class="pull-right">:</span></label>
		            <div class="col-sm-9 col-md-6">
						<select name="po_type" id="po_type" class="form-control">
							<option value="Normal">Normal</option>
							<option value="Additional">Additional</option>
						</select>
		            </div>
		        </div>
		        <div class="form-group m-b-10 po_reference d-none">
		            <label for="po_reference" class="col-sm-3 control-label">Po Reference <span class="pull-right">:</span></label>
		            <div class="col-sm-9 col-md-6">
	    				<select name="po_reference" id="po_reference" class="form-control" disabled="">
	    					<option value="0">-- Choose --</option>
	    				</select>
		            </div>
		        </div>
		        <div class="form-group m-b-10 po_reference d-none">
		            <label for="po_name" class="col-sm-3 control-label">PO Name <span class="pull-right">:</span></label>
		            <div class="col-sm-9 col-md-6">
		              <!-- <input type="text" class="form-control" id="po_name" readonly> -->
		              <textarea class="form-control" id="po_name"  rows="2"readonly></textarea>
		            </div>
		        </div>
			</div>
		</div>
    </form>
  </div>
</div>
<div class="row">
	<div class="white-box">
	    <div class="row">
			<div class="col-md-6">
				<h4 id="table_data_title">PO Lines</h4>
			</div>
	    </div>
		<div class="row">
			<div class="col-md-12">
		      <table id="table_data" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small hover w-full">
		        <thead>
		        	<tr>
			          	<th class="text-center">No</th>
			          	<th class="text-center">Tribe/Usecase</th>
			          	<th class="text-center">RKAP Name (Description)</th>
			          	<th class="text-center">PR Name</th>
			          	<th class="text-center">PR Amount</th>
			          	<th class="text-center">PO Number</th>
			          	<th class="text-center">PO Name</th>
			          	<th class="text-center">PO Amount</th>
			          	<th class="text-center">PO Period From</th>
			          	<th class="text-center">PO Period To</th>
			          	<th class="text-center">Vendor Name</th>
			          	<th class="text-center">Vendor Bank Name</th>
			          	<th class="text-center">Vendor Bank Account Name</th>
			          	<th class="text-center">Vendor Bank Account Number</th>
			        </tr>
		        </thead>
		      </table>
		    </div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<hr>
				<h4 id="table_detail_title">PO Detail of line 1</h4>
			</div>
			<div class="col-md-12">
		      <table id="table_detail" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small w-full">
		        <thead>
		        	<tr>
			          	<th class="text-center">No</th>
			          	<th class="text-center">Description</th>
			          	<th class="text-center">Quantity</th>
			          	<th class="text-center">Price</th>
			          	<th class="text-center">Nominal</th>
			          	<th class="text-center">Description</th>
			          	<th class="text-center">Nominal</th>
			        </tr>
		        </thead>
		      </table>
		    </div>
		</div>
		<div class="row">
	    	<div class="col-md-3 col-md-offset-9 col-sm-6 col-sm-offset-6 m-t-20">
	    		<div class="pull-right"><button type="button" id="save_data" class="btn btn-info m-10 w-100p"><i class="fa fa-save"></i> Save </button></div>
	    	</div>
		</div>
  </div>
</div>
<script>
  $(document).ready(function(){

	const pr_header_id = '<?= $pr_header_id ?>';
	const pr_number    = '<?= $pr_number ?>';
	const opt_default  = '<option value="0" data-name="">-- Choose --</option>';
	let po_line_data   = {};

	let url  = baseURL + 'purchase/load_data_pr_for_po_create';

	Pace.track(function(){
	    $('#table_data').DataTable({
	      "serverSide"      : true,
	      "processing"      : true,
	      "ajax"            : {
									"url"  : url,
									"type" : "POST",
									"dataType": "json",
									"data"    : function ( d ) {
					                              d.pr_header_id = pr_header_id;
					                            }
			                    },
	      "language"        : {
	                            "emptyTable"  : "<span class='label label-danger'>Data Not Found!</span>",
	                            "infoEmpty"   : "Data Kosong",
	                            "processing"  : '<img src="' + imageLoadingSmall + '" alt="" />',
	                            "search"      : "_INPUT_"
	                          },
	      "columns"         : [
				    			{"data": "no", "width": "10px", "class": "text-center" },
				    			{"data": "tribe", "width": "100px" },
				    			{"data": "rkap_name", "width": "300px"},
				    			{"data": "pr_name", "width": "150px"},
				    			{"data": "pr_amount", "width": "100px", "class": "text-right" },
				    			{"data": "po_number", "width": "100px"},
				    			{"data": "po_line_name", "width": "150px"},
				    			{"data": "po_amount", "width": "120px", "class": "text-right" },
				    			{"data": "po_period_from", "width": "120px"},
				    			{"data": "po_period_to", "width": "120px"},
				    			{"data": "vendor_name", "width": "250px"},
				    			{"data": "vendor_bank_name", "width": "150px"},
				    			{"data": "vendor_bank_account", "width": "150px"},
				    			{"data": "vendor_bank_account_number", "width": "150px"}
				    		],
          "drawCallback": function ( settings ) {
          	$(".select2").select2();
          },
	      "pageLength"      : 100,
	      "ordering"        : false,
	      "scrollY"         : 480,
	      "scrollX"         : true,
	      "scrollCollapse"  : true,
	      "autoWidth"       : true,
	      "bAutoWidth"      : true,
	    });
	});

	let table = $('#table_data').DataTable();
	let pr_lines_id = 0;
	let tracker = 1;

	$('#table_data').on( 'draw.dt', function () {
		/*$('#table_data tbody tr').eq(0).addClass('selected');

		row_now       = $('#table_data tbody tr.selected');
		let get_table = table.row( row_now );
		let data      = get_table.data();

		pr_lines_id = data.pr_lines_id;
		table_detail.draw();*/
		if(tracker == 1){
			setTimeout(function(){
				$('#table_data tbody tr').eq(0).trigger('click');
			}, 500);
		}
			
		tracker++;
	});



	$('#table_data_length').html('');
	$('#table_data_paginate').css('display', 'none');
	$('#table_data_filter').html('');

	let url_detail  = baseURL + 'purchase/load_pr_detail_for_po';

	Pace.track(function(){
	    $('#table_detail').DataTable({
	      "serverSide"      : true,
	      "processing"      : true,
	      "ajax"            : {
									"url"  : url_detail,
									"type" : "POST",
									"dataType": "json",
									"data"    : function ( d ) {
					                              d.pr_lines_id = pr_lines_id;
					                            }
			                    },
	      "language"        : {
	                            "emptyTable"  : "<span class='label label-danger'>Data Not Found!</span>",
	                            "infoEmpty"   : "Data Kosong",
	                            "processing"  : '<img src="' + imageLoadingSmall + '" alt="" />',
	                            "search"      : "_INPUT_"
	                          },
	      "columns"         : [
				    			{"data": "no", "width": "10px", "class": "text-center" },
				    			{"data": "detail_desc", "width": "250px", "class": "p-2" },
				    			{"data": "quantity", "width": "100px", "class": "p-2 text-center" },
				    			{"data": "price", "width": "150px", "class": "p-2 text-right" },
				    			{"data": "nominal", "width": "150px", "class": "p-2 text-right" },
				    			{"data": "po_desc", "width": "250px", "class": "p-2" },
				    			{"data": "nominal_detail_po", "width": "150px", "class": "p-2" }
				    		],
	      "pageLength"      : 100,
	      "ordering"        : false,
	      "scrollY"         : 480,
	      "scrollX"         : true,
	      "scrollCollapse"  : true,
	      "autoWidth"       : true,
	      "bAutoWidth"      : true
	    });
	});

	let table_detail = $('#table_detail').DataTable();;
	$('#table_detail_filter').html('');
	$('#table_detail_paginate').css('display', 'none');
	$('#table_detail_length').html('');

    $('#table_data tbody').on('focus', 'tr td input.date-picker-po', function () {
    	$(this).datepicker({
			format: 'dd-mm-yyyy',
			todayHighlight:'TRUE',
			autoclose: true,
			minDate:0,
	    });
    });

	$('#table_data tbody').on('change', 'tr td input.date-picker-po', function () {

		this_row   = $(this).parents("tr").index() + 1;

		date_from = $("#po_period_from-"+this_row).val();
		df_split  = date_from.split("-");
		newDF     = df_split[2]+"/"+df_split[1]+"/"+df_split[0];
		tsDF      = new Date(newDF).getTime()/1000;

		date_to  = $("#po_period_to-"+this_row).val();
		dt_split = date_to.split("-");
		newDT    = dt_split[2]+"/"+dt_split[1]+"/"+dt_split[0];
		tsDT     = new Date(newDT).getTime()/1000;

		if(tsDF > tsDT){
			$("#po_period_to-"+this_row).val(date_from);
			console.log('kerubah')
		}
    });
	$('#table_data tbody').on('change', 'tr td select.vendor_name', function () {
		this_row   = $(this).parents("tr").index() + 1;

		let bank     = $(this).find(':selected').attr('data-bank');
		let nama_rek = $(this).find(':selected').attr('data-nama_rek');
		let no_rek   = $(this).find(':selected').attr('data-no_rek');

		$("#bank_name-"+this_row).val(bank);
		$("#account_name-"+this_row).val(nama_rek);
		$("#account_number-"+this_row).val(no_rek);

    });

    $('#table_detail').on( 'draw.dt', function () {
    	
    	total_detail = table_detail.data().count()

    	if(total_detail > 0){
    		row_now       = $('#table_data tbody tr.selected');
			let get_table = table.row( row_now );
			let data      = get_table.data();
			po_line_key = data.po_line_key;

			if( po_line_key in po_line_data ) {
				data_detail = po_line_data[po_line_key];
				data_detail.forEach(function(value, i) {
					num = i+1;
					$("#po_desc-"+num).val(value.po_desc);
					$("#nominal_detail_po-"+num).val(formatNumber(value.nominal));
				});
			}
    	}
	});


	$('#table_detail tbody').on('input', 'tr td input.nominal_detail_po', function () {
		this_row   = $(this).parents("tr")
		get_data   = table_detail.row(this_row).data();
		pr_nominal = get_data.nominal;
		po_nominal = $(this).val();

		if(parseInt(po_nominal.replace(/\./g, '')) > parseInt(pr_nominal.replace(/\./g, ''))){
			$(this).val(pr_nominal);
		}
    });


	$('#table_detail tbody').on('change', 'tr td input', function () {
		data        = table_detail.row( $(this).parents('tr') ).data();
		pr_lines_id = data.pr_lines_id;
		id_detail   = data.pr_detail_id;
		id_row      = $(this).data('id');

		// autoSaveDetail(pr_lines_id, id_detail, id_row);
		storeDataDetail();
    });

    let tracker_ref = 1;

	$('#po_type').on('change', function () {
		val_po_type = $(this).val();

		if(val_po_type == "Additional"){
			$(".po_reference").removeClass('d-none');
			if(tracker_ref == 1){
				$("#po_reference").css('cursor', 'wait');
				getPoReference();
			}
			tracker_ref++;
		}
		else{
			$(".po_reference").addClass('d-none');
		}
    });

	$('#po_reference').on('change', function () {
		val_po_name = $(this).find(":selected").data('name');
		$("#po_name").html(val_po_name);
    });


	$('#table_detail tbody').on('change', 'tr td input.nominal_detail_po', function () {
		if($(this).val().trim().length === 0){
			$(this).val(0);
		}
		getNominal();
    });


    function getNominal(){
		let total_detail = table_detail.data().count();
		let totalNominal = 0;

		for (var i = 1; i <= total_detail; i++) {
			nominal_detail_val = $("#nominal_detail_po-"+i).val();
			totalNominal += parseInt(nominal_detail_val.replace(/\./g, ''));
	    }

	    $("#table_data tbody tr.selected").find("input.po_amount").val(formatNumber(totalNominal));
    }

    function getPoReference(){

		let directorat = <?= $pr_directorat ?>;
		let division   = <?= $pr_division ?>;
		let unit       = <?= $pr_unit ?>;

	    $.ajax({
	        url   : baseURL + 'purchase/load_po_reference',
	        type  : "POST",
	        data  : {directorat : directorat, division : division, unit : unit},
	        dataType: "json",
	        success : function(result){
        		let po_ref_opt = opt_default;
	        	if(result.status == true){
					data = result.data;
		        	for(var i = 0; i < data.length; i++) {
					    obj = data[i];
					    po_ref_opt += '<option value="'+ obj.po_number +'" data-name="'+ obj.po_name +'">'+ obj.po_number +'</option>';
					}
	        	}
				$("#po_reference").html(po_ref_opt);
				$("#po_reference").attr('disabled', false);
				$("#po_reference").css('cursor', 'default');
	        }
	    });
    }

    function autoSaveDetail(pr_lines_id, id_detail, id_row){

		po_desc        = $("#po_desc-"+id_row).val();
		nominal_detail = $("#nominal_detail_po-"+id_row).val();
		po_amount      = parseInt(nominal_detail.replace(/\./g, ''));

		if(po_desc != "" && po_amount > 0){

	    	$.ajax({
	          url       : baseURL + 'purchase/auto_save_detail',
	          type      : 'post',
	          data      : {
							pr_lines_id: pr_lines_id,
							id_detail: id_detail,
							pr_number: pr_number,
							po_desc: po_desc,
							po_amount: po_amount,
	          			},
	          dataType : 'json',
	          success : function(result){
	          	console.log(result);
	          }
	        });
		}
    }

	$("#save_data").on('click', function () {

		let total_data   = table.data().count();
		let data_po      = [];
		let data_empty   = [];
		let total_amount = 0;
		let po_type      = $("#po_type").val();
		let po_reference = $("#po_reference").val();

		table.rows().eq(0).each( function ( index ) {
	    	j = index+1;
		    data = table.row( index ).data();

			po_number      = $("#po_number-"+j).val();
			po_line_name   = $("#po_line_name-"+j).val();
			po_amount      = $("#po_amount-"+j).val();
			po_period_from = $("#po_period_from-"+j).val();
			po_period_to   = $("#po_period_to-"+j).val();
			vendor_name    = $("#vendor_name-"+j).val();
			bank_name      = $("#bank_name-"+j).val();
			account_name   = $("#account_name-"+j).val();
			account_number = $("#account_number-"+j).val();
			
			id_rkap_line = data.id_rkap_line;
			pr_lines_id  = data.pr_lines_id;
			po_line_key  = data.po_line_key;

			if(po_number != "" && po_line_name != "" && parseInt(po_amount.replace(/\./g, '')) > 0 && vendor_name != ""){
				total_amount += parseInt(po_amount.replace(/\./g, ''));
				detail_po    = po_line_data[po_line_key];
	    		data_po.push({'pr_lines_id' : pr_lines_id, 'id_rkap_line' : id_rkap_line, 'po_number' : po_number, 'po_line_name' : po_line_name, 'po_amount' : parseInt(po_amount.replace(/\./g, '')), 'po_period_from' : po_period_from, 'po_period_to' : po_period_to, 'vendor_name' : vendor_name, 'bank_name' : bank_name, 'account_name' : account_name, 'account_number' : account_number, 'detaiL_po' : detail_po });
			}
			else{
				data_empty.push(j);
			}
		});

		if (data_empty.length > 0)
		{
			emptyList = data_empty.join(', ');
			customNotif('Warning', "Masih ada data kosong pada line nomor: " +emptyList, 'warning');
		}
		else if(po_type == "Additional" && po_reference == 0){
			customNotif('Warning', "Mohon isi PO Reference", 'warning');
		}
		else{

		    $.ajax({
		        url   : baseURL + 'purchase/save_po',
		        type  : "POST",
		        data  : {pr_header_id: pr_header_id, po_amount: total_amount, po_reference: po_reference, data_po : data_po},
		        dataType: "json",
		        beforeSend  : function(){
                          customLoading('show');
                        },
		        success : function(result){
		        	if(result.status == true){
		        		customNotif('Success', "PO CREATED", 'success');
		        		setTimeout(function(){
		        			customLoading('hide');
		        			$(location).attr('href', baseURL + 'budget/purchase-order');
		        		}, 500);
		        	}
		        	else{
		        		customLoading('hide');
		        		customNotif('Error', result.messages, 'error');
		        	}
		        }
		    });
		}
	});

	$('#table_data tbody').on( 'click', 'tr', function () {
		if (! $(this).hasClass('selected') ) {
			table.$('tr.selected').removeClass('selected');
			$(this).addClass('selected');
			let get_table = table.row( this );
			let data      = get_table.data();
			let index     = get_table.index();
			num = index+1;

			$('#table_detail_title').html('PO Detail of line '+ num);

			pr_lines_id = data.pr_lines_id;
	    	table_detail.draw();
		}
	});


	function storeDataDetail(){

		row_now = $('#table_data tbody tr.selected');
		let get_table      = table.row( row_now );
		let index          = get_table.index()
		let index_Num      = index+1;
		let data           = get_table.data();

		po_line_key = data.po_line_key;

		let total_detail = table_detail.data().count();
		let detail_data  = [];

		for (var i = 1; i <= total_detail; i++) {
			x = i-1;

			data                  = table_detail.row( x ).data();
			pr_detail_id          = data.pr_detail_id;
			po_desc_val           = $("#po_desc-"+i).val();
			nominal_detail_po_val = $("#nominal_detail_po-"+i).val();

			detail_data.push({'pr_detail_id' : pr_detail_id, 'po_desc' : po_desc_val, 'nominal' : parseInt(nominal_detail_po_val.replace(/\./g, ''))});
	    }

	    po_line_data[po_line_key] = detail_data;

	    console.log(po_line_data);

	}

  });
</script>