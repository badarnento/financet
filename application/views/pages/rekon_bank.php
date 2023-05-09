<div class="row">


	<div class="white-box boxshadow">     

		<div class="row">

			<div class="col-sm-3">
				<div class="form-group">
					<label>Accounting Date From</label>
					<div class="input-group">
						<input type="text" class="form-control mydatepicker" id="ddlGlDateFrom" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 4)?>">
						<span class="input-group-addon"><i class="icon-calender"></i></span>
					</div>
				</div>
			</div>

			<div class="col-sm-3">
				<div class="form-group">
					<label>Accounting Date To</label>
					<div class="input-group">
						<input type="text" class="form-control mydatepicker" id="ddlGlDateTo" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 4)?>">
						<span class="input-group-addon"><i class="icon-calender"></i></span>
					</div>
				</div>
			</div>

			<div class="col-md-2">
				<div class="form-group">
					<label>Nature</label>
					<select class="form-control" id="nature">
						<option value="0">--Pilih--</option>
						<?php foreach($get_nature as $value): ?>
							<option value='<?= $value['ID_MASTER_COA'] ?>' data-name='<?= $value['NATURE'] ?>' ><?= $value['NATURE']."-".$value['DESCRIPTION'] ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>


			<div class="col-sm-2">

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
							<th class="text-center">Accounting Date</th>
							<th class="text-center">Batch Name</th>
							<th class="text-center">Journal Name</th>
							<th class="text-center">Currency</th>
							<th class="text-center">Debit</th>
							<th class="text-center">Credit</th>
							<th class="text-center">Nature</th>
							<th class="text-center">Bank</th>
							<th class="text-center">Journal Description</th>
							<th class="text-center">Batch Description</th>
							<th class="text-center">Journal Reference</th>
							<th class="text-center">Line Description</th>

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

	<script>

		$(document).ready(function(){

			// getJenisJournal();
			$("#nature").select2();

			let gl_date_from = $("#ddlGlDateFrom").val();
			let gl_date_to = $("#ddlGlDateTo").val();
			let nature   = $("#nature").find(':selected').attr('data-name');

			$('#tblData').hide();
			$('#tblDatadownload1').hide();


			$('.mydatepicker').datepicker({
				format: 'dd/mm/yyyy',
				todayHighlight:'TRUE',
				autoclose: true,
			});

			let url  = baseURL + 'rekonbank/load_data_rekon_bank';

			let ajaxData = {

				"url"  : url,

				"type" : "POST",

				"data"    : function ( d ) {

					d.gl_date_from = gl_date_from;
					d.gl_date_to   = gl_date_to;
					d.nature 	   = nature;

				}

			}

			let jsonData = [
			{ "data": "no", "width": "10px", "class": "text-center" },
			{ "data": "gl_date", "width": "150px", "class": "text-left" },
			{ "data": "batch_name", "width": "200px", "class": "text-left" },
			{ "data": "journal_name", "width": "200px", "class": "text-left" },
			{ "data": "currency", "width": "150px", "class": "text-left" },
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

				gl_date_from = $("#ddlGlDateFrom").val();
				gl_date_to = $("#ddlGlDateTo").val();
				nature   = $("#nature").find(':selected').attr('data-name');

				table.draw();

				$('#tblData').slideDown(700);
				$('#tblData').css( 'display', 'block' );
				table.columns.adjust().draw();
				$('#tblDatadownload1').slideDown(700);
			});


			$("#btnDownload").on("click", function(){

				let vgl_date_from = '';
				let vgl_date_to = '';
				let vnature = '';

				let url   ="<?php echo site_url(); ?>rekonbank/download_data_rekon_bank";

				vgl_date_from = $("#ddlGlDateFrom").val();
				vgl_date_to = $("#ddlGlDateTo").val();
				vnature = $("#nature").find(':selected').attr('data-name');

				window.open(url+'?date_from='+vgl_date_from +"&date_to="+ vgl_date_to +"&nature="+vnature, '_blank');

				window.focus();

			});

			<?php if($this->session->flashdata('messages') != ""): ?>
				customNotif('Success', '<?= $this->session->flashdata('messages') ?>', 'success');
				table.draw();
				$('#tblData').slideDown(700);
				$('#tblData').css( 'display', 'block' );
				table.columns.adjust().draw();
				$('#tblDatadownload1').slideDown(700);
				<?php elseif($this->session->flashdata('error') != ""): ?>
					customNotif('Error', '<?= $this->session->flashdata('error') ?>', 'error');
					<?php elseif($this->session->flashdata('warning') != ""): ?>
						customNotif('Warning', '<?= $this->session->flashdata('warning') ?>', 'warning');
					<?php endif; ?>

				});

			</script>