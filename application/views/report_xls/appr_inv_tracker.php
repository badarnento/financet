<div class="row">
<div class="white-box boxshadow">
  <div class="row">
    <div class="col-md-3 col-sm-6">
        <div class="form-group">
          <label>Date From</label>
          <div class="input-group">
            <input type="text" class="form-control mydatepicker" id="date_from" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 5)?>">
            <span class="input-group-addon"><i class="icon-calender"></i></span>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="form-group">
          <label>Date To</label>
          <div class="input-group">
            <input type="text" class="form-control mydatepicker" id="date_to" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 5)?>">
            <span class="input-group-addon"><i class="icon-calender"></i></span>
          </div>
        </div>
      </div>
    </div>
  <div class="col-md-2">
      <div class="form-group">
        <button id="btnView" class="btn btn-default btn-rounded custom-input-width btn-block" type="button"><i class="fa fa-search"></i> <span>VIEW</span></button>
      </div>
  </div>
  <div class="row">
    <div class="form-group">
      <div class="col-md-2 col-md-2 col-sm-12">
        <button id="btnCetak" class="btn btn-success btn-rounded btn-block" type="button" ><i class="fa  fa-file-excel-o"></i> <span>Download</span></button>
      </div>
    </div>
  </div>
</div>
</div>

<div class="row">
  <div class="white-box boxshadow m-b-0 border-top-only-5 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">Approval Invoice Tracker</h5>
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
                <th class="text-center">Receive Date</th>
                <th class="text-center">Tanggal Invoice</th>
                <th class="text-center">Due Date</th>
                <th class="text-center">No Journal</th>
                <th class="text-center">No Invoice</th>
                <th class="text-center">No Kontrak</th>
                <th class="text-center">No FPJP</th>
                <th class="text-center">Nama Vendor</th>
                <th class="text-center">Approval Invoice</th>
                <th class="text-center">Approval Invoice Date</th>
                <th class="text-center">Validate Tax</th>
                <th class="text-center">Validate Date Tax</th>
                <th class="text-center">Verification Status</th>
                <th class="text-center">Verification Date</th>
                <th class="text-center">Approval Lead</th>
                <th class="text-center">Approval Lead Date</th>
                <th class="text-center">Approval HoU</th>
                <th class="text-center">Approval HoU Date</th>
              </tr>
            </thead>
          </table>
      </div>
    </div>
  </div>
</div>

<script>
  
  $(document).ready(function(){

    let date_from     = $("#date_from").val();
    let date_to       = $("#date_to").val();

  let url = baseURL + 'report_xls/Appr_inv/load_appr_inv_tracker';

  Pace.track(function(){
    $('#table_data').DataTable({
      "serverSide"      : true,
      "processing"      : true,
      "ajax"            : {
                "url"  : url,
                "type" : "POST",
                "dataType": "json",
                "data"    : function ( d ) {
                            d.date_from        = date_from;
                            d.date_to          = date_to;
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
                  { "data": "receive_date", "width":"100px", "class":"text-center"},
                  { "data": "tanggal_invoice", "width":"100px", "class":"text-center"},
                  { "data": "due_date", "width":"100px", "class":"text-center"},
                  { "data": "no_journal", "width":"200px", "class":"text-left"},
                  { "data": "no_invoice", "width":"200px", "class":"text-left"},
                  { "data": "no_kontrak", "width":"100px", "class":"text-left"},
                  { "data": "no_fpjp", "width":"150px", "class":"text-left"},
                  { "data": "nama_vendor", "width":"300px", "class":"text-left"},
                  { "data": "approved_invoice", "width":"120px", "class":"text-center"},
                  { "data": "approved_invoice_date", "width":"120px", "class":"text-center"},
                  { "data": "validated", "width":"120px", "class":"text-center"},
                  { "data": "validate_date_tax", "width":"120px", "class":"text-center"},
                  { "data": "verificated", "width":"120px", "class":"text-center"},
                  { "data": "verificated_date", "width":"120px", "class":"text-center"},
                  { "data": "approved", "width":"120px", "class":"text-center"},
                  { "data": "approved_date", "width":"120px", "class":"text-center"},
                  { "data": "approved_hou", "width":"120px", "class":"text-center"},
                  { "data": "approved_hou_date", "width":"120px", "class":"text-center"}
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
  $("#tbl_search").on('keyup', "input[type='search']", function(){
      table.search( $(this).val() ).draw();
  });

  $('#btnView').on( 'click', function () {
      date_from     = $("#date_from").val();
      date_to       = $("#date_to").val();
    table.draw();
  });

    $("#btnCetak").on("click", function(){
      date_from  = $('#date_from').val();
      date_to    = $('#date_to').val();
      url         ="<?php echo site_url(); ?>report_xls/Appr_inv/cetak_report";
      window.open(url+'?date_from='+date_from+'&date_to='+date_to, '_blank');
      window.focus();
    });

  $('.mydatepicker').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight:'TRUE',
    autoclose: true,
  });

  });
</script>