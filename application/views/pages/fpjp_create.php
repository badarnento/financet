<div class="row">
  <div class="white-box boxshadow">
	<form class="form-horizontal">
    <div class="row">
    	<div class="col-sm-6">
    		<div class="form-group m-b-10">
	            <label for="type" class="col-sm-3 control-label text-left">FPJP Type <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	            	<select class="form-control" id="type">
			            <option value="0">-- Choose --</option>
			            <?php foreach($type as $value): ?>
			            <option value="<?= $value['ID_MASTER_FPJP'] ?>" data-name="<?= $value['FPJP_NAME'] ?>"><?= $value['FPJP_NAME'] ?></option>
			            <?php endforeach; ?>
			        </select>
	            </div>
	        </div>
	        <div class="form-group m-b-10">
	            <label for="fpjp_name" class="col-sm-3 control-label text-left">FPJP Name <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	              <input type="text" class="form-control" id="fpjp_name" placeholder="FPJP Name" autocomplete="off">
	            </div>
	        </div>
	        <div class="form-group m-b-10">
	            <label for="currency" class="col-sm-3 control-label text-left">Currency <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	            	<?php
		            	$currency = get_currency();
	            	?>
					<select name="currency" id="currency" class="form-control">
						<?php foreach ($currency as $key => $value) : ?>
							<option value="<?= $key ?>"><?= $value ?></option>
						<?php endforeach; ?>
					</select>
	            </div>
	        </div>
	        <div class="form-group m-b-10">
	            <label for="amount" class="col-sm-3 control-label text-left">Total Amount <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	              <input type="text" class="form-control" id="amount" value="0" readonly>
	            </div>
	        </div>
	        <div class="form-group m-b-10">
	            <label for="directorat" class="col-sm-3 control-label text-left">Directorat <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
			          <select class="form-control" id="directorat">
			            <option value="0">-- Choose --</option>
			            <?php foreach($directorat as $value): ?>
			            <option value="<?= $value['ID_DIR_CODE'] ?>" data-name="<?= $value['DIRECTORAT_NAME'] ?>"><?= $value['DIRECTORAT_NAME'] ?></option>
			            <?php endforeach; ?>
			          </select>
	            </div>
	        </div>
	        <div class="form-group m-b-10">
	            <label for="division" class="col-sm-3 control-label text-left">Division <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	            	<select class="form-control" id="division">
	            		<option value="0">-- Choose --</option>
		            </select>
	            </div>
	        </div>
	        <div class="form-group m-b-10">
	            <label for="unit" class="col-sm-3 control-label text-left">Unit <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	            	<select class="form-control" id="unit">
	            		<option value="0">-- Choose --</option>
		            </select>
	            </div>
	        </div>
    	</div>
    	<div class="col-sm-6">
    		<div class="form-group m-b-10">
	          	<label for="fpjp_date" class="col-sm-3 control-label text-left">FPJP Date <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
					<div class="input-group">
						<input type="text" class="form-control mydatepicker" id="fpjp_date" placeholder="dd/mm/yyyy" value="<?= date("d/m/Y")?>">
						<span class="input-group-addon"><i class="icon-calender"></i></span>
					</div>
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
			          	<th class="text-center">Program ID</th>
			          	<th class="text-center">FS</th>
			          	<th class="text-center">Fund Available</th>
			          	<th class="text-center">FPJP Line Name</th>
			          	<th class="text-center">Nominal</th>
			          	<th class="text-center">Nama Pemilik Rekening</th>
			          	<th class="text-center">Nama Bank</th>
			          	<th class="text-center">Nomor Rekening</th>
			        </tr>
		        </thead>
		      </table>
		    </div>
		</div>
  </div>
</div>
<div class="row">
	<div class="white-box">
		<div class="row">
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

<div class="row" id="form-data">
  <div class="white-box">
      <form class="form-horizontal">
        <div class="row">
            <label for="" class="col-sm-2 control-label text-left">Submitter <span class="pull-right">:</span></label>
              <div class="col-sm-3">
                <select class="form-control" id="submitter">
                  <option value="">-- Pilih --</option>
                </select>
              </div>
              <div class="col-sm-3">
                  <input type="text" class="form-control" id="jabatan_sub" placeholder="Jabatan" readonly>
              </div>
          </div>
          &nbsp;
          <div class="row">
            <label for="" class="col-sm-2 control-label text-left">Diketahui/Disetujui <span class="pull-right">:</span></label>
              <div class="col-sm-3">
                <select class="form-control" id="diketahui_1">
                  <option value="0">--Pilih--</option>
                </select>
              </div>
              <div class="col-sm-3">
                  <input type="text" class="form-control" id="jabatan_dik_1" placeholder="Jabatan" readonly>
              </div>
          </div>
          &nbsp;
          <div class="row">
            <label for="" class="col-sm-2 control-label text-left">Diketahui/Disetujui <span class="pull-right">:</span></label>
              <div class="col-sm-3">
                <select class="form-control" id="diketahui_2">
                  <option value="0">--Pilih--</option>
                </select>
              </div>
              <div class="col-sm-3">
                  <input type="text" class="form-control" id="jabatan_dik_2" placeholder="Jabatan" readonly>
              </div>
          </div>
          <div class="form-group m-b-0">
                  <div class="pull-right"><button type="button" id="save_data" class="btn btn-info m-10 w-100p"><i class="fa fa-save"></i> Save </button>
                  </div>
              </div>
      </form>
  </div>
