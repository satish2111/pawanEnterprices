<?php
$title='Clients';
include('header.php'); ?>
<div class="container">
	<div class="row mt-5">
		<div class="col-md-12">
			<h3>Clients</h3> <hr />
			<!--- Success Message --->
			<?php if ($this->session->flashdata('success')) { ?>
			<p style="font-size: 20px; color:green"><?php echo $this->session->flashdata('success'); ?></p>
			<?php }?>
			<!---- Error Message ---->
			<?php if ($this->session->flashdata('error')) { ?>
			<p style="font-size: 20px; color:red"><?php echo $this->session->flashdata('error'); ?></p>
			<?php } ?>
			<div class="row">
				<div class="col-md-2">
					<a href="<?php echo site_url('insert'); ?>" class='mb-5'>
					<button class="btn btn-primary"> Add Client</button></a>
				</div>
				<div class="col-md-10">
					<form action="<?php echo base_url(); ?>index.php/read/search/<?php echo $title?>" method="post" >
						<div class="input-group col-md-6">
							<input type="text" name="search" class="form-control " placeholder="Search By Name" autocomplete="off" required>
						</div>
						<div class="input-group col-md-6" style="float: right; margin-top:-40px;">
							<input type="submit" value="search" class="btn btn-success" name="save"/> &nbsp;&nbsp;
							<a href="<?php echo base_url().'index.php/read/userdata' ?>" class="btn btn-warning" tabindex="10">Clear</a>
						</div>
					</form>
				</div>
			</div>
			<div class="table-responsive mt-3">
				<table id="mytable" class="table table-bordred table-striped">
					<thead>
						<th>#</th>
						<th style='display: none;'>id</th>
						<th>First Name</th>
						<th>Last Name</th>
						<th>Email</th>
						<th>Contact</th>
						<th>Address</th>
						<th>Credit days</th>
						<th>Posting Date</th>
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
						<tr id="<?php echo htmlentities($row->client_id); ?>">
							<td><?php echo htmlentities($cnt);?></td>
							<td style='display: none;'><?php echo htmlentities($row->client_id);?></td>
							<td><?php echo htmlentities($row->FirstName);?></td>
							<td><?php echo htmlentities($row->LastName);?></td>
							<td><?php echo htmlentities($row->EmailId);?></td>
							<td><?php echo htmlentities($row->ContactNumber);?></td>
							<td><?php echo htmlentities($row->Address);?></td>
							<td><?php echo htmlentities($row->creditdays);?></td>
							<td><?php echo htmlentities(date('d-m-Y h:m:s',strtotime($row->PostingDate)));?></td>
							<td>
								<?php
								//for passing row id to controller
								echo  anchor("Read/getdetails/$title/{$row->client_id}",' Edit','class="fas fa-edit btn-xs" aria-hidden="true"')?>
							</td>
							<td>
								<?php
								//for passing row id to controller
								//echo anchor("Delete/index/{$row->client_id}",'Delete','class="fas fa-trash-o  btn-xs" aria-hidden="true"' )?>
								<button onclick="dlefunction(<?php echo $row->client_id ?>)" class="btn-xs btndelete"><span class="fas fa-trash"></span> Delete</button>
								

							</td>
						</tr>
						<?php
						// for serial number increment
						$cnt++;
						}
						}
						else {
						?><tr>
							<td colspan="10"> Records not found</td>
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
	</div>
</div>
<?php include('footer.php'); ?>
<script type="text/javascript">
function dlefunction(id)
	{
	var id="<?php echo base_url();?>index.php/delete/index/"+id;
	Swal.fire({title: 'Are you sure?',text: "You won't be able to revert this!",icon: 'warning',showCancelButton: true,confirmButtonColor: '#3085d6',cancelButtonColor: '#d33',confirmButtonText: 'Yes, delete it!'})
	.then((result) => {if (result.value) {
			$.ajax({url:id,crossOringin:false,mothod:"POST",
				success:function(data)
					{
						Swal.fire({
							title: 'Deleted!',text: "Your file has been deleted.",icon: 'success',timer: 2000,timerProgressBar: true,
								}).then(() => {location.reload();})
								}
							});
						}
					})
	}
</script>