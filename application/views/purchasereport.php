<?php
$title='Suppler Wise Report';
include('header.php');?>
<style>
.col-md-4 {
    margin: 0;
}

.col-md-12>button,
.col-md-12>a {
    margin-right: 1rem;
}

.btn-warning {
    font-weight: 900;
}

.btn-warning:hover {
    color: #fff;
    background-color: #000;
    border: 1px solid #000;
}
.btnprint{
    float: right;
    margin-right: 35%;
    margin-top: -3.3%;
}
@media print {

    #mytable,
    .table td,
    .table th {
        border: 1px solid #000;
    }
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

    <form action="<?php echo base_url(); ?>index.php/purchase/purchaseReportData" method="post">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group row align-items-center">
                    <div class="col-md-4">
                        <label for="Billno"><b>Start Date<sup class="star">*</sup></b></label>
                    </div>
                    <div class="col-md-8">
                        <input class="form-control" id="startdate" data-format="dd/MM/yyyy" name="startdate" type="date"
                            autocomplete="off" required tabindex="0" />
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
                            autocomplete="off" required tabindex="0" />
                    </div>
                </div>

                <?php echo form_error('billdate'); ?>
            </div>
            <div class="col-md-4">
                <div class="form-group row align-items-center">
                    <div class="col-md-4 ">
                        <label for="Paid"><b>Paid/UnPaid<sup class="star">*</sup></b></label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" name="PaidUnPaid" list='PaidUnPaid1' value="" id='PaidUnPaid'
                            placeholder="Paid / Un-Paid" class="form-control" required>
                        <datalist id='PaidUnPaid1' required name='PaidUnPaid1'>
                            <option value="Paid">Paid</option>
                            <option value="Un-Paid">Un-Paid</option>
                        </datalist>
                    </div>
                </div>

                <?php echo form_error('billdate');  ?>
            </div>
            <div class="col-md-12 text-center">
                <button class="btn btn-success" id='search' title='Get Data'>Search</button>
                <a href="<?php echo base_url().'Purchase/purchasereport' ?>" class="btn btn-danger"
                    title='Final Cancel'>Cancel</a>
              
            </div>
    </form>
</div>
 
<button class="btn btn-primary btnprint" onclick='printContent(totalmy)' 
                    id='print'>Print</button>
<div id='totalmy' class='mt-5' >

    <table id="total" class="table table-bordred table-striped">
        <thead>
            <tr>
                <td colspan='8'>
                <?php if(!empty($returnData))
				{ ?>
                    <h2 class='text-center'>Type : <?php echo $returnData['paidornot'] ?></h2>
                    <h4 class='text-center'>Total As No <br /> From:- <?php echo $returnData['startdate'] ?> To :-
                        <?php echo $returnData['enddate'] ?> </h4>
                       
                <?php } ?>
                </td>
            </tr>
            <tr>
                <td>#</td>
                <td>BillNo</td>
                <td>Name</td>
                <td>Total_Amt</td>
                <td>Amt_Paid</td>
                <td>PaidDate</td>
                <td>PaymentMode</td>
            </tr>
        <tbody>
            <?php
				$cnt=1;
				if(!empty($returnData))
				{
                    $returnDatadata=$returnData['data'];
				foreach($returnDatadata as $row)
					{ ?>
                        <tr id='<?php echo $row->Pur_Id;?>'>
                        <td><?php echo $cnt;?></td>
                        <td><?php echo $row->BillNo;?></td>
                        <td><?php echo $row->Name;?></td>
                        <td><?php echo $row->Total_Amt;?></td>
                        <td><?php echo $row->Amt_Paid;?></td>
                        <td><?php echo htmlentities(date('d-m-Y',strtotime($row->PaidDate)));?></td>
                        <td><?php echo $row->PaymentMode;?></td>
                        </tr>
            <?php $cnt++;  } } ?>
        </tbody>
        </thead>
    </table>
</div>

<?php   include('footer.php'); ?>



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
    $('#search').click(function(){
        var startdate = new Date($('#startdate').val());
        var enddate = new Date($('#enddate').val());
        var paidornot = ($('#PaidUnPaid')).val();
        if(startdata!=enddate && paidornot!='')
        {
            document.getElementById("total").style.display = "block";
            document.getElementById("print").style.display = "inline-block";
        }

    })
});

function printContent(el) {
    var tbl = document.getElementById('total');
    if (tbl.rows.length == 0)
    {
            Swal.fire({
                title: 'Warning',
                text: 'Data Table is empty',
                icon: 'error',
                timer: 6000,
                timerProgressBar: true,
            });
    }
    else{
        var restorepage = document.body.innerHTML;
        var printcontent = document.getElementById("totalmy").innerHTML;
        document.body.innerHTML = printcontent;
        window.print();
        document.body.innerHTML = restorepage;
        $('#startdate').val(new Date().toISOString().substr(0, 10));
        $('#enddate').val(new Date().toISOString().substr(0, 10));
    }
}
</script>