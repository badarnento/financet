<?php $this->load->view('ilustration') ?>

<div class="row">
  <div class="white-box boxshadow m-b-0 mt-10 border-top-only-5 z-index-2 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">GR Detail</h5>
    </div>
  </div>
  <div class="white-box boxshadow z-index-2 py-10 border-bottom-only-5 small">
    <div class="row">
    	<form class="form-horizontal">
    	<div class="col-sm-6 col-md-5">
        <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">No BAST <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="no_bast" class="control-label"><?= $no_bast ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">BAST Date <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="bast_date" class="control-label"><?= $bast_date ?></label>
            </div>
          </div>
        	<div class="form-group m-b-10">
          	<label class="col-sm-5 col-md-4 control-label text-left">Directorat <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="directorat" class="control-label"><?= get_directorat($directorat) ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Division <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="division" class="control-label text-left"><?= get_division($division) ?></label>
            </div>
          </div>
        	<div class="form-group m-b-10">
          	<label class="col-sm-5 col-md-4 control-label text-left">Unit <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label id="unit" class="control-label"><?= get_unit($unit) ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">PO Number <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label"><?= $po_number ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Vendor Name <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label text-left"><?= $vendor_name ?></label>
            </div>
          </div>
        </div>

        <div class="col-sm-6 col-md-5">
          <!-- <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">GR Date <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label"><?= $gr_date ?></label>
            </div>
          </div> -->
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Category <span class="pull-right">:</span></label>
            <div class="col-sm-7 col-md-8">
              <label class="control-label"><?= $category ?></label>
            </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Contract Identification <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
              <label class="control-label"><?= $contract ?></label>
              </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Budget Type <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label class="control-label text-left"><?= $budget_type ?></label>
              </div>
          </div>
          <div class="form-group m-b-10">
            <label class="col-sm-5 col-md-4 control-label text-left">Project Ownership <span class="pull-right">:</span></label>
              <div class="col-sm-7 col-md-8">
                <label class="control-label text-left"><?= $project ?></label>
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
      <h5 class="font-weight-700 m-0 text-uppercase">GR Lines</h5>
    </div>
  </div>
  <div class="white-box boxshadow border-bottom-only-5">
	<div class="row">
		<div class="col-md-12">
	      <table id="table_data" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small w-full">
	        <thead>
	        	<tr>
		          	<th class="text-center">No</th>
		          	<th class="text-center">Item Name</th>
		          	<th class="text-center">Item Description</th>
		          	<th class="text-center">Qty</th>
		          	<th class="text-center">UoM</th>
		          	<th class="text-center">Unit Price</th>
		          	<th class="text-center">Total Price</th>
		          	<th class="text-center">Asset Type</th>
		          	<th class="text-center">Serial Number</th>
		          	<th class="text-center">Merek</th>
		          	<th class="text-center">Major Category</th>
		          	<th class="text-center">Minor Category</th>
		          	<th class="text-center">Region</th>
		          	<th class="text-center">Location</th>
		          	<th class="text-center">Project Ownership (Unit)</th>
		          	<th class="text-center">Umur Manfaat <a id="umur_manfaat_info" target="blank"><i class="fa fa-info-circle"></i></a></th>
		          	<th class="text-center">CIP</th>
		          	<th class="text-center">No Invoice</th>
		          	<th class="text-center">Invoice Date</th>
		          	<th class="text-center">Receipt Date</th>
		        </tr>
	        </thead>
	      </table>
	    </div>
	</div>
   </div>
</div>


<div class="row">
  <div class="white-box border-radius-5 small">
      	<!-- <form class="form-horizontal" id="form-submitter2"> -->
	      	<div class="row">
	      		<div class="col-sm-6 col-sm-offset-6">
			        <div class="form-group pull-right">
			        	<button type="button" id="save_data" class="btn btn-info border-radius-5 m-10 w-150p"><i class="fa fa-save"></i> Save </button>
			        </div>
	      		</div>
	      	</div>
      	<!-- </form> -->
  </div>
</div>



