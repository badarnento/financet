<div class="row">
	<form class="form-horizontal">
		<div class="form-group m-b-10">
            <label for="year" class="col-xs-12 col-md-4 control-label text-left w-auto m-b-10">Berikut overview Budget kamu di periode :</label>
            <div class="col-xs-6 col-sm-2">
            	<select class="form-control m-b-10" id="year">
        		<?php foreach($get_exist_year as $value): ?>
        			<option value="<?= $value['TAHUN'] ?>"<?= ($value['TAHUN'] == date('Y')) ? ' selected' : '' ?>><?= $value['TAHUN'] ?></option>
        		<?php endforeach; ?>
            	</select>
            </div>
            <div class="col-xs-6 col-sm-2">
            	<button id="btnPrint" class="btn btn-info border-radius-5 w-150p m-b-10" type="button"><i class="fa fa-download"></i> Download</button>
            </div>
        </div>
	</form>
</div>

<?php $this->load->view('ilustration') ?>

<div class="row">
	<div class="white-box boxshadow m-b-0 mt-10 border-top-only-5 z-index-2 panel-title-custom">
		<div class="col-md-6">
			<h5 class="font-weight-700 m-0 text-uppercase">Informasi Budget</h5>
		</div>
	</div>
	<div class="white-box boxshadow mt-0 z-index-2 py-10 border-bottom-only-5 mb-0">
		<div class="row">
			<div class="col-md-4">
				<h5 class="m-t-5 m-b-10"><img src="<?= base_url('assets/custom/img/ic_rkap.png') ?>" class="m-r-5" alt="budget-rkap">Total RKAP</h5>
				<h3 id="total_rkap" class="font-weight-700 mt-0 m-b-10">Rp. <?= $total_rkap ?></h3>
			</div>
			<div class="col-md-4">
				<h5 class="m-t-5 m-b-10"><img src="<?= base_url('assets/custom/img/ic_budget_used.png') ?>" class="m-r-5" alt="budget-used">Budget Used</h5>
				<h3 id="budget_used" class="font-weight-700 mt-0 m-b-10">Rp. <?= $budget_used ?></h3>
			</div>
			<div class="col-md-4">
				<h5 class="m-t-5 m-b-10"><img src="<?= base_url('assets/custom/img/ic_budget_used.png') ?>" class="m-r-5" alt="budget-remain">Budget Remaining</h5>
				<h3 id="budget_remain" class="font-weight-700 mt-0 m-b-10">Rp. <?= $budget_remain ?></h3>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="white-box boxshadow m-b-0 mt-10 border-top-only-5 z-index-2 panel-title-custom">
		<div class="col-md-6">
			<h5 class="font-weight-700 m-0 text-uppercase">Informasi Justifikasi</h5>
		</div>
	</div>
	<div class="white-box boxshadow mt-0 z-index-2 py-10 border-bottom-only-5 mb-0">
		<div class="row">
			<div class="col-md-4">
				<h5 class="m-t-5 m-b-10">List Justifikasi</h5>
				<h3 id="total_fs" class="font-weight-700 mt-0 m-b-10"><?= $fs_total ?></h3>
			</div>
			<div class="col-md-4">
				<h5 class="m-t-5 m-b-10">Justifikasi Yang Diajukan</h5>
				<h3 id="fs_request" class="font-weight-700 mt-0 m-b-10"><?= $fs_request ?></h3>
			</div>
			<div class="col-md-4">
				<h5 class="m-t-5 m-b-10">Justifikasi Yang Dipakai &amp; Disetujui</h5>
				<h3 id="fs_approved" class="font-weight-700 mt-0 m-b-10"><?= $fs_used ?>/<?= $fs_approved ?></h3>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
	    		<table id="table_data" class="table table-hover small display table-striped table-responsive dataTable w-full">
		            <thead>
		              <tr>
		                <th>Judul Justifikasi</th>
		                <th>Tanggal Upload</th>
		                <th>Nominal</th>
		                <th>Status</th>
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
		
		let directorat = '<?= $id_directorat ?>';
		let division   = <?= (is_array($id_division)) ? '["'.implode('","',  $id_division ).'"]' : "'".$id_division."'" ?>;
		let unit       = <?= (is_array($id_unit)) ? '["'.implode('","',  $id_unit ).'"]' : "'".$id_unit."'" ?>;
		let year       = $("#year").val();

		let trecker = 1;
	
		let url        = baseURL + 'home/get_justification';
		 Pace.track(function(){
	      $('#table_data').DataTable({
	        "serverSide"      : true,
	        "processing"      : true,
	        "ajax"            : {
	                  "url"  : url,
	                  "type" : "POST",
	                  "dataType": "json",
	                  "data"    : function ( d ) {
										d.year       = year;
										d.directorat = directorat;
										d.division   = division;
										d.unit       = unit;
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
	                            { "data": "fs_name", "width": "250px" },
	                            { "data": "fs_date", "width": "150px" },
	                            { "data": "total_amount", "width": "150px" },
	                            { 
	                              "data": "fs_status",
	                              "width":"100px",
	                              "class":"text-center",
	                              "render": function (data) {
		                                if(data == "approved"){
		                                	badge = "badge-success";
		                                }else if(data == "fs used"){
		                                	badge = "badge-info";
		                                }else if(data == "returned"){
		                                	badge = "badge-warning";
		                                }else if(data == "rejected"){
		                                	badge = "badge-danger";
		                                }else{
		                                	badge = "badge-default";
		                                }
	                                	return '<div class="badge '+badge+' text-lowercase"> '+ data +'</div>';
	                            	}
	                            },
	                            { 
	                              "data": "id_fs",
	                              "width":"80px",
	                              "class":"text-center",
	                              "render": function (data) {
	                                return '<a href="javascript:void(0)" class="action-view" title="Click to view Justification" data-id="' + data + '"><i class="fa fa-eye text-grey" aria-hidden="true"></i></a>';
	                              }
	                            }
	                ],
	          "drawCallback": function ( settings ) {

	          	if(trecker == 1){
	  				// $("#table_data_length").remove();
          			// tbl_length = $(".dataTables_length");
	          	}

          		// tbl_length.appendTo("#table_data_info");

	          	// $("#table_data_info").append(' x ' + tbl_length);

	          	trecker++;

	          },
	          // "dom": '<"top"f>rt<"bottom"lp><"clear">',
			// "sDom": 'Rlfrtlip',
			"fnDrawCallback": function () {
		        $('#table_data_length').prepend($('#table_data_info'));
		    },
		     "dom": '<"top"i>rt<"bottom"flp><"clear">',
	        // "pageLength"      : 100,
	        "ordering"        : false,
	        "scrollY"         : 480,
	        "scrollX"         : false,
	        "scrollCollapse"  : true,
	        "autoWidth"       : true,
	        "bAutoWidth"      : true,
	      });
	  });

	  let table = $('#table_data').DataTable();

	  $("#table_data_filter").remove();

	  $("#year").on("change", function(){
	  	year = $(this).val();
	  	table.draw();
	  	get_justification(year);
	  });

	  function get_justification(year) {
		    $.ajax({
		        url   : baseURL + 'api-budget/load_data_budget_summary',
		        type  : "POST",
		        data  : {year: year, directorat: directorat, division: division, unit: unit},
		        dataType: "json",
		        success : function(result){

		        	data = result;

					$("#total_rkap").html("Rp. " + data.total_rkap);
					$("#budget_used").html("Rp. " + data.budget_used);
					$("#budget_remain").html("Rp. " + data.budget_remain);
					$("#total_fs").html(data.fs_total);
					$("#fs_request").html(data.fs_request);
					$("#fs_approved").html(data.fs_used + "/" + data.fs_approved);
		        }
		    });
		}


		$('#table_data').on('click', 'a.action-view', function () {
	      id_fs     = $(this).data('id');
	      $(location).attr('href', baseURL + 'feasibility-study/' + id_fs);
	    });

	    $("#btnPrint").on("click", function(){

			cdivision   = '<?= (!is_array($division) && $division != "") ? $division : "undefined" ?>';
			cunit       = '<?= (!is_array($unit) && $unit != "") ? $unit : "undefined" ?>';

			url   = 'capex/cetak_data_header/';

			let params  = { 'year' : $("#year").val(), 'directorat' : '<?= ($directorat) ? $directorat : "undefined" ?>', 'division' :  <?= (is_array($division)) ? '["'.implode('","',  $division ).'"]' : 'cdivision' ?>, 'unit' : <?= (is_array($unit)) ? '["'.implode('","',  $unit ).'"]' : 'cunit' ?> };

			open_url(url, params);
		});
	});

</script>