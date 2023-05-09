<?php if(strtolower($pr_status) == "returned"): ?>
  	<div class="row">
	      <button id="edit_pr" class="btn btn-warning border-radius-5 w-100p m-b-10" type="button"><i class="fa fa-edit"></i> Edit PR</button>
  	</div>
<?php endif; ?>

<?php $this->load->view('ilustration') ?>


<div class="row">
  <div class="white-box boxshadow m-b-0 mt-10 border-top-only-5 z-index-2 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">PR Detail</h5>
    </div>
  </div>
  <div class="white-box boxshadow z-index-2 py-10 border-bottom-only-5 small">
    <div class="row">
    	<form class="form-horizontal">
    	<div class="col-sm-6 col-md-5">
        	<div class="form-group m-b-10">
          	<label class="col-sm-5 col-md-4 control-label text-left">PR Number <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="fs_number" class="control-label"><?= $pr_number ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">PR Name <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="fs_name" class="control-label text-left"><?= $pr_name ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
              <label class="col-sm-5 col-md-4 control-label text-left">Currency<?= ($pr_currency != "IDR") ? "/Rate" : "" ?> <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label id="fs_currency" class="control-label"><?= $pr_currency ?><?= ($pr_currency != "IDR") ? " / ".$pr_rate : "" ?></label>
              </div>
          </div>
        	<div class="form-group m-b-10">
          	<label class="col-sm-5 col-md-4 control-label text-left">Total Amount <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="fs_amount" class="control-label"><?= $pr_amount ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Directorate <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label"><?= get_directorat($pr_directorat) ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Division <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label text-left"><?= get_division($pr_division) ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Unit <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label text-left"><?= get_unit($pr_unit) ?></label>
            </div>
          </div>
          <?php if($id_fs > 0): ?>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Justification  <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label text-left"><?= get_fs($id_fs) ?> - <?= get_fs($id_fs, "FS_NAME") ?> &nbsp; <a class="btn btn-outline btn-xs btn-info" href="<?= $fs_link ?>" target="_blank"> <i class="fa fa-external-link"></i></i> View </a></label>
            </div>
          </div>
          <?php endif; ?>
          <?php if($dpl_number): ?>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">DPL  <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label text-left"><?= $dpl_number ?> &nbsp; <a class="btn btn-outline btn-xs btn-info" href="<?= $dpl_link ?>" target="_blank"> <i class="fa fa-external-link"></i></i> View </a></label>
            </div>
          </div>
          <?php endif; ?>
        </div>
        <div class="col-sm-6 col-md-5">
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">PR Date <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label"><?= $pr_date ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Document <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
              <?php if($pr_document): ?>
              <button class="btn btn-xs mt-2 btn-success" data-toggle="modal" data-target="#modal-attachment" type="button" > <i class="fa fa-download"></i> Download </button>

                <?php else: ?>
                <label class="control-label text-left">&ndash;</label>
                <?php endif; ?>
              </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Status <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label class="control-label text-left"><?= $pr_status ?></label>
              </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Assigned to <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label class="control-label text-left"><?= $status_assign ?></label>
              </div>
          </div>
          <?php if($pr_document_return): ?>
            <div class="form-group m-b-10">
              <label class="col-sm-5 col-md-4 control-label text-left">Return Attachment<span class="pull-right">:</span></label>
                <div class="col-sm-7 col-md-8">
                <a href="<?= $pr_document_return ?>" class="btn btn-xs mt-2 btn-success"> <i class="fa fa-download"></i> Download </a>
                </div>
            </div>
          <?php endif; ?>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Submitter/Jabatan <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label class="control-label text-left"><?= $pr_submitter ?> &ndash; <?= $pr_jabatan_sub ?></label>
              </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Last Update <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label class="control-label text-left"><?= $pr_last_update ?></label>
              </div>
          </div>
    	   <div class="form-group m-b-10">
    		<label class="col-sm-5 col-md-4 control-label text-left">PR History <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
    			<?php if($pr_history): ?>
    			<button id="btn-comment" class="btn btn-xs mt-2 btn-info" data-toggle="modal" data-target="#modal-comment" type="button" > <i class="fa fa- fa-comment"></i> Show History </button>
				<?php else: ?>
            	<label class="control-label text-left">&ndash;</label>
        		<?php endif; ?>
            </div>
        </div>
        </div>
    	</form>
    </div>
  </div>
</div>


