<?php if(strtolower($fs_status) == "returned"): ?>
  	<div class="row">
	      <button id="edit_fs" class="btn btn-warning border-radius-5 w-150 m-b-10" type="button"><i class="fa fa-edit"></i> Edit Justif</button>
  	</div>
<?php endif; ?>

<?php $this->load->view('ilustration') ?>

<div class="row">
  <div class="white-box boxshadow m-b-0 mt-10 border-top-only-5 z-index-2 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">Justif Detail</h5>
    </div>
  </div>
  <div class="white-box boxshadow z-index-2 py-10 border-bottom-only-5 small">
	<form class="form-horizontal">
		<div class="row">
	    	<div class="col-sm-6">
	        	<div class="form-group m-b-10">
		          	<label class="col-sm-5 col-md-4 control-label text-left">Justif Number <span class="pull-right">:</span></label>
		            <div class="col-sm-7 col-md-8">
		            	<label id="fs_number" class="control-label text-left"><?= $fs_number ?></label>
		            </div>
		        </div>
		        <div class="form-group m-b-10">
		            <label class="col-sm-5 col-md-4 control-label text-left">Justif Name <span class="pull-right">:</span></label>
		            <div class="col-sm-7 col-md-8">
		            	<label id="fs_name" class="control-label text-left"><?= $fs_name ?></label>
		            </div>
		        </div>
		        <div class="form-group m-b-10">
		          	<label class="col-sm-5 col-md-4 control-label text-left">Description <span class="pull-right">:</span></label>
		            <div class="col-sm-7 col-md-8">
		            	<label id="fs_description" class="control-label text-left" style="word-break: break-all"><?= $fs_description ?></label>
		            </div>
		        </div>
		        <div class="form-group m-b-10">
	        		<label class="col-sm-5 col-md-4 control-label text-left">Currency<?= ($fs_currency != "IDR") ? "/Rate" : "" ?> <span class="pull-right">:</span></label>
	        		<div class="col-sm-7 col-md-8">
	        			<label id="fs_currency" class="control-label text-left"><?= $fs_currency ?><?= ($fs_currency != "IDR") ? " / ".$fs_rate : "" ?></label>
	        		</div>
	        	</div>
		        <div class="form-group m-b-10">
		        	<label class="col-sm-5 col-md-4 control-label text-left">Total Amount <span class="pull-right">:</span></label>
		            <div class="col-sm-7 col-md-8">
		            	<label id="fs_amount" class="control-label text-left"><?= $fs_amount ?></label>
		            </div>
		        </div>
		        <div class="form-group m-b-10">
		            <label class="col-sm-5 col-md-4 control-label text-left">Directorate <span class="pull-right">:</span></label>
		            <div class="col-sm-7 col-md-8">
		            	<label class="control-label text-left"><?= get_directorat($fs_directorat) ?></label>
		            </div>
		        </div>
		        <div class="form-group m-b-10">
		            <label class="col-sm-5 col-md-4 control-label text-left">Division <span class="pull-right">:</span></label>
		            <div class="col-sm-7 col-md-8">
		            	<label class="control-label text-left"><?= get_division($fs_division) ?></label>
		            </div>
		        </div>
		        <div class="form-group m-b-10">
		            <label class="col-sm-5 col-md-4 control-label text-left">Unit <span class="pull-right">:</span></label>
		            <div class="col-sm-7 col-md-8">
		            	<label class="control-label text-left"><?= get_unit($fs_unit) ?></label>
		            </div>
		        </div>
		        <?php if($district): ?>
		        <div class="form-group m-b-10">
		            <label class="col-sm-5 col-md-4 control-label text-left">District Area <span class="pull-right">:</span></label>
		            <div class="col-sm-7 col-md-8">
		            	<label class="control-label text-left"><?= $district_name ?></label>
		            </div>
		        </div>
		        <?php endif; ?>
	        </div>
	        <div class="col-sm-6">
	        	<div class="form-group m-b-10">
		            <label class="col-sm-5 col-md-4 control-label text-left">Date <span class="pull-right">:</span></label>
		            <div class="col-sm-7 col-md-8">
		            	<label class="control-label text-left"><?= $fs_date ?></label>
		            </div>
		        </div>
	        	<div class="form-group m-b-10">
	        		<label class="col-sm-5 col-md-4 control-label text-left">Attachment <span class="pull-right">:</span></label>
		            <div class="col-sm-7 col-md-8">
		            	<?php if(count($fs_attachment) == 1): ?>
		            	<label class="control-label"><a id="fs_attachment" class="btn btn-xs btn-success" href="<?= $fs_attachment[0]['FILE_LINK'] ?>" target="_blank"> <i class="fa fa-download"></i> Download </a></label>
		            	<?php elseif(count($fs_attachment) > 1): ?>
        				<button class="btn btn-xs mt-2 btn-success" data-toggle="modal" data-target="#modal-attachment" type="button" > <i class="fa fa-download"></i> Download </button>
        				<?php else: ?>
		            	<label class="control-label text-left">&ndash;</label>
	            		<?php endif; ?>
		            </div>
		        </div>
	        	<div class="form-group m-b-10">
	        		<label class="col-sm-5 col-md-4 control-label text-left">Status <span class="pull-right">:</span></label>
		            <div class="col-sm-7 col-md-8">
		            	<label class="control-label text-left">
		            		<?php 
			            		if(strtolower($fs_status) == "approved"){
									$badge = "badge-success";
								}else if(strtolower($fs_status) == "returned"){
									$badge = "badge-warning";
								}else if(strtolower($fs_status) == "rejected"){
									$badge = "badge-danger";
								}else if(strtolower($fs_status) == "fs used"){
									$fs_status = "Justif Used";
									$badge = "badge-info";
								}
								else{
									$badge = "badge-default";
								}
							?>
		            		<div class="badge <?= $badge ?> text-lowercase font-size-12"> <?= ucwords($fs_status) ?> </div>
	            		</label>
		            </div>
		        </div>
	        	<div class="form-group m-b-10">
	        		<label class="col-sm-5 col-md-4 control-label text-left">Last Update <span class="pull-right">:</span></label>
		            <div class="col-sm-7 col-md-8">
		            	<label class="control-label text-left" style="word-break: break-all"><?= $fs_status_desc ?> at <?= $fs_last_update ?></label>
		            	<?php if(isset($fs_approval_remark)): ?>
		            	<br>
		            	<!-- <label class="control-label text-left">&quot;<?= $fs_approval_remark ?>&quot;</label> -->
		            	<?php endif; ?>
		            </div>
		        </div>
	        	<div class="form-group m-b-10">
	        		<label class="col-sm-5 col-md-4 control-label text-left">Comment History <span class="pull-right">:</span></label>
		            <div class="col-sm-7 col-md-8">
            			<?php if($comment_history): ?>
            			<button id="btn-comment" class="btn btn-xs mt-2 btn-info" data-toggle="modal" data-target="#modal-comment" type="button" > <i class="fa fa- fa-comment"></i> Show Comment </button>
        				<?php else: ?>
		            	<label class="control-label text-left">&ndash;</label>
	            		<?php endif; ?>
		            </div>
		        </div>
	        </div>
		</div>
    </form>
  </div>
