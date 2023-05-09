<div class="row">
	<form class="form-horizontal">
		<div class="form-group m-b-10">
            <div class="col-xs-4 col-sm-2">
            	<select class="form-control m-b-10" id="year">
        		<?php foreach($get_exist_year as $value): ?>
        			<option value="<?= $value['TAHUN'] ?>"<?= ($value['TAHUN'] == date('Y')) ? ' selected' : '' ?>><?= $value['TAHUN'] ?></option>
        		<?php endforeach; ?>
            	</select>
            </div>
            <div class="col-xs-4 col-sm-2 w-auto">
            	<button class="btn btn-info btn-outline border-radius-5 w-100p m-b-10" type="button"><i class="fa fa-search"></i> View</button>
            </div>
            <div class="col-xs-4 col-sm-2">
            	<button class="btn btn-info border-radius-5 w-150p m-b-10" type="button"><i class="fa fa-download"></i> Download</button>
            </div>
        </div>
	</form>
</div>

<?php $this->load->view('ilustration') ?>

<div class="row">
	<div class="white-box boxshadow m-b-0 mt-10 border-top-only-5 z-index-2 panel-title-custom">
		<div class="col-md-6">
			<h5 class="font-weight-700 m-0 text-uppercase">Informasi Budget</h5>
		</div>
	</div>
	<div class="white-box boxshadow mt-0 z-index-2 py-10 border-bottom-only-5 mb-0">
		<div class="row">
			<div class="col-md-2">
				<div class="form-group">
					<label class="control-label font-size-12">Program ID</label>
					<select class="form-control input-sm" id="entry" name="entry">
						<option value="" data-name="" >-- Choose --</option>
						<?php foreach ($program_id as $key => $value): ?>
							<option value="<?= $value['ENTRY_OPTIMIZE'] ?>"><?= $value['ENTRY_OPTIMIZE'] ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
					<label class="control-label font-size-12">Nominal RKAP</label>
					<input type="text" class="form-control input-sm" id="nominalEntry" name="nominalEntry" readonly value="195.000.000.003">
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
					<label class="control-label font-size-12">Justification</label>
					<input type="text" class="form-control input-sm" id="fsEntry" name="fsEntry" readonly value="0">
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
					<label class="control-label font-size-12">Fund Avl RKAP</label>
					<input type="text" class="form-control input-sm" id="faRkapEntry" name="faRkapEntry" readonly value="0">
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
					<label class="control-label font-size-12">Fund Avl Justif</label>
					<input class="form-control input-sm" id="avdEntry" name="avdEntry" type="text" readonly value="0">
				</div>
			</div>
			<div class="col-md-2<?= (in_array("Budget Controller", $group_name)) ? '' : ' d-none' ?>">
				<div class="form-group">
					<label class="control-label font-size-12">Fund Avl BUFFER (PR - PO)</label>
					<input class="form-control input-sm" id="avdEntrybck" name="avdEntrybck" type="text" readonly value="0">
				</div>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="white-box boxshadow m-b-0 mt-10 border-top-only-5 z-index-2 panel-title-custom">
		<div class="col-md-6">
			<h5 class="font-weight-700 m-0 text-uppercase">Informasi Justifikasi</h5>
		</div>
	</div>
	<div class="white-box boxshadow mt-0 z-index-2 py-10 border-bottom-only-5 mb-0">
		<div class="row">
			Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rerum cum ad perferendis fuga, quae asperiores aut explicabo suscipit itaque, ipsam accusamus incidunt deleniti saepe. Perferendis ducimus maiores veritatis nesciunt facere.
		</div>
		<div class="row">
			<div class="col-md-12">
	    		<table id="table_data" class="table table-hover small display table-striped table-responsive dataTable w-full">
		            <thead>
		              <tr>
		                <th>Judul Justifikasi</th>
		                <th>Tanggal Upload</th>
		                <th>Status</th>
		                <th>Action</th>
		              </tr>
		            </thead>
	          </table>
	    	</div>
		</div>
	</div>
</div>