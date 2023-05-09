<?php $this->load->view('ilustration') ?>

<div class="row">
  <div class="white-box boxshadow m-b-0 mt-10 border-top-only-5 z-index-2 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase"><?= $title ?></h5>
    </div>
  </div>
  <div class="white-box boxshadow z-index-2 py-10 border-bottom-only-5 small">
      	<div class="row">
      		<div class="col-md-8">
	      		<div class="form-group m-b-10">
	                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
	                    <div id="fileinput" class="form-control input-sm" data-trigger="fileinput"> <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div><span class="input-group-addon btn btn-default btn-outline btn-file"> <span class="fileinput-new">Select file</span> <span class="fileinput-exists">Change</span>
	                    <input id="attachment" type="file" name="attachment" data-name="fpjp" accept=".csv"> </span> <a id="fileinput_remove" href="javascript:void(0)" class="input-group-addon btn btn-warning fileinput-exists" data-dismiss="fileinput">Remove</a>
	                </div>
                    <div class="progress progress-lg d-none">
                        <div id="progress" class="progress-bar progress-bar-info active progress-bar-striped" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%" role="progressbar"></div>
                    </div>
                    <!-- <span class="help-block"><small>Filename: FPJP_(No.Invoice)_(Vendor Name)_(Group Name).</small></span>  -->
		        </div>
	        </div>
	        <div class="col-md-4">
	        	<button type="button" id="btn_upload" class="btn btn-info border-radius-5 w-150p"><i class="fa fa-upload"></i> Upload </button>
	        </div>
  	    </div>

	  	<div class="row">
      		<div class="col-md-8">
		        <div class="form-group pull-right">
		        </div>
      		</div>
      	</div>
  </div>
</div>


<div class="row">
  <div class="white-box boxshadow m-b-0 border-top-only-5 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">Upload History</h5>
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
		        <th class="text-center">NO</th>
		        <th class="text-center">FILE NAME</th>
		        <th class="text-center">KEY UPLOAD</th>
		        <th class="text-center">STATUS</th>
		        <th class="text-center">UPLOAD BY</th>
		        <th class="text-center">UPLOAD TIME</th>
              </tr>
            </thead>
          </table>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function(){


		let url = baseURL + 'upload-csv/settlement/load_data_settlement';

		Pace.track(function(){
		  $('#table_data').DataTable({
		    "serverSide"      : true,
		    "processing"      : true,
		    "ajax"            : {
		              "url"  : url,
		              "type" : "POST",
		              "dataType": "json"
		                      },
		    "language"        : {
		                          "emptyTable"  : "<span class='label label-danger'>Data Not Found!</span>",
		                          "infoEmpty"   : "Empty Data",
		                          "processing"  : '<img src="' + imageLoadingSmall + '" alt="" />',
		                          "search"      : "_INPUT_"
		                        },
		    "columns"         : [
		            {"data": "no", "width": "8px", "class": "text-center" },
		              {"data": "file_name", "width": "250px" },
		              {"data": "key_upload", "width": "60px", "class": "text-center"  },
		              {"data": "status", "width": "60px", "class": "text-center"  },
		              {"data": "upload_by", "width": "100px", "class": "text-center"  },
		              {"data": "upload_date", "width": "100px", "class": "text-center"  }
		            ],
		    "pageLength"      : 100,
		    "ordering"        : false,
		    "scrollY"         : 540,
		    "scrollCollapse"  : true,
		    "scrollX"         : false,
		    "autoWidth"       : true,
		    "bAutoWidth"      : false
		  });
		});

		let table = $('#table_data').DataTable();

		$('#table_data_filter').remove();
		$("#tbl_search").on('keyup', "input[type='search']", function(){
		  table.search( $(this).val() ).draw();
		});

		$('#btn_upload').on('click', function() {

		    if($('#attachment').val() == false){
				customNotif('Warning', 'Please select file first', 'warning');
		    	return false;
		    }

		    var file_data = $('#attachment').prop('files')[0];   
		    var form_data = new FormData();                  
		    form_data.append('file', file_data);
          	form_data.append('category', 'settlement');

		    $.ajax({
		        url   : baseURL + 'upload-csv/settlement/upload',
				type  : "POST",
				data  : form_data,
				dataType: "json",
				cache: false,
				contentType: false,
				processData: false,
				beforeSend  : function(){
		                 customLoading('show');
		                },
		        success : function(result){
		        	if(result.status == true){

		        		$("#fileinput_remove").trigger('click');
	                 	customLoading('hide');
		        		customNotif('Success', result.messages, 'success');
		        	}
		        	else{
		        		customLoading('hide');
		        		customNotif('Error', result.messages, 'error');
		        	}

		    		setTimeout(function(){
		    			table.draw();
		    		}, 300);
		        }
		    });
		});
});


</script>