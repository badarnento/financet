<div class="row">
  <div class="white-box boxshadow">
	<form class="form-horizontal">
    <div class="row">
		<div class="col-sm-6">
        	<div class="form-group m-b-10">
            	<label for="batch_name" class="col-sm-3 control-label">Batch Name <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	              <input type="text" class="form-control" id="batch_name" value="<?= $batch_name ?>" readonly>
	            </div>
	        </div>
        	<div class="form-group m-b-10">
            	<label for="batch_bank" class="col-sm-3 control-label">Bank Name <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	              <input type="text" class="form-control" id="batch_bank" value="<?= $batch_bank ?>" readonly>
	            </div>
	        </div>
        	<div class="form-group m-b-10">
            	<label for="amount" class="col-sm-3 control-label">Total Amount <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	            	<input type="text" class="form-control" id="amount" readonly value="<?= $batch_amount ?>">
	            </div>
	        </div>
	    </div>
	    <div class="col-sm-6">
        	<div class="form-group m-b-10">
          		<label for="fs_date" class="col-sm-3 control-label">Batch Date <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
					<div class="input-group">
						<input type="text" class="form-control" id="fs_date" value="<?= $batch_date ?>" readonly>
					</div>
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
			          	<th class="text-center">Journal Name</th>
			          	<th class="text-center">Vendor</th>
			          	<th class="text-center">Nama Bank</th>
			          	<th class="text-center">Tanggal Invoice</th>
			          	<th class="text-center">Total Amount</th>
			          	<th class="text-center"></th>
			        </tr>
		        </thead>
		      </table>
		    </div>
		</div>
  </div>
</div>
<div class="row" id="tblDatadownload1">

		<div class="col-md-12">

			<div class="white-box boxshadow">     

				<div class="row">

					<div class="form-group">

						<div class="col-md-offset-5 col-md-2 m-b-10">

							<button id="btnDownload1" class="btn btn-success btn-rounded btn-block" type="button" ><i class="fa  fa-file-excel-o"></i> <span>Download</span></button>

						</div>

					</div>

				</div>

			</div>

		</div>

	</div>

<div class="row">
	<div class="white-box">
		<div class="row">
			<div class="col-md-12">
		      <table id="table_journal" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small hover w-full">
		        <thead>
		        	<tr>
						<th class="text-center">No</th>
						<th class="text-center">No Journal</th>
						<th class="text-center">Account Description</th>
						<th class="text-center">Nature</th>
						<th class="text-center">Journal Description</th>
						<th class="text-center">Invoice Description</th>
						<th class="text-center">Status</th>
						<th class="text-center">GL Date</th>
						<th class="text-center">Debit</th>
						<th class="text-center">Credit</th>
						<!-- <th class="text-center">Reverse</th> -->
			        </tr>
		        </thead>
		      </table>
		    </div>
		</div>
  </div>
</div>

<div class="row" id="tblDatadownload2">

		<div class="col-md-12">

			<div class="white-box boxshadow">     

				<div class="row">

					<div class="form-group">

						<div class="col-md-offset-5 col-md-2 m-b-10">

							<button id="btnDownload2" class="btn btn-success btn-rounded btn-block" type="button" ><i class="fa  fa-file-excel-o"></i> <span>Download</span></button>

						</div>

					</div>

				</div>

			</div>

		</div>

	</div>


<div id="modal-view" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h2 class="modal-title text-white" id="modal-view-label" >Invoice Detail</h2>
        </div>
        <div class="modal-body">
			<div class="row">
				<div class="col-md-12">
				<!-- <div class="table-responsive"> -->
					<table id="table_detail" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small w-full">
						<thead>
				        	<tr>
								<th class="text-center">No</th>
								<th class="text-center">No Journal</th>
								<th class="text-center">Vendor</th>
								<th class="text-center">No Invoice</th>
								<th class="text-center">Tanggal Invoice</th>
								<th class="text-center">Invoice Description</th>
								<th class="text-center">Bank Charge</th>
								<th class="text-center">Amount</th>
					        </tr>
				        </thead>
				    </table>
				<!-- </div> -->
				</div>
	        </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal"><i class="fa fa-times-circle"></i> CLOSE</button>
        </div>
    </div>
  </div>
</div>
<style>
	.dataTable tr:hover td.disable-hover{
		background: #f7fafc !important;
	}
	/* 
	 tr td.disable-hover:hover{
			
		} */
	/* disable-hover */
</style>

