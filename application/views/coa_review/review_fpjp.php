<?php $this->load->view('ilustration') ?>

<div class="row">
  <div class="white-box boxshadow m-b-0 mt-10 border-top-only-5 z-index-2 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">FPJP Approval</h5>
    </div>
  </div>
  <div class="white-box boxshadow z-index-2 py-10 border-bottom-only-5 small">
    <div class="row">
      <form class="form-horizontal">
      <div class="col-sm-6 col-md-5">
        <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">FPJP Type <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="fpjp_type" class="control-label text-left"><?= $fpjp_type ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">FPJP Number <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="fpjp_number" class="control-label text-left"><?= $fpjp_number ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">FPJP Name <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="fpjp_name" class="control-label text-left"><?= $fpjp_name ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
              <label class="col-sm-5 col-md-4 control-label text-left">Currency<?= ($fpjp_currency != "IDR") ? "/Rate" : "" ?> <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label id="fpjp_currency" class="control-label text-left"><?= $fpjp_currency ?><?= ($fpjp_currency != "IDR") ? " / ".$fpjp_rate : "" ?></label>
              </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Total Amount <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="fpjp_amount" class="control-label text-left"><?= $fpjp_amount ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Directorate <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label text-left"><?= get_directorat($fpjp_directorat) ?></label>
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
            <label class="col-sm-5 col-md-4 control-label text-left">Justification <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
            <?php if($id_fs > 0): ?>
                <label class="control-label text-left"><?= get_fs($id_fs) ?> - <?= get_fs($id_fs, "FS_NAME") ?> &nbsp; <a class="btn btn-outline btn-xs btn-info" href="<?= $fs_link ?>" target="_blank"> <i class="fa fa-external-link"></i></i> View </a>
                </label>
            <?php else: ?>
                <label class="control-label text-left">Non Justif</label>
            <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-md-5">
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">FPJP Date <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label text-left"><?= $fpjp_date ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Vendor Name <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label text-left"><?= $vendor_name ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Document Uploaded <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label class="control-label text-left"><?= ($fpjp_doc_upload != "-") ? '<a id="fpjp_attachment" class="btn btn-xs btn-success" href="'.$fpjp_doc_upload.'" target="_blank"> <i class="fa fa-download"></i> Download </a>' : $fpjp_doc_upload ?></label>
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
            <label class="col-sm-5 col-md-4 control-label text-left">FPJP History <span class="pull-right">:</span></label>
                <div class="col-sm-7 col-md-8">
              <?php if($fpjp_history): ?>
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

<div class="row<?= ($fpjp_justif == 'non_justif') ? ' d-none' : '' ?>">
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
                <th class="text-center">Item Description</th>
                <th class="text-center">Nature</th>
                <th class="text-center">Nature</th>
                <th class="text-center">Product</th>
                <th class="text-center">Product</th>
                <th class="text-center">Tribe</th>
                <th class="text-center">Tribe</th>
                <th class="text-center">QTY</th>
                <th class="text-center">PPN</th>
                <th class="text-center">Price</th>
                <th class="text-center">Total Price</th>
            </tr>
          </thead>
        </table>
      </div>
  </div>
  </div>
</div>



<div class="row">
  <div class="white-box boxshadow">
    <div class="row">
      <div id="approvaL_display" class="col-sm-12">
        <?php if(isset($approver_before_name)): ?>
        <div class="m-b-15">
          <h5>Approved by <?= $approver_before_name ?> at <?= $approver_before_date ?></h5>
          <h5>Remark : <?= (empty($approver_before_remark)) ? "-" : $approver_before_remark ?></h5>
        </div>
        <?php endif;?>
      <?php if($review_status == "N"){ ?>
          <button class="btn btn-info waves-effect m-r-5 pull-right" data-toggle="modal" data-target="#modal-confirm" type="button" ><i class="fa fa-save"></i> Save</button>
      <?php } else if($review_status == "Y"){ ?>
          <h3 class="text-success">Thank You For Your <b>Confirmation!</b></h3>
          <h5>Confirm at <?= date("d F Y", strtotime($review_date)) ?></h5>
      <?php } ?>
      </div>
    </div>
  </div>
</div>



 <div class="modal fade" id="modal-confirm" role="dialog" aria-labelledby="modal-confirm">
    <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title text-white">Confirmation</h4>
          </div>
          <div class="modal-body">
              Are You Sure?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cancel</button>
            <button type="button" class="btn btn-success waves-effect" id="btn_approval"><i class="fa fa-check-circle"></i> Confirm</button>
          </div>
      </div>
    </div>
  </div>

