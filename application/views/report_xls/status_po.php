<div class="row">
<div class="white-box boxshadow">
  <div class="row">
    <div class="form-group">
      <div class="col-md-2 col-md-2 col-sm-12">
        <button id="btnDownload" class="btn btn-success btn-rounded btn-block" type="button" ><i class="fa  fa-file-excel-o"></i> <span>Download</span></button>
      </div>
    </div>
  </div>
</div>
</div>

<div class="row">
  <div class="white-box boxshadow m-b-0 border-top-only-5 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">Status PO</h5>
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
                <th class="text-center">Vendor Name</th>
                <th class="text-center">PO Number</th>
                <th class="text-center">Period From</th>
                <th class="text-center">Period To</th>
                <th class="text-center">PO Amount</th>
                <th class="text-center">Invoice Number</th>
                <th class="text-center">Paid Amount</th>
                <th class="text-center">Remaining Amount</th>
                <th class="text-center">Status</th>
              </tr>
            </thead>
          </table>
      </div>
    </div>
  </div>
</div>

<script>
  
  $(document).ready(function(){

  let url = baseURL + 'report_xls/StatusPO_ctl/load_data';

  Pace.track(function(){
    $('#table_data').DataTable({
      "serverSide"      : true,
      "processing"      : true,
      "ajax"            : {
                "url"  : url,
                "type" : "POST",
                "dataType": "json",
                "data"    : function ( d ) {
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
                  { "data": "vendor_name", "width":"300px", "class":"text-left"},
                  { "data": "po_number", "width":"100", "class":"text-left"},
                  { "data": "period_from", "width":"100px", "class":"text-left"},
                  { "data": "period_to", "width":"100px", "class":"text-right"},
                  { "data": "amount_po", "width":"100px", "class":"text-right"},
                  { "data": "no_invoice", "width":"300px", "class":"text-left"},
                  { "data": "amount_paid", "width":"100px", "class":"text-right"},
                  { "data": "amount_remaining", "width":"100px", "class":"text-right"},
                  { "data": "status", "width":"100px", "class":"text-left"}
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
      url         ="<?php echo site_url(); ?>report_xls/StatusPO_ctl/cetak_report";
      window.open(url, '_blank');
      window.focus();
    });

  });
</script>