<div class="row">
  <div class="white-box boxshadow">
    <div class="row">
    	<form class="form-horizontal">
    	<div class="col-sm-6 col-md-5">
        	<div class="form-group m-b-10">
          	<label class="col-sm-5 col-md-4 control-label text-left">PR Number <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="pr_number" class="control-label"><?= $pr_number ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">PR Name <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <input type="text" class="form-control" id="pr_name" placeholder="PR Name" autocomplete="off" value="<?= $pr_name ?>">
            </div>
          </div>
          <div class="form-group m-b-10">
              <label class="col-sm-5 col-md-4 control-label text-left">Currency<?= ($pr_currency != "IDR") ? "/Rate" : "" ?> <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label id="pr_currency" class="control-label"><?= $pr_currency ?><?= ($pr_currency != "IDR") ? " / ".$pr_rate : "" ?></label>
              </div>
          </div>
        	<div class="form-group m-b-10">
          	<label class="col-sm-5 col-md-4 control-label text-left">Total Amount <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
            	<input type="text" class="form-control" id="amount" readonly value="<?= $pr_amount ?>">
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
            <label class="col-sm-5 col-md-4 control-label text-left">Justif Number <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label text-left"><?= get_fs($id_fs) ?> - <?= get_fs($id_fs, "FS_NAME") ?></label>
            </div>
          </div>
          <?php endif; ?>
        </div>
        <div class="col-sm-6 col-md-5">
          <div class="form-group m-b-10">
            <label for="pr_date" class="col-sm-5 col-md-4 control-label text-left">PR Date <span class="pull-right">:</span></label>
            <div class="col-sm-9 col-md-6">
				<div class="input-group">
					<input type="text" class="form-control mydatepicker" id="pr_date" placeholder="dd-mm-yyyy" value="<?= $pr_date ?>">
					<span class="input-group-addon"><i class="icon-calender"></i></span>
				</div>
            </div>
          </div>

          <!-- <div class="form-group m-b-10">
          	<label class="col-sm-5 col-md-4 control-label text-left">Attachment <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label"><a id="pr_attachment" href="<?= base_url("uploads/").$pr_attachment ?>" target="_blank">Download</a></label>
            </div>
          </div> -->
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Status <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label class="control-label text-left">Returned</label>
              </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Submitter/Jabatan <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label class="control-label text-left"><?= $pr_submitter ?>/<?= $pr_jabatan_sub ?></label>
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
  <div class="white-box boxshadow">
    <div class="row">
    	<div class="col-md-12">
  			<table id="table_data" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small hover w-full hover" style="font-size:11px !important;">
            <thead>
              <tr>
				<th class="text-center">No</th>
				<!-- <th class="text-center">RKAP Name</th> -->
				<th class="text-center">Name</th>
				<th class="text-center">Fund Available</th>
				<th class="text-center">Nominal</th>
              </tr>
            </thead>
  		    </table>
      </div>
    </div>
	<hr>
	<div class="row">
		<div class="col-md-12">
	      <table id="table_detail" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small w-full">
	        <thead>
	        	<tr>
		          	<th class="text-center">No</th>
		          	<th class="text-center">Item Name</th>
		          	<th class="text-center">Item Description</th>
		          	<th class="text-center">Category Item</th>
		          	<th class="text-center">Lines Type</th>
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
  <div class="white-box">
      	<form class="form-horizontal" id="form-submitter">
	        <div class="row">
	        	<div class="col-md-8">
			        <div class="form-group m-b-10">
			        	<label for="submitter" class="col-sm-3 control-label text-left">Submitter <span class="pull-right">:</span></label>
			        	<div class="col-sm-4">
			                <select class="form-control" id="submitter">
			                  <option value="">-- Pilih --</option>
			                </select>
			            </div>
			            <div class="col-sm-5">
			            	<label class="control-label text-left" id="jabatan_sub"></label>
			            </div>
			        </div>
		  	       
	        	</div>
	        </div>



	      	<div class="row">
	      		<div class="col-md-8">

              <div class="form-group m-b-10">
                <div class="attachment_group">
                  <label class="col-sm-3 control-label text-left">Document <span class="pull-right">:</span></label>
                  <div id="attachment_view" class="col-sm-9">
                    <label class="control-label text-left"><a id="pr_attachment" href="<?= base_url("download/") . encrypt_string("uploads/pr_attachment/".$pr_attachment, true) ?>" title="<?= $pr_attachment ?>" target="_blank"><?= substr($pr_attachment, 0, 25). "..."?> </a> <button type="button" id="change_file" class="btn btn-warning btn-xs m-l-10 px-10 py-2 pull-right"><i class="fa fa-edit"></i> Change </button> </label>
		            </div>
		                  <div id="upload_attachment" class="col-sm-7 d-none">
		                      <div class="fileinput fileinput-new input-group" data-provides="fileinput">
		                          <div id="fileinput" class="form-control input-sm" data-trigger="fileinput"> <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div><span class="input-group-addon btn btn-default btn-file"> <span class="fileinput-new">Select file</span> <span class="fileinput-exists">Change</span>
		                          <input id="attachment" type="file" name="attachment" data-name="pr" accept=".pdf,.zip,.rar"> </span> <a id="fileinput_remove" href="javascript:void(0)" class="input-group-addon btn btn-warning fileinput-exists" data-dismiss="fileinput">Remove</a>
		                      </div>
		                        <div class="progress progress-lg d-none">
		                            <div id="progress" class="progress-bar progress-bar-info active progress-bar-striped" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%" role="progressbar"></div>
		                        </div>
		                        <button type="button" id="cancel_change_file" class="btn btn-danger btn-xs px-5 pull-right"><i class="fa fa-times"></i> Cancel </button>
		            </div>
		                </div>
		                <div class="col-sm-offset-3 col-sm-7">
		                  <!-- <button type="button" id="addFile" class="btn btn-info btn-xs px-5 pull-right"><i class="fa fa-upload"></i> Change </button> -->
		                </div>
		            </div>
		        </div>
	  	    </div>

	      	<div class="row">
	      		<div class="col-md-8">
	              <div class="form-group m-b-10">
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
	                      <?php
	                        $index = 1;
	                        foreach ($arr_doc_list as $key => $value):?>
	                        <?php if(is_array($value)): ?>
		                          <div class="row p-t-5">
		                            <label class="control-label"><i><?= $value['name'] ?></i> : </label>
		                            <?php foreach ($value['detail'] as $vldtl):?>
                                  <?php $valx = $value['key']."_".strtolower($vldtl); ?>
		                              <div class="checkbox checkbox-info d-inline-block m-r-10">
		                                  <input id="document_list-<?= $index ?>" class="document_list" value="<?= $value['key'] ?>_<?= strtolower($vldtl) ?>" type="checkbox"<?=(in_array($valx, $doc_checklist)) ? ' checked':''?>>
		                                  <label for="document_list-<?= $index ?>"> <strong><?= $vldtl ?></strong> </label>
		                              </div>
		                            <?php $index++; ?>
		                          <?php endforeach; ?>
		                          </div>
	                        <?php else: ?>
                              <?php $valx = strtolower(str_replace(" ", "_", $value)); ?>
		                          <div class="checkbox checkbox-info d-inline-block m-r-10 p-t-5">
		                              <input id="document_list-<?= $index ?>" class="document_list" value="<?= $value ?>" type="checkbox"<?=(in_array($valx, $doc_checklist)) ? ' checked':''?>>
		                              <label for="document_list-<?= $index ?>"> <strong><?= $value ?></strong> </label>
		                          </div>
	                          <?php $index++; ?>
	                        <?php endif; ?>
	                      <?php endforeach; ?>
	              </div>
	            </div>
	          </div>
	      	</div>
	        <div class="form-group m-b-0">
	        	<div class="pull-right"><button type="button" id="save_data" class="btn btn-info m-10 w-100p"><i class="fa fa-save"></i> Save </button>
	        	</div>
	        </div>
      	</form>
  </div>