<div class="row">
  <div class="white-box boxshadow m-b-0 border-top-only-5 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">PR Lines</h5>
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
        				<th class="text-center">Name</th>
        				<th class="text-center">Fund Available</th>
        				<th class="text-center">Nominal</th>
              </tr>
            </thead>
  		    </table>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="white-box boxshadow m-b-0 border-top-only-5 panel-title-custom">
    <div class="col-md-6">
      <h5 id="table_detail_title" class="font-weight-700 m-0 text-uppercase">PR Lines Detail</h5>
    </div>
  </div>
  <div class="white-box boxshadow border-bottom-only-5">
	<div class="row">
		<div class="col-md-12">
	      <table id="table_detail" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small hover w-full">
	        <thead>
	        	<tr>
		          	<th class="text-center">No</th>
		          	<th class="text-center">Item Name</th>
		          	<th class="text-center">Item Description</th>
		          	<th class="text-center">Category Item</th>
                <th class="text-center">Lines Type</th>
		          	<th class="text-center">Nature</th>
		          	<th class="text-center">Quantity</th>
		          	<th class="text-center">UoM</th>
		          	<th class="text-center">Unit Price</th>
		          	<th class="text-center">Total Price</th>
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
		            	<label class="control-label text-left"><?= $pr_submitter ?> / <?= (strtolower($pr_jabatan_sub) == 'procurement') ? 'Procurement Support' : $pr_jabatan_sub  ?></label>
		            </div>
		        </div>
	        	<div class="form-group m-b-10">
	        		<label class="col-sm-5 col-md-3 control-label text-left">Approval <span class="pull-right">:</span></label>
		            <div class="col-sm-7 col-md-7">
		            <?php foreach ($pr_approval as $key => $value):?>
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

<?php if($pr_document): ?>
<div id="modal-attachment" class="modal fade small" tabindex="-3" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h2 class="modal-title text-white font-size-18" id="modal-attachment-label">PR Document</h2>
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
                  <?php foreach ($pr_document as $key => $value):?>
                  <tr>
                    <td><?= $value['FILE_NAME'] ?></td>
                    <td><?= $value['UPLOADED_BY'] ?></td>
                    <td><?= dateFormat($value['DATE_UPLOADED'], 'fintool') ?></td>
                    <td>
                      <label class="control-label w-full"><a class="btn btn-xs btn-success w-full py-5" title="Click to Download" href="<?= $value['FILE_LINK'] ?>"> <i class="fa fa-download"></i> </a></label>
                    </td>
                  </tr>
                <?php endforeach;?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
                <div class="form-group m-b-10 mt-10">
                    <label for="approval_remark" class="col-sm-3 control-label text-left">Document Checklist <span class="pull-right">:</span></label>
                    <div class="col-sm-9" id="document_list">

                      <?php
                        $arr_doc_list[] = 'Justifikasi';
                        $arr_doc_list[] = 'Program Control Review';
                        $arr_doc_list[] = 'BoQ';
                        $arr_doc_list[] = array('name' => 'DPL',
                                                'key' => 'dpl',
                                                'detail' => array('PL') );
                        $arr_doc_list[] = array('name' => 'RKS',
                                                'key' => 'rks',
                                                'detail' => array('Tender','PL') );
                        $arr_doc_list[] = array('name' => 'MoM Joint Planning Process',
                                                'key' => 'mom',
                                                'detail' => array('PO') );
                        $arr_doc_list[] = array('name' => 'Nodin Pembuatan MPA/Amandemen',
                                                'key' => 'nodin',
                                                'detail' => array('Amd','Tender','PL') );
                      ?>
                      <!-- <i class='text-success fa fa-check-circle fa-lg' title='Approved'></i -->
                        <?php
                          $index = 1;
                          foreach ($arr_doc_list as $key => $value):?>
                          <?php if(is_array($value)): ?>
                              <div class="row">
                                <label class="control-label"><i><?= $value['name'] ?></i> : </label>
                                <?php foreach ($value['detail'] as $vldtl):?>
                                  <?php $valx = $value['key']."_".strtolower($vldtl); ?>
                                  <div class="d-inline-block m-r-20">
                                      <label for="document_list-<?= $index ?>" class="<?= $valx ?>"><strong><?= $vldtl ?> <i class='<?=(in_array($valx, $doc_checklist)) ? 'fa fa-check-circle text-success ':'fa fa-times-circle '?>m-l-5' title='Approved'></i></strong></label>
                                  </div>
                                <?php $index++; ?>
                              <?php endforeach; ?>
                              </div>
                          <?php else: ?>
                              <?php $valx = strtolower(str_replace(" ", "_", $value)); ?>
                              <div class="row">
                                  <label for="document_list-<?= $index ?>"><strong><?= $value ?> <i class='<?=(in_array($valx, $doc_checklist)) ? 'fa fa-check-circle text-success ':'fa fa-times-circle '?>m-l-5' title='Approved'></i></strong></label>                                  
                              </div>
                            <?php $index++; ?>
                          <?php endif; ?>
                        <?php endforeach; ?>
                </div>
              </div>
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



