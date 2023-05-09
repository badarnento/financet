
<?php $this->load->view('ilustration') ?>
<!-- <div class="row">
  <div class="white-box boxshadow m-b-0 mt-10 border-top-only-5 z-index-2 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">FPJP Detail</h5>
    </div>
  </div>
  <div class="white-box boxshadow z-index-2 py-10 border-bottom-only-5 small"> -->

<div class="row">
	<div class="white-box boxshadow m-b-0 mt-10 border-top-only-5 z-index-2 panel-title-custom">
		<div class="col-md-6">
			<h5 class="font-weight-700 m-0 text-uppercase">Informasi PO</h5>
		</div>
	</div>
	<div class="white-box boxshadow mt-0 z-index-2 py-10 border-bottom-only-5 small">
		<div class="row">
			<div class="col-md-4">
				<h5 class="m-t-5 m-b-10">Total Pending Drafting</h5>
				<h3 id="total_fs" class="font-weight-700 mt-0 m-b-10"><?= $total_pr_pending ?></h3>
			</div>
			<div class="col-md-4">
				<h5 class="m-t-5 m-b-10">Total PO Pending</h5>
				<h3 id="fs_request" class="font-weight-700 mt-0 m-b-10"><?= $total_po_pending ?></h3>
			</div>
			<div class="col-md-4">
				<h5 class="m-t-5 m-b-10">Total PO Succeed</h5>
				<h3 id="fs_approved" class="font-weight-700 mt-0 m-b-10"><?= $total_po_success ?></h3>
			</div>
		</div>
	</div>
</div>


<div class="row">
  <div class="white-box boxshadow m-b-0 border-top-only-5 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">List PR to Drafting PO</h5>
    </div>
  </div>
  <div class="white-box boxshadow border-bottom-only-5">
	<div class="row">
		<div class="col-md-12">
    		<table id="table_data" class="table table-hover small display table-striped table-responsive dataTable w-full">
		        <thead>
		        	<tr>
		                <th>Unit</th>
		                <th>PR Number</th>
		                <th>PR Name</th>
		                <th>PR Date</th>
		                <th>Amount</th>
		                <th>Action</th>
			        </tr>
		        </thead>
		    </table>
	    </div>
	</div>
  </div>
</div>


<script>

	$(document).ready(function(){
		
		let trecker = 1;
	
		let url        = baseURL + 'purchase-order/api/load_pr_header_for_buyer';
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
	                              "infoEmpty"   : "Data Kosong",
	                              "processing"  : '<img src="' + imageLoadingSmall + '" alt="" />',
	                              "search"      : "_INPUT_",
	                              'paginate': {
								      'previous': '<i class="fa fa-angle-left"></i>',
								      'next': '<i class="fa fa-angle-right"></i>',
								    },
								    "lengthMenu": " &nbsp; _MENU_ &nbsp; rows per page"
	                            },
	        "columns"         : [
	                            { "data": "unit", "width": "150px" },
	                            { "data": "pr_number", "width": "150px" },
	                            { "data": "pr_name", "width": "150px" },
	                            { "data": "pr_date", "width": "100px" },
	                            { "data": "total_amount", "width": "100px", "class":"text-right" },
	                            { 
	                              "data": "pr_header_id",
	                              "width":"70px",
	                              "class":"text-center",
	                              "render": function (data) {
	                                return '<a href="javascript:void(0)" class="action-create text-info" title="Click to view Create PO" data-id="' + data + '"> Create PO </a>';
	                              }
	                            }
	                ],
	          "drawCallback": function ( settings ) {

	          	if(trecker == 1){
	          	}

	          	trecker++;

	          },
			"fnDrawCallback": function () {
		        $('#table_data_length').prepend($('#table_data_info'));
		    },
		     "dom": '<"top"i>rt<"bottom"flp><"clear">',
	        "ordering"        : false,
	        "scrollY"         : 250,
	        "scrollX"         : false,
	        "scrollCollapse"  : true,
	        "autoWidth"       : true,
	        "bAutoWidth"      : true,
	      });
	  });

		let table = $('#table_data').DataTable();

		$("#table_data_filter").remove();

		$('#table_data').on('click', 'a.action-create', function () {
			let pr_header_id = $(this).data('id');
			$(location).attr('href', baseURL + 'po/drafting/' + pr_header_id);
		});

  });

</script>