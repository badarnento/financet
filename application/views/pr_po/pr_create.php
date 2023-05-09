<?php $this->load->view('ilustration') ?>

<div class="row">
  <div class="white-box boxshadow m-b-0 mt-10 border-top-only-5 z-index-2 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">PR Detail</h5>
    </div>
  </div>
  <div class="white-box boxshadow z-index-2 py-10 border-bottom-only-5 small">
	<form class="form-horizontal">
    <div class="row">
		<div class="col-sm-7">
			<div class="form-group m-b-10">
	            <label for="pr_name" class="col-sm-3 control-label text-left">PR Name <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	              <input type="text" class="form-control input-sm" id="pr_name" placeholder="PR Name" autocomplete="off">
	            </div>
	        </div>
	        <div class="form-group m-b-10">
	            <label for="directorat" class="col-sm-3 control-label text-left">Directorate <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	            	<select class="form-control input-sm" id="directorat">
	            		<?php
							$id_directorat   = $this->session->userdata('id_dir_code');
							$directorat_name = get_directorat($id_directorat);

	            			if($id_directorat > 0 && $binding == true):
            					echo '<option value="'.$id_directorat.'" data-name="'.$directorat_name.'">'.$directorat_name.'</option>';
	            			else:

	            				$opt_dir = '<option value="0">-- Choose --</option>';

	            				$last_dir = "";

	            				foreach($directorat as $value):

									$id_dir_code = $value['ID_DIR_CODE'];
									$dir_name  = $value['DIRECTORAT_NAME'];

									if($dir_name != $last_dir):

	            						$opt_dir .= '<option value="'.$id_dir_code.'" data-name="'.$dir_name.'">'.$dir_name.'</option>';
	            					endif;
	            					$last_dir = $dir_name;

	            				endforeach;

	            				echo $opt_dir;

	            			endif;

	            		?>
	            	</select>
	            </div>
	        </div>
	        <div class="form-group m-b-10">
	            <label for="division" class="col-sm-3 control-label text-left">Division <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	            	<select class="form-control input-sm" id="division">
			          <?php
			            if($binding == false || $data_binding['division'] == false):
			              echo '<option value="0">-- Choose --</option>';
			            else:
			            
			              foreach($data_binding['division'] as $value):
			                $replace = str_replace("&","|AND|", $value['DIVISION_NAME']);
			          ?>
			              <option value="<?= $value['ID_DIVISION'] ?>" data-name="<?= $value['DIVISION_NAME'] ?>"><?= $value['DIVISION_NAME'] ?></option>
			          <?php
			              endforeach; 
			            endif;
			          ?>
		            </select>
	            </div>
	        </div>
	        <div class="form-group m-b-10">
	            <label for="unit" class="col-sm-3 control-label text-left">Unit <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	            	<select class="form-control input-sm" id="unit">
			            <?php
			              if($binding == false || $data_binding['unit'] == false):
			                echo '<option value="0">-- Choose --</option>';
			              else:
			              
			                foreach($data_binding['unit'] as $value):
			                  $replace = str_replace("&","|AND|", $value['UNIT_NAME']);
			            ?>
			                <option value="<?= $value['ID_UNIT'] ?>" data-unt="<?= $replace ?>" data-name="<?= $value['UNIT_NAME'] ?>"><?= $value['UNIT_NAME'] ?></option>
			            <?php
			                endforeach; 
			              endif;
			            ?>
		            </select>
	            </div>
	        </div>
	        <div class="form-group m-b-10">
	            <label for="fs_header" class="col-sm-3 control-label text-left">Justification <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	            	<select class="form-control input-sm" id="fs_header">
	            		<option value="0">-- Choose --</option>
		            </select>
	            </div>
	        </div>
	        <div class="form-group m-b-10 d-none" id="dpl_group">
	            <label for="dpl" class="col-sm-3 control-label text-left">No DPL <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	            	<select class="form-control input-sm" id="dpl">
	            		<option value="0">-- Choose --</option>
		            </select>
	            </div>
	        </div>
	    </div>
	    <div class="col-sm-5">
	    	<div class="form-group m-b-10">
	          	<label for="pr_date" class="col-sm-3 control-label text-left">PR Date <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
					<div class="input-group">
						<input type="text" class="form-control input-sm mydatepicker" id="pr_date" placeholder="dd/mm/yyyy" value="<?= date("d-m-Y")?>">
						<span class="input-group-addon"><i class="icon-calender"></i></span>
					</div>
	            </div>
	        </div>
	        
	        <div class="form-group m-b-10">
	          	<label for="delivery_date" class="col-sm-3 control-label text-left">Delivery Date <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
					<div class="input-group">
						<input type="text" class="form-control input-sm mydatepicker" id="delivery_date" placeholder="dd/mm/yyyy" value="<?= date("d-m-Y")?>">
						<span class="input-group-addon"><i class="icon-calender"></i></span>
					</div>
	            </div>
	        </div>
	        <div class="form-group m-b-10">
	            <label for="dpl" class="col-sm-3 control-label text-left">Delivery Location <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	            	<select class="form-control" id="delivery_location">
	            <option value="0">--Choose--</option>
	              <?php 
	                foreach($get_location as $value):
	                  echo "<option value='".$value['LOCATION']."'>".$value['LOCATION']."</option>";
	                endforeach
	              ?>
	          		</select>
	            </div>
	        </div>
	        <div class="form-group m-b-10">
	            <label for="currency" class="col-sm-3 control-label text-left">Currency <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	              <input type="text" class="form-control input-sm" id="currency" value="IDR" readonly>
	            </div>
	        </div>
			<div id="currency_rate" class="form-group m-b-10 d-none">
	            <label for="rate" class="col-sm-3 control-label text-left">Rate <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	              <input type="text" class="form-control input-sm" id="rate" placeholder="Rate" value="0" readonly="">
	            </div>
	        </div>
	        <div class="form-group m-b-10">
	            <label for="amount" class="col-sm-3 control-label text-left">Total Amount <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	              <input type="text" class="form-control input-sm" id="amount" value="0" readonly>
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
      <h5 class="font-weight-700 m-0 text-uppercase">PR Lines</h5>
    </div>
  </div>
  <div class="white-box boxshadow border-bottom-only-5">
	<div class="row">
		<div class="col-md-12">
	      <table id="table_data" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small w-full">
	        <thead>
	        	<tr>
		          	<th class="text-center">Justif Name</th>
		          	<th class="text-center">Description</th>
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
      <h5 class="font-weight-700 m-0 text-uppercase">Detail of line</h5>
    </div>
  </div>
  <div class="white-box boxshadow border-bottom-only-5">
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
      	<form class="form-horizontal" id="form-submitter">
	        <div class="row">
	        	<div class="col-md-8">
			        <div class="form-group m-b-10">
			        	<label for="submitter" class="col-sm-3 control-label text-left">Submitter <span class="pull-right">:</span></label>
			        	<div class="col-sm-4">
			                <select class="form-control input-sm" id="submitter">
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
			  	        	<label class="col-sm-3 control-label text-left">Document<span class="pull-right">:</span></label>
				            <div class="col-sm-7">
				                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
				                    <div id="fileinput" class="form-control input-sm" data-trigger="fileinput"> <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div><span class="input-group-addon btn btn-default btn-outline btn-file"> <span class="fileinput-new">Select file</span> <span class="fileinput-exists">Change</span>
				                    <input id="attachment" type="file" name="attachment" data-name="pr" accept=".pdf,.zip,.rar"> </span> <a id="fileinput_remove" href="javascript:void(0)" class="input-group-addon btn btn-warning fileinput-exists" data-dismiss="fileinput">Remove</a>
				                </div>
			                    <div class="progress progress-lg d-none">
			                        <div id="progress" class="progress-bar progress-bar-info active progress-bar-striped" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%" role="progressbar"></div>
			                    </div>
							</div>
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
		                              <div class="checkbox checkbox-info d-inline-block m-r-10">
		                                  <input id="document_list-<?= $index ?>" class="document_list" value="<?= $value['key'] ?>_<?= strtolower($vldtl) ?>" type="checkbox">
		                                  <label for="document_list-<?= $index ?>"> <strong><?= $vldtl ?></strong> </label>
		                              </div>
		                            <?php $index++; ?>
		                          <?php endforeach; ?>
		                          </div>
	                        <?php else: ?>
		                          <div class="checkbox checkbox-info d-inline-block m-r-10 p-t-5">
		                              <input id="document_list-<?= $index ?>" class="document_list" value="<?= $value ?>" type="checkbox">
		                              <label for="document_list-<?= $index ?>"> <strong><?= $value ?></strong> </label>
		                          </div>
	                          <?php $index++; ?>
	                        <?php endif; ?>
	                      <?php endforeach; ?>
	              </div>
	            </div>
	          </div>
	      	</div>
	      	<div class="row">
	      		<div class="col-sm-6 col-sm-offset-6">
			        <div class="form-group pull-right">
			        	<button type="button" id="save_data" class="btn btn-info border-radius-5 m-10 w-150p"><i class="fa fa-save"></i> Save </button>
			        </div>
	      		</div>
	      	</div>
      	</form>
  </div>
