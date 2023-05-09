<?php $this->load->view('ilustration') ?>

<div class="row">
  <div class="white-box boxshadow m-b-0 mt-10 border-top-only-5 z-index-2 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">GR Detail</h5>
    </div>
  </div>
  <div class="white-box boxshadow z-index-2 py-10 border-bottom-only-5 small">
	<form class="form-horizontal">
    <div class="row">
		<div class="col-sm-7">
			<div class="form-group m-b-10">
	            <label for="no_bast" class="col-sm-3 control-label text-left">No BAST <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	              <input type="text" class="form-control input-sm" id="no_bast" placeholder="No BAST" autocomplete="off">
	            </div>
	        </div>

	        <div class="form-group m-b-10">
	          	<label for="bast_date" class="col-sm-3 control-label text-left">BAST Date <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
					<div class="input-group">
						<input type="text" class="form-control input-sm mydatepicker" id="bast_date" placeholder="dd/mm/yyyy" value="<?= date("d-m-Y")?>">
						<span class="input-group-addon"><i class="icon-calender"></i></span>
					</div>
	            </div>
	        </div>
		
	        <div class="form-group m-b-10">
	            <label for="directorat" class="col-sm-3 control-label text-left">Directorate <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	            	<select class="form-control input-sm" id="directorat">
	            		<?php
							$id_directorat   = $this->session->userdata('id_dir_code');
							$directorat_name = get_directorat($id_directorat);

	            			if($id_directorat > 0 && $binding == true):
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
			            ?>
			                <option value="<?= $value['ID_UNIT'] ?>" data-unt="<?= $replace ?>" data-name="<?= $value['UNIT_NAME'] ?>"><?= $value['UNIT_NAME'] ?></option>
			            <?php
			                endforeach; 
			              endif;
			            ?>
		            </select>
	            </div>
	        </div>
	        <div class="form-group m-b-10">
	            <label for="fs_header" class="col-sm-3 control-label text-left">Po Number <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	            	<select class="form-control input-sm" id="fs_header">
	            		<option value="0">-- Choose --</option>
		            </select>
	            </div>
	        </div>
	        <div class="form-group m-b-10" id="vname">
	            <label for="vendor_name" class="col-sm-3 control-label text-left">Vendor Name <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	            	<label class="control-label text-left" id="vendor_name"></label>
	            </div>
	        </div>
	    </div>
	    <div class="col-sm-5">
	    	<!-- <div class="form-group m-b-10">
	          	<label for="gr_date" class="col-sm-4 control-label text-left">GR Date <span class="pull-right">:</span></label>
	            <div class="col-sm-8 col-md-6">
					<div class="input-group">
						<input type="text" class="form-control input-sm mydatepicker" id="gr_date" placeholder="dd/mm/yyyy" value="<?= date("d-m-Y")?>">
						<span class="input-group-addon"><i class="icon-calender"></i></span>
					</div>
	            </div>
	        </div> -->
	        <div class="form-group m-b-10" >
	          	<label for="category" class="col-sm-4 control-label text-left">Category <span class="pull-right">:</span></label>
	            <div class="col-sm-8 col-md-6">
					<select class="form-control input-sm" id="category">
	            		<option value="quantity" selected>By Quantity</option>
	            		<option value="amount">By Amount</option>
		            </select>
	            </div>
	        </div>
	        <div class="form-group m-b-10" style="display: none">
	            <label for="contract" class="col-sm-4 control-label text-left">Contract Identification <span class="pull-right">:</span></label>
	            <div class="col-sm-8 col-md-6">
	            	<select class="form-control input-sm" id="contract">
	            		<option value="">-- Choose --</option>
	            		<?php foreach ($contract_identification as $key => $value):?>
	            			<option value="<?= $value['CODE'] ?>"><?= $value['NAME'] ?></option>
	            		<?php endforeach;?>
		            </select>
	            </div>
	        </div>
	        <div class="form-group m-b-10"  style="display: none">
	            <label for="budget_type" class="col-sm-4 control-label text-left">Budget Type <span class="pull-right">:</span></label>
	            <div class="col-sm-8 col-md-6">
	              <!-- <input type="text" class="form-control input-sm" id="budget_type" placeholder="Budget Type" autocomplete="off">
	             -->
					<select class="form-control input-sm" id="budget_type">
	            		<option value=" ">-- Choose --</option>
	            		<option value="CAPEX">CAPEX</option>
	            		<option value="OPEX">OPEX</option>
		            </select>
	            </div>
	        </div>
	      <div class="form-group m-b-10" style="display: none">
	            <label for="project_owner" class="col-sm-4 control-label text-left">Project Ownership <span class="pull-right">:</span></label>
	            <div class="col-sm-8 col-md-6">
	              <select class="form-control input-sm" id="project_owner">
	            		<option value="DO">-- Choose --</option>
	            		<?php foreach ($project_ownership as $key => $value):?>
	            			<option value="<?= $value['CODE'] ?>"><?= $value['OWNERSHIP_NAME'] ?></option>
	            		<?php endforeach;?>
	              </select>
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
      <h5 class="font-weight-700 m-0 text-uppercase">GR Lines</h5>
    </div>
  </div>
  <div class="white-box boxshadow border-bottom-only-5">
	<div class="row">
		<div class="col-md-12">
	      <table id="table_data" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small w-full">
	        <thead>
	        	<tr>
		          	<th class="text-center">No</th>
		          	<th class="text-center">Item Name</th>
		          	<th class="text-center">Item Description</th>
		          	<th class="text-center">Qty</th>
		          	<th class="text-center">UoM</th>
		          	<th class="text-center">Unit Price</th>
		          	<th class="text-center">Total Price</th>
		     <!--      	<th class="text-center">Asset Type</th>
		          	<th class="text-center">Serial Number</th>
		          	<th class="text-center">Merek</th>
		          	<th class="text-center">Major Category</th>
		          	<th class="text-center">Minor Category</th> -->
		          	<th class="text-center">Region</th>
		          	<th class="text-center">Location</th>
		          	<th class="text-center">Project Ownership (Unit)</th>
		          	 <!-- <th class="text-center">Umur Manfaat <a id="umur_manfaat_info" target="blank"><i class="fa fa-info-circle"></i></a></th>  -->
		          	<!-- <th class="text-center">CIP</th> -->
		          	<th class="text-center">No Invoice</th>
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
	        	<div class="col-md-8">
			        <div class="form-group m-b-10">
			        	<label for="submitter" class="col-sm-3 control-label text-left">Submitter <span class="pull-right">:</span></label>
			        	<div class="col-sm-4">
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
				                    <input id="attachment" type="file" name="attachment" data-name="gr" accept=".pdf,.zip,.rar"> </span> <a id="fileinput_remove" href="javascript:void(0)" class="input-group-addon btn btn-warning fileinput-exists" data-dismiss="fileinput">Remove</a>
				                </div>
			                    <div class="progress progress-lg d-none">
			                        <div id="progress" class="progress-bar progress-bar-info active progress-bar-striped" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%" role="progressbar"></div>
			                    </div>
							</div>
			            </div>
			        </div>
		        </div>
	  	    </div>
      	</form>
  </div>
