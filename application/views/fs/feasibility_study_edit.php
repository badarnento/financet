<?php $this->load->view('ilustration') ?>

<div class="row">
  <div class="white-box boxshadow m-b-0 mt-10 border-top-only-5 z-index-2 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">Justification Details</h5>
    </div>
  </div>
  <div class="white-box boxshadow z-index-2 py-10 border-bottom-only-5 small">
	<form class="form-horizontal">
    <div class="row">

    	<div class="col-sm-6">
        	<div class="form-group m-b-10">
          	<label class="col-sm-5 col-md-4 control-label text-left">Number <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="fs_number" class="control-label"><?= $fs_number ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Justification Name <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <input type="text" class="form-control input-sm" id="fs_name" placeholder="Name" value="<?= $fs_name ?>">
            </div>
          </div>
    		<div class="form-group m-b-10">
          	<label class="col-sm-5 col-md-4 control-label text-left">Description <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <textarea class="form-control input-sm" id="fs_description" rows="3"><?= $fs_description ?></textarea>
            </div>
          </div>

			<div class="form-group m-b-10">
	            <label for="currency" class="col-sm-5 col-md-4 control-label text-left">Currency <span class="pull-right">:</span></label>
	            <div class="col-sm-7 col-md-8">
	            	<?php
		            	$currency = get_currency();
	            	?>
					<select name="currency" id="currency" class="form-control input-sm">
						<?php foreach ($currency as $key => $value) : ?>
							<option value="<?= $key ?>"<?= ($value==$fs_currency) ? ' selected' : ''?>><?= $value ?></option>
						<?php endforeach; ?>
					</select>
	            </div>
	        </div>
			<div class="form-group m-b-10 currency_display<?= ($fs_currency == "IDR") ? ' d-none' : ''?>">
	            <label for="rate" class="col-sm-5 col-md-4 control-label text-left">Rate <span class="pull-right">:</span></label>
	            <div class="col-sm-7 col-md-8">
	              <input type="text" class="form-control input-sm money-format" id="rate" placeholder="Rate" value="<?= $fs_rate ?>">
	            </div>
	        </div>
    	  <div class="form-group m-b-10">
          	<label class="col-sm-5 col-md-4 control-label text-left">Total Amount <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
	        <?php if($enable_edit): ?>
              <input type="text" class="form-control input-sm" id="amount" readonly value="<?= $fs_amount ?>">
	        <?php else: ?>
              <input type="hidden" class="form-control input-sm" id="amount" readonly value="<?= $fs_amount ?>">
              <label class="control-label text-left"><?= $fs_amount ?></label>
	        <?php endif; ?>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Directorate <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="fs_directorat" class="control-label"><?= get_directorat($fs_directorat) ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Division <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="fs_division" class="control-label text-left"><?= get_division($fs_division) ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Unit <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="fs_unit" class="control-label text-left"><?= get_unit($fs_unit) ?></label>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Date <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label"><?= $fs_date ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label for="status" class="col-sm-5 col-md-4 control-label text-left">Status <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label"><?= ($fs_status == "request_approve") ? "Waiting approval" :  ucfirst($fs_status) ?></label>
            </div>
          </div>
	    	<div class="form-group m-b-10">
	    		<label class="col-sm-5 col-md-4 control-label text-left">Last Update <span class="pull-right">:</span></label>
	            <div class="col-sm-7 col-md-8">
	            	<label class="control-label text-left" style="word-break: break-all"><?= $fs_status_desc ?> at <?= $fs_last_update ?></label>
	            	<?php if(isset($fs_approval_remark)): ?>
	            	<br>
	            	<label class="control-label text-left" style="word-break: break-all">&quot;<?= $fs_approval_remark ?>&quot;</label>
	            	<?php endif; ?>
	            </div>
	        </div>

        </div>

    </div>
    </form>
  </div>
</div>


