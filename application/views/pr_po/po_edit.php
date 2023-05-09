
<?php $this->load->view('ilustration') ?>

<div class="row">
  <div class="white-box boxshadow m-b-0 mt-10 border-top-only-5 z-index-2 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">PO Detail</h5>
    </div>
  </div>
  <div class="white-box boxshadow z-index-2 py-10 border-bottom-only-5 small">
    <div class="row">
    	<form class="form-horizontal">
    	<div class="col-sm-6 col-md-5">
        	<div class="form-group m-b-10">
          	<label class="col-sm-5 col-md-4 control-label text-left">PR Number <span class="pull-right">:</span></label>
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
            <label class="col-sm-5 col-md-4 control-label text-left">PO Date Created<span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label"><?= $po_date ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
              <label class="col-sm-5 col-md-4 control-label text-left">Currency<?= ($po_currency != "IDR") ? "/Rate" : "" ?> <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label id="pr_currency" class="control-label"><?= strtoupper($po_currency) ?><?= (strtoupper($po_currency) != "IDR") ? " / ".$pr_rate : "" ?></label>
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
              <label class="control-label"><?= get_directorat($po_directorat) ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Division <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label text-left"><?= get_division($po_division) ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Unit <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label text-left"><?= get_unit($po_unit) ?></label>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-md-5">

            <div class="form-group m-b-10">
	            <label class="col-sm-5 col-md-4 control-label text-left">PO Type <span class="pull-right">:</span></label>
	            <div class="col-sm-7 col-md-8">
	              <label class="control-label text-left"><?= $po_type ?></label>
	            </div>
	        </div>
	        <?php if($po_type != "Normal"): ?>
        	<div class="form-group m-b-10">
	            <label class="col-sm-5 col-md-4 control-label text-left">PO Reference <span class="pull-right">:</span></label>
	            <div class="col-sm-7 col-md-8">
	              <label class="control-label text-left"><?= $po_reference ?></label>
	            </div>
	        </div>
	        <?php endif; ?>
	        <div class="form-group m-b-10">
	            <label for="po_reference" class="col-sm-5 col-md-4 control-label text-left">Po Category <span class="pull-right">:</span></label>
	            <div class="col-sm-7 col-md-6">
					<select name="po_reference" id="po_category" class="form-control input-sm">
						<!-- <option value="">-- Choose --</option> -->
						<?php $category_po_arr = array("PO Template", "Non Template", "MPA") ?>
						<?php foreach ($category_po_arr as $value) : ?>
							<option value="<?= $value ?>"<?= ($value == $po_category) ? ' selected' : ''?>><?= $value ?></option>
						<?php endforeach; ?>
					</select>
	            </div>
	        </div>

	        <div class="form-group m-b-10">
	            <label for="po_amount_total" class="col-sm-5 col-md-4 control-label text-left">Total Amount PO<span class="pull-right">:</span></label>
	            <div class="col-sm-7 col-md-6">
          			<label id="po_amount_total" class="control-label"><?= $po_amount ?></label>
	            </div>
	        </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Status <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-6">
	            	<label class="control-label text-left">
	            		<?php 
		            		if(strtolower($po_status) == "approved"){
								$badge = "badge-success";
							}else if(strtolower($po_status) == "canceled" || strtolower($po_status) == "rejected"){
								$badge = "badge-danger";
							}else if(strtolower($po_status) == "partial paid" || strtolower($po_status) == "paid"){
								$badge = "badge-info";
							}else if(strtolower($po_status) == "returned"){
								$badge = "badge-warning";
							}else if(strtolower($po_status) == "request_approve"){
								$po_status = "Waiting approval";
								$badge = "badge-default";
							}
							else{
								$po_status = "DRAFT";
								$badge = "badge-default";
							}
						?>
	            		<div class="badge <?= $badge ?> text-lowercase font-size-12"> <?= ucwords($po_status) ?> </div>
	        		</label>
              </div>
          </div>


        	<div class="form-group m-b-10">
        		<label class="col-sm-5 col-md-4 control-label text-left">Last Update <span class="pull-right">:</span></label>
	            <div class="col-sm-7 col-md-8">
	            	<label class="control-label text-left" style="word-break: break-all"><?= $po_status_desc ?> at <?= $po_last_update ?></label>
	            	<?php if(isset($po_approval_remark)): ?>
	            	<br>
	            	<!-- <label class="control-label text-left">&quot;<?= $po_approval_remark ?>&quot;</label> -->
	            	<?php endif; ?>
	            </div>
	        </div>

	    	<div class="form-group m-b-10">
	    		<label class="col-sm-5 col-md-4 control-label text-left">PO History <span class="pull-right">:</span></label>
	            <div class="col-sm-7 col-md-8">
	    			<?php if($po_history): ?>
	    			<button id="btn-comment" class="btn btn-xs mt-2 btn-info" data-toggle="modal" data-target="#modal-comment" type="button" > <i class="fa fa- fa-comment"></i> Show History </button>
					<?php else: ?>
	            	<label class="control-label text-left">&ndash;</label>
	        		<?php endif; ?>
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
		<div class="col-md-12">
	      <table id="table_data" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small hover w-full">
            <thead>
              <tr>
				<th class="text-center">No</th>
				<th class="text-center" hidden="true">No po</th>
				<th class="text-center">PR Name</th>
				<th class="text-center">PR Amount</th>
				<th class="text-center">PO Number</th>
				<th class="text-center">PO Name</th>
				<th class="text-center">PO Amount</th>
				<th class="text-center">PO Period</th>
				<th class="text-center">Vendor Name</th>
				<th class="text-center">Bank Name</th>
				<th class="text-center">Bank Account</th>
				<th class="text-center">Bank Account Number</th>
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
		          	<th class="text-center">Unit Price</th>
		          	<th class="text-center">Total Price</th>
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
                  <div id="attachment_view" class="col-sm-9">
                    <label class="control-label text-left"><a id="po_attachment" href="<?= base_url("download/") . encrypt_string("uploads/po_attachment/".$po_attachment, true) ?>" title="<?= $po_attachment ?>" target="_blank"><?= substr($po_attachment, 0, 25). "..."?> </a> <button type="button" id="change_file" class="btn btn-warning btn-xs m-l-10 px-10 py-2 pull-right"><i class="fa fa-edit"></i> Change </button> </label>
		            </div>
		                  <div id="upload_attachment" class="col-sm-7 d-none">
		                      <div class="fileinput fileinput-new input-group" data-provides="fileinput">
		                          <div id="fileinput" class="form-control input-sm" data-trigger="fileinput"> <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div><span class="input-group-addon btn btn-default btn-file"> <span class="fileinput-new">Select file</span> <span class="fileinput-exists">Change</span>
		                          <input id="attachment" type="file" name="attachment" data-name="po" accept=".pdf,.zip,.rar"> </span> <a id="fileinput_remove" href="javascript:void(0)" class="input-group-addon btn btn-warning fileinput-exists" data-dismiss="fileinput">Remove</a>
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
      		<div class="col-md-10">
	      		<div class="form-group m-b-10">
		            <label for="po_amount_total" class="col-sm-3 control-label text-left">MPA / Contract Reference<span class="pull-right">:</span></label>
		            <div class="col-sm-7">
	          			<input type="text" class="form-control" id="mpa" placeholder="MPA Reference" autocomplete="off" value="<?= $mpa_reference ?>">
		            </div>
		        </div>
	        </div>
			    <div class="col-md-10">
			        <div class="form-group m-b-10">
			          	<label for="est_date" class="col-sm-3 control-label text-left">Estimate Date <span class="pull-right">:</span></label>
			            <div class="col-sm-7">
											<input type="text" class="form-control input-sm" id="est_date" placeholder="Estimate Date" value="<?= $est_date ?>">
			            </div>
			        </div>
			    </div>
      		<div class="col-md-10">
	      		<div class="form-group m-b-10">
		            <label for="po_amount_total" class="col-sm-3 control-label text-left">TOP<span class="pull-right">:</span></label>
		            <div class="col-sm-7">
	          			<input type="text" class="form-control" id="top" placeholder="TOP" autocomplete="off" value="<?= $top ?>">
		          </div>
		        </div>
	        </div>
	  	    <div class="col-md-10">
            <div class="form-group m-b-10">
              <label for="submitter" class="col-sm-3 control-label text-left">Notes <span class="pull-right">:</span></label>
              <div class="col-sm-7">
            <textarea class="form-control input-sm" id="notes" rows="5"><?= $notes ?></textarea>
                </div>
            </div>
          </div>
      	</form>
      	<div class="row">
	<div class="col-sm-6 col-sm-offset-6">
  		<button id="save_data" class="btn btn-info border-radius-5 w-150p pull-right" type="button"><i class="fa fa-save"></i> Save</button>
	</div>