</div>


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
		                if($value['STATUS'] == "approved" || $value['STATUS'] == "verified"){
		                  $badge = "badge-success";
		                }else if($value['STATUS'] == "returned"){
		                  $badge = "badge-warning";
		                }else if($value['STATUS'] == "assigned"){
		                  $badge = "badge-info";
		                }else if($value['STATUS'] == "rejected"){
		                  $badge = "badge-danger";
		                }else{
		                  $badge = "badge-default";
		                }
		               ?>
				        <tr>
				        	<td><?= $value['PIC_NAME'] ?></td>
				        	<td><div class="badge <?= $badge ?> text-lowercase"> <?= ucfirst($value['STATUS']) ?> </div> </td>
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
	const opt_default = '<option value="0" data-name="">-- Choose --</option>';
	const opt_default2  = '<option value="" data-name="">-- Choose --</option>';
	const CURRENCY    = '<?= $pr_currency ?>';
	const rate        = '<?= $pr_rate ?>';

	const directorat  = <?= $pr_directorat ?>;
	const division    = <?= $pr_division ?>;
	const unit        = <?= $pr_unit ?>;
	const submitter   = '<?= $pr_submitter ?>';

	let last_attachment  = "<?= $pr_attachment ?>";
	let attachment_file = last_attachment;
	let attach_category = $('#attachment').data('name');

	getSubmitter();
	
	let url = baseURL + 'purchase-requisition/api/load_data_lines_edit';


	let opt_type = opt_default2;
	opt_type           += '<option value="goods" data-name="">Goods</option>';
	opt_type           += '<option value="services" data-name="">Services</option>';

	let counter        = 1;
	let pr_line_data   = {};

	let buttonAdd = '<button type="button" id="addRow-detail" class="btn btn-success btn-sm m-l-5 mr-5"><i class="fa fa-plus"></i> Add New </button>';
		buttonAdd += '<button type="button" id="deleteRow-detail" class="btn btn-danger btn-sm m-l-5 mr-5" disabled><i class="fa fa-trash"></i> Delete </button>';

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
													d.category     = 'edit';
					                            }
			                    },
	      "language"        : {
	                            "emptyTable"  : "<span class='label label-danger'>Data Not Found!</span>",
	                            "infoEmpty"   : "Empty Data",
	                            "processing"  : '<img src="' + imageLoadingSmall + '" alt="" />',
	                            "search"      : "_INPUT_"
	                          },
	      "columns"         : [
								{"data": "no", "width": "10px", "class": "text-center" },
				    			// {"data": "rkap_name", "width": "250px" },
				    			{"data": "line_name", "width": "150px" },
				    			{"data": "fund_av", "width": "100px", "class": "text-right"  },
				    			{"data": "nominal", "width": "100px", "class": "text-right"  }


				    		],
        "drawCallback": function ( settings ) {
          // $('#table_data_paginate').html('');
        },
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

	$('#table_data_length').html('<h4>PR Lines</h4>');
	$('#table_data_paginate').remove();
	let tracker = 0;

	$('#table_data').on( 'draw.dt', async function () {
		if(tracker == 0){
			await getDataDetail();
		}
		tracker++;
	});

	let rkap_item      = '<div class="form-group m-b-0"><input id="rkap_item-'+counter+'" class="form-control input-sm rkap_item"></div>';
	let rkap_desc      = '<div class="form-group m-b-0"><input id="rkap_desc-'+counter+'" class="form-control input-sm rkap_desc"></div>';
	let category_item_opt     = '<div class="form-group m-b-0"><select id="category_item_opt-'+counter+'" class="form-control input-sm category_item_opt select-center select2">';
			category_item_opt += opt_default;
			category_item_opt += '</select></div>';
	let quantity       = '<div class="form-group m-b-0"><input id="quantity-'+counter+'" data-id="'+counter+'" class="form-control input-sm quantity text-center" value="1" min="1" max="99999" type="number"></div>';
	let uom_opt     = '<div class="form-group m-b-0"><select id="uom_opt-'+counter+'" class="form-control input-sm uom_opt select-center select2">';
			uom_opt += opt_default;
			uom_opt += '</select></div>';

	let type_opt     = '<div class="form-group m-b-0"><select id="type_opt-'+ counter +'" class="form-control input-sm type_opt select-center">';
			type_opt += opt_type;
			type_opt += '</select></div>';
	let price          = '<div class="form-group m-b-0"><input id="price-'+counter+'" data-id="'+counter+'" class="form-control input-sm price text-right money-format" value="0"></div>';
	let nominal_detail = '<div class="form-group m-b-0"><input id="nominal_detail-'+counter+'" class="form-control input-sm nominal_detail text-right" value="0" readonly></div>';

	let table_detail = $('#table_detail').DataTable({
	    "data":[{
					"no": counter,
					"rkap_item": rkap_item,
					"rkap_desc": rkap_desc,
					"category_item": category_item_opt,
					"type": type_opt,
					"quantity": quantity,
					"uom": uom_opt,
					"price": price,
					"nominal_detail": nominal_detail
	    		}],
	    "columns":[
	    			{"data": "no", "width": "10px", "class": "text-center" },
	    			{"data": "rkap_item", "width": "250px", "class": "p-2" },
	    			{"data": "rkap_desc", "width": "250px", "class": "p-2" },
	    			{"data": "category_item", "width": "250px", "class": "p-2" },
	    			{"data": "type", "width": "100px", "class": "p-2" },
	    			{"data": "quantity", "width": "100px", "class": "p-2 text-center" },
	    			{"data": "uom", "width": "150px", "class": "p-2" },
	    			{"data": "price", "width": "150px", "class": "p-2" },
	    			{"data": "nominal_detail", "width": "200px", "class": "p-2" }
	    		],
        "drawCallback": function ( settings ) {
      		// $(".select2").select2();
        },
		"ordering"        : false,
		"scrollY"         : 480,
    "pageLength"      : 500,
    "ordering"        : false,
		"scrollX"         : true,
		"scrollCollapse"  : true,
		"paging" 		  : false
	});

	$('#table_detail_length').html('<h4 id="table_detail_title">PR Detail</h4>')
	$('#table_detail_paginate').remove()
	$('input[type="search"]').attr('placeholder','Search here...').addClass('form-control input-sm m-0');


	$('#table_detail_filter').html(buttonAdd);

	$('#table_data tbody').on( 'click', 'tr', function () {
		console.log(tracker);
		if(tracker == 1){
			table.$('tr.selected').removeClass('selected');
			$(this).addClass('selected');
			let get_table      = table.row( this );
			let index          = get_table.index()
			let index_Num      = index+1;
			let data           = get_table.data();

			total_data = table.data().count();

			$("#deleteRow-detail").attr('disabled', true);
			if(total_data > 1){
				$("#deleteRow").attr('disabled', false);
			}

			let pr_line_key_val = data.pr_line_key;
			let rkapline_val    = data.id_rkap_line;
			
			data_detail = pr_line_data[pr_line_key_val];

			let newDataDtl = [];

			$("#table_detail_title").html("Detail of line " + index_Num);

			data_detail.forEach(function(value, i) {

			    j=i+1;
				
				let rkap_item_val  = '<div class="form-group m-b-0"><input id="rkap_item-'+ j +'" class="form-control input-sm rkap_item" value="'+value.rkap_item+'"></div>';
				let rkap_desc_val  = '<div class="form-group m-b-0"><input id="rkap_desc-'+ j +'" class="form-control input-sm rkap_desc" value="'+value.rkap_desc+'"></div>';
				let category_item_opt     = '<div class="form-group m-b-0"><select id="category_item_opt-'+ j +'" class="form-control input-sm category_item_opt select-center select2">';
						category_item_opt += opt_default;
						category_item_opt += '</select></div>';
				let quantity       = '<div class="form-group m-b-0"><input id="quantity-'+j+'" data-id="'+j+'" class="form-control input-sm quantity text-center" value="'+value.quantity+'" min="1" max="99999" type="number"></div>';
				let uom_opt     = '<div class="form-group m-b-0"><select id="uom_opt-'+ j +'" class="form-control input-sm uom_opt select-center select2">';
						uom_opt += opt_default;
						uom_opt += '</select></div>';
				let type_opt     = '<div class="form-group m-b-0"><select id="type_opt-'+ j +'" class="form-control input-sm type_opt select-center select2">';
						type_opt += opt_type;
						type_opt += '</select></div>';

				let price          = '<div class="form-group m-b-0"><input id="price-'+j+'" data-id="'+j+'" class="form-control input-sm price text-right money-format" value="'+formatNumber(value.price)+'"></div>';
				let nominal_detail = '<div class="form-group m-b-0"><input id="nominal_detail-'+j+'" class="form-control input-sm nominal_detail text-right" value="'+formatNumber(value.nominal_detail)+'" readonly></div>';

				newDataDtl.push({
								"no": j,
								"rkap_desc": rkap_desc_val,
								"rkap_item": rkap_item_val,
								"category_item": category_item_opt,
								"type": type_opt,
								"quantity": quantity,
								"uom": uom_opt,
								"price": price,
								"nominal_detail": nominal_detail
				    		});
				getCategory(j, value.category_item);
				getuom(j, value.uom);
			});

			table_detail.rows().remove().draw();
			table_detail.rows.add(newDataDtl).draw();

			$("#table_detail_title").html("Detail of line " + index_Num);
			tracker++;
		}

	});


	$('#addRow-detail').on( 'click', function () {
		indexNow_detail = table_detail.data().count();

		if($("#rkap_desc-"+indexNow_detail).val() == 0 || $("#nominal_detail-"+indexNow_detail).val() == 0){
			customNotif('Warning', 'Please fill out all field!', 'warning');
		}
		else{

			numDetail = indexNow_detail+1;

			let rkap_desc      = '<div class="form-group m-b-0"><input id="rkap_desc-'+numDetail+'" class="form-control input-sm rkap_desc"></div>';
			let rkap_item      = '<div class="form-group m-b-0"><input id="rkap_item-'+numDetail+'" class="form-control input-sm rkap_item"></div>';
			let category_item_opt     = '<div class="form-group m-b-0"><select id="category_item_opt-'+numDetail+'" class="form-control input-sm category_item_opt select-center select2">';
					category_item_opt += opt_default;
					category_item_opt += '</select></div>';
			let quantity       = '<div class="form-group m-b-0"><input id="quantity-'+numDetail+'" data-id="'+numDetail+'" class="form-control input-sm quantity text-center" value="1" min="1" max="99999" type="number"></div>';
			let uom_opt     = '<div class="form-group m-b-0"><select id="uom_opt-'+numDetail+'" class="form-control input-sm uom_opt select-center select2">';
					uom_opt += opt_default;
					uom_opt += '</select></div>';
			let type_opt     = '<div class="form-group m-b-0"><select id="type_opt-'+numDetail+'" class="form-control input-sm type_opt select-center select2">';
					type_opt += opt_type;
					type_opt += '</select></div>';
			let price          = '<div class="form-group m-b-0"><input id="price-'+numDetail+'" data-id="'+numDetail+'" class="form-control input-sm price text-right money-format" value="0"></div>';
			let nominal_detail = '<div class="form-group m-b-0"><input id="nominal_detail-'+numDetail+'" class="form-control input-sm nominal_detail text-right" value="0" readonly></div>';

		    table_detail.rows.add(
		       [{ 
		       			"no": numDetail,
		    			"rkap_item": rkap_item,
		    			"rkap_desc": rkap_desc,
		    			"category_item": category_item_opt,
		    			"type": type_opt,
		    			"quantity": quantity,
		    			"uom": uom_opt,
		    			"price": price,
		    			"nominal_detail": nominal_detail
	    		}]
		    ).draw();

			let row_now      = $('#table_data tbody tr.selected');
			let get_table    = table.row( row_now );
			let data         = get_table.data();
			let rkapline_val = data.id_rkap_line;

			getCategory(numDetail);
			getuom(numDetail);

		}
	});




	$('#table_detail tbody').on('input change blur', 'tr td input.quantity, tr td input.price', function () {
		if($(this).val().trim().length === 0){
			$(this).val(0);
		}

		index       = $(this).data('id');
		qty_int     = parseInt( $("#quantity-"+index).val() );
		price_int   = parseInt( $("#price-"+index).val().replace(/\./g, '') );
		nominal_val = (qty_int > 0 && price_int > 0) ? qty_int * price_int : 0;

		/*if( CURRENCY != "IDR"){
			rate_val = parseInt(rate.replace(/\./g, '') );
			nominal_currency = (price_int * rate_val) * qty_int ;
			nominal_val = (qty_int > 0 && price_int > 0) ? nominal_currency : 0;
		}*/

		$("#nominal_detail-"+index).val(formatNumber(nominal_val));

		setTimeout(function(){
			getNominal();
			getAmount();
		}, 300);

    });

	$('#table_detail tbody').on('change', 'tr td input, tr td select', function () {
		storeDataDetail();
    });

	$('#table_detail tbody').on( 'click', 'tr', function () {
		if (! $(this).hasClass('selected') ) {
			table_detail.$('tr.selected').removeClass('selected');

			total_data = table_detail.data().count();

			if(total_data > 1){
				$("#deleteRow-detail").attr('disabled', false);
			}
			$(this).addClass('selected');
		}
	});

	$('#deleteRow-detail').on( 'click', function () {

		get_table  = table_detail.row( $('#table_detail tbody tr.selected') );
		index      = get_table.index()

		total_data  = table_detail.data().count();

		if(total_data > 0){
			table_detail.row(index).remove().draw();

			table_detail.column( 0 ).data().each( function ( i ) {
				num = i+1;
				$('#table_detail tbody tr:eq(' + i + ') td:eq(0)').html(num);
				this_row = $('#table_detail tbody tr:eq(' + i + ')');
				this_row.find("input.rkap_desc").attr("id", 'rkap_desc-'+num);
				this_row.find("select.category_item_opt").attr("id", 'category_item_opt-'+num);
				this_row.find("select.type_opt").attr("id", 'type_opt-'+num);
				this_row.find("select.uom_opt").attr("id", 'uom_opt-'+num);
				this_row.find("input.quantity").attr("id", 'quantity-'+num);
				this_row.find("input.quantity").attr('data-id', num);
				this_row.find("input.price").attr("id", 'price-'+num);
				this_row.find("input.price").attr('data-id', num);
				this_row.find("input.nominal_detail").attr("id", 'nominal_detail-'+num).data('id', num);

			});

			$(this).attr('disabled', true);

			getNominal();
			getAmount();
			storeDataDetail();
		}

    });



    $('#pr_date').datepicker({
		format: 'dd-mm-yyyy',
		todayHighlight:'TRUE',
		autoclose: true,
    });


    $("#save_data").on('click', function () {

		getAmount();
		
		let pr_name      = $("#pr_name").val();
		let pr_date      = $("#pr_date").val();
		let submitter    = $("#submitter").val();
		let jabatan_sub  = $("#jabatan_sub").html();
		let amount       = parseInt($("#amount").val().replace(/\./g, ''));
		let total_data   = table.data().count();
		let fatalNominal = false;
		let notice       = '';

	    for (var i = 1; i <= total_data; i++) {
			fund_av       = $("#fund_av-"+i).val();
			nominal_val   = $("#nominal-"+i).val();

			if( parseInt(nominal_val.replace(/\./g, '')) < 1){
				fatalNominal =  true;
				notice       = 'Nilai nominal masih kosong pada data ke '+i;
				break;
			}
			if( parseInt(nominal_val.replace(/\./g, '')) > parseInt(fund_av.replace(/\./g, '')) ){
				fatalNominal =  true;
				notice       = 'Nilai nominal lebih dari Fund Available pada data ke '+i;
				break;
			}

			if( CURRENCY != "IDR"){
				rate_val = parseInt(rate.replace(/\./g, '') );
				convert_rp = parseInt(nominal_val.replace(/\./g, '')) * rate_val;

				if(convert_rp >  parseInt(fund_av.replace(/\./g, ''))){
					fatalNominal =  true;
					notice       = 'Nilai nominal lebih dari Fund Available pada data ke '+i;
					break;
				}
			}
	    }

	    let doc_list = [];
	    $('#document_list input[type=checkbox]').each(function() {
	       if ($(this).is(":checked")) {
	        valDoc = ($(this).val() != '') ? $(this).val() : $("#others_doc").val();
	        if(valDoc != ''){
	          doc_list.push(valDoc);
	        }
	       }
	    });

		if(pr_name == "" || amount == 0){
    		customNotif('Warning', "Please fill all field", 'warning');
		}
		else if (attachment_file == "")
		{
			customNotif('Warning', 'Please upload document', 'warning');
			return false;
		}
		else if (fatalNominal == true)
		{
			customNotif('Warning', notice, 'warning');
			return false;
		}
		else{

			let data_lines  = [];
			table.rows().eq(0).each( function ( index ) {
		    	j = index+1;
			    data = table.row( index ).data();

				fund_av         = $("#fund_av-"+j).val();
				line_name     = $("#line_name-"+j).val();
				nominal_val     = $("#nominal-"+j).val();

				rkap_val        = data.id_rkap;
				
				
				pr_lines_id     = data.pr_lines_id;
				pr_line_key_val = data.pr_line_key;
				data_detail     = pr_line_data[pr_line_key_val];

				if(parseInt(fund_av.replace(/\./g, '')) > 0 && parseInt(nominal_val.replace(/\./g, '')) > 0){
		    		data_lines.push({'id_rkap' : rkap_val, 'line_name' : line_name, 'pr_lines_id' : pr_lines_id, 'nominal' : parseInt(nominal_val.replace(/\./g, '')), 'detail_data' : data_detail});
				}
			});

		    data = {
						pr_header_id : ID_PR,
						directorat : directorat,
						division : division,
						pr_name : pr_name,
						pr_date : pr_date,
						amount : amount,
						submitter : submitter,
						jabatan_sub : jabatan_sub,
						doc_list : doc_list,
						attachment : attachment_file,
						data_line : data_lines
		    		}
				$(this).attr('disabled', true);

		    $.ajax({
		        url   : baseURL + 'purchase-requisition/api/save_pr_edit_v2',
		        type  : "POST",
		        data  : data,
		        dataType: "json",
		        beforeSend  : function(){
                          customLoading('show');
                        },
		        success : function(result){
		        	console.log(result)
		        	if(result.status == true){
		        		customNotif('Success', "PR Updated", 'success');
		        		setTimeout(function(){
		        			customLoading('hide');
		        			$(location).attr('href', baseURL + 'purchase-requisition');
		        		}, 500);
		        	}
		        	else{
								$("#save_data").removeAttr('disabled');
		        		customLoading('hide');
		        		customNotif('Error', result.messages, 'error');
		        	}
		        }
		    });
		}
	});

    $("#submitter").on("change", function(){
		getJabatan("submitter");
    });

    function getNominal(){
		let total_detail = table_detail.data().count();
		let totalNominal = 0;

		for (var i = 1; i <= total_detail; i++) {
			nominal_detail_val = $("#nominal_detail-"+i).val();
			totalNominal += parseInt(nominal_detail_val.replace(/\./g, ''));
	    }

	    $("#table_data tbody tr.selected").find("input.nominal").val(formatNumber(totalNominal));
    }

    function getAmount() {
		let total_data  = table.data().count();
		let totalAmount = 0;

		for (var i = 1; i <= total_data; i++) {
			get_nominal = $("#nominal-"+i).val();
			totalAmount += parseInt(get_nominal.replace(/\./g, ''));
	    }
		$("#amount").val(formatNumber(totalAmount));
	}

	function getCategory(id_row, id_select=0) {

		$("#category_item_opt-"+id_row).attr('disabled', true);
		$("#category_item_opt-"+id_row).css('cursor', 'wait');

	    $.ajax({
	        url   : baseURL + 'api-budget/load_category_item',
	        type  : "POST",
	        data  : {},
	        dataType: "json",
	        success : function(result){
				let category_item_opt = opt_default;
				let val_category = '';
	        	if(result.status == true){
					data = result.data;
					if(data.length > 1){
			        	for(var i = 0; i < data.length; i++) {
							obj = data[i];
							let selected   = '';
						    if(id_select == obj.category_item){
						    	val_category = obj.category_item;
						    	selected = ' selected';
						    }
						    category_item_opt += '<option value="'+ obj.category_item +'"'+selected+'>'+ obj.category_item +'</option>';
						}
					}
	        	}
				$("#category_item_opt-"+id_row).html(category_item_opt);
				$("#category_item_opt-"+id_row).attr('disabled', false);
				$("#category_item_opt-"+id_row).css('cursor', 'default');
      			$("#category_item_opt-"+id_row).select2();
				if(val_category != ""){
					$("#category_item_opt-"+id_row).val(val_category).trigger('change.select2');
				}
	        }
	    });
	}

	function getuom(id_row, id_select=0) {

		$("#uom_opt-"+id_row).attr('disabled', true);
		$("#uom_opt-"+id_row).css('cursor', 'wait');

	    $.ajax({
	        url   : baseURL + 'api-budget/load_uom',
	        type  : "POST",
	        data  : {},
	        dataType: "json",
	        success : function(result){
				let uom_opt = opt_default;
				let val_category = '';
	        	if(result.status == true){
					data = result.data;
					if(data.length > 1){
			        	for(var i = 0; i < data.length; i++) {
							obj = data[i];
							let selected   = '';
						    if(id_select == obj.uom){
						    	val_category = obj.uom;
						    	selected = ' selected';
						    }
						    uom_opt += '<option value="'+ obj.uom +'"'+selected+'>'+ obj.uom +'</option>';
						}
					}
	        	}
				$("#uom_opt-"+id_row).html(uom_opt);
				$("#uom_opt-"+id_row).attr('disabled', false);
				$("#uom_opt-"+id_row).css('cursor', 'default');
      			$("#uom_opt-"+id_row).select2();
				if(val_category != ""){
					$("#uom_opt-"+id_row).val(val_category).trigger('change.select2');
				}
	        }
	    });
	}


	function getDataDetail(){

		table.rows().eq(0).each( function ( index ) {
			let row         = table.row( index );
			let data        = row.data();
			let pr_lines_id = data.pr_lines_id;
			let pr_line_key = data.pr_line_key;

		    $.ajax({
		        url   : baseURL + 'purchase-requisition/api/load_pr_detail_for_edit',
		        type  : "POST",
		        data  : {pr_lines_id : pr_lines_id},
		        dataType: "json",
		        success : function(result){
					let newData = [];
					result.forEach(function(value, i) {
							newData.push({
								"no": value.no,
								"rkap_item": value.rkap_item,
								"rkap_desc": value.rkap_desc,
								"category_item": value.category_item,
								"type": value.type,
								"quantity": value.quantity,
								"uom": value.uom,
								"price": value.price,
								"nominal_detail": value.nominal_detail
						});
					});

					pr_line_data[pr_line_key] = newData;
		        }
		    });

		});

		setTimeout(function(){
			$('#table_data tbody tr').eq(0).trigger('click');
		}, 1000);
	}

	function storeDataDetail(){

		row_now = $('#table_data tbody tr.selected');
		let get_table      = table.row( row_now );
		let index          = get_table.index()
		let index_Num      = index+1;
		let data           = get_table.data();

		pr_line_key = data.pr_line_key;

		let total_detail = table_detail.data().count();
		let detail_data  = [];

		for (var i = 1; i <= total_detail; i++) {
			x=i-1;
			data         = table_detail.row( x ).data();
			pr_detail_id = data.pr_detail_id;

			rkap_item_val      = $("#rkap_item-"+i).val();
			rkap_desc_val      = $("#rkap_desc-"+i).val();
			category_item_val  = $("#category_item_opt-"+i).val();
			quantity_val       = $("#quantity-"+i).val();
			uom_val       	   = $("#uom_opt-"+i).val();
			type_val       	   = $("#type_opt-"+i).val();
			price_val          = $("#price-"+i).val();
			nominal_detail_val = $("#nominal_detail-"+i).val();

			if(rkap_desc_val != "" && parseInt(price_val.replace(/\./g, '')) > 0){
    			detail_data.push({'rkap_item' : rkap_item_val, 'rkap_desc' : rkap_desc_val, 'category_item' : category_item_val, 'quantity' : quantity_val, 'type' : type_val, 'uom' : uom_val, 'price' : parseInt(price_val.replace(/\./g, '')), 'nominal_detail' : parseInt(nominal_detail_val.replace(/\./g, ''))});
			}
	    }

	    pr_line_data[pr_line_key] = detail_data;

	    console.log(pr_line_data);

	}

    function getSubmitter(){

	    $.ajax({
	        url   : baseURL + 'feasibility-study/api/load_data_submitter',
	        type  : "POST",
	        data  : {directorat: directorat, division: division, unit: unit},
	        dataType: "json",
	        success : function(result){      
        		let submitter_opt = '';
	        	if(result.status == true){
					data = result.data;
					if(data.length == 1){
						for(var i = 0; i < data.length; i++) {
						    obj = data[i];
						    // submitter_opt += '<option value="'+ obj.nama +'" data-jabatan="'+ obj.jabatan +'">'+ obj.nama +'</option>';
						    selected = (submitter === obj.nama) ? ' selected': '';
						    submitter_opt += '<option value="'+ obj.nama +'" data-jabatan="'+ obj.jabatan +'" '+ selected +'>'+ obj.nama +'</option>';
						}
					}else{
						submitter_opt = opt_default;
						for(var i = 0; i < data.length; i++) {
						    obj = data[i];
						    // submitter_opt += '<option value="'+ obj.nama +'" data-jabatan="'+ obj.jabatan +'">'+ obj.nama +'</option>';
						    selected = (submitter === obj.nama) ? ' selected': '';
						    submitter_opt += '<option value="'+ obj.nama +'" data-jabatan="'+ obj.jabatan +'" '+ selected +'>'+ obj.nama +'</option>';
						}
					}
		        	
	        	}
				$("#submitter").html(submitter_opt);				
				getJabatan("submitter");
	        }
	    });
	}

	function getJabatan(category){
		if(category == "submitter"){
			valJabtan = $("#submitter").find(':selected').attr('data-jabatan');
			$("#jabatan_sub").html(valJabtan);
		}
	}


	 $('#change_file').on( 'click', function () {
    $("#attachment_view").addClass("d-none");
    $("#upload_attachment").removeClass("d-none");
    attachment_file = '';
  });
  
  $('#cancel_change_file').on( 'click', function () {
    $("#upload_attachment").addClass("d-none");
    $("#attachment_view").removeClass("d-none");
    attachment_file = last_attachment;
  });

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

          if(fileSize > 31000){
            upload = false;
            alert('Max file size 30M')
            $(this).val(lastValue);
          }

          extension_allow = ['pdf','zip','rar'];
          extension       = fileName.split('.').pop().toLowerCase();
          if (extension_allow.indexOf(extension) < 0) {
        upload = false;
        alert('Extention not allowed');
        $(this).val(lastValue);
          }

      if(upload){
        $("#progress").parent().removeClass('d-none');
            
        file     = $('#attachment')[0].files[0];
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
          data  : {file: attachment_file, category:attach_category},
          dataType: "json",
          success : function(result){
            attachment_file = '';
            return true;
          }
      });

      return true;
  }

  });
</script>