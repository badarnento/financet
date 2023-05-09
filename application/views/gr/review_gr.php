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
    		<table id="table_data" class="table table-responsive w-full table-striped table-hover table-bordered display cell-border stripe small" style="font-size:11px !important;">
	          <thead>
	            <tr>
    					<th class="text-center">No.</th>
    					<th class="text-center">DIRECTORAT</th>
    					<th class="text-center">DIVISON</th>
    					<th class="text-center">UNIT</th>
    					<th class="text-center">GR NUMBER</th>
    					<th class="text-center">NO BAST</th>
    					<th class="text-center">STATUS</th>
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

    let url        = baseURL + 'gr/approval/api/load_gr_to_review';
    let ajaxData = {
      "url"  : url,
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
			{ "data": "unit", "width": "150px", "class": "p-5" },
			{ "data": "gr_number", "width": "150px", "class": "p-5" },
			{ "data": "no_bast", "width": "150px", "class": "p-5" },
			{ "data": "status_description", "width": "150px", "class": "text-center p-5" },
			{ 
                "data": "id_gr",
                "width":"50px",
                "class":"text-center",
                "render": function (data) {
                   return '<a href="javascript:void(0)" class="action-view" title="Click to view FS Detail" data-id="' + data + '"><i class="fa fa-search text-success" aria-hidden="true"></i></a>';
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
		id_gr = $(this).data('id');
  	$(location).attr('href', baseURL + 'gr/review/' + id_gr);
	});


});
</script>