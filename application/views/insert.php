<?php
$title='Clients-Add';
include('header.php'); ?>
<div class="container">
	<div class="row mt-5">
		<div class="col-md-12 ">
			<h3><?php echo $title?></h3>
			<hr />
		</div>
	</div>

	
	<?php echo form_open('insert/InsertTblusers',['name'=>'insertdata','autocomplete'=>'off']);?>
	<div class="row ">
		<div class="col-md-4"><b>First Name <sup class="star">*</sup></b>
			<?php echo form_input(['name'=>'firstname','class'=>'form-control','placeholder'=>'First Name','required'=>'required','value'=>set_value('firstname')]);?>
			<?php echo form_error('firstname',"<div style='color:red'>","</div>");?>
		</div>
		<div class="col-md-4"><b>Last Name <sup class="star">*</sup></b>
			<?php echo form_input(['name'=>'lastname','class'=>'form-control','placeholder'=>'Last Name','required'=>'required','value'=>set_value('lastname')]);?>
			<?php echo form_error('lastname',"<div style='color:red'>","</div>");?>
		</div>
		<div class="col-md-4"><b>Email id <sup class="star">*</sup></b>
			<?php echo form_input(['name'=>'emailid','class'=>'form-control','placeholder'=>'Email id','required'=>'required','value'=>set_value('emailid')]);?>
			<?php echo form_error('emailid',"<div style='color:red'>","</div>");?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4"><b>Credit days<sup class="star">*</sup></b>
			<?php echo form_input(['name'=>'creditdays','onkeypress'=>'return isNumberKey(event)','class'=>'form-control','maxlength'=>'2','minlength'=>'1','placeholder'=>'Credit days','required'=>'required','value'=>set_value('creditdays')]);?>
			<?php echo form_error('creditdays',"<div style='color:red'>","</div>");?>
		</div>
		<div class="col-md-4"><b>Contactno <sup class="star">*</sup></b>
			<?php echo form_input(['name'=>'contactno','class'=>'form-control','placeholder'=>'Contactno','required'=>'required','maxlength'=>'10','minlength'=>'10','onkeypress'=>'return isNumberKey(event)','value'=>set_value('contactno')]);?>
			<?php echo form_error('contactno',"<div style='color:red'>","</div>");?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12"><b>Address <sup class="star">*</sup></b>
			<?php echo form_textarea(['name'=>'address','class'=>'form-control','placeholder'=>'Address','rows'=>'04','value'=>set_value('address')]);?>
			<?php echo form_error('address',"<div style='color:red'>","</div>");?>
		</div>
	</div>
	<div class="row" style="margin-top:1%">
		<div class="col-md-12 text-center">
			<?php echo form_submit(['name'=>'insert','value'=>'Submit','class' => 'btn btn-primary']);?>
			<a href="<?php echo base_url();?>read/userdata" id="cancel" name="cancel" class="btn btn-danger">Cancel</a>
		</div>
	</div>
	
	<?php echo form_close();?>
</div>
</div>
<?php include('footer.php'); ?>

<script type="text/javascript">
	function isNumberKey(evt) {
	var charCode = (evt.which) ? evt.which : event.keyCode
	if (charCode > 31 && (charCode < 48 || charCode > 57))
		return false;
	return true;
}
</script>