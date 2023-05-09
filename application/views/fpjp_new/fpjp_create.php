<?php $this->load->view('ilustration') ?>

<div class="row">
  <div class="white-box boxshadow m-b-0 mt-10 border-top-only-5 z-index-2 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">FPJP Detail</h5>
    </div>
  </div>
  <div class="white-box boxshadow z-index-2 py-10 border-bottom-only-5 small">
	<form class="form-horizontal">
    <div class="row">
		<div class="col-sm-6">
    		<div class="form-group m-b-10">
	            <label for="type" class="col-sm-3 control-label text-left">FPJP Type <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
			        <select class="form-control input-sm" id="type">
	            		<option value="0">-- Choose --</option>
			            <?php foreach($fpjp_type as $label => $opt): ?>
						    <optgroup label="<?= $label ?>">
						    <?php foreach ($opt as $value): ?>
			           			 <option value="<?= $value['ID_MASTER_FPJP'] ?>" data-name="<?= $value['FPJP_NAME'] ?>" data-category="<?= $value['CATEGORY'] ?>"<?=($fpjp_tax == true && $value['FPJP_NAME'] == "Pembayaran Pajak") ? ' selected' : '' ?>><?= $value['FPJP_NAME'] ?></option>
						    <?php endforeach; ?>
						    </optgroup>
						<?php endforeach; ?>
                    </select>
	            </div>
	        </div>
			<div class="form-group m-b-10">
	            <label for="fpjp_name" class="col-sm-3 control-label text-left">FPJP Name <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	              <input type="text" class="form-control input-sm" id="fpjp_name" placeholder="FPJP Name" autocomplete="off">
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
	        <!-- <div class="form-group m-b-10">
	            <label for="justif_cat" class="col-sm-3 control-label text-left">Category <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	            	<select class="form-control input-sm" id="justif_cat" disabled="true">
	            		<option value="">-- Choose --</option>
	            		<option value="justif">Justification</option>
	            		<option value="non_justif">Non Justifiation</option>
		            </select>
	            </div>
	        </div> -->
	        <div id="justif" class="form-group m-b-10 d-none">
	            <label for="fs_header" class="col-sm-3 control-label text-left">Justif Number <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	            	<select class="form-control input-sm" id="fs_header">
	            		<option value="0">-- Choose --</option>
		            </select>
	            </div>
	        </div>
	        <div class="form-group m-b-10">
	            <label for="currency" class="col-sm-3 control-label text-left">Currency <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	              <input type="text" class="form-control input-sm" id="currency" value="IDR" readonly>
	            </div>
	        </div>
				<div id="currency_rate" class="form-group m-b-10 d-none">
	            <label for="rate" class="col-sm-3 control-label text-left">Rate <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	              <input type="text" class="form-control input-sm" id="rate" placeholder="Rate" value="0" readonly="">
	            </div>
	        </div>
	        <div class="form-group m-b-10">
	            <label for="amount" class="col-sm-3 control-label text-left">Total Amount <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	              <input type="text" class="form-control input-sm" id="amount" value="0" readonly>
	            </div>
	        </div>
	    </div>
	    <div class="col-sm-6">
	    	<div class="form-group m-b-10">
	          	<label for="fpjp_date" class="col-sm-3 control-label text-left">FPJP Date <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
					<div class="input-group">
						<input type="text" class="form-control input-sm mydatepicker" id="fpjp_date" placeholder="dd/mm/yyyy" value="<?= date("d-m-Y")?>">
						<span class="input-group-addon"><i class="icon-calender"></i></span>
					</div>
	            </div>
	        </div>
          <div class="form-group m-b-10">
            <label for="vendor_name" class="col-sm-3 control-label text-left">Vendor Name <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-6">
              <select class="form-control input-sm" id="vendor_name" name="vendor_name">
	            		<option value="">-- Choose --</option>
			            <?php
	              			// $get_vendor = get_all_vendor();
	              			$get_vendor = $all_vendor;
			                foreach($get_vendor as $key => $value):
			            ?>
                      <option value="<?= $key ?>" data-vendor="<?= $value ?>"><?= $value ?></option>
			            <?php
			                endforeach; 
			            ?>
              </select>
            </div>
          </div>
	        <div class="form-group m-b-10">
	            <label for="bank_name" class="col-sm-3 control-label text-left">Bank Name <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
              	<select class="form-control input-sm" id="data_bank" name="data_bank">
	            		<option value="">-- Please Select Vendor --</option>
              	</select>
	            </div>
	        </div>
	        <div class="form-group m-b-10">
	            <label for="account_name" class="col-sm-3 control-label text-left">Account Name <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	              <input type="text" class="form-control input-sm" id="account_name" placeholder="Nama Pemilik Rekening" readonly="true" required>
	            </div>
	        </div>
	        <div class="form-group m-b-10">
	            <label for="account_number" class="col-sm-3 control-label text-left">Account Number<span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	              <input type="text" class="form-control input-sm" id="account_number" placeholder="Nomor Rekening" readonly="true" required>
	              <input type="hidden" class="form-control input-sm" id="site_code"aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa>
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
      <h5 class="font-weight-700 m-0 text-uppercase">FPJP Lines</h5>
    </div>
  </div>
  <div class="white-box boxshadow border-bottom-only-5">
	<div class="row">
		<div class="col-md-12">
		      <table id="table_data" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small w-full">
		        <thead>
		        	<tr>
			          	<th class="text-center">No</th>
			          	<th class="text-center">RKAP Name</th>
			          	<th class="text-center">Justif Name</th>
			          	<th class="text-center">Name</th>
			          	<th class="text-center">Fund Available</th>
			          	<th class="text-center">Nominal</th>
			          	<th class="text-center">Original Amount</th>
			          	<!-- <th class="text-center">Nama Pemilik Rekening</th>
			          	<th class="text-center">Nama Bank</th>
			          	<th class="text-center">Nomor Rekening</th> -->
			        </tr>
		        </thead>
		      </table>
		    </div>
		</div>
  </div>
</div>

