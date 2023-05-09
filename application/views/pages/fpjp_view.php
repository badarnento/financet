<div class="row">
  <div class="white-box boxshadow">
	<form class="form-horizontal">
    <div class="row">
		<div class="col-sm-6">
          <div class="form-group m-b-10">
            <label for="fpjp_number" class="col-sm-3 control-label">FPJP Number <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-6">
              <input type="text" class="form-control" id="fpjp_number" value="<?= $fpjp_number ?>" readonly>
            </div>
          </div>
      </div>
      <div class="col-sm-6">
          <div class="form-group m-b-10">
          	<label for="fpjp_date" class="col-sm-3 control-label">FPJP Date <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-6">
				<div class="input-group">
					<input type="text" class="form-control" id="fpjp_date" value="<?= $fpjp_date ?>" readonly>
				</div>
            </div>
          </div>
      </div>
    </div>
    <div class="row">
    	<div class="col-sm-6">
    		<div class="form-group m-b-10">
            <label for="type" class="col-sm-3 control-label">FPJP Type <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-6">
              <input type="text" class="form-control" id="type" readonly value="<?= get_type($fpjp_type_id) ?>">
            </div>
          </div>
          <div class="form-group m-b-10">
            <label for="fpjp_name" class="col-sm-3 control-label">FPJP Name <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-6">
              <input type="text" class="form-control" id="fpjp_name" value="<?= $fpjp_name ?>" readonly>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label for="currency" class="col-sm-3 control-label">Currency <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-6">
            	<input type="text" class="form-control" id="currency" readonly value="<?= strtoupper($fpjp_currency) ?>">
            </div>
          </div>
          <div class="form-group m-b-10">
            <label for="amount" class="col-sm-3 control-label">Total Amount <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-6">
            	<input type="text" class="form-control" id="amount" readonly value="<?= $fpjp_amount ?>">
            </div>
          </div>
          <div class="form-group m-b-10">
            <label for="directorat" class="col-sm-3 control-label">Directorat <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-6">
              <input type="text" class="form-control" id="directorat" readonly value="<?= get_directorat($fpjp_directorat) ?>">
            </div>
          </div>
          <div class="form-group m-b-10">
            <label for="division" class="col-sm-3 control-label">Division <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-6">
              <input type="text" class="form-control" id="division" readonly value="<?= get_division($fpjp_division) ?>">
            </div>
          </div>
          <div class="form-group m-b-10">
            <label for="unit" class="col-sm-3 control-label">Unit <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-6">
              <input type="text" class="form-control" id="unit" readonly value="<?= get_unit($fpjp_unit) ?>">
            </div>
          </div>
    	</div>
    </div>
    </form>
  </div>
</div>

