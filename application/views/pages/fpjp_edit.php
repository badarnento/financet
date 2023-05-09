<div class="row">
  <div class="white-box boxshadow">
	<form class="form-horizontal">
    <div class="row">
		<div class="col-sm-6">
          <div class="form-group m-b-10">
            <label for="fpjp_number" class="col-sm-3 control-label text-left">FPJP Number <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-6">
              <input type="text" class="form-control" id="fpjp_number" placeholder="FPJP Number" disabled value="<?= $fpjp_number ?>">
            </div>
          </div>
          <div class="form-group m-b-10">
            <label for="fpjp_name" class="col-sm-3 control-label text-left">FPJP Name <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-6">
              <input type="text" class="form-control" id="fpjp_name" placeholder="FPJP Name" value="<?= $fpjp_name ?>">
            </div>
          </div>
          <div class="form-group m-b-10">
            <label for="type" class="col-sm-3 control-label text-left">FPJP Type <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-6">
		          <select class="form-control" id="type">
		            <option value="0">-- Choose --</option>
					<?php foreach ($type as $key => $value):?>
					<option value="<?= $value['ID_MASTER_FPJP'] ?>" data-name="<?= $value['FPJP_NAME'] ?>"<?=($value['ID_MASTER_FPJP'] == $fpjp_type) ? ' selected':'' ?>><?= $value['FPJP_NAME'] ?></option>
		            <?php endforeach; ?>
		          </select>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label for="currency" class="col-sm-3 control-label text-left">Currency <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-6">
				<select name="currency" id="currency" class="form-control" readonly>
					<?php $get_currency = get_currency(); ?>
					<?php foreach ($get_currency as $key => $value):?>
					<option value="<?= strtoupper($key) ?>"<?=($key == strtolower($fpjp_currency)) ? ' selected':'' ?>><?= $value ?></option>
					<?php endforeach; ?>
				</select>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label for="amount" class="col-sm-3 control-label text-left">Total Amount <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-6">
              <input type="TEXT" class="form-control" id="amount" readonly value="<?= $fpjp_amount ?>">
            </div>
          </div>
          <div class="form-group m-b-10">
		        <label for="directorat" class="col-sm-3 control-label text-left">Directorate <span class="pull-right">:</span></label>
		        <div class="col-sm-9 col-md-6">
		        	<input type="text" class="form-control" id="directorat" data-id="<?= $fpjp_directorat ?>" readonly value="<?= get_directorat($fpjp_directorat) ?>">
		        </div>
		    </div>
		    <div class="form-group m-b-10">
		        <label for="division" class="col-sm-3 control-label text-left">Division <span class="pull-right">:</span></label>
		        <div class="col-sm-9 col-md-6">
		        	<input type="text" class="form-control" id="division" data-id="<?= $fpjp_division ?>" readonly value="<?= get_division($fpjp_division) ?>">
		        </div>
		    </div>
		    <div class="form-group m-b-10">
		        <label for="unit" class="col-sm-3 control-label text-left">Unit <span class="pull-right">:</span></label>
		        <div class="col-sm-9 col-md-6">
		        	<input type="text" class="form-control" id="unit" readonly value="<?= get_unit($fpjp_unit) ?>">
		        </div>
		    </div>
      </div>
      <div class="col-sm-6">
          <div class="form-group m-b-10">
          	<label for="fpjp_date" class="col-sm-3 control-label text-left">FPJP Date <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-6">
				<div class="input-group">
					<input type="text" class="form-control mydatepicker" id="fpjp_date" placeholder="dd/mm/yyyy" value="<?= $fpjp_date ?>">
					<span class="input-group-addon"><i class="icon-calender"></i></span>
				</div>
            </div>
          </div>

          <div class="form-group m-b-10">
            <label for="status" class="col-sm-3 control-label text-left">Status <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-6">
				<select name="status" id="status" class="form-control">
					<option value="Approved"<?= (strtolower($fpjp_status) == "approved") ? ' selected' : '' ?>>Approved</option>
					<option value="Canceled"<?= (strtolower($fpjp_status) == "canceled") ? ' selected' : '' ?>>Canceled</option>
				</select>
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
			<div class="col-md-12">
		      <table id="table_data" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small w-full">
		        <thead>
		        	<tr>
			          	<th class="text-center">No</th>
			          	<th class="text-center">Tribe/Usecase</th>
			          	<th class="text-center">RKAP Name (Description)</th>
			          	<th class="text-center">FS</th>
			          	<th class="text-center">Name</th>
			          	<th class="text-center">Fund Available</th>
			          	<th class="text-center">Nominal</th>
			          	<th class="text-center">Nama Pemilik Rekening</th>
			          	<th class="text-center">Nama Bank</th>
			          	<th class="text-center">Nomor Rekening</th>
			        </tr>
		        </thead>
		      </table>
		    </div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<hr>
				<h4 id="table_detail_title">Detail of line 1</h4>
			</div>
			<div class="col-md-12">
		      <table id="table_detail" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small w-full">
		        <thead>
		        	<tr>
			          	<th class="text-center">No</th>
			          	<th class="text-center">Description</th>
			          	<th class="text-center">Nature</th>
			          	<th class="text-center">Quantity</th>
			          	<th class="text-center">Price</th>
			          	<th class="text-center">Nominal</th>
			        </tr>
		        </thead>
		      </table>
		    </div>
		</div>
  </div>