</div>

<script>
  $(document).ready(function(){

	const opt_default  = '<option value="0" data-name="">-- Choose --</option>';
	const opt_default2  = '<option value="" data-name="">-- Choose --</option>';

/*	const is_bod = <?= ($this->session->userdata('is_bod')) ? 'true' : 'false' ?>;
	
	if(is_bod == true){
		getDivision();
	}*/

	const binding = <?= ($binding) ? "'".$binding."'" : 'false' ?>;
	if(binding != false){
	    if(binding == 'directorat'){
	      getDivision();
	    }
	    if(binding == 'division'){
	      getUnit();
	    }
        if(binding == 'unit'){
          setTimeout(function(){
  			getFS();
			getSubmitter();
          }, 1000);
        }

	}
	let attachment_file = "";
    let attach_category = $('#attachment').data('name');
	let pr_line_data = {};
	let id_fs        = $("#fs_header").val();
	let url          = baseURL + 'purchase-requisition/api/load_data_fs';

	Pace.track(function(){
	    $('#table_data').DataTable({
	      "serverSide"      : true,
	      "processing"      : true,
	      "ajax"            : {
									"url"  : url,
									"type" : "POST",
									"dataType": "json",
									"data"    : function ( d ) {
					                              d.id_fs = id_fs;
					                            }
			                    },
	      "language"        : {
	                            "emptyTable"  : "<span class='label label-danger'>Data Not Found!</span>",
	                            "infoEmpty"   : "Empty record",
	                            "processing"  : '<img src="' + imageLoadingSmall + '" alt="" />',
	                            "search"      : "_INPUT_"
	                          },
	      "columns"         : [
								{"data": "fs_name", "width": "200px", "class": "p-5" },
								{"data": "line_name", "width": "200px", "class": "p-5" },
								{"data": "fund_av", "width": "120px", "class": "p-5" },
								{"data": "nominal", "width": "120px"}
				    		],
          "drawCallback": function ( settings ) {
          	if(id_fs > 0){
	            let row_datas   = this.api().rows({ page: 'current' }).data();
	            row_datas.each(function (data, i) {
	            	pr_line_random = data.pr_line_key;
				    detail_data = [{'rkap_item' : '', 'rkap_desc' : '', 'category_item': '', 'type': '', 'nature' : 0, 'quantity' : 1, 'uom' : '', 'price' : 0, 'nominal' : 0}];
				    pr_line_data[pr_line_random] = detail_data;
	            });

      			setTimeout(function(){
	          		$('#table_data tbody tr').eq(0).trigger('click');
				}, 100);
          	}else{
				table_detail.rows().remove().draw();
          	}
          },
	      "ordering"        : false,
	      "pageLength"      : 500,
	      "scrollY"         : 480,
	      "scrollX"         : true,
	      "scrollCollapse"  : true,
	      "autoWidth"       : true,
	      "bAutoWidth"      : true,
	       "info": false
	    });
	});

	let table = $('#table_data').DataTable();
	$('#table_data_length').remove();
	$('#table_data_paginate').remove();
	$('#table_data_filter').remove();
	$('#table_detail_paginate').remove();

	let buttonAdd = '<button type="button" id="addRow-detail" class="btn btn-success btn-sm m-l-5 mr-5"><i class="fa fa-plus"></i> Add New </button>';
		buttonAdd += '<button type="button" id="deleteRow-detail" class="btn btn-danger btn-sm m-l-5 mr-5" disabled><i class="fa fa-trash"></i> Delete </button>';

		let data_call         = 1;
		let uom_opt           = opt_default2;
		let category_item_opt = opt_default2;
		let type_opt = opt_default2;
		type_opt           += '<option value="goods" data-name="">Goods</option>';
		type_opt           += '<option value="services" data-name="">Services</option>';

    let table_detail = $('#table_detail').DataTable({
	    "data":[],
	    "columns":[
	    			{"data": "no", "width": "10px", "class": "text-center" },
	    			{"data": "rkap_item", "width": "200", "class": "p-2" },
	    			{"data": "rkap_desc", "width": "200", "class": "p-2" },
	    			{"data": "category_item", "width": "200", "class": "p-2" },
	    			{"data": "type", "width": "100", "class": "p-2" },
	    			{"data": "nature", "width": "150px", "class": "p-2 hidden" },
	    			{"data": "quantity", "width": "70px", "class": "p-2 text-center" },
	    			{"data": "uom", "width": "150px", "class": "p-2 text-center" },
	    			{"data": "price", "width": "120px", "class": "p-2" },
	    			{"data": "nominal_detail", "width": "150px", "class": "p-2" }
	    		],
		      "language"        : {
		                            "emptyTable"  : "<span class='label label-danger'>Data Not Found!</span>",
		                            "infoEmpty"   : "Empty record",
		                            "processing"  : '<img src="' + imageLoadingSmall + '" alt="" />',
		                            "search"      : "_INPUT_"
		                          },
          "drawCallback": function ( settings ) {
          	if(data_call == 1){
          		uom_opt = getUom();
          		category_item_opt = getCategoryItem();
          		data_call++;
          	}
          },
					"ordering"        : false,
					"scrollY"         : 480,
					"scrollX"         : true,
					"scrollCollapse"  : true,
				    "pageLength"      : 500,
					// "paging" 		  : false
	});

	$('#table_detail_filter').html(buttonAdd);
	$('#table_detail_length').remove();
	$('#table_detail_paginate').remove();

	$('#table_data tbody').on( 'click', 'tr', function () {
		if (! $(this).hasClass('selected') ) {
			table.$('tr.selected').removeClass('selected');
			let get_table = table.row( this );
			let index     = get_table.index()
			let index_Num = index+1;
			let data      = get_table.data();

			total_data = table.data().count();
			if(total_data > 0){
				$(this).addClass('selected');

				$("#deleteRow-detail").attr('disabled', true);

				let pr_line_key_val = data.pr_line_key;
				let id_rkap = data.id_rkap;
				let id_rkap_line = data.id_rkap_line;

				data_detail = pr_line_data[pr_line_key_val];

				let newData = [];

				$("#table_detail_title").html("Detail of line " + index_Num);

				data_detail.forEach(function(value, i) {

				    j=i+1;
					
					let rkap_item_val  = '<div class="form-group m-b-0"><input id="rkap_item-'+ j +'" class="form-control input-sm rkap_item" value="'+value.rkap_item+'" autocomplete="off"></div>';
					let rkap_desc_val  = '<div class="form-group m-b-0"><input id="rkap_desc-'+ j +'" class="form-control input-sm rkap_desc" value="'+value.rkap_desc+'" autocomplete="off"></div>';
					let category_item     = '<div class="form-group m-b-0"><select id="category_item_opt-'+ j +'" data-id="'+j+'" class="form-control input-sm category_item_opt select-center select2">';
							category_item += category_item_opt;
							category_item += '</select></div>';
					let nature_hidden     = '<div class="form-group m-b-0"><input id="nature_hidden-'+ j +'" class="form-control input-sm nature_hidden" value=""></div>';
					let nature_opt     = '<div class="form-group m-b-0"><select id="nature_opt-'+ j +'" class="form-control input-sm nature_opt select-center">';
							nature_opt += opt_default;
							nature_opt += '</select></div>';
					let quantity       = '<div class="form-group m-b-0"><input id="quantity-'+j+'" data-id="'+j+'" class="form-control input-sm quantity text-center" value="'+value.quantity+'" min="1" max="99999" type="number"></div>';
					// let uom  = '<div class="form-group m-b-0"><input id="uom-'+ j +'" class="form-control input-sm uom" value="'+value.uom+'" autocomplete="off"></div>';
					let uom     = '<div class="form-group m-b-0"><select id="uom_opt-'+ j +'" class="form-control input-sm uom_opt select-center">';
							uom += uom_opt;
							uom += '</select></div>';
					let type     = '<div class="form-group m-b-0"><select id="type_opt-'+ j +'" class="form-control input-sm type_opt select-center">';
							type += type_opt;
							type += '</select></div>';
					let price          = '<div class="form-group m-b-0"><input id="price-'+j+'" data-id="'+j+'" class="form-control input-sm price text-right money-format" value="' + formatNumber(value.price) + '"></div>';
					let nominal_detail = '<div class="form-group m-b-0"><input id="nominal_detail-'+j+'" class="form-control input-sm nominal_detail text-right" value="'+formatNumber(value.nominal)+'" readonly></div>';
					newData.push({
									"pr_line_key": pr_line_key_val,
									"no": j,
									"rkap_item": rkap_item_val,
									"rkap_desc": rkap_desc_val,
									"category_item": category_item,
									"type": type,
									"nature": nature_hidden,
									// "nature": nature_opt,
									"quantity": quantity,
									"uom": uom,
									"price": price,
									"nominal_detail": nominal_detail
					    		});
					getNature(j, id_rkap_line, value.nature);
					// getCategoryItem(j, value.category_item);
					// getUom(j, value.uom);

					setTimeout(function(){
						$("#category_item_opt-"+j).select2();
						$("#uom_opt-"+j).select2();
					}, 300);
				});

				table_detail.rows().remove().draw();
				table_detail.rows.add(newData).draw();

				setTimeout(function(){
					getNominal();
					getAmount();
				}, 300);
			}
		}

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

	$('#addRow-detail').on( 'click', function () {
		indexNow_detail = table_detail.data().count();

		if($("#rkap_item-"+indexNow_detail).val() == "" || $("#nature_opt-"+indexNow_detail).val() == 0 || $("#category_item_opt-"+indexNow_detail).val() == "" || $("#uom_opt-"+indexNow_detail).val() == "" || $("#type_opt-"+indexNow_detail).val() == "" || $("#nominal_detail-"+indexNow_detail).val() == 0){
			customNotif('Warning', 'Please fill out all field!', 'warning');
		}
		else{

			numDetail = indexNow_detail+1;

			let rkap_item      = '<div class="form-group m-b-0"><input id="rkap_item-'+numDetail+'" class="form-control input-sm rkap_item" autocomplete="off"></div>';
			let rkap_desc      = '<div class="form-group m-b-0"><input id="rkap_desc-'+numDetail+'" class="form-control input-sm rkap_desc" autocomplete="off"></div>';
			let category_item     = '<div class="form-group m-b-0"><select id="category_item_opt-'+numDetail+'" data-id="'+numDetail+'" class="form-control input-sm category_item_opt select-center select2">';
					category_item += category_item_opt;
					category_item += '</select></div>';
			let nature_hidden     = '<div class="form-group m-b-0"><input id="nature_hidden-'+ numDetail +'" class="form-control input-sm nature_hidden" value=""></div>';
			let nature_opt     = '<div class="form-group m-b-0"><select id="nature_opt-'+numDetail+'" class="form-control input-sm nature_opt select-center">';
					nature_opt += opt_default;
					nature_opt += '</select></div>';
			let quantity       = '<div class="form-group m-b-0"><input id="quantity-'+numDetail+'" data-id="'+numDetail+'" class="form-control input-sm quantity text-center" value="1" min="1" max="99999" type="number"></div>';
			let price          = '<div class="form-group m-b-0"><input id="price-'+numDetail+'" data-id="'+numDetail+'" class="form-control input-sm price text-right money-format" value="0"></div>';
			// let uom          = '<div class="form-group m-b-0"><input id="uom-'+numDetail+'" class="form-control input-sm uom" autocomplete="off"></div>';
			let uom     = '<div class="form-group m-b-0"><select id="uom_opt-'+numDetail+'" class="form-control input-sm uom_opt select-center">';
					uom += uom_opt;
					uom += '</select></div>';
			let type     = '<div class="form-group m-b-0"><select id="type_opt-'+numDetail+'" class="form-control input-sm type_opt select-center">';
					type += type_opt;
					type += '</select></div>';
			let nominal_detail = '<div class="form-group m-b-0"><input id="nominal_detail-'+numDetail+'" class="form-control input-sm nominal_detail text-right" value="0" readonly></div>';

		    table_detail.rows.add(
		       [{ 
								"no": numDetail,
								"rkap_item": rkap_item,
								"rkap_desc": rkap_desc,
								"category_item": category_item,
								"type": type,
								"nature": nature_hidden,
								// "nature": nature_opt,
								"quantity": quantity,
								"uom": uom,
								"price": price,
								"nominal_detail": nominal_detail
	    		}]
		    ).draw();

			get_table = table.row( $('#table_data tbody tr.selected') );
			data      = get_table.data();

			id_rkap_selected = data.id_rkap_line;

			getNature(numDetail, id_rkap_selected);

			setTimeout(function(){
				$("#category_item_opt-"+numDetail).select2();
				$("#uom_opt-"+numDetail).select2();
			}, 300);

		}
	});

	$('#table_detail tbody').on('input change blur', 'tr td input.quantity, tr td input.price', function () {
		if($(this).val().trim().length === 0){
			$(this).val(0);
		}

		/*index     = $(this).data('id');
		price     = $("#price-"+index);
		price_val = price.val().replace(/[^\d.]/g, "")
						.replace(/^(\d*\.)(.*)\.(.*)$/, '$1$2$3')
						.replace(/\.(\d{2})\d+/, '.$1')
						.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		price.val( price_val);

		qty_int     = parseInt( $("#quantity-"+index).val() );
		price_rpl   = price.val().replace(/,/g, '').replace(/\./g, '.');
		nominal_val = (qty_int > 0 && price_rpl > 0) ? qty_int * price_rpl : 0;*/

		/*if( $("#currency").val() != "IDR"){
			rate_val = parseInt( $("#rate").val().replace(/\./g, '') );
			nominal_currency = (price_int * rate_val) * qty_int ;
			nominal_val = (qty_int > 0 && price_int > 0) ? nominal_currency : 0;
		}*/

		index       = $(this).data('id');
		qty_int     = parseInt( $("#quantity-"+index).val() );
		price_int   = parseInt( $("#price-"+index).val().replace(/\./g, '') );
		nominal_val = (qty_int > 0 && price_int > 0) ? qty_int * price_int : 0;

		$("#nominal_detail-"+index).val(formatNumber(nominal_val));

		setTimeout(function(){
			getNominal();
			getAmount();
		}, 300);

    });

	$('#table_detail tbody').on('change', 'tr td select.category_item_opt', function () {

		index  = $(this).data('id');
		// cate   = $("#category_item_opt-"+index);
		nature = $(this).find(':selected').attr('data-nature');

		if(nature != ""){
			console.log(nature)
			// bla = $("#nature_opt-"+index).find(':selected').attr('data-nature');
			// $("#nature_opt-"+index).attr('data-nature', nature).trigger('change');
			bla = $("#nature_opt-"+index).find(':selected').find(`[data-nature='${nature}']`)
			console.log(bla)
			bla = $("#nature_opt-"+index).find(':selected').filter('[data-nature="'+nature+'"]');
			console.log(bla)
			bla = $("#nature_opt-"+index).find(':selected').attr('data-nature', nature);
			console.log(bla)
			// $("#nature_opt-"+index).attr('data-nature', nature).trigger('change.select2');

			// $("#nature_opt-"+index).val(bla).trigger('change.select2');
			// $("#nature_hidden-"+index).val(nature);
		}
    });

	$('#table_detail tbody').on('change', 'tr td select.nature_opt', function () {
		console.log('changed');
    });

  /*  $('#table_detail tbody').on('input', 'tr td input.price', function (event) {
		  if (event.which >= 37 && event.which <= 40) return;
		  $(this).val(function(index, value) {
		    return value
		      // Keep only digits and decimal points:
		      .replace(/[^\d.]/g, "")
		      // Remove duplicated decimal point, if one exists:
		      .replace(/^(\d*\.)(.*)\.(.*)$/, '$1$2$3')
		      // Keep only two digits past the decimal point:
		      .replace(/\.(\d{2})\d+/, '.$1')
		      // Add thousands separators:
		      .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
		  });
    });*/

	$('#table_detail tbody').on('change', 'tr td input, tr td select', function () {
		storeDataDetail();
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
				// this_row.find("select.nature_opt").attr("id", 'nature_opt-'+num);
				this_row.find("input.nature_hidden").attr("id", 'nature_hidden-'+num);
				this_row.find("select.category_item_opt").attr("id", 'category_item_opt-'+num);
				this_row.find("select.uom_opt").attr("id", 'uom_opt-'+num);
				this_row.find("select.type_opt").attr("id", 'uom_opt-'+num);
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

    $('#pr_date, #delivery_date').datepicker({
		format: 'dd-mm-yyyy',
		todayHighlight:'TRUE',
		autoclose: true,
    });

    $("#save_data").on('click', function () {

			getAmount();
			
			let id_fs             = $("#fs_header").val();
			let directorat        = $("#directorat").val();
			let division          = $("#division").val();
			let unit              = $("#unit").val();
			let id_dpl            = $("#dpl").val();
			let pr_name           = $("#pr_name").val();
			let pr_date           = $("#pr_date").val();
			let currency          = $("#currency").val();
			let submitter         = $("#submitter").val();
			let jabatan_sub       = $("#jabatan_sub").html();
			let delivery_date     = $("#delivery_date").val();
			let delivery_location = $("#delivery_location").val();
			let rate              = parseInt($("#rate").val().replace(/\./g, ''));
			let amount            = parseInt($("#amount").val().replace(/\./g, ''));
			let total_data        = table.data().count();
			let fatalNominal      = false;
			let notice            = '';


	    let doc_list = [];
	    $('#document_list input[type=checkbox]').each(function() {
	       if ($(this).is(":checked")) {
	        valDoc = ($(this).val() != '') ? $(this).val() : $("#others_doc").val();
	        if(valDoc != ''){
	          doc_list.push(valDoc);
	        }
	       }
	    });

	    for (var i = 1; i <= total_data; i++) {
			fund_av       = $("#fund_av-"+i).val();
			nominal_val   = $("#nominal-"+i).val();

			/*if( parseInt(nominal_val.replace(/\./g, '')) < 1){
				fatalNominal =  true;
				notice       = 'Nilai nominal masih kosong pada data ke '+i;
				break;
			}*/
			if( parseInt(nominal_val.replace(/\./g, '')) > parseInt(fund_av.replace(/\./g, '')) ){
				fatalNominal =  true;
				notice       = 'Nilai nominal lebih dari Fund Available pada data ke '+i;
				break;
			}

			if( $("#currency").val() != "IDR"){
				rate_val   = parseInt( $("#rate").val().replace(/\./g, '') );
				convert_rp = parseInt(nominal_val.replace(/\./g, '')) * rate_val;

				if(convert_rp >  parseInt(fund_av.replace(/\./g, ''))){
					fatalNominal =  true;
					notice       = 'Nilai nominal lebih dari Fund Available pada data ke '+i;
					break;
				}
			}
	    }

		if(unit == "" || pr_name == "" || amount == 0){
    		customNotif('Warning', "Please fill all field", 'warning');
		}
		else if (is_dpl == true && dpl == "0")
		{
			customNotif('Warning', 'Please upload document', 'warning');
			return false;
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

				rkap_val        = data.id_rkap;
				line_name_val   = $("#line_name-"+j).val();
				fund_av         = $("#fund_av-"+j).val();
				nominal_val     = $("#nominal-"+j).val();
				
				pr_line_key_val = data.pr_line_key;
				data_detail     = pr_line_data[pr_line_key_val];

				if(rkap_val != "" && line_name_val != "" && parseInt(fund_av.replace(/\./g, '')) > 0 && parseInt(nominal_val.replace(/\./g, '')) > 0){
		    		data_lines.push({'id_rkap' : rkap_val, 'nominal' : parseInt(nominal_val.replace(/\./g, '')), 'line_name' : line_name_val, 'detail_data' : data_detail});
				}
			});

		    data = {
						id_fs : id_fs,
						id_dpl : id_dpl,
						directorat : directorat,
						division : division,
						unit : unit,
						pr_name : pr_name,
						pr_date : pr_date,
						amount : amount,
						currency : currency,
						rate : rate,
						submitter : submitter,
						jabatan_sub : jabatan_sub,
						doc_list : doc_list,
						data_line : data_lines,
						attachment : attachment_file,
						delivery_date : delivery_date,
						delivery_location : delivery_location
		    		}

				$(this).attr('disabled', true);

		    $.ajax({
		        url   : baseURL + 'purchase-requisition/api/save_pr',
		        type  : "POST",
		        data  : data,
		        dataType: "json",
		        beforeSend  : function(){
                          customLoading('show');
                        },
		        success : function(result){
		        	if(result.status == true){
		        		customNotif('Success', "PR CREATED", 'success');
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

  	$("#directorat").bind("click", function(e) {
	    lastValue = $(this).val();
	}).bind("change", function(e) {
		change = true;
		if(change == true){
			$("#table_data tbody").find("tr:not(:first)").remove();

			$("#division").html(opt_default);
	  		if( $(this).val() != "0"){
	  			getDivision();
	  		}
			$("#unit").html(opt_default);
			$("#fs_header").html(opt_default);

	  		id_fs = 0;
	  		table.clear().draw();
		}
	});

	$("#division").bind("click", function(e) {
	    lastValue = $(this).val();
	}).bind("change", function(e) {
		change = true;
		if(change == true){
			$("#table_data tbody").find("tr:not(:first)").remove();

			$("#unit").html(opt_default);
	  		if( $(this).val() != "0"){
	  			getUnit();
	  		}
			$("#fs_header").html(opt_default);

	  		id_fs = 0;
	  		table.clear().draw();
		}
	});

	$("#unit").bind("click", function(e) {
	    lastValue = $(this).val();
	}).bind("change", function(e) {
		change = true;
		if(change == true){
			$("#table_data tbody").find("tr:not(:first)").remove();

			$("#fs_header").html(opt_default);
	  		if( $(this).val() != "0"){
	  			getFS();
	  		}
			getSubmitter();

	  		id_fs = 0;
	  		table.clear().draw();
		}
	});

	$("#fs_header").bind("click", function(e) {
	    lastValue = $(this).val();
	}).bind("change", function(e) {
		change = true;
		if(change == true){
			$("#table_data tbody").find("tr:not(:first)").remove();

			id_fs       = $(this).val();
			fs_currency = $(this).find(':selected').attr('data-currency');
			fs_rate     = $(this).find(':selected').attr('data-rate');
			data_dpl      = $(this).find(':selected').attr('data-dpl');

			if(data_dpl == "1"){
				is_dpl = true;
				$("#dpl_group").removeClass("d-none");
				getDPL(id_fs);
			}else{
				is_dpl = false;
				$("#dpl_group").addClass("d-none");
			}

			$("#currency").val(fs_currency);
			$("#rate").val( formatNumber( fs_rate ) );

	  		if( fs_currency != "IDR"){
				$("#currency_rate").removeClass("d-none");
	  		}else{
				$("#currency_rate").addClass("d-none");
	  		}

  			table.draw();
		}
	});

	$("#submitter").on("change", function(){
		getJabatan("submitter");
    });


    function getAmount() {
		let total_data  = table.data().count();
		let totalAmount = 0;

		for (var i = 1; i <= total_data; i++) {
			get_nominal = $("#nominal-"+i).val();
			totalAmount += parseInt(get_nominal.replace(/\./g, ''));
	    }
		$("#amount").val(formatNumber(totalAmount));
	}

    function getNominal(){
		let total_detail = table_detail.data().count();
		let totalNominal = 0;

		for (var i = 1; i <= total_detail; i++) {
			nominal_detail_val = $("#nominal_detail-"+i).val();
			totalNominal += parseInt(nominal_detail_val.replace(/\./g, ''));
	    }

	    $("#table_data tbody tr.selected").find("input.nominal").val(formatNumber(totalNominal));
    }

   function getDivision() {

		let directorat    = $("#directorat").find(':selected').attr('data-name');

		$("#division").attr('disabled', true);
		$("#division").css('cursor', 'wait');

	    $.ajax({
	        url   : baseURL + 'api-budget/load_data_rkap_view',
	        type  : "POST",
	        data  : {category : "division", directorat : directorat},
	        dataType: "json",
	        success : function(result){
        		let division_opt = opt_default;
	        	if(result.status == true){
					data = result.data;
		        	for(var i = 0; i < data.length; i++) {
					    obj = data[i];
					    division_opt += '<option value="'+ obj.id_division +'" data-name ="'+ obj.division +'">'+ obj.division +'</option>';
					}
	        	}
				$("#division").html(division_opt);
				$("#division").attr('disabled', false);
				$("#division").css('cursor', 'default');
	        }
	    });
	}

    function getUnit() {

		let directorat = $("#directorat").find(':selected').attr('data-name');
		let division   = $("#division").find(':selected').attr('data-name');

		$("#unit").attr('disabled', true);
		$("#unit").css('cursor', 'wait');

	    $.ajax({
	        url   : baseURL + 'api-budget/load_data_rkap_view',
	        type  : "POST",
	        data  : {category : "unit", directorat : directorat, division : division},
	        dataType: "json",
	        success : function(result){
        		let unit = opt_default;
	        	if(result.status == true){
					data = result.data;
		        	for(var i = 0; i < data.length; i++) {
					    obj = data[i];
					    unit += '<option value="'+ obj.id_unit +'" data-name ="'+ obj.unit +'">'+ obj.unit +'</option>';
					}
	        	}
				$("#unit").html(unit);
				$("#unit").attr('disabled', false);
				$("#unit").css('cursor', 'default');
	        }
	    });
	}

    function getFS() {

		let directorat = $("#directorat").val();
		let division   = $("#division").val();
		let unit       = $("#unit").val();

		$("#fs_header").attr('disabled', true);
		$("#fs_header").css('cursor', 'wait');

	    $.ajax({
	        url   : baseURL + 'api-budget/load_fs_header',
	        type  : "POST",
	        data  : {directorat : directorat, division : division, unit : unit, category : 'pr'},
	        dataType: "json",
	        success : function(result){
        		let fs_header = opt_default;
	        	if(result.status == true){
					data = result.data;
		        	for(var i = 0; i < data.length; i++) {
					    obj = data[i];
					    fs_header += '<option value="'+ obj.id_fs +'" data-currency="'+ obj.currency +'" data-dpl="'+ obj.is_dpl +'" data-rate="'+ obj.rate +'">'+ obj.fs_number +' - '+ obj.fs_name +'</option>';
					}
	        	}
				$("#fs_header").html(fs_header);
				$("#fs_header").attr('disabled', false);
				$("#fs_header").css('cursor', 'default');
		  		setTimeout(function(){
					$("#fs_header").select2();
				}, 300);
	        }
	    });
	}

  function getDPL(id_fs) {

		$("#dpl").attr('disabled', true);
		$("#dpl").css('cursor', 'wait');

    $.ajax({
        url   : baseURL + 'api-budget/load_dpl',
        type  : "POST",
        data  : {id_fs : id_fs},
        dataType: "json",
        success : function(result){
      		let dpl = opt_default;
        	if(result.status == true){
						data = result.data;
		        	for(var i = 0; i < data.length; i++) {
						    obj = data[i];
						    dpl = '<option value="'+ obj.id_dpl +'">' + obj.dpl_number + '</option>';
							}
        	}
					$("#dpl").html(dpl);
					$("#dpl").attr('disabled', false);
					$("#dpl").css('cursor', 'default');
        }
    });
	}


   function getNature(id_row, id_rkap, id_select=0) {

	    $.ajax({
	        url   : baseURL + 'api-budget/load_nature_by_rkap',
	        type  : "POST",
	        data  : {id_rkap : id_rkap},
	        dataType: "json",
	        success : function(result){
	        	if(result.status == true){
					data = result.data;
					if(data.length > 1){
			        	for(var i = 0; i < data.length; i++) {
							obj = data[i];
							let selected = '';
						    if(obj.selected == true){
						    	val_nature = obj.id_coa;
						    	selected = ' selected';
						    }
						    if(id_select == obj.id_coa){
						    	val_nature = obj.id_coa;
						    	selected = ' selected';
						    }
						}
					}else{
						obj = data[0];
				    	val_nature = obj.id_coa;
					}
	        	}
				if(val_nature != ""){
					$("#nature_hidden-"+id_row).val(val_nature);
				}
	        }
	    });
	}

	function getCategoryItem() {

        return new Promise(resolve => {
            $.ajax({
	       				url   : baseURL + 'api-budget/load_category_item',
                type  : "POST",
                dataType: "json",
                success : function(result){
					        	if(result.status == true){
											data = result.data;
								      for(var i = 0; i < data.length; i++) {
												obj = data[i];
						    				category_item_opt += '<option value="'+obj.category_item+'" data-nature="'+obj.nature+'">'+ obj.category_item +'</option>';
											}
					        	}
                    resolve(category_item_opt);
                }
            });
        });
	}

	function getUom() {

        return new Promise(resolve => {
            $.ajax({
	       				url   : baseURL + 'api-budget/load_uom',
                type  : "POST",
                dataType: "json",
                success : function(result){
					        	if(result.status == true){
											data = result.data;
								      for(var i = 0; i < data.length; i++) {
												obj = data[i];
							    			uom_opt += '<option value="'+obj.uom+'">'+ obj.uom +'</option>';
											}
					        	}
                    resolve(uom_opt);
                }
            });
        });
	}


	function storeDataDetail(){

		let get_table      = table.row( $('#table_data tbody tr.selected') );
		let index          = get_table.index();
		let index_Num      = index+1;
		let data           = get_table.data();

		pr_line_key = data.pr_line_key;

		let total_detail = table_detail.data().count();
		let detail_data  = [];

		for (var i = 1; i <= total_detail; i++) {
			rkap_desc_val        = $("#rkap_desc-"+i).val();
			rkap_item_val        = $("#rkap_item-"+i).val();
			category_item_val    = $("#category_item_opt-"+i).val();
			category_item_nature = $("#category_item_opt-"+i).find(':selected').attr('data-nature');
			
			nature_hidden        = $("#nature_hidden-"+i).val();
			quantity_val         = $("#quantity-"+i).val();
			type_val             = $("#type_opt-"+i).val();
			uom_val              = $("#uom_opt-"+i).val();
			price_val            = $("#price-"+i).val();
			nominal_detail_val   = $("#nominal_detail-"+i).val();

			detail_data.push({'rkap_desc' : rkap_desc_val, 'rkap_item' : rkap_item_val, 'category_item' : category_item_val, 'category_item_nature' : category_item_nature, 'nature' : nature_hidden, 'quantity' : quantity_val, 'uom' : uom_val, 'type' : type_val, 'price' : parseInt(price_val.replace(/\./g, '')), 'nominal' : parseInt(nominal_detail_val.replace(/\./g, ''))});
	    }

    	pr_line_data[pr_line_key] = detail_data;

	}



    function getSubmitter(){

		let directorat = $("#directorat").val();
		let division   = $("#division").val();
		let unit       = $("#unit").val();

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
						    submitter_opt += '<option value="'+ obj.nama +'" data-jabatan="'+ obj.jabatan +'">'+ obj.nama +'</option>';
						}
					}else{
						submitter_opt = opt_default;
						for(var i = 0; i < data.length; i++) {
						    obj = data[i];
						    submitter_opt += '<option value="'+ obj.nama +'" data-jabatan="'+ obj.jabatan +'">'+ obj.nama +'</option>';
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

  });
</script>