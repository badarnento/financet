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
		<div class="col-sm-7">
			<div class="form-group m-b-10">
	            <label for="fs_name" class="col-sm-3 control-label text-left">Justification Name <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	              <input type="text" class="form-control input-sm" id="fs_name" placeholder="Name">
	            </div>
	        </div>
			<div class="form-group m-b-10">
	            <label for="fs_description" class="col-sm-3 control-label text-left">Description <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	              <textarea class="form-control input-sm" id="fs_description" rows="2"></textarea>
	            </div>
	        </div>
			<div class="form-group m-b-10">
	            <label for="currency" class="col-sm-3 control-label text-left">Currency <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	            	<?php
		            	$currency = get_currency();
	            	?>
					<select name="currency" id="currency" class="form-control input-sm">
						<?php foreach ($currency as $key => $value) : ?>
							<option value="<?= $key ?>"><?= $value ?></option>
						<?php endforeach; ?>
					</select>
	            </div>
	        </div>
			<div class="form-group m-b-10 currency_display d-none">
	            <label for="rate" class="col-sm-3 control-label text-left">Rate <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	              <input type="text" class="form-control input-sm money-format" id="rate" placeholder="Rate" value="0">
	            </div>
	        </div>
	        <div class="form-group m-b-10">
	            <label for="amount" class="col-sm-3 control-label text-left">Total Amount <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	              <input type="TEXT" class="form-control input-sm" id="amount" value="0" readonly>
	            </div>
	        </div>
	        <div class="form-group m-b-10">
	            <label for="directorat" class="col-sm-3 control-label text-left">Directorate <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	            	<select class="form-control input-sm" id="directorat">
	            		<?php
							$id_directorat   = $this->session->userdata('id_dir_code');
							$directorat_name = get_directorat($id_directorat);

	            			if(!$su_budget && $id_directorat > 0 && $binding == true):
            					echo '<option value="'.$id_directorat.'" data-name="'.$directorat_name.'">'.$directorat_name.'</option>';
	            			else:

	            				$opt_dir = '<option value="0">-- Choose --</option>';

	            				$last_dir = "";

	            				foreach($directorat as $value):

									$id_dir_code = $value['ID_DIR_CODE'];
									$dir_name  = $value['DIRECTORAT_NAME'];

									if($dir_name != $last_dir):

	            						$opt_dir .= '<option value="'.$id_dir_code.'" data-name="'.$dir_name.'">'.$dir_name.'</option>';
	            					endif;
	            					$last_dir = $dir_name;

	            				endforeach;

	            				echo $opt_dir;

	            			endif;

	            		?>
	            	</select>
	            </div>
	        </div>
	        <div class="form-group m-b-10">
	            <label for="division" class="col-sm-3 control-label text-left">Division <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	            	<select class="form-control input-sm" id="division">
			          <?php
			            if($binding == false || $data_binding['division'] == false):
			              echo '<option value="0">-- Choose --</option>';
			            else:
			            
			              foreach($data_binding['division'] as $value):
			                $replace = str_replace("&","|AND|", $value['DIVISION_NAME']);
			          ?>
			              <option value="<?= $value['ID_DIVISION'] ?>" data-name="<?= $value['DIVISION_NAME'] ?>"><?= $value['DIVISION_NAME'] ?></option>
			          <?php
			              endforeach; 
			            endif;
			          ?>
		            </select>
	            </div>
	        </div>
	        <div class="form-group m-b-10">
	            <label for="unit" class="col-sm-3 control-label text-left">Unit <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	            	<select class="form-control input-sm" id="unit">
			            <?php
			              if($binding == false || $data_binding['unit'] == false):
			                echo '<option value="0">-- Choose --</option>';
			              else:
			              
			                foreach($data_binding['unit'] as $value):
			                  $replace = str_replace("&","|AND|", $value['UNIT_NAME']);

			                  if($data_binding['division'][0]['ID_DIVISION'] == $value['ID_DIVISION']):
			            ?>
			                <option value="<?= $value['ID_UNIT'] ?>" data-unt="<?= $replace ?>" data-name="<?= $value['UNIT_NAME'] ?>"><?= $value['UNIT_NAME'] ?></option>
			              <?php endif; ?>
			            <?php
			                endforeach; 
			              endif;
			            ?>
		            </select>
	            </div>
	        </div>
	        <div class="form-group m-b-10 d-none" id="district-area">
	            <label for="district" class="col-sm-3 control-label text-left">District Area <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	            	<select class="form-control input-sm" id="district">
			             <option value="0">-- Choose --</option>
		            </select>
	            </div>
	        </div>
      </div>
      <div class="col-sm-5">
          <div class="form-group m-b-10">
          	<label for="fs_date" class="col-sm-3 control-label text-left">Date <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-6">
				<div class="input-group">
					<input type="text" class="form-control input-sm mydatepicker" id="fs_date" placeholder="dd/mm/yyyy" value="<?= date("d-m-Y")?>">
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
  <div class="white-box boxshadow m-b-0 border-top-only-5 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">Rkap Detail</h5>
    </div>
  </div>
  <div class="white-box boxshadow border-bottom-only-5">
	<div class="row">
		<div class="col-md-12">
	      <table id="table_data" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small w-full">
	        <thead>
	        	<tr>
					<th class="text-center">No</th>
					<th class="text-center">Tribe/Usecase</th>
					<th class="text-center">RKAP Name</th>
					<th class="text-center">Program ID</th>
					<th class="text-center">Proc Type Real</th>
					<th class="text-center">Proc Type</th>
					<th class="text-center">Proc Type Description</th>
					<th class="text-center">Description</th>
					<th class="text-center">Period Start</th>
					<th class="text-center">Period End</th>
					<th class="text-center">FA RKAP</th>
					<th id="currency_text" class="text-center currency_text">Nominal Currency</th>
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
      	<form class="form-horizontal" id="form-submitter">
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
		  	       
	        	</div>
	        </div>
	      	<div class="row">
	      		<div class="col-md-8">
		      		<div class="form-group m-b-10">
		  	        	<div class="attachment_group">
			  	        	<label class="col-sm-3 control-label text-left">Document <span class="pull-right">:</span></label>
				            <div class="col-sm-7">
				                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
				                    <div id="fileinput" class="form-control input-sm" data-trigger="fileinput"> <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div><span class="input-group-addon btn btn-default btn-outline btn-file"> <span class="fileinput-new">Select file</span> <span class="fileinput-exists">Change</span>
				                    <input id="attachment" type="file" name="attachment" accept=".pdf" required="required"> </span> <a id="fileinput_remove" href="javascript:void(0)" class="input-group-addon btn btn-warning fileinput-exists" data-dismiss="fileinput">Remove</a>
				                </div>
			                    <div class="progress progress-lg d-none">
			                        <div id="progress" class="progress-bar progress-bar-info active progress-bar-striped" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%" role="progressbar"></div>
			                    </div>
							</div>
			            </div>
		            	<!-- <div class="col-sm-offset-3 col-sm-7">
		            		<button type="button" id="addFile" class="btn btn-info btn-xs px-5 pull-right"><i class="fa fa-plus"></i> Add New </button>
		            	</div> -->
			        </div>
		        </div>
	  	    </div>

      	</form>
      	<!-- <div class="row">
      		<div class="col-md-12">
      			        <div class="form-group m-b-10">
      			        	<label for="submitter" class="control-label text-left">Attachment</label>
      		            	<p class="text-muted small"> For multiple file upload</p>
      			      		<form action="#" class="dropzone">
      			                <div class="fallback">
      			                    <input name="file" type="file" multiple />
      			                </div>
      			            </form>
      			</div>
      		</div>
      	</div> -->
      	<div class="row">
      		<div class="col-sm-6 col-sm-offset-6">
		        <div class="form-group pull-right">
		        	<button type="button" id="save_data" class="btn btn-info border-radius-5 m-10 w-150p"><i class="fa fa-save"></i> Save </button>
		        </div>
      		</div>
      	</div>
  </div>
