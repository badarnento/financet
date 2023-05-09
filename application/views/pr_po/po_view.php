<div class="row">
  <button id="edit_po" class="btn btn-warning border-radius-5 w-100p m-b-10" type="button"><i class="fa fa-edit"></i> Edit PO</button>
</div>

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
            <label class="col-sm-5 col-md-4 control-label text-left">PO Date <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label"><?= $po_date ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
              <label class="col-sm-5 col-md-4 control-label text-left">Currency<?= ($po_currency != "IDR") ? "/Rate" : "" ?> <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label id="pr_currency" class="control-label"><?= strtoupper($po_currency) ?><?= (strtoupper($po_currency) != "IDR") ? " / ".$po_rate : "" ?></label>
              </div>
          </div>
        	<div class="form-group m-b-10">
          	<label class="col-sm-5 col-md-4 control-label text-left">Total Amount PO<span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="pr_amount" class="control-label"><?= $po_amount ?></label>
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
	        <?php  if($po_reference && $po_reference != "NULL"): ?>
        	<div class="form-group m-b-10">
	            <label class="col-sm-5 col-md-4 control-label text-left">PO Reference <span class="pull-right">:</span></label>
	            <div class="col-sm-7 col-md-8">
	              <label class="control-label text-left"><?= $po_reference ?></label>
	            </div>
	        </div>
	        <?php  endif; ?>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">PO Category <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label"><?= $po_category ?></label>
            </div>
          </div>

    	<div class="form-group m-b-10">
    		<label class="col-sm-5 col-md-4 control-label text-left">Document <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
            	<label class="control-label text-left"><?= ($po_document != "-") ? '<a id="fpjp_attachment" class="btn btn-xs btn-success" href="'.$po_document_link.'" target="_blank"> <i class="fa fa-download"></i> Download </a>' : $po_document ?></label>
            </div>
        </div>
          <?php if($po_document_clauses): ?>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Document of Clauses<span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label class="control-label"><a class="btn btn-xs btn-success" href="<?= $po_document_clauses_link ?>" target="_blank"> <i class="fa fa-download"></i> Download </a></label>
              </div>
          </div>
            <?php endif; ?>
          <div class="form-group m-b-10">
	    		<label class="col-sm-5 col-md-4 control-label text-left">Status <span class="pull-right">:</span></label>
	            <div class="col-sm-7 col-md-8">
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
		<div id="tbl_search" class="col-md-12 positon-relative">
			<i class="fa fa-search positon-absolute p-5 mt-2 ml-5 pos"></i>
			<input class="input-field px-30 form-control bg-lightgrey input-sm  border-radius-5 mb-10" type="search" placeholder="Search here...">
		</div>
		<div class="col-md-12">
	      <table id="table_data" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small hover w-full">
            <thead>
              <tr>
				<th class="text-center">No</th>
	          	<th class="text-center">PR Name</th>
	          	<th class="text-center">PR Amount</th>
	          	<th class="text-center">PO Name</th>
	          	<th class="text-center">PO Number</th>
	          	<th class="text-center">PO Amount</th>
	          	<th class="text-center">PO Period</th>
	          	<th class="text-center">Vendor Name</th>
	          	<th class="text-center">Bank Name</th>
	          	<th class="text-center">Bank Account Name</th>
	          	<th class="text-center">Bank Account</th>
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
      <h5 id="table_detail_title" class="font-weight-700 m-0 text-uppercase">Detail of</h5>
    </div>
  </div>
  <div class="white-box boxshadow border-bottom-only-5">
	<div class="row">
		<div class="col-md-12">
	      <!-- <table id="table_detail" class="table dataTable table-responsive table-striped table-bordered cell-border  small hover w-full"> -->
		  <table id="table_detail" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small hover w-full">
	        <thead>
	        	<tr>
					<th class="text-center">Item Name</th>
					<th class="text-center">Item Description</th>
					<th class="text-center">Category Item</th>
					<th class="text-center">Quantity</th>
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
      <form class="form-horizontal">
        <div class="row">
        	<div class="col-sm-8">
	        	<div class="form-group m-b-10">
	        		<label class="col-sm-5 col-md-3 control-label text-left">Buyer <span class="pull-right">:</span></label>
		            <div class="col-sm-8">
		            	<label class="control-label text-left"><?= $po_buyer ?></label>
		            </div>
		        </div>
	        	<div class="form-group m-b-10">
	        		<label class="col-sm-5 col-md-3 control-label text-left">Approval <span class="pull-right">:</span></label>
		            <div class="col-sm-7 col-md-7">
		            <?php foreach ($po_approval as $key => $value):?>
		            	<label class="control-label text-left"><?= $key+1?>. <?= $value['NAME'] ?> / <?= $value['JABATAN'] ?> &nbsp; &nbsp;<?php if($value['STATUS'] == "approved"){ echo "<i class='text-success fa fa-check-circle fa-lg' title='Approved'></i>";}elseif($value['STATUS'] == "returned"){  echo "<i class='text-warning fa fa-arrow-circle-left fa-lg' title='Returned'></i>"; } elseif($value['STATUS'] == "rejected"){  echo "<i class='text-danger fa fa-times-circle fa-lg' title='Rejected'></i>"; } elseif($value['STATUS'] == "request_approve"){  echo "<i class='fa fa-clock-o fa-lg' title='Waiting approval'></i>"; }  ?></label>
		            	<br>
		            <?php endforeach;?>
		            </div>
		            <div class="col-md-2">
		            	
		            </div>
		        </div>
		        <div class="form-group m-b-10">
		            <label class="col-sm-5 col-md-3 control-label text-left">MPA / Contract Reference <span class="pull-right">:</span></label>
		            <div class="col-sm-8">
		              <label class="control-label"><?= $mpa_reference ?></label>
		            </div>
		        </div>
		        <div class="form-group m-b-10">
		            <label class="col-sm-5 col-md-3 control-label text-left">Estimate Date <span class="pull-right">:</span></label>
		            <div class="col-sm-8">
		              <label class="control-label"><?= $est_date ?></label>
		            </div>
		        </div>
		        <div class="form-group m-b-10">
		            <label class="col-sm-5 col-md-3 control-label text-left">TOP <span class="pull-right">:</span></label>
		            <div class="col-sm-8">
		              <label class="control-label text-left"><?= $top ?></label>
		            </div>
		        </div>
		        <div class="form-group m-b-10">
	        		<label class="col-sm-5 col-md-3 control-label text-left">Notes <span class="pull-right">:</span></label>
		            <div class="col-sm-8">
		            	<label class="control-label text-left"><?= $notes ?></label>
		            </div>
		        </div>
        	</div>
        </div>
      </form>
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

	const ID_PO       = '<?= $id_po ?>';
	let status        = $('#status').val();
	
	let url = baseURL + 'purchase-order/api/load_data_lines';

	Pace.track(function(){
	    $('#table_data').DataTable({
	      "serverSide"      : true,
	      "processing"      : true,
	      "ajax"            : {
									"url"  : url,
									"type" : "POST",
									"dataType": "json",
									"data"    : function ( d ) {
													d.po_header_id = ID_PO;
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
				    			{"data": "line_name", "width": "150px" },
				    			{"data": "nominal", "width": "100px", "class": "text-right"  },
				    			{"data": "po_name", "width": "150px" },
				    			{"data": "po_number", "width": "120px", "class": "text-center" },
				    			{"data": "nominal_amount", "width": "100px", "class": "text-right"  },
				    			{"data": "po_period", "width": "150px" },
				    			{"data": "vendor_name", "width": "150px" },
				    			{"data": "bank_name", "width": "150px" },
				    			{"data": "bank_account_name", "width": "200px" },
				    			{"data": "bank_account", "width": "150px" }
				    		],
        "drawCallback": function ( settings ) {
          // $('#table_data_paginate').html('');
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

	$('#table_data_filter').remove();
	$('#table_data_length').remove();
	$('#table_data_paginate').remove();

	$("#tbl_search").on('keyup', "input[type='search']", function(){
	  table.search( $(this).val() ).draw();
	});

	let url_detail  = baseURL + 'purchase-order/api/load_data_details';
	let po_line_id = 0;

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
				    			{"data": "item_desc", "width": "200px" },
				    			{"data": "detail_desc", "width": "150px" },
				    			{"data": "category_item", "width": "150px" },
				    			{"data": "qty", "width": "50px", "class": "p-2 text-center"  },
				    			{"data": "uom", "width": "100px", "class": "p-2 text-center"},
				    			{"data": "item_price", "width": "100px", "class": "p-2 text-right" },
				    			{"data": "total_price", "width": "100px", "class": "p-2 text-right" }
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

	let table_detail = $('#table_detail').DataTable();
	$('#table_detail_length').remove();
	$('#table_detail_filter').remove();
	$('#table_detail_paginate').remove();

	$('#table_data').on( 'draw.dt', function () {
    	setTimeout(function(){
			let get_data = table.row(0).data();
			po_line_id = get_data.po_line_id;
			$("#table_detail_title").html('Detail of '+ get_data.po_number);
			table_detail.draw();
    	}, 500);
	});

	$('#table_data tbody').on( 'click', 'tr', function () {
		if (! $(this).hasClass('selected') ) {
			table.$('tr.selected').removeClass('selected');
			$(this).addClass('selected');
			let data = table.row( this ).data();
			po_line_id = data.po_line_id;
			$("#table_detail_title").html('Detail of '+ data.po_number);
			table_detail.draw();
		}
	});

	const po_enc = "<?= $id_po_enc ?>";

	$("#edit_po").on('click', function () {
		$(location).attr('href', baseURL + 'purchase-order/edit/'+po_enc);
	});

  });
</script>