</div>
  </div>
</div>


<?php if($po_history): ?>
<style>
	table.comment-history tbody td {
	  word-break: break-word;
	  vertical-align: midle;
	}
</style>
<div id="modal-comment" class="modal fade small" tabindex="-3" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h2 class="modal-title text-white font-size-18" id="modal-comment-label">PO History</h2>
        </div>
        <div class="modal-body">
	        <div class="row">
	        	<div class="col-md-12">
    			  <table class="table comment-history display table-striped table-responsive dataTable w-full">
			        <thead>
			        	<tr>
							<th width="30%">PIC</th>
							<th width="20%">STATUS</th>
							<th width="30%">REMARK</th>
							<th width="20%">ACTION DATE</th>
				        </tr>
			        </thead>
			        <tbody>
		                <?php foreach ($po_history as $key => $value):
		                if($value['STATUS'] == "approved"){
		                  $badge = "badge-success";
		                }else if($value['STATUS'] == "returned"){
		                  $badge = "badge-warning";
		                }else if($value['STATUS'] == "assigned"){
		                  $badge = "badge-info";
		                }else if($value['STATUS'] == "rejected"){
		                  $badge = "badge-danger";
		                }else{
		                  $badge = "badge-default";
		                }
		               ?>
				        <tr>
				        	<td><?= $value['PIC_NAME'] ?></td>
				        	<td><div class="badge <?= $badge ?> text-lowercase"> <?= ucfirst($value['STATUS']) ?> </div> </td>
				        	<td><?= $value['REMARK'] ?></td>
				        	<td><?= dateFormat($value['ACTION_DATE'], 'fintool', false) ?></td>
				        </tr>
    					<?php endforeach;?>
			        </tbody>
			      </table>
			    </div>
	        </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger w-100p border-radius-5 btn-outline waves-effect" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
        </div>
    </div>
  </div>
