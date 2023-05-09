<!-- <?php if(strtolower($pr_status) == "returned"): ?> -->
    <div class="row">
        <button id="edit_pr" class="btn btn-warning border-radius-5 w-100p m-b-10" type="button"><i class="fa fa-edit"></i> Edit GR</button>
    </div>
<!-- <?php endif; ?> -->

<?php $this->load->view('ilustration') ?>


<div class="row">
  <div class="white-box boxshadow m-b-0 mt-10 border-top-only-5 z-index-2 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">GR Detail</h5>
    </div>
  </div>
  <div class="white-box boxshadow z-index-2 py-10 border-bottom-only-5 small">
    <div class="row">
      <form class="form-horizontal">
      <div class="col-sm-6 col-md-5">
        <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">No BAST <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="no_bast" class="control-label"><?= $no_bast ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">BAST Date <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="bast_date" class="control-label"><?= $bast_date ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Directorat <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="directorat" class="control-label"><?= get_directorat($directorat) ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Division <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="division" class="control-label text-left"><?= get_division($division) ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Unit <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="unit" class="control-label"><?= get_unit($unit) ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">PO Number <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label"><?= $po_number ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Vendor Name <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label text-left"><?= $vendor_name ?></label>
            </div>
          </div>
        </div>

        <div class="col-sm-6 col-md-5">
          <!-- <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">GR Date <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label"><?= $gr_date ?></label>
            </div>
          </div> -->
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Category <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label"><?= $category ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Contract Identification <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label class="control-label text-left"><?= $contract ?></label>
              </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Budget Type <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label class="control-label text-left"><?= $budget_type ?></label>
              </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Project Ownership <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label class="control-label text-left"><?= $project ?></label>
              </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Document Uploaded<span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label class="control-label text-left"><?= ($gr_doc_upload != "-") ? '<a id="gr_attachment" class="btn btn-xs btn-success" href="'.$gr_doc_upload.'" target="_blank"> <i class="fa fa-download"></i> Download </a>' : $gr_doc_upload ?></label>
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
      <h5 class="font-weight-700 m-0 text-uppercase">GR Lines</h5>
    </div>
  </div>
  <div class="white-box boxshadow border-bottom-only-5">
  <div class="row">
    <div id="tbl_search" class="col-md-12 positon-relative">
      <i class="fa fa-search positon-absolute p-5 mt-2 ml-5 pos"></i>
      <input class="input-field px-30 form-control bg-lightgrey input-sm  border-radius-5 mb-10" type="search" placeholder="Search here...">
    </div>
    <div class="col-md-12" style="overflow: auto;">
        <table id="table_data" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small hover w-full">
            <thead>
              <tr>
        <th class="text-center">No</th>
        <th class="text-center">Item Name</th>
        <th class="text-center">Item Description</th>
        <th class="text-center">Quantity</th>
        <th class="text-center">UoM</th>
        <th class="text-center">Unit Price</th>
        <th class="text-center">Total Price</th>
        <!-- di hide dulu kata badar 18 mei 2021 -->
        <!-- <th class="text-center">Asset Type</th> -->
        <!-- <th class="text-center">Serial Number</th>
        <th class="text-center">Merek</th>
        <th class="text-center">Major Category</th>
        <th class="text-center">Minor Category</th> -->
        <th class="text-center">Region</th>
        <th class="text-center">Location</th>
        <th class="text-center">Project Ownership (Unit)</th>
        <!-- <th class="text-center">Umur Manfaat</th> -->
        <th class="text-center">CIP</th>
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
      <form class="form-horizontal">
        <div class="row">
          <div class="col-sm-8">
            <div class="form-group m-b-10">
              <label class="col-sm-5 col-md-3 control-label text-left">Submitter <span class="pull-right">:</span></label>
                <div class="col-sm-8">
                  <label class="control-label text-left"><?= $gr_submitter ?> / <?= (strtolower($gr_jabatan_sub) == 'procurement') ? 'Procurement Support' : $gr_jabatan_sub  ?></label>
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
          <h3 class="text-danger">This GR has been <b>Rejected!</b></h2>
          <h5>Rejected at <?= $trx_date ?></h5>
      <?php } else if($trx_status == "returned"){ ?>
          <h3 class="text-warning">This GR has been <b>Returned!</b></h3>
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
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group m-b-10">
                  <label for="approval_remark" class="control-label">Description <span class="pull-right">:</span></label>
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


<script>
  $(document).ready(function(){

  const ID_GR       = '<?= $id_gr ?>';
  const LEVEL       = '<?= $level ?>';
  
  let url = baseURL + 'general-receipt/api/load_data_gr_lines';

  Pace.track(function(){
      $('#table_data').DataTable({
        "serverSide"      : true,
        "processing"      : true,
        "ajax"            : {
                  "url"  : url,
                  "type" : "POST",
                  "dataType": "json",
                  "data"    : function ( d ) {
                          d.pr_header_id = ID_GR;
                                      }
                          },
        "language"        : {
                              "emptyTable"  : "<span class='label label-danger'>Data Not Found!</span>",
                              "infoEmpty"   : "Empty Data",
                              "processing"  : '<img src="' + imageLoadingSmall + '" alt="" />',
                              "search"      : "_INPUT_"
                            },
        "columns"         : [
                {"data": "no", "width": "5px", "class": "text-center" },
                  {"data": "item_name", "width": "200px" },
                  {"data": "item_description", "width": "150px" },
                  {"data": "quantity", "width": "100px", "class": "text-center"  },
                  {"data": "uom", "width": "100px", "class": "text-center"  },
                  {"data": "unit_price", "width": "100px", "class": "text-right"  },
                  {"data": "total_price", "width": "100px", "class": "text-right"  },
                  // di hide dulu kata badar 18 mei 2021
                  // {"data": "asset_type", "width": "100px", "class": "text-left"  },
                  // {"data": "serial_number", "width": "100px", "class": "text-left"  },
                  // {"data": "merek", "width": "100px", "class": "text-left"  },
           //        {"data": "major_category", "width": "180px", "class": "p-5 text-left"},
           //        {"data": "minor_category", "width": "180px", "class": "p-5 text-left"},
                  {"data": "region", "width": "180px", "class": "p-5 text-left"},
                  {"data": "lokasi", "width": "180px", "class": "p-5 text-left"},
                  {"data": "project_owner_unit", "width": "180px", "class": "p-5 text-left"},
                  // {"data": "umur_manfaat", "width": "100px", "class": "text-left"  },
                  {"data": "cip", "width": "60px", "class": "p-5 text-center"},
                  {"data": "no_invoice", "width": "120px", "class": "p-5 text-center"},
                  {"data": "invoice_date", "width": "100px", "class": "text-center"  },
                  {"data": "receipt_date", "width": "100px", "class": "text-center"  }
                ],
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


  $('.btn-approval').on( 'click', function () {
    approve = $(this).data('approve');

    if(approve == "reject"){
      $("#btn_approval").removeClass('btn-warning btn-success');
      $("#btn_approval").addClass('btn-danger');
      $("#modal-approve-label").html('Reject');
      btn_approval = '<i class="fa fa-times-circle"></i> Reject';
      approvaL_display = '<h3 class="text-danger">This GR has been <b>Rejected!</b></h2>';
    }
    else if(approve == "return"){
      $("#btn_approval").removeClass('btn-danger btn-success');
      $("#btn_approval").addClass('btn-warning');
      $("#modal-approve-label").html('Return');
      btn_approval = '<i class="fa fa-arrow-circle-left"></i> Return';
      approvaL_display = '<h3 class="text-warning">This GR has been <b>Returned!</b></h3>';
    }
    else{
      $("#btn_approval").removeClass('btn-danger btn-warning');
      $("#btn_approval").addClass('btn-success');
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

  $('#btn_approval').on( 'click', function () {
    remark = $("#approval_remark").val();
      $.ajax({
        url       : baseURL + 'gr/approval/api/action_approval',
        type      : 'post',
        data      : { id_gr : ID_GR, level : LEVEL, approval : approve, remark : remark},
        beforeSend  : function(){
                        customLoading('show');
                      },
        dataType : 'json',
        success : function(result){
          console.log(result)
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