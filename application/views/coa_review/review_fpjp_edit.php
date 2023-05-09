
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
        <th class="text-center">Line Name</th>
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
                <th class="text-center">Tax</th>
                <th class="text-center">Price</th>
                <th class="text-center">Nominal</th>
            </tr>
          </thead>
        </table>
      </div>
  </div>
  </div>
<?php if($coa_edit == false):?>
  <div class="white-box boxshadow border-bottom-only-5">
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
                  <div id="attachment_view" class="col-sm-9">
                    <label class="control-label text-left"><a id="fpjp_attachment" href="<?= base_url("download/") . encrypt_string("uploads/fpjp_attachment/".$fpjp_attachment, true) ?>" title="<?= $fpjp_attachment ?>" target="_blank"><?= substr($fpjp_attachment, 0, 25). "..."?> </a> <button type="button" id="change_file" class="btn btn-warning btn-xs m-l-10 px-10 py-2 pull-right"><i class="fa fa-edit"></i> Change </button> </label>
            </div>
                  <div id="upload_attachment" class="col-sm-7 d-none">
                      <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                          <div id="fileinput" class="form-control input-sm" data-trigger="fileinput"> <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div><span class="input-group-addon btn btn-default btn-file"> <span class="fileinput-new">Select file</span> <span class="fileinput-exists">Change</span>
                          <input id="attachment" type="file" name="attachment" data-name="fpjp" accept=".pdf,.zip,.rar"> </span> <a id="fileinput_remove" href="javascript:void(0)" class="input-group-addon btn btn-warning fileinput-exists" data-dismiss="fileinput">Remove</a>
                      </div>
                        <div class="progress progress-lg d-none">
                            <div id="progress" class="progress-bar progress-bar-info active progress-bar-striped" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%" role="progressbar"></div>
                        </div>
                        <button type="button" id="cancel_change_file" class="btn btn-danger btn-xs px-5 pull-right"><i class="fa fa-times"></i> Cancel </button>
                        <span class="help-block"><small>Filename: FPJP_(No.Invoice)_(Vendor Name)_(Group Name).</small></span> 
            </div>
                </div>
                <div class="col-sm-offset-3 col-sm-7">
                  <!-- <button type="button" id="addFile" class="btn btn-info btn-xs px-5 pull-right"><i class="fa fa-upload"></i> Change </button> -->
                </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-8">
            <div class="form-group m-b-10">
              <label for="submitter" class="col-sm-3 control-label text-left">Notes <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
            <textarea class="form-control input-sm" id="notes" rows="8"><?= $notes ?></textarea>
                </div>
            </div>
          </div>
        </div>
    </form>
  </div>
<?php endif; ?>
</div>

