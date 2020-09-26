<?php
$title='Purchase Wise Report';
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
        </div>
    </div>
    <form action="<?php echo base_url(); ?>purchase/reportpurchasefromto" method="post" onsubmit="return check()">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group row align-items-center">
                    <div class="col-md-3">
                        <label for="Suppler"><b>Suppler</b></label>
                    </div>
                    <div class="col-md-9">
                        <input id="supplername" list="supplername1" placeholder="Select Suppler" type="text"
                            name="supplername" class="form-control" style="text-transform: uppercase;">
                        <datalist id='supplername1' required name='supplername1'>
                            <?php
					$columnnames='suppler_id,FirstName';
					$tablename='tblsuppler';
					
					$this->load->model('PurchaseModel', 'Purchase');
					$supplerdata=$this->Purchase->Supplerlist($columnnames,$tablename);
					foreach ($supplerdata as $row) { ?>
                            <option data-value='<?php echo $row->suppler_id?>' value="<?php echo $row->FirstName; ?>"
                                <?php echo set_select('supplername',$row->FirstName); ?>>
                            </option>
                            <?php
					}
					?>
                        </datalist>
                        <?php echo form_error('supplername'); ?>
                    </div>
                </div>
            </div>
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
            <div class="col-md-12 text-center">
                <button class="btn btn-success" id='search' title='Get Data' >Search</button>
                <a href="<?php echo base_url().'Purchase/purchaseFromToReport' ?>" class="btn btn-danger" title='Final Cancel'>Cancel</a>
                <button class="btn btn-primary" onclick='printContent(mytable)' style='display: none;'
                    id='print'>Print</button>
            </div>
            </from>
        </div>
<div class='row'>
<div class="col-md-12 mt-5">
<?php 
    if(!empty($returnData['startdate'])){ $totalpurchase='';$totalpurchasePaid='';?> 
    <?php if(!empty($returnData['supplername'])){?>
<h3 id='supplername' class='text-center'>Suppler Name<?php echo $returnData['supplername'];?></h3>
<?php } ?>
<h4 class='text-center'>From : <span id='fromid'><?php echo $returnData['startdate'];?></span> To : <span id='Toid'><?php echo $returnData['enddate'];?></span></h4>
<h4 class='text-center'> Total Purchase : <span><?php echo $totalpurchase; ?></span> Total Paid : <span><?php echo $totalpurchasePaid; ?></span> </h4>
<?php } ?>
</div>
</div>
        <div class="table-responsive mt-3">
            <table id="mytable" class="table table-bordred table-striped">
                <thead>
                    <th>Bill No</th>
                    <th>Bill Date</th>
                    <th>Suppler Name</th>
                    <th>Total Amt</th>
                    <th>Paid Date</th>
                    <th>Paid Amt</th>
                    <th>Payment Mode</th>
                </thead>
                <tbody>
                    <?php
						if(!empty($returnData['result']))
						{
                            // echo "<pre/>";
                            // print_r($returnData['result']);
                            // exit();
							foreach($returnData['result'] as $row)
							{
                                $totalpurchase=+$row->Total_Amt;
                                $totalpurchasePaid=+$row->Amt_Paid;
						?>
                    <tr>
                        <td><?php echo htmlentities($row->BillNo);?></td>
                        <td><?php echo htmlentities(date('d-m-Y',strtotime($row->BillDate)));?></td>
                        <td><?php echo htmlentities($row->Name);?></td>
                        <td><?php echo htmlentities($row->Total_Amt);?></td>
                        <td><?php echo htmlentities(date('d-m-Y',strtotime($row->PaidDate)));?></td>
                        <td><?php echo htmlentities($row->Amt_Paid);?></td>
                        <td><?php echo htmlentities($row->PaymentMode);?></td>
                    </tr>
                    <?php						
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
        </div>
</div>

<div class="pageloader" style='display: none;' id='pageloader'></div>
</div>
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
});
function check(){
    var startdate = new Date($('#startdate').val());
    var enddate = new Date($('#enddate').val());

    if (startdate.getTime() === enddate.getTime()) {
        Swal.fire({
            title: 'Warning',
            text: 'Start Date and End Date is Same,Please Changes That',
            icon: 'error',
            timer: 20000,
            timerProgressBar: true,
        });
        return false;
    } else {
        $("#supplername option").each(function(i, el) {
            data[$(el).data("value")] = $(el).val();
        });
        var supplername = $('#supplername').val();
        var supplernameID = $('#supplername1 [value="' + supplername + '"]').data('value');
        var data = {
            'startdate': document.getElementById('startdate').value,
            'enddate': document.getElementById('enddate').value,
            'supplername': supplernameID
        };
        // document.getElementById("pageloader").style.display = "block";
        // $.ajax({
        //     data: data,
        //     type: "POST",
        //     url: "/pawanenterprises/purchase/reportpurchasefromto",
        //     crossOrigin: false,
        //     dataType: 'json',
        //     success: function(result) {
        //         $(".pageloader").fadeOut("slow");
        //         if (result['status'] == "success") {
        //             document.getElementById("print").style.display = "inline-block";

        //         }

        //     }
        // })
    }
};

</script>