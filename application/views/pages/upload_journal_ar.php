<div class="row">

	<div class="white-box boxshadow">     
		<div class="row">

			<form  id="form-import" action="<?=site_url()?>uploadjournalar/save_import_upload_journal_ar" class="form-horizontal" method="post"  enctype="multipart/form-data">  

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
							<th class="text-center">AP Invoice Netting</th>
							<th class="text-center">Validated</th> 
							<th class="text-center">AP Netting</th>
							<th class="text-center">AP Amount</th>
							<th class="text-center">AP Description</th>
							<th class="text-center">Action</th>

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

			let url  = baseURL + 'uploadjournalar/load_data_upload_journal_ar';

			Pace.track(function(){
				$('#table_data').DataTable({
					"serverSide"      : true,
					"processing"      : true,
					"ajax"            : {
						"url"  : url,
						"type" : "POST",
						"dataType": "json",
						"data"    : function ( d ) {
							d.gl_date_from = gl_date_from;
							d.gl_date_to   = gl_date_to;
							d.nature 	   = nature;
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
							{ "data": "reference_3", "width": "200px", "class": "text-left" },
							{ "data": "ar_invoice_netting", "width": "200px", "class": "text-left" },
							{ "data": "validated", "width": "100px", "class": "text-center" },
							{ "data": "ap_invoice", "width": "100px", "class": "text-center" },
							{ "data": "ap_credit", "width": "100px", "class": "text-right" },
							{ "data": "ap_description", "width": "200px" },
							{ "data": "action", "class":"text-letf", "width": "50px" }
					],
					"pageLength"      : 100,
					"ordering"        : false,
					"scrollY"         : 480,
					"scrollX"         : true,
					"scrollCollapse"  : true,
					"autoWidth"       : true,
					"bAutoWidth"      : true,
					"rowsGroup": [14,15,16,17,18],
					drawCallback: function (settings) {
						var api = this.api();
						var rows = api.rows({ page: 'current' }).nodes();
						var last = null;
						api.column(3, { page: 'current' }).data().each(function (group, i) {
							if (last != group && i > 0) {

								$(rows).eq(i).before(
									'<tr class="group"><td align="center" colspan="19" style="color:#fff;background-color:#999;line-height: 0px;height: 5px;padding: 0px;">&nbsp;</td></tr>'
									);
							}
							last = group;
						});
					}
				});
			});

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

				let url   ="<?php echo site_url(); ?>uploadjournalar/download_data_upload_journal_ar";

				vgl_date_from = $("#ddlGlDateFrom").val();
				vgl_date_to = $("#ddlGlDateTo").val();
				vnature = $("#nature").find(':selected').attr('data-name');

				window.open(url+'?date_from='+vgl_date_from +"&date_to="+ vgl_date_to +"&nature="+vnature, '_blank');

				window.focus();

			});

			$('#table_data').on('click', 'input.checklist', function () {

				let data  = table.row( $(this).parents('tr') ).data();
				let nojournal = data.journal_name;

				if (this.checked) {
					check = 'Y';
				}else{
					check = 'N';
				}

				console.log(check);
				console.log(nojournal);

				$.ajax({
					url   : baseURL + 'uploadjournalar/update_journal_validate',
					type  : "POST",
					data  : {validated_status : check, nojournal : nojournal},
					dataType: "json",
					success : function(result){
						console.log(result);
						if (result.status == true) {
							table.ajax.reload(null, false);
							customNotif('Success', result.messages, 'success');
						} else {
							customNotif('Failed', result.messages, 'error');
						}
					}
				});
			});


			$('#table_data ').on( 'click', 'a.action-delete', function () {

				data      = table.row( $(this).parents('tr') ).data();
				journal_desc = data.journal_encypt;

				$("#modal-delete-label").html('Delete Data : ' + data.journal_name );
				$("#modal-delete").modal('show');
			});

			$('#button-delete').on( 'click', function () {

				$.ajax({
					url       : baseURL + 'uploadjournalar/delete_upload_invoice/'+journal_desc,
					type      : 'GET',
					beforeSend  : function(){
						customLoading('show');
					},
					dataType : 'json',
					success : function(result){
						id_delete = 0;
						$("#modal-delete").modal('hide');
						customLoading('hide');
						if (result.status == true) {
							table.ajax.reload(null, false);
							table2.ajax.reload(null, false);
							customNotif('Success', result.messages, 'success');
						} else {
							customNotif('Failed', result.messages, 'error');
						}
					}
				});
			});

			$('#form-import').validator().on('submit', function(e) {
			if (!e.isDefaultPrevented()){
				$.ajax({
					url     : baseURL + 'uploadjournalar/save_import_upload_journal_ar',
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


		$('#table_data tbody').bind('click', 'tr td select.ap_invoice', function () {
		}).bind("change", function(e) {
			change = true;
			total_row = table.data().count();
			changeConfirmation = confirm("Are you sure to Netting this Journal?");
	        this_id = e.target.id;

		    if (!changeConfirmation) {
		        $("#"+this_id).val("0");
				change = false;
		    }

			if(change == true){

				let data           = table.row( $("#"+this_id).parents('tr') ).data();
				let journal_name   = data.journal_name;
				let journal_desc   = data.journal_encypt;
				
				let ap_invoice     = $("#"+this_id).val();
				let ap_credit      = $("#"+this_id).find(':selected').attr('data-credit');
				let ap_description = $("#"+this_id).find(':selected').attr('data-description');
				
				$("#ap_credit-"+journal_desc).html(ap_credit);
				$("#ap_description-"+journal_desc).html(ap_description);
				
				val_ap_credit      =  parseInt(ap_credit.replace(/\./g, ''));
				val_ap_description =  ap_description;
				val_ap_netting     = (ap_invoice != '0') ? 'Y' : 'N';

			    $.ajax({
				  url   : baseURL + 'uploadjournalar/update_ap_netting',
			      type  : "POST",
			      data  : {journal_name :  journal_name, ap_netting : val_ap_netting, ap_invoice : ap_invoice, ap_credit : val_ap_credit, ap_description : val_ap_description},
			      dataType: "json",
			      success : function(result){
			      	$("#"+this_id).attr("disabled", true);
			      }
			    });
			}
		});



		/*$('#table_data tbody').on('change', 'tr td select.ap_invoice', function () {

			let data         = table.row( $(this).parents('tr') ).data();
			let journal_name = data.journal_name;
			let journal_desc = data.journal_encypt;
			
			let ap_invoice   = $(this).val();
			let ap_credit    = $(this).find(':selected').attr('data-credit');

			$("#ap_credit-"+journal_desc).html(ap_credit);

			val_ap_credit   =  parseInt(ap_credit.replace(/\./g, ''));
			val_ap_netting = (ap_invoice != '0') ? 'Y' : 'N';

		    $.ajax({
			  url   : baseURL + 'uploadjournalar/update_ap_netting',
		      type  : "POST",
		      data  : {journal_name :  journal_name, ap_netting : val_ap_netting, ap_invoice : ap_invoice, ap_credit : val_ap_credit},
		      dataType: "json",
		      success : function(result){
		      	console.log('success');
		      }
		    });
		});*/



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