<div class="row">

	<div class="white-box boxshadow" id="id_upload">     
		<div class="row">

			<form method="post" action="<?=site_url()?>gl/save_import_gl_header" class="form-horizontal" enctype="multipart/form-data">  
				<label class="form-control-label">Import File</label>
				<div class="form-group">
					<div class="col-sm-3 m-b-10">
						<input class="custom-input-file form-control input-group-addon" type="file" name="file" accept=".xls,.xlsx" id="fileToUpload" required="required">
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
				<label>Receive Date From</label>
				<div class="input-group">
					<input type="text" class="form-control mydatepicker" id="ddlInnvoiceDateFrom" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 4)?>">
					<span class="input-group-addon"><i class="icon-calender"></i></span>
				</div>
			</div>
		</div>

		<div class="col-sm-3">
			<div class="form-group">
				<label>Receive Date To</label>
				<div class="input-group">
					<input type="text" class="form-control mydatepicker" id="ddlInnvoiceDateTo" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 4)?>">
					<span class="input-group-addon"><i class="icon-calender"></i></span>
				</div>
			</div>
		</div>

		<div class="col-sm-3">
			<div class="form-group">
				<label class="form-control-label" for="ddlVendorName">Vendor Name</label>
				<select class="form-control" id="ddlVendorName" name="ddlVendorName">
					<option value="">-- Choose Vendor -- </option> 
				</select> 
			</div>
		</div>

		<div class="col-sm-3">

			<div class="form-group">
				<label>&nbsp;</label>
				<button id="btnView" class="btn btn-default btn-rounded custom-input-width btn-block" type="button"><i class="fa fa-search"></i> <span>VIEW</span></button>

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
						<th class="text-center">No Urut</th>
						<th class="hidden" >Header ID</th>
						<th class="text-center">Transaction Date</th>
						<th class="text-center">Invoice Date</th>
						<th class="text-center">Batch Name</th>
						<th class="text-center">No Journal</th>
						<th class="text-center">Nama Vendor</th>
						<th class="text-center">No Invoice</th>
						<th class="text-center">No Kontrak</th>
						<th class="text-center">Description</th>
						<th class="text-center">Currency</th>
						<th class="text-center">DPP</th>
						<th class="text-center">Kurs</th>
						<th class="text-center">Nominal Rate</th>
						<th class="text-center">No FPJP</th>
						<th class="text-center">Nama Rekening</th>
						<th class="text-center">Nama Bank</th>
						<th class="text-center">Acct Number</th>
						<th class="text-center">TOP</th>
						<th class="text-center">Due Date</th>
						<th class="text-center">Nature</th>
						<th class="text-center">Denda</th>
						<th class="text-center">Notes FPJP</th>
						<th class="text-center">Is BAST</th>
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

<div class="row" id="tblDataJournalBeforeTax">
	<div class="col-md-12">
		<div class="white-box">
			<hr>
			<h4 id="table_detail_title">Journal Before Tax</h4>
			<table id="table_data2" class="table table-responsive w-full table-striped table-hover table-bordered display cell-border stripe hover dataTable small">
				<thead>
					<tr>
						<th class="text-center">No.</th>
						<th class="text-center">Transaction Date</th>
						<th class="text-center">Invoice Date</th>
						<th class="text-center">Due Date</th>
						<th class="text-center">Batch Name</th>
						<th class="text-center">Batch Description</th>
						<th class="text-center">Nama Vendor</th>
						<th class="text-center">Nama Journal</th>
						<th class="text-center">No Invoice</th>
						<th class="text-center">No Kontrak</th>
						<th class="text-center">Nature</th>
						<th class="text-center">Account Description</th>
						<th class="text-center">Currency</th>
						<th class="text-center">Debet</th>
						<th class="text-center">Credit</th>
						<th class="text-center">Journal Description</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>

<div class="row" id="tblDatadownload2">
	<div class="col-md-12">
		<div class="white-box boxshadow">     
			<div class="row">
				<div class="form-group">
					<div class="col-md-offset-5 col-md-2 m-b-10">
						<button id="btnPrint" class="btn btn-success btn-rounded btn-block" type="button" ><i class="fa  fa-file-excel-o"></i> <span>Download</span></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- add by abun -->
