<?php
$title='Party Wise Payment Details Report';
include('header.php');?>
<style>
.col-md-4 {
    margin: 0;
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
        </div>
    </div>
    <form action="<?php echo base_url(); ?>index.php/sale/partyWiseDetail" method="post">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group row align-items-center">
                    <div class="col-md-3">
                        <label for="laClient"><b>Client</b><sup class="star">*</sup></label>
                    </div>
                    <div class="col-md-9">
                        <input id="client" list="client1" placeholder="Select Client Name" type="text" name="Client"
                            class="form-control" style="text-transform: uppercase;">
                        <datalist id='client1' required name='client1'>
                            <?php
							$this->load->model('SaleModel', 'sale');
							$clientnamelist=$this->sale->clientlistPayment();
							foreach ($clientnamelist as $row)
					{ ?>
                            <option data-value='<?php echo $row->client_id?>'
                                data-createdate='<?php echo $row->creditdays?>' value="<?php echo $row->FirstName; ?>">
                            </option>
                            <?php
					}
					?>
                        </datalist>
                        <?php echo form_error('Client'); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group row align-items-center">
                    <div class="col-md-4">
                        <label for="Billno"><b>Start Date<sup class="star">*</sup></b></label>
                    </div>
                    <div class="col-md-8">
                        <input class="form-control" id="startdate" data-format="dd/MM/yyyy" name="stardate" type="date"
                            autocomplete="off" required tabindex="0" name='startdate' />
                    </div>
                </div>
                <?php echo form_error('billdate'); ?>
            </div>
            <div class="col-md-4">
                <div class="form-group row align-items-center">
                    <div class="col-md-4 ">
                        <label for="Billno"><b>End Date<sup class="star">*</sup></b></label>
                    </div>
                    <div class="col-md-8">
                        <input class="form-control" id="enddate" data-format="dd/MM/yyyy" name="enddate" type="date"
                            autocomplete="off" required tabindex="0" name='enddate' />
                    </div>
                </div>

                <?php echo form_error('billdate'); ?>
            </div>


            <?php echo form_error('billdate'); ?>
        </div>
        <div class="col-md-12 text-center">
            <button class="btn btn-success" id='search' title='Get Data'>Search</button>
            <a href="<?php echo base_url().'sale/report' ?>" class="btn btn-danger" title='Final Cancel'>Cancel</a>
            <button class="btn btn-primary" onclick='printContent(mytable)' id='print'>Print</button>

        </div>
    </form>
    <div class="table-responsive mt-3 blocks" id='mytable'>
    <?php if(isset($returnData['client'])){?> 
        <h4>Client Name : <?php echo $returnData['client']; ?></h4>
        <h4>From : <?php echo  $returnData['startdate']; ?> To : <?php echo  $returnData['enddate']; ?></h4>
        <table class="table table-bordred table-striped block-col">
            <thead>
                <th>#</th>
                <th>Bill No</th>
                <th>Name</th>
                <th>BIll Amount</th>
                <th>Paid Date</th>
                <th>Amount</th>
                <th>Outstanding</th>
                <th>Remark</th>
            </thead>
            <tbody>

                <?php  
                        $Amttotal=null;
                        $cnt=1;
                        $billno=$returnData['data'][0]->Fk_Sale_id;
                        $Outstanding=null;
                        $prvAmt=null;
                        foreach($returnData['data'] as $key => $rw) {  $Amttotal+= ($rw->Amt); 
                            if($billno==$rw->Fk_Sale_id)
                            {
                                if($Outstanding==null)
                                {
                                    $Outstanding=$rw->TotalAmt-$rw->Amt;
                                    $prvAmt=$Outstanding;
                                }
                                else {
                                     $Outstanding=$prvAmt-$rw->Amt;
                                    

                                     $prvAmt=$Outstanding;
                                }
                            }
                            else{
                                $billno=$rw->Fk_Sale_id;
                                $Outstanding=$rw->TotalAmt-$rw->Amt;
                                $prvAmt =$Outstanding;
                            }
                         ?>
                <tr id='<?php echo $rw->Fk_Sale_id;?>'>
                    <td><?php echo $cnt;?></td>
                    <td><?php echo $rw->Fk_Sale_id;?></td>
                    <td><?php echo $rw->Name; ?> </td>
                    <td><?php echo $rw->TotalAmt; ?> </td>
                    <td><?php echo (date('d-m-Y',strtotime($rw->DateOfPaid)));?></td>
                    <td><?php echo $rw->Amt;?></td>
                    <td><?php echo $Outstanding;?></td>
                    <td><?php echo $rw->Remark;?></td>
                    
                </tr>
                <?php 
                    
                    $cnt++; }?>
                <tr>
                    <td colspan='6'>
                        <h4 style='float:right;color:green; '><?php echo number_format($Amttotal,2)?>
                        </h4>
                    </td>
                    <td colspan='2'></td>
                    
                </tr>
            </tbody>
        </table>
        <?php } 
        ?>
    </div>
</div>
<div class="pageloader" style='display: none;' id='pageloader'></div>
<?php include('footer.php'); ?>
<script>
$(document).ready(function() {

    $('#startdate').val(new Date().toISOString().substr(0, 10));

    $('#enddate').val(new Date().toISOString().substr(0, 10));

    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();
    if (dd < 10) {
        dd = '0' + dd
    }
    if (mm < 10) {
        mm = '0' + mm
    }
    today = yyyy + '-' + mm + '-' + dd;

    $("#client").blur(function() {
        var textboxvalue = document.getElementById("client").value;

        if (textboxvalue != '') {
            document.getElementById("pageloader").style.display = "block";
            var data = {
                'textboxvalue': textboxvalue,
            };
            $.ajax({
                data: data,
                type: "POST",
                url: "/pawanenterprises/sale/checkuser",
                crossOrigin: false,
                dataType: 'json',
                success: function(result) {
                    $(".pageloader").fadeOut("slow");
                    if (result.status == "success") {


                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Client Name Not Found',
                            icon: 'error',
                            showCancelButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                        });
                        document.getElementById("Client").value = '';
                        $("#Client").focus();
                    }
                }
            });
        }
    });
    //client vaild from backend end//
});

function printContent(el) {
    var restorepage = document.body.innerHTML;
    var printcontent = document.getElementById("mytable").innerHTML;
    document.body.innerHTML = printcontent;
    window.print();
    document.body.innerHTML = restorepage;
    $('#startdate').val(new Date().toISOString().substr(0, 10));
    $('#enddate').val(new Date().toISOString().substr(0, 10));
}
</script>