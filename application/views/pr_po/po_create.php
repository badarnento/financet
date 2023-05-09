
<?php $this->load->view('ilustration') ?>

<div class="row">
  <div class="white-box boxshadow m-b-0 mt-10 border-top-only-5 z-index-2 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">PR Detail</h5>
    </div>
  </div>
  <div class="white-box boxshadow z-index-2 py-10 border-bottom-only-5 small">
    <div class="row">
    	<form class="form-horizontal">
    	<div class="col-sm-6 col-md-5">
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">PR Number  <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label text-left"><?= $pr_number ?> &nbsp; <a class="btn btn-outline btn-xs btn-info" href="<?= $pr_link ?>" target="_blank"> <i class="fa fa-external-link"></i></i> View </a></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">PR Name <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="pr_name" class="control-label text-left"><?= $pr_name ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">PR Date <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label"><?= $pr_date ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
              <label class="col-sm-5 col-md-4 control-label text-left">Currency<?= ($pr_currency != "IDR") ? "/Rate" : "" ?> <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label id="pr_currency" class="control-label"><?= $pr_currency ?><?= ($pr_currency != "IDR") ? " / ".$pr_rate : "" ?></label>
              </div>
          </div>
        	<div class="form-group m-b-10">
          	<label class="col-sm-5 col-md-4 control-label text-left">Total Amount PR<span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="pr_amount" class="control-label"><?= $pr_amount ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Directorate <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label"><?= get_directorat($pr_directorat) ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Division <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label text-left"><?= get_division($pr_division) ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Unit <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label text-left"><?= get_unit($pr_unit) ?></label>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-md-5">

	        <div class="form-group m-b-10">
	            <label for="po_type" class="col-sm-5 col-md-4 control-label text-left">PO Type <span class="pull-right">:</span></label>
	            <div class="col-sm-7 col-md-6">
					<select name="po_type" id="po_type" class="form-control input-sm">
						<option value="Normal">Normal</option>
						<option value="Additional">Additional</option>
					</select>
	            </div>
	        </div>
	        <div class="form-group m-b-10 po_reference d-none">
	            <label for="po_reference" class="col-sm-5 col-md-4 control-label text-left">Po Reference <span class="pull-right">:</span></label>
	            <div class="col-sm-7 col-md-6">
					<select name="po_reference" id="po_reference" class="form-control input-sm" disabled="">
						<option value="0">-- Choose --</option>
					</select>
	            </div>
	        </div>
	        <div class="form-group m-b-10 po_reference d-none">
	            <label for="po_name" class="col-sm-5 col-md-4 control-label text-left">PO Name <span class="pull-right">:</span></label>
	            <div class="col-sm-7 col-md-6">
	              <textarea class="form-control input-sm" id="po_name" rows="2"readonly></textarea>
	            </div>
	        </div>
	        <div class="form-group m-b-10">
	            <label for="currency" class="col-sm-5 col-md-4 control-label text-left">Currency <span class="pull-right">:</span></label>
	            <div class="col-sm-7 col-md-6">
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
	        <div class="form-group m-b-10 rate d-none">
	            <label for="rate" class="col-sm-5 col-md-4 control-label text-left">Rate<span class="pull-right">:</span></label>
	            <div class="col-sm-7 col-md-6">
	              <input type="text" class="form-control input-sm money-format" id="rate" placeholder="Rate" value="10000">
	            </div>
	        </div>
	        <div class="form-group m-b-10">
	            <label for="po_category" class="col-sm-5 col-md-4 control-label text-left">PO Category <span class="pull-right">:</span></label>
	            <div class="col-sm-7 col-md-6">
					<select name="po_category" id="po_category" class="form-control input-sm">
						<!-- <option value="">-- Choose --</option> -->
						<?php $category_po_arr = array("PO Template", "Non Template", "MPA") ?>
						<?php foreach ($category_po_arr as $value) : ?>
							<option value="<?= $value ?>"><?= $value ?></option>
						<?php endforeach; ?>
					</select>
	            </div>
	        </div>
	        <div id="po_clause" class="form-group m-b-10 d-none">
	            <label for="attachment-3" class="col-sm-5 col-md-4 control-label text-left">Clause Attachment <span class="pull-right">:</span></label>
	            <div class="col-sm-7 col-md-6">
		                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
		                    <div id="fileinput" class="form-control input-sm" data-trigger="fileinput"> <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div><span class="input-group-addon btn btn-default btn-outline btn-file"> <span class="fileinput-new">Select file</span> <span class="fileinput-exists">Change</span>
		                    <input id="attachment-3" type="file" name="attachment-3" data-name="po" accept=".pdf"> </span> <a id="fileinput_remove-3" href="javascript:void(0)" class="input-group-addon btn btn-warning fileinput-exists" data-dismiss="fileinput">Remove</a>
		                </div>
	                    <div class="progress progress-lg d-none">
	                        <div id="progress-3" class="progress-bar progress-bar-info active progress-bar-striped" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%" role="progressbar"></div>
	                    </div>
	            </div>
	        </div>
	        <div class="form-group m-b-10">
	            <label for="po_amount_total" class="col-sm-5 col-md-4 control-label text-left">Total Amount<span class="currency_idr"></span><span class="pull-right">:</span></label>
	            <div class="col-sm-7 col-md-6">
          			<label id="po_amount_total" class="control-label">0</label>
	            </div>
	        </div>
         </div>
    	</form>
    </div>
  </div>
</div>



