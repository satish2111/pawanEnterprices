<?php
$title='Sale';
include('header.php'); ?>
<style>
.col-md-4 {
    margin-bottom: 0.8rem;
    width: 50%;
    display: inline-flex;

}
.swal2-input{
    margin: 0.5rem 0rem!important;
}
.swal2-actions
{
    margin: 0.2em auto 0!important;
}
.size{
    width: 107px;
}
.size1{
    width:112px;
}


.table-responsive{
    overflow-x: hidden;
}
</style>
<div class="container">
    <div class="row mt-5">
        <div class="col-md-12 ">
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
			<div class="col-sm-12 text-center" style='color:red;'><h6 style='margin-right: 6rem;'>Please Enter Only One TextBox</h6></div>
                <div class="col-md-2">
                    <a href="<?php echo base_url().'sale/add'; ?>" class='mb-5'>
                        <button class="btn btn-primary"> Add Sale</button></a>
                </div>
                <div class="col-md-10">
                    <form action="<?php echo base_url(); ?>sale/search" method="get" onsubmit="return ValidationEvent()">
                        <div class=" input-group col-md-4">
                            <input type="text" name="search" id="search" list="search1" class="form-control "
                                placeholder="Search By Name" autocomplete='off' require>
                                <input type="hidden" name="selectclientid" id='selectclientid' value="">
                            <datalist id='search1' name='search1'>
                                <?php
							$this->load->model('SaleModel', 'sale');
							$clientnamelist=$this->sale->clientlistpurchase();
							foreach ($clientnamelist as $row) { ?>
                                <option data-value='<?php echo $row->client_id?>' value="<?php echo $row->FirstName; ?>"
                                    <?php echo set_select('supplername',$row->FirstName); ?> >
                                </option>
                                <?php
							}
							?>
                            </datalist>
                        </div>
                        <div class=" input-group col-md-4">
                            <input type="text" name="productname" list='productname1' value="" id='productname'
                                placeholder="Product Name" class="form-control" require>
                            <datalist id='productname1' name='productname1'>
                                <?php
							$this->load->model('SaleModel', 'sale');
                            $productnamelist=$this->sale->productDatalist();
							
							foreach ($productnamelist as $productData)
							{ ?>
                                <option data-value='<?php echo $productData->Qty ?>'
                                    data-mrp='<?php echo $productData->MRP?>'
                                    value="<?php echo $productData->ProductName ?>">
                                </option>
                                <?php
							}
								?>
                            </datalist>
                        </div>
                        <div class="input-group col-md-3" style="float: right;margin-right: 6%;">
                            <input type="submit" value="search" class="btn btn-success" name="save"  id=btnsearch/> &nbsp;&nbsp;
                            <a href="<?php echo base_url().'sale' ?>" class="btn btn-warning">Clear</a>
                        </div>
                    </form>
				</div>
				
			</div>
			
        </div>
        <div class="table-responsive mt-3">
            <table id="mytable" class="table table-bordred table-striped">
                <thead>
                    <th style='display:none'>#</th>
                    <th style='display: none;'>id</th>
                    <th>Bill No</th>
                    <th>Bill Date</th>
                    <th>Client Name</th>
                    <th>Total Amt</th>
                    <th>Paid Amt</th>
                    <th>Paid Date</th>
                    <th>Outstanding Amt</th>
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
                        <td style='display:none'><?php echo htmlentities($cnt);?></td>
                        <td style='display: none;'><?php echo htmlentities($row->Sale_id);?></td>
                        <td><?php echo htmlentities($row->Sale_id);?></td>
                        <td class='size'><?php echo htmlentities(date('d-m-Y',strtotime($row->Billdate)));?></td>
                        <td><?php echo htmlentities($row->FirstName.' '.$row->LastName);?></td>
                        <td><?php echo htmlentities($row->TotalAmt);?></td>
                        <td><?php echo htmlentities($row->PaidAmt);?></td>
                        <td class='size1'><?php echo htmlentities(date('d-m-Y',strtotime($row->lastpaiddate)));?></td>
                        <td><?php echo htmlentities($row->OutstandingAmt);?></td>
                        <?php if(floatval($row->TotalAmt)==floatval($row->PaidAmt))
                        {
                        ?>
                        <td>
                            <?php echo  anchor("sale/billprint/{$row->Sale_id}",' Print','class="fas fa-print btn-xs btn btn-primary " aria-hidden="true"') ?>
                        </td>
                        <td>
                           
                            <!-- for passing row id to controller for payment -->
                             <button onclick="payment(this,<?php echo  $row->Sale_id;?>)" disabled
                                    class=" btn-xs btn btn-success"><span class="fas fa-rupee-sign "> Payment</button>
                        </td>
                        <td>
                            <?php
							//for passing row id to controller for editing
							echo  anchor("sale/getdetails/{$row->Sale_id}",' Edit','class="fas fa-edit btn-xs btn btn-dark" aria-hidden="true"')?>
                        </td>
                        <td>
                            <!-- delete from database-->
                            <button style='line-height: 1' onclick="dlefunction(<?php echo $row->Sale_id ?>)"
                                class="btn-xs btn btn-danger"><span class="fas fa-trash"></span> Delete</button>
                        </td>
                        <?php 
                        } else{ ?> 

                        <td>
                            <?php echo  anchor("sale/billprint/{$row->Sale_id}",' Print','class="fas fa-print btn-xs btn btn-primary " aria-hidden="true"') ?>
                        </td>
                        <td>
                           
                            <!-- for passing row id to controller for payment -->
                             <button onclick="payment(this,<?php echo  $row->Sale_id;?>)" 
                                    class=" btn-xs btn btn-success"><span class="fas fa-rupee-sign "> Payment</button>
                        </td>
                        <td>
                            <?php
							//for passing row id to controller for editing
							echo  anchor("sale/getdetails/{$row->Sale_id}",' Edit','class="fas fa-edit btn-xs btn btn-dark" aria-hidden="true"')?>
                        </td>
                        <td>
                            <!-- delete from database-->
                            <button style='line-height: 1' onclick="dlefunction(<?php echo $row->Sale_id ?>)"
                                class="btn-xs btn btn-danger"><span class="fas fa-trash"></span> Delete</button>
                        </td>
                        <?php } ?>
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
 function ValidationEvent() {
     var clientname=document.getElementById("search").value;
     var productname=document.getElementById("productname").value;
     if(clientname=='' && productname=='')
     {
        // alert('hi');
        Swal.fire({
                    title: 'Error!',
                    text: "Please Select Client name Or Product Name",
                    icon: 'error',
                    timer: 2000,
                    timerProgressBar: true,
                        })
        return false;
        
     }
 };