</div>

<div class="row">
  <div class="white-box boxshadow m-b-0 border-top-only-5 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">Rkap Detail</h5>
    </div>
  </div>
  <div class="white-box boxshadow border-bottom-only-5">
	<div class="row">
		<div id="tbl_search" class="col-md-12 positon-relative">
			<i class="fa fa-search positon-absolute p-5 mt-2 ml-5 pos"></i>
			<input class="input-field px-30 form-control bg-lightgrey input-sm  border-radius-5 mb-10" type="search" placeholder="Search here...">
		</div>
		<div class="col-md-12">
	      <table id="table_data" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small hover w-full">
	        <thead>
	        	<tr>
		          	<th class="text-center">No</th>
					<th class="text-center">Tribe/Usecase</th>
					<th class="text-center">RKAP Name</th>
					<th class="text-center">Program ID</th>
					<th class="text-center">Proc Type</th>
					<th class="text-center">Proc Type Desc</th>
					<th class="text-center">Description</th>
					<th class="text-center">Period Start</th>
					<th class="text-center">Period End</th>
					<th class="text-center">FA RKAP</th>
					<th id="currency_text" class="text-center">Nominal <?= $fs_currency ?></th>
					<th class="text-center">Nominal</th>
		        </tr>
	        </thead>
	      </table>
	    </div>
	</div>
  </div>