<div class="row<?=($fpjp_boq == false)?' d-none':''?>" id="fpjp_boq">
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
                  <th class="text-center" style="display: none">BOQ Line Id</th>
                  <th class="text-center">Item Name</th>
                  <th class="text-center">Item Description</th>
                  <th class="text-center">Quantity</th>
                  <th class="text-center">UoM</th>
                  <th class="text-center">Unit Price</th>
                  <th class="text-center">Total Price</th>
                  <th class="text-center">Asset Type</th>
                  <th class="text-center">Serial Number</th>
                  <th class="text-center">Merek</th>
                  <th class="text-center">Umur Manfaat <a id="umur_manfaat_info" target="blank"><i class="fa fa-info-circle"></i></a></th>
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
          <div class="col-sm-6 col-sm-offset-6">
            <div class="form-group pull-right">
              <a href="<?= base_url('fpjp') ?>"  class="btn btn-danger border-radius-5 m-0 w-100p"><i class="fa fa-times"></i> Discard </a>
              <button type="button" id="save_data" class="btn btn-info border-radius-5 ml-10 w-100p"><i class="fa fa-save"></i> Save </button>
            </div>
          </div>
        </div>
      </form>
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

  const ID_FPJP     = '<?= $id_fpjp ?>';
  const ID_BOQ      = '<?= $id_fpjp_boq ?>';
  const opt_default = '<option value="0" data-name="">-- Choose --</option>';
  const opt_tax  = '<option value="0">0</option><option value="1"> 1% </option><option value="10"> 10% </option>';
  const CURRENCY    = '<?= $fpjp_currency ?>';
  const rate        = '<?= $fpjp_rate ?>';
  
  const directorat = <?= $fpjp_directorat ?>;
  const division   = <?= $fpjp_division ?>;
  const unit       = <?= $fpjp_unit ?>;
  let submitter    = '<?= $fpjp_submitter ?>';
  let jabatan_sub  = '<?= $fpjp_jabatan_sub ?>';

  const COA_EDIT    = <?= ($coa_edit) ? 'true' : 'false' ?>;
  const ENABLE_EDIT = <?= ($enable_edit) ? 'true' : 'false' ?>;

  let attachment_file = "";
  let last_attachment = "";

    let attach_category = $('#attachment').data('name');
  
  let disabled_el = '';
  let disabled_el2 = '';

  if(COA_EDIT){
    disabled_el = ' readonly';
  }

  if(ENABLE_EDIT && disabled_el == ''){
    disabled_el2 = ' readonly';
  }

  if(COA_EDIT == false){
    getSubmitter();
  }


   <?= 'let data_bank = '. json_encode($all_bank) . ';'; ?>

    setTimeout(function(){
        $("#vendor_name").trigger('change');
        $("#vendor_name").select2();
      }, 500);



  $("#vendor_name").on("change", function(){
    let key_vendor = $(this).val();

    $("#account_name").val("");
    $("#account_number").val("");

    let arr_bank = data_bank[key_vendor];
    let total_bank = arr_bank.length;
    if(total_bank > 1){
      let opt_bank = '<option value="">-- Choose --</option>';
      let selected_acc_number = '<?= $selected_acc_number?>';

      for (var i=0; i < arr_bank.length; i++) {
        bank = arr_bank[i];
        selected_val = (selected_acc_number == bank.ACCT_NUMBER) ? ' selected' : '';
        opt_bank += '<option value="' + bank.ACCT_NUMBER + '" data-bank="' + bank.NAMA_BANK + '" data-rek="' + bank.NAMA_REKENING + '" data-acct="' + bank.ACCT_NUMBER + '"' + selected_val + '>' + bank.NAMA_BANK + ' - ' + bank.NAMA_REKENING + '</option>';
      }
      $("#data_bank").html(opt_bank);
      if(selected_acc_number != ""){
        setTimeout(function(){
          $("#data_bank").trigger('change');
        }, 200);
      }
    }else{
      bank = arr_bank[0];
      let opt_bank = '<option value="' + bank.NAMA_BANK + '" data-bank="' + bank.NAMA_BANK + '">' + bank.NAMA_BANK + '</option>';
      $("#data_bank").html(opt_bank);
      $("#account_name").val(bank.NAMA_REKENING);
      $("#account_number").val(bank.ACCT_NUMBER);
    }

  });

  $("#data_bank").on("change", function(){

    this_row   = $(this).parents("tr").index() + 1;

    let nama_rek = $(this).find(':selected').attr('data-rek');
    let no_rek   = $(this).find(':selected').attr('data-acct');

    $("#account_name").val(nama_rek);
    $("#account_number").val(no_rek);

  });

  
  let url = baseURL + 'fpjp/api/load_data_lines';

  let counter        = 1;
  let fpjp_line_data   = {};
  let fpjp_line_boq   = {};

  let buttonAdd = '<button type="button" id="addRow-detail" class="btn btn-success btn-sm m-l-5 mr-5"><i class="fa fa-plus"></i> Add New </button>';
    buttonAdd += '<button type="button" id="deleteRow-detail" class="btn btn-danger btn-sm m-l-5 mr-5" disabled><i class="fa fa-trash"></i> Delete </button>';

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
                          d.category     = 'edit';
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
                  {"data": "line_name_edit", "width": "150px" },
                  {"data": "fund_av_edit", "width": "100px", "class": "text-right"  },
                  {"data": "nominal_edit", "width": "100px", "class": "text-right"  },
                  {"data": "original_amount_edit", "width": "100px", "class": "text-right"  }/*,
                  {"data": "pemilik_rekening_edit", "width": "150px", "class": "p-5" },
                  {"data": "nama_bank_edit", "width": "150px", "class": "p-5" },
                  {"data": "no_rekening_edit", "width": "100px", "class": "p-5" }*/
                ],
        "drawCallback": function ( settings ) {
          // $('#table_data_paginate').html('');
        },
        <?php if($id_fs == false): ?>
        "columnDefs": [
                {
                    "targets": [2],
                    "visible": false
                }
            ],
        <?php endif; ?>
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
/*
  $('#table_data_length').html('<h4>FPJP Lines</h4>');
  $('#table_data_paginate').remove();*/



  $('#table_data_filter').remove();
  $('#table_data_length').remove();
  $('#table_data_paginate').remove();

    $("#tbl_search").on('keyup', "input[type='search']", function(){
        table.search( $(this).val() ).draw();
    });


  let tracker = 0;

  $('#table_data').on( 'draw.dt', function () {
    if(tracker == 0){
      getDataDetail();
      getDataDetailBoq();
    }
    tracker++;
  });

  let rkap_desc      = '<div class="form-group m-b-0"><input id="rkap_desc-'+counter+'" class="form-control input-sm rkap_desc"'+ disabled_el +'></div>';
  let nature_opt     = '<div class="form-group m-b-0"><select id="nature_opt-'+counter+'" class="form-control input-sm nature_opt select-center">';
      nature_opt += opt_default;
      nature_opt += '</select></div>';
  let quantity       = '<div class="form-group m-b-0"><input id="quantity-'+counter+'" data-id="'+counter+'" class="form-control input-sm quantity text-center" value="1" min="1" max="99999" type="number"'+ disabled_el +'></div>';
  let price          = '<div class="form-group m-b-0"><input id="price-'+counter+'" data-id="'+counter+'" class="form-control input-sm price text-right money-format" value="0"'+ disabled_el+disabled_el2 +'></div>';
  let nominal_detail = '<div class="form-group m-b-0"><input id="nominal_detail-'+counter+'" class="form-control input-sm nominal_detail text-right" value="0" readonly></div>';
  let tax_opt     = '<div class="form-group m-b-0"><select id="tax_opt-'+ counter +'"'+ disabled_el+disabled_el2 +' class="form-control input-sm tax_opt select-center">';
      tax_opt += opt_tax;
      tax_opt += '</select></div>';

  let table_detail = $('#table_detail').DataTable({
      "data":[{
          "no": counter,
          "rkap_desc": rkap_desc,
          "nature": nature_opt,
          "quantity": quantity,
          "tax": tax_opt,
          "price": price,
          "nominal_detail": nominal_detail
          }],
      "columns":[
            {"data": "no", "width": "10px", "class": "text-center" },
            {"data": "rkap_desc", "width": "250px", "class": "p-2" },
            {"data": "nature", "width": "300px", "class": "p-2" },
            {"data": "quantity", "width": "100px", "class": "p-2 text-center" },
            {"data": "tax", "width": "70px", "class": "p-2" },
            {"data": "price", "width": "150px", "class": "p-2" },
            {"data": "nominal_detail", "width": "200px", "class": "p-2" }
          ],
    "ordering"        : false,
    "scrollY"         : 480,
    "scrollX"         : true,
    "scrollCollapse"  : true,
    "paging"      : false
  });

  $('#table_detail_length').html('<h4 id="table_detail_title">FPJP Detail</h4>')
  $('#table_detail_paginate').remove()
  $('input[type="search"]').attr('placeholder','Search here...').addClass('form-control input-sm m-0');

  let urlboq = baseURL + 'fpjp/api/load_fpjp_boq_edit';

  let lov_asset = '<option value="">-- Choose --</option>';

  let fpjp_item      = '<div class="form-group m-b-0"><input id="fpjp_item-'+counter+'" class="form-control input-sm fpjp_item" autocomplete="off"></div>';
  let fpjp_boq_id      = '<div class="form-group m-b-0"><input id="fpjp_boq_id-'+counter+'" class="form-control input-sm fpjp_boq_id" autocomplete="off"></div>';
  let fpjp_desc      = '<div class="form-group m-b-0"><input id="fpjp_desc-'+counter+'" class="form-control input-sm fpjp_desc" autocomplete="off"></div>';
  let uom      = '<div class="form-group m-b-0"><input id="uom-'+counter+'" class="form-control input-sm uom" autocomplete="off"></div>';
  let qty_boq       = '<div class="form-group m-b-0"><input id="qty_boq-'+counter+'" data-id="'+counter+'" class="form-control input-sm qty_boq text-center" value="1" min="1" max="99999" type="number"></div>';
  let unit_price          = '<div class="form-group m-b-0"><input id="unit_price-'+counter+'" data-id="'+counter+'" class="form-control input-sm unit_price text-right money-format" value="0"></div>';
  let total_price = '<div class="form-group m-b-0"><input id="total_price-'+counter+'" class="form-control input-sm total_price text-right" value="0" readonly></div>';
  let serial_number   = '<div class="form-group m-b-0"><input id="serial_number-'+counter+'" class="form-control input-sm serial_number" autocomplete="off"></div>';
  let merek   = '<div class="form-group m-b-0"><input id="merek-'+counter+'" class="form-control input-sm merek" autocomplete="off"></div>';
  let umur_manfaat   = '<div class="form-group m-b-0"><input id="umur_manfaat-'+counter+'" class="form-control input-sm umur_manfaat" autocomplete="off"></div>';
  let invoice_date  = '<div class="form-group m-b-0"><input id="invoice_date-'+counter+'" class="form-control input-sm invoice_date date_period" ></div>';
  let receipt_date = '<div class="form-group m-b-0"><input id="receipt_date-'+counter+'" class="form-control input-sm receipt_date date_period" ></div>';
  let asset_type = '<div class="form-group m-b-0"><select id="asset_type-'+counter+'" class="form-control input-sm asset_type select2 select-center">'+lov_asset+'</select></div>';

  let table_boq = $('#table_boq').DataTable({
      "data":[{
          "no": counter,
          "fpjp_boq_id": fpjp_boq_id,
          "fpjp_item": fpjp_item,
          "fpjp_desc": fpjp_desc,
          "qty_boq": qty_boq,
          "uom": uom,
          "unit_price": unit_price,
          "total_price": total_price,
          "asset_type": asset_type,
          "serial_number": serial_number,
          "merek": merek,
          "umur_manfaat": umur_manfaat,
          "invoice_date": invoice_date,
          "receipt_date": receipt_date
          }],
      "columns":[
            {"data": "no", "width": "10px", "class": "text-center" },
            {"data": "fpjp_boq_id", "width": "180px", "class": "p-5 hidden" },
            {"data": "fpjp_item", "width": "150px", "class": "p-2" },
            {"data": "fpjp_desc", "width": "150px", "class": "p-2" },
            {"data": "qty_boq", "width": "100px", "class": "p-2" },
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
    "ordering"        : false,
    "scrollY"         : 480,
    "scrollX"         : true,
    "scrollCollapse"  : true,
    "paging"      : false
  });

  $('#table_boq_length').html('<h4 id="table_detail_title">FPJP Detail</h4>')
  $('#table_boq_paginate').remove()
  $('input[type="search"]').attr('placeholder','Search here...').addClass('form-control input-sm m-0');

  let buttonAddboq = '<button type="button" id="addRow-detail_boq" class="btn btn-success btn-sm m-l-5 mr-5"><i class="fa fa-plus"></i> Add New </button>';
    buttonAddboq += '<button type="button" id="deleteRow-detail_boq" class="btn btn-danger btn-sm m-l-5 mr-5" disabled><i class="fa fa-trash"></i> Delete </button>';

  $('#table_boq_filter').html(buttonAddboq);


  $('#table_detail_filter').html(buttonAdd);
  if(COA_EDIT){
    $('#table_detail_filter').remove();
  }

  $('#table_data tbody').on( 'click', 'tr', function () {
      table.$('tr.selected').removeClass('selected');
      $(this).addClass('selected');
      let get_table      = table.row( this );
      let index          = get_table.index()
      let index_Num      = index+1;
      let data           = get_table.data();

      total_data = table.data().count();

      $("#deleteRow-detail").attr('disabled', true);
      if(total_data > 1){
        $("#deleteRow").attr('disabled', false);
      }

      let fpjp_line_key_val = data.fpjp_line_key;
      let rkapline_val    = data.id_rkap_line;
      
      data_detail = fpjp_line_data[fpjp_line_key_val];

      let newDataDtl = [];

      $("#table_detail_title").html("Detail of line " + index_Num);

      data_detail.forEach(function(value, i) {

          j=i+1;
        
        let rkap_desc_val  = '<div class="form-group m-b-0"><input id="rkap_desc-'+ j +'" class="form-control input-sm rkap_desc" value="'+value.rkap_desc+'"'+ disabled_el +'></div>';
        let nature_opt     = '<div class="form-group m-b-0"><select id="nature_opt-'+ j +'" class="form-control input-sm nature_opt select-center">';
            nature_opt += opt_default;
            nature_opt += '</select></div>';
        let quantity       = '<div class="form-group m-b-0"><input id="quantity-'+j+'" data-id="'+j+'" class="form-control input-sm quantity text-center" value="'+value.quantity+'" min="1" max="99999" type="number"'+ disabled_el +'></div>';
        let tax_opt     = '<div class="form-group m-b-0"><select id="tax_opt-'+ j +'"'+ disabled_el+disabled_el2 +' class="form-control input-sm tax_opt select-center">';
            tax_opt += opt_tax;
            tax_opt += '</select></div>';
        let price          = '<div class="form-group m-b-0"><input id="price-'+j+'" data-id="'+j+'" class="form-control input-sm price text-right money-format" value="'+formatNumber(value.price)+'"'+ disabled_el+disabled_el2  +'></div>';
        let nominal_detail = '<div class="form-group m-b-0"><input id="nominal_detail-'+j+'" class="form-control input-sm nominal_detail text-right" value="'+formatNumber(value.nominal_detail)+'" readonly></div>';

        newDataDtl.push({
                "no": j,
                "rkap_desc": rkap_desc_val,
                "nature": nature_opt,
                "quantity": quantity,
                "tax": tax_opt,
                "price": price,
                "nominal_detail": nominal_detail
                });
        getNature(j, rkapline_val, value.nature);

        setTimeout(function(){
          getTax(j, value.tax);
        }, 300);
      });

      table_detail.rows().remove().draw();
      table_detail.rows.add(newDataDtl).draw();

      $("#table_detail_title").html("Detail of line " + index_Num);

  });


  $('#addRow-detail').on( 'click', function () {
    indexNow_detail = table_detail.data().count();

    if($("#rkap_desc-"+indexNow_detail).val() == "" || $("#nature_opt-"+indexNow_detail).val() == 0 || $("#nominal_detail-"+indexNow_detail).val() == 0){
      customNotif('Warning', 'Please fill out all field!', 'warning');
    }
    else{

      numDetail = indexNow_detail+1;

      let rkap_desc      = '<div class="form-group m-b-0"><input id="rkap_desc-'+numDetail+'" class="form-control input-sm rkap_desc"'+ disabled_el +'></div>';
      let nature_opt     = '<div class="form-group m-b-0"><select id="nature_opt-'+numDetail+'" class="form-control input-sm nature_opt select-center">';
          nature_opt += opt_default;
          nature_opt += '</select></div>';
      let quantity       = '<div class="form-group m-b-0"><input id="quantity-'+numDetail+'" data-id="'+numDetail+'" class="form-control input-sm quantity text-center" value="1" min="1" max="99999" type="number"'+ disabled_el +'></div>';
      let tax_opt     = '<div class="form-group m-b-0"><select id="tax_opt-'+numDetail+'" class="form-control input-sm tax_opt select-center">';
          tax_opt += opt_tax;
          tax_opt += '</select></div>';
      let price          = '<div class="form-group m-b-0"><input id="price-'+numDetail+'" data-id="'+numDetail+'" class="form-control input-sm price text-right money-format" value="0"'+ disabled_el +'></div>';
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

      let row_now      = $('#table_data tbody tr.selected');
      let get_table    = table.row( row_now );
      let data         = get_table.data();
      let rkapline_val = data.id_rkap_line;

      getNature(numDetail, rkapline_val);

    }
  });




  $('#table_detail tbody').on('input change blur', 'tr td input.quantity, tr td input.price', function () {
    if($(this).val().trim().length === 0){
      $(this).val(0);
    }

    index       = $(this).data('id');
    qty_int     = parseInt( $("#quantity-"+index).val() );
    price_int   = parseInt( $("#price-"+index).val().replace(/\./g, '') );
    nominal_val = (qty_int > 0 && price_int > 0) ? qty_int * price_int : 0;

    if( CURRENCY != "IDR"){
      rate_val = parseInt(rate.replace(/\./g, '') );
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



    $('#fpjp_date').datepicker({
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

    $('#addRow-detail_boq').on( 'click', function () {
    indexNow_detail_boq = table_boq.data().count();

      numDetailBoq = indexNow_detail_boq+1;
      let lov_asset = '<option value="">-- Choose --</option>';
       lov_asset += '<option value="Asset"> Asset </option>';
       lov_asset += '<option value="Non Asset"> Non Asset </option>';

      let fpjp_item      = '<div class="form-group m-b-0"><input id="fpjp_item-'+numDetailBoq+'" class="form-control input-sm fpjp_item" autocomplete="off"></div>';
      let fpjp_boq_id      = '<div class="form-group m-b-0"><input id="fpjp_boq_id-'+numDetailBoq+'" class="form-control input-sm fpjp_boq_id" autocomplete="off"></div>';
      let fpjp_desc      = '<div class="form-group m-b-0"><input id="fpjp_desc-'+numDetailBoq+'" class="form-control input-sm fpjp_desc" autocomplete="off"></div>';
      let uom      = '<div class="form-group m-b-0"><input id="uom-'+numDetailBoq+'" class="form-control input-sm uom" autocomplete="off"></div>';
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
                "fpjp_boq_id": fpjp_boq_id,
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

    get_table  = table_boq.row( $('#table_boq tbody tr.selected') );
    index      = get_table.index()

    total_data  = table_boq.data().count();

    if(total_data > 0){
      table_boq.row(index).remove().draw();

      table_boq.column( 0 ).data().each( function ( i ) {
        num = i+1;
        $('#table_boq tbody tr:eq(' + i + ') td:eq(0)').html(num);
        this_row = $('#table_boq tbody tr:eq(' + i + ')');
        this_row.find("input.fpjp_item").attr("id", 'fpjp_item-'+num);
        this_row.find("input.fpjp_desc").attr("id", 'fpjp_desc-'+num);
        this_row.find("input.uom").attr("id", 'uom-'+num);
        this_row.find("input.quantity").attr("id", 'uom-'+num);
        this_row.find("input.unit_price").attr("id", 'unit_price-'+num);
        this_row.find("input.total_price").attr('id', 'total_price-'+num);
        this_row.find("select.asset_type").attr('id', 'asset_type-'+num);
        this_row.find("input.serial_number").attr('id', 'serial_number-'+num);
        this_row.find("input.umur_manfaat").attr('id', 'umur_manfaat-'+num);
        this_row.find("input.invoice_date").attr('id', 'invoice_date-'+num);
        this_row.find("input.receipt_date").attr('id', 'receipt_date-'+num);
      });

      $(this).attr('disabled', true);
    }

    });


    $("#save_data").on('click', function () {

    getAmount();
    
    let fpjp_name    = $("#fpjp_name").val();
    let fpjp_date    = $("#fpjp_date").val();
    let notes      = $("#notes").val();

    let vendor_val           = $("#vendor_name").find(':selected').attr('data-vendor');
    let nama_bank_val        = $("#data_bank").find(':selected').attr('data-bank');
    let pemilik_rekening_val = $("#account_name").val();
    let no_rekening_val      = $("#account_number").val();

    if(COA_EDIT == false){
      submitter    = $("#submitter").val();
      jabatan_sub  = $("#jabatan_sub").html();
    }
    amount       = parseInt($("#amount").val().replace(/\./g, ''));

    let total_data   = table.data().count();
    let fatalNominal = false;
    let notice       = '';
    let checkFund = false;
    <?php if($id_fs > 0): ?>
    checkFund = true;
    <?php endif; ?>

      for (var i = 1; i <= total_data; i++) {
        fund_av       = $("#fund_av-"+i).val();
        nominal_val   = $("#nominal-"+i).val();

        if( parseInt(nominal_val.replace(/\./g, '')) < 1){
          fatalNominal =  true;
          notice       = 'Nilai nominal masih kosong pada data ke '+i;
          break;
        }

        if(checkFund){

          if( parseInt(nominal_val.replace(/\./g, '')) > parseInt(fund_av.replace(/\./g, '')) ){
            fatalNominal =  true;
            notice       = 'Nilai nominal lebih dari Fund Available pada data ke '+i;
            break;
          }

        }

        
      }

     let data_boq  = [];

    table_boq.rows().eq(0).each( function (index) {
    i = index+1;
    let total_boq = 0;
    data = table_boq.row( index ).data();

          boq_header_id  = data.fpjp_boq_id;
          fpjp_item      = $("#fpjp_item-"+i).val();
          fpjp_desc      = $("#fpjp_desc-"+i).val();
          qty_boq        = $("#qty_boq-"+i).val();
          uom            = $("#uom-"+i).val();
          unit_price     = $("#unit_price-"+i).val();
          total_price    = $("#total_price-"+i).val();
          total_boq      += parseInt(total_price.replace(/\./g, ''));
          asset_type     = $("#asset_type-"+i).val();
          serial_number  = $("#serial_number-"+i).val();
          merek          = $("#merek-"+i).val();
          umur_manfaat   = $("#umur_manfaat-"+i).val();
          invoice_date   = $("#invoice_date-"+i).val();
          receipt_date   = $("#receipt_date-"+i).val();

          data_boq.push({'boq_header_id' : boq_header_id, 'fpjp_item' : fpjp_item, 'fpjp_desc' : fpjp_desc, 'qty_boq' : qty_boq, 'uom' : uom, 'unit_price' : parseInt(unit_price.replace(/\./g, '')), 'total_price' : parseInt(total_price.replace(/\./g, '')), 'asset_type' : asset_type, 'serial_number' : serial_number, 'merek' : merek, 'umur_manfaat' : umur_manfaat, 'invoice_date' : invoice_date, 'receipt_date' : receipt_date});

          if( total_boq > amount ){
              fatalNominal =  true;
              notice       = 'Nilai BOQ lebih besar dari Total Amount';
            }
      });

    if(COA_EDIT == false && (fpjp_name == "" || amount == 0)){
        customNotif('Warning', "Please fill all field", 'warning');
    }
    else if (fatalNominal == true)
    {
      customNotif('Warning', notice, 'warning');
      return false;
    }
    else{

      let data_lines  = [];
      table.rows().eq(0).each( function ( index ) {
          j = index+1;
          data = table.row( index ).data();

          fund_av              = $("#fund_av-"+j).val();
          line_name_val        = $("#line_name-"+j).val();
          original_amount_val  = $("#original_amount-"+j).val();
          
          fpjp_lines_id     = data.fpjp_lines_id;
          fpjp_line_key_val = data.fpjp_line_key;
          data_detail     = fpjp_line_data[fpjp_line_key_val];

          if( parseInt(nominal_val.replace(/\./g, '')) > 0){
              data_lines.push({'fpjp_lines_id' : fpjp_lines_id, 'line_name' : line_name_val, 'nominal' : parseInt(nominal_val.replace(/\./g, '')), 'original_amount' : original_amount_val, 'detail_data' : data_detail});
          }

      });

        data = {
                fpjp_header_id : ID_FPJP,
                directorat : directorat,
                division : division,
                fpjp_name : fpjp_name,
                fpjp_date : fpjp_date,
                amount : amount,
                coa_updated : COA_EDIT,
                attachment : attachment_file,
                submitter : submitter,
                jabatan_sub : jabatan_sub,
                vendor : vendor_val,
                bank_name : nama_bank_val,
                pemilik_rekening : pemilik_rekening_val,
                no_rekening : no_rekening_val,
                notes : notes,
                data_line : data_lines,
                data_boq : data_boq
            }

        $.ajax({
            url   : baseURL + 'fpjp/api/save_fpjp_edit',
            type  : "POST",
            data  : data,
            dataType: "json",
            beforeSend  : function(){
                          customLoading('show');
                        },
            success : function(result){
              if(result.status == true){
                customNotif('Success', "FPJP Updated", 'success');
                setTimeout(function(){
                  customLoading('hide');
                  $(location).attr('href', baseURL + 'fpjp');
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

    $("#submitter").on("change", function(){
    getJabatan("submitter");
    });

    function getNominal(){
      let total_detail = table_detail.data().count();
      let totalNominal = 0;

      for (var i = 1; i <= total_detail; i++) {
        val_tax = parseInt($("#tax_opt-"+i).val());
        nominal_detail_val = parseInt($("#nominal_detail-"+i).val().replace(/\./g, ''));
        if( parseInt(val_tax) > 0){
          count_tax = nominal_detail_val * (val_tax / 100 );
          nominal_detail_val += Math.round(count_tax);
        }
        totalNominal += nominal_detail_val;
      }

      $("#table_data tbody tr.selected").find("input.nominal").val(formatNumber(totalNominal));
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

  function getTax(id_row, id_select=0) {

    $("#tax_opt-"+id_row).attr('disabled', true);
    $("#tax_opt-"+id_row).css('cursor', 'wait');

      if(id_select > 0 ){
      $("#tax_opt-"+id_row).val(id_select).change();
      }
    $("#tax_opt-"+id_row).attr('disabled', false);
    $("#tax_opt-"+id_row).css('cursor', 'default');
  }

  function getNature(id_row, id_rkap, id_select=0) {

    $("#nature_opt-"+id_row).attr('disabled', true);
    $("#nature_opt-"+id_row).css('cursor', 'wait');

      $.ajax({
          url   : baseURL + 'api-budget/load_nature_by_rkap',
          type  : "POST",
          data  : {id_rkap : id_rkap, category : 'edit'},
          dataType: "json",
          success : function(result){
        let nature_opt = opt_default;
        let val_nature = '';

            if(result.status == true){
          data = result.data;
          if(data.length > 1){
                for(var i = 0; i < data.length; i++) {
              obj = data[i];
              let selected = '';
                if(id_select == obj.id_coa){
                val_nature = obj.id_coa;
                selected   = ' selected';
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
            $("#nature_opt-"+id_row).select2();
        if(val_nature != ""){
          $("#nature_opt-"+id_row).val(val_nature).trigger('change.select2');
        }
          }
      });
  }
  

  function getDataDetail(){

    table.rows().eq(0).each( function ( index ) {
      let row         = table.row( index );
      let data        = row.data();
      let fpjp_lines_id = data.fpjp_lines_id;
      let fpjp_line_key = data.fpjp_line_key;

        $.ajax({
            url   : baseURL + 'fpjp/api/load_fpjp_detail_for_edit',
            type  : "POST",
            data  : {fpjp_lines_id : fpjp_lines_id},
            dataType: "json",
            success : function(result){
          let newData = [];
          result.forEach(function(value, i) {
              newData.push({
                "no": value.no,
                "rkap_desc": value.rkap_desc,
                "nature": value.nature,
                "quantity": value.quantity,
                "tax": value.tax,
                "price": value.price,
                "nominal_detail": value.nominal
            });
          });

          fpjp_line_data[fpjp_line_key] = newData;
            }
        });

    });

    setTimeout(function(){
      $('#table_data tbody tr').eq(0).trigger('click');
    }, 1000);
  }

  function getDataDetailBoq(){

      $.ajax({
        url   : urlboq,
          type  : "POST",
          data  : {category : 'edit', fpjp_header_id : ID_FPJP},
          dataType: "json",
          success : function(result){
        let newData = [];
        result.forEach(function(value, i) {
            newData.push({
              "no": value.no,
              "fpjp_item": value.fpjp_item,
              "fpjp_boq_id": value.fpjp_boq_id,
              "fpjp_desc": value.fpjp_desc,
              "qty_boq": value.qty_boq,
              "uom": value.uom,
              "unit_price": value.unit_price,
              "total_price": value.total_price,
              "serial_number": value.serial_number,
              "merek": value.merek,
              "umur_manfaat": value.umur_manfaat,
              "invoice_date": value.invoice_date,
              "receipt_date": value.receipt_date,
              "asset_type": value.asset_type
          });
        });

        table_boq.rows().remove().draw();
        table_boq.rows.add(newData).draw();
          }
      });
  }

  function storeDataDetail(){

    row_now = $('#table_data tbody tr.selected');
    let get_table      = table.row( row_now );
    let index          = get_table.index()
    let index_Num      = index+1;
    let data           = get_table.data();

    fpjp_line_key = data.fpjp_line_key;

    let total_detail = table_detail.data().count();
    let detail_data  = [];

    for (var i = 1; i <= total_detail; i++) {
      x=i-1;
      data         = table_detail.row( x ).data();
      fpjp_detail_id = data.fpjp_detail_id;

      rkap_desc_val      = $("#rkap_desc-"+i).val();
      nature_val         = $("#nature_opt-"+i).val();
      quantity_val       = $("#quantity-"+i).val();
      price_val          = $("#price-"+i).val();
      nominal_detail_val = $("#nominal_detail-"+i).val();
      tax_val            = $("#tax_opt-"+i).val();

      if(rkap_desc_val != "" && nature_val != 0 && parseInt(price_val.replace(/\./g, '')) > 0){
          detail_data.push({'rkap_desc' : rkap_desc_val, 'nature' : nature_val, 'quantity' : quantity_val, 'tax' : tax_val, 'price' : parseInt(price_val.replace(/\./g, '')), 'nominal_detail' : parseInt(nominal_detail_val.replace(/\./g, ''))});
      }
      }

      fpjp_line_data[fpjp_line_key] = detail_data;

  }

    function getSubmitter(){

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
                // submitter_opt += '<option value="'+ obj.nama +'" data-jabatan="'+ obj.jabatan +'">'+ obj.nama +'</option>';
                selected = (submitter === obj.nama) ? ' selected': '';
                submitter_opt += '<option value="'+ obj.nama +'" data-jabatan="'+ obj.jabatan +'" '+ selected +'>'+ obj.nama +'</option>';
            }
          }else{
            submitter_opt = opt_default;
            for(var i = 0; i < data.length; i++) {
                obj = data[i];
                // submitter_opt += '<option value="'+ obj.nama +'" data-jabatan="'+ obj.jabatan +'">'+ obj.nama +'</option>';
                selected = (submitter === obj.nama) ? ' selected': '';
                submitter_opt += '<option value="'+ obj.nama +'" data-jabatan="'+ obj.jabatan +'" '+ selected +'>'+ obj.nama +'</option>';
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

  $('#table_boq tbody').on('input change blur', 'tr td input.qty_boq, tr td input.unit_price', function () {
    if($(this).val().trim().length === 0){
      $(this).val(0);
    }

    index       = $(this).data('id');
    qty_int     = parseInt( $("#qty_boq-"+index).val() );
    price_int   = parseInt( $("#unit_price-"+index).val().replace(/\./g, '') );
    nominal_val = (qty_int > 0 && price_int > 0) ? qty_int * price_int : 0;

    $("#total_price-"+index).val(formatNumber(nominal_val));

    });

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



  $('#change_file').on( 'click', function () {
    $("#attachment_view").addClass("d-none");
    $("#upload_attachment").removeClass("d-none");
    attachment_file = last_attachment;
  });
  
  $('#cancel_change_file').on( 'click', function () {
    $("#upload_attachment").addClass("d-none");
    $("#attachment_view").removeClass("d-none");
    last_attachment =attachment_file;
    attachment_file = '';
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
          data  : {file: attachment_file},
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