<div class="row">
  <div class="white-box boxshadow">
    <div class="row">
      <div class="col-sm-3">
        <div class="form-group">
          <label>Status</label>
          <select class="form-control" id="status">
            <option value="">-- All Status --</option>
            <option value="need_review" selected>Need Review</option>
            <option value="reviewed">Reviewed</option>
          </select>
        </div>
      </div>
      <div class="col-sm-3">
        <div class="form-group">
          <label>&nbsp;</label>
          <button id="btnView" class="btn btn-default btn-rounded custom-input-width btn-block" type="button"><i class="fa fa-search"></i> <span>VIEW</span></button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="white-box boxshadow">
    <div class="row">
      <div class="col-md-12">
        <table id="table_fpjp" class="table table-responsive w-full table-striped table-hover table-bordered display cell-border stripe small" style="font-size:11px !important;">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th class="text-center">DIRECTORAT</th>
                <th class="text-center">DIVISON</th>
                <th class="text-center">UNIT</th>
                <th class="text-center">FPJP NUMBER</th>
                <th class="text-center">FPJP NAME</th>
                <th class="text-center">TOTAL AMOUNT</th>
                <th class="text-center">ACTION</th>
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
      <div class="col-md-12">
        <table id="table_pr" class="table table-responsive w-full table-striped table-hover table-bordered display cell-border stripe small" style="font-size:11px !important;">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th class="text-center">DIRECTORAT</th>
                <th class="text-center">DIVISON</th>
                <th class="text-center">UNIT</th>
                <th class="text-center">PR NUMBER</th>
                <th class="text-center">PR NAME</th>
                <th class="text-center">TOTAL AMOUNT</th>
                <th class="text-center">ACTION</th>
              </tr>
            </thead>
          </table>
      </div>

    </div>
  </div>
</div>

<script>
  $(document).ready(function(){

  let status        = $('#status').val();
  let dtl_clicked   = false;

    let url        = baseURL + 'coa-review/api/load_fpjp_to_review';
    let url2        = baseURL + 'coa-review/api/load_pr_to_review';

    let ajaxData = {
      "url"  : url,
      "type" : "POST",
      "dataType": "json",
      "data"    : function ( d ) {
       d.status    = status;
     }
   }
    let ajaxData2 = {
      "url"  : url2,
      "type" : "POST",
      "dataType": "json",
      "data"    : function ( d ) {
       d.status    = status;
     }
   }

   let jsonData = [
      { "data": "no", "width": "30px", "class": "text-center p-5" },
      { "data": "directorat", "width": "100px", "class": "p-5" },
      { "data": "division", "width": "100px", "class": "p-5" },
      { "data": "unit", "width": "100px", "class": "text-center p-5" },
      { "data": "fpjp_number", "width": "150px", "class": "p-5" },
      { "data": "fpjp_name", "width": "150px", "class": "p-5" },
      { "data": "total_amount", "width": "100px", "class": "text-right p-5" },
      { 
        "data": "id_fpjp",
          "width":"50px",
          "class":"text-center",
          "render": function (data) {
             return '<a href="javascript:void(0)" class="action-view" title="Click to view FS Detail" data-id="' + data + '"><i class="fa fa-search text-success" aria-hidden="true"></i></a>';
          }
        }
       ];

  data_table(ajaxData,jsonData, "table_fpjp");

  table_fpjp = $('#table_fpjp').DataTable();

  // $('#table_fpjp_filter').html('');
  // $('#table_fpjp_length').html('');

  $('#table_fpjp').on('click', 'a.action-view', function () {
      id_fpjp = $(this).data('id');
      $(location).attr('href', baseURL + 'coa-review/fpjp/' + id_fpjp);
  });

   let jsonData2 = [
      { "data": "no", "width": "30px", "class": "text-center p-5" },
      { "data": "directorat", "width": "100px", "class": "p-5" },
      { "data": "division", "width": "100px", "class": "p-5" },
      { "data": "unit", "width": "100px", "class": "text-center p-5" },
      { "data": "pr_number", "width": "150px", "class": "p-5" },
      { "data": "pr_name", "width": "150px", "class": "p-5" },
      { "data": "total_amount", "width": "100px", "class": "text-right p-5" },
      { 
        "data": "id_pr",
            "width":"50px",
            "class":"text-center",
            "render": function (data) {
               return '<a href="javascript:void(0)" class="action-view" title="Click to view FS Detail" data-id="' + data + '"><i class="fa fa-search text-success" aria-hidden="true"></i></a>';
            }
        }
       ];

  data_table(ajaxData2,jsonData2, "table_pr");

  table_pr = $('#table_pr').DataTable();

  // $('#table_pr_filter').html('');
  // $('#table_pr_length').html('');

  $('#table_pr').on('click', 'a.action-view', function () {
    id_pr = $(this).data('id');
      $(location).attr('href', baseURL + 'coa-review/pr/' + id_pr);
  });

  $('#btnView').on('click', function () {
    status = $('#status').val();
    table_fpjp.draw();
    table_pr.draw();
  });


});
</script>