<div class="row">
  <div class="white-box boxshadow m-b-0 border-top-only-5 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">PO Lines</h5>
    </div>
  </div>
  <div class="white-box boxshadow border-bottom-only-5">
	<div class="row">
		<div id="tbl_search" class="col-md-10 positon-relative">
			<i class="fa fa-search positon-absolute p-5 mt-2 ml-5 pos"></i>
			<input class="input-field px-30 form-control bg-lightgrey input-sm  border-radius-5 mb-10" type="search" placeholder="Search here...">
		</div>
		<div class="col-md-2">
			<div class="checkbox mt-5 checkbox-info pull-right">
                <input id="copy_all" type="checkbox">
                <label for="copy_all">Copy to All</label>
            </div>
		</div>
		<div class="col-md-12">
	      <table id="table_data" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small hover w-full">
            <thead>
              <tr>
				<th class="text-center">No</th>
				<th class="text-center">PR Name</th>
				<th class="text-center">PR Amount<span class="currency_idr"></span></th>
				<th class="text-center">PO Number</th>
				<th class="text-center">PO Name</th>
				<th class="text-center">PO Amount<span class="currency_idr"></span></th>
				<th class="text-center" hidden="true">Currency Rate</th>
				<th class="text-center">PO Period</th>
				<th class="text-center">Vendor Name</th>
				<th class="text-center">Bank Name</th>
				<th class="text-center">Bank Account Name</th>
				<th class="text-center">Bank Account</th>
				<th class="text-center">Split</th>
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
      <h5 id="table_detail_title" class="font-weight-700 m-0 text-uppercase">Detail of line</h5>
    </div>
  </div>
  <div class="white-box boxshadow border-bottom-only-5">
	<div class="row">
		<div class="col-md-12">
	      <table id="table_detail" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small hover w-full">
	        <thead>
	        	<tr>
				 	<th class="text-center">No</th>
				 	<th class="text-center">Item Name</th>
		          	<th class="text-center">Item Description</th>
		          	<th class="text-center">Category Item</th>
		          	<th class="text-center">Qty</th>
		          	<th class="text-center">UoM</th>
		          	<th class="text-center">Unit Price<span class="currency_non_idr"></span></th>
		          	<th class="text-center">Total Price<span class="currency_non_idr"></span></th>
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
	      		<div class="col-md-10">
		      		<div class="form-group m-b-10">
		                <div class="attachment_group">
			  	        	<label class="col-sm-3 control-label text-left">Document Sourcing<span class="pull-right">:</span></label>
				            <div class="col-sm-7">
				                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
				                    <div id="fileinput" class="form-control input-sm" data-trigger="fileinput"> <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div><span class="input-group-addon btn btn-default btn-outline btn-file"> <span class="fileinput-new">Select file</span> <span class="fileinput-exists">Change</span>
				                    <input id="attachment-1" type="file" name="attachment-1" data-name="po" accept=".pdf,.zip,.rar"> </span> <a id="fileinput_remove-1" href="javascript:void(0)" class="input-group-addon btn btn-warning fileinput-exists" data-dismiss="fileinput">Remove</a>
				                </div>
			                    <div class="progress progress-lg d-none">
			                        <div id="progress-1" class="progress-bar progress-bar-info active progress-bar-striped" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%" role="progressbar"></div>
			                    </div>
							</div>
			            </div>
			        </div>
		        </div>
	  	    </div>
	  	    <div class="row">
		  	    <div class="col-md-10">
			  	    <div class="form-group m-b-10">
			            <label for="po_amount_total" class="col-sm-3 control-label text-left">MPA / Contract Reference<span class="currency_idr"></span><span class="pull-right">:</span></label>
			            <div class="col-sm-7">
		          			<input type="text" class="form-control input-sm" id="mpa" placeholder="MPA Reference" autocomplete="off">
			            </div>
			        </div>
			    </div>
			</div>
			<div class="row">
			    <div class="col-md-10">
			        <div class="form-group m-b-10">
			          	<label for="est_date" class="col-sm-3 control-label text-left">Estimate Date <span class="pull-right">:</span></label>
			            <div class="col-sm-7">
											<input type="text" class="form-control input-sm" id="est_date" placeholder="Estimate Date">
			            </div>
			        </div>
			    </div>
			</div>
		    <div class="row">
			    <div class="col-md-10">
			        <div class="form-group m-b-10">
			            <label for="top" class="col-sm-3 control-label text-left">TOP<span class="top"></span><span class="pull-right">:</span></label>
			            <div class="col-sm-7">
		          			<input type="text" class="form-control input-sm" id="top" placeholder="TOP" autocomplete="off">
			            </div>
			        </div>
			    </div>
			</div>
		    <div class="row">
		        <div class="col-md-10">
			  	    <div class="form-group m-b-10">
			            <label for="notes" class="col-sm-3 control-label text-left">Notes <span class="pull-right">:</span></label>
			            <div class="col-sm-7">
			              <textarea class="form-control input-sm" id="notes" rows="5"  maxlength="500"></textarea>
                        <span class="help-block"><small>Max 500 Character</small></span> 
			            </div>
			        </div>
			    </div>
			</div>
	      	<div class="row">
	      		<div class="col-sm-6 col-sm-offset-6">
			        <div class="form-group pull-right">
			        	<button type="button" id="return_pr" data-toggle="modal" data-target="#modal-return" class="btn btn-warning border-radius-5 w-150p"><i class="fa fa-save"></i> Return PR </button>
			        	<button type="button" id="save_data" class="btn btn-info border-radius-5 m-10 w-150p"><i class="fa fa-save"></i> Save </button>
			        </div>
	      		</div>
	      	</div>
      	</form>
  </div>
</div>