<div id="modal-edit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<form role="form" id="form-edit" data-toggle="validator">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h2 class="modal-title text-white" id="modal-edit-label">Edit Data</h2>
				</div>
				<div class="modal-body"> 

					<div class="row">
						<div class="form-group">
							<div class="col-lg-6">
								<div class="col-lg-12">
									<label class="form-control-label" for="txtTanggalInvoice">Transaction Date</label>
									<div class="input-group">
										<input type="text" class="form-control mydatepicker" id="txtTanggalInvoice" name="txtTanggalInvoice" data-toggle="validator" data-error="Date Cannot less than Today" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 4)?>">
										<span class="input-group-addon"><i class="icon-calender"></i></span>
									</div>
									<div class="help-block with-errors"></div>
									<input type="hidden" name="gl_header_id" id="gl_header_id">
								</div>
							</div>

							<div class="col-lg-6">
								<div class="col-lg-12">
									<label class="form-control-label" for="txtInvoiceDate">Invoice Date</label>
									<div class="input-group">
										<input type="text" class="form-control mydatepicker" id="txtInvoiceDate" name="txtInvoiceDate" data-toggle="validator" data-error="Date Cannot less than Today" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 4)?>">
										<span class="input-group-addon"><i class="icon-calender"></i></span>
									</div>
									<div class="help-block with-errors"></div>
									<!-- <input type="hidden" name="gl_header_id" id="gl_header_id"> -->
								</div>
							</div>
						</div>
						
					</div>

					<div class="row">
						<div class="form-group">
							<div class="col-lg-6">
								<div class="col-lg-12">
								<div>
									<label class="form-control-label" for="txtBatchName">Batch Name</label>
									<input type="text" class="form-control" id="txtBatchName" name="txtBatchName" placeholder="Batch Name" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" required />
									<div class="help-block with-errors"></div>
								</div>
								</div>
							</div>
					
							<div class="col-lg-6">
								<div class="col-lg-12">
								<div>
									<label class="form-control-label" for="txtNoJournal">No Journal</label>
									<input type="text" class="form-control" id="txtNoJournal" name="txtNoJournal" placeholder="No Journal" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" readonly="true" />
									<div class="help-block with-errors"></div>
								</div>
								</div>
							</div>
						</div>

					</div>



					<div class="row">
						<div class="form-group">

							<div class="col-lg-6">
								<div class="col-lg-12">
									<label class="form-control-label" for="txtNamaVendor">Nama Vendor</label>
									<input type="text" class="form-control" id="txtNamaVendor" name="txtNamaVendor" placeholder="Nama Vendor" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" required/>
									<div class="help-block with-errors"></div>
								</div>
							</div>

							<div class="col-lg-6">
								<div class="col-lg-12">
									<label class="form-control-label" for="txtNoInvoice">No Invoice</label>
									<input type="text" class="form-control" id="txtNoInvoice" name="txtNoInvoice" placeholder="No Invoice" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" required/>
									<div class="help-block with-errors"></div>
								</div>
							</div>
						</div>
					</div>


					<div class="row">
						<div class="form-group">
							<div class="col-lg-6">
								<div class="col-lg-12">
									<label class="form-control-label" for="txtNoKontrak">No Kontrak</label>
									<input type="text" class="form-control" id="txtNoKontrak" name="txtNoKontrak" placeholder="No Kontrak" autocomplete="off"/>
								</div>
							</div>
							<!-- <div class="col-lg-6">
								<div class="col-lg-12">
									<label class="form-control-label" for="txtMaterai">Materai</label>
									<input type="text" class="form-control" id="txtMaterai" name="txtMaterai" placeholder="Materai" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" required/>
									<div class="help-block with-errors"></div>
								</div>
							</div> -->
							<div class="col-lg-6 d-none">
								<div class="col-lg-12">
									<label class="form-control-label" for="txtDPP">DPP</label>
									<input type="text" class="form-control" id="txtDPP" name="txtDPP" placeholder="DPP" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" required/>
									<div class="help-block with-errors"></div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="form-group">
							<div class="col-lg-6">
								<div class="col-lg-12">
									<label class="form-control-label" for="txtNoFPJP">No FPJP</label>
									<input type="text" class="form-control" id="txtNoFPJP" name="txtNoFPJP" placeholder="No FPJP" autocomplete="off"/>
								</div>
							</div>

						<div class="col-lg-6">
								<div class="col-lg-12">
									<label class="form-control-label" for="txtNamaRekening">Nama Rekening</label>
									<input type="text" class="form-control" id="txtNamaRekening" name="txtNamaRekening" placeholder="Nama Rekening" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" required/>
									<div class="help-block with-errors"></div>
								</div>
							</div>
						</div>
					</div>


					<div class="row">
						<div class="form-group">
							<div class="col-lg-6">
								<div class="col-lg-12">
									<label class="form-control-label" for="txtNamaBank">Nama Bank</label>
									<input type="text" class="form-control" id="txtNamaBank" name="txtNamaBank" placeholder="Nama Bank" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" required/>
									<div class="help-block with-errors"></div>
								</div>
							</div>
						<div class="col-lg-6">
								<div class="col-lg-12">
									<label class="form-control-label" for="txtAcctNumber">Acct Number</label>
									<input type="text" class="form-control" id="txtAcctNumber" name="txtAcctNumber" placeholder="Acct Number" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" required/>
									<div class="help-block with-errors"></div>
								</div>
							</div>
							
						</div>
					</div>

					<div class="row">
						<div class="form-group">
							<div class="col-lg-6">
								<div class="col-lg-12">
									<label class="form-control-label" for="txtTop">Top</label>
									<input type="text" class="form-control" id="txtTop" name="txtTop" placeholder="Top" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" required/>
									<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-lg-6 d-none">
								<div class="col-lg-12">
									<label class="form-control-label" for="txtNature">Nature</label>
									<input type="text" class="form-control" id="txtNature" name="txtNature" placeholder="Nature" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" readonly="true"/>
									<div class="help-block with-errors"></div>
								</div>
							</div>
						</div>
					</div>

					<!-- <div class="row">
						<div class="form-group">
							<div class="col-lg-12">
								<div class="col-lg-8">
									<label class="form-control-label" for="txtDueDate">Due Date</label>
									<input type="text" class="form-control" id="txtDueDate" name="txtDueDate" placeholder="Due Date" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" required disabled/>
									<div class="help-block with-errors"></div>
								</div>
							</div>
						</div>
					</div> -->

					<div class="row">
						<div class="form-group">
							<div class="col-lg-6">
								<div class="col-lg-12">
									<label class="form-control-label" for="txtDenda">Denda</label>
									<input type="text" class="form-control money-format" id="txtDenda" name="txtDenda" placeholder="Denda" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" required/>
									<div class="help-block with-errors"></div>
								</div>
							</div>
						</div>
					</div>


					<div class="row d-none">
						<div class="form-group">
							<div class="col-lg-12">
								<div class="col-lg-12">
									<label class="form-control-label" for="txtDescription">Description</label>
									<input type="text" class="form-control" id="txtDescription" name="txtDescription" placeholder="Description" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" required/>
									<div class="help-block with-errors"></div>
								</div>
							</div>
						</div>
					</div>


				</div>
				<div class="modal-footer">
					<!-- <button type="reset" class="btn btn-default"><i class="fa fa-trash-o"></i> Clear</button> -->
					<button type="button" class="btn btn-danger waves-effect" data-dismiss="modal"><i class="fa fa-times-circle"></i> CANCEL</button>
					<button type="submit" class="btn btn-info waves-effect" id="btnSave"><i class="fa fa-save"></i> Save</button>
				</div>
			</form>  
		</div>
	</div>