<?php if($pr_history): ?>
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
          <h2 class="modal-title text-white font-size-18" id="modal-comment-label">PR History</h2>
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
		                <?php foreach ($pr_history as $key => $value):
                      $statusHistory = strtolower($value['STATUS']);
		                if($statusHistory == "approved" || $statusHistory == "verified"){
		                  $badge = "badge-success";
		                }else if($statusHistory == "returned"){
		                  $badge = "badge-warning";
		                }else if($statusHistory == "assigned" || $statusHistory == "po created"){
		                  $badge = "badge-info";
		                }else if($statusHistory == "rejected"){
		                  $badge = "badge-danger";
		                }else{
		                  $badge = "badge-default";
		                }
		               ?>
				        <tr>
				        	<td><?= $value['PIC_NAME'] ?></td>
				        	<td><div class="badge <?= $badge ?> text-lowercase"> <?= $statusHistory ?> </div> </td>
				        	<td><?= $value['REMARK'] ?></td>
				        	<td><?= dateFormat($value['ACTION_DATE'], 'fintool', false) ?></td>
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

	const ID_PR       = '<?= $id_pr ?>';
	let status        = $('#status').val();
	
	let url = baseURL + 'purchase-requisition/api/load_data_lines_view';

	Pace.track(function(){
	    $('#table_data').DataTable({
	      "serverSide"      : true,
	      "processing"      : true,
	      "ajax"            : {
									"url"  : url,
									"type" : "POST",
									"dataType": "json",
									"data"    : function ( d ) {
													d.pr_header_id = ID_PR;
													d.category     = 'view';
					                            }
			                    },
	      "language"        : {
	                            "emptyTable"  : "<span class='label label-danger'>Data Not Found!</span>",
	                            "infoEmpty"   : "Empty Data",
	                            "processing"  : '<img src="' + imageLoadingSmall + '" alt="" />',
	                            "search"      : "_INPUT_"
	                          },
	      "columns"         : [
								{"data": "no", "width": "5px", "class": "text-center" },
				    			{"data": "line_name", "width": "150px" },
				    			{"data": "fund_av", "width": "100px", "class": "text-right"  },
				    			{"data": "nominal", "width": "100px", "class": "text-right"  }
				    		],
        "pageLength"      : 500,
	      "ordering"        : false,
	      "scrollY"         : 480,
	      "scrollCollapse"  : true,
	      "scrollX"         : true,
	      "autoWidth"       : true,
	      "bAutoWidth"      : false
	    });
	});

	let table = $('#table_data').DataTable();

	$('#table_data_filter').remove();
	$('#table_data_length').remove();
	$('#table_data_paginate').remove();

	$("#tbl_search").on('keyup', "input[type='search']", function(){
	  table.search( $(this).val() ).draw();
	});

	let url_detail  = baseURL + 'purchase-requisition/api/load_data_details';
	let pr_lines_id = 0;

	Pace.track(function(){
	    $('#table_detail').DataTable({
	      "serverSide"      : true,
	      "processing"      : true,
	      "ajax"            : {
									"url"  : url_detail,
									"type" : "POST",
									"dataType": "json",
									"data"    : function ( d ) {
													d.pr_lines_id = pr_lines_id;
													d.category    = 'view';
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
				    			{"data": "item_desc", "width": "200px" },
				    			{"data": "detail_desc", "width": "200px" },
				    			{"data": "category_item", "width": "200px" },
				    			{"data": "goods_services", "width": "100px" },
                  {"data": "nature", "width": "150px" },
				    			{"data": "quantity", "width": "50px", "class": "text-center"  },
				    			{"data": "uom", "width": "50px", "class": "text-center"  },
				    			{"data": "price", "width": "100px", "class": "text-right"  },
				    			{"data": "nominal", "width": "200px", "class": "text-right" }
				    		],
	      "pageLength"      : 500,
	      "ordering"        : false,
	      "scrollY"         : 480,
	      "scrollX"         : true,
	      "scrollCollapse"  : true,
	      "autoWidth"       : true,
	      "bAutoWidth"      : true
	    });
	});

	let table_detail = $('#table_detail').DataTable();
	$('#table_detail_length').remove()
	$('#table_detail_info').remove()
	$('#table_detail_filter').remove()
	$('#table_detail_paginate').remove()
	
	$('#table_data').on( 'draw.dt', function () {
    	setTimeout(function(){
			let get_data = table.row(0).data();
			pr_lines_id = get_data.pr_lines_id;
			table_detail.draw();
    	}, 500);
	});

	$('#table_data tbody').on( 'click', 'tr', function () {
		if (! $(this).hasClass('selected') ) {
			table.$('tr.selected').removeClass('selected');
			$(this).addClass('selected');
			let data = table.row( this ).data();
			pr_lines_id = data.pr_lines_id;
      is_show = data.is_show;

      if(is_show > 0){
        $("#table_detail_title").html('Detail of '+ data.line_name);
        table_detail.draw();
      }
			
		}
	});

	const pr_enc = "<?= $id_pr_enc ?>";

	$("#edit_pr").on('click', function () {
		$(location).attr('href', baseURL + 'purchase-requisition/edit/'+pr_enc);
	});

  });
</script>