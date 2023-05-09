<?php $this->load->view('ilustration') ?>


<div class="row">
  <div class="white-box boxshadow m-b-0 mt-10 border-top-only-5 z-index-2 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">FPJP Detail</h5>
    </div>
  </div>
  <div class="white-box boxshadow z-index-2 py-10 border-bottom-only-5 small">
    <div class="row">
      <form class="form-horizontal">
      <div class="col-sm-6 col-md-5">
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">FPJP Number <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="fs_number" class="control-label"><?= $fpjp_number ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">FPJP Name <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="fs_name" class="control-label text-left"><?= $fpjp_name ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
              <label class="col-sm-5 col-md-4 control-label text-left">Currency<?= ($fpjp_currency != "IDR") ? "/Rate" : "" ?> <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label id="fs_currency" class="control-label"><?= $fpjp_currency ?><?= ($fpjp_currency != "IDR") ? " / ".$fpjp_rate : "" ?></label>
              </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Total Amount <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="fs_amount" class="control-label"><?= $fpjp_amount ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Directorate <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label"><?= get_directorat($fpjp_directorat) ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Division <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label text-left"><?= get_division($fpjp_division) ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Unit <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label text-left"><?= get_unit($fpjp_unit) ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Justif Number <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label text-left"><?= get_fs($id_fs) ?> - <?= get_fs($id_fs, "FS_NAME") ?></label>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-md-5">
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">FPJP Date <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label"><?= $fpjp_date ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Status <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label class="control-label text-left"><?= $fpjp_status ?></label>
              </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Submitter/Jabatan <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label class="control-label text-left"><?= $fpjp_submitter ?>/<?= $fpjp_jabatan_sub ?></label>
              </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Last Update <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label class="control-label text-left"><?= $fpjp_status_desc ?> at <?= (isset($approver_before_name)) ? $approver_before_date : $fpjp_last_update ?></label>
                <?php if(isset($fpjp_approval_remark)): ?>
                <br>
                <label class="control-label text-left">&quot;<?= $fpjp_approval_remark ?>&quot;</label>
                <?php endif; ?>
              </div>
          </div>
          <?php if($fs_attachment): ?>
        <div class="form-group m-b-10">
          <label class="col-sm-5 col-md-4 control-label text-left">Justif Document <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label class="control-label"><a id="fs_attachment" class="btn btn-xs btn-success" href="<?= $fs_attachment['FILE_LINK'] ?>" target="_blank"> <i class="fa fa-download"></i> Download </a></label>
              </div>
          </div>
      <?php endif; ?>
        </div>
      </form>
    </div>
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
    <div id="tbl_search" class="col-md-12 positon-relative">
      <i class="fa fa-search positon-absolute p-5 mt-2 ml-5 pos"></i>
      <input class="input-field px-30 form-control bg-lightgrey input-sm  border-radius-5 mb-10" type="search" placeholder="Search here...">
    </div>
    <div class="col-md-12">
        <table id="table_data" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small hover w-full">
            <thead>
              <tr>
            <th class="text-center">No</th>
            <th class="text-center">Name</th>
            <th class="text-center">Fund Available</th>
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
  <div class="white-box boxshadow m-b-0 border-top-only-5 panel-title-custom">
    <div class="col-md-6">
      <h5 id="table_detail_title" class="font-weight-700 m-0 text-uppercase">FPJP Lines Detail</h5>
    </div>
  </div>
  <div class="white-box boxshadow border-bottom-only-5">
  <div class="row">
    <div class="col-md-12">
        <table id="table_detail" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small hover w-full">
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
      <form class="form-horizontal">
        <div class="row">
          <div class="col-sm-8">
            <div class="form-group m-b-10">
              <label class="col-sm-5 col-md-3 control-label text-left">Submitter <span class="pull-right">:</span></label>
                <div class="col-sm-8">
                  <label class="control-label text-left"><?= $fpjp_submitter ?> / <?= $fpjp_jabatan_sub ?></label>
                </div>
            </div>
            <div class="form-group m-b-10">
              <label class="col-sm-5 col-md-3 control-label text-left">Approval <span class="pull-right">:</span></label>
                <div class="col-sm-7 col-md-7">
                <?php foreach ($fpjp_approval as $key => $value):?>
                  <label class="control-label text-left"><?= $key+1?>. <?= $value['NAME'] ?> / <?= $value['JABATAN'] ?> &nbsp; &nbsp;<?php if($value['STATUS'] == "approved"){ echo "<i class='text-success fa fa-check-circle fa-lg' title='Approved'></i>";}elseif($value['STATUS'] == "returned"){  echo "<i class='text-warning fa fa-arrow-circle-left fa-lg' title='Returned'></i>"; } elseif($value['STATUS'] == "rejected"){  echo "<i class='text-danger fa fa-times-circle fa-lg' title='Rejected'></i>"; } elseif($value['STATUS'] == "request_approve"){  echo "<i class='fa fa-clock-o fa-lg' title='Waiting approval'></i>"; }  ?></label>
                  <br>
                <?php endforeach;?>
                </div>
                <div class="col-md-2">
                  
                </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-8">
            <div class="form-group m-b-10">
              <label class="col-sm-5 col-md-3 control-label text-left">No Invoice <span class="pull-right">:</span></label>
                <div class="col-sm-8">
                  <label class="control-label text-left"><?= $fpjp_no_invoice ?></label>
                </div>
            </div>
          </div>
          <div class="col-sm-8">
            <div class="form-group m-b-10">
              <label class="col-sm-5 col-md-3 control-label text-left">Invoice Date <span class="pull-right">:</span></label>
                <div class="col-sm-8">
                  <label class="control-label text-left"><?= $fpjp_invoice_date ?></label>
                </div>
            </div>
          </div>
          <div class="col-sm-8">
            <div class="form-group m-b-10">
              <label class="col-sm-5 col-md-3 control-label text-left">Document Uploaded <span class="pull-right">:</span></label>
                <div class="col-sm-8">
                  <label class="control-label text-left"><?= ($fpjp_doc_upload != "-") ? '<a id="fpjp_attachment" class="btn btn-xs btn-success" href="'.$fpjp_doc_upload.'" target="_blank"> <i class="fa fa-download"></i> Download </a>' : $fpjp_doc_upload ?></label>
                </div>
            </div>
          </div>
          <div class="col-sm-8">
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
          <button class="btn btn-success waves-effect btn-approval m-r-5" data-approve="approve" type="button"<?= ($disabled_act == true) ? ' disabled' : '' ?>><i class="fa fa-check-circle"></i> Approve</button>
          <button class="btn btn-warning waves-effect btn-approval m-r-5" data-approve="return" type="button"<?= ($disabled_act == true) ? ' disabled' : '' ?>><i class="fa fa-arrow-circle-left"></i> Return</button>
          <button class="btn btn-danger waves-effect btn-approval" data-approve="reject" type="button"<?= ($disabled_act == true) ? ' disabled' : '' ?>><i class="fa fa-times-circle"></i> Reject</button>
      <?php } else if($trx_status == "approved"){ ?>
          <h3 class="text-success">Thank You For Your <b>Confirmation!</b></h3>
          <h5>Approved at <?= $trx_date ?></h5>
      <?php } else if($trx_status == "rejected"){ ?>
          <h3 class="text-danger">This FPJP has been <b>Rejected!</b></h2>
          <h5>Rejected at <?= $trx_date ?></h5>
      <?php } else if($trx_status == "returned"){ ?>
          <h3 class="text-warning">This FPJP has been <b>Returned!</b></h3>
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

  const ID_FPJP       = '<?= $id_fpjp ?>';
  const LEVEL       = '<?= $level ?>';
  const FPJP_CURRENCY = '<?= $fpjp_currency ?>';
  let status        = $('#status').val();
  
  let url = baseURL + 'fpjp/api/load_data_lines';

  Pace.track(function(){
      $('#table_data').DataTable({
        "serverSide"      : true,
        "processing"      : true,
        "ajax"            : {
                  "url"  : url,
                  "type" : "POST",
                  "dataType": "json",
                  "data"    : function ( d ) {
                          d.fpjp_header_id = ID_FPJP;
                          d.category     = 'view';
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
                    {"data": "fund_available", "width": "100px", "class": "text-right"  },
                    {"data": "nominal", "width": "100px", "class": "text-right"  },
                    {"data": "pemilik_rekening", "width": "150px" },
                    {"data": "nama_bank", "width": "150px" },
                    {"data": "no_rekening", "width": "100px" }
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

  let url_detail  = baseURL + 'fpjp/api/load_data_details';
  let fpjp_lines_id = 0;

  Pace.track(function(){
      $('#table_detail').DataTable({
        "serverSide"      : true,
        "processing"      : true,
        "ajax"            : {
                  "url"  : url_detail,
                  "type" : "POST",
                  "dataType": "json",
                  "data"    : function ( d ) {
                          d.fpjp_lines_id = fpjp_lines_id;
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
                  {"data": "no", "width": "10px", "class": "text-center" },
                  {"data": "detail_desc", "width": "200px" },
                  {"data": "nature", "width": "200px" },
                  {"data": "quantity", "width": "50px", "class": "text-center"  },
                  {"data": "tax_view", "width": "100px", "class": "text-center" },
                  {"data": "price", "width": "100px", "class": "text-right"  },
                  {"data": "nominal", "width": "200px", "class": "text-right" }
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
  $('#table_detail_info').remove()
  $('#table_detail_filter').remove()
  $('#table_detail_paginate').remove()
  $('input[type="search"]').attr('placeholder','Search here...').addClass('form-control input-sm m-0');

  $('#table_data').on( 'draw.dt', function () {
      setTimeout(function(){
      let get_data = table.row(0).data();
      fpjp_lines_id = get_data.fpjp_lines_id;
      table_detail.draw();
      }, 500);
  });

  $('#table_data tbody').on( 'click', 'tr', function () {
    if (! $(this).hasClass('selected') ) {
      table.$('tr.selected').removeClass('selected');
      $(this).addClass('selected');
      let data = table.row( this ).data();
      fpjp_lines_id = data.fpjp_lines_id;

      $("#table_detail_title").html('Detail of '+ data.line_name);
      table_detail.draw();
    }
  });


  $('.btn-approval').on( 'click', function () {
    approve = $(this).data('approve');

    if(approve == "reject"){
      $("#btn_approval").removeClass('btn-warning btn-success');
      $("#btn_approval").addClass('btn-danger');
      $("#modal-approve-label").html('Reject');
      btn_approval = '<i class="fa fa-times-circle"></i> Reject';
      approvaL_display = '<h3 class="text-danger">This FPJP has been <b>Rejected!</b></h2>';
    }
    else if(approve == "return"){
      $("#btn_approval").removeClass('btn-danger btn-success');
      $("#btn_approval").addClass('btn-warning');
      $("#modal-approve-label").html('Return');
      btn_approval = '<i class="fa fa-arrow-circle-left"></i> Return';
      approvaL_display = '<h3 class="text-warning">This FPJP has been <b>Returned!</b></h3>';
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
        url       : baseURL + 'fpjp/approval/api/action_approval',
        type      : 'post',
        data      : { id_fpjp : ID_FPJP, level : LEVEL, approval : approve, remark : remark},
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