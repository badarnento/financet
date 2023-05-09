<div class="row">



	<div class="white-box boxshadow">     

		<div class="row">



			<form id="form-import" action="<?= base_url()?>uploadclearingbank/save_import_upload_clearing_bank" method="post" enctype="multipart/form-data"> 


				<div class="col-sm-3">

					<div class="form-group">

						<label class="form-control-label">Import File</label>
						<input class="custom-input-file" type="file" name="fileToUpload" id="fileToUpload" required="required">

					</div>

				</div>


				<div class="col-sm-3">

					<div class="form-group">

						<label class="form-control-label">Bank</label>
						<select class="form-control" id="ddlBank" name="ddlBank">
							<option value="">-- Choose Bank -- </option> 
						</select> 

					</div>

				</div>

				<div class="col-sm-3">

					<div class="form-group">
						<label class="form-control-label">&nbsp;</label>
						<input type="submit" class="btn btn-info btn-rounded btn-block" id="import" value="Import" name="import">

					</div>

				</div>



			</form>   



		</div>



	</div>



	<div class="white-box boxshadow">     



		<div class="row">



			<div class="col-sm-3">

				<div class="form-group">

					<label>Post Date From</label>

					<div class="input-group">

						<input type="text" class="form-control mydatepicker" id="ddlPostDateFrom" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 4)?>">

						<span class="input-group-addon"><i class="icon-calender"></i></span>

					</div>

				</div>

			</div>



			<div class="col-sm-3">

				<div class="form-group">

					<label>Post Date To</label>

					<div class="input-group">

						<input type="text" class="form-control mydatepicker" id="ddlPostDateTo" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 4)?>">

						<span class="input-group-addon"><i class="icon-calender"></i></span>

					</div>

				</div>

			</div>

			<div class="col-sm-3">

				<div class="form-group">

					<label class="form-control-label">Bank</label>
					<select class="form-control" id="ddlBankTransaction" name="ddlBankTransaction">
						<option value="">-- Choose Bank -- </option> 
					</select>
				</div>

			</div>



			<div class="col-sm-3">

				<div class="form-group">

					<label>&nbsp;</label>
					<button id="btnView" class="btn btn-default btn-rounded custom-input-width btn-block" type="button"><i class="fa fa-bars"></i> <span>VIEW</span></button>
				</div>


			</div> 



		</div>



	</div>



	<div class="row" id="tblData">



		<div class="col-md-12">



			<div class="white-box">



				<table id="table_data" class="table table-responsive w-full table-striped table-hover table-bordered display cell-border stripe hover dataTable small">



					<thead>



						<tr>

							<th class="text-center">No.</th>

							<th class="text-center">Transaction Date</th>

							<th class="text-center">No Rekening</th>

							<th class="text-center">Currency</th>

							<th class="text-center">Debit</th>

							<th class="text-center">Credit</th>

							<th class="text-center">Balance</th>

							<th class="text-center">Bank</th>

							<th class="text-center">Description</th>

							<th class="text-center">Status</th>



						</tr>



					</thead>



				</table>



			</div>



		</div>



	</div>



	<div class="row" id="tblDatadownload1">

		<div class="col-md-12">

			<div class="white-box boxshadow">     

				<div class="row">

					<div class="form-group">

						<div class="col-md-offset-5 col-md-2 m-b-10">

							<button id="btnDownload" class="btn btn-success btn-rounded btn-block" type="button" ><i class="fa  fa-file-excel-o"></i> <span>Download</span></button>

						</div>

					</div>

				</div>

			</div>

		</div>

	</div>





	<div id="whitebox2" class="white-box boxshadow">     



		<div class="row">



			<div class="col-sm-3">

				<div class="form-group">

					<label class="form-control-label" for="lblBatchName">Batch Name</label>

					<select class="form-control" id="ddlBatchName" name="ddlBatchName" data-toggle="validator" data-error="Please choose one" required>

						<option value="">-- Choose Batch Name --</option> 

					</select>

					<div class="help-block with-errors"></div>

				</div>

			</div>



			<div class="col-sm-3">



				<div class="form-group">



					<label>&nbsp;</label>



					<button id="btnView2" class="btn btn-default btn-rounded custom-input-width btn-block" type="button"><i class="fa fa-bars"></i> <span>VIEW</span></button>



				</div>



			</div> 



		</div>



	</div>



	<div class="row" id="tblData2">



		<div class="col-md-12">



			<div class="white-box">



				<hr>



				<h4 id="table_detail_title2">Batch Payment</h4>



				<table id="table_data2" class="table table-responsive w-full table-striped table-hover table-bordered display cell-border stripe hover dataTable small">



					<thead>



						<tr>



							<th class="text-center">No.</th>

							<th class="text-center">Batch Date</th>

							<th class="text-center">Batch Name</th>

							<!-- <th class="text-center">Batch Number</th>

								<th class="text-center">Journal Payment Number</th> -->

								<th class="text-center">Tanggal Invoice</th>

								<th class="text-center">No Journal</th>

								<th class="text-center">Nama Vendor</th>

								<th class="text-center">No Invoice</th>

								<th class="text-center">No Kontrak</th>

								<th class="text-center">Description</th>

								<th class="text-center">DPP</th>

								<th class="text-center">No FPJP</th>

								<th class="text-center">Amount to Payment</th>

								<th class="text-center">Nama Rekening</th>

								<th class="text-center">Nama Bank</th>

								<th class="text-center">Acct Number</th>

							<!-- <th class="text-center">TOP</th>

								<th class="text-center">Due Date</th> -->

								<th class="text-center">Status</th>
								<th class="text-center">Reverse</th>

								<!-- <th class="text-center">Bank Charge</th> -->



							</tr>



						</thead>



					</table>



				</div>



			</div>



		</div>



		<div class="row" id="tblDatadownload2">

			<div class="white-box boxshadow">     

				<div class="row">

					<div class="col-md-12">

						<div class="form-group">

							<div class="col-md-offset-5 col-md-2 m-b-10">

								<button id="btnDownload2" class="btn btn-success btn-rounded btn-block" type="button" ><i class="fa  fa-file-excel-o"></i> <span>Download</span></button>

							</div>

						</div>

					</div>

				</div>

			</div>

		</div>





		<script>



			$(document).ready(function(){

				getBatchName();
				getBank();
				getBankTransaction();

				let post_date_from = $("#ddlPostDateFrom").val();

				let post_date_to = $("#ddlPostDateTo").val();

				let batch_name = $("#ddlBatchName").val();

				let bank = $("#ddlBankTransaction").val();



				$("#ddlBatchName").on("change", function(){
					batch_name = $("#ddlBatchName").val();
				});


				$("#ddlBankTransaction").on("change", function(){
					bank = $("#ddlBankTransaction").val();
				});



				$('#tblData').hide();

				$('#tblDatadownload1').hide();



			// $('#whitebox2').hide();

			$('#tblData2').hide();

			$('#tblDatadownload2').hide();



			$('.mydatepicker').datepicker({

				format: 'dd/mm/yyyy',

				todayHighlight:'TRUE',

				autoclose: true,

			});



			let url  = baseURL + 'uploadclearingbank/load_data_upload_clearing_bank';



			let ajaxData = {



				"url"  : url,



				"type" : "POST",



				"data"    : function ( d ) {

					d.post_date_from = post_date_from;

					d.post_date_to   = post_date_to;

					d.bank   = bank;

				}



			}



			let jsonData = [

			{ "data": "no", "width": "10px", "class": "text-center" },

			{ "data": "transaction_date", "width": "150px", "class": "text-left" },

			{ "data": "no_rekening", "width": "100px", "class": "text-left" },

			{ "data": "kurs", "width": "50px", "class": "text-left" },

			{ "data": "debit", "width": "100px", "class": "text-left" },

			{ "data": "credit", "width": "100px", "class": "text-left" },

			{ "data": "balance", "width": "100px", "class": "text-left" },

			{ "data": "bank", "width": "75px", "class": "text-left" },

			{ "data": "description", "width": "500px", "class": "text-left" },

			{ "data": "status", "width": "100px", "class": "text-left" }



			];

			data_table(ajaxData,jsonData);

			table = $('#table_data').DataTable();

			let url2  = baseURL + 'uploadclearingbank/load_data_batch_payment';

			Pace.track(function(){
				$('#table_data2').DataTable({
					"serverSide"      : true,
					"processing"      : true,
					"ajax"            : {
						"url"  : url2,
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
					{ "data": "no", "width": "10px", "class": "text-center" },
					{ "data": "batch_date", "width": "150px", "class": "text-left" },
					{ "data": "batch_name", "width": "150px", "class": "text-left" },
					{ "data": "tanggal_invoice", "width": "100px", "class": "text-left" },
					{ "data": "no_journal", "width": "150px", "class": "text-left" },
					{ "data": "nama_vendor", "width": "150px", "class": "text-left" },
					{ "data": "no_invoice", "width": "100px", "class": "text-left" },
					{ "data": "no_kontrak", "width": "100px", "class": "text-left" },
					{ "data": "description", "width": "250px", "class": "text-left" },
					{ "data": "dpp", "width": "100px", "class": "text-left" },
					{ "data": "no_fpjp", "width": "100px", "class": "text-left" },
					{ "data": "amount_to_payment", "width": "100px", "class": "text-left" },
					{ "data": "nama_rekening", "width": "150px", "class": "text-left" },
					{ "data": "nama_bank", "width": "100px", "class": "text-left" },
					{ "data": "acct_number", "width": "100px", "class": "text-left" },
					{ "data": "status", "class":"text-letf", "width": "100px" },
					{"data": "journal_reverse", "width": "100px", "class": "text-center disable-hover"  }
					],
					"rowsGroup"       : [16],
					"pageLength"      : 100,
					"ordering"        : false,
					"scrollY"         : 480,
					"scrollX"         : true,
					"scrollCollapse"  : true,
					"autoWidth"       : true,
					"bAutoWidth"      : true
				});
			});

			table2 = $('#table_data2').DataTable();


			$('#btnView').on( 'click', function () {



				post_date_from = $("#ddlPostDateFrom").val();

				post_date_to = $("#ddlPostDateTo").val();

				bank = $("#ddlBankTransaction").val();


				table.draw();



				$('#tblData').slideDown(700);

				$('#tblData').css( 'display', 'block' );

				table.columns.adjust().draw();

				$('#tblDatadownload1').slideDown(700);

			});



			$('#btnView2').on( 'click', function () {

				// table2.draw();

				$('#tblData2').slideDown(700);

				$('#tblData2').css( 'display', 'block' );

				table2.columns.adjust().draw();

				$('#tblDatadownload2').slideDown(700);

			});


			$('#table_data2').on('click', 'a.action-reverse', function () {

				no_journal = $(this).data('id');

				$.ajax({
					url       : baseURL + 'uploadclearingbank/reverse_journal',
					type      : 'post',
					data      : { no_journal: no_journal},
					beforeSend  : function(){
						customLoading('show');
					},
					dataType : 'json',
					success : function(result){
						customLoading('hide');
						if (result.status == true) {
							table2.ajax.reload(null, false);
							customNotif('Success', result.messages, 'success');
						} else {
							customNotif('Failed', result.messages, 'error');
						}
					}
				});

			});



			$("#btnDownload").on("click", function(){



				let vpost_date_from = '';

				let vpost_date_to = '';

				let vbank = '';



				let url   ="<?php echo site_url(); ?>uploadclearingbank/download_data_upload_clearing_bank";



				vpost_date_from = $("#ddlPostDateFrom").val();

				vpost_date_to = $("#ddlPostDateTo").val();

				vbank = $("#ddlBankTransaction").val();



				window.open(url+'?date_from='+vpost_date_from +"&date_to="+ vpost_date_to +"&bank="+ vbank, '_blank');



				window.focus();



			});





			$("#btnDownload2").on("click", function(){



				let vbatch_name = '';



				let url   ="<?php echo site_url(); ?>uploadclearingbank/download_data_batch_payment";



				vbatch_name = $("#ddlBatchName").val();



				window.open(url+'?batch_name='+ vbatch_name, '_blank');



				window.focus();



			});


			function getBatchName()

			{

				$.ajax({

					url   : baseURL + 'uploadclearingbank/load_ddl_batch_name',

					type  : "POST",

					dataType: "html",

					success : function(result){

						$("#ddlBatchName").html("");       

						$("#ddlBatchName").html(result);          

					}

				});     

			}

			function getBank()

			{

				$.ajax({

					url   : baseURL + 'master/load_ddl_bank_la',

					type  : "POST",

					dataType: "html",

					success : function(result){

						$("#ddlBank").html("");       

						$("#ddlBank").html(result);          

					}

				});     

			}

			function getBankTransaction()

			{

				$.ajax({

					url   : baseURL + 'uploadclearingbank/load_ddl_bank_transaction',

					type  : "POST",

					dataType: "html",

					success : function(result){

						$("#ddlBankTransaction").html("");       

						$("#ddlBankTransaction").html(result);          

					}

				});     

			}

			$('#form-import').validator().on('submit', function(e) {
				if (!e.isDefaultPrevented()){
					$.ajax({
						url     : baseURL + 'uploadclearingbank/save_import_upload_clearing_bank',
						type    : "POST",
						data:  new FormData(this),
						contentType: false,
						cache: false,
						processData:false,
						dataType:'json',
						beforeSend  : function()
						{
							customLoading('show');
						},
						success : function(result){
							console.log(result);
							
							if (result.status == true) {
								$("#fileToUpload").val("");
								table.ajax.reload(null, false);
								customLoading('hide');
								customNotif('Success', result.messages, 'success');
							} else {
								$("#fileToUpload").val("");
								customNotif('Failed', result.messages, 'error');
								customLoading('hide');
							}
						}
					});
				}
				e.preventDefault();
			});



			<?php if($this->session->flashdata('messages') != ""): ?>

				customNotif('Success', '<?= $this->session->flashdata('messages') ?>', 'success');

				table.draw();

				$('#tblData').slideDown(700);

				$('#tblData').css( 'display', 'block' );

				table.columns.adjust().draw();

				$('#tblDatadownload1').slideDown(700);



				table2.draw();

				$('#whitebox2').slideDown(700);

				$('#tblData2').slideDown(700);

				$('#tblData2').css( 'display', 'block' );

				table2.columns.adjust().draw();

				$('#tblDatadownload2').slideDown(700);

				<?php elseif($this->session->flashdata('error') != ""): ?>

					customNotif('Error', '<?= $this->session->flashdata('error') ?>', 'error');

					<?php elseif($this->session->flashdata('warning') != ""): ?>

						customNotif('Warning', '<?= $this->session->flashdata('warning') ?>', 'warning');

					<?php endif; ?>



				});


			</script>