</div>
<?php endif; ?>

<script>
  $(document).ready(function(){

  	const redirect_po = '<?= $this->uri->segment(3) ?>';

	const pr_header_id = '<?= $pr_header_id ?>';
	const po_header_id = '<?= $po_header_id ?>';
	const pr_number    = '<?= $pr_number ?>';
	const opt_default  = '<option value="0" data-name="">-- Choose --</option>';
	let po_line_data   = {};

	let url  = baseURL + 'purchase-order/api/load_data_po_lines_edit';


	let last_attachment  = "<?= $po_attachment ?>";
	let attachment_file = last_attachment;
	let attach_category = $('#attachment').data('name');


	Pace.track(function(){
	    $('#table_data').DataTable({
	      "serverSide"      : true,
	      "processing"      : true,
	      "ajax"            : {
									"url"  : url,
									"type" : "POST",
									"dataType": "json",
									"data"    : function ( d ) {
					                              d.po_header_id = po_header_id;
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
				    			{"data": "po_number_edit", "width": "10px", "class": "hidden" },
				    			{"data": "pr_name", "width": "150px"},
				    			{"data": "pr_amount", "width": "120px", "class": "text-right" },
				    			{"data": "po_number", "width": "100px"},
				    			{"data": "po_line_name", "width": "150px"},
				    			{"data": "po_amount", "width": "120px", "class": "text-right" },
				    			{"data": "po_period", "width": "150px"},
				    			{"data": "vendor_name", "width": "150px"},
				    			{"data": "vendor_bank_name", "width": "150px"},
				    			{"data": "vendor_bank_account", "width": "150px"},
				    			{"data": "vendor_bank_account_number", "width": "150px"}
				    		],
          "drawCallback": function ( settings ) {
          	$(".select2").select2();
            let api         = this.api();
            let row_datas   = api.rows({ page: 'current' }).data();
            row_datas.each(function (data, i) {
            	j = i+1;
				nama_bank = data.nama_bank;
				bank_name = $("#bank_name-"+j).val();
				$("#bank_name-"+j).val(nama_bank).trigger('change.select2');
            });
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


	$('#table_data_length').html('');
	$('#table_data_paginate').css('display', 'none');
	$('#table_data_filter').html('');

	let url_detail  = baseURL + 'purchase-order/api/load_data_detail_edit';
	let po_line_id = 0;

	let tracker = 0;

	Pace.track(function(){
	    $('#table_detail').DataTable({
	      "serverSide"      : true,
	      "processing"      : true,
	      "ajax"            : {
									"url"  : url_detail,
									"type" : "POST",
									"dataType": "json",
									"data"    : function ( d ) {
					                              d.po_line_id = po_line_id;
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
				    			{"data": "qty", "width": "60px", "class": "p-2 text-center" },
				    			{"data": "uom", "width": "80px", "class": "p-2 text-center" },
				    			{"data": "unit_price", "width": "150px", "class": "p-2 text-right" },
				    			{"data": "total_price", "width": "150px", "class": "p-2 text-right" }
				    		],
          "drawCallback": function ( settings ) {
          	tracker++;
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
	$('#table_detail_length').html('');
	$('#table_detail_paginate').css('display', 'none');
	$('#table_detail_filter').html('');

    $('#table_data tbody').on('focus', 'tr td input.input-daterange-datepicker', function () {
	    $(this).daterangepicker({
          locale: {
            format: 'DD/MM/YYYY'
          },
			"autoApply": true,
			// minDate:new Date,
		});
	});

	$('#table_data').on( 'draw.dt', function () {
		$('#table_data tbody tr').eq(0).addClass('selected');

		row_now       = $('#table_data tbody tr.selected');
		let get_table = table.row( row_now );
		let data      = get_table.data();

		po_line_id = data.po_line_id;
		table_detail.draw();
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
		}
    });

    $('#table_detail').on( 'draw.dt', function () {

    	if(tracker > 1){

			row_now       = $('#table_data tbody tr.selected');
			let get_table = table.row( row_now );
			let data      = get_table.data();
			po_line_key = data.po_line_key;

			if( po_line_key in po_line_data ) {
				data_detail = po_line_data[po_line_key];
				data_detail.forEach(function(value, i) {
					num = i+1;
					$("#po_desc-"+num).val(value.po_desc);
					$("#unit_price-"+num).val(formatNumber(value.nominal));
				});
			}

    	}
	});

	$('#table_data tbody').on('input', 'tr td input.unit_price', function () {
		id_row            = $(this).data('id');
		nominal_detail_po = $(this).val();
		nominal           = $("#nominal-"+id_row).val();

		if(parseInt(nominal_detail_po.replace(/\./g, '')) > parseInt(nominal.replace(/\./g, ''))){
			$(this).val(nominal);
		}
    });

	$('#table_detail tbody').on('input', 'tr td input.unit_price', function () {
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
		id_detail   = data.po_detail_id;
		id_row      = $(this).data('id');

		// autoSaveDetail(pr_lines_id, id_detail, id_row);
		storeDataDetail();
    });


	$('#table_detail tbody').on('change', 'tr td input.unit_price', function () {
		if($(this).val().trim().length === 0){
			$(this).val(0);
		}
		getNominal();
    });

	$('#table_data tbody').on('change', 'tr td input.po_number', function () {
		getTotalAmount();
    });

	$('#table_data tbody').on('change', 'tr td input.po_line_name', function () {
		getTotalAmount();
    });


    function getNominal(){
		let total_detail = table_detail.data().count();
		let totalNominal = 0;

		for (var i = 1; i <= total_detail; i++) {
			nominal_detail_val = $("#total_price-"+i).val();
			totalNominal += parseInt(nominal_detail_val.replace(/\./g, ''));
	    }

	    $("#table_data tbody tr.selected").find("input.po_amount").val(formatNumber(totalNominal));
		getTotalAmount();
    }

    function autoSaveDetail(pr_lines_id, id_detail, id_row){

		po_desc        = $("#po_desc-"+id_row).val();
		nominal_detail = $("#unit_price-"+id_row).val();
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
	          }
	        });
		}
    }

	$("#save_data").on('click', function () {
		
		let total_data   = table.data().count();
		let data_po      = [];
		let data_empty   = [];
		let total_amount = 0;
		let po_date      = $("#po_date").val();
		let po_category  = $("#po_category").val();
		let mpa  		 = $("#mpa").val();
		let est_date  	 = $("#est_date").val();
		let top  	 	 = $("#top").val();
		let notes  	 	 = $("#notes").val();
		let fatalNominal = false;
		let notice       = '';
		let ttl_price    = '';
		let total        = '';

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

	    if(fatalNominal == true){
	    	customNotif('warning', notice ,'warning');
	    	return false;
	    }else{
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
			
			po_line_id  = data.po_line_id;
			po_line_key  = data.po_line_key;

			if(po_number != "" && parseInt(po_amount.replace(/\./g, '')) > 0 && vendor_name != 0){
				total_amount += parseInt(po_amount.replace(/\./g, ''));
				detail_po    = po_line_data[po_line_key];
	    		data_po.push({ 'po_line_id' : po_line_id, 'po_number' : po_number, 'po_line_name' : po_line_name, 'po_amount' : parseInt(po_amount.replace(/\./g, '')), 'po_period' : po_period, 'vendor_name' : vendor_name, 'bank_name' : bank_name, 'account_name' : account_name, 'account_number' : account_number, 'detail_po' : detail_po });
			}
		});


		if (data_empty.length > 0)
		{
			emptyList = data_empty.join(', ');
			customNotif('Warning', "Masih ada data kosong pada line nomor: " +emptyList, 'warning');
		}
		else if (attachment_file == "")
		{
			customNotif('Warning', 'Please upload document', 'warning');
			return false;
		}
		else{
			$(this).attr('disabled', true);

				data = {
								pr_header_id: pr_header_id,
									po_category : po_category,
									po_header_id: po_header_id,
									po_amount: total_amount,
									mpa : mpa,
									est_date : est_date,
									top : top,
									attachment : attachment_file,
									notes : notes,
									data_po : data_po
								}

		    $.ajax({
		        url   : baseURL + 'purchase-order/api/save_po_edit',
		        type  : "POST",
		        data  : data,
		        dataType: "json",
		        beforeSend  : function(){
                          customLoading('show');
                        },
		        success : function(result){
		        	if(result.status == true){
		        		customNotif('Success', "PO UPDATED", 'success');
		        		setTimeout(function(){
		        			customLoading('hide');
		        			$(location).attr('href', baseURL + 'purchase-order/' + redirect_po);
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

	$('#table_data').on( 'draw.dt', function () {
    	setTimeout(function(){
			let get_data = table.row(0).data();
			po_number_edit = get_data.po_number_edit;
			$("#table_detail_title").html('Detail of '+ get_data.po_number_edit);
			table_detail.draw();
    	}, 500);
	});

	$('#table_data tbody').on( 'click', 'tr', function () {
		if (! $(this).hasClass('selected') ) {
			table.$('tr.selected').removeClass('selected');
			$(this).addClass('selected');
			let get_table = table.row( this );
			let data      = get_table.data();
			let index     = get_table.index();
			num = index+1;

			$('#table_detail_title').html('Detail of line '+ num);

			po_line_id = data.po_line_id;
	    	table_detail.draw();

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
			po_detail_id          = data.po_detail_id;
			po_desc_val           = $("#po_desc-"+i).val();
			total_price 		  = $("#total_price-"+i).val();
			item_desc_val 		  = $("#item_desc-"+i).val();
			qty_val 			  = $("#qty-"+i).val();
			unit_price_val 		  = $("#unit_price-"+i).val();

			detail_data.push({'po_detail_id' : po_detail_id, 'item_desc' : item_desc_val, 'po_desc' : po_desc_val, 'qty' : qty_val, 'unit_price' :  parseInt(unit_price_val.replace(/\./g, '')), 'total_price' : parseInt(total_price.replace(/\./g, ''))});
	    }

	    po_line_data[po_line_key] = detail_data;

	}

	function getTotalAmount(){
		let total = table.data().count();
		let totalAmount = 0;
		for (var i = 1; i <= total; i++) {

			if($("#po_number-"+i).val() != '' && $("#po_line_name-"+i).val() != ''){
				po_amount   = $("#po_amount-"+i).val().replace(/\./g, '');
				totalAmount += parseInt(po_amount);
			}
	    }

	    $("#po_amount_total").text(formatNumber(totalAmount));
    }

    $('#table_detail tbody').on('input change blur', 'tr td input.qty, tr td input.unit_price', function () {
		if($(this).val().trim().length === 0){
			$(this).val(0);
		}

		index       = $(this).data('id');
		qty_int     = parseInt( $("#qty-"+index).val() );
		price_int   = parseInt( $("#unit_price-"+index).val().replace(/\./g, '') );
		nominal_val = (qty_int > 0 && price_int > 0) ? qty_int * price_int : 0;

		$("#total_price-"+index).val(formatNumber(nominal_val));

		setTimeout(function(){
			getNominal();
		}, 300);

    });


  $('#change_file').on( 'click', function () {
    $("#attachment_view").addClass("d-none");
    $("#upload_attachment").removeClass("d-none");
    attachment_file = '';
  });
  
  $('#cancel_change_file').on( 'click', function () {
    $("#upload_attachment").addClass("d-none");
    $("#attachment_view").removeClass("d-none");
    attachment_file = last_attachment;
  });

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
            
        file     = $('#attachment')[0].files[0];
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
          data  : {file: attachment_file, category:attach_category},
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