</div>

<script>
  $(document).ready(function(){


	let counter       = 1;
	const opt_default = '<option value="0" data-name="">-- Choose --</option>';
	let directorat    = $("#directorat").val();
	
	let pr_line_data  = {};

	let buttonAdd = '<button type="button" id="addRow" class="btn btn-success btn-sm m-l-5 mr-5"><i class="fa fa-plus"></i> Add New </button>';
		buttonAdd += '<button type="button" id="deleteRow" class="btn btn-danger btn-sm m-l-5 mr-5" disabled><i class="fa fa-trash"></i> Delete </button>';
	let buttonAdd2 = '<button type="button" id="addRow-detail" class="btn btn-success btn-sm m-l-5 mr-5"><i class="fa fa-plus"></i> Add New </button>';
		buttonAdd2 += '<button type="button" id="deleteRow-detail" class="btn btn-danger btn-sm m-l-5 mr-5" disabled><i class="fa fa-trash"></i> Delete </button>';
	let tribe_opt = '<div class="form-group m-b-0"><select id="tribe_opt-'+counter+'" data-id="' + counter + '" class="form-control input-sm tribe_opt select-center">';
		tribe_opt     += opt_default;
		tribe_opt     += '/<select></div>';
	let rkap_opt = '<div class="form-group m-b-0"><select id="rkap_opt-'+counter+'" data-id="' + counter + '" class="form-control input-sm rkap_opt select-center">';
		rkap_opt     += opt_default;
		rkap_opt     += '</select></div>';
	let tier_opt = '<div class="form-group m-b-0"><select id="tier_opt-'+counter+'" data-id="' + counter + '" class="form-control input-sm tier_opt select-center">';
		tier_opt     += opt_default;
		tier_opt     += '</select></div>';
	let fs_opt = '<div class="form-group m-b-0"><select id="fs_opt-'+counter+'" data-id="' + counter + '" class="form-control input-sm fs_opt select-center">';
		fs_opt     += opt_default;
		fs_opt     += '</select></div>';
	let line_name = '<div class="form-group m-b-0"><input id="line_name-'+counter+'" data-id="' + counter + '" class="form-control input-sm line_name" autocomplete="off"></div>';	
	let fund_av = '<div class="form-group m-b-0"><input id="fund_av-'+counter+'" data-id="' + counter + '" class="form-control input-sm fund_av text-right" value="0" readonly></div>';
	let nominal = '<div class="form-group m-b-0"><input id="nominal-'+counter+'" data-id="' + counter + '" class="form-control input-sm nominal text-right" value="0" readonly></div>';
	let pemilik_rekening = '<div class="form-group m-b-0"><input id="pemilik_rekening-' + counter + '" data-id="' + counter + '" class="form-control input-sm pemilik_rekening"></div>';
	let nama_bank    = '<div class="form-group m-b-0"><select id="nama_bank_opt-'+counter+'" class="form-control input-sm nama_bank_opt select-center select2">';
		nama_bank += opt_default;
		nama_bank += '</select></div>';
	let no_rekening      = '<div class="form-group m-b-0"><input id="no_rekening-' + counter + '" data-id="' + counter + '" class="form-control input-sm no_rekening"></div>';


	let pr_line_random = generateRandomKey(6);

    let table = $('#table_data').DataTable({
	    "data":[{
	    		 	"pr_line_key": pr_line_random,
	    		 	"no": counter,
	    			"tribe": tribe_opt,
	    			"rkap_name": rkap_opt,
	    			"tier": tier_opt,
	    			"fs": fs_opt,
	    			"fund_available": fund_av,
	    			"line_name": line_name,
	    			"nominal": nominal,
	    			"pemilik_rekening": pemilik_rekening,
	    			"nama_bank": nama_bank,
	    			"no_rekening": no_rekening
	    		}],
	    "columns":[
	    			{"data": "no", "width": "40px", "class": "text-center p-2" },
	    			{"data": "tribe", "width": "150px" },
	    			{"data": "rkap_name", "width": "300px", "class": "p-2" },
	    			{"data": "tier", "width": "200px", "class": "p-2" },
	    			{"data": "fs", "width": "200px", "class": "p-2" },
	    			{"data": "fund_available", "width": "150px", "class": "p-2" },
	    			{"data": "line_name", "width": "200px", "class": "p-2" },
	    			{"data": "nominal", "width": "150px", "class": "p-2" },
	    			{"data": "pemilik_rekening", "width": "150px"},
	    			{"data": "nama_bank", "width": "200px"},
	    			{"data": "no_rekening", "width": "150px"}
	    		],
	    "drawCallback": function ( settings ) {
	    	runSelect2(counter);
	    },
		"ordering"        : false,
		"scrollY"         : 480,
		"scrollX"         : true,
		"scrollCollapse"  : true,
		"paging" 		  : false
	});

	getBank(counter);

	let counter_detail = 1;
	
	let rkap_desc      = '<div class="form-group m-b-0"><input id="rkap_desc-'+counter+'" class="form-control input-sm rkap_desc" autocomplete="off" ></div>';
	let nature_opt     = '<div class="form-group m-b-0"><select id="nature_opt-'+counter+'" class="form-control input-sm nature_opt select-center">';
			nature_opt += opt_default;
			nature_opt += '</select></div>';
	let quantity       = '<div class="form-group m-b-0"><input id="quantity-'+counter+'" data-id="'+counter+'" class="form-control input-sm quantity text-center" value="1" min="1" max="99999" type="number"></div>';
	let price          = '<div class="form-group m-b-0"><input id="price-'+counter+'" data-id="'+counter+'" class="form-control input-sm price text-right money-format" value="0"></div>';
	let nominal_detail = '<div class="form-group m-b-0"><input id="nominal_detail-'+counter+'" class="form-control input-sm nominal_detail text-right" value="0" readonly></div>';

    let table_detail = $('#table_detail').DataTable({
	    "data":[{
	    		 	"no": counter_detail,
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

	$('#table_data tbody tr').eq(counter-1).addClass('selected');
	$('#table_detail tbody tr').eq(counter-1).addClass('selected');
	$('#table_data_filter').html(buttonAdd);
	$('#table_detail_filter').html(buttonAdd2);

	$('#table_data tbody').on( 'click', 'tr', function () {
		if (! $(this).hasClass('selected') ) {
			table.$('tr.selected').removeClass('selected');
			$(this).addClass('selected');
			let get_table = table.row( this );
			let index     = get_table.index()
			let index_Num = index+1;
			let data      = get_table.data();

			total_data = table.data().count();

			$("#deleteRow-detail").attr('disabled', true);
			if(total_data > 1){
				$("#deleteRow").attr('disabled', false);
			}
			
			let pr_line_key_val = data.pr_line_key;
			let rkapline_val   = $("#rkap_opt-"+index_Num).val();

			data_detail = pr_line_data[pr_line_key_val];
			let newData = [];

			$("#table_detail_title").html("Detail of line " + index_Num);

			data_detail.forEach(function(value, i) {

			    j=i+1;
				
				let rkap_desc_val  = '<div class="form-group m-b-0"><input id="rkap_desc-'+ j +'" class="form-control input-sm rkap_desc" value="'+value.rkap_desc+'" autocomplete="off"></div>';
				let nature_opt     = '<div class="form-group m-b-0"><select id="nature_opt-'+ j +'" class="form-control input-sm nature_opt select-center">';
						nature_opt += opt_default;
						nature_opt += '</select></div>';
				let quantity       = '<div class="form-group m-b-0"><input id="quantity-'+j+'" data-id="'+j+'" class="form-control input-sm quantity text-center" value="'+value.quantity+'" min="1" max="99999" type="number"></div>';
				let price          = '<div class="form-group m-b-0"><input id="price-'+j+'" data-id="'+j+'" class="form-control input-sm price text-right money-format" value="'+formatNumber(value.price)+'"></div>';
				let nominal_detail = '<div class="form-group m-b-0"><input id="nominal_detail-'+j+'" class="form-control input-sm nominal_detail text-right" value="'+formatNumber(value.nominal)+'" readonly></div>';

				newData.push({
								"pr_line_key": pr_line_key_val,
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
			table_detail.rows.add(newData).draw();

			setTimeout(function(){
				getNominal();
				getAmount();
			}, 300);
		}

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

	let detail_data = [{'rkap_desc' : '', 'nature' : 0, 'nominal' : 0}];

	pr_line_data[pr_line_random] = detail_data;

	let table_detail_data = [1];
	let detail_data_all   = [];

	$('#addRow').on( 'click', function () {
		indexNow = table.data().count();
		$("#deleteRow").attr('disabled', false);

		if($("#tibe_opt-"+indexNow).val() == 0 || $("#rkap_opt-"+indexNow).val() == 0 || $("#line_name-"+indexNow).val() == "" || $("#fund_av-"+indexNow).val() == 0){
			customNotif('Warning', 'Please fill out all line field!', 'warning');
		}
		else if( $("#rkap_desc-1").val() == "" || $("#nature_opt-1").val() == 0 || parseInt($("#price-1").val().replace(/\./g, '')) == 0){
			customNotif('Warning', 'Please fill out all detail field!', 'warning');
		}
		else if(parseInt($("#fund_av-"+indexNow).val().replace(/\./g, '')) < parseInt($("#nominal-"+indexNow).val().replace(/\./g, ''))){
			customNotif('Warning', 'Nilai nominal lebih dari Fund Available!', 'warning');
		}
		else{
		    table_detail.rows().remove().draw();
			pr_line_random = generateRandomKey(6);

			counter++;
			$("#table_detail_title").html("Detail of line " + counter);

			let tribe_opt = '<div class="form-group m-b-0"><select id="tribe_opt-'+counter+'" data-id="' + counter + '" class="form-control input-sm tribe_opt select-center" name="tribe_name">';
				tribe_opt += opt_default;
				tribe_opt += '</select></div>';
			let rkap_opt = '<div class="form-group m-b-0"><select id="rkap_opt-'+counter+'" data-id="' + counter + '" class="form-control input-sm rkap_opt select-center">';
				rkap_opt += opt_default;
				rkap_opt += '</select></div>';
			let tier_opt = '<div class="form-group m-b-0"><select id="tier_opt-'+counter+'" data-id="' + counter + '" class="form-control input-sm tier_opt select-center">';
				tier_opt += opt_default;
				tier_opt += '</select></div>';
			let fs_opt = '<div class="form-group m-b-0"><select id="fs_opt-'+counter+'" data-id="' + counter + '" class="form-control input-sm fs_opt select-center">';
				fs_opt += opt_default;
				fs_opt += '</select></div>';
			let line_name = '<div class="form-group m-b-0"><input id="line_name-'+counter+'" data-id="' + counter + '" class="form-control input-sm line_name" autocomplete="off" ></div>';	
			let fund_av = '<div class="form-group m-b-0"><input id="fund_av-'+counter+'" data-id="' + counter + '" class="form-control input-sm fund_av text-right" value="0" readonly></div>';
			let nominal = '<div class="form-group m-b-0"><input id="nominal-'+counter+'" data-id="' + counter + '" class="form-control input-sm nominal text-right" value="0" readonly></div>';
			let pemilik_rekening = '<div class="form-group m-b-0"><input id="pemilik_rekening-' + counter + '" data-id="' + counter + '" class="form-control input-sm pemilik_rekening"></div>';
			let nama_bank    = '<div class="form-group m-b-0"><select id="nama_bank_opt-'+counter+'" class="form-control input-sm nama_bank_opt select-center select2">';
				nama_bank += opt_default;
				nama_bank += '</select></div>';
			let no_rekening      = '<div class="form-group m-b-0"><input id="no_rekening-' + counter + '" data-id="' + counter + '" class="form-control input-sm no_rekening"></div>';

			table.$('tr.selected').removeClass('selected');

		    getBank(counter);
		    table.rows.add(
		       [{ 
					"pr_line_key": pr_line_random,
	    			"no": counter,
	    			"tribe": tribe_opt,
	    			"rkap_name": rkap_opt,
	    			"tier": tier_opt,
	    			"fs": fs_opt,
	    			"fund_available": fund_av,
					"line_name": line_name,
	    			"nominal": nominal,
	    			"pemilik_rekening": pemilik_rekening,
	    			"nama_bank": nama_bank,
	    			"no_rekening": no_rekening
	    		}]
		    ).draw();
		    detail_data = [{'rkap_desc' : '', 'nature' : 0, 'nominal' : 0}];
		    pr_line_data[pr_line_random] = detail_data;

		    getTribe(counter);
	    	$('#table_data tbody tr').eq(counter-1).addClass('selected');
		    $("#addRow-detail").trigger( "click" );
		}
	});


	$('#addRow-detail').on( 'click', function () {
		indexNow_detail = table_detail.data().count();

		if($("#rkap_desc-"+indexNow_detail).val() == 0 || $("#nature_opt-"+indexNow_detail).val() == 0 || $("#nominal_detail-"+indexNow_detail).val() == 0){
			customNotif('Warning', 'Please fill out all field!', 'warning');
		}
		else{

			numDetail = indexNow_detail+1;

			let rkap_desc      = '<div class="form-group m-b-0"><input id="rkap_desc-'+numDetail+'" class="form-control input-sm rkap_desc" autocomplete="off"></div>';
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

			id_rkap_selected = $("#table_data tbody tr.selected").find("select.rkap_opt").val();
			console.log(id_rkap_selected);

			getNature(numDetail, id_rkap_selected);

		}
	});


	$("#directorat").bind("click", function(e) {
	    lastValue = $(this).val();
	}).bind("change", function(e) {
		change = true;
		total_row = table.data().count();
		if(lastValue > 0){
			changeConfirmation = true;
			if(total_row > 1){
				changeConfirmation = confirm("Merubah Directorate akan menghapus data?");
			}
		    if (!changeConfirmation) {
		        $(this).val(lastValue);
				change = false;
		    }
		}
		if(change == true){
			$("#table_data tbody").find("tr:not(:first)").remove();

	  		directorat = $(this).val();

	  		if(directorat > 0){
	  			getDivision();
	  		}


			getSubmitter(1);
			getDiketahui_1(2);
			getDiketahui_2(3);

			$("#unit").html(opt_default);

			$(".tribe_opt").html(opt_default);
			$(".rkap_opt").html(opt_default);
			$(".tier_opt").html(opt_default);
			$(".fs_opt").html(opt_default);
			$(".line_name").val('');
			$(".fund_av").val('0');
			$(".nominal").val('0');
		}
	});

	$("#division").bind("click", function(e) {
	    lastValue = $(this).val();
	}).bind("change", function(e) {
		change = true;
		total_row = table.data().count();
		if(lastValue != 0){
			changeConfirmation = true;
			if(total_row > 1){
				changeConfirmation = confirm("Merubah Division akan menghapus data?");
			}
		    if (!changeConfirmation) {
		        $(this).val(lastValue);
				change = false;
		    }
		}
		if(change == true){
			$("#table_data tbody").find("tr:not(:first)").remove();

	  		division = $(this).val();

	  		if(division != "0"){
	  			getUnit();
	  		}

			getSubmitter(1);

			$(".tribe_opt").html(opt_default);
			$(".rkap_opt").html(opt_default);
			$(".tier_opt").html(opt_default);
			$(".fs_opt").html(opt_default);
			$(".line_name").val('');
			$(".fund_av").val('0');
			$(".nominal").val('0');
		}
	});
	$("#unit").bind("click", function(e) {
	    lastValue = $(this).val();
	}).bind("change", function(e) {
		change = true;
		total_row = table.data().count();
		if(lastValue != 0){
			changeConfirmation = true;
			if(total_row > 1){
				changeConfirmation = confirm("Merubah unit akan menghapus data?");
			}
		    if (!changeConfirmation) {
		        $(this).val(lastValue);
				change = false;
		    }
		}
		if(change == true){
			$("#table_data tbody").find("tr:not(:first)").remove();

	  		unit = $(this).val();

	  		if(unit != "0"){
	  			getTribe(1);
	  		}

			$(".rkap_opt").html(opt_default);
			$(".tier_opt").html(opt_default);
			$(".fs_opt").html(opt_default);
			$(".line_name").val('');
			$(".fund_av").val('0');
			$(".nominal").val('0');
		}
	});


	$('#table_data tbody').on('change', 'tr td select.tribe_opt', function () {
		id_row = $(this).data('id');
		tribe  = $(this).find(':selected').val();
  		getRkapLine(tribe, id_row);
  		$("#line_name-"+id_row).val('');
  		$("#fund_av-"+id_row).val('0');
  		$("#nominal-"+id_row).val('0');
    });

	// Belum bikin validasi kalau ingin rubah rkapline di row pertama, namun didata kedua rkapnya sudah dipilih
	$('#table_data tbody').on('change', 'tr td select.rkap_opt', function () {
		id_row = $(this).data('id');
		id_rkap = $(this).val();
		getTier(id_rkap, id_row);
		getNature(id_row, id_rkap, 0);
  		$("#nominal-"+id_row).val('0');
    });

	$('#table_data tbody').on('change', 'tr td select.tier_opt', function () {
		id_row  = $(this).data('id');
		id_rkap = $(this).val();
		getFs(id_rkap, id_row);
		$("#nominal-"+id_row).val('0');
    });

	$('#table_data tbody').on('change', 'tr td select.fs_opt', function () {
		id_row = $(this).data('id');
		id_fs  = $(this).val();
		getFundAv(id_fs, id_row);
  		$("#nominal-"+id_row).val('0');
    });

	$('#table_data tbody').on('input', 'tr td input.nominal', function () {
		id_row      = $(this).data('id');
		val_nominal = $(this).val();
		fund_av     = $("#fund_av-"+id_row).val().toString().replace(/\./g, '');

		if(parseInt(val_nominal) > parseInt(fund_av)){
			$(this).val(fund_av);
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

    $("#submitter").on("change", function(){
		getJabatan("submitter");
    });

    $("#diketahui_1").on("change", function(){
    	 getJabatan("diketahui_1");
    });

    $("#diketahui_2").on("change", function(){
    	getJabatan("diketahui_2");
    });

	$('#deleteRow').on( 'click', function () {

		row_now     = $('#table_data tbody tr.selected');
		get_table   = table.row( row_now );
		index       = get_table.index()
		data        = get_table.data();
		pr_line_key = data.pr_line_key;

		total_data  = table.data().count();

		if(total_data > 0){
			table.row(index).remove().draw();

			table.column( 0 ).data().each( function ( value, i ) {
				num = i+1;
				get_id = $('#table tbody tr:eq(' + i + ') td:eq(0)').html();

				$('#tribe_opt-'+get_id).attr("id", 'tribe_opt-'+num);
				$('#rkap_opt-'+get_id).attr("id", 'rkap_opt-'+num);
				$('#fa_opt-'+get_id).attr("id", 'fa_opt-'+num);
				$('#line_name-'+get_id).attr("id", 'line_name-'+num);
				$('#fund_av-'+get_id).attr("id", 'fund_av-'+num);
				$('#nominal-'+get_id).attr("id", 'nominal-'+num);
				$('#pemilik_rekening-'+get_id).attr("id", 'pemilik_rekening-'+num);
				$('#nama_bank_opt-'+get_id).attr("id", 'nama_bank_opt-'+num);
				$('#no_rekening-'+get_id).attr("id", 'no_rekening-'+num);
				$('#table_data tbody tr:eq(' + i + ') td:eq(0)').html(num);
			});

			delete pr_line_data[pr_line_key];

			counter = num;

			$(this).attr('disabled', true);

			setTimeout(function(){
				getNominal();
				getAmount();
			}, 200);
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

				get_id = $('#table_detail tbody tr:eq(' + i + ') td:eq(0)').html();
				
				$('#rkap_desc-'+get_id).attr("id", 'rkap_desc-'+num);
				$('#nature_opt-'+get_id).attr("id", 'nature_opt-'+num);
				$('#quantity-'+get_id).attr("id", 'quantity-'+num);
				$('#price-'+get_id).attr("id", 'price-'+num);
				$('#quantity-'+get_id).data('id', num);
				$('#price-'+get_id).data('id', num);
				$('#nominal_detail-'+get_id).attr("id", 'nominal_detail-'+num);

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
		
		let directorat   = $("#directorat").val();
		let division     = $("#division").val();
		let unit         = $("#unit").val();
		let currency     = $("#currency").val();
		let amount       = parseInt($("#amount").val().replace(/\./g, ''));
		let total_data   = table.data().count();
		let fatalNominal = false;
		let fpjp_name     = $("#fpjp_name").val();
		let fpjp_type     = $("#type").val();
		let fpjp_date     = $("#fpjp_date").val();
		let submitter     = $("#submitter").val();
		let diketahui_1   = $("#diketahui_1").val();
		let diketahui_2   = $("#diketahui_2").val();
		let jabatan_sub   = $("#jabatan_sub").val();
		let jabatan_dik_1 = $("#jabatan_dik_1").val();
		let jabatan_dik_2 = $("#jabatan_dik_2").val();

	    for (var i = 1; i <= total_data; i++) {
			fund_av       = $("#fund_av-"+i).val();
			nominal_val   = $("#nominal-"+i).val();

			if(parseInt(nominal_val.replace(/\./g, '')) > parseInt(fund_av.replace(/\./g, ''))){
				fatalNominal =  true;
				customNotif('Warning', 'Nilai nominal lebih dari Fund Available pada data ke '+i+'!', 'warning');
			}
	    }

		if(fpjp_name == "" || unit == "" || amount == "0"){
    		customNotif('Warning', "Please fill all field", 'warning');
		}
		else if (fatalNominal == true)
		{
			return false;
		}
		else{

			let data_lines  = [];
			table.rows().eq(0).each( function ( index ) {
		    	j = index+1;
			    data = table.row( index ).data();

				rkap_val             = $("#rkap_opt-"+j).val();
				fs_val               = $("#fs_opt-"+j).val();
				line_name_val        = $("#line_name-"+j).val();
				fund_av              = $("#fund_av-"+j).val();
				nominal_val          = $("#nominal-"+j).val();
				pemilik_rekening_val = $("#pemilik_rekening-"+j).val();
				nama_bank_val        = $("#nama_bank_opt-"+j).val();
				no_rekening_val      = $("#no_rekening-"+j).val();

				pr_line_key_val = data.pr_line_key;
				data_detail    = pr_line_data[pr_line_key_val];

				if(rkap_val != "" && line_name_val != "" && parseInt(fund_av.replace(/\./g, '')) > 0 && parseInt(nominal_val.replace(/\./g, '')) > 0){
		    		data_lines.push({'id_rkap' : rkap_val, 'id_fs' : fs_val, 'nominal' : parseInt(nominal_val.replace(/\./g, '')), 'line_name' : line_name_val, 'pemilik_rekening' : pemilik_rekening_val, 'nama_bank' : nama_bank_val, 'no_rekening' : no_rekening_val, 'detail_data' : data_detail});
				}
			});

		    data = {
						amount : amount,
						currency : currency,
						fpjp_name : fpjp_name,
						fpjp_type : fpjp_type,
						fpjp_date : fpjp_date,
						directorat : directorat,
						submitter : submitter,
						diketahui_1 : diketahui_1,
						diketahui_2 : diketahui_2,
						jabatan_sub : jabatan_sub,
						jabatan_dik_1 : jabatan_dik_1,
						jabatan_dik_2 : jabatan_dik_2,
						data_line : data_lines,
						division : division,
						unit : unit
		    		}

		    console.log(data);

		    $.ajax({
		        url   : baseURL + 'fpjp/save_fpjp',
		        type  : "POST",
		        data  : data,
		        dataType: "json",
		        beforeSend  : function(){
	                          customLoading('show');
	                        },
		        success : function(result){
		        	console.log(result);
		        	if(result.status == true){
		        		customNotif('Success', "FPJP CREATED", 'success');
		        		setTimeout(function(){
		        			$(location).attr('href', baseURL + 'fpjp/fpjp');
		        		}, 1000);
		        	}
		        	else{
						customLoading('hide');
						customNotif('Error', result.messages, 'error');
					}
		        }
		    });

		}
	});

    $('#fpjp_date').datepicker({
		format: 'dd/mm/yyyy',
		todayHighlight:'TRUE',
		autoclose: true,
    });

    function get_all_data(){
		let form_data  = table.rows().data();

	    table.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
		    let data = this.data();
		} );
    }


    function getDivision() {

		let directorat    = $("#directorat").find(':selected').attr('data-name');

		$("#division").attr('disabled', true);
		$("#division").css('cursor', 'wait');

	    $.ajax({
	        url   : baseURL + 'purchase/load_data_rkap_view',
	        type  : "POST",
	        data  : {category : "division", directorat : directorat},
	        dataType: "json",
	        success : function(result){
        		let division_opt = opt_default;
	        	if(result.status == true){
					data = result.data;
		        	for(var i = 0; i < data.length; i++) {
					    obj = data[i];
					    division_opt += '<option value="'+ obj.id_division +'" data-name ="'+ obj.division +'">'+ obj.division +'</option>';
					}
	        	}
				$("#division").html(division_opt);
				$("#division").attr('disabled', false);
				$("#division").css('cursor', 'default');
	        }
	    });
	}

    function getUnit() {

		let directorat = $("#directorat").find(':selected').attr('data-name');
		let division   = $("#division").find(':selected').attr('data-name');

		$("#unit").attr('disabled', true);
		$("#unit").css('cursor', 'wait');

	    $.ajax({
	        url   : baseURL + 'purchase/load_data_rkap_view',
	        type  : "POST",
	        data  : {category : "unit", directorat : directorat, division : division},
	        dataType: "json",
	        success : function(result){
        		let unit = opt_default;
	        	if(result.status == true){
					data = result.data;
		        	for(var i = 0; i < data.length; i++) {
					    obj = data[i];
					    unit += '<option value="'+ obj.id_unit +'" data-name ="'+ obj.unit +'">'+ obj.unit +'</option>';
					}
	        	}
				$("#unit").html(unit);
				$("#unit").attr('disabled', false);
				$("#unit").css('cursor', 'default');
	        }
	    });
	}

    function getTribe(id_row) {

		let directorat = $("#directorat").find(':selected').attr('data-name');
		let division   = $("#division").find(':selected').attr('data-name');
		let unit       = $("#unit").find(':selected').attr('data-name');

		$("#tribe_opt-"+id_row).attr('disabled', true);
		$("#tribe_opt-"+id_row).css('cursor', 'wait');

	    $.ajax({
	        url   : baseURL + 'purchase/load_data_rkap_view',
	        type  : "POST",
	        data  : {category : "tribe", directorat : directorat, division : division, unit : unit},
	        dataType: "json",
	        success : function(result){
        		let tribe_opt = '';
	        	if(result.status == true){
					data = result.data;
		        	for(var i = 0; i < data.length; i++) {
					    obj = data[i];
					    tribe_opt += '<option value="'+ obj.tribe +'">'+ obj.tribe +'</option>';
					}
	        	}
				$("#tribe_opt-"+id_row).html(tribe_opt);
				$("#tribe_opt-"+id_row).attr('disabled', false);
				$("#tribe_opt-"+id_row).css('cursor', 'default');
				getRkapLine($("#tribe_opt-"+id_row).val(), id_row);
	        }
	    });
	}

    function getNature(id_row, id_rkap, id_select=0) {

    	console.log(id_row);
    	console.log(id_rkap);

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


    function getRkapLine(tribe, id_row) {

		let directorat   = $("#directorat").find(':selected').attr('data-name');
		let division     = $("#division").find(':selected').attr('data-name');
		let unit         = $("#unit").find(':selected').attr('data-name');
		let exclude_rkap = [];

		/*if(id_row > 1){
			row_before = parseInt(id_row)-1;
			for (var i = 1; i <= row_before; i++) {
				exclude_rkap_val = $("#rkap_opt-"+i).val();
				exclude_rkap.push(exclude_rkap_val);
			}
		}*/

		$("#rkap_opt-"+id_row).attr('disabled', true);
		$("#rkap_opt-"+id_row).css('cursor', 'wait');

	    $.ajax({
	        url   : baseURL + 'purchase/load_data_rkap',
	        type  : "POST",
	        data  : {directorat : directorat, division : division, unit : unit, tribe : tribe, exclude_rkap : exclude_rkap},
	        dataType: "json",
	        success : function(result){
        		let rkap_opt = opt_default;
	        	if(result.status == true){
					data = result.data;
		        	for(var i = 0; i < data.length; i++) {
					    obj = data[i];
					    rkap_opt += '<option value="'+ obj.id_rkap_line +'" data-name="'+ obj.division_name +'">'+ obj.rkap_name +'</option>';
					}
	        	}
				$("#rkap_opt-"+id_row).html(rkap_opt);
				$("#rkap_opt-"+id_row).attr('disabled', false);
				$("#rkap_opt-"+id_row).css('cursor', 'default');
	        }
	    });
	}

	function getTier(id_rkap, id_row){
	    $.ajax({
	        url   : baseURL + 'api-budget/load_data_tier',
	        type  : "POST",
	        data  : {id_rkap : id_rkap},
	        dataType: "json",
	        success : function(result){
	        	console.log(result);
        		let tier_opt = opt_default;
	        	if(result.status == true){
					data = result.data;
					if(data.length == 1){
						tier_opt = '';
					}
		        	for(var i = 0; i < data.length; i++) {
					    obj = data[i];
					    tier_opt += '<option value="'+ obj.id_rkap +'">'+ obj.tier_name +'</option>';
					}
	        	}
				$("#tier_opt-"+id_row).html(tier_opt);
				$("#tier_opt-"+id_row).attr('disabled', false);
				$("#tier_opt-"+id_row).css('cursor', 'default');
				getFs($("#tier_opt-"+id_row).val(), id_row);
	        }
	    });
	}

	function getFs(id_rkap, id_row){

		let exclude_fs = [];

		if(id_row > 1){
			row_before = parseInt(id_row)-1;
			for (var i = 1; i <= row_before; i++) {
				exclude_fs_val = parseInt($("#fs_opt-"+i).val());
				exclude_fs.push(exclude_fs_val);
			}
		}
	    $.ajax({
	        url   : baseURL + 'api-budget/load_data_fs',
	        type  : "POST",
	        data  : {id_rkap : id_rkap},
	        dataType: "json",
	        success : function(result){
        		let fs_opt = opt_default;
	        	if(result.status == true){
					data = result.data;
					if(data.length == 1){
						fs_opt = '';
					}
		        	for(var i = 0; i < data.length; i++) {
					    obj = data[i];

					    if(exclude_fs.indexOf(parseInt(obj.id_fs)) === -1)
				    	fs_opt += '<option value="'+ obj.id_fs +'">'+ obj.fs_number +'</option>';
					    
					}
	        	}
				$("#fs_opt-"+id_row).html(fs_opt);
				$("#fs_opt-"+id_row).attr('disabled', false);
				$("#fs_opt-"+id_row).css('cursor', 'default');
				getFundAv($("#fs_opt-"+id_row).val(), id_row);
	        }
	    });
	}

    function getFundAv(id_fs, id_row) {
	    $.ajax({
	        url   : baseURL + 'api-budget/load_data_fa_fs',
	        type  : "POST",
	        data  : {id_fs : id_fs},
	        dataType: "json",
	        success : function(result){
        		let fund_av = 0;
	        	if(result.status == true){
	        		data = result.data;
					fund_av = data.fa_fs;
	        	}
				$("#fund_av-"+id_row).val(formatNumber(fund_av));
	        }
	    });
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

    function getNominal(){
		let total_detail = table_detail.data().count();
		let totalNominal = 0;

		for (var i = 1; i <= total_detail; i++) {
			nominal_detail_val = $("#nominal_detail-"+i).val();
			totalNominal += parseInt(nominal_detail_val.replace(/\./g, ''));
	    }

	    $("#table_data tbody tr.selected").find("input.nominal").val(formatNumber(totalNominal));
    }

    function getSubmitter(jabatan_code){

		let directorat = $("#directorat").val();
		//let division   = $("#division").val();
		let division   = $("#division").find(':selected').attr('dat_id');

	    $.ajax({
	        url   : baseURL + 'api-budget/load_data_submitter',
	        type  : "POST",
	        data  : {directorat:directorat, division:division, jabatan_code:jabatan_code},
	        dataType: "json",
	        success : function(result){      
        		let submitter_opt = opt_default;
	        	if(result.status == true){
					data = result.data;
		        	for(var i = 0; i < data.length; i++) {
					    obj = data[i];
					    submitter_opt += '<option value="'+ obj.nama +'" data-jabatan="'+ obj.jabatan +'">'+ obj.nama +'</option>';
					}
	        	}
				$("#submitter").html(submitter_opt);				
				getJabatan("submitter");
	        }
	    });
	}

    function getDiketahui_1(jabatan_code){
	    let directorat = $("#directorat").val();

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
					    diketahui_1_opt += '<option value="'+ obj.nama +'" data-jabatan="'+ obj.jabatan +'">'+ obj.nama +'</option>';
					}
	        	}
				$("#diketahui_1").html(diketahui_1_opt);				
				getJabatan("diketahui_1");
	        }
	    });
	}

    function getDiketahui_2(jabatan_code){
	    let directorat = $("#directorat").val();

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
					    diketahui_2_opt += '<option value="'+ obj.nama +'" data-jabatan="'+ obj.jabatan +'">'+ obj.nama +'</option>';
					}
	        	}
				$("#diketahui_2").html(diketahui_2_opt);				
				getJabatan("diketahui_2");
	        }
	    });
	}

	function storeDataDetail(){

		row_now = $('#table_data tbody tr.selected');
		let get_table      = table.row( row_now );
		let index          = get_table.index()
		let index_Num      = index+1;
		let data           = get_table.data();

		pr_line_key = data.pr_line_key;

		let total_detail = table_detail.data().count();
		let detail_data  = [];

		for (var i = 1; i <= total_detail; i++) {
			rkap_desc_val      = $("#rkap_desc-"+i).val();
			nature_val         = $("#nature_opt-"+i).val();
			quantity_val       = $("#quantity-"+i).val();
			price_val          = $("#price-"+i).val();
			nominal_detail_val = $("#nominal_detail-"+i).val();

			if(rkap_desc_val != "" && nature_val != 0 && parseInt(price_val.replace(/\./g, '')) > 0){
    			detail_data.push({'rkap_desc' : rkap_desc_val, 'nature' : nature_val, 'quantity' : quantity_val, 'price' : parseInt(price_val.replace(/\./g, '')), 'nominal' : parseInt(nominal_detail_val.replace(/\./g, ''))});
			}
	    }

	    pr_line_data[pr_line_key] = detail_data;

	}


	function getBank(id_row) {

    	$("#nama_bank_opt-"+id_row).attr('disabled', true);
		$("#nama_bank_opt-"+id_row).css('cursor', 'wait');

	    $.ajax({
	        url   : baseURL + 'fpjp/load_data_bank',
	        type  : "POST",
	        dataType: "json",
	        success : function(result){
				let nama_bank_opt = opt_default;
				data = result.data;
	        	for(var i = 0; i < data.length; i++) {
					obj = data[i];
				    nama_bank_opt += '<option value="'+ obj.bank_name + '">'+ obj.bank_name +'</option>';
				}
				$("#nama_bank_opt-"+id_row).html(nama_bank_opt);
				$("#nama_bank_opt-"+id_row).attr('disabled', false);
				$("#nama_bank_opt-"+id_row).css('cursor', 'default');
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

	function runSelect2(i){
       $("#nama_bank_opt-"+i).select2();
	}

  });
</script>