<div class="row">
  <div class="white-box border-radius-5 small">
        <form class="form-horizontal" id="form-submitter">
        
          <div class="row">
            <div class="col-sm-8">
            <div class="form-group m-b-10">
              <label class="col-sm-5 col-md-3 control-label text-left">Document Uploaded <span class="pull-right">:</span></label>
                <div class="col-sm-8">
                  <label class="control-label text-left"><?= ($gr_doc_upload != "-") ? '<a id="gr_attachment" class="btn btn-xs btn-success" href="'.$gr_doc_upload.'" target="_blank"> '.$attachment.' &nbsp; <i class="fa fa-download"></i> Download </a>' : $gr_doc_upload ?></label>
                </div>
            </div>
          </div>
            </div>
        </form>
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
		            	<label class="control-label text-left"><?= $gr_submitter ?> / <?= (strtolower($gr_jabatan_sub) == 'procurement') ? 'Procurement Support' : $gr_jabatan_sub  ?></label>
		            </div>
		        </div>
	        	<div class="form-group m-b-10">
	        		<label class="col-sm-5 col-md-3 control-label text-left">Approval <span class="pull-right">:</span></label>
		            <div class="col-sm-7 col-md-7">
		            <?php foreach ($gr_approval as $key => $value):?>
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
<div id="modal_umur_manfaat" class="modal fade" tabindex="-3" role="dialog">
    <div class="modal-md modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12">
            	<img src="<?= base_url('assets/img/umur_manfaat.JPG') ?>" alt="info_umur_manfaat" class="img-responsive">
            </div>
          </div>
        </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function(){

		const opt_default = '<option value="0" data-name="">-- Choose --</option>';
		const ID_GR       = '<?= $id_gr ?>';
		const DIRECTORAT  = '<?= $id_dir_code ?>';
		let url           = baseURL + 'gr/approval/api/load_data_gr_lines';


		Pace.track(function(){
	    $('#table_data').DataTable({
	      "serverSide"      : true,
	      "processing"      : true,
	      "ajax"            : {
									"url"  : url,
									"type" : "POST",
									"dataType": "json",
									"data"    : function ( d ) {
																					d.id_gr = ID_GR;
																					d.directorat = DIRECTORAT;
					                            }
			                    },
	      "language"        : {
	                            "emptyTable"  : "<span class='label label-danger'>Data Not Found!</span>",
	                            "infoEmpty"   : "Empty record",
	                            "processing"  : '<img src="' + imageLoadingSmall + '" alt="" />',
	                            "search"      : "_INPUT_"
	                          },
	      "columns"         : [
								{"data": "no", "width": "10px", "class": "text-center" },
								{"data": "item_name", "width": "150px", "class": "p-5" },
								{"data": "item_description", "width": "180px", "class": "p-5" },
								{"data": "quantity", "width": "60px", "class": "p-5 text-center"},
								{"data": "uom", "width": "120px", "class": "p-5 text-center"},
								{"data": "unit_price", "width": "120px", "class": "p-5 text-right"},
								{"data": "total_price", "width": "120px", "class": "p-5 text-right"},
								{"data": "asset_type", "width": "120px", "class": "p-5 text-right"},
								{"data": "serial_number", "width": "120px", "class": "p-5 text-left"},
								{"data": "merek", "width": "120px", "class": "p-5 text-left"},
								{"data": "major_category", "width": "180px", "class": "p-5 text-left"},
								{"data": "minor_category", "width": "180px", "class": "p-5 text-left"},
								{"data": "region", "width": "180px", "class": "p-5 text-left"},
								{"data": "lokasi", "width": "180px", "class": "p-5 text-left"},
								{"data": "project_owner_unit", "width": "180px", "class": "p-5 text-left"},
								{"data": "umur_manfaat", "width": "120px", "class": "p-5 text-left"},
								{"data": "cip", "width": "60px", "class": "p-5 text-center"},
								{"data": "no_invoice", "width": "120px", "class": "p-5 text-center"},
								{"data": "invoice_date", "width": "120px", "class": "p-5 text-center"},
								{"data": "receipt_date", "width": "120px", "class": "p-5 text-center"},
				    		],
          "drawCallback": function ( settings ) {
			
          },
	      "ordering"        : false,
	      "pageLength"      : 100,
	      "scrollY"         : 480,
	      "scrollX"         : true,
	      "scrollCollapse"  : true,
	      "autoWidth"       : true,
	      "bAutoWidth"      : true,
	    });
	});

	let table = $('#table_data').DataTable();
	$('#table_data_length').remove();
	$('#table_data_paginate').remove();
	$('#table_data_filter').remove();


	 $("#save_data").on('click', function () {

		let budget_type   = $("#budget_type").val();

		let fatalError = false;
		let fatalList1 = '';
		let fatalList2 = '';
		let fatalList3 = '';
		let fatalList4 = '';
		let fatalList5 = '';
		let fatalList6 = '';
		let fatalList7 = '';
		let fatalList8 = '';

		let total_data    = table.data().count();

    for (var i = 1; i <= total_data; i++) {

				asset_type         = $("#asset_type-"+i).val();
				serial_number      = $("#serial_number-"+i).val();
				merek              = $("#merek-"+i).val();
				major_category     = $("#major_category-"+i).val();
				minor_category     = $("#minor_category-"+i).val();
				project_owner_unit = $("#project_owner_unit-"+i).val();
				umur_manfaat       = $("#umur_manfaat-"+i).val();
				cip                = $("#cip-"+i).val();


				if (asset_type == ""){
					fatalError =  true;
					if(fatalList1 == ""){
						fatalList1 = i;
					}else{
						fatalList1 += ', ' +i;
					}
				}

				if (serial_number == ""){
					fatalError =  true;
					if(fatalList2 == ""){
						fatalList2 = i;
					}else{
						fatalList2 += ', ' +i;
					}
				}

				if (merek == ""){
					fatalError =  true;
					if(fatalList3 == ""){
						fatalList3 = i;
					}else{
						fatalList3 += ', ' +i;
					}
				}

				if (major_category == ""){
					fatalError =  true;
					if(fatalList4 == ""){
						fatalList4 = i;
					}else{
						fatalList4 += ', ' +i;
					}
				}

				if (minor_category == ""){
					fatalError =  true;
					if(fatalList5 == ""){
						fatalList5 = i;
					}else{
						fatalList5 += ', ' +i;
					}
				}

				if (project_owner_unit == ""){
					fatalError =  true;
					if(fatalList6 == ""){
						fatalList6 = i;
					}else{
						fatalList6 += ', ' +i;
					}
				}

				if (umur_manfaat == ""){
					fatalError =  true;
					if(fatalList7 == ""){
						fatalList7 = i;
					}else{
						fatalList7 += ', ' +i;
					}
				}

				if (cip == ""){
					fatalError =  true;
					if(fatalList8 == ""){
						fatalList8 = i;
					}else{
						fatalList8 += ', ' +i;
					}
				}

    }


		if(fatalError == true){

			if(fatalList1 != ""){
				customNotif('Warning', 'Asset Type cannot be null', 'warning');
			}
			if(fatalList2 != ""){
				customNotif('Warning', 'Serial Number cannot be null', 'warning');
			}
			if(fatalList3 != ""){
				customNotif('Warning', 'Merek cannot be null', 'warning');
			}
			if(fatalList4 != ""){
				customNotif('Warning', 'Major Category cannot be null', 'warning');
			}
			if(fatalList5 != ""){
				customNotif('Warning', 'Minor Category cannot be null', 'warning');
			}
			if(fatalList6 != ""){
				customNotif('Warning', 'Project Ownership Unit cannot be null', 'warning');
			}
			if(fatalList7 != ""){
				customNotif('Warning', 'Umur Manfaat cannot be null', 'warning');
			}
			if(fatalList8 != ""){
				customNotif('Warning', 'CIP cannot be null', 'warning');
			}
		}
		else
		{

			let data_lines  = [];

			table.rows().eq(0).each( function (index) {

		    	j = index+1;
			    data = table.row( index ).data();
				
					gr_line_id          = data.gr_line_id;
					
					asset_type         = $("#asset_type-"+j).val();
					serial_number      = $("#serial_number-"+j).val();
					merek              = $("#merek-"+j).val();
					major_category     = $("#major_category-"+j).val();
					minor_category     = $("#minor_category-"+j).val();
					// project_owner_unit = $("#project_owner_unit-"+j).val();
					umur_manfaat       = $("#umur_manfaat-"+j).val();
					cip                = $("#cip-"+j).val();

	    		data_lines.push({'gr_line_id' : gr_line_id, 'asset_type' : asset_type, 'serial_number' : serial_number, 'merek' : merek, 'umur_manfaat' : umur_manfaat, 'major_category' : major_category , 'minor_category' : minor_category/*, 'project_owner_unit' : project_owner_unit */, 'cip' : cip });

			
			});

	    data = {
							id_gr : ID_GR,
							budget_type : budget_type,
							data_line : data_lines
			    		}


			    $.ajax({
			        url   : baseURL + 'gr/approval/api/update_gr',
			        type  : "POST",
			        data  : data,
			        dataType: "json",
			        beforeSend  : function(){
	                          customLoading('show');
	                        },
			        success : function(result){
			        	if(result.status == true){
			        		customNotif('Success', "GR UPDATED", 'success');
			        		setTimeout(function(){
			        			customLoading('hide');
			        			$(location).attr('href', baseURL + 'gr/review');
			        		}, 500);
			        	}
			        	else{
			        		customLoading('hide');
			        		customNotif('Error', result.messages, 'error');
			        	}
			        }
			    });
		}
		
	});


  $('#umur_manfaat_info').on( 'click', function () {
    	$("#modal_umur_manfaat").modal('show');
	});

	let major_category_value = "";
	let major_category_id = "";
	let minor_category_id = "";

	$('#table_data').on('change', 'select.major_category', function () {

	  let data  = table.row( $(this).parents('tr') ).data();
	  major_category_value = this.value;
	  major_category_id = this.id;

      minor_category_id = major_category_id.replace("major", "minor");

	  getminorcategory();

	});


	function getminorcategory()
  {

    $.ajax({
	  url   : baseURL + 'general-receipt/api/load_ddl_minor',
      type  : "POST",
      data  : {major_category_value :  major_category_value},
	        dataType: "json",
	        success : function(result){
        		let minor_opt = opt_default;
	        	if(result.status == true){

					data = result.data;
		        	for(var i = 0; i < data.length; i++) {
					    obj = data[i];
					    minor_opt += '<option value="'+ obj.code +'" data-name ="'+ obj.minor_name +'">'+ obj.minor_name +'</option>';
					}
	        	}
				$("#"+minor_category_id).html(minor_opt);

				// setTimeout(function(){
				// 	$(".minor_category").select2();
				// }, 300);
	        }
	    });  

  }



  });
</script>