<div id="modal-return" class="modal fade" tabindex="-3" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h2 class="modal-title text-white" id="modal-return-label">Return this PR</h2>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group m-b-10">
                  <label for="notes_user" class="control-label">Notes to User <span class="pull-right">:</span></label>
                    <textarea class="form-control" id="notes_user" rows="3" placeholder="Please input notes"></textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
	      		<div class="form-group m-b-10">
	                <div class="attachment_group">
                  		<label for="attachment-2" class="control-label">Attachment <span class="pull-right">:</span></label>
		                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
		                    <div id="fileinput-2" class="form-control input-sm" data-trigger="fileinput"> <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div><span class="input-group-addon btn btn-default btn-outline btn-file"> <span class="fileinput-new">Select file</span> <span class="fileinput-exists">Change</span>
		                    <input id="attachment-2" type="file" name="attachment-2" data-name="po" accept=".pdf,.zip,.rar,.jpg"> </span> <a id="fileinput_remove-2" href="javascript:void(0)" class="input-group-addon btn btn-warning fileinput-exists" data-dismiss="fileinput">Remove</a>
		                </div>
	                    <div class="progress progress-lg d-none">
	                        <div id="progress-2" class="progress-bar progress-bar-info active progress-bar-striped" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%" role="progressbar"></div>
	                    </div>
		            </div>
		        </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cancel</button>
          <button type="button" class="btn btn-warning waves-effect" id="btn_return"><i class="fa fa-check-circle"></i> Return</button>
        </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function(){

	const pr_header_id = '<?= $pr_header_id ?>';
	const pr_number    = '<?= $pr_number ?>';
	const directorat   = <?= $pr_directorat ?>;
	const division     = <?= $pr_division ?>;
	const unit         = <?= $pr_unit ?>;
	const valDate      = '<?= date('d/m/Y') . " - " . date('d/m/Y') ?>';
	const opt_default  = '<option value="0" data-name="">-- Choose --</option>';
	let po_line_data   = {};
	let attachment_file1 = "";
	let attachment_file2 = "";
	let attachment_file3 = "";
    let attach_category = 'po';

	let url  = baseURL + 'purchase-order/api/load_data_pr_for_po_create';

	let track = 0;
	let counter = 0;
	let dot = ".";

    let table = $('#table_data').DataTable({
	    "data":[],
      "language"        : {
                            "emptyTable"  : "<span class='label label-danger'>Data Not Found!</span>",
                            "infoEmpty"   : "Data Kosong",
                            "processing"  : '<img src="' + imageLoadingSmall + '" alt="" />',
                            "search"      : "_INPUT_"
                          },
	    "columns":[
	    			{"data": "no", "width": "10px", "class": "text-center" },
	    			{"data": "pr_name", "width": "150px"},
	    			{"data": "pr_amount", "width": "100px", "class": "text-right" },
	    			{"data": "po_number", "width": "100px"},
	    			{"data": "po_line_name", "width": "150px"},
	    			{"data": "po_amount", "width": "140px", "class": "text-right" },
	    			{"data": "currency_rate", "width": "100px", "class": "text-right hidden" },
	    			{"data": "po_period", "width": "150px"},
	    			{"data": "vendor_name", "width": "200px"},
	    			{"data": "vendor_bank_name", "width": "130px"},
	    			{"data": "vendor_bank_account", "width": "150px"},
	    			{"data": "vendor_bank_account_number", "width": "130px"},
	    			{"data": "action_split", "width": "80px"}
	    		],
          "drawCallback": function ( settings ) {
          	// $(".select2").select2();
          	if(track == 0){
				let len = this.api().page.len();
          		$.ajax({
			        url   : baseURL + 'purchase-order/api/load_data_pr_for_po_create',
			        type  : "POST",
			        data  : {pr_header_id: pr_header_id,length: len},
			        dataType: "json",
			        success : function(result){
			        	data = result.data;
			        	for (var i = 0; i < data.length; i++) {
			        		row = data[i];
			        		counter++;
			        		table.rows.add(
							       [{
										"pr_lines_id": row.pr_lines_id,
										"po_line_key": row.po_line_key,
										"id_rkap_line": row.id_rkap_line,
										"no": counter,
										"pr_name": row.pr_name,
										"pr_amount": row.pr_amount,
										"po_number": row.po_number,
										"po_line_name": row.po_line_name,
										"po_amount": row.po_amount,
										"currency_rate": row.currency_rate,
										"po_period": row.po_period,
										"vendor_name": row.vendor_name,
										"vendor_bank_name": row.vendor_bank_name,
										"vendor_bank_account": row.vendor_bank_account,
										"vendor_bank_account_number": row.vendor_bank_account_number,
										"action_split": row.action_split
						    		}]
							    );
			        	}
			        	track++;
			        	table.draw();
			        }
			    });
				
			   
          	}
          	else if(track == 1) {

		    	$(".select2").select2();
	      		$('.po_period').daterangepicker({
		          locale: {
		            format: 'DD/MM/YYYY'
		          },
					"autoApply": true,
					// minDate:new Date,
				});

				setTimeout(function(){
					$('#table_data tbody tr').eq(0).trigger('click');
	        		setTimeout(function(){
		        		$('#table_data tbody tr').eq(0).addClass('selected');
					}, 200);
				}, 500);
	        	track++;
          	}

          },
	      "pageLength"      : 100,
	      "ordering"        : false,
	      "scrollY"         : 480,
	      "scrollX"         : true,
	      "scrollCollapse"  : true,
	      "autoWidth"       : true,
	      "bAutoWidth"      : true
	});

	let pr_lines_id = 0;
	let tracker = 1;


	$('#table_data tbody').on( 'click', 'tr', function () {
		if (! $(this).hasClass('selected') ) {
			table.$('tr.selected').removeClass('selected');
			$(this).addClass('selected');
			let get_table = table.row( this );
			let data      = get_table.data();
			let index     = get_table.index();
			num = index+1;
			$('#table_detail_title').html('Detail of line '+ num);

			pr_lines_id = data.pr_lines_id;
	    	table_detail.draw();
		}
	});

	$('#table_data_length').remove();
	$('#table_data_paginate').remove();
	$('#table_data_filter').remove();

	let url_detail  = baseURL + 'purchase-order/api/load_pr_detail_for_po';

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
													d.dot    = dot;
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
				    			{"data": "item_desc", "width": "250px", "class": "p-2" },
				    			{"data": "detail_desc", "width": "250px", "class": "p-2" },
				    			{"data": "category_item", "width": "250px", "class": "p-2" },
				    			{"data": "qty_edit", "width": "60px", "class": "p-2 text-center" },
				    			{"data": "uom", "width": "80px", "class": "p-2 text-center" },
				    			{"data": "unit_price", "width": "150px", "class": "p-2 text-right" },
				    			{"data": "total_price", "width": "150px", "class": "p-2 text-right" }
				    		],
          "drawCallback": function ( settings ) {
			setTimeout(function(){
				storeDataDetail();
			}, 1000);
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

	let table_detail = $('#table_detail').DataTable();;
	$('#table_detail_filter').remove();
	$('#table_detail_paginate').remove();
	$('#table_detail_length').remove();

	$('#table_data tbody').on('change', 'tr td select.vendor_name', function () {
		this_row   = $(this).parents("tr").index() + 1;

		let bank     = $(this).find(':selected').attr('data-bank');
		let nama_rek = $(this).find(':selected').attr('data-nama_rek');
		let no_rek   = $(this).find(':selected').attr('data-no_rek');

		$("#bank_name-"+this_row).val(bank);
		$("#account_name-"+this_row).val(nama_rek);
		$("#account_number-"+this_row).val(no_rek);

    });

	$('#table_data tbody').on('change', 'tr td input.po_number', function () {
		getTotalAmount();
    });

	$('#table_data tbody').on('change', 'tr td input.po_line_name', function () {
		getTotalAmount();
    });

	function drawDetail(){

		table.rows().eq(0).each( function ( index ) {

		    data = table.row( index ).data();
			pr_lines_id = data.pr_lines_id;
	    	table_detail.draw();
		});
	}


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
					$("#item_desc-"+num).val(value.item_desc);
					$("#qty-"+num).val(value.qty);
					$("#unit_price-"+num).val(value.unit_price);
					$("#total_price-"+num).val(value.total_price);
				});
			}
    	}
	});


	$('#table_data tbody').on('click', 'tr td button.action-split', function () {
		this_row = $(this).parents("tr")
		index    = table.row(this_row).index();
		get_data = table.row(this_row).data();
		counter++;

		j = index+1;

		let val_po_number    = $("#po_number-" + j).val();
		let val_po_line_name = $("#po_line_name-" + j).val();

		let po_number       = '<div class="form-group m-b-0"><input id="po_number-' + counter + '" data-id="' + counter + '" class="form-control input-sm po_number" value="' + val_po_number + '"></div>';
		let po_line_name    = '<div class="form-group m-b-0"><input id="po_line_name-' + counter + '" data-id="' + counter + '" class="form-control input-sm po_line_name" value="' + val_po_line_name + '"></div>';
		let po_amount       = '<div class="form-group m-b-0"><input id="po_amount-' + counter + '" data-id="' + counter + '" class="form-control input-sm po_amount text-right money-format" type="text" value="0" readonly></div>';
		let po_period    = '<div class="form-group m-b-0"><input class="form-control po_period input-daterange-datepicker input-sm" type="text" id="po_period-' + counter + '" data-id="' + counter + '" value="' +  valDate  + '"/></div>';
		let vendor_opt = '<div class="form-group m-b-0"><select id="vendor_name-' + counter + '" class="form-control input-sm vendor_name select2 select-center"></select></div>';
		let bank_name       = '<div class="form-group m-b-0"><input id="bank_name-' + counter + '" data-id="' + counter + '" class="form-control input-sm bank_name" readonly></div>';
		let account_name    = '<div class="form-group m-b-0"><input id="account_name-' + counter + '" data-id="' + counter + '" class="form-control input-sm account_name" readonly></div>';
		let account_number  = '<div class="form-group m-b-0"><input id="account_number-' + counter + '" data-id="' + counter + '" class="form-control input-sm account_number" readonly></div>';

		let action_split = 'Split from No ' + j;
		get_vendor(counter);

		let random_po_line_key = generateRandomKey(5);

	    table.rows.add(
	       [{
				"no": counter,
				"id_rkap_line": get_data.id_rkap_line,
				"pr_lines_id": get_data.pr_lines_id,
				"po_line_key": "k"+random_po_line_key,
				"no": counter,
				"pr_name": get_data.pr_name,
				"pr_amount": get_data.pr_amount,
				"po_number": po_number,
				"po_line_name": po_line_name,
				"po_amount": po_amount,
				"po_period": po_period,
				"vendor_name": vendor_opt,
				"vendor_bank_name": bank_name,
				"vendor_bank_account": account_name,
				"vendor_bank_account_number": account_number,
				"action_split": action_split
    		}]
	    ).draw();

    	setTimeout(function(){
			table.$('tr.selected').removeClass('selected');
	        $('#table_data tbody tr').eq(counter-1).addClass('selected');

	  		$('.po_period').daterangepicker({
	          locale: {
	            format: 'DD/MM/YYYY'
	          },
				"autoApply": true,
				// minDate:new Date,
			});
    		$("#vendor_name-"+counter).select2();

	      }, 500);
    });



	$('#table_detail tbody').on('input', 'tr td input.unit_price', function () {
		this_row   = $(this).parents("tr")
		get_data   = table_detail.row(this_row).data();
		pr_nominal = get_data.pr_price;
		po_nominal = $(this).val();
		currency = $("#currency").val();

		if( currency == "IDR"){
			if(parseInt(po_nominal.replace(/\./g, '')) > parseInt(pr_nominal.replace(/\./g, ''))){
				$(this).val(pr_nominal);
			}
		}

		
    });


	$('#table_detail tbody').on('change', 'tr td input', function () {
		data        = table_detail.row( $(this).parents('tr') ).data();
		pr_lines_id = data.pr_lines_id;
		id_detail   = data.pr_detail_id;
		id_row      = $(this).data('id');

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

	$('#po_category').on('change', function () {
		val_po_cat = $(this).val();

		if(val_po_cat == "Non Template"){
			$("#po_clause").removeClass('d-none');
		}else{
			$("#po_clause").addClass('d-none');
		}

    });

    $('#currency').on('change', function () {
		val_currency = $(this).val();
		/*$(".po_amount").val(0);
		$(".po_amount").removeClass('money-format');*/

		if(val_currency != "IDR"){
			dot = ",";
			$(".rate").removeClass('d-none');
			$(".unit_price").removeClass('money-format');
			$(".currency_idr").text(' (IDR)');
			$(".currency_non_idr").text('('+val_currency+')');
			$(".unit_price").val(0);
			$(".po_amount").val(0);


		}else{
			dot = ".";
			$(".rate").addClass('d-none');
			$(".unit_price").addClass('money-format');
			$(".currency_idr").text('');
			$(".currency_non_idr").text('');
		}
		// getNominal();
    });


    $('#rate').on('change', function () {
		getNominal();
    });

	$('#po_reference').on('change', function () {
		val_po_name = $(this).find(":selected").data('name');
		$("#po_name").html(val_po_name);
    });


	$('#table_detail tbody').on('change', 'tr td input.unit_price', function () {
		if($(this).val().trim().length === 0){
			$(this).val(0);
		}
		getNominal();
    });


   /* function getAmount() {
		let total_data  = table.data().count();
		let totalAmount = 0;

		for (var i = 1; i <= total_data; i++) {
			get_nominal = $("#nominal-"+i).val().replace(/,/g, '').replace(/\./g, '.');
			totalAmount += parseFloat(get_nominal);
	    }
		$("#amount").val(formatNumberDecimal(totalAmount));
	}

    function getNominal(){
		let total_detail = table_detail.data().count();
		let totalNominal = 0;

		for (var i = 1; i <= total_detail; i++) {
			nominal_detail_val = $("#nominal_detail-"+i).val().replace(/,/g, '').replace(/\./g, '.');
			totalNominal += parseFloat(nominal_detail_val);
	    }

	    $("#table_data tbody tr.selected").find("input.nominal").val(formatNumberDecimal(totalNominal));
    }*/

	function getTotalAmount(){
		let total = table.data().count();
		let totalAmount = 0;

		for (var i = 1; i <= total; i++) {
			if($("#po_number-"+i).val() != '' && $("#po_line_name-"+i).val() != ''){
				po_amount   = $("#po_amount-"+i).val();
				totalAmount += parseInt(po_amount.replace(/\./g, ''));
			}
	    }

	    $("#po_amount_total").html(formatNumber(totalAmount));
    }


    function getNominal(){
		let total_detail = table_detail.data().count();
		let totalNominal = 0;
		let rate = parseInt($("#rate").val().replace(/\./g, ''));
		for (var i = 1; i <= total_detail; i++) {

			if(dot == "."){
				nominal_detail_val = $("#total_price-"+i).val();
				totalNominal += parseInt(nominal_detail_val.replace(/\./g, ''));
			}else{
				nominal_detail_val = $("#total_price-"+i).val().replace(/,/g, '').replace(/\./g, '.');
				nominal_detail_val = nominal_detail_val * rate;
				totalNominal += parseInt(nominal_detail_val);
			}
				
	    }

	    console.log(totalNominal);

	   	 $("#table_data tbody tr.selected").find("input.po_amount").val(formatNumber(totalNominal));

	    getTotalAmount();
    }

    function getPoReference(){
	    $.ajax({
	        url   : baseURL + 'purchase-order/api/load_po_reference',
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
      		setTimeout(function(){
          	$("#po_reference").select2();
			    }, 300);
	        }
	    });
    }

    function get_vendor(id_row){

		// $("#vendor_name-"+id_row).attr('disabled', true);
		// $("#vendor_name-"+id_row).css('cursor', 'wait');

	    $.ajax({
	        url   : baseURL + 'purchase-order/api/get_vendor',
	        type  : "POST",
	        dataType: "json",
	        success : function(result){
				let vendor_opt = opt_default;
				data = result;

	        	for(var i = 0; i < data.length; i++) {
					obj = data[i];

				    vendor_opt += '<option value="'+ obj.nama_vendor +'" data-bank="'+ obj.nama_bank +'" data-nama_rek="'+ obj.nama_rekening +'" data-no_rek="'+ obj.acct_number +'">'+ obj.nama_vendor +'</option>';
				}
				$("#vendor_name-"+id_row).html(vendor_opt);
				// $("#vendor_name-"+id_row).attr('disabled', false);
				// $("#vendor_name-"+id_row).css('cursor', 'default');
		    	// $(".select2").select2();
	        }
	    });
    }


	$("#copy_all").on('click', function () {

		row_now = $('#table_data tbody tr.selected');
		let get_table      = table.row( row_now );
		let index          = get_table.index()
		let index_Num      = index+1;

		c_po_number   = $("#po_number-"+index_Num).val();
		c_po_name     = $("#po_line_name-"+index_Num).val();
		c_po_period   = $("#po_period-"+index_Num).val();
		c_vendor_name = $("#vendor_name-"+index_Num).val();

		if($(this).prop('checked')){
			$(".po_number").val(c_po_number);
			$(".po_line_name").val(c_po_name);
			$(".po_period").val(c_po_period);
			$(".vendor_name").val(c_vendor_name).trigger("change");
			$(".action-split").attr('disabled', true);
		}else{
			$(".po_number").val('');
			$(".po_line_name").val('');
			$(".po_period").val('');
			$(".vendor_name").val(0).trigger("change");;
			$("#po_number-"+index_Num).val(c_po_number);
			$("#po_line_name-"+index_Num).val(c_po_name);
			$("#po_period-"+index_Num).val(c_po_period);
			$("#vendor_name-"+index_Num).val(c_vendor_name).trigger("change");
			$(".action-split").attr('disabled',false);
		}

		getTotalAmount();
    });
	$("#save_data").on('click', function () {
		
		let total_data   = table.data().count();
		let data_po      = [];
		let data_empty   = [];
		let total_amount = 0;
		let po_type      = $("#po_type").val();
		let po_reference = $("#po_reference").val();
		let currency     = $("#currency").val();
		let rate         = $("#rate").val();
		let po_category  = $("#po_category").val();
		let mpa          = $("#mpa").val();
		let est_date     = $("#est_date").val();
		let top          = $("#top").val();
		let notes        = $("#notes").val();

		let fatalError = false;
		let fatalList0 = '';
		let fatalList1 = '';
		let fatalList2 = '';
		let fatalList3 = '';
		getTotalAmount();
		if(total_data == 1){

			po_number    = $("#po_number-1").val();
			po_line_name = $("#po_line_name-1").val();
			po_amount    = $("#po_amount-1").val();
			vendor_name  = $("#vendor_name-1").val();
			
			if(parseInt(po_amount.replace(/\./g, '')) < 1){
				fatalError =  true;
				if(fatalList0 == ""){
					fatalList0 = 1;
				}else{
					fatalList0 += ', ' +1;
				}
			}
			if(po_number == ""){
				fatalError =  true;
				if(fatalList1 == ""){
					fatalList1 = 1;
				}else{
					fatalList1 += ', ' +1;
				}
			}
			if(po_line_name == ""){
				fatalError =  true;
				if(fatalList2 == ""){
					fatalList2 = 1;
				}else{
					fatalList2 += ', ' +1;
				}
			}
			if(vendor_name == 0){
				fatalError =  true;
				if(fatalList3 == ""){
					fatalList3 = 1;
				}else{
					fatalList3 += ', ' +1;
				}
			}
		}

	    for (var i = 1; i <= total_data; i++) {
	    	j = i-1;
			
			data         = table.row( j ).data();
			po_number    = $("#po_number-"+i).val();
			po_line_name = $("#po_line_name-"+i).val();
			po_amount    = $("#po_amount-"+i).val();
			vendor_name  = $("#vendor_name-"+i).val();
			po_line_key  = data.po_line_key;

			if (po_line_key in po_line_data){
				if(parseInt(po_amount.replace(/\./g, '')) > 0){
					if(po_number == ""){
						fatalError =  true;
						if(fatalList1 == ""){
							fatalList1 = i;
						}else{
							fatalList1 += ', ' +i;
						}
					}
					if(po_line_name == ""){
						fatalError =  true;
						if(fatalList2 == ""){
							fatalList2 = i;
						}else{
							fatalList2 += ', ' +i;
						}
					}
					if(vendor_name == 0){
						fatalError =  true;
						if(fatalList3 == ""){
							fatalList3 = i;
						}else{
							fatalList3 += ', ' +i;
						}
					}
				}
			}
	    }

	    let notice       = '';
		let ttl_price 	 = '';
		let total 		 = '';
		let fatalNominal = false;

		for (var i = 1; i <= total_data; i++) {
			index = i-1;
			data = table.row( index ).data();
			ttl_price       = $("#po_amount-"+i).val();
			nominal_val 	= data.pr_amount;

			if( parseInt(ttl_price.replace(/\./g, '')) > parseInt(nominal_val.replace(/\./g, '')) ){
				fatalNominal =  true;
				notice       = 'Nilai PO Amount lebih dari PR Amount pada data ke '+i;
				break;
			}
	    }

	    ttl_rate  = '';
	    fatalRate = false;

	    for (var i = 1; i <= total_data; i++) {
		index = i-1;
	    data = table.row(index).data();
	    ttl_rate 			= $("#rate").val();
	    nominal_val 		= data.pr_amount;
	    currency_rate_val 	= data.currency_rate;
	    // rate_final 			= ttl_rate * currency_rate_val;

	    console.log(currency_rate_val);
	    if( parseInt(ttl_rate.replace(/\./g, '')) > parseInt(nominal_val.replace(/\./g, '')) ){
				fatalRate =  true;
				notice       = 'Nilai Rate lebih dari PR Amount pada data ke ';
			}
		}

	    if(fatalNominal == true){
	    	customNotif('warning', notice ,'warning');
	    	return false;
	    }

	    if(fatalRate == true){
	    	customNotif('warning', notice ,'warning');
	    	return false;
	    }
		
		if(po_type == "Additional" && po_reference == 0){
			customNotif('Warning', "Mohon isi PO Reference", 'warning');
		}
		else if(attachment_file1 == ""){
				customNotif('Warning', 'Mohon upload dokumen sourcing!', 'warning');
		}
		else if(po_category == "Non Template" && attachment_file3 == ""){
				customNotif('Warning', 'Mohon upload dokumen klausa!', 'warning');
		}
		else if(fatalError == true){
			if(fatalList0 != ""){
				customNotif('Warning', 'Isi PO Amount pada data ke '+fatalList0+'!', 'warning');
			}
			if(fatalList1 != ""){
				customNotif('Warning', 'Isi PO Number pada data ke '+fatalList1+'!', 'warning');
			}
			if(fatalList2 != ""){
				customNotif('Warning', 'Isi PO Name pada data ke '+fatalList2+'!', 'warning');
			}
			if(fatalList3 != ""){
				customNotif('Warning', 'Pilih Vendor pada data ke '+fatalList3+'!', 'warning');
			}
		}
		else{

			table.rows().eq(0).each( function ( index ) {
		    	j = index+1;
			    data = table.row( index ).data();

				po_number      = $("#po_number-"+j).val();
				po_line_name   = $("#po_line_name-"+j).val();
				po_amount      = $("#po_amount-"+j).val();
				po_period      = $("#po_period-"+j).val();
				vendor_name    = $("#vendor_name-"+j).val();
				bank_name      = $("#bank_name-"+j).val();
				account_name   = $("#account_name-"+j).val();
				account_number = $("#account_number-"+j).val();
				
				id_rkap_line = data.id_rkap_line;
				pr_lines_id  = data.pr_lines_id;
				po_line_key  = data.po_line_key;

				// if(po_number != "" && parseFloat(po_amount.replace(/,/g, '').replace(/\./g, '.')) > 0 && vendor_name != 0){
				if(po_number != "" && parseInt(po_amount.replace(/\./g, '')) > 0 && vendor_name != 0){
						
					total_amount += parseInt(po_amount.replace(/\./g, ''));
					// total_amount += parseFloat(po_amount.replace(/,/g, '').replace(/\./g, '.'));

					detail_po    = po_line_data[po_line_key];
		    		data_po.push({'pr_lines_id' : pr_lines_id, 'id_rkap_line' : id_rkap_line, 'po_number' : po_number, 'po_line_name' : po_line_name, 'po_amount' : 
		    			parseInt(po_amount.replace(/\./g, '')), 'po_period' : po_period, 'vendor_name' : vendor_name, 'bank_name' : bank_name, 'account_name' : account_name, 'account_number' : account_number, 'detail_po' : detail_po });
				}
			});


			if (data_po === undefined || data_po.length == 0) {
				customNotif('Warning', 'Mohon lengkapi data lines terelbih dulu!', 'warning');
			}else{
				$(this).attr('disabled', true);

			    data = {
							pr_header_id: pr_header_id,
							po_amount: total_amount,
							attachment : attachment_file1,
							attachment_clausa : attachment_file3,
							po_type: po_type,
							po_reference: po_reference,
							currency: currency,
							rate : rate,
							po_category : po_category,
							mpa : mpa,
							est_date : est_date,
							top : top,
							notes : notes,
							data_po : data_po
			    		}

				console.log(data);
			    $.ajax({
			        url   : baseURL + 'purchase-order/api/save_po',
			        type  : "POST",
			        data  : data,
			        dataType: "json",
			        beforeSend  : function(){
	                          customLoading('show');
	                        },
			        success : function(result){
			        	if(result.status == true){
			        		customNotif('Success', "PO CREATED", 'success');
			        		setTimeout(function(){
			        			customLoading('hide');
			        			$(location).attr('href', baseURL + 'purchase-order');
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
		}
	});

	$("#btn_return").on('click', function () {

		notes_user = $("#notes_user").val();
		
		if (notes_user == "") {
			customNotif('Warning', 'Please fill notes', 'warning');
		}else{
			$(this).attr('disabled', true);
		    data = {
						pr_header_id: pr_header_id,
						notes_user: notes_user,
						attachment : attachment_file2,
		    		}
			
		    $.ajax({
		        url   : baseURL + 'purchase-order/api/return_pr',
		        type  : "POST",
		        data  : data,
		        dataType: "json",
		        beforeSend  : function(){
                          customLoading('show');
                        },
		        success : function(result){
		        	if(result.status == true){
		        		customNotif('Success', "PR Returned", 'success');
		        		setTimeout(function(){
		        			customLoading('hide');
		        			$(location).attr('href', baseURL + 'purchase-order');
		        		}, 500);
		        	}
		        	else{
						$("#btn_return").removeAttr('disabled');
		        		customLoading('hide');
		        		customNotif('Error', result.messages, 'error');
		        	}
		        }
		    });
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
			data          = table_detail.row( x ).data();
			pr_detail_id  = data.pr_detail_id;
			po_desc_val   = $("#po_desc-"+i).val();
			item_desc     = $("#item_desc-"+i).val();
			qty           = $("#qty-"+i).val();
			console.log(qty);
			unit_price    = $("#unit_price-"+i).val();
			total_price   = $("#total_price-"+i).val();
			uom           = data.uom;
			category_item = data.category_item;

			detail_data.push({'pr_detail_id' : pr_detail_id, 'po_desc' : po_desc_val, 'category_item' : category_item, 'item_desc' : item_desc, 'qty' : qty, 'uom' : uom, 'unit_price' : unit_price, 'total_price' : total_price});
	    }

	    po_line_data[po_line_key] = detail_data;

	}

	function formatNumberDecimal(x,y=',') {
		if(y=="."){
			return formatNumber(x);
		}else{
			  return x.toString().replace(/[^\d.]/g, "")
				      .replace(/^(\d*\.)(.*)\.(.*)$/, '$1$2$3')
				      .replace(/\.(\d{2})\d+/, '.$1')
				      .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		}
	}

	$('#table_detail tbody').on('input blur', 'tr td input.qty, tr td input.unit_price', function () {
		if($(this).val().trim().length === 0){
			$(this).val(0);
		}
		index     = $(this).data('id');
		qty_int   = parseInt( $("#qty-"+index).val() );
		price_int = $("#unit_price-"+index);
		currency = $("#currency").val();


		if( currency == "IDR"){
			$(this).mask('000.000.000.000.000', {reverse: true});
			price_val = formatNumber(price_int.val().replace(/\./g, ''));
		}else{
			$(this).mask('000,000,000,000,000.00', {reverse: true});

			/*price_val = price_int.val().toString().replace(/[^\d.]/g, "")
				      .replace(/^(\d*\.)(.*)\.(.*)$/, '$1$2$3')
				      .replace(/\.(\d{2})\d+/, '.$1')
				      .replace(/\B(?=(\d{3})+(?!\d))/g, ",");*/

			price_val = formatNumberDecimal(price_int.val());

		}
		price_int.val( price_val);

		if( dot == "."){
			price_rpl   = price_int.val().replace(/,/g, '').replace(/\./g, '');
		}else{
			price_rpl   = price_int.val().replace(/,/g, '').replace(/\./g, '.');
		}
		nominal_val = (qty_int > 0 && price_rpl > 0) ? qty_int * price_rpl : 0;

		$("#total_price-"+index).val(formatNumberDecimal(nominal_val, dot));

    });

    let jqXHR;

    $("#fileinput_remove-1").on("click", function(e) {
    	deleteFile(1);
		jqXHR.abort();
	    reset_proggress(1);
	});

    $("#attachment-1").bind("click", function(e) {
        lastValue = $(this).val();
        reset_proggress(1);
    }).bind("change", function(e) {
      upload = true;
      if(lastValue != ""){
        upload = false;
        if(deleteFile(1)){
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
          $("#progress-1").parent().removeClass('d-none');
              
          file = $('#attachment-1')[0].files[0];
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
                          $("#progress-1").css('width', percentage+'%');
                          $("#progress-1").html(percentage+'%');
                          
                          }
                      }, false);
                      return xhr;
                  },
                  success: function(result) {
                      $("#progress-1").css('width', '100%');
                      if(result.status == true){
                              $("#progress-1").html('100%');
                              setTimeout(function(){
                                $("#progress-1").html('Complete!');
                                $("#progress-1").removeClass('progress-bar-info');
                                $("#progress-1").addClass('progress-bar-success');
                                attachment_file1 = result.messages;
                      }, 1000);
                            }else{
                              setTimeout(function(){
                                $("#progress-1").html(result.messages);
                                $("#progress-1").removeClass('progress-bar-info');
                                $("#progress-1").addClass('progress-bar-danger');
                      }, 500);
                      }
                  },
              error: function (xhr, ajaxOptions, thrownError) {
                $("#progress-1").parent().addClass('d-none');
                    $("#progress-1").css('width', '100%');
                    $("#progress-1").removeClass('progress-bar-info');
                    setTimeout(function(){
                      $("#progress-1").addClass('progress-bar-danger');
                  $("#progress-1").parent().removeClass('d-none');
                      $("#progress-1").html('Error Connection');
            }, 200);
              }
              });
              return false;

        }
              
      }
    });

    let jqXHR2;

    $("#fileinput_remove-2").on("click", function(e) {
    	deleteFile(2);
		jqXHR2.abort();
	    reset_proggress(2);
	});

    $("#attachment-2").bind("click", function(e) {
        lastValue = $(this).val();
        reset_proggress(2);
    }).bind("change", function(e) {
      upload2 = true;
      if(lastValue != ""){
        upload2 = false;
        if(deleteFile(2)){
          upload2 = true;
        }
      }
      if($(this).val() != ""){

        fileSize = this.files[0].size;
        fileName = this.files[0].name;
        fileType = this.files[0].type;
        fileSize = fileSize/1000
        j=0;

        if(fileSize > 11000){
          upload2 = false;
          alert('Max file size 10M')
          $(this).val(lastValue);
        }

        extension_allow = ['pdf','zip','rar'];
        extension       = fileName.split('.').pop().toLowerCase();
        if (extension_allow.indexOf(extension) < 0) {
			upload2 = false;
			alert('Extention not allowed');
			$(this).val(lastValue);
        }

        if(upload2){
          $("#progress-2").parent().removeClass('d-none');
              
          file2 = $('#attachment-2')[0].files[0];
          formData2 = new FormData();
          formData2.append('file', file2);
          formData2.append('category', attach_category);

              jqXHR2  = $.ajax({
                  url: baseURL + "upload/do_upload",
                  type: 'POST',
                  data: formData2,
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
                          $("#progress-2").css('width', percentage+'%');
                          $("#progress-2").html(percentage+'%');
                          
                          }
                      }, false);
                      return xhr;
                  },
                  success: function(result) {
                      $("#progress-2").css('width', '100%');
                      if(result.status == true){
                              $("#progress-2").html('100%');
                              setTimeout(function(){
                                $("#progress-2").html('Complete!');
                                $("#progress-2").removeClass('progress-bar-info');
                                $("#progress-2").addClass('progress-bar-success');
                                attachment_file2 = result.messages;
                      }, 1000);
                            }else{
                              setTimeout(function(){
                                $("#progress-2").html(result.messages);
                                $("#progress-2").removeClass('progress-bar-info');
                                $("#progress-2").addClass('progress-bar-danger');
                      }, 500);
                      }
                  },
              error: function (xhr, ajaxOptions, thrownError) {
                $("#progress-2").parent().addClass('d-none');
                    $("#progress-2").css('width', '100%');
                    $("#progress-2").removeClass('progress-bar-info');
                    setTimeout(function(){
                      $("#progress-2").addClass('progress-bar-danger');
                  $("#progress-2").parent().removeClass('d-none');
                      $("#progress-2").html('Error Connection');
            }, 200);
              }
              });
              return false;

        }
              
      }
    });

    let jqXHR3;

    $("#fileinput_remove-3").on("click", function(e) {
    	deleteFile(3);
		jqXHR3.abort();
	    reset_proggress(3);
	});

    $("#attachment-3").bind("click", function(e) {
        lastValue = $(this).val();
        reset_proggress(3);
    }).bind("change", function(e) {
      upload3 = true;
      if(lastValue != ""){
        upload3 = false;
        if(deleteFile(3)){
          upload3 = true;
        }
      }
      if($(this).val() != ""){

        fileSize = this.files[0].size;
        fileName = this.files[0].name;
        fileType = this.files[0].type;
        fileSize = fileSize/1000
        j=0;

        if(fileSize > 3100){
          upload3 = false;
          alert('Max file size 3M')
          $(this).val(lastValue);
        }

        extension_allow = ['pdf'];
        extension       = fileName.split('.').pop().toLowerCase();
        if (extension_allow.indexOf(extension) < 0) {
			upload3 = false;
			alert('Extention not allowed');
			$(this).val(lastValue);
        }

        if(upload3){
          $("#progress-3").parent().removeClass('d-none');
              
          file3 = $('#attachment-3')[0].files[0];
          formData3 = new FormData();
          formData3.append('file', file3);
          formData3.append('category', attach_category);

              jqXHR3  = $.ajax({
                  url: baseURL + "upload/do_upload",
                  type: 'POST',
                  data: formData3,
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
                          $("#progress-3").css('width', percentage+'%');
                          $("#progress-3").html(percentage+'%');
                          
                          }
                      }, false);
                      return xhr;
                  },
                  success: function(result) {
                      $("#progress-3").css('width', '100%');
                      if(result.status == true){
                              $("#progress-3").html('100%');
                              setTimeout(function(){
                                $("#progress-3").html('Complete!');
                                $("#progress-3").removeClass('progress-bar-info');
                                $("#progress-3").addClass('progress-bar-success');
                                attachment_file3 = result.messages;
                      }, 1000);
                            }else{
                              setTimeout(function(){
                                $("#progress-3").html(result.messages);
                                $("#progress-3").removeClass('progress-bar-info');
                                $("#progress-3").addClass('progress-bar-danger');
                      }, 500);
                      }
                  },
              error: function (xhr, ajaxOptions, thrownError) {
                $("#progress-3").parent().addClass('d-none');
                    $("#progress-3").css('width', '100%');
                    $("#progress-3").removeClass('progress-bar-info');
                    setTimeout(function(){
                      $("#progress-3").addClass('progress-bar-danger');
                  $("#progress-3").parent().removeClass('d-none');
                      $("#progress-3").html('Error Connection');
            }, 200);
              }
              });
              return false;

        }
              
      }
    });

	function reset_proggress(i){
      $('#file_input-'+i).val("");
      $("#delete_file-"+i).addClass('d-none');
      $("#progress-"+i).removeClass("progress-bar-success");
      $("#progress-"+i).removeClass("progress-bar-danger");
      $("#progress-"+i).parent().addClass('d-none');
      $("#progress-"+i).css('width', '0%');
      $("#progress-"+i).html('0%');
    }

	 function deleteFile(i){

	 	if(i == 1){
	 		attachment_f = attachment_file1;
	 	}
	 	else if(i == 2){
	 		attachment_f = attachment_file2;
	 	}else{
	 		attachment_f = attachment_file3;
	 	}

      $.ajax({
            url   : baseURL + 'upload/delete',
            type  : "POST",
            data  : {file: attachment_f, category: attach_category},
            dataType: "json",
            success : function(result){
				 	if(i == 1){
              			attachment_file1 = '';
	 				}
	 				else if(i == 2){
              			attachment_file2 = '';
				 	}else{
              			attachment_file3 = '';
				 	}
              		return true;
            }
        });

        return true;
    }

  });
</script>