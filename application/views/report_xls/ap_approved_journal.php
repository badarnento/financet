<?php $this->load->view('ilustration') ?>

<div class="row">
  <div class="white-box boxshadow">
    <div class="row">
      <div class="col-md-3 col-sm-6">
        <div class="form-group">
          <label>Accounting Date From</label>
          <div class="input-group">
            <input type="text" class="form-control mydatepicker" id="accounting_date_from" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 5)?>">
            <span class="input-group-addon"><i class="icon-calender"></i></span>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="form-group">
          <label>Accounting Date To</label>
          <div class="input-group">
            <input type="text" class="form-control mydatepicker" id="accounting_date_to" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 5)?>">
            <span class="input-group-addon"><i class="icon-calender"></i></span>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label>&nbsp;</label>
          <button id="btnView" class="btn btn-default btn-rounded custom-input-width btn-block" type="button"><i class="fa fa-search"></i> <span>VIEW</span></button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="white-box boxshadow m-b-0 border-top-only-5 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">AP Approved Journal</h5>
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
                <th class="text-center">No.</th>
                <th class="text-center">Accounting Date</th>
                <th class="text-center">Batch Name</th>
                <th class="text-center">Journal Name</th>
                <th class="text-center">Debit</th>
                <th class="text-center">Credit</th>
                <th class="text-center">Nature</th>
                <th class="text-center">Account Description</th>
                <th class="text-center">Journal Description</th>
                <th class="text-center">Reference 1</th>
                <th class="text-center">Reference 2</th>
                <th class="text-center">Reference 3</th>
                <th class="text-center">Paid Date</th>
                <th class="text-center">Type Tax</th>
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
      <div class="form-group">
        <div class="col-md-5"></div>
        <div class="col-md-2 col-md-2 col-sm-12">
          <button id="btnDownload" class="btn btn-success btn-rounded btn-block" type="button" ><i class="fa  fa-file-excel-o"></i> <span>Download</span></button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  
  $(document).ready(function(){

  let accounting_date_from     = $("#accounting_date_from").val();
  let accounting_date_to       = $("#accounting_date_to").val();

  let url = baseURL + 'report_xls/AP_Approved_Journal/load_data';

  Pace.track(function(){
    $('#table_data').DataTable({
      "serverSide"      : true,
      "processing"      : true,
      "ajax"            : {
                "url"  : url,
                "type" : "POST",
                "dataType": "json",
                "data"    : function ( d ) {
                              d.accounting_date_from        = accounting_date_from;
                              d.accounting_date_to          = accounting_date_to;
                                    }
                        },
      "language"        : {
                            "emptyTable"  : "<span class='label label-danger'>Data Not Found!</span>",
                            "infoEmpty"   : "Empty Data",
                            "processing"  : '<img src="' + imageLoadingSmall + '" alt="" />',
                            "search"      : "_INPUT_"
                          },
      "columns"         : [
                  { "data": "no", "width":"10px", "class":"text-center"},
                  { "data": "accounting_date", "width":"100px", "class":"text-left"},
                  { "data": "batch_name", "width":"200", "class":"text-left"},
                  { "data": "journal_name", "width":"300px", "class":"text-left"},
                  { "data": "debit", "width":"100px", "class":"text-right"},
                  { "data": "credit", "width":"100px", "class":"text-right"},
                  { "data": "nature", "width":"100px", "class":"text-center"},
                  { "data": "account_description", "width":"300px", "class":"text-left"},
                  { "data": "journal_description", "width":"300px", "class":"text-left"},
                  { "data": "reference_1", "width":"100px", "class":"text-left"},
                  { "data": "reference_2", "width":"100px", "class":"text-left"},
                  { "data": "reference_3", "width":"100px", "class":"text-left"},
                  { "data": "paid_date", "width":"100px", "class":"text-left"},
                  { "data": "type_tax", "width":"200px", "class":"text-left"}
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

  $("#btnDownload").on("click", function(){
      accounting_date_from     = $("#accounting_date_from").val();
      accounting_date_to       = $("#accounting_date_to").val();
      url         ="<?php echo site_url(); ?>report_xls/AP_Approved_Journal/cetak_report";
      window.open(url+'?accounting_date_from='+accounting_date_from +"&accounting_date_to="+ accounting_date_to, '_blank');
      window.focus();
  });

  $('#btnView').on( 'click', function () {
      accounting_date_from     = $("#accounting_date_from").val();
      accounting_date_to       = $("#accounting_date_to").val();
    table.draw();
  });

  $('.mydatepicker').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight:'TRUE',
    autoclose: true,
  });

  });
</script>