</div>

<div id="modal-reject" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<form role="form" id="form-reject" data-toggle="validator">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h2 class="modal-title text-white" id="modal-reject-label">Reject</h2>
				</div>
				<div class="modal-body"> 

					<div class="row">
						<div class="form-group">
							<div class="col-lg-12">
									<label class="form-control-label" for="txtReason">Reason</label>
									<textarea class="form-control" id="txtReason" name="txtReason" rows="4"style="min-width: 100%" placeholder="If Reject please input this"> </textarea>
									<div class="help-block with-errors"></div>
							</div>
						</div>
					</div>

					<div class="row">
					  <div class="white-box border-radius-5 small">
					      	<form class="form-horizontal" id="form-submitter">
						      
						      	<div class="row">
						      		<div class="col-md-12">
							      		<div class="form-group m-b-10">
							                <div class="attachment_group">
								  	        	<label class="col-sm-3 control-label text-left">Document <span class="pull-right">:</span></label>
									            <div class="col-sm-10">
									                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
									                    <div id="fileinput" class="form-control input-sm" data-trigger="fileinput"> <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div><span class="input-group-addon btn btn-default btn-outline btn-file"> <span class="fileinput-new">Select file</span> <span class="fileinput-exists">Change</span>
									                    <input id="attachment" type="file" name="attachment" data-name="fpjp_return" accept=".pdf,.jpg,.png,.jpeg"> </span> <a id="fileinput_remove" href="javascript:void(0)" class="input-group-addon btn btn-warning fileinput-exists" data-dismiss="fileinput">Remove</a>
									                </div>
								                    <div class="progress progress-lg d-none">
								                        <div id="progress" class="progress-bar progress-bar-info active progress-bar-striped" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%" role="progressbar"></div>
								                    </div>
												</div>
								            </div>
								        </div>
							        </div>
						  	    </div>
					      	</form>
					  </div>
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger waves-effect" id="button-reject"><i class="fa fa-times"></i> Reject</button>
					<!-- <button type="button" class="btn btn-success waves-effect" id="button-approved"><i class="fa fa-check"></i> Approve</button> -->
				</div>
			</form>  
		</div>
	</div>