</div>

<div class="row">
  <div class="white-box border-radius-5 small">
      <form class="form-horizontal">
        <div class="row">
        	<div class="col-sm-8">
	        	<div class="form-group m-b-10">
	        		<label class="col-sm-5 col-md-3 control-label text-left">Submitter <span class="pull-right">:</span></label>
		            <div class="col-sm-8">
		            	<label class="control-label text-left"><?= $fs_submitter ?> / <?= $fs_jabatan_sub ?></label>
		            </div>
		        </div>
	        	<div class="form-group m-b-10">
	        		<label class="col-sm-5 col-md-3 control-label text-left">Approval <span class="pull-right">:</span></label>
		            <div class="col-sm-7 col-md-7">
		            <?php foreach ($fs_approval as $key => $value):?>
		            	<label class="control-label text-left"><?= $key+1?>. <?= $value['NAME'] ?> / <?= $value['JABATAN'] ?> &nbsp; &nbsp;<?php if($value['STATUS'] == "approved"){ echo "<i class='text-success fa fa-check-circle fa-lg' title='Approved'></i>";}elseif($value['STATUS'] == "returned"){  echo "<i class='text-warning fa fa-arrow-circle-left fa-lg' title='Returned'></i>"; } elseif($value['STATUS'] == "rejected"){  echo "<i class='text-danger fa fa-times-circle fa-lg' title='Rejected'></i>"; } elseif($value['STATUS'] == "request_approve"){  echo "<i class='fa fa-clock-o fa-lg' title='Waiting approval'></i>"; }  ?></label>
		            	<br>
		            <?php endforeach;?>
		            </div>
		            <div class="col-md-2">
		            	
		            </div>
		        </div>
        	</div>
        </div>
      </form>
  </div>
</div>

<?php if($comment_history): ?>
<style>
	table.comment-history tbody td {
	  word-break: break-word;
	  vertical-align: midle;
	}
</style>
<div id="modal-comment" class="modal fade small" tabindex="-3" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h2 class="modal-title text-white font-size-18" id="modal-comment-label">Comment History</h2>
        </div>
        <div class="modal-body">
	        <div class="row">
	        	<div class="col-md-12">
    			  <table class="table comment-history display table-striped table-responsive dataTable w-full">
			        <thead>
			        	<tr>
							<th width="30%">PIC</th>
							<th width="20%">STATUS</th>
							<th width="30%">REMARK</th>
							<th width="20%">ACTION DATE</th>
				        </tr>
			        </thead>
			        <tbody>
				        <?php foreach ($comment_history as $key => $value):
								if($value['STATUS'] == "approved"){
									$badge = "badge-success";
								}else if($value['STATUS'] == "returned"){
									$badge = "badge-warning";
								}else if($value['STATUS'] == "rejected"){
									$badge = "badge-danger";
								}
                       	?>
				        <tr>
				        	<td><?= $value['PIC_NAME'] ?></td>
				        	<td><div class="badge <?= $badge ?> text-lowercase"> <?= ucfirst($value['STATUS']) ?> </div> </td>
				        	<td><?= $value['REMARK'] ?></td>
				        	<td><?= dateFormat($value['UPDATED_DATE'], 'fintool', false) ?></td>
				        </tr>
    					<?php endforeach;?>
			        </tbody>
			      </table>
			    </div>
	        </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger w-100p border-radius-5 btn-outline waves-effect" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
        </div>
    </div>
  </div>