<div class="row">
  <div class="white-box boxshadow m-b-0 border-top-only-5 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">Rkap Detail</h5>
    </div>
  </div>
  <div class="white-box boxshadow border-bottom-only-5">
	<div class="row">
		<div class="col-md-12">
	      <table id="table_data" class="table dataTable table-responsive table-striped table-bordered cell-border stripe w-full small">
	        <thead>
	        	<tr>
	        		<th class="text-center">No</th>
					<th class="text-center">Tribe/Usecase</th>
					<th class="text-center">RKAP Name</th>
					<th class="text-center">Program ID</th>
					<th class="text-center">Proc Type</th>
					<th class="text-center">Proc Type Desc</th>
					<th class="text-center">Description</th>
					<th class="text-center">Period Start</th>
					<th class="text-center">Period End</th>
					<th class="text-center">Fund Avl RKAP</th>
					<th id="currency_text" class="text-center">Nominal</th>
					<th class="text-center">Nominal</th>
		        </tr>
	        </thead>
	      </table>
	    </div>
	</div>
  </div>
</div>

<div class="row">
  <div class="white-box border-radius-5 small">
      <form class="form-horizontal">

        <div class="row">
        	<div class="col-md-8">
		        <div class="form-group m-b-10">
		        	<label for="submitter" class="col-sm-3 control-label text-left">Submitter <span class="pull-right">:</span></label>
		        	<div class="col-sm-3">
		                <select class="form-control input-sm" id="submitter">
		                  <option value="">-- Pilih --</option>
		                </select>
		            </div>
		            <div class="col-sm-5">
		            	<label class="control-label text-left" id="jabatan_sub"></label>
		            </div>
		        </div>
	  	        <div class="form-group m-b-10">
	  	        	<div class="attachment_group">
		  	        	<label class="col-sm-3 control-label text-left">Document <span class="pull-right">:</span></label>
			            <div id="attachment_view" class="col-sm-9">
	            			<label class="control-label text-left"><a id="fs_attachment" href="<?= base_url("download/") . encrypt_string("uploads/".$fs_attachment, true) ?>" title="<?= $fs_attachment ?>" target="_blank"><?= substr($fs_attachment, 0, 25). "..."?> </a> <button type="button" id="change_file" class="btn btn-warning btn-xs m-l-10 px-10 py-2 pull-right"><i class="fa fa-edit"></i> Change </button> </label>
						</div>
			            <div id="upload_attachment" class="col-sm-7 d-none">
			                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
			                    <div id="fileinput" class="form-control input-sm" data-trigger="fileinput"> <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div><span class="input-group-addon btn btn-default btn-file"> <span class="fileinput-new">Select file</span> <span class="fileinput-exists">Change</span>
			                    <input id="attachment" type="file" name="attachment" accept=".pdf" required="required"> </span> <a id="fileinput_remove" href="javascript:void(0)" class="input-group-addon btn btn-warning fileinput-exists" data-dismiss="fileinput">Remove</a>
			                </div>
		                    <div class="progress progress-lg d-none">
		                        <div id="progress" class="progress-bar progress-bar-info active progress-bar-striped" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%" role="progressbar"></div>
		                    </div>
		                    <button type="button" id="cancel_change_file" class="btn btn-danger btn-xs px-5 pull-right"><i class="fa fa-times"></i> Cancel </button>
						</div>
		            </div>
	            	<div class="col-sm-offset-3 col-sm-7">
	            		<!-- <button type="button" id="addFile" class="btn btn-info btn-xs px-5 pull-right"><i class="fa fa-upload"></i> Change </button> -->
	            	</div>
		        </div>
        	</div>
        </div>
       <!--  <div class="row">
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
       </div> -->
<!--         <div class="form-group m-b-0">
	<div class="pull-right">
		<a href="<?= base_url('feasibility-study') ?>"  class="btn btn-danger m-10 w-100p"><i class="fa fa-times"></i> Discard </a>
		<button type="button" id="save_data" class="btn btn-info m-10 w-100p"><i class="fa fa-save"></i> Save </button>
	</div>
</div> -->

      	<div class="row">
      		<div class="col-sm-6 col-sm-offset-6">
		        <div class="form-group pull-right">
        			<a href="<?= base_url('feasibility-study') ?>"  class="btn btn-danger border-radius-5 m-0 w-100p"><i class="fa fa-times"></i> Discard </a>
		        	<button type="button" id="save_data" class="btn btn-info border-radius-5 ml-10 w-100p"><i class="fa fa-save"></i> Save </button>
		        </div>
      		</div>
      	</div>
      </form>
  </div>
</div>

