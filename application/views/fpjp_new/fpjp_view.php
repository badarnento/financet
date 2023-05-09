<?php if(strtolower($fpjp_status) == "returned"): ?>
    <div class="row">
        <button id="edit_fpjp" class="btn btn-warning border-radius-5 w-100p m-b-10" type="button"><i class="fa fa-edit"></i> Edit FPJP</button>
    </div>
<?php endif; ?>

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
              <label id="fpjp_type" class="control-label text-left"><?= get_type($fpjp_type) ?></label>
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
          <!-- <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Category <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label text-left"><?= $fpjp_justif_type ?></label>
            </div>
          </div> -->
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Justification <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
            <?php if($id_fs > 0): ?>
                <label class="control-label text-left"><?= get_fs($id_fs) ?> - <?= get_fs($id_fs, "FS_NAME") ?> &nbsp; <a class="btn btn-outline btn-xs btn-info" href="<?= $fs_link ?>" target="_blank"> <i class="fa fa-external-link"></i></i> View </a>
                </label>
                
                  <?php if($id_fs_2 > 0): ?>
                  <label class="control-label text-left"><?= get_fs($id_fs_2) ?> - <?= get_fs($id_fs_2, "FS_NAME") ?> &nbsp; <a class="btn btn-outline btn-xs btn-info" href="<?= $fs_link_2 ?>" target="_blank"> <i class="fa fa-external-link"></i></i> View </a>
                  </label>
                  <?php endif; ?>
                  <?php if($id_fs_3 > 0): ?>
                  <label class="control-label text-left"><?= get_fs($id_fs_3) ?> - <?= get_fs($id_fs_3, "FS_NAME") ?> &nbsp; <a class="btn btn-outline btn-xs btn-info" href="<?= $fs_link_3 ?>" target="_blank"> <i class="fa fa-external-link"></i></i> View </a>
                  </label>
                  <?php endif; ?>
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
            <label class="col-sm-5 col-md-4 control-label text-left">Vendor Bank Name <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label text-left"><?= $bank_name ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Bank Account Name <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label text-left"><?= $bank_account_name ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Bank Account Number <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label text-left"><?= $bank_account_number ?></label>
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
                <label class="control-label text-left"><?= $fpjp_submitter ?> &ndash; <?= $fpjp_jabatan_sub ?></label>
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
      <!--   <div class="form-group m-b-10">
          <label class="col-sm-5 col-md-4 control-label text-left">Justif Document <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label class="control-label"><a id="fs_attachment" class="btn btn-xs btn-success" href="<?= $fs_attachment['FILE_LINK'] ?>" target="_blank"> <i class="fa fa-download"></i> Download </a></label>
              </div>
          </div> -->
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
                <th class="text-center">PPN</th>
                <!-- <th class="text-center">COD & DGT</th> -->
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
          <!-- <div class="col-sm-8">
            <div class="form-group m-b-10">
              <label class="col-sm-5 col-md-3 control-label text-left">Document List <span class="pull-right">:</span></label>
                <div class="col-sm-8">
                   <?php
                    if($fpjp_doc_list != "-"):
                      foreach ($fpjp_doc_list as $key => $value):?>
                        <label class="control-label text-left"><?= $value ?> <i class='text-info fa fa-check-circle fa-lg' title='<?= $value ?>'></i></label>
                        <br>
                  <?php
                      endforeach;
                    else:
                      echo $fpjp_doc_list;
                    endif;
                  ?>
                </div>
            </div>
          </div> -->
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

<div class="row" id="fpjp_boq">
  <div class="white-box boxshadow m-b-0 border-top-only-5 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">FPJP BOQ</h5>
    </div>
  </div>
  <div class="white-box boxshadow border-bottom-only-5">
    <div class="row">
      <div class="col-md-12">
          <table id="table_detail_boq" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small w-full">
            <thead>
              <tr>
                  <th class="text-center">No</th>
                  <th class="text-center">Item Name</th>
                  <th class="text-center">Item Description</th>
                  <th class="text-center">Quantity</th>
                  <th class="text-center">UoM</th>
                  <th class="text-center">Unit Price</th>
                  <th class="text-center">Total Price</th>
                  <th class="text-center">Asset Type</th>
                  <th class="text-center">Serial Number</th>
                  <th class="text-center">Merek</th>
                  <th class="text-center">Umur Manfaat</th>
                  <th class="text-center">Invoice Date</th>
                  <th class="text-center">Receipt Date</th>
              </tr>
            </thead>
          </table>
        </div>
    </div>
   </div>
 </div>

<script>
  $(document).ready(function(){

  const ID_FPJP   = '<?= $id_fpjp ?>';
  const ID_BOQ   = '<?= $fpjp_boq ?>';
  const FPJP_TYPE = $('#fpjp_type').text();
  console.log(FPJP_TYPE);
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
                  {"data": "original_amount", "width": "100px", "class": "text-right"  }/*,
                  {"data": "pemilik_rekening", "width": "150px" },
                  {"data": "nama_bank", "width": "150px" },
                  {"data": "no_rekening", "width": "100px" }*/
                ],
        "drawCallback": function ( settings ) {
          // $('#table_data_paginate').html('');
        },
        <?php if(strtolower($fpjp_justif_type) != "justification"):?>
        "columnDefs": [
            {
                "targets": [ 2 ],
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
                  // {"data": "cod_dgt", "width": "100px", "class": "text-center" },
                  {"data": "price", "width": "100px", "class": "text-right"  },
                  {"data": "nominal", "width": "200px", "class": "text-right" }
                ],
      "columnDefs": [
              {
                  "targets": [ 5 ],
                  "visible": false
              }
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

  let url_detail_boq  = baseURL + 'fpjp/api/load_data_details_boq';
  let fpjp_header_id = 0;

  Pace.track(function(){
      $('#table_detail_boq').DataTable({
        "serverSide"      : true,
        "processing"      : true,
        "ajax"            : {
                  "url"  : url_detail_boq,
                  "type" : "POST",
                  "dataType": "json",
                  "data"    : function ( d ) {
                          d.fpjp_header_id = ID_FPJP;
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
        "pageLength"      : 100,
        "ordering"        : false,
        "scrollY"         : 480,
        "scrollX"         : true,
        "scrollCollapse"  : true,
        "autoWidth"       : true,
        "bAutoWidth"      : true
      });
  });

  let table_detail_boq = $('#table_detail_boq').DataTable();

  if(FPJP_TYPE == "Credit Card" ||FPJP_TYPE == "Reimburse"){
    table_detail.columns( [5] ).visible( true );
  }
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
      table_detail_boq.draw();
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

  <?php if(isset($coa_review)): ?>
    customNotif('Success', 'Thank you for your confirmation', 'success', 5000);
  <?php endif; ?>

  const fpjp_enc = "<?= $id_fpjp_enc ?>";

  $("#edit_fpjp").on('click', function () {
    $(location).attr('href', baseURL + 'fpjp/edit/'+fpjp_enc);
  });

  if(ID_BOQ != ""){
    $("#fpjp_boq").show();
  }else{
    $("#fpjp_boq").hide();
  }

  });
</script>