<div class="row">
  <div class="white-box boxshadow m-b-0 border-top-only-5 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">FPJP Lines Detail</h5>
    </div>
  </div>
  <div class="white-box boxshadow border-bottom-only-5">
		<div class="row">
			<div class="col-md-12">
		      <table id="table_detail" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small w-full">
		        <thead>
		        	<tr>
			          	<th class="text-center">No</th>
			          	<th class="text-center">Description</th>
			          	<th class="text-center">Nature</th>
			          	<th class="text-center">Qty</th>
			          	<th class="text-center">PPN</th>
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
			            <label for="no_invoice" class="col-sm-3 control-label text-left">No Invoice <span class="pull-right">:</span></label>
			            <div class="col-sm-4">
			              <input type="text" class="form-control input-sm" id="no_invoice" placeholder="No Invoice" autocomplete="off">
			            </div>
			        </div>
	        	</div>
	        </div>
	        <div class="row">
	        	<div class="col-md-8">
			    	<div class="form-group m-b-10">
			          	<label for="invoice_date" class="col-sm-3 control-label text-left">Invoice Date <span class="pull-right">:</span></label>
			            <div class="col-sm-4">
							<div class="input-group">
								<input type="text" class="form-control input-sm mydatepicker" id="invoice_date" placeholder="dd/mm/yyyy" value="">
								<span class="input-group-addon"><i class="icon-calender"></i></span>
							</div>
			            </div>
			        </div>
	        	</div>
	        </div>
	        <div class="row" id="aset">
	        	<div class="col-md-8">
		        	<div class="form-group m-b-10">
			            <label for="top" class="col-sm-3 control-label text-left">Is Asset <span class="pull-right">:</span></label>
			            <div id="is_asset" class="col-sm-9">
                            <div class="checkbox checkbox-info d-inline-block p-t-5 w-100p">
                                <input id="is_asset-1" class="is_asset" name="is_asset" type="checkbox">
                                <label for="is_asset-1"> Is Asset </label>
                            </div>
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
				                    <input id="attachment" type="file" name="attachment" data-name="fpjp" accept=".pdf,.zip,.rar"> </span> <a id="fileinput_remove" href="javascript:void(0)" class="input-group-addon btn btn-warning fileinput-exists" data-dismiss="fileinput">Remove</a>
				                </div>
			                    <div class="progress progress-lg d-none">
			                        <div id="progress" class="progress-bar progress-bar-info active progress-bar-striped" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%" role="progressbar"></div>
			                    </div>
			                    <span class="help-block"><small>Filename: FPJP_(No.Invoice)_(Vendor Name)_(Group Name).</small></span> 
							</div>
			            </div>
			        </div>
		        </div>
	  	    </div>
	  	    <div class="row">
		  	    <div class="col-md-8">
			  	    <div class="form-group m-b-10">
			            <label for="notes" class="col-sm-3 control-label text-left">Notes <span class="pull-right">:</span></label>
			            <div class="col-sm-9 col-md-6">
			              <textarea class="form-control input-sm" id="notes" rows="8"></textarea>
			            </div>
			        </div>
			    </div>
			</div>
      	</form>
  </div>
</div>

