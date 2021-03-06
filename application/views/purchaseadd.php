<?php
$title='Purchase-Add';
include('header.php'); ?>
<div class="container">
	<div class="row mt-5">
		<div class="col-md-12 ">
			<h3><?php echo $title?></h3>
			<hr />
		</div>
	</div>
	<style type="text/css" media="screen">
	.col-md-4{
		margin-bottom: 0rem;
		}
	.form-group{
		margin-bottom: 0.9rem;
	}
	</style>
	<div class="row">
		<div class="col-md-4">
			<div class="form-group row align-items-center">
				<div class="col-md-3">
					<input type="hidden" id="session" value="<?php echo $this->session->id;?>">
					<label for="Billno"><b>BillNo.</b> <sup class="star">*</sup></label>
				</div>
				<div class="col-md-9">
					<input type="text" name="txtbillno" value="" id='billno' placeholder="Billno" class="form-control" >
				</div>
			</div>
			<?php echo form_error('billno'); ?>
		</div>
		<div class="col-md-4">
			<div class="form-group row align-items-center">
				<div class="col-md-3">
					<label for="Billno"><b>BillDate</b><sup class="star">*</sup></label>
				</div>
				<div class="col-md-9">
					<input class="form-control" id="date"  data-format="dd/MM/yyyy" name="date"  type="date" autocomplete="off" required />
				</div>
			</div>
			<?php echo form_error('billdate'); ?>
		</div>
		<div class="col-md-4">
			<div class="form-group row align-items-center">
				<div class="col-md-3">
					<label for="Suppler"><b>Suppler</b><sup class="star">*</sup></label>
				</div>
				<div class="col-md-9">
					<input id="supplername" list="supplername1" placeholder="Select Suppler"  type="text" name="supplername" class="form-control"  style="text-transform: uppercase;">
					<datalist  id='supplername1' required  name='supplername1'>
					<?php
					$columnnames='suppler_id,FirstName';
					$tablename='tblsuppler';
					
					$this->load->model('PurchaseModel', 'Purchase');
					$supplerdata=$this->Purchase->Supplerlist($columnnames,$tablename);
					foreach ($supplerdata as $row) { ?>
					<option data-value='<?php echo $row->suppler_id?>' value = <?php echo $row->FirstName; ?> <?php echo set_select('supplername',$row->FirstName); ?>>
					</option>
					<?php
					}
					?>
					</datalist >
					<?php echo form_error('supplername'); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<div class="form-group row align-items-center">
				<div class="col-md-3">
					<label for="Product"><b>Product</b><sup class="star">*</sup></label>
				</div>
				<div class="col-md-9">
					<input type="text" name="txtProduct" value="" id ='productname' placeholder="Product Name" class="form-control" required>
				</div>
				<?php echo form_error('product'); ?>
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group row align-items-center">
				<div class="col-md-6">
					<label for="Qty"><b>Qty</b> <sup class="star">*</sup></label>
				</div>
				<div class="col-md-6">
					<input type="text" name="txtProduct" value="" id='quantity' placeholder="Qty" oninput="calculate()" class="form-control" onkeypress="return isNumberKey(event)" maxlength="5" required>
				</div>
			</div>
			<?php echo form_error('quantity'); ?>
		</div>
		<div class="col-md-2">
			<div class="form-group row align-items-center">
				<div class="col-md-5">
					<label for="cost"><b>Cost</b> <sup class="star">*</sup></label>
				</div>
				<div class="col-md-7">
					<input type="text" name="txtcost" value="" id='cost' placeholder="Cost" class="form-control" oninput="calculate()" maxlength="7" required>
				</div>
			</div>
			<?php echo form_error('cost'); ?>
		</div>
		<div class="col-md-2">
			<div class="form-group row align-items-center">
				<div class="col-md-5">
					<label for="mrp">DP<small style='font-size: 0.8rem;'>(MRP)</small> <sup class="star" style='float: right;margin-top: -0.7rem;'>*</sup></label>
				</div>
				<div class="col-md-7">
					<input type="text" name="txtmra" value="" id='mrp' placeholder="MRP" class="form-control" onkeypress="return isNumberKey(event)" maxlength="7" required>
				</div>
			</div>
			<?php echo form_error('mrp'); ?>
		</div>
		<div class="col-md-2">
			<div class="form-group row align-items-center">
				<div class="col-md-4">
					<label for="mrp"><b>Gross</b></label>
				</div>
				<div class="col-md-8">
					<input type="text" name="txtgross" tabindex='-1' value="" id='gross' placeholder="Gross" class="form-control" readonly>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="col-sm-12 offset-4">
				<button  class="btn btn-primary"  id='btnsave' title="Save In Table">Save</button>
				<a href="<?php echo base_url().'index.php/purchase' ?>" class="btn btn-danger" id='canel' title="Cancel the Data" >Cancel</a>
				<button  class="btn btn-warning"  name='reset' id='reset' title="Reset the BoxText">Reset</button>
			</div>
		</div>
	</div>
	<hr />
	<div class="row" id ="totalpanel" style='display: none;background-color:#cccccc47; padding:1rem 0; border-radius: 15px'>
		<div class="col-md-4" >
			<div class="form-group row align-items-center">
				<div class="col-md-4">
					<label for="nameField" class="col-sm-9">Total_Item:-</label>
				</div>
				<div class="col-md-8">
					<label for="nameField"  name="totalitem" id="totalitem" class="col-sm-3" style="color:red; font-weight:500; font-size:1.2rem;">0</label>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group row align-items-center">
				<div class="col-md-4">
					<label for="nameField" class="col-sm-9">Total_QTY</label>
				</div>
				<div class="col-md-8">
					<label for="nameField" name="totalqty" id="totalqty" class="col-sm-3" style="color:red; font-weight:500; font-size:1.2rem;">0</label>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group row align-items-center">
				<div class="col-md-4">
					<label for="nameField" class="col-sm-2" >Total:- </label>
				</div>
				<div class="col-md-8">
					<input type="text" name='totalgross' id='totalgross' class="form-control" value="" style="color:red; font-weight:500; font-size:1.2rem;" />
				</div>
			</div>
		</div>
		<div class="col-md-12 offset-5">
			<button  class="btn btn-primary" id='done' title='Final Save' >Done</button>
			<a href="<?php echo base_url().'index.php/purchase' ?>" class="btn btn-danger" title='Final Cancel'>Cancel</a>
		</div>
	</div>	
	
	<br/>
	<div class="row">
		<div class="col-md-12 table-responsive">			
			<table id="table1" name="table1" class="table table-bordered table-striped" style="display: none;">
				<thead>
					<tr>
						<th>SRNO</th>
						<th>ProductName</th>
						<th>Qty</th>
						<th>Cost</th>
						<th>MRP (DP)</th>
						<th>Gross</th>
						<th>Remove</th>
						<th style="display:none;">date</th>
						<th style="display:none;">billno</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div>
	<div class="pageloader" style='display: none;' id='pageloader'></div>
	

</div>

<?php include('footer.php'); ?>
<script src="<?php echo base_url('assests/js/purchaseAdd.js'); ?>"></script>
<script type="text/javascript">
	function dateclick() {
	document.getElementById("date").innerHTML = new Date().toISOString().substr(0, 10);
}
</script>