<div class="row">
	<div class="white-box">
		<div class="row">
			<div class="col-md-12">
		      <table id="table_data" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small hover w-full">
		        <thead>
		        	<tr>
			          	<th class="text-center">No</th>
			          	<th class="text-center">Tribe/Usecase</th>
			          	<th class="text-center">RKAP Name (Description)</th>
			          	<th class="text-center">FS</th>
			          	<th class="text-center">Name</th>
			          	<th class="text-center">Fund Available</th>
			          	<th class="text-center">Nominal</th>
			          	<th class="text-center">Nama Pemilik Rekening</th>
			          	<th class="text-center">Nama Bank</th>
			          	<th class="text-center">Nomor Rekening</th>
			        </tr>
		        </thead>
		      </table>
		    </div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<hr>
				<h4 id="table_detail_title">Data Detail</h4>
			</div>
			<div class="col-md-12">
		      <table id="table_detail" class="table dataTable w-full table-responsive table-striped table-bordered cell-border stripe small table-hover">
		        <thead>
		        	<tr>
			          	<th class="text-center">No</th>
			          	<th class="text-center">Description</th>
			          	<th class="text-center">Nature</th>
			          	<th class="text-center">Quantity</th>
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
  <div class="white-box boxshadow">
	<form class="form-horizontal">
    <div class="row">
		<div class="col-sm-6">
          <div class="form-group m-b-10">
            <label for="submiter" class="col-sm-5 control-label">Submiter <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-6">
              <input type="text" class="form-control" id="submiter" value="<?= get_submiter($fpjp_submiter) ?>" readonly>
            </div>
          </div>
      </div>
      <div class="col-sm-6">
          <div class="form-group m-b-10">
          	<div class="col-sm-9 col-md-6">
				<div class="input-group">
					<input type="text" class="form-control" id="jabatan_sub" value="<?= $fpjp_jabatan_sub ?>" readonly>
				</div>
            </div>
          </div>
      </div>
    </div>
    <div class="row">
		<div class="col-sm-6">
          <div class="form-group m-b-10">
            <label for="diketahui1" class="col-sm-5 control-label">Diketahui/Disetujui <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-6">
              <input type="text" class="form-control" id="diketahui1" value="<?= get_dik($fpjp_dik1) ?>" readonly>
            </div>
          </div>
      </div>
      <div class="col-sm-6">
          <div class="form-group m-b-10">
          	<div class="col-sm-9 col-md-6">
				<div class="input-group">
					<input type="text" class="form-control" id="jabatan1" value="<?= $fpjp_jabatan_dik1 ?>" readonly>
				</div>
            </div>
          </div>
      </div>
    </div>
    <div class="row">
		<div class="col-sm-6">
          <div class="form-group m-b-10">
            <label for="diketahui2" class="col-sm-5 control-label">Diketahui/Disetujui <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-6">
              <input type="text" class="form-control" id="diketahui2" value="<?= get_dik($fpjp_dik2) ?>" readonly>
            </div>
          </div>
      </div>
      <div class="col-sm-6">
          <div class="form-group m-b-10">
          	<div class="col-sm-9 col-md-6">
				<div class="input-group">
					<input type="text" class="form-control" id="jabatan2" value="<?= $fpjp_jabatan_dik2 ?>" readonly>
				</div>
            </div>
          </div>
      </div>
    </div>
    </form>
  </div>
</div>

<script>
  $(document).ready(function(){

	const fpjp_header_id = '<?= $fpjp_header_id ?>';
	let url = baseURL + 'fpjp/load_data_lines';

	Pace.track(function(){
	    $('#table_data').DataTable({
	      "serverSide"      : true,
	      "processing"      : true,
	      "ajax"            : {
									"url"  : url,
									"type" : "POST",
									"dataType": "json",
									"data"    : function ( d ) {
					                              d.fpjp_header_id = fpjp_header_id;
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
								{"data": "tribe", "width": "150px" },
								{"data": "rkap_name", "width": "200px" },
								{"data": "fs_name", "width": "200px" },
								{"data": "fpjp_line_name", "width": "150px" },
								{"data": "fund_available", "width": "100px", "class": "text-right"  },
								{"data": "nominal", "width": "100px", "class": "text-right"  },
								{"data": "pemilik_rekening", "width": "150px"},
								{"data": "nama_bank", "width": "150px"},
								{"data": "no_rekening", "width": "150px"}
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

	$('input[type="search"]').attr('placeholder','Search here...').addClass('form-control input-sm m-l-0');

	let table = $('#table_data').DataTable();
	
	let url_detail  = baseURL + 'fpjp/load_data_details';
	let fpjp_lines_id = '<?= $fpjp_header_id ?>';

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
				    			{"data": "quantity", "width": "50px", "class": "text-center" },
				    			{"data": "price", "width": "100px", "class": "text-right" },
				    			{"data": "nominal", "width": "200px", "class": "text-right" }
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

	$('#table_data').on( 'draw.dt', function () {
    	setTimeout(function(){
			let get_data = table.row(0).data();
			fpjp_lines_id = get_data.fpjp_lines_id;
			table_detail.draw();
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

    

  });
</script>