</div>

<div class="row">
  <div class="white-box">
      <form class="form-horizontal">
        <div class="row">
	        <div class="form-group m-b-10">
	        	<label for="submitter" class="col-sm-2 control-label text-left">Submitter <span class="pull-right">:</span></label>
	        	<div class="col-sm-3">
	                <select class="form-control" id="submitter">
	                  <option value="">-- Pilih --</option>
	                </select>
	            </div>
	            <div class="col-sm-3">
	            	<input type="text" class="form-control" id="jabatan_sub" readonly>
	            </div>
	        </div>
        </div>
        <div class="row">
	        <div class="form-group m-b-10">
	        	<label for="diketahui_1" class="col-sm-2 control-label text-left">Diketahui/Disetujui <span class="pull-right">:</span></label>
	        	<div class="col-sm-3">
	                <select class="form-control" id="diketahui_1">
	                  <option value="">-- Pilih --</option>
	                </select>
	            </div>
	            <div class="col-sm-3">
	            	<input type="text" class="form-control" id="jabatan_dik_1" readonly>
	            </div>
	        </div>
        </div>
        <div class="row">
	        <div class="form-group m-b-10">
	        	<label for="diketahui_2" class="col-sm-2 control-label text-left">Diketahui/Disetujui <span class="pull-right">:</span></label>
	        	<div class="col-sm-3">
	                <select class="form-control" id="diketahui_2">
	                  <option value="">-- Pilih --</option>
	                </select>
	            </div>
	            <div class="col-sm-3">
	            	<input type="text" class="form-control" id="jabatan_dik_2" readonly>
	            </div>
	        </div>
        </div>
        <div class="form-group m-b-0">
        	<div class="pull-right"><button type="button" id="update_data" class="btn btn-info m-10 w-100p"><i class="fa fa-save"></i> Update </button>
        	</div>
        </div>
      </form>
  </div>
</div>