</div>

<div class="row">
  <div class="white-box border-radius-5 small">
      	<!-- <form class="form-horizontal" id="form-submitter2"> -->
	      	<div class="row">
	      		<div class="col-sm-6 col-sm-offset-6">
			        <div class="form-group pull-right">
			        	<button type="button" id="save_data" class="btn btn-info border-radius-5 m-10 w-150p"><i class="fa fa-save"></i> Save </button>
			        </div>
	      		</div>
	      	</div>
      	<!-- </form> -->
  </div>
</div>



<div id="modal_umur_manfaat" class="modal fade" tabindex="-3" role="dialog">
    <div class="modal-md modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12">
            	<img src="<?= base_url('assets/img/umur_manfaat.JPG') ?>" alt="info_umur_manfaat" class="img-responsive">
            </div>
          </div>
        </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function(){

  	$("#vname").hide();
  	let attachment_file = "";
    let attach_category = $('#attachment').data('name');

	const opt_default  = '<option value="0" data-name="">-- Choose --</option>';

	const binding = <?= ($binding) ? "'".$binding."'" : 'false' ?>;
	if(binding != false){
	    if(binding == 'directorat'){
	      getDivision();
	    }
	    if(binding == 'division'){
	      getUnit();
	    }
        if(binding == 'unit'){
          setTimeout(function(){
  			getFS();
			getSubmitter();
          }, 1000);
        }

	}

 	$('#bast_date').datepicker({
		format: 'dd-mm-yyyy',
		todayHighlight:'TRUE',
		autoclose: true,
    });


	let pr_line_data = {};
	let id_fs        = $("#fs_header").val();
	let directorat   = $("#directorat").val();
	let url          = baseURL + 'general-receipt/api/load_data_gr_create';

	Pace.track(function(){
	    $('#table_data').DataTable({
	      "serverSide"      : true,
	      "processing"      : true,
	      "ajax"            : {
									"url"  : url,
									"type" : "POST",
									"dataType": "json",
									"data"    : function ( d ) {
					                              d.id_fs = id_fs;
					                              d.directorat = directorat;
					                            }
			                    },
	      "language"        : {
	                            "emptyTable"  : "<span class='label label-danger'>Data Not Found!</span>",
	                            "infoEmpty"   : "Empty record",
	                            "processing"  : '<img src="' + imageLoadingSmall + '" alt="" />',
	                            "search"      : "_INPUT_"
	                          },
	      "columns"         : [
								{"data": "no", "width": "10px", "class": "text-center" },
								{"data": "item_name", "width": "150px", "class": "p-5" },
								{"data": "item_description", "width": "180px", "class": "p-5" },
								{"data": "quantity", "width": "60px", "class": "p-5 text-center"},
								{"data": "uom", "width": "120px", "class": "p-5 text-center"},
								{"data": "item_price", "width": "120px", "class": "p-5 text-right"},
								{"data": "total_price", "width": "120px", "class": "p-5 text-right"},
							/*	{"data": "asset_type", "width": "120px", "class": "p-5 text-right"},
								{"data": "serial_number", "width": "120px", "class": "p-5 text-left"},
								{"data": "merek", "width": "120px", "class": "p-5 text-left"},
								{"data": "major_category", "width": "180px", "class": "p-5 text-left"},
								{"data": "minor_category", "width": "180px", "class": "p-5 text-left"},*/
								{"data": "region", "width": "180px", "class": "p-5 text-left"},
								{"data": "lokasi", "width": "180px", "class": "p-5 text-left"},
								{"data": "project_owner_unit", "width": "180px", "class": "p-5 text-left"},
								/*{"data": "umur_manfaat", "width": "120px", "class": "p-5 text-left"},
								{"data": "cip", "width": "60px", "class": "p-5 text-center"},*/
								{"data": "no_invoice", "width": "120px", "class": "p-5 text-center"},
								{"data": "invoice_date", "width": "120px", "class": "p-5 text-center"},
								{"data": "receipt_date", "width": "120px", "class": "p-5 text-center"},
				    		],
          "drawCallback": function ( settings ) {

      		totalX = table.data().count();

          	if(totalX > 0){

          		catt = $("#fs_header").find(':selected').attr('data-category');
          		catt2 = $("#category").val();

          		if(catt != "null"){
	          		setTimeout(function(){

						if(catt == "amount"){
				    		$("#category").val(catt).change();
				    		$("#category").attr('disabled', true);
							$('.quantity').attr('readonly', true);
				    		$('.unit_price').removeAttr("readonly");
				    	}
				    	else
				    	{
				    		$("#category").val(catt).change();
				    		$("#category").attr('disabled', true);
				    		$('.quantity').removeAttr("readonly");
				    		$('.unit_price').attr('readonly', true);
				    	}
					}, 300);
          		}else{
	          		setTimeout(function(){
			    		$('#category').removeAttr("disabled");
						if(catt2 == "amount"){
							$('.quantity').attr('readonly', true);
				    		$('.unit_price').removeAttr("readonly");
				    	}
				    	else
				    	{
				    		$('.quantity').removeAttr("readonly");
				    		$('.unit_price').attr('readonly', true);
				    	}
					}, 300);

          		}

          	}
			
          },
	      "ordering"        : false,
	      "pageLength"      : 100,
	      "scrollY"         : 480,
	      "scrollX"         : true,
	      "scrollCollapse"  : true,
	      "autoWidth"       : true,
	      "bAutoWidth"      : true,
	    });
	});

	let table = $('#table_data').DataTable();
	$('#table_data_length').remove();
	$('#table_data_paginate').remove();
	$('#table_data_filter').remove();


// new beberapa kolom di hide
	 $("#save_data").on('click', function () {

		let po_number     = $("#fs_header").val();
		let vendor_name   = $("#fs_header").find(':selected').attr('data-name');
		let po_header_id  = $("#fs_header").find(':selected').attr('data-poid');
		let pr_header_id  = $("#fs_header").find(':selected').attr('data-prid');
		let id_fs         = $("#fs_header").find(':selected').attr('data-fs');
		let directorat    = $("#directorat").val();
		let division      = $("#division").val();
		let unit          = $("#unit").val();
		let submitter     = $("#submitter").val();
		let jabatan_sub   = $("#jabatan_sub").html();
		let category      = $("#category").val();
		let contract      = $("#contract").val();
		let budget_type   = $("#budget_type").val();
		let project_owner = $("#project_owner").val();
		let no_bast       = $("#no_bast").val();
		let bast_date     = $("#bast_date").val();
		let total_data    = table.data().count();

		let fatalError = false;
		let fatalList0 = '';
		let fatalList1 = '';
		let fatalList2 = '';
		let fatalList3 = '';
		let fatalList4 = '';
		let fatalList5 = '';
		let fatalList6 = '';
		let fatalList7 = '';
		let fatalList8 = '';
		let fatalList9 = '';
		let fatalList10 = '';
		let fatalList11 = '';
		let fatalList12 = '';



	    for (var i = 1; i <= total_data; i++) {
			// serial_number      = $("#serial_number-"+i).val();
			// asset_type         = $("#asset_type-"+i).val();
			// merek              = $("#merek-"+i).val();
			// umur_manfaat       = $("#umur_manfaat-"+i).val();
			// major_category     = $("#major_category-"+i).val();
			// minor_category     = $("#minor_category-"+i).val();
			invoice_date       = $("#invoice_date-"+i).val();
			receipt_date       = $("#receipt_date-"+i).val();
			region             = $("#region-"+i).val();
			lokasi             = $("#lokasi-"+i).val();
			project_owner_unit = $("#project_owner_unit-"+i).val();
			cip                = $("#cip-"+i).val();
			no_invoice         = $("#no_invoice-"+i).val();

			// if (serial_number == ""){
			// 	fatalError =  true;
			// 	if(fatalList0 == ""){
			// 		fatalList0 = i;
			// 	}else{
			// 		fatalList0 += ', ' +i;
			// 	}
			// }

			// if (asset_type == ""){
			// 	fatalError =  true;
			// 	if(fatalList1 == ""){
			// 		fatalList1 = i;
			// 	}else{
			// 		fatalList1 += ', ' +i;
			// 	}
			// }

			// if (merek == ""){
			// 	fatalError =  true;
			// 	if(fatalList2 == ""){
			// 		fatalList2 = i;
			// 	}else{
			// 		fatalList2 += ', ' +i;
			// 	}
			// }

			// if (umur_manfaat == ""){
			// 	fatalError =  true;
			// 	if(fatalList3 == ""){
			// 		fatalList3 = i;
			// 	}else{
			// 		fatalList3 += ', ' +i;
			// 	}
			// }

	/*		if (invoice_date == ""){
				fatalError =  true;
				if(fatalList4 == ""){
					fatalList4 = i;
				}else{
					fatalList4 += ', ' +i;
				}
			}

			if (receipt_date == ""){
				fatalError =  true;
				if(fatalList5 == ""){
					fatalList5 = i;
				}else{
					fatalList5 += ', ' +i;
				}
			}*/

			// if (major_category == ""){
			// 	fatalError =  true;
			// 	if(fatalList6 == ""){
			// 		fatalList6 = i;
			// 	}else{
			// 		fatalList6 += ', ' +i;
			// 	}
			// }

			// if (minor_category == ""){
			// 	fatalError =  true;
			// 	if(fatalList7 == ""){
			// 		fatalList7 = i;
			// 	}else{
			// 		fatalList7 += ', ' +i;
			// 	}
			// }

		/*	if (region == ""){
				fatalError =  true;
				if(fatalList8 == ""){
					fatalList8 = i;
				}else{
					fatalList8 += ', ' +i;
				}
			}

			if (lokasi == ""){
				fatalError =  true;
				if(fatalList9 == ""){
					fatalList9 = i;
				}else{
					fatalList9 += ', ' +i;
				}
			}

			if (project_owner_unit == ""){
				fatalError =  true;
				if(fatalList10 == ""){
					fatalList10 = i;
				}else{
					fatalList10 += ', ' +i;
				}
			}*/

		/*	if (cip == ""){
				fatalError =  true;
				if(fatalList11 == ""){
					fatalList11 = i;
				}else{
					fatalList11 += ', ' +i;
				}
			}*/

		/*	if (no_invoice== ""){
				fatalError =  true;
				if(fatalList12 == ""){
					fatalList12 = i;
				}else{
					fatalList12 += ', ' +i;
				}
			}*/

	    }


		if(fatalError == true){
			// if(fatalList0 != ""){
			// 	customNotif('Warning', 'Serial Number cannot be null', 'warning');
			// }
			// if(fatalList1 != ""){
			// 	customNotif('Warning', 'Asset Type cannot be null', 'warning');
			// }
			// if(fatalList2 != ""){
			// 	customNotif('Warning', 'Merek cannot be null', 'warning');
			// }
			// if(fatalList3 != ""){
			// 	customNotif('Warning', 'Umur Manfaat cannot be null', 'warning');
			// }
			if(fatalList4 != ""){
				customNotif('Warning', 'Invoice Date cannot be null', 'warning');
			}
			if(fatalList5 != ""){
				customNotif('Warning', 'Receipt Date cannot be null', 'warning');
			}
			// if(fatalList6 != ""){
			// 	customNotif('Warning', 'Major Category cannot be null', 'warning');
			// }
			// if(fatalList7 != ""){
			// 	customNotif('Warning', 'Minor Category cannot be null', 'warning');
			// }
			if(fatalList8 != ""){
				customNotif('Warning', 'Region cannot be null', 'warning');
			}
			if(fatalList9 != ""){
				customNotif('Warning', 'Location cannot be null', 'warning');
			}
			/*if(fatalList10 != ""){
				customNotif('Warning', 'Project Ownership Unit cannot be null', 'warning');
			}*/
			/*if(fatalList11 != ""){
				customNotif('Warning', 'CIP cannot be null', 'warning');
			}*/
			if(fatalList12 != ""){
				customNotif('Warning', 'No Invoice cannot be null', 'warning');
			}
		}
		else
		{

			let data_lines  = [];

			table.rows().eq(0).each( function (index) {

		    	j = index+1;
			    data = table.row( index ).data();
				
				item_name          = data.item_name;
				po_detail_id       = data.po_detail_id;
				item_description   = data.item_description;
				quantity           = $("#quantity-"+j).val();
				uom                = data.uom;
				item_price         = $("#unit_price-"+j).val();
				total_price        = $("#total_price-"+j).val();
				// asset_type         = $("#asset_type-"+j).val();
				// serial_number      = $("#serial_number-"+j).val();
				// merek              = $("#merek-"+j).val();
				// umur_manfaat       = $("#umur_manfaat-"+j).val();
				invoice_date       = $("#invoice_date-"+j).val();
				receipt_date       = $("#receipt_date-"+j).val();
				
				// major_category     = $("#major_category-"+j).val();
				// minor_category     = $("#minor_category-"+j).val();
				region             = $("#region-"+j).val();
				lokasi             = $("#lokasi-"+j).val();
				project_owner_unit = $("#project_owner_unit-"+j).val();
				cip                = $("#cip-"+j).val();
				no_invoice         = $("#no_invoice-"+j).val();
				

				// if(invoice_date != "" && receipt_date != ""){
		  //   		data_lines.push({'po_detail_id' : po_detail_id, 'asset_type' : asset_type, 'item_name' : item_name,'item_description' : item_description, 'quantity' : quantity, 'uom' : uom, 'item_price' : parseInt(item_price.replace(/\./g, '')), 'total_price' : parseInt(total_price.replace(/\./g, '')), 'serial_number' : serial_number, 'merek' : merek, 'umur_manfaat' : umur_manfaat, 'invoice_date' : invoice_date, 'receipt_date' : receipt_date, 'major_category' : major_category , 'minor_category' : minor_category , 'region' : region , 'lokasi' : lokasi , 'project_owner_unit' : project_owner_unit , 'cip' : cip, 'no_invoice' : no_invoice});
				// }

				if(invoice_date != "" && receipt_date != ""){
		    		data_lines.push({'po_detail_id' : po_detail_id, 'item_name' : item_name,'item_description' : item_description, 'quantity' : quantity, 'uom' : uom, 'item_price' : parseInt(item_price.replace(/\./g, '')), 'total_price' : parseInt(total_price.replace(/\./g, '')),'invoice_date' : invoice_date, 'receipt_date' : receipt_date,  'region' : region , 'lokasi' : lokasi , 'project_owner_unit' : project_owner_unit , 'cip' : cip, 'no_invoice' : no_invoice});
				}
			});

		    data = {
							po_number : po_number,
							po_header_id : po_header_id,
							pr_header_id : pr_header_id,
							id_fs : id_fs,
							directorat : directorat,
							division : division,
							unit : unit,
							submitter : submitter,
							jabatan_sub : jabatan_sub,
							category : category,
							contract : contract,
							budget_type : budget_type,
							project_owner : project_owner,
							attachment : attachment_file,
							no_bast : no_bast,
							bast_date : bast_date,
							data_line : data_lines
			    		}

	    		console.log(data);

			    $.ajax({
			        url   : baseURL + 'general-receipt/api/save_gr',
			        type  : "POST",
			        data  : data,
			        dataType: "json",
			        beforeSend  : function(){
	                          customLoading('show');
	                        },
			        success : function(result){
			        	if(result.status == true){
			        		customNotif('Success', "GR CREATED", 'success');
			        		setTimeout(function(){
			        			customLoading('hide');
			        			$(location).attr('href', baseURL + 'general-receipt');
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

  	$("#directorat").bind("click", function(e) {
	    lastValue = $(this).val();
	}).bind("change", function(e) {
		change = true;
		if(change == true){
			$("#table_data tbody").find("tr:not(:first)").remove();

			$("#division").html(opt_default);
	  		if( $(this).val() != "0"){
	  			getDivision();
	  		}
			$("#unit").html(opt_default);
			$("#fs_header").html(opt_default);

			directorat = $(this).val();

	  		id_fs = 0;
	  		table.draw();
		}
	});

	$("#division").bind("click", function(e) {
	    lastValue = $(this).val();
	}).bind("change", function(e) {
		change = true;
		if(change == true){
			$("#table_data tbody").find("tr:not(:first)").remove();

			$("#unit").html(opt_default);
	  		if( $(this).val() != "0"){
	  			getUnit();
	  		}
			$("#fs_header").html(opt_default);

	  		id_fs = 0;
	  		table.draw();
		}
	});

	$("#unit").bind("click", function(e) {
	    lastValue = $(this).val();
	}).bind("change", function(e) {
		change = true;
		if(change == true){
			$("#table_data tbody").find("tr:not(:first)").remove();

			$("#fs_header").html(opt_default);
	  		if( $(this).val() != "0"){
	  			getFS();
	  		}
			getSubmitter();

	  		id_fs = 0;
	  		table.draw();
		}
	});

	$("#fs_header").bind("click", function(e) {
	    lastValue = $(this).val();
	}).bind("change", function(e) {
		change = true;
		if(change == true){
			$("#table_data tbody").find("tr:not(:first)").remove();

			id_fs       = $(this).val();
			// fs_currency = $(this).find(':selected').attr('data-currency');
			// fs_rate     = $(this).find(':selected').attr('data-rate');

			// $("#currency").val(fs_currency);
			// $("#rate").val( formatNumber( fs_rate ) );

	  // 		if( fs_currency != "IDR"){
			// 	$("#currency_rate").removeClass("d-none");
	  // 		}else{
			// 	$("#currency_rate").addClass("d-none");
	  // 		}

  			table.draw();
		}

	});

 //    function getAmount() {
	// 	let total_data  = table.data().count();
	// 	let totalAmount = 0;

	// 	for (var i = 1; i <= total_data; i++) {
	// 		get_nominal = $("#nominal-"+i).val();
	// 		totalAmount += parseInt(get_nominal.replace(/\./g, ''));
	//     }
	// 	$("#amount").val(formatNumber(totalAmount));
	// }

  //   function getNominal(){
		// let total_detail = table_detail.data().count();
		// let totalNominal = 0;

		// for (var i = 1; i <= total_detail; i++) {
		// 	nominal_detail_val = $("#nominal_detail-"+i).val();
		// 	totalNominal += parseInt(nominal_detail_val.replace(/\./g, ''));
	 //    }

	 //    $("#table_data tbody tr.selected").find("input.nominal").val(formatNumber(totalNominal));
  //   }

  $('#table_data tbody').on('focus', 'tr td input.date_period', function () {
    	$(this).datepicker({
			format: 'dd-mm-yyyy',
			todayHighlight:'TRUE',
			autoclose: true,
			minDate:0,
	    });
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
					    unit += '<option value="'+ obj.id_unit +'" data-name ="'+ obj.unit +'">'+ obj.unit +'</option>';
					}
	        	}
				$("#unit").html(unit);
				$("#unit").attr('disabled', false);
				$("#unit").css('cursor', 'default');
	        }
	    });
	}

    function getFS() {

		let directorat = $("#directorat").val();
		let division   = $("#division").val();
		let unit       = $("#unit").val();

		$("#fs_header").attr('disabled', true);
		$("#fs_header").css('cursor', 'wait');

	    $.ajax({
	        url   : baseURL + 'general-receipt/api/load_po_number',
	        type  : "POST",
	        data  : {directorat : directorat, division : division, unit : unit},
	        dataType: "json",
	        success : function(result){
        		let fs_header = opt_default;
        		console.log(result.status);
	        	if(result.status == true){
					data = result.data;
		        	for(var i = 0; i < data.length; i++) {
					    obj = data[i];
					    fs_header += '<option value="'+ obj.po_number +'" data-fs = "'+ obj.id_fs +'" data-name = "'+ obj.vendor_name +'" data-poid = "'+ obj.po_header_id +'" data-prid = "'+ obj.pr_header_id +'" data-category = "'+ obj.category +'">'+ obj.po_number +' - '+ obj.po_line_desc +'</option>';
					}
	        	}
				$("#fs_header").html(fs_header);
				$("#fs_header").attr('disabled', false);
				$("#fs_header").css('cursor', 'default');
		  		setTimeout(function(){
					$("#fs_header").select2();
				}, 300);
	        }
	    });
	}


   function getNature(id_row, id_rkap, id_select=0) {

		$("#nature_opt-"+id_row).attr('disabled', true);
		$("#nature_opt-"+id_row).css('cursor', 'wait');

	    $.ajax({
	        url   : baseURL + 'api-budget/load_nature_by_rkap',
	        type  : "POST",
	        data  : {id_rkap : id_rkap},
	        dataType: "json",
	        success : function(result){
				let nature_opt = opt_default;
	        	if(result.status == true){
					data = result.data;

					if(data.length > 1){
			        	for(var i = 0; i < data.length; i++) {
							obj = data[i];
							let selected = '';
						    if(obj.selected == true){
						    	selected = ' selected';
						    }
						    if(id_select == obj.id_coa){
						    	selected = ' selected';
						    }
						    nature_opt += '<option value="'+ obj.id_coa +'"'+selected+'>'+ obj.nature_desc +'</option>';
						}
					}else{
						obj = data[0];
					    nature_opt = '<option value="'+ obj.id_coa +'">'+ obj.nature_desc +'</option>';
					}
	        	}
				$("#nature_opt-"+id_row).html(nature_opt);
				$("#nature_opt-"+id_row).attr('disabled', false);
				$("#nature_opt-"+id_row).css('cursor', 'default');
	        }
	    });
	}


	function storeDataDetail(){

		let get_table      = table.row( $('#table_data tbody tr.selected') );
		let index          = get_table.index();
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

			detail_data.push({'rkap_desc' : rkap_desc_val, 'nature' : nature_val, 'quantity' : quantity_val, 'price' : parseInt(price_val.replace(/\./g, '')), 'nominal' : parseInt(nominal_detail_val.replace(/\./g, ''))});
	    }

    	pr_line_data[pr_line_key] = detail_data;

	}

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

    $('#table_data tbody').on('input', 'tr td input.unit_price ', function () {
		id_row      = $(this).data('id');
		unit_price = $(this).val().toString().replace(/\./g, '');
		total_price     = $("#total_price-"+id_row).val();

		if(parseInt(unit_price) > parseInt(total_price.toString().replace(/\./g, ''))){
			$(this).val(total_price);
		}
    });

    $("#fs_header").on('change', function(){
    	let po_number    = $(this).val();
    	let vendor_name  = $("#fs_header").find(':selected').attr('data-name');
    	let category  = $("#fs_header").find(':selected').attr('data-category');
    	let substring = po_number.substring(0, 1);
    	if(po_number != "0" && po_number != ""){
    		 $("#vname").show();
    		 $("#vendor_name").text(vendor_name);

    		 console.log('hasil ponumber : ' + po_number);
    		 console.log('hasil substring : ' + substring);

    		 if(substring == "P")
    		 {
    		  document.getElementById('contract').value='04';
    		 }
    		 else
    		 {
			  document.getElementById('contract').value='02';
    		 }

    	}else{
    		 $("#vname").hide();
    	}
    });


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
        fileName = this.files[0].name;
        fileType = this.files[0].type;
        fileSize = fileSize/1000
        j=0;

        if(fileSize > 21000){
          upload = false;
          alert('Max file size 20M')
          $(this).val(lastValue);
        }

        extension_allow = ['pdf','zip','rar'];
        console.log(fileName);
        console.log(fileType);
        extension       = fileName.split('.').pop().toLowerCase();
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
          formData.append('category', attach_category);

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
            data  : {file: attachment_file, category: attach_category},
            dataType: "json",
            success : function(result){
              attachment_file = '';
              return true;
            }
        });

        return true;
    }


    /*$('#table_data tbody').on('focus', 'tr td input.umur_manfaat', function () {
        $("#modal_umur_manfaat").modal('show');
    });*/

    $('#umur_manfaat_info').on( 'click', function () {
    	$("#modal_umur_manfaat").modal('show');
	});


	$("#category").on('change', function(){
    	var values = this.value;
    	if (values == 'quantity') 
    	{
    		$('.quantity').removeAttr("readonly");
    		// $('.total_price').attr('readonly', true);
    		$('.unit_price').attr('readonly', true);
    	}
    	else
    	{
			$('.quantity').attr('readonly', true);
    		// $('.total_price').removeAttr("readonly");
    		$('.unit_price').removeAttr("readonly");
    	}
    });


      let major_category_value = "";
	  let major_category_id = "";
      let minor_category_id = "";

	$('#table_data').on('change', 'select.major_category', function () {

	  let data  = table.row( $(this).parents('tr') ).data();
	  major_category_value = this.value;
	  major_category_id = this.id;

      minor_category_id = major_category_id.replace("major", "minor");

	  getminorcategory();

	});


	function getminorcategory()
  {

  	console.log(major_category_value);
  	console.log(minor_category_id);


    $.ajax({
	  url   : baseURL + 'general-receipt/api/load_ddl_minor',
      type  : "POST",
      data  : {major_category_value :  major_category_value},
	        dataType: "json",
	        success : function(result){
        		let minor_opt = opt_default;
	        	if(result.status == true){

					data = result.data;
		        	for(var i = 0; i < data.length; i++) {
					    obj = data[i];
					    minor_opt += '<option value="'+ obj.code +'" data-name ="'+ obj.minor_name +'">'+ obj.minor_name +'</option>';
					}
	        	}
				$("#"+minor_category_id).html(minor_opt);

				// setTimeout(function(){
				// 	$(".minor_category").select2();
				// }, 300);
	        }
	    });  

  }


	$('#table_data').on('change', 'select.region', function () {

	  let data  = table.row( $(this).parents('tr') ).data();
	  region_value = this.value;
	  region_id = this.id;

      lokasi_id = region_id.replace("region", "lokasi");

	  getlokasi();

	});

	function getlokasi()
  {

  	console.log(region_value);
  	console.log(lokasi_id);


    $.ajax({
	  url   : baseURL + 'general-receipt/api/load_ddl_location',
      type  : "POST",
      data  : {region_value :  region_value},
	        dataType: "json",
	        success : function(result){
        		let lokasi_opt = opt_default;
	        	if(result.status == true){

					data = result.data;
		        	for(var i = 0; i < data.length; i++) {
					    obj = data[i];
					    lokasi_opt += '<option value="'+ obj.code +'" data-name ="'+ obj.lokasi +'">'+ obj.lokasi +'</option>';
					}
	        	}
				$("#"+lokasi_id).html(lokasi_opt);

				// setTimeout(function(){
				// 	$(".location").select2();
				// }, 300);
	        }
	    });  

  }


  });
</script>