<div class="row" id="fpjp_boq">
  <div class="white-box boxshadow m-b-0 border-top-only-5 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">FPJP BOQ</h5>
    </div>
  </div>
  <div class="white-box boxshadow border-bottom-only-5">
		<div class="row">
			<div class="col-md-12">
		      <table id="table_boq" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small w-full">
		        <thead>
		        	<tr>
			          	<th class="text-center">No</th>
			          	<th class="text-center">Item Name</th>
			          	<th class="text-center">Item Description</th>
			          	<th class="text-center">Qty</th>
			          	<th class="text-center">UoM</th>
			          	<th class="text-center">Unit Price</th>
			          	<th class="text-center">Total Price</th>
			          	<th class="text-center">Asset Type</th>
			          	<th class="text-center">Serial Number</th>
			          	<th class="text-center">Merek</th>
			          	<th class="text-center"><a href="javascript:void(0)" title="UoM Information" id="umur_manfaat_info" style="color:#fff !important">Umur Manfaat <i class="fa fa-info-circle"></i></a></th>
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
  <div class="white-box boxshadow m-b-0 border-top-only-5 panel-title-custom">
  	<div class="row">
      		<div class="col-sm-6 col-sm-offset-6">
		        <div class="form-group pull-right">
		        	<button type="button" id="save_data" class="btn btn-info border-radius-5 m-10 w-150p"><i class="fa fa-save"></i> Save </button>
		        </div>
      		</div>
      	</div>
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

	const opt_default  = '<option value="0" data-name="">-- Choose --</option>';
	let opt_tax  = '<option value="0">0</option><option value="1"> 1% </option><option value="1.1"> 1,1% </option><option value="10"> 10% </option><option value="11"> 11% </option>';
	let opt_justif  = '<option value="">-- Choose --</option><option value="justif">Justification</option><option value="non_justif">Non Justifiation</option>';
	let attachment_file = "";
    let attach_category = $('#attachment').data('name');

   <?= 'let data_bank = '. json_encode($all_bank) . ';'; ?>

    setTimeout(function(){
				$("#vendor_name").select2();
      }, 500);


	<?php if($fpjp_tax): ?>

      setTimeout(function(){
					$('#type').trigger('change');
      }, 800);
	<?php endif; ?>


	$("#vendor_name").on("change", function(){
		let key_vendor = $(this).val();


			$("#account_name").val("");
			$("#account_number").val("");

		let arr_bank = data_bank[key_vendor];
		let total_bank = arr_bank.length;
		if(total_bank > 1){
			let opt_bank = '<option value="">-- Choose --</option>';
	    for (var i=0; i < arr_bank.length; i++) {
	    	bank = arr_bank[i];
	    	site_code = bank.SITE_CODE;

	    	val_bank = bank.NAMA_BANK + ' - ' + bank.ACCT_NUMBER;
	    	if (site_code.includes('Principal') || site_code.includes('Agent')) { 
	    		val_bank = bank.NAMA_BANK + ' - ' + bank.ACCT_NUMBER +' - ' + site_code;
				}
				opt_bank += '<option value="' + bank.ACCT_NUMBER + '" data-bank="' + bank.NAMA_BANK + '" data-rek="' + bank.NAMA_REKENING + '" data-acct="' + bank.ACCT_NUMBER + '" data-site="' + site_code + '" >' + val_bank + '</option>';
			}
			$("#data_bank").html(opt_bank);
		}else{
    	bank = arr_bank[0];
			let opt_bank = '<option value="' + bank.NAMA_BANK + '" data-bank="' + bank.NAMA_BANK + '">' + bank.NAMA_BANK + '</option>';
			$("#data_bank").html(opt_bank);
			$("#account_name").val(bank.NAMA_REKENING);
			$("#account_number").val(bank.ACCT_NUMBER);
			$("#site_code").val(bank.SITE_CODE);
		}

  });

	$("#data_bank").on("change", function(){

		this_row   = $(this).parents("tr").index() + 1;

		let nama_rek  = $(this).find(':selected').attr('data-rek');
		let no_rek    = $(this).find(':selected').attr('data-acct');
		let site_code = $(this).find(':selected').attr('data-site');

		$("#account_name").val(nama_rek);
		$("#account_number").val(no_rek);
		$("#site_code").val(site_code);

  });

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
	let fpjp_line_data = {};
	let id_fs        = $("#fs_header").val();
	let url          = baseURL + 'fpjp/api/load_data_fs';

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
								{"data": "rkap_name", "width": "300px", "class": "p-5" },
								{"data": "fs_name", "width": "180px", "class": "p-5" },
								{"data": "line_name", "width": "180px", "class": "p-5" },
								{"data": "fund_av", "width": "120px", "class": "p-5" },
								{"data": "nominal", "width": "120px"},
								{"data": "original_amount", "width": "120px"}/*,
								{"data": "pemilik_rekening", "width": "150px", "class": "p-5" },
								{"data": "nama_bank", "width": "150px", "class": "p-5" },
								{"data": "no_rekening", "width": "100px", "class": "p-5" }*/
				    		],
          "drawCallback": function ( settings ) {
          	// if(id_fs > 0){
	            let row_datas   = this.api().rows({ page: 'current' }).data();
	            row_datas.each(function (data, i) {
	            	fpjp_line_random = data.fpjp_line_key;
				    detail_data = [{'rkap_desc' : '', 'nature' : 0, 'quantity' : 1,  'price' : 0, 'nominal' : 0}];
				    fpjp_line_data[fpjp_line_random] = detail_data;
	            });

      			setTimeout(function(){
	          		$('#table_data tbody tr').eq(0).trigger('click');
       				$(".select2").select2();
				}, 500);
    //       	}else{
				// table_detail.rows().remove().draw();
    //       	}
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

	let buttonAdd = '<button type="button" id="addRow-detail" class="btn btn-success btn-sm m-l-5 mr-5"><i class="fa fa-plus"></i> Add New </button>';
		buttonAdd += '<button type="button" id="deleteRow-detail" class="btn btn-danger btn-sm m-l-5 mr-5" disabled><i class="fa fa-trash"></i> Delete </button>';
	
    let table_detail = $('#table_detail').DataTable({
	    "data":[],
	    "columns":[
	    			{"data": "no", "width": "10px", "class": "text-center" },
	    			{"data": "rkap_desc", "width": "250px", "class": "p-2" },
	    			{"data": "nature", "width": "260px", "class": "p-2" },
	    			{"data": "quantity", "width": "60px", "class": "p-2 text-center" },
	    			{"data": "tax", "width": "100px", "class": "p-2" },
	    			{"data": "price", "width": "130px", "class": "p-2" },
	    			{"data": "nominal_detail", "width": "170px", "class": "p-2" }
	    		],
	      "language"        : {
	                            "emptyTable"  : "<span class='label label-danger'>Data Not Found!</span>",
	                            "infoEmpty"   : "Empty record",
	                            "processing"  : '<img src="' + imageLoadingSmall + '" alt="" />',
	                            "search"      : "_INPUT_"
	                          },
		"ordering"        : false,
		"scrollY"         : 480,
		"scrollX"         : true,
		"scrollCollapse"  : true,
    "pageLength"      : 500,
		// "paging" 		  : false
	});

	$('#table_detail_filter').html(buttonAdd);
	$('#table_detail_length').remove();
	$('#table_detail_paginate').remove();

	//FPJP BOQ
	let buttonAddboq = '<button type="button" id="addRow-detail_boq" class="btn btn-success btn-sm m-l-5 mr-5"><i class="fa fa-plus"></i> Add New </button>';
		buttonAddboq += '<button type="button" id="deleteRow-detail_boq" class="btn btn-danger btn-sm m-l-5 mr-5" disabled><i class="fa fa-trash"></i> Delete </button>';

		let uom_call = 1;
		let uom_opt = opt_default;
	
    let table_boq = $('#table_boq').DataTable({
	    "data":[],
	    "columns":[
	    			{"data": "no", "width": "10px", "class": "text-center" },
	    			{"data": "fpjp_item", "width": "150px", "class": "p-2" },
	    			{"data": "fpjp_desc", "width": "150px", "class": "p-2" },
	    			{"data": "qty_boq", "width": "50px", "class": "p-2" },
	    			{"data": "uom", "width": "100px", "class": "p-2" },
	    			{"data": "unit_price", "width": "100px", "class": "p-2" },
	    			{"data": "total_price", "width": "100px", "class": "p-2" },
	    			{"data": "asset_type", "width": "120px", "class": "p-5 text-right"},
					{"data": "serial_number", "width": "120px", "class": "p-5 text-left"},
					{"data": "merek", "width": "120px", "class": "p-5 text-left"},
					{"data": "umur_manfaat", "width": "120px", "class": "p-5 text-left"},
					{"data": "invoice_date", "width": "120px", "class": "p-5 text-center"},
					{"data": "receipt_date", "width": "120px", "class": "p-5 text-center"}
	    		],
	      "language"        : {
	                            "emptyTable"  : "<span class='label label-danger'>Data Not Found!</span>",
	                            "infoEmpty"   : "Empty record",
	                            "processing"  : '<img src="' + imageLoadingSmall + '" alt="" />',
	                            "search"      : "_INPUT_"
	                          },
          "drawCallback": function ( settings ) {
          	if(uom_call == 1){
          		uom_opt = getUom();
          		uom_call++;
          	}
          },
		"ordering"        : false,
		"scrollY"         : 480,
		"scrollX"         : true,
		"scrollCollapse"  : true,
    "pageLength"      : 500,
		// "paging" 		  : false
	});

	$('#table_boq_filter').html(buttonAddboq);
	$('#table_boq_length').remove();
	$('#table_boq_paginate').remove();

	//END FPJP BOQ

	$('#table_data tbody').on( 'click', 'tr', function () {
		if (! $(this).hasClass('selected') ) {
			table.$('tr.selected').removeClass('selected');
			let get_table = table.row( this );
			let index     = get_table.index()
			let index_Num = index+1;
			let data      = get_table.data();

			total_data = table.data().count();
			if(total_data > 0){
				$(this).addClass('selected');

				$("#deleteRow-detail").attr('disabled', true);

				let fpjp_line_key_val = data.fpjp_line_key;
				let id_rkap = data.id_rkap;

				data_detail = fpjp_line_data[fpjp_line_key_val];

				let newData = [];

				$("#table_detail_title").html("Detail of line " + index_Num);

				data_detail.forEach(function(value, i) {

				    j=i+1;
					
					let rkap_desc_val  = '<div class="form-group m-b-0"><input id="rkap_desc-'+ j +'" class="form-control input-sm rkap_desc" value="'+value.rkap_desc+'" maxlength="150" autocomplete="off"></div>';
					let nature_opt     = '<div class="form-group m-b-0"><select id="nature_opt-'+ j +'" class="form-control input-sm nature_opt select-center">';
							nature_opt += opt_default;
							nature_opt += '</select></div>';
					let quantity       = '<div class="form-group m-b-0"><input id="quantity-'+j+'" data-id="'+j+'" class="form-control input-sm quantity text-center" value="'+value.quantity+'" min="1" max="99999" type="number"></div>';
					let price          = '<div class="form-group m-b-0"><input id="price-'+j+'" data-id="'+j+'" class="form-control input-sm price text-right money-format-negative" value="'+formatNumber(value.price)+'"></div>';
					let nominal_detail = '<div class="form-group m-b-0"><input id="nominal_detail-'+j+'" class="form-control input-sm nominal_detail text-right" value="'+formatNumber(value.nominal)+'" readonly></div>';
					let tax_opt     = '<div class="form-group m-b-0"><select id="tax_opt-'+ j +'" class="form-control input-sm tax_opt select-center">';
							tax_opt += opt_tax;
							tax_opt += '</select></div>';

					newData.push({
									"fpjp_line_key": fpjp_line_key_val,
									"no": j,
									"rkap_desc": rkap_desc_val,
									"nature": nature_opt,
									"quantity": quantity,
									"tax": tax_opt,
									"price": price,
									"nominal_detail": nominal_detail
					    		});
					getTax(j, value.tax);
					getNature(j, id_rkap, value.nature);
				});

				table_detail.rows().remove().draw();
				table_detail.rows.add(newData).draw();

				setTimeout(function(){
					getNominal();
					getAmount();
				}, 300);
			}
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

	$('#addRow-detail').on( 'click', function () {
		indexNow_detail = table_detail.data().count();

		if($("#rkap_desc-"+indexNow_detail).val() == "" || $("#nominal_detail-"+indexNow_detail).val() == 0){
			customNotif('Warning', 'Please fill out all field!', 'warning');
		}
		else{

			numDetail = indexNow_detail+1;

			let rkap_desc      = '<div class="form-group m-b-0"><input id="rkap_desc-'+numDetail+'" class="form-control input-sm rkap_desc" autocomplete="off" maxlength="150"></div>';
			let nature_opt     = '<div class="form-group m-b-0"><select id="nature_opt-'+numDetail+'" class="form-control input-sm nature_opt select-center">';
					nature_opt += opt_default;
					nature_opt += '</select></div>';
			let tax_opt     = '<div class="form-group m-b-0"><select id="tax_opt-'+numDetail+'" class="form-control input-sm tax_opt select-center">';
					tax_opt += opt_tax;
					tax_opt += '</select></div>';
			let quantity       = '<div class="form-group m-b-0"><input id="quantity-'+numDetail+'" data-id="'+numDetail+'" class="form-control input-sm quantity text-center" value="1" min="1" max="99999" type="number"></div>';
			let price          = '<div class="form-group m-b-0"><input id="price-'+numDetail+'" data-id="'+numDetail+'" class="form-control input-sm price text-right money-format-negative" value="0"></div>';
			let nominal_detail = '<div class="form-group m-b-0"><input id="nominal_detail-'+numDetail+'" class="form-control input-sm nominal_detail text-right" value="0" readonly></div>';

		    table_detail.rows.add(
		       [{ 
		       			"no": numDetail,
		    			"rkap_desc": rkap_desc,
		    			"nature": nature_opt,
		    			"quantity": quantity,
		    			"tax": tax_opt,
		    			"price": price,
		    			"nominal_detail": nominal_detail
	    		}]
		    ).draw();

			get_table = table.row( $('#table_data tbody tr.selected') );
			data      = get_table.data();

			id_rkap_selected = data.id_rkap;

			getNature(numDetail, id_rkap_selected);

		}
	});

	$('#table_boq tbody').on('input change blur', 'tr td input.qty_boq, tr td input.unit_price', function () {
		if($(this).val().trim().length === 0){
			$(this).val(0);
		}

		index       = $(this).data('id');
		qty_int_boq     = parseInt( $("#qty_boq-"+index).val() );
		price_int_boq   = parseInt( $("#unit_price-"+index).val().replace(/\./g, '') );
		nominal_val_boq = (qty_int_boq > 0 && price_int_boq > 0) ? qty_int_boq * price_int_boq : 0;

		if( $("#currency").val() != "IDR"){
			rate_val_boq = parseInt( $("#rate").val().replace(/\./g, '') );
			nominal_currency_boq = (price_int_boq * rate_val_boq) * qty_int_boq ;
			nominal_val_boq = (qty_int_boq > 0 && price_int_boq > 0) ? nominal_currency_boq : 0;
		}

		$("#total_price-"+index).val(formatNumber(nominal_val_boq));

    });


	$('#table_detail tbody').on('change blur', 'tr td select.tax_opt', function () {
			getNominal();
  });

	$('#table_detail tbody').on('input change blur', 'tr td input.quantity, tr td input.price', function () {
		if($(this).val().trim().length === 0){
			$(this).val(0);
		}

		index       = $(this).data('id');
		qty_int     = parseInt( $("#quantity-"+index).val() );
		price_int   = parseInt( $("#price-"+index).val().replace(/\,/g, '') );
		nominal_val = (qty_int > 0) ? qty_int * price_int : 0;

		if( $("#currency").val() != "IDR"){
			rate_val = parseInt( $("#rate").val().replace(/\./g, '') );
			nominal_currency = (price_int * rate_val) * qty_int ;
			nominal_val = (qty_int > 0 && price_int > 0) ? nominal_currency : 0;
		}

		$("#nominal_detail-"+index).val(formatNumber(nominal_val));

		setTimeout(function(){
			getNominal();
			getAmount();
		}, 300);

    });


	$('#table_detail tbody').on('change', 'tr td input, tr td select', function () {
		storeDataDetail();
    });

	$('#deleteRow-detail').on( 'click', function () {

		get_table  = table_detail.row( $('#table_detail tbody tr.selected') );
		index      = get_table.index()

		total_data  = table_detail.data().count();

		if(total_data > 0){
			table_detail.row(index).remove().draw();

			table_detail.column( 0 ).data().each( function ( i ) {
				num = i+1;
				$('#table_detail tbody tr:eq(' + i + ') td:eq(0)').html(num);
				this_row = $('#table_detail tbody tr:eq(' + i + ')');
				this_row.find("input.rkap_desc").attr("id", 'rkap_desc-'+num);
				this_row.find("select.nature_opt").attr("id", 'nature_opt-'+num);
				this_row.find("select.tax_opt").attr("id", 'tax_opt-'+num);
				this_row.find("input.quantity").attr("id", 'quantity-'+num);
				this_row.find("input.quantity").attr('data-id', num);
				this_row.find("input.price").attr("id", 'price-'+num);
				this_row.find("input.price").attr('data-id', num);
				this_row.find("input.nominal_detail").attr("id", 'nominal_detail-'+num).data('id', num);

			});

			$(this).attr('disabled', true);

			getNominal();
			getAmount();
			storeDataDetail();
		}

    });

    $('#fpjp_date, #invoice_date').datepicker({
		format: 'dd-mm-yyyy',
		todayHighlight:'TRUE',
		autoclose: true,
    });

    $('#table_boq tbody').on('focus', 'tr td input.date_period', function () {
    	$(this).datepicker({
			format: 'dd-mm-yyyy',
			todayHighlight:'TRUE',
			autoclose: true,
			minDate:0,
	    });
    });

	$('#addRow-detail_boq').on( 'click', async function () {
		indexNow_detail_boq = table_boq.data().count();

			numDetailBoq = indexNow_detail_boq+1;

			lov_asset = '<option value="">-- Choose --</option>';
			lov_asset += '<option value="Fixed Asset">Fixed Asset</option>';
			lov_asset += '<option value="Expense">Expense</option>';
			lov_asset += '<option value="Non Asset">Non Asset</option>';
			lov_asset += '<option value="Intangible Asset">Intangible Asset</option>';
		   

			let fpjp_item      = '<div class="form-group m-b-0"><input id="fpjp_item-'+numDetailBoq+'" class="form-control input-sm fpjp_item" autocomplete="off"></div>';
			let fpjp_desc      = '<div class="form-group m-b-0"><input id="fpjp_desc-'+numDetailBoq+'" class="form-control input-sm fpjp_desc" autocomplete="off"></div>';
			// let uom      = '<div class="form-group m-b-0"><input id="uom-'+numDetailBoq+'" class="form-control input-sm uom" autocomplete="off"></div>';
			let uom     = '<div class="form-group m-b-0"><select id="uom_opt-'+ numDetailBoq +'" class="form-control input-sm uom select-center">';
							uom += uom_opt;
							uom += '</select></div>';
			let qty_boq       = '<div class="form-group m-b-0"><input id="qty_boq-'+numDetailBoq+'" data-id="'+numDetailBoq+'" class="form-control input-sm qty_boq text-center" value="1" min="1" max="99999" type="number"></div>';
			let unit_price          = '<div class="form-group m-b-0"><input id="unit_price-'+numDetailBoq+'" data-id="'+numDetailBoq+'" class="form-control input-sm unit_price text-right money-format" value="0"></div>';
			let total_price = '<div class="form-group m-b-0"><input id="total_price-'+numDetailBoq+'" class="form-control input-sm total_price text-right" value="0" readonly></div>';
			let serial_number   = '<div class="form-group m-b-0"><input id="serial_number-'+numDetailBoq+'" class="form-control input-sm serial_number" autocomplete="off"></div>';
			let merek   = '<div class="form-group m-b-0"><input id="merek-'+numDetailBoq+'" class="form-control input-sm merek" autocomplete="off"></div>';
			let umur_manfaat   = '<div class="form-group m-b-0"><input id="umur_manfaat-'+numDetailBoq+'" class="form-control input-sm umur_manfaat" autocomplete="off"></div>';
			let invoice_date  = '<div class="form-group m-b-0"><input id="invoice_date-'+numDetailBoq+'" class="form-control input-sm invoice_date date_period" ></div>';
			let receipt_date = '<div class="form-group m-b-0"><input id="receipt_date-'+numDetailBoq+'" class="form-control input-sm receipt_date date_period" ></div>';
			let asset_type = '<div class="form-group m-b-0"><select id="asset_type-'+numDetailBoq+'" class="form-control input-sm asset_type select2 select-center">'+lov_asset+'</select></div>';

		    table_boq.rows.add(
		       [{ 
		       			"no": numDetailBoq,
		    			"fpjp_item": fpjp_item,
		    			"fpjp_desc": fpjp_desc,
		    			"qty_boq": qty_boq,
		    			"uom": uom,
		    			"unit_price": unit_price,
		    			"total_price": total_price,
		    			"serial_number": serial_number,
		    			"merek": merek,
		    			"umur_manfaat": umur_manfaat,
		    			"invoice_date": invoice_date,
		    			"receipt_date": receipt_date,
		    			"asset_type": asset_type
	    		}]
		    ).draw();

				setTimeout(function(){
					$("#uom_opt-"+numDetailBoq).select2();
		    numDetailBoq++;
				}, 300);


	});

	$('#table_boq tbody').on( 'click', 'tr', function () {
		if (! $(this).hasClass('selected') ) {
			table_boq.$('tr.selected').removeClass('selected');
			total_data = table_boq.data().count();

			if(total_data > 1){
				$("#deleteRow-detail_boq").attr('disabled', false);
			}
			$(this).addClass('selected');
		}
	});

	$('#deleteRow-detail_boq').on( 'click', function () {

		get_table_boq  = table_boq.row( $('#table_boq tbody tr.selected') );
		index      = get_table_boq.index()

		total_data  = table_boq.data().count();

		if(total_data > 0){
			table_boq.row(index).remove().draw();

			table_boq.column( 0 ).data().each( function ( i ) {
				num = i+1;
				$('#table_boq tbody tr:eq(' + i + ') td:eq(0)').html(num);
				this_row = $('#table_boq tbody tr:eq(' + i + ')');
				this_row.find("input.fpjp_item").attr("id", 'fpjp_item-'+num);
				this_row.find("input.fpjp_desc").attr("id", 'fpjp_desc-'+num);
				this_row.find("input.qty_boq").attr("id", 'qty_boq-'+num);
				this_row.find("input.qty_boq").attr('data-id', num);
				this_row.find("input.uom").attr("id", 'uom-'+num);
				this_row.find("input.unit_price").attr("id", 'unit_price-'+num);
				this_row.find("input.unit_price").attr('data-id', 'unit_price-'+num);
				this_row.find("input.total_price").attr("id", 'total_price-'+num);
				this_row.find("select.asset_type").attr("id", 'asset_type-'+num);
				this_row.find("input.serial_number").attr("id", 'serial_number-'+num);
				this_row.find("input.merek").attr("id", 'merek-'+num);
				this_row.find("input.umur_manfaat").attr("id", 'umur_manfaat-'+num);
				this_row.find("input.invoice_date").attr("id", 'invoice_date-'+num);
				this_row.find("input.receipt_date").attr("id", 'receipt_date-'+num);

			});

			$(this).attr('disabled', true);
		}

    });

    $("#save_data").on('click', function () {

		getAmount();
		
		let id_fs        = $("#fs_header").val();
		let type         = $("#type").val();
		let type_name    = $("#type").find(':selected').attr('data-name');
		let type_cat     = $("#type").find(':selected').attr('data-category');
		let directorat   = $("#directorat").val();
		let division     = $("#division").val();
		let unit         = $("#unit").val();
		let fpjp_name    = $("#fpjp_name").val();
		let fpjp_date    = $("#fpjp_date").val();
		let currency     = $("#currency").val();
		let justif_cat   = $("#justif_cat").val();
		let submitter    = $("#submitter").val();
		let jabatan_sub  = $("#jabatan_sub").html();
		let no_invoice   = $("#no_invoice").val();
		let invoice_date = $("#invoice_date").val();
		let notes        = $("#notes").val();

		let vendor_val           = $("#vendor_name").find(':selected').attr('data-vendor');
		let nama_bank_val        = $("#data_bank").find(':selected').attr('data-bank');
		let pemilik_rekening_val = $("#account_name").val();
		let no_rekening_val      = $("#account_number").val();
		let site_code_val        = $("#site_code").val();

		var doc_list = [];

		let rate         = parseInt($("#rate").val().replace(/\./g, ''));
		let amount       = parseInt($("#amount").val().replace(/\./g, ''));
		let total_data   = table.data().count();
		let fatalNominal = false;
		let notice       = '';

		if( type_cat ==  "Non Justif"){
			justif_cat = "non_justif";
			id_fs = 0;
		}else{
			justif_cat = "justif";
		}

	    if (justif_cat == "justif"){

		    for (var i = 1; i <= total_data; i++) {
				fund_av       = $("#fund_av-"+i).val();
				nominal_val   = $("#nominal-"+i).val();

				if( parseInt(nominal_val.replace(/\./g, '')) > parseInt(fund_av.replace(/\./g, '')) ){
					fatalNominal =  true;
					notice       = 'Nilai nominal lebih besar dari Fund Available pada data ke '+i;
					break;
				}
		    }
		}

		let data_boq = [];

			if ($("#is_asset-1").is(":checked")) {
			   let total_detail_boq = table_boq.data().count();
			   let detail_data_boq  = [];
			   let total_boq = 0;

				for (var i = 1; i <= total_detail_boq; i++) {
					fpjp_item      = $("#fpjp_item-"+i).val();
					fpjp_desc      = $("#fpjp_desc-"+i).val();
					qty_boq        = $("#qty_boq-"+i).val();
					uom            = $("#uom-"+i).val();
					unit_price 	   = $("#unit_price-"+i).val();
					total_price    = $("#total_price-"+i).val();
					serial_number  = $("#serial_number-"+i).val();
					asset_type     = $("#asset_type-"+i).val();
					merek          = $("#merek-"+i).val();
					umur_manfaat   = $("#umur_manfaat-"+i).val();
					invoice_date   = $("#invoice_date-"+i).val();
					receipt_date   = $("#receipt_date-"+i).val();
					total_boq 	   += parseInt(total_price.replace(/\./g, ''));

					data_boq.push({'fpjp_item' : fpjp_item, 'fpjp_desc' : fpjp_desc, 'qty_boq' : qty_boq, 'uom' : uom, 'unit_price' : parseInt(unit_price.replace(/\./g, '')), 'total_price' : parseInt(total_price.replace(/\./g, '')), 'serial_number' : serial_number, 'asset_type' : asset_type, 'merek' : merek, 'umur_manfaat' : umur_manfaat, 'invoice_date' : invoice_date, 'receipt_date' : receipt_date});
			    }

			    if( total_boq > amount ){
					fatalNominal =  true;
					notice       = 'Nilai BOQ lebih besar dari Total Amount';
				}
		   }

		if(unit == "" || fpjp_name == "" /*|| type == 0 */|| amount == 0){
    		customNotif('Warning', "Please fill all field", 'warning');
		}
		else if (fatalNominal == true)
		{
			customNotif('Warning', notice, 'warning');
			return false;
		}
		else if(vendor_val == ""){
			customNotif('Warning', 'Plese fill Vendor Name!', 'warning');
		}
		else if(nama_bank_val == ""){
			customNotif('Warning', 'Plese fill Bank Name!', 'warning');
		}
		else if(pemilik_rekening_val == ""){
			customNotif('Warning', 'Plese fill Account Name!', 'warning');
		}
		else if(no_rekening_val == ""){
			customNotif('Warning', 'Plese fill Account Number!', 'warning');
		}
		else if(attachment_file == ""){
			customNotif('Warning', 'Upload Document!', 'warning');
		}
		else{

			let data_lines  = [];
			table.rows().eq(0).each( function ( index ) {
		    	j = index+1;
			    data = table.row( index ).data();

				line_name_val        = $("#line_name-"+j).val();
				nominal_val          = $("#nominal-"+j).val();
				original_amount      = $("#original_amount-"+j).val();

				rkap_val = 0;

				if (justif_cat == "justif"){
					rkap_val = data.id_rkap;
					fund_av  = $("#fund_av-"+j).val();
				}else{

				}
				
				fpjp_line_key_val = data.fpjp_line_key;
				data_detail     = fpjp_line_data[fpjp_line_key_val];
/*
				if(rkap_val != "" && line_name_val != "" && parseInt(fund_av.replace(/\./g, '')) > 0 && parseInt(nominal_val.replace(/\./g, '')) > 0){*/
				if( justif_cat == "justif"){

					if(parseInt(fund_av.replace(/\./g, '')) > 0 && parseInt(nominal_val.replace(/\./g, '')) > 0){
		    			data_lines.push({'id_rkap' : rkap_val, 'nominal' : parseInt(nominal_val.replace(/\./g, '')), 'line_name' : line_name_val, 'pemilik_rekening' : pemilik_rekening_val, 'nama_bank' : nama_bank_val, 'no_rekening' : no_rekening_val, 'original_amount' : original_amount, 'detail_data' : data_detail});
					} 
				}else{
		    			data_lines.push({'id_rkap' : rkap_val, 'nominal' : parseInt(nominal_val.replace(/\./g, '')), 'line_name' : line_name_val, 'pemilik_rekening' : pemilik_rekening_val, 'nama_bank' : nama_bank_val, 'no_rekening' : no_rekening_val, 'original_amount' : original_amount, 'detail_data' : data_detail});
				}
			});

		    data = {
								id_fs : id_fs,
								directorat : directorat,
								division : division,
								unit : unit,
								type : type,
								type_name : type_name,
								justif_cat : justif_cat,
								fpjp_name : fpjp_name,
								fpjp_date : fpjp_date,
								amount : amount,
								currency : currency,
								rate : rate,
								submitter : submitter,
								jabatan_sub : jabatan_sub,
								no_invoice : no_invoice,
								invoice_date : invoice_date,
								vendor : vendor_val,
								bank_name : nama_bank_val,
								pemilik_rekening : pemilik_rekening_val,
								no_rekening : no_rekening_val,
								site_code : site_code_val,
								notes : notes,
								doc_list : doc_list,
								attachment : attachment_file,
								data_line : data_lines,
								data_boq : data_boq
		    		}
			$(this).attr('disabled', true)

		    $.ajax({
		        url   : baseURL + 'fpjp/api/save_fpjp',
		        type  : "POST",
		        data  : data,
		        dataType: "json",
		        beforeSend  : function(){
                         customLoading('show');
                        },
		        success : function(result){
		        	if(result.status == true){
		        		customNotif('Success', "FPJP CREATED", 'success');
		        		setTimeout(function(){
		        			customLoading('hide');
		        			$(location).attr('href', baseURL + 'fpjp');
		        		}, 500);
		        	}
		        	else{
								$("#save_data").removeAttr('disabled');
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
			$("#justif_cat").html(opt_justif);
			$("#justif_cat").attr('disabled', true);
			$("#division").html(opt_default);
	  		if( $(this).val() != "0"){
	  			getDivision();
	  		}
			$("#unit").html(opt_default);
			$("#fs_header").html(opt_default);

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

			$("#justif_cat").html(opt_justif);
			$("#justif_cat").attr('disabled', true);
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

			$("#justif_cat").html(opt_justif);
			$("#justif_cat").attr('disabled', false);
			
			getSubmitter();

			let type_name    = $("#type").find(':selected').attr('data-category');

			if(type_name == "Non Justif"){

				table_detail.columns( [2] ).visible( true );
				$('#fpjp_lines').hide();
				$('#table_data_filter').show();
				$("#justif").addClass('d-none');
				table.columns( [1,2,4] ).visible( false );

				id_fs = 'non_justif';
				if( $("#unit").val() != "0"){
		  			table.draw();
		  		}

			}else{
				table_detail.columns( [2] ).visible( false );
				$('#fpjp_lines').show();
				getFS();
				id_fs = 0;
	  			table.draw();
			}
		}
	});



	$("#justif_cat").on("change", function(){

		justif_cat = $(this).val();

  		$("#fs_header").attr('disabled', true);
		$("#fs_header").html(opt_default);
		// table.clear().draw();
		if(justif_cat == "justif"){
			id_fs = 0;
			$("#justif").removeClass('d-none');
			table.columns( [1,2,4] ).visible( true );
			$('#table_data_filter').hide();
	  		if( $("#unit").val() != "0"){
	  			getFS();
	  		}
		}
		else {

			if(justif_cat == "non_justif"){
				$('#table_data_filter').show();
				$("#justif").addClass('d-none');
				table.columns( [1,2,4] ).visible( false );

				id_fs = 'non_justif';
				table.draw();
			}
		}

    });


	$("#fs_header").bind("click", function(e) {
	    lastValue = $(this).val();
	}).bind("change", function(e) {
		change = true;
		if(change == true){
			$("#table_data tbody").find("tr:not(:first)").remove();

			id_fs       = $(this).val();
			fs_currency = $(this).find(':selected').attr('data-currency');
			fs_rate     = $(this).find(':selected').attr('data-rate');

			$("#currency").val(fs_currency);
			$("#rate").val( formatNumber( fs_rate ) );

	  		if( fs_currency != "IDR"){
				$("#currency_rate").removeClass("d-none");
	  		}else{
				$("#currency_rate").addClass("d-none");
	  		}

  			table.draw();
		}
	});


	$("#type").on("change", function(){
		type_val      = $(this).val();
		type_name     = $(this).find(':selected').attr('data-name');
		type_cat      = $(this).find(':selected').attr('data-category');
		let total_dtl = table_detail.data().count();

		if(type_cat == "Non Justif"){

				table_detail.columns( [2] ).visible( true );
				$('#fpjp_lines').hide();
			$('#table_data_filter').show();
			$("#justif").addClass('d-none');
			table.columns( [1,2,4] ).visible( false );

			id_fs = 'non_justif';
			if( $("#unit").val() != "0"){
	  			table.draw();
	  		}

		}else{

			justif_cat = "justif";

				table_detail.columns( [2] ).visible( false );
				$('#fpjp_lines').show();
			id_fs = 0;
			table.draw();
			$("#justif").removeClass('d-none');
			table.columns( [1,2,4] ).visible( true );
			$('#table_data_filter').hide();
	  		if( $("#unit").val() != "0"){
	  			getFS();
	  			$("#fs_header").removeAttr('disabled');
	  		}

		}

		for (var i = 1; i <= total_dtl; i++) {
			$("#tax_opt-"+i).html(opt_tax);
	    }
    });

	$("#submitter").on("change", function(){
		getJabatan("submitter");
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

    function getNominal(){
		let total_detail = table_detail.data().count();
		let totalNominal = 0;

		for (var i = 1; i <= total_detail; i++) {
			val_tax = parseFloat($("#tax_opt-"+i).val());
			console.log(val_tax)
			nominal_detail_val = parseInt($("#nominal_detail-"+i).val().replace(/\./g, ''));
			if( parseInt(val_tax) > 0){
				count_tax = nominal_detail_val * (val_tax / 100 );
				nominal_detail_val += Math.round(count_tax);
			}
			totalNominal += nominal_detail_val;
	    }

	    $("#table_data tbody tr.selected").find("input.nominal").val(formatNumber(totalNominal));
    }

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
	        url   : baseURL + 'api-budget/load_fs_header',
	        type  : "POST",
	        data  : {directorat : directorat, division : division, unit : unit, category : 'fpjp'},
	        dataType: "json",
	        success : function(result){
        		let fs_header = opt_default;
	        	if(result.status == true){
					data = result.data;
		        	for(var i = 0; i < data.length; i++) {
					    obj = data[i];
					    fs_header += '<option value="'+ obj.id_fs +'" data-currency="'+ obj.currency +'" data-rate="'+ obj.rate +'">'+ obj.fs_number +' - '+ obj.fs_name +'</option>';
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
		 if(id_rkap == 0){
		 	dataNat = {id_rkap : id_rkap, category : 'edit'}
		 }else{
		 	dataNat = {id_rkap : id_rkap}
		 }

		type_name = $("#type").find(':selected').attr('data-name');
		selectedNature = '';
		if(type_name == "Reimbursement PPh 23"){
			selectedNature = 108;
		}
		if(type_name == "Unidentified Deposit"){
			selectedNature = 57987;
		}

	    $.ajax({
	        url   : baseURL + 'api-budget/load_nature_by_rkap',
	        type  : "POST",
	        data  : dataNat,
	        dataType: "json",
	        success : function(result){
				let nature_opt = opt_default;
	        	if(result.status == true){
					data = result.data;

					if(data.length > 1){
						nature_optOnly = '';
			        	for(var i = 0; i < data.length; i++) {
							obj = data[i];
							let selected = '';
						    if(obj.selected == true){
						    	selected = ' selected';
						    }
						    if(id_select == obj.id_coa){
						    	selected = ' selected';
						    }
						    if(selectedNature == obj.id_coa){
						    	nature_optOnly = '<option value="'+ obj.id_coa +'" selected>'+ obj.nature_desc +'</option>';
						    }
						    nature_opt += '<option value="'+ obj.id_coa +'"'+selected+'>'+ obj.nature_desc +'</option>';
						}
						if(nature_optOnly != ''){
							nature_opt = nature_optOnly;
						}
					}else{
						obj = data[0];
					    nature_opt = '<option value="'+ obj.id_coa +'">'+ obj.nature_desc +'</option>';
					}
	        	}
				$("#nature_opt-"+id_row).html(nature_opt);
				$("#nature_opt-"+id_row).attr('disabled', false);
				$("#nature_opt-"+id_row).css('cursor', 'default');
      			$("#nature_opt-"+id_row).select2();
	        }
	    });
	}

	function getUom() {

        return new Promise(resolve => {
            $.ajax({
	       				url   : baseURL + 'api-budget/load_uom',
                type  : "POST",
                dataType: "json",
                success : function(result){
					        	if(result.status == true){
											data = result.data;
								      for(var i = 0; i < data.length; i++) {
												obj = data[i];
							    			uom_opt += '<option value="'+obj.uom+'>'+ obj.uom +'</option>';
											}
					        	}
                    resolve(uom_opt);
                }
            });
        });
	}

	 $(".rkap_desc").keyup(function() {
        var maxChars = 150;
        if ($(this).val().length > maxChars) {
            $(this).val($(this).val().substr(0, maxChars));
            alert("This field can take a maximum of 150 characters");
        }
    });

   function getTax(id_row, id_rkap, id_select=0) {

		$("#tax_opt-"+id_row).attr('disabled', true);
		$("#tax_opt-"+id_row).css('cursor', 'wait');

	    if(id_select > 0 ){
			$("#tax_opt-"+id_row).val(id_select);
	    }
		$("#tax_opt-"+id_row).attr('disabled', false);
		$("#tax_opt-"+id_row).css('cursor', 'default');
	}


	function storeDataDetail(){

		let get_table      = table.row( $('#table_data tbody tr.selected') );
		let index          = get_table.index();
		let index_Num      = index+1;
		let data           = get_table.data();

		fpjp_line_key = data.fpjp_line_key;

		let total_detail = table_detail.data().count();
		let detail_data  = [];

		for (var i = 1; i <= total_detail; i++) {
			rkap_desc_val      = $("#rkap_desc-"+i).val();
			nature_val         = $("#nature_opt-"+i).val();
			quantity_val       = $("#quantity-"+i).val();
			price_val          = $("#price-"+i).val();
			nominal_detail_val = $("#nominal_detail-"+i).val();
			tax_val            = $("#tax_opt-"+i).val();

			detail_data.push({'rkap_desc' : rkap_desc_val, 'nature' : nature_val, 'quantity' : quantity_val, 'tax' : tax_val, 'price' : parseInt(price_val.replace(/\,/g, '')), 'nominal' : parseInt(nominal_detail_val.replace(/\./g, ''))});
	    }

    	fpjp_line_data[fpjp_line_key] = detail_data;

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
        fileName = this.files[0].name;
        fileType = this.files[0].type;
        fileSize = fileSize/1000
        j=0;

        if(fileSize > 31000){
          upload = false;
          alert('Max file size 30M')
          $(this).val(lastValue);
        }

        extension_allow = ['pdf','zip','rar'];
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

    $("#type").on('change', function(){
    	let type_fpjp    = $(this).find(':selected').attr('data-name');
    	if(type_fpjp == "Pembayaran Vendor" || type_fpjp == "Reimburse"){
    		 $("#aset").show();
    	}else{
    		 $("#aset").hide();
    		 $("#fpjp_boq").hide();
    		 $("#is_asset-1").attr("checked", false);
    	}

    });

    $('#is_asset-1').on( 'click', function () {
    	total_data = table_boq.data().count();
    	if ($(this).is(":checked")) {
		   $("#fpjp_boq").show();
		   $('#addRow-detail_boq').trigger('click');        
		   }else{
		   	if(total_data <= 1 && $('#fpjp_item-1').val() == ""){
		   		table_boq.rows().remove().draw();
		   	}
		   	$("#fpjp_boq").hide();
		   }
	});

    $("#fpjp_boq").hide();
    $("#aset").hide();

    $('#umur_manfaat_info').on( 'click', function () {
    	$("#modal_umur_manfaat").modal('show');
	});
	
	$('#table_data tbody').on('input', 'tr td input.original_amount', function (event) {

		  if (event.which >= 37 && event.which <= 40) return;
		  $(this).val(function(index, value) {
		    return value
		      // Keep only digits and decimal points:
		      .replace(/[^\d.]/g, "")
		      // Remove duplicated decimal point, if one exists:
		      .replace(/^(\d*\.)(.*)\.(.*)$/, '$1$2$3')
		      // Keep only two digits past the decimal point:
		      .replace(/\.(\d{2})\d+/, '.$1')
		      // Add thousands separators:
		      .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
		  });
    });
  });
</script>
