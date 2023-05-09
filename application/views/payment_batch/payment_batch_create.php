<div class="row">
  <div class="white-box boxshadow">
    <div class="row">
    	<div class="col-sm-2">
	        <div class="form-group">
	        	<label for="invoice_date_from" class="control-label">Invoice Date From</label>
	        	<div class="input-group">
	        		<!-- <input type="text" class="form-control mydatepicker" id="invoice_date_from" value="01-01-2019"> -->
	        		<input type="text" class="form-control mydatepicker" id="invoice_date_from" value="<?= dateFormat(time(), 5)?>">
	        		<span class="input-group-addon"><i class="icon-calender"></i></span>
	        	</div>
	        </div>
	    </div>
    	<div class="col-sm-2">
	        <div class="form-group">
	        	<label for="invoice_date_to" class="control-label">Invoice Date To</label>
	        	<div class="input-group">
	        		<input type="text" class="form-control mydatepicker" id="invoice_date_to" value="<?= dateFormat(time(), 5)?>">
	        		<span class="input-group-addon"><i class="icon-calender"></i></span>
	        	</div>
	        </div>
	    </div>
	    <div class="col-sm-2">
	        <div class="form-group">
	        	<label>Bank Name</label>
		    	<select class="form-control" id="bank_name">
		    		<option value="bni">BNI</option>
		    		<option value="others">Others</option>
		    	</select>
	        </div>
	    </div>
	    <div class="col-sm-2">
	        <div class="form-group">
	        	<label>Amount Group By Vendor</label>
		    	<select class="form-control" id="amount_group">
		    		<option value="0">No</option>
		    		<option value="1">Yes</option>
		    	</select>
	        </div>
	    </div>
	    <div class="col-sm-2">
	        <div class="form-group">
	        	<label>Amount</label>
		    	<select class="form-control" id="amount">
		    		<option value="1">&le; Rp 500 Juta dan setara</option>
		    		<option value="2">&le; Rp 5 Milyar dan setara</option>
		    		<option value="3">&le; Rp 10 Milyar dan setara</option>
		    		<option value="4">&gt; Rp 10 Milyar dan setara</option>
		    	</select>
	        </div>
	    </div>
	    <div class="col-sm-2">
	        <div class="form-group">
	        	<label>&nbsp;</label>
	        	<button id="btnView" class="btn btn-default btn-rounded custom-input-width btn-block" type="button"><i class="fa fa-search"></i> <span>FIND</span></button>
	        </div>
	    </div>
    </div>
  </div>
</div>

<div class="row">
	<div class="white-box">
		<div class="row">
			<div class="col-md-12">
				<h4 id="table_line_title">Invoice List</h4>
			</div>
			<div class="col-md-12">
		      <table id="table_data" class="table dataTable table-responsive table-striped table-bordered cell-border stripe w-full table-hover small">
		        <thead>
		        	<tr>
			          	<th class="text-center">No</th>
			          	<th class="text-center">Journal Name</th>
			          	<th class="text-center">Vendor</th>
			          	<th class="text-center">Nama Bank</th>
			          	<th class="text-center">Tanggal Invoice</th>
			          	<th class="text-center">Total Amount</th>
			          	<th class="text-center"></th>
			          	<th class="text-center"></th>
			        </tr>
		        </thead>
		      </table>
		    </div>
		</div>
		<div class="row">
	     	<div class="col-md-4 col-md-offset-4 text-center">
	     		<button type="button" id="save_data" class="btn btn-info btn-rounded m-10 w-150p"><i class="fa fa-save"></i> Create </button>
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
        	<!-- <div id="netting_group" class="row d-none">
        		<div class="col-md-12">
        							<div class="checkbox checkbox-danger checkbox-circle">
        		                        <input id="netting_ar" type="checkbox">
        		                        <label for="netting_ar"> Netting AR </label>
        		                    </div>
        						</div>
        					<div class="col-sm-4">
        	                     <div class="form-group m-b-10">
        				            <label for="ar_invoice" class="control-label">AR Invoice <span class="pull-right">:</span></label>
        				            <input type="text" class="form-control" id="ar_invoice" value="" readonly>
        				        </div>
        					</div>
        					<div class="col-sm-4">
        	                     <div class="form-group m-b-10">
        				            <label for="ar_debit" class="control-label">Amount Debit <span class="pull-right">:</span></label>
        				            <input type="text" class="form-control" id="ar_debit" value="0" readonly>
        				        </div>
        					</div>
        	</div> -->
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<table id="table_detail" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small w-full">
							<thead>
					        	<tr>
									<th class="text-center">No</th>
									<th class="text-center">Journal Name</th>
									<th class="text-center">Vendor</th>
									<th class="text-center">Currency</th>
									<th class="text-center">No Invoice</th>
									<th class="text-center">Tanggal Invoice</th>
									<th class="text-center">Amount</th>
									<th class="text-center">Bank Charge</th>
									<th class="text-center">BI Rate</th>
									<th class="text-center">Group Journal</th>
									<th class="text-center">AR Invoice</th>
									<th class="text-center">AR Amount</th>
									<th class="text-center">AR Invoice Description</th>
									
						        </tr>
					        </thead>
					    </table>
					</div>
				</div>
	        </div>
        </div>
        <!-- <div class="modal-footer">
          <button type="reset" class="btn btn-default"><i class="fa fa-trash-o"></i> Clear</button>
          <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal"><i class="fa fa-times-circle"></i> CANCEL</button>
          <button type="submit" class="btn btn-info waves-effect" id="btnSave"><i class="fa fa-save"></i> Save</button>
        </div> -->
    </div>
  </div>
