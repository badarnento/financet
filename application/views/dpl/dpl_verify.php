<div class="row">
  <div class="white-box boxshadow">
    <div class="row">
      <div class="col-sm-3">
        <div class="form-group">
          <label>Status</label>
          <select class="form-control" id="status">
            <option value="">-- All Status --</option>
            <option value="wait_verify" selected>Need to verify</option>
            <option value="verified">Verified</option>
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
      					<th class="text-center">JUSTIFICATION</th>
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

  const LEVEL_VERIFIER = '<?= $level_verifier ?>';

    let url        = baseURL + 'dpl/verification/api/load_dpl_to_verify';
    let ajaxData = {
      "url"  : url,
      "type" : "POST",
      "dataType": "json",
      "data"    : function ( d ) {
       d.status    = status;
       d.level    = LEVEL_VERIFIER;
     }
   }

   let jsonData = [
			{ "data": "no", "width": "30px", "class": "text-center p-5" },
			{ "data": "directorat", "width": "100px", "class": "p-5" },
			{ "data": "division", "width": "100px", "class": "p-5" },
			{ "data": "unit", "width": "100px", "class": "text-center p-5" },
			{ "data": "justification", "width": "150px", "class": "p-5" },
      { "data": "dpl_number", "width": "100px", "class": "p-5" },
			{ 
                "data": "id_dpl_verify",
                "width":"50px",
                "class":"text-center",
                "render": function (data) {
                   return '<a href="javascript:void(0)" class="action-view px-5" title="Click to Verify or Reject DPL" data-id="' + data + '"><i class="fa fa-search text-success" aria-hidden="true"></i></a><a href="javascript:void(0)" class="action-cetak px-5" title="Click to Download PDF File" data-id="' + data + '"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>';
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
		id_dpl = $(this).data('id');
  	$(location).attr('href', baseURL + 'dpl/verification/' + id_dpl);
	});


  $('#table_data').on('click', 'a.action-cetak', function () {
      get_table = table.row( $(this).parents('tr') );
      index     = get_table.index();
      data      = get_table.data();
      id_dpl    = data.id_dpl;
      url       = baseURL + "dpl/print/";
      window.open(url+'/'+id_dpl, '_blank');
      window.focus();

  });


});
</script>