<script>
  $(document).ready(function(){
	
	const batch_name = '<?= $batch_name ?>';
	let url          = baseURL + 'payment-batch/api/load_batch_payment_view';
	let journal_name = '';

	Pace.track(function(){
	    $('#table_data').DataTable({
	      "serverSide"      : true,
	      "processing"      : true,
	      "ajax"            : {
									"url"  : url,
									"type" : "POST",
									"dataType": "json",
									"data"    : function ( d ) {
													d.batch_name = batch_name;
													d.category   = 'view';
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
								{"data": "no_journal", "width": "150px", "class": "text-center"  },
								{"data": "nama_vendor", "width": "250px" },
								{"data": "nama_bank", "width": "150px", "class": "text-center"  },
								{"data": "tgl_invoice", "width": "100px", "class": "text-center"  },
								{"data": "total_amount", "width": "120px", "class": "text-right"  },
								{ 
			                        "data": "nama_vendor",
			                        "width":"20px",
			                        "class":"text-center",
			                        "render": function (data) {
			                           return '<a href="javascript:void(0)" class="action-view" title="Click to view Journal Detail" data-id="' + data + '"><i class="fa fa-search text-success" aria-hidden="true"></i></a>';
			                        }
			                    }
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

	const url2 = baseURL + 'payment-batch/api/load_batch_detail';

	Pace.track(function(){
	    $('#table_detail').DataTable({
	      "serverSide"      : true,
	      "processing"      : true,
	      "ajax"            : {
									"url"  : url2,
									"type" : "POST",
									"dataType": "json",
									"data"    : function ( d ) {
													d.batch_name = batch_name;
													d.no_journal = journal_name;
					                            }
			                    },
	      "language"        : {
	                            "emptyTable"  : "<span class='label label-danger'>Data Not Found!</span>",
	                            "infoEmpty"   : "Data Kosong",
	                            "processing"  : '<img src="' + imageLoadingSmall + '" alt="" />',
	                            "search"      : "_INPUT_"
	                          },
	      "columns"         : [
								{"data": "no", "width": "10px", "class": "p-t-5 p-b-5 text-center" },
								{"data": "no_journal", "width": "100px", "class": "p-t-5 p-b-5 text-center"  },
								{"data": "nama_vendor", "width": "150px", "class": "p-5" },
								{"data": "no_invoice", "width": "150px", "class": "p-t-5 p-b-5 text-center"  },
								{"data": "tgl_invoice", "width": "100px", "class": "p-t-5 p-b-5 text-center"  },
								{"data": "invoice_desc", "width": "250px", "class": "p-t-5 p-b-5 text-left"  },
								{"data": "bank_charge", "width": "80px", "class": "p-t-5 p-b-5 text-center"  },
								{"data": "amount", "width": "100px", "class": "p-t-5 p-b-5 text-right"  }
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

	let table_detail = $('#table_detail').DataTable();

	const url3 = baseURL + 'payment-batch/api/load_data_journal_payment';

	Pace.track(function(){
	    $('#table_journal').DataTable({
	      "serverSide"      : true,
	      "processing"      : true,
	      "ajax"            : {
									"url"  : url3,
									"type" : "POST",
									"dataType": "json",
									"data"    : function ( d ) {
													d.batch_name = batch_name;
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
								{"data": "no_journal", "width": "150px", "class": "text-center"  },
								{"data": "account_description", "width": "200px" },
								{"data": "nature", "width": "100px", "class": "text-center" },
								{"data": "journal_description", "width": "150px"  },
								{"data": "invoice_desc", "width": "200px"  },
								{"data": "status", "width": "100px", "class": "text-center"  },
								{"data": "gl_date", "width": "100px", "class": "text-center"  },
								{"data": "amount_debet", "width": "100px", "class": "text-right"  },
								{"data": "amount_credit", "width": "100px", "class": "text-right"  }
								// {"data": "journal_reverse", "width": "50px", "class": "text-center disable-hover"  }
								/*{ 
			                        "data": "journal_reverse",
			                        "width":"20px",
			                        "class":"text-center disable-hover",
			                        "render": function (data) {
			                        	// console.log(data.includes("REV") );
			                        	if(!journ) {
			                        		return '<a href="javascript:void(0)" class="action-reverse" title="Reverse Journal '+data+'" data-id="' + data + '"><i class="fa fa-refresh text-success" aria-hidden="true"></i></a>';
			                        	}
			                        	else{
			                        		return '';
			                        	}

			                        	return journ;
			                        }
			                    }*/
				    		],
		  "rowsGroup"       : [9],
	      "pageLength"      : 100,
	      "ordering"        : false,
	      "scrollY"         : 480,
	      "scrollX"         : true,
	      "scrollCollapse"  : true,
	      "autoWidth"       : true,
	      "bAutoWidth"      : true
	    });
	});

	$("#btnDownload1").on("click", function()
		{

		let url   ="<?php echo site_url(); ?>payment/batch_payment_ctl/donwload_data_batch_payment_view";

		vbatch_name = $("#batch_name").val();
		console.log(vbatch_name);

		window.open(url+'?nama_batch='+ vbatch_name, '_blank');
		window.focus();
		});

		$("#btnDownload2").on("click", function()
		{

		let url   ="<?php echo site_url(); ?>payment/batch_payment_ctl/donwload_data_journal_payment";

		vbatch_name = $("#batch_name").val();
		console.log(vbatch_name);

		window.open(url+'?nama_batch='+ vbatch_name, '_blank');
		window.focus();
		});

	$('input[type="search"]').attr('placeholder','Search here...').addClass('form-control input-sm m-l-0');

	let table_journal = $('#table_journal').DataTable();

	$('#table_data').on('click', 'a.action-view', function () {
		this_row      = $(this).parents('tr');
		let data      = table.row( this_row ).data();
		let index_row = this_row.index() + 1;
		vendor       = $(this).data('id');
		journal_name = data.no_journal;
		// journal_name = $("#journal_name-"+index_row).html();;
		nama_vendor  = vendor;
		$("#modal-view").modal('show');
		table_detail.columns.adjust().draw();
    });

	$('#table_journal').on('click', 'a.action-reverse', function () {

		no_journal = $(this).data('id');

		$.ajax({
          url       : baseURL + 'payment-batch/api/reverse_journal',
          type      : 'post',
          data      : { no_journal: no_journal},
          beforeSend  : function(){
                          customLoading('show');
                        },
          dataType : 'json',
          success : function(result){
            customLoading('hide');
            if (result.status == true) {
              table_journal.ajax.reload(null, false);
              customNotif('Success', result.messages, 'success');
            } else {
              customNotif('Failed', result.messages, 'error');
            }
          }
        });

    });
	
  });
</script>