<script>
  $(document).ready(function(){

	const fpjp_header_id = '<?= $fpjp_header_id ?>';
	const opt_default  = '<option value="0" data-name="">-- Choose --</option>';
	let counter        = 1;
	let fpjp_line_data   = {};

	let buttonAdd2 = '<button type="button" id="addRow-detail" class="btn btn-success btn-sm m-l-5 mr-5"><i class="fa fa-plus"></i> Add New </button>';
		buttonAdd2 += '<button type="button" id="deleteRow-detail" class="btn btn-danger btn-sm m-l-5 mr-5" disabled><i class="fa fa-trash"></i> Delete </button>';

	const directorat  = $("#directorat").data('id');
	const division    = $("#division").data('id');
	const submitter   = '<?= $fpjp_submit ?>';
	const diketahui_1 = '<?= $fpjp_dik1 ?>';
	const diketahui_2 = '<?= $fpjp_dik2 ?>';

	getSubmitter(1);
	getDiketahui_1(2);
	getDiketahui_2(3);

	let url            = baseURL + 'fpjp/load_data_lines';

	Pace.track(function(){
	    $('#table_data').DataTable({
	      "serverSide"      : true,
	      "processing"      : true,
	      "ajax"            : {
									"url"  : url,
									"type" : "POST",
									"dataType": "json",
									"data"    : function ( d ) {
													d.fpjp_header_id = fpjp_header_id;
													// d.category     = 'edit';
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
				    			{"data": "tribe", "width": "150px" },
				    			{"data": "rkap_name", "width": "300px" },
				    			{"data": "fs_name", "width": "150px" },
				    			{"data": "fpjp_line_name", "width": "200px" },
				    			{"data": "fund_available", "width": "150px", "class": "text-right"  },
				    			{"data": "nominal_edit", "width": "150px", "class": "text-right"  },
								{"data": "pemilik_rekening_edit", "width": "150px"},
								{"data": "nama_bank_edit", "width": "150px"},
								{"data": "no_rekening_edit", "width": "150px"}
				    		],
	    "drawCallback": function ( settings ) {
	    	$(".select2").select2();
	    },
	      "pageLength"      : 10,
	      "ordering"        : false,
	      "scrollY"         : 480,
	      "scrollX"         : true,
	      "scrollCollapse"  : true,
	      "autoWidth"       : true,
	      "bAutoWidth"      : true
	    });
	});

	$('input[type="search"]').attr('placeholder','Search here...').addClass('form-control input-sm m-l-0');

	let table   = $('#table_data').DataTable();
	let tracker = 0;

	$('#table_data').on( 'draw.dt', function () {
		if(tracker == 0){
			getDataDetail();
		}
		tracker++;
	});

	let rkap_desc      = '<div class="form-group m-b-0"><input id="rkap_desc-'+counter+'" class="form-control input-sm rkap_desc"></div>';
	let nature_opt     = '<div class="form-group m-b-0"><select id="nature_opt-'+counter+'" class="form-control input-sm nature_opt select-center">';
			nature_opt += opt_default;
			nature_opt += '</select></div>';
	let quantity       = '<div class="form-group m-b-0"><input id="quantity-'+counter+'" data-id="'+counter+'" class="form-control input-sm quantity text-center" value="1" min="1" max="99999" type="number"></div>';
	let price          = '<div class="form-group m-b-0"><input id="price-'+counter+'" data-id="'+counter+'" class="form-control input-sm price text-right money-format" value="0"></div>';
	let nominal_detail = '<div class="form-group m-b-0"><input id="nominal_detail-'+counter+'" class="form-control input-sm nominal_detail text-right" value="0" readonly></div>';

	let table_detail = $('#table_detail').DataTable({
	    "data":[{
					"no": counter,
					"rkap_desc": rkap_desc,
					"nature": nature_opt,
					"quantity": quantity,
					"price": price,
					"nominal_detail": nominal_detail
	    		}],
	    "columns":[
	    			{"data": "no", "width": "10px", "class": "text-center" },
	    			{"data": "rkap_desc", "width": "250px", "class": "p-2" },
	    			{"data": "nature", "width": "300px", "class": "p-2" },
	    			{"data": "quantity", "width": "100px", "class": "p-2 text-center" },
	    			{"data": "price", "width": "150px", "class": "p-2" },
	    			{"data": "nominal_detail", "width": "200px", "class": "p-2" }
	    		],
		"ordering"        : false,
		"scrollY"         : 480,
		"scrollX"         : true,
		"scrollCollapse"  : true,
		"paging" 		  : false
	});

	$('#table_detail_filter').html(buttonAdd2);

	$('#table_data tbody').on( 'click', 'tr', function () {
			table.$('tr.selected').removeClass('selected');
			$(this).addClass('selected');
			let get_table      = table.row( this );
			let index          = get_table.index()
			let index_Num      = index+1;
			let data           = get_table.data();

			total_data = table.data().count();

			$("#deleteRow-detail").attr('disabled', true);
			if(total_data > 1){
				$("#deleteRow").attr('disabled', false);
			}

			let fpjp_line_key_val = data.fpjp_line_key;
			let rkapline_val    = data.id_rkap_line;
			
			data_detail = fpjp_line_data[fpjp_line_key_val];

			let newDataDtl = [];

			$("#table_detail_title").html("Detail of line " + index_Num);

			data_detail.forEach(function(value, i) {

			    j=i+1;
				
				let rkap_desc_val  = '<div class="form-group m-b-0"><input id="rkap_desc-'+ j +'" class="form-control input-sm rkap_desc" value="'+value.rkap_desc+'"></div>';
				let nature_opt     = '<div class="form-group m-b-0"><select id="nature_opt-'+ j +'" class="form-control input-sm nature_opt select-center">';
						nature_opt += opt_default;
						nature_opt += '</select></div>';
				let quantity       = '<div class="form-group m-b-0"><input id="quantity-'+j+'" data-id="'+j+'" class="form-control input-sm quantity text-center" value="'+value.quantity+'" min="1" max="99999" type="number"></div>';
				let price          = '<div class="form-group m-b-0"><input id="price-'+j+'" data-id="'+j+'" class="form-control input-sm price text-right money-format" value="'+formatNumber(value.price)+'"></div>';
				let nominal_detail = '<div class="form-group m-b-0"><input id="nominal_detail-'+j+'" class="form-control input-sm nominal_detail text-right" value="'+formatNumber(value.nominal_detail)+'" readonly></div>';

				newDataDtl.push({
								"no": j,
								"rkap_desc": rkap_desc_val,
								"nature": nature_opt,
								"quantity": quantity,
								"price": price,
								"nominal_detail": nominal_detail
				    		});
				getNature(j, rkapline_val, value.nature);
			});

			table_detail.rows().remove().draw();
			table_detail.rows.add(newDataDtl).draw();

			$("#table_detail_title").html("Detail of line " + index_Num);

	});


	$('#addRow-detail').on( 'click', function () {
		indexNow_detail = table_detail.data().count();

		if($("#rkap_desc-"+indexNow_detail).val() == 0 || $("#nature_opt-"+indexNow_detail).val() == 0 || $("#nominal_detail-"+indexNow_detail).val() == 0){
			customNotif('Warning', 'Please fill out all field!', 'warning');
		}
		else{

			numDetail = indexNow_detail+1;

			let rkap_desc      = '<div class="form-group m-b-0"><input id="rkap_desc-'+numDetail+'" class="form-control input-sm rkap_desc"></div>';
			let nature_opt     = '<div class="form-group m-b-0"><select id="nature_opt-'+numDetail+'" class="form-control input-sm nature_opt select-center">';
					nature_opt += opt_default;
					nature_opt += '</select></div>';
			let quantity       = '<div class="form-group m-b-0"><input id="quantity-'+numDetail+'" data-id="'+numDetail+'" class="form-control input-sm quantity text-center" value="1" min="1" max="99999" type="number"></div>';
			let price          = '<div class="form-group m-b-0"><input id="price-'+numDetail+'" data-id="'+numDetail+'" class="form-control input-sm price text-right money-format" value="0"></div>';
			let nominal_detail = '<div class="form-group m-b-0"><input id="nominal_detail-'+numDetail+'" class="form-control input-sm nominal_detail text-right" value="0" readonly></div>';

		    table_detail.rows.add(
		       [{ 
		       			"no": numDetail,
		    			"rkap_desc": rkap_desc,
		    			"nature": nature_opt,
		    			"quantity": quantity,
		    			"price": price,
		    			"nominal_detail": nominal_detail
	    		}]
		    ).draw();

			let row_now      = $('#table_data tbody tr.selected');
			let get_table    = table.row( row_now );
			let data         = get_table.data();
			let rkapline_val = data.id_rkap_line;

			getNature(numDetail, rkapline_val);

		}
	});



	$('#table_detail tbody').on('input change blur', 'tr td input.quantity, tr td input.price', function () {
		if($(this).val().trim().length === 0){
			$(this).val(0);
		}

		data_id      = $(this).data('id');
		
		quantity_val = $("#quantity-"+data_id).val();
		price_val    = $("#price-"+data_id).val();

		qty_int = parseInt(quantity_val);
		price_int = parseInt(price_val.replace(/\./g, ''));
		nominal_val = 0;

		if(qty_int > 0 && price_int > 0){
			nominal_val = qty_int * price_int;
		}

		$("#nominal_detail-"+data_id).val(formatNumber(nominal_val));

		setTimeout(function(){
			getNominal();
			getAmount();
		}, 300);

    });

	$('#table_detail tbody').on('change', 'tr td input, tr td select', function () {
		storeDataDetail();
    });



	$('#table_detail tbody').on( 'click', 'tr', function () {
		if (! $(this).hasClass('selected') ) {
			table_detail.$('tr.selected').removeClass('selected');

			total_data = table_detail.data().count();

			if(total_data > 1){
				$("#deleteRow-detail").attr('disabled', false);
			}
			$(this).addClass('selected');
		}
	});




	$('#deleteRow-detail').on( 'click', function () {

		row_now    = $('#table_detail tbody tr.selected');
		get_table  = table_detail.row( row_now );
		index      = get_table.index()

		total_data  = table_detail.data().count();

		if(total_data > 0){
			table_detail.row(index).remove().draw();

			table_detail.column( 0 ).data().each( function ( value, i ) {
				num = i+1;

				$('#rkap_desc-'+value).attr("id", 'rkap_desc-'+num);
				$('#nature_opt-'+value).attr("id", 'nature_opt-'+num);
				$('#quantity-'+value).attr("id", 'quantity-'+num);
				$('#price-'+value).attr("id", 'price-'+num);
				$('#quantity-'+value).data('id', num);
				$('#price-'+value).data('id', num);
				$('#nominal_detail-'+value).attr("id", 'nominal_detail-'+num);

				$('#table_detail tbody tr:eq(' + i + ') td:eq(0)').html(num);
			});

			$(this).attr('disabled', true);

			getNominal();
			getAmount();
			storeDataDetail();
		}

    });


    $("#save_data").on('click', function () {

		getAmount();
		
		let pr_name      = $("#pr_name").val();
		let currency     = $("#currency").val();
		let status       = $("#status").val();
		let amount       = parseInt($("#amount").val().replace(/\./g, ''));
		let total_data   = table.data().count();
		let fatalNominal = false;

	    /*for (var i = 1; i <= total_data; i++) {
			fund_av       = $("#fund_av-"+i).val();
			nominal_val   = $("#nominal-"+i).val();

			if(parseInt(nominal_val.replace(/\./g, '')) > parseInt(fund_av.replace(/\./g, ''))){
				fatalNominal =  true;
				customNotif('Warning', 'Nilai nominal lebih dari Fund Available pada data ke '+i+'!', 'warning');
			}
	    }*/

		if(pr_name == "" || amount == "0"){
    		customNotif('Warning', "Please fill all field", 'warning');
		}
		else if (fatalNominal == true)
		{
			return false;
		}
		else{

			table.rows().eq(0).each( function ( index ) {
		    	j = index+1;
			    data = table.row( index ).data();

				fund_av         = $("#fund_av-"+j).val();
				nominal_val     = $("#nominal-"+j).val();
				
				fpjp_lines_id     = data.fpjp_lines_id;
				fpjp_line_key_val = data.fpjp_line_key;
				data_detail     = fpjp_line_data[fpjp_line_key_val];

				if(parseInt(fund_av.replace(/\./g, '')) > 0 && parseInt(nominal_val.replace(/\./g, '')) > 0){
		    		data_lines.push({'fpjp_lines_id' : fpjp_lines_id, 'nominal' : parseInt(nominal_val.replace(/\./g, '')), 'detail_data' : data_detail});
				}
			});

		    data = {
						fpjp_header_id : fpjp_header_id,
						pr_name : pr_name,
						status : status,
						amount : amount,
						currency : currency,
						data_line : data_lines
		    		};


		    $.ajax({
		        url   : baseURL + 'purchase/save_pr_edit',
		        type  : "POST",
		        data  : data,
		        dataType: "json",
		        beforeSend  : function(){
                          customLoading('show');
                        },
		        success : function(result){
		        	console.log(result);
		        	if(result.status == true){
		        		customNotif('Success', "PR UPDATED", 'success');
		        		setTimeout(function(){
		        			customLoading('hide');
		        			$(location).attr('href', baseURL + 'budget/purchase-requisition');
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


	$("#update_data").on('click', function () {

		getAmount();
		
		let fpjp_name    = $("#fpjp_name").val();
		let fpjp_type 	 = $("#type").val();
		let fpjp_date 	 = $("#fpjp_date").val();
		let amount       = parseInt($("#amount").val().replace(/\./g, ''));
		let status       = $("#status").val();
		let currency     = $("#currency").val();
		let submitter      = $("#submitter").val();
		let diketahui_1    = $("#diketahui_1").val();
		let diketahui_2    = $("#diketahui_2").val();
		let jabatan_sub    = $("#jabatan_sub").val();
		let jabatan_dik_1  = $("#jabatan_dik_1").val();
		let jabatan_dik_2  = $("#jabatan_dik_2").val();

		if(fpjp_name == "" || amount == "0"){
    		customNotif('Warning', "Please fill all field", 'warning');
		}
		else{

			let data_lines  = [];

			table.rows().eq(0).each( function ( index ) {
		    	j = index+1;
			    data = table.row( index ).data();
				nominal_val      = $("#nominal-"+j).val();
				pemilik_rekening = $("#pemilik_rekening-"+j).val();
				nama_bank        = $("#nama_bank-"+j).val();
				no_rekening      = $("#no_rekening-"+j).val();

				nominal_val     = $("#nominal-"+j).val();
				
				fpjp_lines_id     = data.fpjp_lines_id;
				fpjp_line_key_val = data.fpjp_line_key;
				data_detail     = fpjp_line_data[fpjp_line_key_val];

				if(parseInt(nominal_val.replace(/\./g, '')) > 0){
		    		data_lines.push({'fpjp_lines_id' : fpjp_lines_id, 'nominal' : parseInt(nominal_val.replace(/\./g, '')), 'pemilik_rekening' : pemilik_rekening, 'nama_bank' : nama_bank, 'no_rekening' : no_rekening, 'detail_data' : data_detail});
				}
			});

		    data = {
						fpjp_header_id : fpjp_header_id,
						fpjp_name : fpjp_name,
						fpjp_type : fpjp_type,
						fpjp_date : fpjp_date,
						amount : amount,
						data_line : data_lines,
						status : status,
						currency : currency,
						submitter : submitter,
						diketahui_1 : diketahui_1,
						diketahui_2 : diketahui_2,
						jabatan_sub : jabatan_sub,
						jabatan_dik_1 : jabatan_dik_1,
						jabatan_dik_2 : jabatan_dik_2
		    		};

		    $.ajax({
		        url   : baseURL + 'fpjp/update_fpjp',
		        type  : "POST",
		        data  : data,
		        dataType: "json",
		        beforeSend  : function(){
                          customLoading('show');
                        },
		        success : function(result){
		        	console.log(result);
		        	if(result.status == true){
		        		customNotif('Success', "FPJP UPDATED", 'success');
		        		setTimeout(function(){
		        			customLoading('hide');
		        			$(location).attr('href', baseURL + 'fpjp/fpjp');
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

    function getNominal(){
		let total_detail = table_detail.data().count();
		let totalNominal = 0;

		for (var i = 1; i <= total_detail; i++) {
			nominal_detail_val = $("#nominal_detail-"+i).val();
			totalNominal += parseInt(nominal_detail_val.replace(/\./g, ''));
	    }

	    $("#table_data tbody tr.selected").find("input.nominal").val(formatNumber(totalNominal));
    }

    function getAmount() {
		let total_data  = table.data().count();
		let totalAmount = 0;

		for (var i = 1; i <= total_data; i++) {
			get_nominal = $("#nominal-"+i).val();
			totalAmount += parseInt(get_nominal.replace(/\./g, ''));
	    }
		$("#amount").val(formatNumber(totalAmount));
	}

	function getNature(id_row, id_rkap, id_select=0) {

		$("#nature_opt-"+id_row).attr('disabled', true);
		$("#nature_opt-"+id_row).css('cursor', 'wait');

	    $.ajax({
	        url   : baseURL + 'purchase/load_data_nature',
	        type  : "POST",
	        data  : {id_rkap : id_rkap},
	        dataType: "json",
	        success : function(result){
				let nature_opt = opt_default;
	        	if(result.status == true){
					data = result.data;
		        	for(var i = 0; i < data.length; i++) {
						obj = data[i];
						let selected   = '';
					    if(id_select == obj.id_coa){
					    	selected = ' selected';
					    }
					    nature_opt += '<option value="'+ obj.id_coa +'"'+selected+'>'+ obj.nature_desc +'</option>';
					}
	        	}
				$("#nature_opt-"+id_row).html(nature_opt);
				$("#nature_opt-"+id_row).attr('disabled', false);
				$("#nature_opt-"+id_row).css('cursor', 'default');
	        }
	    });
	}


	function getDataDetail(){


		table.rows().eq(0).each( function ( index ) {
			let row         = table.row( index );
			let data        = row.data();
			let fpjp_lines_id = data.fpjp_lines_id;
			let fpjp_line_key = data.fpjp_line_key;

		    $.ajax({
	        	url   : baseURL + 'fpjp/load_fpjp_detail_for_edit',
		        type  : "POST",
		        data  : {fpjp_lines_id : fpjp_lines_id},
		        dataType: "json",
		        success : function(result){
					let newData = [];
					console.log(result)
					result.forEach(function(value, i) {
							newData.push({
								"no": value.no,
								"rkap_desc": value.rkap_desc,
								"nature": value.nature,
								"quantity": value.quantity,
								"price": value.price,
								"nominal_detail": value.nominal
						});
					});

					fpjp_line_data[fpjp_line_key] = newData;
		        }
		    });

		});

		setTimeout(function(){
			$('#table_data tbody tr').eq(0).trigger('click');
		}, 1000);
	}

	function storeDataDetail(){

		row_now = $('#table_data tbody tr.selected');
		let get_table      = table.row( row_now );
		let index          = get_table.index()
		let index_Num      = index+1;
		let data           = get_table.data();

		fpjp_line_key = data.fpjp_line_key;

		let total_detail = table_detail.data().count();
		let detail_data  = [];

		for (var i = 1; i <= total_detail; i++) {
			x=i-1;
			data         = table_detail.row( x ).data();
			fpjp_detail_id = data.fpjp_detail_id;

			rkap_desc_val      = $("#rkap_desc-"+i).val();
			nature_val         = $("#nature_opt-"+i).val();
			quantity_val       = $("#quantity-"+i).val();
			price_val          = $("#price-"+i).val();
			nominal_detail_val = $("#nominal_detail-"+i).val();

			if(rkap_desc_val != "" && nature_val != 0 && parseInt(price_val.replace(/\./g, '')) > 0){
    			detail_data.push({'rkap_desc' : rkap_desc_val, 'nature' : nature_val, 'quantity' : quantity_val, 'price' : parseInt(price_val.replace(/\./g, '')), 'nominal_detail' : parseInt(nominal_detail_val.replace(/\./g, ''))});
			}
	    }

	    fpjp_line_data[fpjp_line_key] = detail_data;

	}




  	$('#fpjp_date').datepicker({
		format: 'dd/mm/yyyy',
		todayHighlight:'TRUE',
		autoclose: true,
    });

	$("#submitter").on("change", function(){
		getJabatan("submitter");
    });

    $("#diketahui_1").on("change", function(){
    	 getJabatan("diketahui_1");
    });

    $("#diketahui_2").on("change", function(){
    	getJabatan("diketahui_2");
    });

    function getSubmitter(jabatan_code){

	    $.ajax({
	        url   : baseURL + 'api-budget/load_data_submitter',
	        type  : "POST",
	        data  : {directorat:directorat, division:division, jabatan_code:jabatan_code},
	        dataType: "json",
	        success : function(result){      
				let submitter_opt = opt_default;
	        	if(result.status == true){
					let data     = result.data;
		        	for(var i = 0; i < data.length; i++) {
					    obj = data[i];
					    selected = (submitter === obj.nama) ? ' selected': '';
					    submitter_opt += '<option value="'+ obj.nama +'" data-jabatan="'+ obj.jabatan +'" '+ selected +'>'+ obj.nama +'</option>';
					}
	        	}
				$("#submitter").html(submitter_opt);				
				getJabatan("submitter");
	        }
	    });
	}

    function getDiketahui_1(jabatan_code){

	    $.ajax({
	        url   : baseURL + 'api-budget/load_data_submitter',
	        type  : "POST",
	        data  : {directorat:directorat, jabatan_code:jabatan_code},
	        dataType: "json",
	        success : function(result){      
        		let diketahui_1_opt = opt_default;
	        	if(result.status == true){
					data = result.data;
		        	for(var i = 0; i < data.length; i++) {
					    obj = data[i];
					    selected = (diketahui_1 === obj.nama) ? ' selected': '';
					    diketahui_1_opt += '<option value="'+ obj.nama +'" data-jabatan="'+ obj.jabatan +'" '+ selected +'>'+ obj.nama +'</option>';
					}
	        	}
				$("#diketahui_1").html(diketahui_1_opt);				
				getJabatan("diketahui_1");
	        }
	    });
	}

    function getDiketahui_2(jabatan_code){

	    $.ajax({
	        url   : baseURL + 'api-budget/load_data_submitter',
	        type  : "POST",
	        data  : {directorat:directorat, jabatan_code:jabatan_code},
	        dataType: "json",
	        success : function(result){      
        		let diketahui_2_opt = opt_default;
	        	if(result.status == true){
					data = result.data;
		        	for(var i = 0; i < data.length; i++) {
					    obj = data[i];
					    selected = (diketahui_2 === obj.nama) ? ' selected': '';
					    diketahui_2_opt += '<option value="'+ obj.nama +'" data-jabatan="'+ obj.jabatan +'" '+ selected +'>'+ obj.nama +'</option>';
					}
	        	}
				$("#diketahui_2").html(diketahui_2_opt);				
				getJabatan("diketahui_2");
	        }
	    });
	}

    function getJabatan(category){
		if(category == "submitter"){
			valJabtan = $("#submitter").find(':selected').attr('data-jabatan');
			$("#jabatan_sub").val(valJabtan);
		}
		else if(category == "diketahui_1"){
			valJabtan = $("#diketahui_1").find(':selected').attr('data-jabatan');
			$("#jabatan_dik_1").val(valJabtan);
		}
		else{
			valJabtan = $("#diketahui_2").find(':selected').attr('data-jabatan');
			$("#jabatan_dik_2").val(valJabtan);
		}
	}

    
  });
</script>