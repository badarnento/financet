<?php $this->load->view('ilustration') ?>

<div class="row">
  <div class="white-box boxshadow m-b-0 mt-10 border-top-only-5 z-index-2 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">PO Approval</h5>
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
          	<label class="col-sm-5 col-md-4 control-label text-left">Total Amount <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="fs_amount" class="control-label"><?= $po_amount ?></label>
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
            <label class="col-sm-5 col-md-4 control-label text-left">PO Category <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label"><?= $po_category ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Document <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
              <?php if($po_document): ?>
                <label class="control-label"><a id="fs_attachment" class="btn btn-xs btn-success" href="<?= $po_document_link ?>" target="_blank"> <i class="fa fa-download"></i> Download </a></label>
                <?php else: ?>
                <label class="control-label text-left">&ndash;</label>
                <?php endif; ?>
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
                <label class="control-label text-left"><?= $po_status ?></label>
              </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Last Update <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label class="control-label text-left"><?= $po_status_desc ?> at <?= (isset($approver_before_name)) ? $approver_before_date : $po_last_update ?></label>
                <?php if(isset($po_approval_remark)): ?>
                <br>
                <label class="control-label text-left">&quot;<?= $po_approval_remark ?>&quot;</label>
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
  			<table id="table_data" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small w-full hover" style="font-size:11px !important;">
            <thead>
              <tr>
          			<th class="text-center">No</th>
                <!-- <th class="text-center">PR Name</th> -->
                <!-- <th class="text-center">PR Amount</th> -->
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
      <h5 id="table_detail_title" class="font-weight-700 m-0 text-uppercase">PO Detail</h5>
    </div>
  </div>
  <div class="white-box boxshadow border-bottom-only-5">
  <div class="row">
    <div class="col-md-12">
        <table id="table_detail" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small w-full">
          <thead>
            <tr>
              <th class="text-center">Item Name</th>
              <th class="text-center">Item Description</th>
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


<div class="row">
  <div class="white-box boxshadow">
  	<div class="row">
      <div id="approvaL_display" class="col-sm-8">
        <?php if(isset($approver_before_name)): ?>
        <div class="m-b-15">
          <h5>Approved by <?= $approver_before_name ?> at <?= $approver_before_date ?></h5>
          <h5>Remark : <?= (empty($approver_before_remark)) ? "-" : $approver_before_remark ?></h5>
        </div>
        <?php endif;?>
      <?php if($trx_status == "request_approve"){ ?>
          <button class="btn btn-success waves-effect btn-approval m-r-5" data-approve="approve" type="button" ><i class="fa fa-check-circle"></i> Approve</button>
          <button class="btn btn-warning waves-effect btn-approval m-r-5" data-approve="return" type="button" ><i class="fa fa-arrow-circle-left"></i> Return</button>
          <button class="btn btn-danger waves-effect btn-approval" data-approve="reject" type="button" ><i class="fa fa-times-circle"></i> Reject</button>
      <?php } else if($trx_status == "approved"){ ?>
          <h3 class="text-success">Thank You For Your <b>Confirmation!</b></h3>
          <h5>Approved at <?= $trx_date ?></h5>
      <?php } else if($trx_status == "rejected"){ ?>
          <h3 class="text-danger">This PO has been <b>Rejected!</b></h2>
          <h5>Rejected at <?= $trx_date ?></h5>
      <?php } else if($trx_status == "returned"){ ?>
          <h3 class="text-warning">This PO has been <b>Returned!</b></h3>
          <h5>Returned at <?= $trx_date ?></h5>
      <?php } ?>
      </div>
    </div>
  </div>
</div>

