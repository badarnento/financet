<?php $this->load->view('ilustration') ?>

<div class="row">
  <div class="white-box boxshadow m-b-0 mt-10 border-top-only-5 z-index-2 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">Upload PO HEADER</h5>
    </div>
  </div>
  <div class="white-box boxshadow z-index-2 py-10 border-bottom-only-5 small">
      <div class="row">
		    <form method="post" action="<?= base_url('purchase-order/upload-po-header') ?>" class="form-horizontal" enctype="multipart/form-data">
		      <div class="form-group">
		         <div class="col-md-2 m-b-10">
							<input id ="limit_max" class="form-control input-sm" type="number" name="limit_max" required="required" max_length="100" value="10">
		         </div>
		         <div class="col-md-6 m-b-10">
		            <!-- <input class="custom-input-file" type="file" name="file" accept=".xls,.xlsx" id="fileToUpload"> -->
		            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
		                <div id="fileinput" class="form-control input-sm" data-trigger="fileinput"> <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div><span class="input-group-addon btn btn-default btn-outline btn-file"> <span class="fileinput-new">Select file</span> <span class="fileinput-exists">Change</span>
		                <input id="fileToUpload" type="file" name="file" accept=".xls,.xlsx" required="required"> </span> <a id="fileinput_remove" href="javascript:void(0)" class="input-group-addon btn btn-warning fileinput-exists" data-dismiss="fileinput">Remove</a>
		            </div>
		         </div>
		          <div class="col-md-2 m-b-10">
		            <input type="submit" class="btn btn-info btn-rounded btn-block" value="UPLOAD">
		          </div>
		      </div>
		    </form>
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
  <div class="white-box boxshadow m-b-0 mt-10 border-top-only-5 z-index-2 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">Upload PO BOQ</h5>
    </div>
  </div>
  <div class="white-box boxshadow z-index-2 py-10 border-bottom-only-5 small">
      <div class="row">
		    <form method="post" action="<?= base_url('purchase-order/upload-po-boq') ?>" class="form-horizontal" enctype="multipart/form-data">
		      <div class="form-group">
		         <div class="col-md-2 m-b-10">
							<input id ="limit_max" class="form-control input-sm" type="number" name="limit_max" required="required" max_length="100" value="10">
		         </div>
		         <div class="col-md-6 m-b-10">
		            <!-- <input class="custom-input-file" type="file" name="file" accept=".xls,.xlsx" id="fileToUpload"> -->
		            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
		                <div id="fileinput" class="form-control input-sm" data-trigger="fileinput"> <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div><span class="input-group-addon btn btn-default btn-outline btn-file"> <span class="fileinput-new">Select file</span> <span class="fileinput-exists">Change</span>
		                <input id="fileToUpload" type="file" name="file" accept=".xls,.xlsx" required="required"> </span> <a id="fileinput_remove" href="javascript:void(0)" class="input-group-addon btn btn-warning fileinput-exists" data-dismiss="fileinput">Remove</a>
		            </div>
		         </div>
		          <div class="col-md-2 m-b-10">
		            <input type="submit" class="btn btn-info btn-rounded btn-block" value="UPLOAD">
		          </div>
		      </div>
		    </form>
	    </div>

	  	<div class="row">
      		<div class="col-md-8">
		        <div class="form-group pull-right">
		        </div>
      		</div>
      	</div>
  </div>
</div>