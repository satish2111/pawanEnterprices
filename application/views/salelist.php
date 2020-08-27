<?php
$title='Sale';
include('header.php'); ?>
<div class="container">
	<div class="row mt-5">
		<div class="col-md-12 ">
			<h3><?php echo $title?></h3>
			<hr />
			<!--- Success Message --->
			<div  id="flash-messages">
				<?php if ($this->session->flashdata('success')) { ?>
				<p style="font-size: 20px; color:green"><?php echo $this->session->flashdata('success'); ?></p>
				<?php }?>
				<!---- Error Message ---->
				<?php if ($this->session->flashdata('error')) { ?>
				<p style="font-size: 20px; color:red"><?php echo $this->session->flashdata('error'); ?></p>
				<?php } ?>
			</div>
			<div class="row">
				<div class="col-md-2">
					<a href="<?php echo base_url().'sale/add'; ?>" class='mb-5'>
					<button class="btn btn-primary"> Add Sale</button></a>
				</div>
				<div class="col-md-10">
					<form action="<?php echo base_url(); ?>sale/search" method="post" >
						<div class="input-group col-md-6">
							<input type="text" name="search" id="search" list="search1" class="form-control " placeholder="Search By Name" autocomplete='off'  required>
							<datalist  id='search1' required  name='search1'>
							<?php
							$this->load->model('SaleModel', 'sale');
							$clientnamelist=$this->sale->clientlistpurchase();
							foreach ($clientnamelist as $row) { ?>
							<option data-value='<?php echo $row->client_id?>' value = <?php echo $row->FirstName; ?> <?php echo set_select('supplername',$row->FirstName); ?>>
							</option>
							<?php
							}
							?>
							</datalist >
						</div>
						<div class="input-group col-md-6" style="float: right; margin-top:-40px;">
							<input type="submit" value="search" class="btn btn-success" name="save"/> &nbsp;&nbsp;
							<a href="<?php echo base_url().'sale' ?>" class="btn btn-warning">Clear</a>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="table-responsive mt-3">
			<table id="mytable" class="table table-bordred table-striped">
				<thead>
					<th>#</th>
					<th style='display: none;'>id</th>
					<th>Bill No</th>
					<th>Bill Date</th>
					<th>Client Name</th>
					<th>Total Amt</th>
					<th>Paid Amt</th>
					<th>Paid Date</th>
					<th>Print</th>
					<th>Payment</th>
					<th>Edit</th>
					<th>Delete</th>	
				</thead>
				<tbody>
					<?php
					$cnt=1;
					if(!empty($result))
					{
						foreach($result as $row)
						{
					?>
					<tr id="<?php echo htmlentities($row->Sale_id); ?>">
						<td><?php echo htmlentities($cnt);?></td>
						<td style='display: none;'><?php echo htmlentities($row->Sale_id);?></td>
						<td><?php echo htmlentities($row->Sale_id);?></td>
						<td><?php echo htmlentities(date('d-m-Y',strtotime($row->Billdate)));?></td>
						<td><?php echo htmlentities($row->FirstName.' '.$row->LastName);?></td>
						<td><?php echo htmlentities($row->TotalAmt);?></td>
						<td><?php echo htmlentities($row->PaidAmt);?></td>
						<td><?php echo htmlentities('d-m-Y',strtotime($row->lastpaiddate));?></td>
						<td>
							<?php echo  anchor("sale/billprint/{$row->Sale_id}",' Print','class="fas fa-print btn-xs btn btn-primary " aria-hidden="true"') ?>
						</td>
						<td>
							<?php
							//for passing row id to controller for payment
							echo  anchor("sale/payment/$title/{$row->Sale_id}/{$row->Sale_id}",' Payment','class="fas fa-rupee-sign btn-xs btn btn-success " aria-hidden="true"')?>
						</td>
						<td>
							<?php
							//for passing row id to controller for editing
							echo  anchor("sale/getdetails/{$row->Sale_id}",' Edit','class="fas fa-edit btn-xs btn btn-dark" aria-hidden="true"')?>
						</td>
						<td>
							<!-- delete from database-->
							<button style='line-height: 1'onclick="dlefunction(<?php echo $row->Sale_id ?>)" class="btn-xs btn btn-danger"><span class="fas fa-trash"></span> Delete</button>
						</td>
					</tr>
					<?php
					// for serial number increment
					$cnt++;
					}
					}
					else {
					?><tr>
						<td colspan="12" style="color:red">Today's Records not found</td>
					</tr>
					<?php
					}
					?>
					<?php
					?>
				</tbody>
			</table>
			<div style="margin-left:2%;">
				<?php echo $this->pagination->create_links(); ?>
			</div>
		</div>
	</div>
	<div class="pageloader" style='display: none;' id='pageloader'></div>
</div>
<?php include('footer.php'); ?>
<script type="text/javascript">
function dlefunction(id){
	var id="<?php echo base_url();?>index.php/sale/delete/"+id;
	Swal.fire({
		title: 'Are you sure?',
		text: "You won't be able to revert this!",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
		if (result.value) {
			$.ajax({
			url:id,
			crossOringin:false,
			mothod:"POST",
			success:function(data)
			{
				Swal.fire({
				title: 'Deleted!',
				text: "Your file has been deleted.",
				icon: 'success',
				timer: 2000,
				timerProgressBar: true,
				})
			.then(() => {
			location.reload();
			})
			}
			});
		}
	})
}
</script>