<?php if($fpjp_history): ?>
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
          <h2 class="modal-title text-white font-size-18" id="modal-comment-label">FPJP History</h2>
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
                <?php foreach ($fpjp_history as $key => $value):
                if($value['STATUS'] == "approved"){
                  $badge = "badge-success";
                }else if($value['STATUS'] == "returned"){
                  $badge = "badge-warning";
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

  const ID_FPJP        = '<?= $id_fpjp ?>';
  const JUSTIF_TYPE    = '<?= $fpjp_justif ?>';
  const TRIBE_CHANGE   = <?= ($fpjp_justif == 'non_justif') ? 'true' : 'false' ?>;
  const PRODUCT_CHANGE = <?= (strtolower($fpjp_type) == 'gaji') ? 'true' : 'false' ?>;
  const FPJP_CURRENCY  = '<?= $fpjp_currency ?>';
  const REVIEW         = <?= ($review_status == "N") ? 'true' : 'false'?>;
  let status           = $('#status').val();
  
  let fpjp_category = 0;
  
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
                  {"data": "line_name", "width": "250px" },
                  {"data": "fund_available", "width": "100px", "class": "text-right"  },
                  {"data": "nominal", "width": "150px", "class": "text-right"  }
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
  let url_detail  = baseURL + 'coa-review/api/load_data_details_fpjp';
  let fpjp_header_id = 0;

  Pace.track(function(){
      $('#table_detail').DataTable({
        "serverSide"      : true,
        "processing"      : true,
        "ajax"            : {
                  "url"  : url_detail,
                  "type" : "POST",
                  "dataType": "json",
                  "data"    : function ( d ) {
                              d.fpjp_header_id = ID_FPJP;
                              d.justif_type    = JUSTIF_TYPE;
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
                  {"data": "nature_opt", "width": "200px" },
                  {"data": "product", "width": "100px" },
                  {"data": "product_opt", "width": "100px" },
                  {"data": "tribe", "width": "150px" },
                  {"data": "tribe_opt", "width": "150px" },
                  {"data": "quantity", "width": "50px", "class": "text-center"  },
                  {"data": "tax_view", "width": "50px", "class": "text-center"},
                  {"data": "price", "width": "100px", "class": "text-right"  },
                  {"data": "nominal", "width": "200px", "class": "text-right" }
                ],
        "drawCallback": function ( settings ) {
            setTimeout(function(){
              $(".select2").select2();
            }, 400);
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


  let table_detail = $('#table_detail').DataTable();
  $('#table_detail_length').remove()
  $('#table_detail_info').remove()
  $('#table_detail_filter').remove()
  $('#table_detail_paginate').remove()


  $('#table_detail').on( 'draw.dt', function () {

      if(REVIEW){
        disabled = [2];
        enabled  = [3];
        if(PRODUCT_CHANGE){
          disabled.push( 4 );
          enabled.push( 5);
        }else{
          disabled.push( 4, 5 );
        }

        if(TRIBE_CHANGE){
          disabled.push( 6 );
          enabled.push( 7);
        }else{
          disabled.push( 7 );
        }
      }else{
        enabled  = [2];
        disabled = [3];
        if(PRODUCT_CHANGE){
          enabled.push( 4 );
          disabled.push( 5);
        }else{
          disabled.push( 4);
          disabled.push( 5);
        }
        if(TRIBE_CHANGE){
          enabled.push( 6 );
          disabled.push( 7);
        }else{
          disabled.push( 7);
        }
      }

      console.log(enabled);
      console.log(disabled);

      table_detail.columns( enabled ).visible( true );
      table_detail.columns( disabled ).visible( false );
  });


  $('#btn_approval').on( 'click', function () {
    fatalError = false;

    if (fatalError == true)
    {
      customNotif('Warning', notice, 'warning');
      return false;
    }
    else{

      let detail_data = [];
      tribe_val = 0;
      product_val = 0;
      table_detail.rows().eq(0).each( function ( index ) {
        j = index+1;
        data = table_detail.row( index ).data();

        nature_val = $("#nature_opt-"+j).val();
        if(TRIBE_CHANGE){
          tribe_val   = $("#tribe_opt-"+j).val();
        }

        if(PRODUCT_CHANGE){
          product_val = $("#product_opt-"+j).val();
        }
        
        fpjp_detail_code = data.fpjp_detail_code;

        detail_data.push( {'fpjp_detail_code' : fpjp_detail_code,  'nature' : nature_val, 'tribe' : tribe_val, 'product' : product_val} );

      });

      console.log(detail_data)

      $.ajax({
        url       : baseURL + 'coa-review/api/update_coa_fpjp',
        type      : 'post',
        data      : { id_fpjp : ID_FPJP, data_lines : detail_data},
        beforeSend  : function(){
                        customLoading('show');
                      },
        dataType : 'json',
        success : function(result){
          customLoading('hide');
          if (result.status == true) {
            $("#modal-confirm").modal('hide');
            customNotif('Success', result.messages, 'success');
            setTimeout(function(){
              location.reload();
            }, 400);
          } else {
            customNotif('Failed', result.messages, 'error');
          }
        }
      });
    }
  });


    setTimeout(function(){
      $(".select2").select2();
    }, 500);
});
</script>