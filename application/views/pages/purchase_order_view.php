<div class="row">
  <div class="white-box boxshadow">
	<form class="form-horizontal">
    <div class="row">
		<div class="col-sm-6">
          <div class="form-group m-b-10">
            <label for="pr_number" class="col-sm-3 control-label">PR Number <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-6">
              <input type="text" class="form-control" id="pr_number" value="<?= $pr_number ?>" readonly>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label for="pr_name" class="col-sm-3 control-label">PR Name <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-6">
              <input type="text" class="form-control" id="pr_name" value="<?= $pr_name ?>" readonly>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label for="currency" class="col-sm-3 control-label">Currency <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-6">
            	<input type="text" class="form-control" id="currency" readonly value="<?= strtoupper($po_currency) ?>">
            </div>
          </div>
          <div class="form-group m-b-10">
            <label for="amount" class="col-sm-3 control-label">Total Amount <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-6">
            	<input type="text" class="form-control" id="amount" readonly value="<?= $po_amount ?>">
            </div>
          </div>
          <div class="form-group m-b-10">
            <label for="directorat" class="col-sm-3 control-label">Directorat <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-6">
              <input type="text" class="form-control" id="directorat" readonly value="<?= get_directorat($po_directorat) ?>">
            </div>
          </div>
          <div class="form-group m-b-10">
            <label for="division" class="col-sm-3 control-label">Division <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-6">
              <input type="text" class="form-control" id="division" readonly value="<?= get_division($po_division) ?>">
            </div>
          </div>
          <div class="form-group m-b-10">
            <label for="unit" class="col-sm-3 control-label">Unit <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-6">
              <input type="text" class="form-control" id="unit" readonly value="<?= get_unit($po_unit) ?>">
            </div>
          </div>
      </div>
      <div class="col-sm-6">
      	<div class="form-group m-b-10">
	      	<label for="pr_date" class="col-sm-3 control-label">PO Date <span class="pull-right">:</span></label>
	        <div class="col-sm-9 col-md-6">
				<div class="input-group">
					<input type="text" class="form-control" id="pr_date" value="<?= $po_date ?>" readonly>
				</div>
	        </div>
	    </div>
		<div class="form-group m-b-10">
          	<label for="po_type" class="col-sm-3 control-label">PO Type <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-6">
				<div class="input-group">
					<input type="text" class="form-control" id="po_type" value="<?= $po_type ?>" readonly>
				</div>
            </div>
        </div>
        <?php if($po_type == "Additional"): ?>
        <div class="form-group m-b-10">
          	<label for="po_reference" class="col-sm-3 control-label">PO Reference <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-6">
				<div class="input-group">
					<input type="text" class="form-control" id="po_reference" value="<?= $po_reference ?>" readonly>
				</div>
            </div>
        </div>
	    <!-- <div class="form-group m-b-10">
	              	<label for="po_name" class="col-sm-3 control-label">PO Name <span class="pull-right">:</span></label>
	                <div class="col-sm-9 col-md-6">
	    				<div class="input-group">
	    					<input type="text" class="form-control" id="po_name" value="" readonly>
	    				</div>
	    			</div>
	    		</div> -->
  		<?php endif; ?>
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
			          	<th class="text-center">PR Name</th>
			          	<th class="text-center">PR Amount</th>
			          	<th class="text-center">PO Name</th>
			          	<th class="text-center">PO Number</th>
			          	<th class="text-center">PO Amount</th>
			          	<th class="text-center">PO Period From</th>
			          	<th class="text-center">PO Period To</th>
			          	<th class="text-center">Vendor Name</th>
			          	<th class="text-center">Bank Name</th>
			          	<th class="text-center">Bank Account Name</th>
			          	<th class="text-center">Bank Account</th>
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
		      <table id="table_detail" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small table-hover w-full">
		        <thead>
		        	<tr>
			          	<th class="text-center">No</th>
			          	<th class="text-center">Description PR</th>
			          	<th class="text-center">Quantity</th>
			          	<th class="text-center">Price</th>
			          	<th class="text-center">Nominal PR</th>
			          	<th class="text-center">Description PO</th>
			          	<th class="text-center">Nominal PO</th>
			        </tr>
		        </thead>
		      </table>
		    </div>
		</div>
  </div>
</div>

<script>
  $(document).ready(function(){

	const po_header_id = '<?= $po_header_id ?>';
	let url            = baseURL + 'purchase/load_data_line_po';

	Pace.track(function(){
	    $('#table_data').DataTable({
	      "serverSide"      : true,
	      "processing"      : true,
	      "ajax"            : {
									"url"  : url,
									"type" : "POST",
									"dataType": "json",
									"data"    : function ( d ) {
													d.po_header_id = po_header_id;
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
				    			{"data": "line_name", "width": "150px" },
				    			{"data": "nominal", "width": "100px", "class": "text-right"  },
				    			{"data": "po_name", "width": "150px" },
				    			{"data": "po_number", "width": "150px" },
				    			{"data": "nominal_amount", "width": "100px", "class": "text-right"  },
				    			{"data": "po_period_from", "width": "150px" },
				    			{"data": "po_period_to", "width": "150px" },
				    			{"data": "vendor_name", "width": "150px" },
				    			{"data": "bank_name", "width": "150px" },
				    			{"data": "bank_account_name", "width": "200px" },
				    			{"data": "bank_account", "width": "150px" }
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
	
	let url_detail  = baseURL + 'purchase/load_data_detail_po';
	let po_line_id = 0;

	Pace.track(function(){
	    $('#table_detail').DataTable({
	      "serverSide"      : true,
	      "processing"      : true,
	      "ajax"            : {
									"url"  : url_detail,
									"type" : "POST",
									"dataType": "json",
									"data"    : function ( d ) {
													d.po_line_id = po_line_id;
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
				    			{"data": "quantity", "width": "50px", "class": "text-center"  },
				    			{"data": "price", "width": "100px", "class": "text-right"  },
				    			{"data": "nominal", "width": "200px", "class": "text-right" },
				    			{"data": "po_desc", "width": "200px" },
				    			{"data": "nominal_po", "width": "200px", "class": "text-right" }
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
			po_line_id = get_data.po_line_id;
			table_detail.draw();
    	}, 500);
	});

	$('#table_data tbody').on( 'click', 'tr', function () {
		if (! $(this).hasClass('selected') ) {
			table.$('tr.selected').removeClass('selected');
			$(this).addClass('selected');
			let data = table.row( this ).data();
			po_line_id = data.po_line_id;

			console.log(po_line_id);

			$("#table_detail_title").html('Detail of '+ data.po_number);
			table_detail.draw();
		}
	});

    
  });
</script>