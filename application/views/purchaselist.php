<?php
$title='Purchase';
include('header.php'); ?>
<div class="container">
    <div class="row mt-5">
        <div class="col-md-12">
            <h3><?php echo $title?></h3>
            <hr />
            <!--- Success Message --->
            <div id="flash-messages">
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
                    <a href="<?php echo site_url('purchase/PurchaseAdd'); ?>" class='mb-5'>
                        <button class="btn btn-primary"> Add Purchase</button></a>
                </div>
                <div class="col-md-10">
                    <form action="<?php echo base_url(); ?>index.php/purchase/search" method="post">
                        <div class="input-group col-md-6">
                            <input type="text" name="search" id="search" list="search1" class="form-control "
                                placeholder="Search By Name" autocomplete='off' required>
                            <datalist id='search1' required name='search1'>
                                <?php
							
							$this->load->model('PurchaseModel', 'Purchase');
							$supplernamelist=$this->Purchase->Supplerlistpurchase();

							foreach ($supplernamelist as $row) { ?>
                                <option data-value='<?php echo $row->suppler_id?>' value=<?php echo $row->FirstName; ?>
                                    <?php echo set_select('supplername',$row->FirstName); ?>>
                                </option>
                                <?php
							}
							?>
                            </datalist>
                        </div>
                        <div class="input-group col-md-6" style="float: right; margin-top:-40px;">
                            <input type="submit" value="search" class="btn btn-success" name="save" /> &nbsp;&nbsp;
                            <a href="<?php echo base_url().'index.php/purchase' ?>" class="btn btn-warning">Clear</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="table-responsive mt-3">
                <table id="mytable" class="table table-bordred table-striped">
                    <thead>
                        <th>#</th>
                        <th style='display: none;'>id</th>
                        <th>Suppler Name</th>
                        <th>Bill No</th>
                        <th>Bill Date</th>
                        <th>Total Amt</th>
                        <th>Paid Amt</th>
                        <th>Paid Date</th>
                        <th>Payment Mode</th>
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
                        <tr id="<?php echo htmlentities($row->pur_id); ?>">
                            <td><?php echo htmlentities($cnt);?></td>
                            <td style='display: none;'><?php echo htmlentities($row->pur_id);?></td>
                            <td><?php echo htmlentities($row->FirstName);?></td>
                            <td><?php echo htmlentities($row->billno);?></td>
                            <td><?php echo htmlentities(date('d-m-Y',strtotime($row->billdate)));?></td>
                            <td><?php echo htmlentities($row->total_amt);?></td>
                            <td><?php echo htmlentities($row->amt_paid);?></td>
                            <td><?php echo htmlentities('d-m-Y',strtotime($row->paiddate));?></td>
                            <td><?php echo htmlentities($row->paymentmode);?></td>
                            <td>
                                <!-- <?php
								//for passing row id to controller for payment
								// echo  anchor("purchase/payment/$title/{$row->pur_id}/{$row->FirstName}",' Payment','class="fas fa-rupee-sign btn-xs btn btn-success " aria-hidden="true"')?>
								 -->
                                <button onclick="payment(this)" class=" btn-xs btn btn-success "><span
                                        class="fas fa-rupee-sign"> Payment</button>

                            </td>
                            <td>
                                <?php
								//for passing row id to controller for editing
								echo  anchor("purchase/getdetails/{$row->pur_id}/{$row->FirstName}",' Edit','class="fas fa-edit btn-xs" aria-hidden="true"')?>
                            </td>
                            <td>
                                <?php
								//for passing row id to controller
								//echo anchor("Delete/index/{$row->client_id}",'Delete','class="fas fa-trash-o  btn-xs" aria-hidden="true"' )?>
                                <button onclick="dlefunction(<?php echo $row->pur_id ?>)" class="btn-xs btndelete"><span
                                        class="fas fa-trash"> Delete</button>
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
    </div>
</div>
<?php include('footer.php'); ?>
<script type="text/javascript">
function payment(p){
	var currow = $(p).closest('tr');
	var totalAmt = currow.find('td:eq(5)').text();
	var date = new Date().toISOString().substr(0, 10);
    (async () => {

const { value: formValues } = await Swal.fire({
  title: 'Multiple inputs',
  html:
	'<label for="nameField" class="col-sm-12" >Total Amount</label>'+
	'<input id="swal-input1" class="swal2-input" value='+totalAmt+'  readonly style="background-color:#ccc">' +
	'<label for="Billno"><b>BillDate</b><sup class="star">*</sup></label>'+
	'<input class="form-control" id="paiddate"  data-format="dd/MM/yyyy" name="paiddate"  type="date" autocomplete="off" value='+date+' required />'+
	'<label for="nameField" class="col-sm-12" style="margin-top: 1em;">Remark</label>'+
	'<input id="swal-input2" class="swal2-input" autofocus>',
  //focusConfirm: false,
  preConfirm: () => {
	return [
	  document.getElementById('swal-input1').value,
	  document.getElementById('swal-input2').value
	]
  }
})

if (formValues) {
  Swal.fire(JSON.stringify(formValues))
}

})()
}

function dlefunction(id) {
    var id = "<?php echo base_url();?>index.php/purchase/delete/" + id;
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
                url: id,
                crossOringin: false,
                mothod: "POST",
                success: function(data) {
                    if (data.status == "success") {
                        Swal.fire({
                            title: 'Deleted!',
                            text: "Your file has been deleted.",
                            icon: 'success',
                            timer: 2000,
                            timerProgressBar: true,
                        }).then(() => {
                            location.reload();
                        })
                    } else if (data.status == "sales") {
                        Swal.fire({
                            title: 'Error!',
                            text: "your can't delete that bill because sum products are saled.",
                            icon: 'error',
                            timer: 6000,
                            timerProgressBar: true,
                        })
                    }

                }
            });
        }
    })
}
</script>