</div>



<div class="modal fade" id="modal-approve" role="dialog" aria-labelledby="modal-approve">
  <div class="modal-dialog">
    <div class="modal-content">
       <form role="form" id="form-approve">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-white">Confirmation</h4>
            </div>
            <div class="modal-body">
                Do you want to approve?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">CANCEL</button>
                <button type="button" class="btn btn-info" id="button-approve" name="confirm" value="Yes">YES</button>
            </div>
        </form>
    </div>
  </div>
</div>





<script>

	$(document).ready(function(){

		getVendorName();

		let attachment_file = "";
   	    let attach_category = $('#attachment').data('name');


		let groupings = "";
		let groupingstext = "";
		let invoice_date_from = $("#ddlInnvoiceDateFrom").val();
		let invoice_date_to = $("#ddlInnvoiceDateTo").val();
		let vendor_name = $("#ddlVendorName").val();
		$('#tblData').hide();
		$('#tblDataJournalBeforeTax').hide();
		$('#tblDatadownload1').hide();
		$('#tblDatadownload2').hide();

		$("#ddlVendorName").on("change", function(){
			vendor_name = $("#ddlVendorName").val();
		});

		$('.mydatepicker').datepicker({
			format: 'dd/mm/yyyy',
			todayHighlight:'TRUE',
			autoclose: true,
		});

		let url  = baseURL + 'gl/load_data_gl_header';

		Pace.track(function(){
			$('#table_data').DataTable({
				"serverSide"      : true,
				"processing"      : true,
				"ajax"            : {
					"url"  : url,
					"type" : "POST",
					"dataType": "json",
					"data"    : function ( d ) {
						d.invoice_date_from = invoice_date_from;
						d.invoice_date_to   = invoice_date_to;
						d.vendor_name   = vendor_name;
					}
				},
				"language"        : {
					"emptyTable"  : "<span class='label label-danger'>Data Not Found!</span>",
					"infoEmpty"   : "Data Kosong",
					"processing"  : '<img src="' + imageLoadingSmall + '" alt="" />',
					"search"      : "_INPUT_"
				},
				"columns"         : [
				{ "data": "no", "width": "50px", "class": "text-center" },
				{ "data": "gl_header_id", "width": "10px", "class": "hidden" },
				{ "data": "tanggal_invoice", "width": "100px", "class": "text-left" },
				{ "data": "invoice_date", "width": "100px", "class": "text-left" },
				{ "data": "batch_name", "width": "150px", "class": "text-left" },
				{ "data": "no_journal", "width": "150px", "class": "text-left" },
				{ "data": "nama_vendor", "width": "150px", "class": "text-left" },
				{ "data": "no_invoice", "width": "150px", "class": "text-left" },
				{ "data": "no_kontrak", "width": "150px", "class": "text-left" },
				{ "data": "description", "width": "250px", "class": "text-left" },
				{ "data": "currency", "width": "50px", "class": "text-center" },
				{ "data": "dpp", "width": "100px", "class": "text-right" },
				{ "data": "kurs", "width": "100px", "class": "text-right" },
				{ "data": "nominal_rate", "width": "100px", "class": "text-right" },
				{ "data": "no_fpjp", "width": "150px" },
				{ "data": "nama_rekening", "width": "150px" },
				{ "data": "nama_bank", "width": "120px" },
				{ "data": "acct_number", "width": "120px" },
				{ "data": "top", "width": "40px", "class": "text-center" },
				{ "data": "due_date", "width": "100px" },
				{ "data": "nature", "class":"text-center", "width": "80px" },
				{ "data": "denda", "class":"text-right", "width": "100px" },
				{ "data": "notes_user", "width": "120px" },
				{ "data": "is_bast", "class":"text-center hidden", "width": "40px" },
				{ "data": "action", "class":"text-center hidden", "width": "100px" }
				],
				"pageLength"      : 100,
		      	"ordering"        : false,
		      	"scrollY"         : 480,
		      	"scrollX"         : true,
		      	"scrollCollapse"  : true,
		      	"autoWidth"       : true,
		      	"bAutoWidth"      : true,
		      	"rowsGroup": [23,24],
				drawCallback: function (settings) {
		        var api = this.api();
		        var rows = api.rows({ page: 'current' }).nodes();
		        var last = null;
		        api.column(5, { page: 'current' }).data().each(function (group, i) {
		          if (last != group && i > 0) {

		            $(rows).eq(i).before(
		              '<tr class="group"><td align="center" colspan="23" style="color:#fff;background-color:#999;line-height: 0px;height: 5px;padding: 0px;">&nbsp;</td></tr>'
		              );
		          }
		          last = group;
		        });
		      }
    });
  });

		table = $('#table_data').DataTable();

		let url2  = baseURL + 'gl/load_data_journal_before_tax';

		Pace.track(function(){
			$('#table_data2').DataTable({
				"serverSide"      : true,
				"processing"      : true,
				"ajax"            : {
					"url"  : url2,
					"type" : "POST",
					"dataType": "json",
					"data"    : function ( d ) {
						d.invoice_date_from = invoice_date_from;
						d.invoice_date_to   = invoice_date_to;
						d.vendor_name   = vendor_name;
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
				{ "data": "tanggal_invoice", "width": "150px", "class": "text-left" },
				{ "data": "invoice_date", "width": "150px", "class": "text-left" },
				{ "data": "due_date", "width": "100px", "class": "text-left" },
				{ "data": "batch_name", "width": "150px", "class": "text-left" },
				{ "data": "batch_description", "width": "150px", "class": "text-left" },
				{ "data": "nama_vendor", "width": "150px", "class": "text-left" },
				{ "data": "journal_name", "width": "150px", "class": "text-left" },
				{ "data": "no_invoice", "width": "140px", "class": "text-left" },
				{ "data": "no_kontrak", "width": "140px", "class": "text-left" },
				{ "data": "nature", "class":"text-letf", "width": "100px" },
				{ "data": "account_description", "width": "250px", "class": "text-left" },
				{ "data": "currency", "width": "100px", "class": "text-left" },
				{ "data": "debet", "width": "100px", "class": "text-left" },
				{ "data": "credit", "width": "100px", "class": "text-left" },
				{ "data": "journal_description", "width": "250px", "class": "text-left" }
				],
				"pageLength"      : 100,
				"ordering"        : false,
				"scrollY"         : 480,
				"scrollX"         : true,
				"scrollCollapse"  : true,
				"autoWidth"       : true,
				"bAutoWidth"      : true,
				drawCallback: function (settings) {
					var api = this.api();
					var rows = api.rows({ page: 'current' }).nodes();
					var last = null;
					api.column(7, { page: 'current' }).data().each(function (group, i) {
						if (last != group && i > 0) {

							$(rows).eq(i).before(
								'<tr class="group"><td align="center" colspan="16" style="color:#fff;background-color:#999;line-height: 0px;height: 5px;padding: 0px;">&nbsp;</td></tr>'
								);
						}
						last = group;
					});
				}
			});
		});


		table2 = $('#table_data2').DataTable();

		$("#btnDownload").on("click", function(){

			let vinvoice_date_from = '';
			let vinvoice_date_to = '';
			let vvendor_name = '';

			let url   ="<?php echo site_url(); ?>gl/download_data_gl_header";

			vinvoice_date_from = $("#ddlInnvoiceDateFrom").val();
			vinvoice_date_to = $("#ddlInnvoiceDateTo").val();
			vvendor_name = $("#ddlVendorName").val();

			window.open(url+'?date_from='+vinvoice_date_from +"&date_to="+ vinvoice_date_to+"&vendor_name="+ vvendor_name, '_blank');

			window.focus();

		});

		$("#btnPrint").on("click", function(){

			let vinvoice_date_from = '';
			let vinvoice_date_to = '';
			let vvendor_name = '';

			let url   ="<?php echo site_url(); ?>gl/download_data_before_tax";

			vinvoice_date_from = $("#ddlInnvoiceDateFrom").val();
			vinvoice_date_to = $("#ddlInnvoiceDateTo").val();
			vvendor_name = $("#ddlVendorName").val();

			window.open(url+'?date_from='+vinvoice_date_from +"&date_to="+ vinvoice_date_to+"&vendor_name="+ vvendor_name, '_blank');

			window.focus();

		});


		$('#btnView').on( 'click', function () {

			invoice_date_from = $("#ddlInnvoiceDateFrom").val();
			invoice_date_to = $("#ddlInnvoiceDateTo").val();
			vendor_name = $("#ddlVendorName").val();

			table.draw();
			table2.draw();

			$('#tblData').slideDown(700);
			$('#tblData').css( 'display', 'block' );
			table.columns.adjust().draw();
			$('#tblDatadownload1').slideDown(700);

			$('#tblDataJournalBeforeTax').slideDown(700);
			$('#tblDataJournalBeforeTax').css( 'display', 'block' );
			table2.columns.adjust().draw();
			$('#tblDatadownload2').slideDown(700);
		});


		function getVendorName(){

			$.ajax({
				url   : baseURL + 'gl/load_ddl_all_vendor',
				type  : "POST",
				dataType: "html",
				success : function(result){
					$("#ddlVendorName").html("");       
					$("#ddlVendorName").html(result);
					setTimeout(function(){
						$("#ddlVendorName").select2();
					}, 500);
				}
			});     

		}

	    $('#table_data').on( 'click', 'a.action-edit', function () {

			data = table.row( $(this).parents('tr') ).data();

			$("#isNewRecord").val("0");
			$("#gl_header_id").val(data.gl_header_id);
			console.log(data.gl_header_id);
			$("#txtTanggalInvoice").val(data.tanggal_invoice);
			$("#txtInvoiceDate").val(data.invoice_date);
			$("#txtBatchName").val(data.batch_name);
			$("#txtNoJournal").val(data.no_journal);
			$("#txtNamaVendor").val(data.nama_vendor);
			$("#txtNoInvoice").val(data.no_invoice);
			$("#txtNoKontrak").val(data.no_kontrak);
			$("#txtDescription").val(data.description);
			$("#txtDPP").val(data.dpp);
			$("#txtNoFPJP").val(data.no_fpjp);
			$("#txtNamaRekening").val(data.nama_rekening);
			$("#txtNamaBank").val(data.nama_bank);
			$("#txtAcctNumber").val(data.acct_number);
			$("#txtTop").val(data.top);
			$("#txtDueDate").val(data.due_date);
			$("#txtNature").val(data.nature);
			$("#txtDenda").val(data.denda);
			$("#txtMaterai").val(data.materai);

			$("#modal-edit-label").html('Edit : ' +  data.no_journal );
			$("#modal-edit").modal('show');
		});


		$('#form-edit').validator().on('submit', function(e) {
			if (!e.isDefaultPrevented()){
				$.ajax({
					url     : baseURL + 'gl/edit_upload_invoice',
					type    : "POST",
					data    : $('#form-edit').serialize(),
					beforeSend  : function()
					{
						customLoading('show');
					},
					success : function(result){
						if (result==1) {
							table.ajax.reload(null, false);
							table2.ajax.reload(null, false);
							$("#modal-edit").modal('hide');
							customLoading('hide');
							customNotif('Success','Record changed!','success', 4000 );
						}
						else if (result==0) {
							$("body").removeClass("loading");
							customLoading('hide');
							customNotif('Failed', result,'error', 4000 );
						}
						else{
							$("body").removeClass("loading");
							customLoading('hide');
							customNotif('Failed', result,'error', 4000 );
						}
					}
				});
			}
			e.preventDefault();
		});


		let id_gr = 0;
		let nomor_fpjp = 0;

	    $('#table_data ').on( 'click', 'a.action-delete', function () {

			data      = table.row( $(this).parents('tr') ).data();
			id_delete = data.gl_header_id;
			journal_desc = data.journal_encypt;

			groupings = data.no_fpjp == null ? data.no_kontrak : (data.no_fpjp == "" ? data.no_kontrak : data.no_fpjp);
			groupingstext = data.no_fpjp == null ? "No Kontrak" : (data.no_fpjp == "" ? "No Kontrak" : "No FPJP");

			nomor_fpjp = data.no_fpjp;
			id_gr = data.gr_id;

			$("#modal-reject-label").html('Reject : ' + groupings );
			$("#modal-reject").modal('show');
		});

		//diganti jadi ini ada confirmation
		$('#table_data ').on( 'click', 'a.action-approve', function () {
			
			data         = table.row( $(this).parents('tr') ).data();
			id_delete    = data.gl_header_id;
			journal_desc = data.journal_encypt;

			$("#modal-approve-label").html('Approve : ' + groupings );
			$("#modal-approve").modal('show');

		});

		$('#button-approve').on( 'click', function () {

	      $.ajax({
	        url       : baseURL + 'gl/approve_upload_invoice',
	        type      : 'post',
	        data      : {'journal_name' : journal_desc , 'trigger':"Y"},
	        beforeSend  : function(){
	                        customLoading('show');
	                      },
	        dataType : 'html',
	        success : function(result){
						if (result==1) {
							table.ajax.reload(null, false);
							table2.ajax.reload(null, false);
							$("#modal-approve").modal('hide');
							customLoading('hide');
							customNotif('Success','Successfully approved!','success', 4000 );
						}
						else if (result==0) {
							$("body").removeClass("loading");
							customLoading('hide');
							customNotif('Failed', result,'error', 4000 );
						}
						else{
							$("body").removeClass("loading");
							customLoading('hide');
							customNotif('Failed', result,'error', 4000 );
						}
				}

	      });
	  	});


		$('#button-reject').on( 'click', function () {

			reason = $("#txtReason").val();
			
			$.ajax({
		        url       : baseURL + 'gl/reject_upload_invoice',
		        type      : 'post',
		        data      : {'gl_header_id' : id_delete , 'journal_name' : journal_desc , 'groupingstext' : groupingstext, 'groupings':groupings, 'reason':reason, 'id_gr' : id_gr, 'nomor_fpjp' : nomor_fpjp, 'attachment' : attachment_file},
		        beforeSend  : function(){
		                        customLoading('show');
		                      },
		        dataType : 'html',
		        success : function(result){
						if (result==1) {
							table.ajax.reload(null, false);
							table2.ajax.reload(null, false);
							$("#modal-reject").modal('hide');
							customLoading('hide');
							customNotif('Success','Record changed!','success', 4000 );
						}
						else if (result==0) {
							$("body").removeClass("loading");
							customLoading('hide');
							customNotif('Failed', result,'error', 4000 );
						}
						else{
							$("body").removeClass("loading");
							customLoading('hide');
							customNotif('Failed', result,'error', 4000 );
						}
					}

		      });


		  });

		$('#modal-edit').on('hidden.bs.modal', function () {
			if ($('#form-edit').find('.has-error, .has-danger').length > 0) {
				$(".help-block").html('');
				$('.has-error').removeClass('has-error');
			}

		});

		$('#modal-reject').on('show.bs.modal', function () {
    		$("#txtReason").val('');
  		});

		$('#modal-reject').on('hidden.bs.modal', function () {
    		if(attachment_file != ''){
    			$("#fileinput_remove").trigger('click');
    		}
  		});

		$('#table_data').on('click', 'input.checklist', function () {

			  let data  = table.row( $(this).parents('tr') ).data();
			  let gl_header = data.gl_header_id;
			  let no_journals = data.no_journal;

			  if (this.checked) {
			    check = 'Y';
			  }else{
			    check = '';
			  }

			  console.log(check);
			  console.log(gl_header);

			  $.ajax({
			    url   : baseURL + 'Gl/update_bast',
			    type  : "POST",
			    data  : {verified_status : check, gl_header : gl_header, no_journal : no_journals},
			    dataType: "json",
			    success : function(result){
			      console.log(result);
			      if (result.status == true) {
			        //table.ajax.reload(null, false);
			        customLoading('hide');
			        customNotif('Success', result.messages, 'success');
			      } else {
			        customLoading('hide');
			        customNotif('Failed', result.messages, 'error');
			      }
			    }
			  });
			});

		$('#table_data').on('click', 'input.checklist_vatsa', function () {

			  let data  = table.row( $(this).parents('tr') ).data();
			  let gl_header = data.gl_header_id;

			  if (this.checked) {
			    check = 'Y';
			  }else{
			    check = '';
			  }

			  console.log(check);
			  console.log(gl_header);

			  $.ajax({
			    url   : baseURL + 'Gl/update_vatsa',
			    type  : "POST",
			    data  : {verified_status : check, gl_header : gl_header},
			    dataType: "json",
			    success : function(result){
			      console.log(result);
			      if (result.status == true) {
			        //table.ajax.reload(null, false);
			        customLoading('hide');
			        customNotif('Success', result.messages, 'success');
			      } else {
			        customLoading('hide');
			        customNotif('Failed', result.messages, 'error');
			      }
			    }
			  });
			});

		function setInputFilter(textbox, inputFilter) {
			["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(event) {
				textbox.addEventListener(event, function() {
					if (inputFilter(this.value)) {
						this.oldValue = this.value;
						this.oldSelectionStart = this.selectionStart;
						this.oldSelectionEnd = this.selectionEnd;
					} else if (this.hasOwnProperty("oldValue")) {
						this.value = this.oldValue;
						this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
					} else {
						this.value = "";
					}
				});
			});
		}

		let jqXHR;

    $("#fileinput_remove").on("click", function(e) {
    	deleteFile();
		jqXHR.abort();
	    reset_proggress();
	});

    $("#attachment").bind("click", function(e) {
        lastValue = $(this).val();
        reset_proggress();
    }).bind("change", function(e) {
      upload = true;
      if(lastValue != ""){
        upload = false;
        if(deleteFile()){
          upload = true;
        }
      }
      if($(this).val() != ""){

        fileSize = this.files[0].size;
        fileName = this.files[0].name;
        fileType = this.files[0].type;
        fileSize = fileSize/1000
        j=0;

        if(fileSize > 21000){
          upload = false;
          alert('Max file size 20M')
          $(this).val(lastValue);
        }

        extension_allow = ['pdf','jpg','png','jpeg'];
        console.log(fileName);
        console.log(fileType);
        extension       = fileName.split('.').pop().toLowerCase();
        if (extension_allow.indexOf(extension) < 0) {
			upload = false;
			alert('Extention not allowed');
			$(this).val(lastValue);
        }


        if(upload){
          $("#progress").parent().removeClass('d-none');
              
          file = $('#attachment')[0].files[0];
          formData = new FormData();
          formData.append('file', file);
          formData.append('category', attach_category);

              jqXHR  = $.ajax({
                  url: baseURL + "upload/do_upload",
                  type: 'POST',
                  data: formData,
                  cache: false,
                  dataType: 'json',
                  enctype   : 'multipart/form-data',
                  contentType: false,
                  processData: false,
                  xhr: function() {
                      var xhr = new window.XMLHttpRequest();
                      xhr.upload.addEventListener("progress", function(evt) {
                          if (evt.lengthComputable) {
                              percentage = Math.round((evt.loaded / evt.total) * 100);
                              if(percentage == 100){
                                percentage = 99;
                              }
                          $("#progress").css('width', percentage+'%');
                          $("#progress").html(percentage+'%');
                          
                          }
                      }, false);
                      return xhr;
                  },
                  success: function(result) {
                      $("#progress").css('width', '100%');
                      if(result.status == true){
                              $("#progress").html('100%');
                              setTimeout(function(){
                                $("#progress").html('Complete!');
                                $("#progress").removeClass('progress-bar-info');
                                $("#progress").addClass('progress-bar-success');
                                attachment_file = result.messages;
                      }, 1000);
                            }else{
                              setTimeout(function(){
                                $("#progress").html(result.messages);
                                $("#progress").removeClass('progress-bar-info');
                                $("#progress").addClass('progress-bar-danger');
                      }, 500);
                      }
                  },
              error: function (xhr, ajaxOptions, thrownError) {
                $("#progress").parent().addClass('d-none');
                    $("#progress").css('width', '100%');
                    $("#progress").removeClass('progress-bar-info');
                    setTimeout(function(){
                      $("#progress").addClass('progress-bar-danger');
                  $("#progress").parent().removeClass('d-none');
                      $("#progress").html('Error Connection');
            }, 200);
              }
              });
              return false;

        }
              
      }
    });


	function reset_proggress(){
      $('#file_input').val("");
      $("#delete_file").addClass('d-none');
      $("#progress").removeClass("progress-bar-success");
      $("#progress").removeClass("progress-bar-danger");
      $("#progress").parent().addClass('d-none');
      $("#progress").css('width', '0%');
      $("#progress").html('0%');
    }

	 function deleteFile(){

      $.ajax({
            url   : baseURL + 'upload/delete',
            type  : "POST",
            data  : {file: attachment_file, category: attach_category},
            dataType: "json",
            success : function(result){
              attachment_file = '';
              return true;
            }
        });

        return true;
    }


/*		setInputFilter(document.getElementById("txtDPP"), function(value) 
		{
  		return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
  	});
*/
		setInputFilter(document.getElementById("txtAcctNumber"), function(value) 
		{
  		return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
  	});

		setInputFilter(document.getElementById("txtTop"), function(value) 
		{
  		return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
  	});

		setInputFilter(document.getElementById("txtNature"), function(value) 
		{
  		return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
  	});

		document.getElementById("txtDPP").addEventListener('keyup', function (event) 
		{
			if (event.which >= 37 && event.which <= 40) return;

			this.value = this.value.replace(/\D/g, '')
			.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
		});


		<?php if($this->session->flashdata('messages') != ""): ?>
			customNotif('Success', '<?= $this->session->flashdata('messages') ?>', 'success');
			<?php elseif($this->session->flashdata('error') != ""): ?>
				customNotif('Error', '<?= $this->session->flashdata('error') ?>', 'error');
				<?php elseif($this->session->flashdata('warning') != ""): ?>
					customNotif('Warning', '<?= $this->session->flashdata('warning') ?>', 'warning');
				<?php endif; ?>

				<?php if(in_array("Invoice Inquiry", $group_name)) : ?>
					$("#id_upload").hide();
				<?php endif ?>

			});

		</script>