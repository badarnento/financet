<div class="row">
  <div class="white-box boxshadow">
    <div class="row">
      <div class="col-sm-3">
        <div class="form-group">
          <label>Status</label>
          <select class="form-control" id="status">
            <option value="">-- All Status --</option>
            <option value="request_approve" selected>Need Approval</option>
            <option value="approved">Approved</option>
            <option value="returned">Returned</option>
            <option value="rejected">Rejected</option>
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
    		<table id="table_data" class="table table-responsive w-full table-striped table-hover table-bordered display cell-border stripe small" style="font-size:11px !important;">
	          <thead>
	            <tr>
      					<th class="text-center">NO.</th>
      					<th class="text-center">DIRECTORAT</th>
      					<th class="text-center">DIVISON</th>
      					<th class="text-center">UNIT</th>
                <th class="text-center">JUSTIF NUMBER</th>
      					<th class="text-center">DPL NUMBER</th>
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

    let url        = baseURL + 'dpl/approval/api/load_dpl_to_approve';
    let ajaxData = {
      "url"  : url,
      "type" : "POST",
      "dataType": "json",
      "data"    : function ( d ) {
       d.status    = status;
     }
   }

   let jsonData = [
      { "data": "no", "width": "20px", "class": "text-center p-5" },
      { "data": "directorat", "width": "80px", "class": "p-5" },
      { "data": "division", "width": "100px", "class": "p-5" },
      { "data": "unit", "width": "100px", "class": "p-5" },
      { "data": "justification", "width": "120px", "class": "p-5" },
      { "data": "dpl_number", "width": "80px", "class": "p-5" },
      { 
                "data": "id_dpl_approval",
                "width":"30px",
                "class":"text-center",
                "render": function (data) {
                   return '<a href="javascript:void(0)" class="action-view" title="Click to view DPL Detail" data-id="' + data + '"><i class="fa fa-search text-success" aria-hidden="true"></i></a>';
                }
            }
         ];

  data_table(ajaxData,jsonData);

  table = $('#table_data').DataTable();

  $('#btnView').on('click', function () {
    status = $('#status').val();
    table.draw();
  });

  $('#table_detail_filter').html('');
  $('#table_detail_length').html('');

  $('#table_data').on('click', 'a.action-view', function () {
    id_dpl_approval = $(this).data('id');
    $(location).attr('href', baseURL + 'dpl/approval/' + id_dpl_approval);
  });


});
</script>