</div>
<?php
	$all_unit = array();
	if($binding == false || $data_binding['unit'] == false):
	else:
	  $all_unit = array();
	  foreach($data_binding['unit'] as $value):
	    $all_unit[] = $value['ID_UNIT'];
	  endforeach;
	endif;
	$all_unit = json_encode($all_unit);
?>
<script>
  $(document).ready(function(){
	let counter       = 1;
	let district      = 0;
	let checkArea     = false;
	const opt_default = '<option value="0" data-name="">-- Choose --</option>';
	let directorat    = $("#directorat").val();
	let lastRkap      = 0;
	let counter_file  = counter;

	let attachment_file = "";

	const SU_BUDGET = <?= $su_budget_js  ?>;
	const binding = <?= ($binding) ? "'".$binding."'" : 'false' ?>;

	const enable_old_rkap = <?= ($enable_old_rkap) ? 'true' : 'false' ?>;
  	let  all_unit = <?= $all_unit ?>;

	if(binding != false){
	    if(binding == 'directorat'){
	      getDivision();
	    }
	    if(binding == 'division'){
	      getUnit();
	    }
        if(binding == 'unit'){
          setTimeout(function(){
          	getTribe(1);
			getSubmitter();
			resetElm();
          }, 1000);
        }

	}

	if( $("#division").val() > 0){
		setTimeout(function(){
		  	division_name = $("#division").find(':selected').attr('data-name').toLowerCase();
				if(division_name == "sharia" || division_name == "expansion area"){
		  		$("#district-area").removeClass('d-none');
		  		checkArea = true;
		  		getDistrict();
				}else{
		  		$("#district-area").addClass('d-none');
				}
		}, 300);
	}


	const YEAR  = '<?= date("Y")?>;'
	const MONTH = '<?= date("m")?>;'
	const DATE  = '<?= date("d-m-Y") ?>'
	
	let buttonAdd = '<button type="button" id="addRow" class="btn btn-success btn-sm m-l-5 mr-5"><i class="fa fa-plus"></i> Add New </button>';
		buttonAdd += '<button type="button" id="deleteRow" class="btn btn-danger btn-sm m-l-5 mr-5" disabled><i class="fa fa-trash"></i> Delete </button>';
	let tribe_opt = '<div class="form-group m-b-0"><select id="tribe_opt-' + counter + '" data-id="' + counter + '" class="form-control input-sm tribe_opt select-center">';
		tribe_opt += opt_default;
		tribe_opt += '/<select></div>';
	let rkap_opt  = '<div class="form-group m-b-0"><select id="rkap_opt-' + counter + '" data-id="' + counter + '" class="form-control input-sm rkap_opt select-center select2">';
		rkap_opt += opt_default;
		rkap_opt += '</select></div>';
	let tier_opt  = '<div class="form-group m-b-0"><select id="tier_opt-' + counter + '" data-id="' + counter + '" class="form-control input-sm tier_opt select-center">';
		tier_opt += opt_default;
		tier_opt += '</select></div>';
	let proc_type_real = '<div class="form-group m-b-0"><input id="proc_type_real-' + counter + '" data-id="' + counter + '" class="form-control input-sm text-center proc_type_real" readonly></div>';
	let proc_opt  = '<div class="form-group m-b-0"><select id="proc_opt-' + counter + '" data-id="' + counter + '" class="form-control input-sm proc_opt select-center">';
		proc_opt += opt_default;
		proc_opt += '</select></div>';
	let proc_desc = '<div class="form-group m-b-0"><input id="proc_desc-' + counter + '" data-id="' + counter + '" class="form-control input-sm proc_desc"></div>';
	let line_name = '<div class="form-group m-b-0"><input id="line_name-' + counter + '" data-id="' + counter + '" class="form-control input-sm line_name" ></div>';
	let period_start = '<div class="form-group m-b-0"><input id="period_start-' + counter +'" data-id="' + counter +'" class="form-control input-sm period_start date_period" value=""></div>';
	let period_end = '<div class="form-group m-b-0"><input id="period_end-' + counter +'" data-id="' + counter +'" class="form-control input-sm period_end date_period" value=""></div>';
	let fund_av   = '<div class="form-group m-b-0"><input id="fund_av-' + counter + '" data-id="' + counter + '" class="form-control input-sm fund_av text-right" data-real="0" value="0" readonly></div>';
	let nominal_currency   = '<div class="form-group m-b-0"><input id="nominal_currency-' + counter + '" data-id="' + counter + '" class="form-control input-sm nominal_currency text-right money-format" value="0"></div>';
	let nominal   = '<div class="form-group m-b-0"><input id="nominal-' + counter + '" data-id="' + counter + '" class="form-control input-sm nominal text-right money-format" value="0"></div>';

    let table = $('#table_data').DataTable({
	    "data":[{
					"no": counter,
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
	    		}],
	    "columns":[
	    			{"data": "no", "width": "40px", "class": "text-center" },
	    			{"data": "tribe", "width": "150px", "class": "p-2" },
	    			{"data": "rkap_name", "width": "300px", "class": "p-2" },
	    			{"data": "tier", "width": "200px", "class": "p-2" },
	    			{"data": "proc_type_real", "width": "150px", "class": "p-2 text-center" },
	    			{"data": "proc_type", "width": "150px", "class": "p-2" },
	    			{"data": "proc_desc", "width": "200px", "class": "p-2" },
	    			{"data": "line_name", "width": "200px", "class": "p-2" },
	    			{"data": "period_start", "width": "150px", "class": "p-2" },
	    			{"data": "period_end", "width": "150px", "class": "p-2" },
	    			{"data": "fund_available", "width": "150px", "class": "p-2" },
	    			{"data": "nominal_currency", "width": "150px", "class": "p-2" },
	    			{"data": "nominal", "width": "150px", "class": "p-2" }
	    		],
		"columnDefs": [
            {
                "targets": [ 11 ],
                "visible": false
            }
        ],
          "drawCallback": function ( settings ) {
          	// $(".select2").select2();
          },
		"ordering"        : false,
		"scrollY"         : 480,
		"scrollX"         : true,
		"scrollCollapse"  : true,
		"paging" 		  : false
	});


	$('#table_data tbody tr').eq(counter-1).addClass('selected');
	$('#table_data_filter').html(buttonAdd);

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
			
			setTimeout(function(){
				getAmount();
			}, 300);
		}

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
/*
	$('#addFile').on( 'click', function () {

		counter_file++;

		if(counter_file > 2){
			$(this).attr('disabled', true);
		}

		let attachment_added = '<div id="attachment_added-'+counter_file+'" class="row">'
	  	        	+'<label class="col-sm-2 control-label text-left">&nbsp;</label>'
		            +'<div class="col-sm-offse-2 col-sm-7">'
		                +'<div class="fileinput fileinput-new input-group" data-provides="fileinput">'
		                    +'<div id="fileinput" class="form-control" data-trigger="fileinput"> <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div><span class="input-group-addon btn btn-default btn-file"> <span class="fileinput-new">Select file</span> <span class="fileinput-exists">Change</span>'
		                    +'<input id="attachment-'+counter_file+'" type="file" name="attachment" accept=".pdf,.doc,.docx" required="required"> </span> <a id="fileinput_remove" href="javascript:void(0)" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>'
		                +'</div>'
	                    +'<div class="progress-'+counter_file+' progress-lg d-none">'
	                        +'<div id="progress-'+counter_file+'" class="progress-bar progress-bar-info active progress-bar-striped" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%" role="progressbar"></div>'
	                    +'</div>'
		            +'</div>'
		            +'<div class="col-sm-2 p-0">'
		            	+'<label class="control-label font-size-18"><a href="javascript:void(0)" class="text-danger delete-file" data-id="'+counter_file+'"><i class="fa fa-times"></i></a></label>'
		            +'</div>'
	            +'</div>';

        $(".attachment_group").append(attachment_added);
	});

	$(".delete-file").on( 'click', function () {
		console.log('clicked');
	});*/


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
			let period_start = '<div class="form-group m-b-0"><input id="period_start-' + i +'" data-id="' + i +'" class="form-control input-sm period_start date_period" value=""></div>';
			let period_end = '<div class="form-group m-b-0"><input id="period_end-' + i +'" data-id="' + i +'" class="form-control input-sm period_end date_period" value=""></div>';
			let fund_av   = '<div class="form-group m-b-0"><input id="fund_av-' + i + '" data-id="' + i + '" class="form-control input-sm fund_av text-right" data-real="0" value="0" readonly></div>';
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

	function resetElm(){
		lastRkap = 0;
		$(".tribe_opt").html(opt_default);
		$(".rkap_opt").html(opt_default);
		$(".tier_opt").html(opt_default);
		$(".proc_type_real").html(opt_default);
		$(".proc_opt").html(opt_default);
		$(".proc_desc").html(opt_default);
		$(".line_name").val('');
		$(".period_start").val('');
		$(".period_end").val('');
		$(".fund_av").val('0');
		$(".nominal_currency").val('0');
		$(".nominal").val('0');
	}


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
			counter = 1;
			if(total_row > 1){
				table.clear().draw();
		    	resetRow(counter);
			}

	  		directorat = $(this).val();

	  		if(directorat > 0){
	  			getDivision();
	  		}else{
	  			$("#division").html(opt_default);
	  		}

			$("#unit").html(opt_default);
			resetElm();
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
			counter = 1;
			if(total_row > 1){
				table.clear().draw();
		    	resetRow(counter);
			}

  		division = $(this).val();
			division_name = $(this).find(':selected').attr('data-name').toLowerCase();

			if(division_name == "sharia" || division_name == "expansion area"){
	  		$("#district-area").removeClass('d-none');
	  		getDistrict();
			}else{
	  		$("#district-area").addClass('d-none');
			}

	  		if(division != "0"){
	  			getUnit();
	  		}else{
	  			$("#unit").html(opt_default);
	  		}

			resetElm();

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
			counter = 1;
			if(total_row > 1){
				table.clear().draw();
		    	resetRow(counter);
			}

	  		unit = $(this).val();

	  		if(unit != "0"){
				getTribe(1);
	  		}
			getSubmitter();
			resetElm();
		}
	});


	$('#table_data tbody').on('change', 'tr td select.tribe_opt', function () {
		id_row = $(this).data('id');
		tribe = $(this).val();

  		getRkapLine(tribe, id_row);
  		$("#line_name-"+id_row).val('');
  		$("#fund_av-"+id_row).val('0');
  		$("#nominal-"+id_row).val('0');
    });

	// Belum bikin validasi kalau ingin rubah rkapline di row pertama, namun didata kedua rkapnya sudah dipilih
	// $('#table_data tbody').bind('click', 'tr td select.rkap_opt', function () {

	$('#table_data tbody').on('click', 'tr td select.rkap_opt', function () {
		lastRkap = $(this).val();
    });
	$('#table_data tbody').on('change', 'tr td select.rkap_opt', function () {
		id_row  = $(this).data('id');
		id_rkap = $(this).val();
		proc    = $(this).find(':selected').attr('data-proc');
		disb    = $(this).find(':selected').attr('data-disabled');
		cadangan    = $(this).find(':selected').attr('data-cadangan');


		if(cadangan == 1){
			// table.columns( 10 ).visible(false);
			// $("#fund_av-"+id_row).val('hidden');
		}
		/*if(SU_BUDGET == false){
			if(disb == "1"){
				$(this).val(lastRkap);
				alert("RKAP sudah di luar Periode/Quartal");
	            $('#rkap_opt-'+id_row).select2('val', lastRkap);
	            return false;
			}
		}*/
		getTier(id_rkap, id_row);
		getProcType(proc, id_row);
		getFundAv(id_rkap, id_row, cadangan);
		$("#proc_type_real-"+id_row).val(proc);
		$("#nominal-"+id_row).val('0');
    });

	$('#table_data tbody').on('change', 'tr td select.tier_opt', function () {
		id_row  = $(this).data('id');
		id_rkap = $(this).val();
		$("#nominal-"+id_row).val('0');
    });

	/*$('#table_data tbody').on('change', 'tr td select.proc_opt', function () {
		id_row    = $(this).data('id');
		this_proc = $(this).val();

		if(proc.toLowerCase() != "procurement - penunjukan langsung"){
			is_dpl = true;
		}
    });*/

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

		getAmount();
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
    });



    $('#table_data tbody').on('focus', 'tr td input.date_period', function () {
    	$(this).datepicker({
			format: 'dd-mm-yyyy',
			todayHighlight:'TRUE',
			autoclose: true,
			minDate:0,
	    });
    });

	$('#deleteRow').on( 'click', function () {

		row_now    = $('#table_data tbody tr.selected');
		get_table  = table.row( row_now );
		index      = get_table.index()
		data       = get_table.data();

		total_data  = table.data().count();
		counter--;

		if(total_data > 0){
			table.row(index).remove().draw();

			table.column( 0 ).data().each( function ( value, i ) {
				num = i+1;

				$('#tribe_opt-'+value).attr("id", 'tribe_opt-'+num);
				$('#rkap_opt-'+value).attr("id", 'rkap_opt-'+num);
				$('#tier_opt-'+value).attr("id", 'tier_opt-'+num);
				$('#proc_type_real-'+value).attr("id", 'proc_type_real-'+num);
				$('#proc_opt-'+value).attr("id", 'proc_opt-'+num);
				$('#proc_desc-'+value).attr("id", 'proc_desc-'+num);
				$('#line_name-'+value).attr("id", 'line_name-'+num);
				$('#fund_av-'+value).attr("id", 'fund_av-'+num);
				$('#nominal_currency-'+value).attr("id", 'nominal_currency-'+num);
				$('#nominal-'+value).attr("id", 'nominal-'+num);

				$('#table_data tbody tr:eq(' + i + ') td:eq(0)').html(num);
			});

			$(this).attr('disabled', true);

			setTimeout(function(){
				getAmount();
			}, 200);
		}

    });


	$("#currency").on("change", function(){
		currency_val = $(this).val();
		if($(this).val() != "IDR"){
			$(".currency_display").removeClass('d-none');
			$(".nominal").attr('readonly', true);
			table.columns( [11] ).visible(true);
			setTimeout(function(){
				$("#currency_text").html('Nominal ' + currency_val);
			}, 100);

		}
		else{
			$("#currency_text").html('Nominal Currency');
			$(".currency_display").addClass('d-none');
			$(".nominal").attr('readonly', false);
			$(".nominal").val('0');
			table.columns( [11] ).visible(false);
		}
    });


	$("#submitter").on("change", function(){
		getJabatan("submitter");
    });


	$("#save_data").on('click', function () {

		getAmount();
		
		let directorat = $("#directorat").val();
		let division   = $("#division").val();
		let unit       = $("#unit").val();
		let district   = $("#district").val();

		
		let fs_name        = $("#fs_name").val();
		let fs_description = $("#fs_description").val();
		let currency       = $("#currency").val();
		let rate           = parseInt($("#rate").val().replace(/\./g, ''));
		let fs_date        = $("#fs_date").val();
		let amount         = parseInt($("#amount").val().replace(/\./g, ''));
		let total_data     = table.data().count();
		
		let submitter      = $("#submitter").val();
		let jabatan_sub    = $("#jabatan_sub").html();
		let is_dpl = false;
		let fatalError = false;
		let fatalList0 = '';
		let fatalList1 = '';
		let fatalList2 = '';
		let fatalList3 = '';
		let fatalList4 = '';
	    for (var i = 1; i <= total_data; i++) {
			fund_av            = $("#fund_av-"+i).val();
			fund_av_real       = $("#fund_av-"+i).attr('data-real');
			nominal_val        = $("#nominal-"+i).val();
			proc_type_real_val = $("#proc_type_real-"+i).val();
			proc_opt_val       = $("#proc_opt-"+i).val();
			period_start_val   = $("#period_start-"+i).val();
			period_end_val     = $("#period_end-"+i).val();
			proc_desc_val      = $("#proc_desc-"+i).val();

			cek_cadangan = $("#rkap_opt-"+i).find(':selected').attr('data-cadangan') ==  "1"
		
			if (proc_opt_val == "0"){
				fatalError =  true;
				if(fatalList0 == ""){
					fatalList0 = i;
				}else{
					fatalList0 += ', ' +i;
				}
			}
			if (/*proc_type_real_val != proc_opt_val && */proc_desc_val == ""){
				fatalError =  true;
				if(fatalList1 == ""){
					fatalList1 = i;
				}else{
					fatalList1 += ', ' +i;
				}
			}

			if (period_start_val == "" || period_end_val == ""){
				fatalError =  true;
				if(fatalList4 == ""){
					fatalList4 = i;
				}else{
					fatalList4 += ', ' +i;
				}
			}


			if(parseInt(nominal_val.replace(/\./g, '')) > parseInt(fund_av_real.replace(/\./g, ''))){
				fatalError =  true;
				if(fatalList2 == ""){
					fatalList2 = i;
				}else{
					fatalList2 += ', ' +i;
				}
			}
		

			if(parseInt(nominal_val.replace(/\./g, '')) < 1){
				fatalError =  true;
				if(fatalList3 == ""){
					fatalList3 = i;
				}else{
					fatalList3 += ', ' +i;
				}
			}


	    }

		if(unit == "" || fs_name == "" || amount == "0" || submitter == ""){
    		customNotif('Warning', "Please fill all field", 'warning');
		}
		else if(checkArea == true && district == "0"){
    		customNotif('Warning', "Please fill district area", 'warning');
		}
		else if(fatalError == true){
			if(fatalList0 != ""){
				customNotif('Warning', 'Isi Proct Type pada data ke '+fatalList0+'!', 'warning');
			}
			if(fatalList1 != ""){
				customNotif('Warning', 'Isi Proct Type Desc pada data ke '+fatalList1+'!', 'warning');
			}
			if(fatalList2 != ""){
				customNotif('Warning', 'Nilai nominal lebih dari FA RKAP pada data ke '+fatalList2+'!', 'warning');
			}
			if(fatalList3 != ""){
				customNotif('Warning', 'Nilai nominal masih kosong pada data ke '+fatalList3+'!', 'warning');
			}
			if(fatalList4 != ""){
				customNotif('Warning', 'Isi Period Program Start dan Period Program End '+fatalList4+'!', 'warning');
			}

		}
		else if(attachment_file == ""){
			customNotif('Warning', 'Upload Document Attachment!', 'warning');
		}
		else{

			let data_lines  = [];
			let fraud = false;
			let risk = false;
			let is_cadangan = false;
			table.rows().eq(0).each( function ( index ) {
	    	j = index+1;
				data                 = table.row( index ).data();
				rkap_opt_val         = $("#rkap_opt-"+j).val();
				proc_opt_val         = $("#proc_opt-"+j).val();
				proc_desc_val        = $("#proc_desc-"+j).val();
				line_name_val        = $("#line_name-"+j).val();
				period_start_val     = $("#period_start-"+j).val();
				period_end_val       = $("#period_end-"+j).val();
				fund_av              = $("#fund_av-"+j).val();
				fund_av_real       = $("#fund_av-"+j).attr('data-real');
				nominal_currency_val = ($("#nominal_currency-"+j).val() == null) ? 0 : parseInt($("#nominal_currency-"+j).val().replace(/\./g, ''));
				nominal_val          = $("#nominal-"+j).val();

				if(proc_opt_val.toLowerCase() == "procurement - penunjukan langsung"){
					is_dpl = true;
				}

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
				if($("#rkap_opt-"+j).find(':selected').attr('data-cadangan') ==  "1"){
					is_cadangan = true;
				}

				if(rkap_opt_val != 0 && parseInt(fund_av_real.replace(/\./g, '')) > 0 && parseInt(nominal_val.replace(/\./g, '')) > 0){
		    		data_lines.push({'id_rkap' : rkap_opt_val, 'proc_type' : proc_opt_val,  'proc_desc' : proc_desc_val,  'line_name' : line_name_val,  'period_start' : period_start_val,  'period_end' : period_end_val, 'nominal_currency' : nominal_currency_val, 'nominal' : parseInt(nominal_val.replace(/\./g, ''))});
				}
			});

			dpl = (is_dpl) ? '1':'0';

		    data = {
					directorat : directorat,
					division : division,
					unit : unit,
					district : district,
					fs_name : fs_name,
					fs_description : fs_description,
					currency : currency,
					rate : rate,
					fs_date : fs_date,
					amount : amount,
					data_line : data_lines,
					submitter : submitter,
					jabatan_sub : jabatan_sub,
					fraud : fraud,
					risk : risk,
					is_cadangan : is_cadangan,
					is_dpl : dpl,
					attachment : attachment_file
	    		}

		    $.ajax({
		        url   : baseURL + 'feasibility-study/api_save',
		        type  : "POST",
		        data  : data,
		        dataType: "json",
		        beforeSend  : function(){
                          customLoading('show');
                        },
		        success : function(result){
		        	if(result.status == true){
		        		customNotif('Success', "Justification CREATED", 'success');
		        		setTimeout(function(){
		        			customLoading('hide');
		        			if(is_dpl){
		        				$(location).attr('href', baseURL + 'dpl/form-create');
		        			}else{
		        				$(location).attr('href', baseURL + 'feasibility-study');
		        			}
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

    $('#fs_date').datepicker({
		format: 'dd-mm-yyyy',
		todayHighlight:'TRUE',
		endDate: new Date(),
		autoclose: true,
    });


    function getDivision() {

		let directorat    = $("#directorat").find(':selected').attr('data-name');

		$("#division").attr('disabled', true);
		$("#division").css('cursor', 'wait');

	    $.ajax({
	        url   : baseURL + 'api-budget/load_data_rkap_view',
	        type  : "POST",
	        data  : {category : "division", directorat : directorat},
	        dataType: "json",
	        success : function(result){
        		let division_opt = opt_default;
	        	if(result.status == true){
					data = result.data;
		        	for(var i = 0; i < data.length; i++) {
					    obj = data[i];
					    division_opt += '<option value="'+ obj.id_division +'" data-name="'+ obj.division +'">'+ obj.division +'</option>';
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
	        url   : baseURL + 'api-budget/load_data_rkap_view',
	        type  : "POST",
	        data  : {category : "unit", directorat : directorat, division : division},
	        dataType: "json",
	        success : function(result){
        		let unit = opt_default;
	        	if(result.status == true){
					data = result.data;
		        	for(var i = 0; i < data.length; i++) {
					    obj = data[i];
	                  if(all_unit.length > 0){
		                  if(all_unit.indexOf(obj.id_unit) != -1){
					    	unit += '<option value="'+ obj.id_unit +'" data-name ="'+ obj.unit +'">'+ obj.unit +'</option>';
		                  }
	                  }
	                  else{
				    	unit += '<option value="'+ obj.id_unit +'" data-name ="'+ obj.unit +'">'+ obj.unit +'</option>';
	                  }
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
	        url   : baseURL + 'api-budget/load_data_rkap_view',
	        type  : "POST",
	        data  : {category : "tribe", directorat : directorat, division : division, unit : unit},
	        dataType: "json",
	        success : function(result){
        		let tribe_opt = '';
	        	if(result.status == true){
					data = result.data;
					if(data.length > 1){
						tribe_opt = opt_default;
					}
		        	for(var i = 0; i < data.length; i++) {
					    obj = data[i];
					    tribe_opt += '<option value="'+ obj.tribe +'">'+ obj.tribe +'</option>';
					}
	        	}
				$("#tribe_opt-"+id_row).html(tribe_opt);
				$("#tribe_opt-"+id_row).attr('disabled', false);
				$("#tribe_opt-"+id_row).css('cursor', 'default');
				getRkapLine($("#tribe_opt-"+id_row).val(), id_row);

			    if(SU_BUDGET == true){
			    	setTimeout(function(){
						$("#tribe_opt-"+id_row).select2();
			    	}, 500);
			    }
	        }
	    });
	}


    function getRkapLine(tribe, id_row) {

		let directorat   = $("#directorat").find(':selected').attr('data-name');
		let division     = $("#division").find(':selected').attr('data-name');
		let unit         = $("#unit").find(':selected').attr('data-name');
		let exclude_rkap = [];

		$("#rkap_opt-"+id_row).attr('disabled', true);
		$("#rkap_opt-"+id_row).css('cursor', 'wait');

	    $.ajax({
	        url   : baseURL + 'api-budget/load_data_rkap/',
	        type  : "POST",
	        data  : {directorat : directorat, division : division, unit : unit, tribe : tribe, exclude_rkap : exclude_rkap},
	        dataType: "json",
	        success : function(result){
        		let rkap_opt = opt_default;
        		let disabled_rkap = '';
        		let disabled_rkap2 = '';
	        	if(result.status == true){
					data = result.data;
		        	for(var i = 0; i < data.length; i++) {
					    obj = data[i];

					    proc = obj.proc_type;
				    	disabled_rkap = (obj.disabled == true) ? '1' : '0';
				    	disabled_rkap2 = (obj.disabled == true && enable_old_rkap == false) ? ' disabled' : '';

					    if(SU_BUDGET == true){
				    		rkap_opt += '<option value="'+ obj.id_rkap_line +'" data-cadangan="'+obj.dana_cadangan+'" data-proc="' + proc + '" data-disabled="' + disabled_rkap + '"'+disabled_rkap2+'>'+ obj.rkap_name +'</option>';
					    }
					    else{
					    	// if(proc.toLowerCase() != "procurement - penunjukan langsung"){
					    		rkap_opt += '<option value="'+ obj.id_rkap_line +'" data-cadangan="'+obj.dana_cadangan+'" data-proc="' + proc + '" data-disabled="' + disabled_rkap + '"'+disabled_rkap2+'>'+ obj.rkap_name +'</option>';
					    	// }
					    }
					}
	        	}
				$("#rkap_opt-"+id_row).html(rkap_opt);
				$("#rkap_opt-"+id_row).attr('disabled', false);
				$("#rkap_opt-"+id_row).css('cursor', 'default');
				$("#rkap_opt-"+id_row).css('cursor', 'default');

				setTimeout(function(){
					$("#rkap_opt-"+id_row).select2();
		    	}, 500);

	        }
	    });
	}

	function getTier(id_rkap, id_row){
	    $.ajax({
	        url   : baseURL + 'api-budget/load_data_tier_from_header',
	        type  : "POST",
	        data  : {id_rkap : id_rkap},
	        dataType: "json",
	        success : function(result){
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
					    	// if(proc.toLowerCase() != "procurement - penunjukan langsung"){
					    		proc_opt += '<option value="'+ proc +'"' + proc_select + '>'+ proc +'</option>';
					    	// }
					    }
					}
	        	}
				$("#proc_opt-"+id_row).html(proc_opt);
				$("#proc_opt-"+id_row).attr('disabled', false);
				$("#proc_opt-"+id_row).css('cursor', 'default');
	        }
	    });
	}

	function getDistrict(){
		    $.ajax({
		        url   : baseURL + 'api-budget/load_data_district',
		        type  : "POST",
		        dataType: "json",
		        success : function(result){
							let district = opt_default;
							let data = result.data;
		        	if(result.status == true){
			        	for(var i = 0; i < data.length; i++) {
							    obj = data[i];
					    		district += '<option value="'+ obj.id_district +'">'+ obj.district_name +'</option>';
								}
		        	}
							$("#district").html(district);
							$("#district").attr('disabled', false);
							$("#district").css('cursor', 'default');
				    	setTimeout(function(){
								$("#district").select2();
				    	}, 400);
		        }
		    });
		}


    function getFundAv(id_rkap, id_row, is_cadangan=0) {
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
			       if(is_cadangan == 1){
			       	$("#fund_av-"+id_row).val('hidden');
			       	$("#fund_av-"+id_row).attr('data-real', formatNumber(fund_av));
			       }else{
			       	$("#fund_av-"+id_row).val(formatNumber(fund_av));
			       	$("#fund_av-"+id_row).attr('data-real', formatNumber(fund_av));
			       }
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

    function getSubmitter(){

		let directorat = $("#directorat").val();
		let division   = $("#division").val();
		let unit       = $("#unit").val();

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
						    submitter_opt += '<option value="'+ obj.nama +'" data-jabatan="'+ obj.jabatan +'">'+ obj.nama +'</option>';
						}
					}else{
						submitter_opt = opt_default;
						for(var i = 0; i < data.length; i++) {
						    obj = data[i];
						    submitter_opt += '<option value="'+ obj.nama +'" data-jabatan="'+ obj.jabatan +'">'+ obj.nama +'</option>';
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
				alert('Max file size 5M');
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
	        	return true;
	        }
	    });

	    return true;
	}

	// $('.dropify').dropify();

  });
</script>

<!-- <style>
	select option:disabled {
		color: #ccc;
	}
</style> -->