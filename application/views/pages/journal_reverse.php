<div class="row">

	<div class="white-box boxshadow">     

		<div class="row">

			<!-- <div class="col-sm-2">
				<div class="form-group">
					<label>Accounting Date From</label>
					<div class="input-group">
						<input type="text" class="form-control mydatepicker" id="ddlGlDateFrom" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 4)?>">
						<span class="input-group-addon"><i class="icon-calender"></i></span>
					</div>
				</div>
			</div>

			<div class="col-sm-2">
				<div class="form-group">
					<label>Accounting Date To</label>
					<div class="input-group">
						<input type="text" class="form-control mydatepicker" id="ddlGlDateTo" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 4)?>">
						<span class="input-group-addon"><i class="icon-calender"></i></span>
					</div>
				</div>
			</div> -->

			<div class="col-sm-4">

				<div class="form-group">

					<label class="form-control-label">Journal Name</label>
					<select class="form-control select2" id="ddlJournalName" name="ddlJournalName" ">
						<option value="">-- Pilih -- </option> 
					</select> 

				</div>

			</div>

			<div class="col-sm-2">

				<div class="form-group">

					<label>&nbsp;</label>

					<button id="btnView" class="btn btn-default btn-rounded custom-input-width btn-block" type="button"><i class="fa fa-bars"></i> <span>View</span></button>

				</div>

			</div>

			<div class="col-sm-2">

				<div class="form-group">

					<label>&nbsp;</label>

					<button id="btnReverse" class="btn btn-danger btn-rounded custom-input-width btn-block" type="button"><i class="fa fa-refresh"></i> <span>Reverse</span></button>

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
							<th class="text-center">Accounting Date</th>
							<th class="text-center">Batch Name</th>
							<th class="text-center">Journal Name</th>
							<th class="text-center">Saldo Awal</th>
							<th class="text-center">Debit</th>
							<th class="text-center">Credit</th>
							<th class="text-center">Nature</th>
							<th class="text-center">Account Description</th>
							<th class="text-center">Journal Description</th>
							<th class="text-center">Reference 1</th>
							<th class="text-center">Reference 2</th>
							<th class="text-center">Reference 3</th>

						</tr>

					</thead>

				</table>

			</div>

		</div>

	</div>

</div>


<script>

	$(document).ready(function(){

		getJournal();

		// let gl_date_from = $("#ddlGlDateFrom").val();
		// let gl_date_to = $("#ddlGlDateTo").val();
		let journalname = $("#ddlJournalName").val();

		$('#tblData').hide();


		$('.mydatepicker').datepicker({
			format: 'dd/mm/yyyy',
			todayHighlight:'TRUE',
			autoclose: true,
		});

		$("#ddlJournalName").on("change", function(){
			journalname = $("#ddlJournalName").val();
			console.log(journalname);
		});

		$("#ddlJournalName").select2();

		let url  = baseURL + 'Journalreverse/load_data_journal_reverse';

			let ajaxData = {

				"url"  : url,

				"type" : "POST",

				"data"    : function ( d ) {

					// d.gl_date_from = gl_date_from;
					// d.gl_date_to   = gl_date_to;
					d.journalname  = journalname;

				}

			}

			let jsonData = [
			{ "data": "no", "width": "10px", "class": "text-center" },
			{ "data": "gl_date", "width": "150px", "class": "text-left" },
			{ "data": "batch_name", "width": "200px", "class": "text-left" },
			{ "data": "journal_name", "width": "200px", "class": "text-left" },
			{ "data": "saldo_awal", "width": "150px", "class": "text-left" },
			{ "data": "debit", "width": "150px", "class": "text-left" },
			{ "data": "credit", "width": "150px", "class": "text-left" },
			{ "data": "nature", "width": "100px", "class": "text-left" },
			{ "data": "account_description", "width": "250px", "class": "text-left" },
			{ "data": "journal_description", "width": "400px", "class": "text-left" },
			{ "data": "reference_1", "width": "200px", "class": "text-left" },
			{ "data": "reference_2", "width": "200px", "class": "text-left" },
			{ "data": "reference_3", "width": "200px", "class": "text-left" }

			];

			data_table(ajaxData,jsonData);

			table = $('#table_data').DataTable();


			$('#btnView').on( 'click', function () {

				// gl_date_from = $("#ddlGlDateFrom").val();
				// gl_date_to = $("#ddlGlDateTo").val();
				journalname = $("#ddlJournalName").val();

				table.draw();

				$('#tblData').slideDown(700);
				$('#tblData').css( 'display', 'block' );
				table.columns.adjust().draw();
			});

			$('#btnReverse').on( 'click', function () {

				let vjournalname = $("#ddlJournalName").val();
				console.log(vjournalname);

				if (vjournalname) 
				{
					$('.modal-body').html('Are you sure want to reverse this data ? ' );
					$("#modal-delete").modal('show');
				}
				else
				{
					customNotif('Failed', 'Please Choose Journal !!', 'error');
				}
				
			});

			$('#button-delete').on( 'click', function () 
			{

				let vjournalname = $("#ddlJournalName").val();
				console.log(vjournalname);

					$.ajax({
						url   : baseURL + 'journalreverse/reverse_journal/',
						type  : "POST",
						beforeSend  : function(){
							customLoading('show');
						},
						data  : {journalname : vjournalname},
						dataType: "json",
						success : function(result){
							customLoading('hide');
							$("#modal-delete").modal('hide');
							console.log(result);
							console.log(result.status);
						if (result.status == true) {
							table.ajax.reload(null, false);
							customNotif('Success', result.messages, 'success');
						}
						 else 
						 {
							customNotif('Failed', result.messages, 'error');
						}
					}
				});
				
			});

			function getJournal()

			{
				$.ajax({

					url   : baseURL + 'Journalreverse/load_ddl_journal',
					type  : "POST",
					dataType: "html",
					success : function(result){
						$("#ddlJournalName").html("");       
						$("#ddlJournalName").html(result);          
					}

				});     

			}

		});

	</script>