$(document).ready(function() {
    $('#swal-input1').blur(function() {
        console.log('tet');
    })
    $("#search").blur(function() {
        $("#search option").each(function(i, el) {
                            data[$(el).data("value")] = $(el).val();
        });
        var client = $('#search').val();
        var clientId = $('#search1 [value="' + client + '"]').data('value');
        document.getElementById("selectclientid").value=clientId;
    });

    
});
function dlefunction(id) {
    var id = "<?php echo base_url();?>index.php/sale/delete/" + id;
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


function payment(p, id) {
    var currow = $(p).closest('tr');
    var billAmt=currow.find('td:eq(5)').text();
    var paidAmt=currow.find('td:eq(6)').text();
    var outstandingAmt=currow.find('td:eq(8)').text();
    var nowPay=parseFloat(billAmt)-parseFloat(paidAmt);
    if(parseFloat(outstandingAmt)==parseFloat(nowPay))
    {
        var totalAmt = nowPay.toFixed(2);
    }
    var date = new Date().toISOString().substr(0, 10);
    var billno = '<h4 style="color:green">Payment Of Bill No ' + currow.find('td:eq(2)').text() + '<h4>';

    (async () => {

        const {
            value: formValues
        } = await Swal.fire({
            title: billno,
            html: '<label for="nameField" class="" style="color:red;float: left;" >Total Bill Amount :- </label>' +
                  '<label for="nameField" class="" style="color:green;float: left;" >&nbsp;'+ billAmt+'</label>'+'<br/><br/>'+
                  '<label for="nameField" class="" style="color:red;float: left;" >Total Paid Amount :- </label>' +
                  '<label for="nameField" class="" id="lbtPaidAmt" style="color:green;float: left;">&nbsp;'+ paidAmt+'</label>'+'<br/><br/>'+
                  '<label for="nameField" class="" style="color:red;float: left;" >Total Outstanding Amount :- </label>' +
                  '<label for="nameField" class="" style="color:green;float: left;" >&nbsp;'+ outstandingAmt+'</label>'+'<br/><br/>'+
                  '<label for="nameField" class="col-sm-12" style="margin-top:0em; padding:0rem;color:green">Now Pay</label>' +
                  '<input id="swal-input1" class="swal2-input form-control"  placeholder="'+nowPay+'" autocomplete ="off">' +
                  '<label for="Billno"><b>Payment Date</b><sup class="star">*</sup></label>' +
                  '<input class="form-control" id="paiddate"  autofocus="autofocus" data-format="dd/MM/yyyy" name="paiddate" style="color:red"  type="date" autocomplete="off" value=' +
                  date + ' required />' +
                  '<label for="nameField" class="col-sm-12" style="margin-top: 1em;color:green">Remark<small Style="color:red"> (For Reference)</small></label>' +
                  '<input id="swal-input2" class="swal2-input form-control" placeholder="Google Pay, NEFT, RTGS " >' +
                 '<h5 for="nameField" style="margin-top: 0.5em;font-size: 0.9rem; color:red;">Note * : Once You click save that mean payment is done and Please Try Avoid Editing after Payment.</h5>',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showCloseButton: true,
            focusConfirm: false,
            position: 'top-end',
            confirmButtonText: '<i class="fa fa-thumbs-up"></i> Save !',
            confirmButtonAriaLabel: 'Thumbs up, great!',
            cancelButtonText: '<i class="fa fa-thumbs-down"></i> Cancel',
            cancelButtonAriaLabel: 'Thumbs down',
            preConfirm: () => {
                if (document.getElementById('swal-input1').value) {
                // Handle return value 
                return [
                    document.getElementById('swal-input1').value,
                    document.getElementById('paiddate').value,
                    document.getElementById('swal-input2').value
                ]
                } else {
                    Swal.showValidationMessage('Pay Now Is Blank')   
                }
                
            }
        })

        if (formValues) {
            var payAmount=document.getElementById('swal-input1').value;
            if(payAmount<=nowPay)
            {
                var CurrentPaidAmt=(parseFloat(document.getElementById('swal-input1').value)+parseFloat(paidAmt)).toFixed(2);
                var CurrentoutstandingAmt=(parseFloat(billAmt)-CurrentPaidAmt).toFixed(2);
                var AmtDetailtabel=(parseFloat(document.getElementById('swal-input1').value)).toFixed(2);
                var urlid = "<?php echo base_url();?>index.php/sale/billPayment/" + id;
                var billdetail = {
                'Sale_id': currow.find('td:eq(2)').text(),
                'TotalAmt': currow.find('td:eq(5)').text(),
                'PaidAmt': CurrentPaidAmt,
                'lastpaiddate': document.getElementById('paiddate').value,
                'Amt': AmtDetailtabel,
                'OutstandingAmt':CurrentoutstandingAmt,
                'Remark': document.getElementById('swal-input2').value,
            }
            var data = {
                'billdetail': billdetail
            }
            console.log(data);
            $.ajax({
                data: data,
                type: "POST",
                url: urlid,
                crossOrigin: false,
                dataType: 'json',
                success: function(data) {
                    if (data.status == "success") {
                        Swal.fire({
                            title: 'Payment!',
                            text: "Updated !!!",
                            icon: 'success',
                            timer: 2000,
                            timerProgressBar: true,
                        }).then(() => {
                            location.reload();
                        })
                    }
                }
            })
               // Swal.fire(JSON.stringify(formValues))
            }
            else{
                var MsgError='The Amount you Enter is Max then Outstanding.  Outstanding Is  '+nowPay +' And You are to Pay That ' +payAmount;
                Swal.fire({
                    title: 'Error',
                    text: MsgError,
                    icon: 'error',
                    showCancelButton: false,
                    timer: 10000,
                    timerProgressBar: true,
                });
            }
            
            
            
        }

    })()
}
</script>