</div>

<script>
  $(document).ready(function(){
	
	const url           = baseURL + 'payment-batch/api/load_data_pb';
	const url2          = baseURL + 'payment-batch/api/load_data_pb_detail';
	const BATCH_NAME    = '<?= $batch_name ?>';
	let bank_name       = $("#bank_name").val();
	let amount_group    = $("#amount_group").val();
	let amount          = $("#amount").val();
	let date_from       = $("#invoice_date_from").val();
	let date_to         = $("#invoice_date_to").val();
	let active_key      = '';
	let nama_vendor     = '';
	let glz_id          = 0;
	let is_groupz       = 1;
	let journal_name    = '';
	let bank_charge     = new Array();
	let gl_data         = new Array();
	let netting_check   = new Array();
	let netting_data    = new Array();
	let ar_netting_show = false;
	let dtl_clicked     = false;
	let run_again     = true;
	
	let ar_netting_data = {};


	Pace.track(function(){
	    $('#table_data').DataTable({
	      "serverSide"      : true,
	      "processing"      : true,
	      "ajax"            : {
									"url"  : url,
									"type" : "POST",
									"dataType": "json",
									"data"    : function ( d ) {
													d.amount       = amount;
													d.bank_name    = bank_name;
													d.amount_group = amount_group;
													d.date_from    = date_from;
													d.date_to      = date_to;
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
								{
									"data": "total_amount",
									"width":"120px",
									"class":"text-right",
									"render": function (data) {
										return formatNumber(data);
									}
	                            },
								{"data": "is_checklist", "width": "20px", "class": "text-center"  },
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
	      "bAutoWidth"      : true,
          "drawCallback": function ( settings ) {
          	addAmountTotal();
            let api         = this.api();
            let row_datas   = api.rows({ page: 'current' }).data();
           /* row_datas.each(function (data, i) {
				random_key = data.random_key;
				vendor     = data.nama_vendor;
				glx_id     = data.id;
				is_group   = data.is_group;
            	if(run_again){
            		console.log("data ke " + i + " " + vendor);
            		run_again = false;
            		storeDataDetail(vendor, random_key, glx_id, is_group);
            	}

            	$.each( result, function( key, value ) {
	            	 data_glNya.push(value.gl_id);
	            });
		      	gl_data[random_key] = data_glNya.join(",");
            });
*/
        		setTimeout(function(){
        			run_store();
        		}, 500);
          }
	    });
	});

	let table = $('#table_data').DataTable();

	function run_store(){

		table.rows().eq(0).each( function ( index ) {
			data       = table.row( index ).data();
			vendor     = data.nama_vendor;
			j          = index+1;
			random_key = data.random_key;
			vendor     = data.nama_vendor;
			glx_id     = data.id;
			is_group   = data.is_group;
			gl_datax   = data.gl_data;
			console.log( j + " " + vendor);
				// storeDataDetail(vendor, random_key);

	      	let data_glNya = [];
			$.each( gl_datax, function( key, value ) {
            	 data_glNya.push(value.gl_id);
            });
	      	gl_data[random_key] = data_glNya.join(",");
		});

		setTimeout(function(){
			console.log(gl_data);
		}, 1500);
	}

	function storeDataDetail(vendor, random_key){

		let date_from    = $("#invoice_date_from").val();
		let date_to      = $("#invoice_date_to").val();
		let amount       = $("#amount").val();
		let amount_group = $("#amount_group").val();

		$.ajax({
			url   : baseURL + 'payment-batch/api/load_data_pb_detail_gl_id/',
			type  : "POST",
			data  : {random_key: random_key,nama_vendor: vendor, bank_name: bank_name, glx_id: glx_id, is_group: is_group,  amount_group: amount_group,  amount: amount, date_from: date_from, date_to: date_to},
	      success : function(result){
	      	let data_glNya = [];
            $.each( result, function( key, value ) {
            	 data_glNya.push(value.gl_id);
            });
	      	gl_data[random_key] = data_glNya.join(",");
	      	run_again = 1;
			// console.log('jalan ' + vendor);
	      }
	    }); 
	}

	Pace.track(function(){
	    $('#table_detail').DataTable({
	      "serverSide"      : true,
	      "processing"      : true,
	      "ajax"            : {
									"url"  : url2,
									"type" : "POST",
									"dataType": "json",
									"data"    : function ( d ) {
													d.gl_id        = glz_id;
													d.is_group     = is_groupz;
													d.nama_vendor  = nama_vendor;
													d.no_journal   = journal_name;
													d.bank_name    = bank_name;
													d.date_from    = date_from;
													d.date_to      = date_to;
													d.amount_group = amount_group;
													d.amount       = amount;
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
								{"data": "currency", "width": "80px", "class": "p-t-5 p-b-5 text-center"  },
								{"data": "no_invoice", "width": "180px", "class": "p-t-5 p-b-5"  },
								{"data": "tgl_invoice", "width": "100px", "class": "p-t-5 p-b-5 text-center"  },
								{"data": "amount", "width": "100px", "class": "p-t-5 p-b-5 text-right"  },
								{"data": "bank_charge", "width": "80px", "class": "p-t-5 p-b-5 text-center"  },
								{"data": "bi_rate", "width": "80px", "class": "p-t-5 p-b-5 text-right"  },
								{"data": "is_group", "width": "80px", "class": "p-t-5 p-b-5 text-center"  },
								{"data": "ar_invoice", "width": "150px", "class": "p-t-5 p-b-5 text-center"  },
								{"data": "ar_debit", "width": "100px", "class": "p-t-5 p-b-5 text-center"  },
								{"data": "ar_description", "width": "200px", "class": "p-t-5 p-b-5"  }
								
				    		],
          "drawCallback": function ( settings ) {

				let api       = this.api();
				let row_datas = api.rows({ page: 'current' }).data();

				if(row_datas.length > 0 && dtl_clicked == true){

					customLoading('hide');

	                row_datas.each(function (data, i) {

	                	if(ar_netting_data[data.key_inv]){
							this_ar_netting = ar_netting_data[data.key_inv];
							this_inv        = this_ar_netting.ar_invoice;
							this_id         = this_ar_netting.id;

	                		$("#ar_invoice-"+this_id).val(this_inv);
	                		$("#ar_invoice-"+this_id).trigger('change');
	                	}

	                });
					$("#modal-view").modal('show');
				}
          },
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

    $('.mydatepicker').datepicker({
		format: 'dd-mm-yyyy',
		todayHighlight:'TRUE',
		autoclose: true,
    });

    $('#btnView').on( 'click', function () {
		amount       = $("#amount").val();
		amount_group = $("#amount_group").val();
		bank_name    = $("#bank_name").val();
		date_from    = $("#invoice_date_from").val();
		date_to      = $("#invoice_date_to").val();
		table.draw();
    });
    $('#amount_group').on( 'change', function () {
    	if($(this).val() == 1){
    		$("#amount").attr('disabled', true);
    	}else{
    		$("#amount").attr('disabled', false);
    	}
    });


    $('#table_data tbody').on('change', 'tr td input.checkbox-data', function () {
    	getAmountTotal();
	});
    $('#table_detail tbody').on('change', 'tr td input.bank_charge', function () {

		let data  = table_detail.row( $(this).parents('tr') ).data();
		let gl_id = data.id;

	    if (this.checked) {
	    	val_bank_charge = 'Y';
	    }else{
	    	val_bank_charge = 'N';
	    }

	    $.ajax({
	      url   : baseURL + 'payment-batch/api/update_bank_charges',
	      type  : "POST",
	      data  : {bank_charges :  val_bank_charge, id :  gl_id},
	      dataType: "json",
	      success : function(result){
	      	console.log('success');
	      }
	    }); 
	});

    $('#table_detail tbody').on('change', 'tr td input.bi_rate', function () {

		let data  = table_detail.row( $(this).parents('tr') ).data();
		let gl_id = data.id;

	    if (this.checked) {
	    	val_bi_rate = 'Y';
	    }else{
	    	val_bi_rate = 'N';
	    }

		val_bi_rate   =  parseInt($(this).val().replace(/\./g, ''));

	    $.ajax({
	      url   : baseURL + 'payment-batch/api/update_bi_rate',
	      type  : "POST",
	      data  : {bi_rate : val_bi_rate, id :  gl_id},
	      dataType: "json",
	      success : function(result){
	      	console.log('success');
	      }
	    }); 
	});

    $('#table_detail tbody').on('change', 'tr td select.ar_invoice', function () {

		let data           = table_detail.row( $(this).parents('tr') ).data();
		let gl_id          = data.id;
		let key_inv        = data.key_inv;
		let ap_invoice     = data.no_invoice;
		let ar_invoice     = $(this).val();
		let ar_debit       = $(this).find(':selected').attr('data-debit');
		let ar_description = $(this).find(':selected').attr('data-description');

		$("#ar_debit-"+gl_id).html(ar_debit);
		$("#ar_description-"+gl_id).html(ar_description);
		
		val_ar_debit   =  parseInt(ar_debit.replace(/\./g, ''));
		val_ar_netting = (ar_invoice != '0') ? 'Y' : 'N';

		if(val_ar_netting == 'Y'){
			data_netting = {ar_netting :  val_ar_netting, ap_invoice : ap_invoice, ar_invoice : ar_invoice, ar_debit : val_ar_debit, ar_description : ar_description, id :  gl_id};
			ar_netting_data[key_inv] = data_netting;

		}else{
			delete ar_netting_data[key_inv];
		}

	    /*$.ajax({
	      url   : baseURL + 'payment-batch/api/update_ar_netting',
	      type  : "POST",
	      data  : {ar_netting :  val_ar_netting, ap_invoice : ap_invoice, ar_invoice : ar_invoice, ar_debit : val_ar_debit, ar_description : ar_description, id :  gl_id},
	      dataType: "json",
	      success : function(result){
	      	console.log('success');
	      }
	    });*/
	});

   /* $('#netting_ar').on( 'change', function () {

		ar_debit   = $("#ar_debit").val();
		ar_invoice = $("#ar_invoice").val();

	    if (this.checked) {
			netting_check[active_key]  = true;
			netting_data[active_key] = {ar_debit: parseInt(ar_debit.replace(/\./g, '')), ar_invoice: ar_invoice}
	    }else{
			netting_check[active_key] = false;
	    }
	});*/
    $('#table_detail tbody').on('change', 'tr td input.is_group', function () {

		let data  = table_detail.row( $(this).parents('tr') ).data();
		let gl_id = data.id;

	    if (this.checked) {
	    	val_is_group = '1';
	    }else{
	    	val_is_group = '0';
	    }

	    $.ajax({
	      url   : baseURL + 'payment-batch/api/update_group_journal',
	      type  : "POST",
	      data  : {is_group :  val_is_group, id :  gl_id},
	      dataType: "json",
	      success : function(result){
	      	table.draw();
	      	table_detail.draw();
	      }
	    }); 
	});

	$('#table_data').on('click', 'a.action-view', function () {
		this_row      = $(this).parents('tr');
		let data      = table.row( this_row ).data();
		let index_row = this_row.index() + 1;
		vendor        = $(this).data('id');
		glz_id        = data.id;
		is_groupz     = data.is_group
		active_key    = data.random_key;

		if( data.ar_invoice != ''){
			table_detail.columns( [10, 11, 12] ).visible( true );
		}else{
			table_detail.columns( [10, 11, 12] ).visible( false );
		}

		console.log(data.ar_invoice);

		journal_name = $("#journal_name-"+index_row).html();;
		nama_vendor  = vendor;

		/*if(netting_check[active_key] == true){
			$("#netting_ar").prop('checked',true);
		}else{
			$("#netting_ar").prop('checked',false);
		}*/
		dtl_clicked = true;
		customLoading('show');

		table_detail.columns.adjust().draw();
    });

	$('#modal-view').on('hidden.bs.modal', function () {
		nama_vendor = 'POQULKJHQWERTY';
		dtl_clicked = false;
		table_detail.draw();
	});


	$("#save_data").on('click', function () {

		let total_amount   = $("#total_amount").html();

		if(total_amount == "0"){
    		customNotif('Warning', "Please check minimum 1", 'warning');
		}
		else{

			let data_lines = [];

			table.rows().eq(0).each( function ( index ) {
				j = index+1;

		    	if ($('#checkbox-'+j).is(':checked')) {
					let netting_ar = [];
					data       = table.row( index ).data();
					gl_id      = data.id;
					random_key = data.random_key;
					no_journal = $("#journal_name-"+j).html();

					/*if(netting_check[random_key] != undefined && netting_data[random_key] != undefined){
						if(netting_check[random_key] == true){
							netting_ar = netting_data[random_key];
						}
					}*/
	    			data_lines.push({'gl_id' : gl_id, 'no_journal' : no_journal, 'batch_name' : BATCH_NAME, 'data_gl':gl_data[random_key]});
	    		}
			});

			console.log(ar_netting_data);

		    $.ajax({
		        url   : baseURL + 'payment-batch/api/save_batch_payment',
		        type  : "POST",
		        data  : {data_lines : data_lines, ar_netting_data : ar_netting_data},
		        dataType: "json",
		        beforeSend  : function(){
                          customLoading('show');
                        },
		        success : function(result){
		        	if(result.status == true){
		        		customNotif('Success', "Payment Batch Created", 'success');
		        		setTimeout(function(){
		        			customLoading('hide');
		        			$(location).attr('href', baseURL + 'payment-batch');
		        		}, 500);
		        	}
		        	else{
		        		customLoading('hide');
		        		customNotif('Error', result.messages, 'error');
		        	}
		        }
		    });
		}
	});

	function getAmountTotal(){
		total_amount = 0;
		startUrut = <?= $no_journal ?>;
		table.rows().eq(0).each( function ( index ) {
			index_Num   = index+1;
			data        = table.row(index).data();
			batch_query = data.batch_name;
	    	$("#journal_name-"+index_Num).html('');

		    if ($('#checkbox-'+index_Num).is(':checked')) {
				urut = String(startUrut).padStart(2, '0');
				journalSpan = urut+'/'+batch_query;
		    	$("#journal_name-"+index_Num).html(journalSpan);
            	amount = parseInt(data.total_amount);
            	total_amount += amount;
            	startUrut++;
            } 
		});
		$('#total_amount').html(formatNumber(total_amount));
	}

    function addAmountTotal(){
		total_data = table.data().count();
		if(total_data > 0){
			rows = table.rows( {page:'current'} ).nodes();
			$(rows).eq( total_data-1 ).after(
				'<tr class="font-weight-bold"><td class="text-right" colspan="5"><strong>Total</strong></td>'+
				'<td colspan="3"><strong id="total_amount">0</strong></td></tr>'
			);
		}
    }

  });
</script>