<div class="row">

	<div class="white-box boxshadow">     
		<div class="row">

			<form  id="form-import" action="<?=site_url()?>uploadjournal/save_import_upload_journal" class="form-horizontal" method="post"  enctype="multipart/form-data">  

				<label class="form-control-label">Import File</label>
				<div class="form-group">
					<div class="col-sm-3 m-b-10">
						<input class="custom-input-file" type="file" name="file" accept=".xls,.xlsx" id="fileToUpload" required="required">
					</div>
					<div class="col-md-2 col-sm-12 m-b-10">
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

					<label class="form-control-label">Jenis Journal</label>
					<select class="form-control" id="ddlJenisJournal" name="ddlJenisJournal" style="max-width: 70%">
						<option value="">-- Pilih -- </option> 
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

			getJenisJournal();

			let gl_date_from = $("#ddlGlDateFrom").val();
			let gl_date_to = $("#ddlGlDateTo").val();
			let nature   = $("#nature").find(':selected').attr('data-name');
			let journaltype = $("#ddlJenisJournal").val();

			$('#tblData').hide();
			$('#tblDatadownload1').hide();


			$('.mydatepicker').datepicker({
				format: 'dd/mm/yyyy',
				todayHighlight:'TRUE',
				autoclose: true,
			});

			$("#ddlJenisJournal").on("change", function(){
				journaltype = $("#ddlJenisJournal").val();
			});

			let url  = baseURL + 'uploadjournal/load_data_upload_journal';

			let ajaxData = {

				"url"  : url,

				"type" : "POST",

				"data"    : function ( d ) {

					d.gl_date_from = gl_date_from;
					d.gl_date_to   = gl_date_to;
					d.nature 	   = nature;
					d.journaltype  = journaltype;

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

				gl_date_from = $("#ddlGlDateFrom").val();
				gl_date_to = $("#ddlGlDateTo").val();
				nature   = $("#nature").find(':selected').attr('data-name');
				journaltype = $("#ddlJenisJournal").val();

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
				let vjournaltype = '';

				let url   ="<?php echo site_url(); ?>uploadjournal/download_data_upload_journal";

				vgl_date_from = $("#ddlGlDateFrom").val();
				vgl_date_to = $("#ddlGlDateTo").val();
				vnature = $("#nature").find(':selected').attr('data-name');
				vjournaltype = $("#ddlJenisJournal").val();

				window.open(url+'?date_from='+vgl_date_from +"&date_to="+ vgl_date_to +"&nature="+vnature  +"&journaltype="+vjournaltype, '_blank');

				window.focus();

			});

			function getJenisJournal()

			{

				$.ajax({

					url   : baseURL + 'uploadjournal/load_ddl_jenis_journal',
					type  : "POST",
					dataType: "html",
					success : function(result){
						$("#ddlJenisJournal").html("");       
						$("#ddlJenisJournal").html(result);          
					}

				});     

			}

			$('#form-import').validator().on('submit', function(e) {
			if (!e.isDefaultPrevented()){
				$.ajax({
					url     : baseURL + 'uploadjournal/save_import_upload_journal',
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
				          customLoading('hide');
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
				<?php elseif($this->session->flashdata('error') != ""): ?>
					customNotif('Error', '<?= $this->session->flashdata('error') ?>', 'error');
					<?php elseif($this->session->flashdata('warning') != ""): ?>
						customNotif('Warning', '<?= $this->session->flashdata('warning') ?>', 'warning');
					<?php endif; ?>

				});

			</script>