<script>
  $(document).ready(function(){
	
	const id_fs       = '<?= $id_fs ?>';
	const url         = baseURL + 'feasibility-study/api/load_data_fs_lines';
	const opt_default = '<option value="0" data-name="">-- Choose --</option>';
	const directorat  = <?= $fs_directorat ?>;
	const division    = <?= $fs_division ?>;
	const unit        = <?= $fs_unit ?>;
	const submitter   = '<?= $fs_submitter ?>';
	
	const directorat_name  = $("#fs_directorat").html();
	const division_name    = $("#fs_division").html();
	const unit_name        = $("#fs_unit").html();
	let attachment_file = "";
	let last_attachment = "";

	let rkap_data  = [];
	const SU_BUDGET = <?= $su_budget_js  ?>;

	let buttonAdd = '<button type="button" id="addRow" class="btn btn-success btn-sm m-l-5 mr-5"><i class="fa fa-plus"></i> Add New </button>';
		buttonAdd += '<button type="button" id="deleteRow" class="btn btn-danger btn-sm m-l-5 mr-5" disabled><i class="fa fa-trash"></i> Delete </button>';

	let counter = 0;
	let visibleProcDesc = false;
	let is_cadangan = false;

	getSubmitter(1);

	Pace.track(function(){
	    $('#table_data').DataTable({
	      "serverSide"      : true,
	      "processing"      : true,
	      "ajax"            : {
									"url"  : url,
									"type" : "POST",
									"dataType": "json",
									"data"    : function ( d ) {
													d.id_fs    = id_fs;
													d.category = 'edit';
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
				    			{"data": "rkap_name", "width": "250px" },
				    			/*{"data": "tribe_edit", "width": "150px" },
				    			{"data": "rkap_edit", "width": "250px" },*/
				    			{"data": "program_id", "width": "150px" },
				    			{"data": "proc_type_edit", "width": "150px" },
				    			{"data": "proc_desc_edit", "width": "150px" },
				    			{"data": "line_name_edit", "width": "250px" },
				    			{"data": "period_start_edit", "width": "150px" },
				    			{"data": "period_end_edit", "width": "150px" },
				    			{"data": "fund_av_edit", "width": "150px", "class": "text-right"  },
				    			{"data": "nominal_currency_edit", "width": "150px", "class": "text-right"  },
				    			{"data": "nominal_edit", "width": "150px", "class": "text-right"  }
				    		],
			"columnDefs": [
	            {
	                "targets": [ 5 <?= ($fs_currency == "IDR" ) ? ", 10" : "" ?> ],
	                "visible": false
	            }
	        ],
          "drawCallback": function ( settings ) {
            let api         = this.api();
            let row_datas   = api.rows({ page: 'current' }).data();
            $('#table_data tbody tr').eq(counter).addClass('selected');
            row_datas.each(function (data, i) {
            	counter++;
            	if(data.proc_desc != ""){
            		visibleProcDesc = true;
            	}
            	if(data.is_cadangan == "1"){
            		is_cadangan = true;
            	}

            	/*if(i==0){
            		getRkapLineData(data.tribe, data.id_rkap_line, 1);
            	}*/
            });
            if(visibleProcDesc){
            	table.columns( [5] ).visible(visibleProcDesc);
            }
          	$('#table_data_paginate').remove();
          },
	      "pageLength"      : 100,
	      "ordering"        : false,
	      "scrollY"         : 480,
	      "scrollX"         : true,
	      "scrollCollapse"  : true,
	      "autoWidth"       : true,
	      "bAutoWidth"      : true
	    });
	});


	let table = $('#table_data').DataTable();
  	$('#table_data_length').remove();
  	$('#table_data_filter').remove();
  	// $('#table_data_filter').html(buttonAdd);

	$('#table_data tbody').on( 'click', 'tr', function () {
		if (! $(this).hasClass('selected') ) {
			table.$('tr.selected').removeClass('selected');
			$(this).addClass('selected');
			let get_table = table.row( this );
			let index     = get_table.index()
			let index_Num = index+1;
			let data      = get_table.data();

			total_data = table.data().count();

			if(total_data > 1){
				$("#deleteRow").attr('disabled', false);
			}
		}
	});

	$('#table_data tbody').on('input', 'tr td input.nominal', function () {
		id_row      = $(this).data('id');
		val_nominal = $(this).val();
		val_nominal = val_nominal.toString().replace(/\./g, '');
		fund_av     = $("#fund_av-"+id_row).val().toString().replace(/\./g, '');

		if(parseInt(val_nominal) > parseInt(fund_av)){
			$(this).val(formatNumber(fund_av));
		}
		getAmount();
    });


	$('#addRow').on( 'click', function () {
		indexNow = table.data().count();
		$("#deleteRow").attr('disabled', false);

		if($("#tibe_opt-"+indexNow).val() == 0 || $("#rkap_opt-"+indexNow).val() == 0 || $("#line_name-"+indexNow).val() == "" || $("#fund_av-"+indexNow).val() == 0 || $("#nominal-"+indexNow).val() == 0){
			customNotif('Warning', 'Please fill out all line field!', 'warning');
		}
		else{

			counter++;
		    resetRow(counter);
		    getTribe(counter);

	    	$('#table_data tbody tr').eq(counter-1).addClass('selected');
		}
	});

	$('#change_file').on( 'click', function () {
		$("#attachment_view").addClass("d-none");
		$("#upload_attachment").removeClass("d-none");
		attachment_file = last_attachment;
	});
	
	$('#cancel_change_file').on( 'click', function () {
		$("#upload_attachment").addClass("d-none");
		$("#attachment_view").removeClass("d-none");
		last_attachment =attachment_file;
		attachment_file = '';
	});

	function resetRow(i){
		    let tribe_opt = '<div class="form-group m-b-0"><select id="tribe_opt-' + i + '" data-id="' + i + '" class="form-control input-sm tribe_opt select-center" name="tribe_name">';
				tribe_opt += opt_default;
				tribe_opt += '</select></div>';
			let rkap_opt = '<div class="form-group m-b-0"><select id="rkap_opt-' + i + '" data-id="' + i + '" class="form-control input-sm rkap_opt select-center select2">';
				rkap_opt += opt_default;
				rkap_opt += '</select></div>';
			let tier_opt = '<div class="form-group m-b-0"><select id="tier_opt-' + i + '" data-id="' + i + '" class="form-control input-sm tier_opt select-center">';
				tier_opt += opt_default;
				tier_opt += '</select></div>';
			let proc_type_real = '<div class="form-group m-b-0"><input id="proc_type_real-' + i + '" data-id="' + i + '" class="form-control input-sm text-center proc_type_real" readonly></div>';
			let proc_opt  = '<div class="form-group m-b-0"><select id="proc_opt-' + i + '" data-id="' + i + '" class="form-control input-sm proc_opt select-center">';
				proc_opt += opt_default;
				proc_opt += '</select></div>';
			let proc_desc = '<div class="form-group m-b-0"><input id="proc_desc-' + i + '" data-id="' + i + '" class="form-control input-sm proc_desc" ></div>';
			let line_name = '<div class="form-group m-b-0"><input id="line_name-' + i + '" data-id="' + i + '" class="form-control input-sm line_name" ></div>';
			let period_start = '<div class="form-group m-b-0"><input id="period_start-' + i +'" data-id="' + i +'" class="form-control input-sm period_start date_period" value="' + DATE + '"></div>';
			let period_end = '<div class="form-group m-b-0"><input id="period_end-' + i +'" data-id="' + i +'" class="form-control input-sm period_end date_period" value="' + DATE + '"></div>';
			let fund_av   = '<div class="form-group m-b-0"><input id="fund_av-' + i + '" data-id="' + i + '" class="form-control input-sm fund_av text-right" value="0" readonly></div>';
			let nominal_currency   = '<div class="form-group m-b-0"><input id="nominal_currency-' + i + '" data-id="' + i + '" class="form-control input-sm nominal_currency text-right money-format" value="0"></div>';
			let nominal   = '<div class="form-group m-b-0"><input id="nominal-' + i + '" data-id="' + i + '" class="form-control input-sm nominal text-right money-format" value="0"></div>';

			table.$('tr.selected').removeClass('selected');

		    table.rows.add(
		       [{
					"no": i,
					"tribe": tribe_opt,
					"rkap_name": rkap_opt,
					"tier": tier_opt,
					"proc_type_real": proc_type_real,
					"proc_type": proc_opt,
					"proc_desc": proc_desc,
					"line_name": line_name,
					"period_start": period_start,
					"period_end": period_end,
					"fund_available": fund_av,
					"nominal_currency": nominal_currency,
					"nominal": nominal
	    		}]
		    ).draw();

	    	$('#table_data tbody tr').eq(i-1).addClass('selected');

	}



	function getRkapLineData(tribe, rkap_val_select, id_row) {
		let exclude_rkap = [];


		if(id_row == 1){

			$("#rkap_opt-"+id_row).attr('disabled', true);
			$("#rkap_opt-"+id_row).css('cursor', 'wait');

		}

	    $.ajax({
	        url   : baseURL + 'api-budget/load_data_rkap/',
	        type  : "POST",
	        data  : {directorat : directorat_name, division : division_name, unit : unit_name, tribe : tribe, exclude_rkap : exclude_rkap},
	        dataType: "json",
	        success : function(result){
        		let rkap_opt = opt_default;
        		let disabled_rkap = '';
	        	if(result.status == true){
					data = result.data;
		        	for(var i = 0; i < data.length; i++) {
					    obj = data[i];
					    proc = obj.proc_type;
					    disabled_rkap = (obj.disabled == true) ? '1' : '0';
					    selected_rkap = (rkap_val_select == obj.id_rkap_line) ? ' selected' : '';
					    if(SU_BUDGET == true){
				    		rkap_data.push({'id_rkap_line': obj.id_rkap_line, 'proc_type' : proc,  'rkap_name' : obj.rkap_name});
				    		rkap_opt += '<option value="'+ obj.id_rkap_line +'" data-proc="' + proc + '" data-disabled="' + disabled_rkap + '"'+selected_rkap+'>'+ obj.rkap_name +'</option>';
					    }
					    else{
					    	if(proc.toLowerCase() != "procurement - penunjukan langsung"){
					    		rkap_data.push({'id_rkap_line': obj.id_rkap_line, 'proc_type' : proc,  'rkap_name' : obj.rkap_name});
					    		rkap_opt += '<option value="'+ obj.id_rkap_line +'" data-proc="' + proc + '" data-disabled="' + disabled_rkap + '"'+selected_rkap+'>'+ obj.rkap_name +'</option>';
					    	}
					    }
					}
	        	}

				if(id_row == 1){
					$("#rkap_opt-"+id_row).html(rkap_opt);
					$("#rkap_opt-"+id_row).attr('disabled', false);
					$("#rkap_opt-"+id_row).css('cursor', 'default');
				}

	        }
	    });
	}

	function getRkapLine(tribe, id_row) {
		let exclude_rkap = [];

		$("#rkap_opt-"+id_row).attr('disabled', true);
		$("#rkap_opt-"+id_row).css('cursor', 'wait');
		let rkap_opt = opt_default;
		let disabled_rkap = '';

    	for(var i = 0; i < rkap_data.length; i++) {
		    obj = data[i];

		    proc = obj.proc_type;
	    	disabled_rkap = (obj.disabled == true) ? '1' : '0';

		    if(SU_BUDGET == true){
	    		rkap_opt += '<option value="'+ obj.id_rkap_line +'" data-proc="' + proc + '" data-disabled="' + disabled_rkap + '">'+ obj.rkap_name +'</option>';
		    }
		    else{
		    	if(proc.toLowerCase() != "procurement - penunjukan langsung"){
		    		rkap_opt += '<option value="'+ obj.id_rkap_line +'" data-proc="' + proc + '" data-disabled="' + disabled_rkap + '">'+ obj.rkap_name +'</option>';
		    	}
		    }
		}
		$("#rkap_opt-"+id_row).html(rkap_opt);
		$("#rkap_opt-"+id_row).attr('disabled', false);
		$("#rkap_opt-"+id_row).css('cursor', 'default');

	}

	function getTier(id_rkap, id_row){
	    $.ajax({
	        url   : baseURL + 'api-budget/load_data_tier_from_header',
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
	        }
	    });
	}

	function getProcType(procRkap, id_row){
	    $.ajax({
	        url   : baseURL + 'api-budget/load_data_proc_type',
	        type  : "POST",
	        data  : {id_rkap : id_rkap},
	        dataType: "json",
	        success : function(result){
        		let proc_opt = opt_default;
	        	if(result.status == true){
					data = result.data;
					if(data.length == 1){
						proc_opt = '';
					}
		        	for(var i = 0; i < data.length; i++) {
					    obj = data[i];
					    proc = obj.proc_type;
				    	proc_select = (procRkap == proc) ? " selected" : "";

					    if(SU_BUDGET == true){
				    		proc_opt += '<option value="'+ proc +'"' + proc_select + '>'+ proc +'</option>';
					    }
					    else{
					    	if(proc.toLowerCase() != "procurement - penunjukan langsung"){
					    		proc_opt += '<option value="'+ proc +'"' + proc_select + '>'+ proc +'</option>';
					    		console.log('masuk ihiw');
					    	}
					    }
					}
	        	}
				$("#proc_opt-"+id_row).html(proc_opt);
				$("#proc_opt-"+id_row).attr('disabled', false);
				$("#proc_opt-"+id_row).css('cursor', 'default');
	        }
	    });
	}

    function getFundAv(id_rkap, id_row) {
	    $.ajax({
	        url   : baseURL + 'api-budget/load_data_fa_rkap',
	        type  : "POST",
	        data  : {id_rkap : id_rkap},
	        dataType: "json",
	        success : function(result){
        		let fund_av = 0;
	        	if(result.status == true){
	        		data = result.data;
					fund_av = data.fa_rkap;
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


	$('#table_data tbody').on('input', 'tr td input.nominal_currency', function () {
		id_row       = $(this).data('id');
		val_currency = $(this).val().toString().replace(/\./g, '');
		val_rate     = $("#rate").val().toString().replace(/\./g, '');
		if(parseInt(val_rate) > 0){
			val_nominal = parseInt(val_rate) * parseInt(val_currency);
		}
		else{
			val_nominal = 1 * parseInt(val_currency);
		}

		$('#nominal-'+id_row).val(formatNumber(val_nominal))

		getAmount();
    });

	$('#table_data tbody').on('input', 'tr td input.nominal', function () {
		id_row      = $(this).data('id');
		val_nominal = $(this).val().toString().replace(/\./g, '');
		fund_av     = $("#fund_av-"+id_row).val();

		if(parseInt(val_nominal) > parseInt(fund_av.toString().replace(/\./g, ''))){
			$(this).val(fund_av);
		}

    });

    $('#table_data tbody').on('focus', 'tr td input.date_period', function () {
    	$(this).datepicker({
			format: 'dd-mm-yyyy',
			todayHighlight:'TRUE',
			autoclose: true,
			minDate:0,
	    });
    });

    $("#rate").on("change", function(){

		val_rate     = $(this).val().toString().replace(/\./g, '');
		table.rows().eq(0).each( function ( index ) {
	    	j = index+1;

			val_currency = $("#nominal_currency-"+j).val().toString().replace(/\./g, '');
			if(parseInt(val_rate) > 0){
				val_nominal = parseInt(val_rate) * parseInt(val_currency);
			}
			else{
				val_nominal = 1 * parseInt(val_currency);
			}

			$('#nominal-'+j).val(formatNumber(val_nominal))
		});
		getAmount();
    });


    $("#currency").on("change", function(){
    	console.log('change');

		if($(this).val() != "IDR"){
			$("#currency_text").html('Nominal ' + $(this).val());
			$(".currency_display").removeClass('d-none');
			$(".nominal").attr('readonly', true);
			table.columns( [10] ).visible(true);
		}
		else{
			console.log('gantiii');
			$("#currency_text").html('Nominal Currency');
			$(".currency_display").addClass('d-none');
			$(".nominal").attr('readonly', false);
			$(".nominal").val('0');
			table.columns( [10] ).visible(false);
		}
    });


	$("#submitter").on("change", function(){
		getJabatan("submitter");
    });


	$("#save_data").on('click', function () {

	    getAmount();
		
		let fs_name        = $("#fs_name").val();
		let fs_description = $('textarea#fs_description').val()
		let currency       = $("#currency").val();
		let rate           = parseInt($("#rate").val().replace(/\./g, ''));
		let amount         = parseInt($("#amount").val().replace(/\./g, ''));
		let total_data     = table.data().count();
		
		let submitter      = $("#submitter").val();
		let jabatan_sub    = $("#jabatan_sub").html();

	    for (var i = 1; i <= total_data; i++) {
			fund_av       = $("#fund_av-"+i).val();
			nominal_val   = $("#nominal-"+i).val();

			if(parseInt(nominal_val.replace(/\./g, '')) > parseInt(fund_av.replace(/\./g, ''))){
				fatalNominal =  true;
				customNotif('Warning', 'Nilai nominal lebih dari FA RKap pada data ke '+i+'!', 'warning');
			}
	    }

		if(fs_name == "" || amount == "0"){
    		customNotif('Warning', "Please fill all field", 'warning');
		}
		else{

			let data_lines = [];
			let fraud      = false;
			let risk       = false;
			table.rows().eq(0).each( function ( index ) {
		    	j = index+1;
				data                 = table.row( index ).data();
				fs_lines_id          = data.id;
				proc_opt_val         = $("#proc_opt-"+j).val();
				proc_desc_val        = ($("#proc_desc-"+j).val() == null) ? '' : $("#proc_desc-"+j).val();
				line_name_val        = $("#line_name-"+j).val();
				period_start_val     = $("#period_start-"+j).val();
				period_end_val       = $("#period_end-"+j).val();
				fund_av              = $("#fund_av-"+j).val();
				nominal_currency_val = ($("#nominal_currency-"+j).val() == null) ? 0 : parseInt($("#nominal_currency-"+j).val().replace(/\./g, ''));
				nominal_val          = $("#nominal-"+j).val();
				proc_lw = proc_opt_val.toLowerCase();

				if (proc_lw ==  "virtual money"){
					fraud = true;
				}

				if (proc_lw.indexOf('vm') > -1)
				{
					fraud = true;
				}
				if(proc_lw ==  "sponsorship"){
					// risk = true;
				}

				if(parseInt(fund_av.replace(/\./g, '')) > 0 && parseInt(nominal_val.replace(/\./g, '')) > 0){
		    		data_lines.push({'fs_lines_id': fs_lines_id, 'proc_type' : proc_opt_val,  'proc_desc' : proc_desc_val,  'line_name' : line_name_val,  'period_start' : period_start_val,  'period_end' : period_end_val, 'nominal_currency' : nominal_currency_val, 'nominal' : parseInt(nominal_val.replace(/\./g, ''))});
				}
			});

		    data = {
					id_fs : id_fs,
					directorat : directorat,
					division : division,
					unit : unit,
					fs_name : fs_name,
					fs_description : fs_description,
					currency : currency,
					rate : rate,
					amount : amount,
					data_line : data_lines,
					submitter : submitter,
					attachment : attachment_file,
					fraud : fraud,
					risk : risk,
					jabatan_sub : jabatan_sub,
					is_cadangan : is_cadangan
	    		}

	    	console.log(data);

		    $.ajax({
		        url   : baseURL + 'feasibility-study/api/update',
		        type  : "POST",
		        data  : data,
		        dataType: "json",
		        beforeSend  : function(){
                          customLoading('show');
                        },
		        success : function(result){
		        	if(result.status == true){
		        		customNotif('Success', "Justification Updated", 'success');
		        		setTimeout(function(){
		        			customLoading('hide');
		        			$(location).attr('href', baseURL + 'feasibility-study');
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



    $('.mydatepicker').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight:'TRUE',
      autoclose: true,
    });

	function getAmount() {
		let total_data  = table.data().count();
		let totalAmount = 0;

		for (var i = 1; i <= total_data; i++) {
			get_nominal = $("#nominal-"+i).val();
			totalAmount += parseInt(get_nominal.replace(/\./g, ''));
	    }
		$("#amount").val(formatNumber(totalAmount));
	}

    function getSubmitter(){

	    $.ajax({
	        url   : baseURL + 'feasibility-study/api/load_data_submitter',
	        type  : "POST",
	        data  : {directorat: directorat, division: division, unit: unit},
	        dataType: "json",
	        success : function(result){      
        		let submitter_opt = '';
	        	if(result.status == true){
					data = result.data;
					if(data.length == 1){
						for(var i = 0; i < data.length; i++) {
						    obj = data[i];
						    // submitter_opt += '<option value="'+ obj.nama +'" data-jabatan="'+ obj.jabatan +'">'+ obj.nama +'</option>';
						    selected = (submitter === obj.nama) ? ' selected': '';
						    submitter_opt += '<option value="'+ obj.nama +'" data-jabatan="'+ obj.jabatan +'" '+ selected +'>'+ obj.nama +'</option>';
						}
					}else{
						submitter_opt = opt_default;
						for(var i = 0; i < data.length; i++) {
						    obj = data[i];
						    // submitter_opt += '<option value="'+ obj.nama +'" data-jabatan="'+ obj.jabatan +'">'+ obj.nama +'</option>';
						    selected = (submitter === obj.nama) ? ' selected': '';
						    submitter_opt += '<option value="'+ obj.nama +'" data-jabatan="'+ obj.jabatan +'" '+ selected +'>'+ obj.nama +'</option>';
						}
					}
		        	
	        	}
				$("#submitter").html(submitter_opt);				
				getJabatan("submitter");
	        }
	    });
	}


    function getJabatan(category){
		if(category == "submitter"){
			valJabtan = $("#submitter").find(':selected').attr('data-jabatan');
			$("#jabatan_sub").html(valJabtan);
		}
	}


    let jqXHR;

    $("#fileinput_remove").on("click", function(e) {
    	deleteFile();
		jqXHR.abort();
	    reset_proggress();
	});

    $("#attachment").bind("click", function(e) {
	    lastValue = $(this).val();
	    reset_proggress();
	}).bind("change", function(e) {
		upload = true;
		if(lastValue != ""){
			upload = false;
			if(deleteFile()){
				upload = true;
			}
		}
		if($(this).val() != ""){

			fileSize = this.files[0].size;
			fileSize = fileSize/1000
			j=0;

			if(fileSize > 5800){
				upload = false;
				alert('Max file size 5MB');
				$(this).val(lastValue);
			}

	        extension_allow = ['pdf'];
	        extension       = this.files[0].name.split('.').pop().toLowerCase();
	        if (extension_allow.indexOf(extension) < 0) {
				upload = false;
				alert('Extention not allowed');
				$(this).val(lastValue);
	        }

			if(upload){
				$("#progress").parent().removeClass('d-none');
            
				file = $('#attachment')[0].files[0];
				formData = new FormData();
				formData.append('file', file);

		        jqXHR  = $.ajax({
		            url: baseURL + "upload/do_upload",
		            type: 'POST',
		            data: formData,
		            cache: false,
		            dataType: 'json',
		          	enctype   : 'multipart/form-data',
		            contentType: false,
		            processData: false,
		            xhr: function() {
		                var xhr = new window.XMLHttpRequest();
		                xhr.upload.addEventListener("progress", function(evt) {
		                    if (evt.lengthComputable) {
		                        percentage = Math.round((evt.loaded / evt.total) * 100);
		                        if(percentage == 100){
		                        	percentage = 99;
		                        }
				                $("#progress").css('width', percentage+'%');
				                $("#progress").html(percentage+'%');
				                
		                    }
		                }, false);
		                return xhr;
		            },
		            success: function(result) {
	                	$("#progress").css('width', '100%');
		                if(result.status == true){
			                $("#progress").html('100%');
		                	setTimeout(function(){
				                $("#progress").html('Complete!');
				                $("#progress").removeClass('progress-bar-info');
				                $("#progress").addClass('progress-bar-success');
			               		attachment_file = result.messages;
							}, 1000);
		                }else{
		                	setTimeout(function(){
				                $("#progress").html(result.messages);
				                $("#progress").removeClass('progress-bar-info');
				                $("#progress").addClass('progress-bar-danger');
							}, 500);
		                }
		            },
			      error: function (xhr, ajaxOptions, thrownError) {
			      	$("#progress").parent().addClass('d-none');
                	$("#progress").css('width', '100%');
	                $("#progress").removeClass('progress-bar-info');
                	setTimeout(function(){
		                $("#progress").addClass('progress-bar-danger');
			      		$("#progress").parent().removeClass('d-none');
		                $("#progress").html('Error Connection');
					}, 200);
			      }
		        });
		        return false;

			}
            
		}
	});


	function reset_proggress(){
      $('#file_input').val("");
      $("#delete_file").addClass('d-none');
      $("#progress").removeClass("progress-bar-success");
      $("#progress").removeClass("progress-bar-danger");
      $("#progress").parent().addClass('d-none');
      $("#progress").css('width', '0%');
      $("#progress").html('0%');
    }

	function deleteFile(){
		$.ajax({
	        url   : baseURL + 'upload/delete',
	        type  : "POST",
	        data  : {file: attachment_file},
	        dataType: "json",
	        success : function(result){
	        	attachment_file = '';
	        	return true;
	        }
	    });

	    return true;
	}
	
  });
</script>