</div>
<?php endif; ?>

<?php if(count($fs_attachment) > 1): ?>
<div id="modal-attachment" class="modal fade small" tabindex="-3" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h2 class="modal-title text-white font-size-18" id="modal-attachment-label">Attachment History</h2>
        </div>
        <div class="modal-body">
	        <div class="row">
	        	<div class="col-md-12">
    			  <table class="table_comment display table-striped table-responsive dataTable w-full">
			        <thead>
			        	<tr>
							<th width="50%">File Name</th>
							<th width="20%">Upload By</th>
							<th width="20%">Date Uploaded</th>
							<th width="10%">Download</th>
				        </tr>
			        </thead>
			        <tbody>
				        <?php foreach ($fs_attachment as $key => $value):?>
				        <tr>
				        	<td><?= $value['FILE_NAME'] ?></td>
				        	<td><?= $value['UPLOADED_BY'] ?></td>
				        	<td><?= dateFormat($value['DATE_UPLOADED'], 'fintool') ?></td>
				        	<td>
				        		<label class="control-label w-full"><a class="btn btn-xs btn-success w-full py-5" title="Click to Download" href="<?= $value['FILE_LINK'] ?>" target="_blank"> <i class="fa fa-download"></i> </a></label>
				        	</td>
				        </tr>
    					<?php endforeach;?>
			        </tbody>
			      </table>
			    </div>
	        </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger w-100p border-radius-5 btn-outline waves-effect" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
        </div>
    </div>
  </div>
</div>
<?php endif; ?>

<script>
  $(document).ready(function(){

	const id_fs = '<?= $id_fs ?>';
	let url     = baseURL + 'feasibility-study/api/load_data_fs_lines';

	Pace.track(function(){
	    $('#table_data').DataTable({
	      "serverSide"      : true,
	      "processing"      : true,
	      "ajax"            : {
									"url"  : url,
									"type" : "POST",
									"dataType": "json",
									"data"    : function ( d ) {
													d.id_fs    = id_fs;
													d.category = 'view';
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
				    			{"data": "rkap_name", "width": "250px" },
				    			{"data": "program_id", "width": "150px" },
				    			{"data": "proc_type", "width": "150px" },
				    			{"data": "proc_desc", "width": "150px" },
				    			{"data": "line_name", "width": "250px" },
				    			{"data": "period_start", "width": "150px" },
				    			{"data": "period_end", "width": "150px" },
				    			{"data": "fund_available", "width": "150px", "class": "text-right"  },
				    			{"data": "nominal_currency", "width": "150px", "class": "text-right"  },
				    			{"data": "nominal", "width": "150px", "class": "text-right"  }
				    		],
			"columnDefs": [
	            {
	                "targets": [<?= ($fs_currency == "IDR" ) ? ", 10" : "" ?> ],
	                "visible": false
	            }
	        ],
        "drawCallback": function ( settings ) {
        },
	      "pageLength"      : 100,
	      "ordering"        : false,
	      "scrollY"         : 480,
	      "scrollX"         : true,
	      "scrollCollapse"  : true,
	      "autoWidth"       : true,
	      "bAutoWidth"      : true
	    });
	});

	let table = $('#table_data').DataTable();
	$('#table_data_filter').remove();
	$('#table_data_length').remove();
	$('#table_data_paginate').remove();

	  $("#tbl_search").on('keyup', "input[type='search']", function(){
	      table.search( $(this).val() ).draw();
	  });
	
	const fs_enc = "<?= $id_fs_enc ?>";

	$("#edit_fs").on('click', function () {
		$(location).attr('href', baseURL + 'feasibility-study/edit/'+fs_enc);
	});

  });
</script>