<div id="modal-approve" class="modal fade" tabindex="-3" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h2 class="modal-title text-white" id="modal-approve-label">Approve</h2>
        </div>
        <div class="modal-body">
            <div class="row" id="approver_group">
              <div class="col-sm-12">
                <div class="form-group m-b-10">
                    <label for="approval_remark" class="control-label">Choose Destination <span class="pull-right">:</span></label>
                      <select class="form-control" id="approver">
                        <option value="">-- Choose --</option>
                        <?php foreach ($approval_list as $key => $value):?>
                         <option value="<?= $value['ID'] ?>"><?= $key+1 . ". " .$value['CATEGORY'] ?> - <?= $value['NAME'] ?></option>
                        <?php endforeach;?>
                      </select>
                </div>
              </div>
            </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group m-b-10">
                  <label for="approval_remark" class="control-label">Remark<span class="pull-right">:</span></label>
                    <textarea class="form-control" id="approval_remark" rows="3" placeholder="Optional"></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cancel</button>
          <button type="button" class="btn btn-success waves-effect" id="btn_approval"><i class="fa fa-check-circle"></i> Approve</button>
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

  const ID_PO       = '<?= $id_po ?>';
  const LEVEL       = '<?= $level ?>';
  let status        = $('#status').val();
  let pr_category   = 0;
	
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
                  // {"data": "line_name", "width": "150px" },
                  // {"data": "nominal", "width": "100px", "class": "text-right"  },
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
													d.category    = 'view';
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
	$('#table_detail_length').remove()
  $('#table_detail_paginate').remove()
  $('#table_detail_filter').remove()
	$('input[type="search"]').attr('placeholder','Search here...').addClass('form-control input-sm m-0');

	$('#table_data').on( 'draw.dt', function () {
    	setTimeout(function(){
			let get_data = table.row(0).data();
			po_line_id = get_data.po_line_id;
			table_detail.draw();
    	}, 500);
	});

  $(".select2").select2();


	$('#table_data tbody').on( 'click', 'tr', function () {
		if (! $(this).hasClass('selected') ) {
			table.$('tr.selected').removeClass('selected');
			$(this).addClass('selected');
			let data = table.row( this ).data();
			po_line_id = data.po_line_id;

			$("#table_detail_title").html('Detail of '+ data.line_name);
			table_detail.draw();
		}
	});


  $('.btn-approval').on( 'click', function () {
    approve = $(this).data('approve');

    if(approve == "reject"){
      $("#pr_category_group").addClass('d-none');
      $("#btn_approval").removeClass('btn-warning btn-success');
      $("#btn_approval").addClass('btn-danger');
      $("#modal-approve-label").html('Reject');
      $("#approver_group").addClass('d-none');
      btn_approval = '<i class="fa fa-times-circle"></i> Reject';
      approvaL_display = '<h3 class="text-danger">This PO has been <b>Rejected!</b></h2>';
    }
    else if(approve == "return"){
      $("#pr_category_group").addClass('d-none');
      $("#btn_approval").removeClass('btn-danger btn-success');
      $("#btn_approval").addClass('btn-warning');
      $("#modal-approve-label").html('Return');
      $("#approver_group").removeClass('d-none');
      btn_approval = '<i class="fa fa-arrow-circle-left"></i> Return';
      approvaL_display = '<h3 class="text-warning">This PO has been <b>Returned!</b></h3>';
    }
    else{
      $("#pr_category_group").removeClass('d-none');
      $("#btn_approval").removeClass('btn-danger btn-warning');
      $("#btn_approval").addClass('btn-success');
      $("#approver_group").addClass('d-none');
      $("#modal-approve-label").html('Approve');
      btn_approval = '<i class="fa fa-check-circle"></i> Approve';
      approvaL_display = '<h3 class="text-success">Thank You For Your <b>Confirmation!</b></h3>';
    }

    $("#btn_approval").html(btn_approval);
    setTimeout(function(){
      $('#modal-approve').modal('show');
    }, 200);

  });


  $('#modal-approve').on('show.bs.modal', function () {
    $("#approval_remark").val('');
  });


  $('#pr_category').on('change', function () {
    if($(this).val() !=''){
      pr_category = $(this).val();
    }else{
      pr_category = 0;
    }
  });

  $('#btn_approval').on( 'click', function () {
    remark = $("#approval_remark").val();
    approver = $("#approver").val();
    console.log(pr_category);
      $.ajax({
        url       : baseURL + 'po/approval/api/action_approval',
        type      : 'post',
        data      : { id_po : ID_PO, level : LEVEL, approval : approve, approver : approver, remark : remark},
        beforeSend  : function(){
                        customLoading('show');
                      },
        dataType : 'json',
        success : function(result){
          customLoading('hide');
          if (result.status == true) {
            $("#approvaL_display").html(approvaL_display);
            $("#modal-approve").modal('hide');
            customNotif('Success', result.messages, 'success');
          } else {
            customNotif('Failed', result.messages, 'error');
          }
        }
      });
  });

  });
</script>