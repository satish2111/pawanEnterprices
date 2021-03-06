<?php
$title='Purchase Wise Report';
include('header.php'); ?>
<style>
.red {
    color: red;
}

.col-md-4 {
    margin-bottom: 0px;
}

/* The Modal (background) */
.modal {
    display: none;
    /* Hidden by default */
    position: fixed;
    /* Stay in place */
    z-index: 1;
    /* Sit on top */
    padding-top: 100px;
    /* Location of the box */
    left: 0;
    top: 0;
    width: 100%;
    /* Full width */
    height: 100%;
    /* Full height */
    overflow: auto;
    /* Enable scroll if needed */
    background-color: rgb(0, 0, 0);
    /* Fallback color */
    background-color: rgba(0, 0, 0, 0.4);
    /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

/* The Close Button */
.close {
    color: #f50626;
    float: right;
    right: 2px;
    font-size: 2em;
    font-weight: bold;
    text-align: right;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

table {
    width: 100%;
}

.colorred {
    color: red;
    font-weight: 500;
}

#TotalQty,
#TotalFree,
#TotalGross {
    color: #007f32;
    font-weight: 900;
    letter-spacing: 1px;
}
</style>
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
                        <input type="hidden" name="supplerid" id='supplerid' value="">
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
                <button class="btn btn-success" id='search' title='Get Data'>Search</button>
                <a href="<?php echo base_url().'Purchase/purchaseFromToReport' ?>" class="btn btn-danger"
                    title='Final Cancel'>Cancel</a>
                <button class="btn btn-primary" onclick='printContent(mytable)' style='display: none;'
                    id='print'>Print</button>
            </div>
        </div>
    </form>

    <div class='row'>
        <div class="col-md-12 mt-5">
            <?php if(!empty($returnData['startdate'])){ $totalpurchase=0;$totalpurchasePaid=0; 
                        foreach($returnData['result'] as $row)
							{
                                $totalpurchase+=$row->Total_Amt;
                                $totalpurchasePaid+=$row->Amt_Paid;}?>
            <?php if(!empty($returnData['supplername'])){?>
            <h3 id='supplername' class='text-center'>
                <span class='red'>Suppler Name :-</span>
                <?php echo $returnData['supplername'];?>
            </h3>
            <?php } ?>
            <h4 class='text-center'><span class='red'>From :- </span>
                <span id='fromid'>
                    <?php echo date('d-m-Y',strtotime($returnData['startdate']));?>
                </span> &
                <span class='red'> To :-</span>
                <span id='Toid'>
                    <?php echo date('d-m-Y',strtotime($returnData['enddate']));?>
                </span>
            </h4>
            <h4 class='text-center'>
                <span class='red'> Total Purchase :- </span>
                <span>
                    <?php echo $totalpurchase; ?></span>
                <span class='red'> Total Paid :-</span>
                <span><?php echo $totalpurchasePaid; ?></span>
            </h4>
            <?php } ?>
        </div>
    </div>

    <?php if(!empty($returnData['result'])) {?>
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
                <th>Bill Detail</th>
            </thead>
            <tbody>
                <?php foreach($returnData['result'] as $row) {?>
                <tr>
                    <td><?php echo htmlentities($row->BillNo);?></td>
                    <td><?php echo htmlentities(date('d-m-Y',strtotime($row->BillDate)));?></td>
                    <td><?php echo htmlentities($row->Name);?></td>
                    <td><?php echo htmlentities($row->Total_Amt);?></td>
                    <td><?php echo htmlentities(date('d-m-Y',strtotime($row->PaidDate)));?></td>
                    <td><?php echo htmlentities($row->Amt_Paid);?></td>
                    <td><?php echo htmlentities($row->PaymentMode);?></td>
                    <td><button class='btn btn-success'
                            onclick="billdetail('<?php echo $row->BillNo ?>','<?php echo $row->Name; ?>','<?php echo $row->suppler_id?>','<?php echo (date('d-m-Y',strtotime($row->BillDate))) ?>')"
                            id='myBtn'> Bill Detail</button>
                    </td>
                </tr> <?php } ?>
            </tbody>
        </table>
    </div>
    <?php } ?>
    <!-- The Modal -->
    <div id="myModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <span class="close" id='close'> &times;</span>
            <div class='row'>
                <div class="col-md-6">
                    <h5>Client Name:- <span id='viewname'></span></h5>
                </div>
                <div class="col-md-3">
                    <h5>Bill No:- <span id='viewbill'></span></h5>
                </div>
                <div class="col-md-3">
                    <h5>Bill Date:- <span id='viewbilldate'></span></h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table id="table1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Srno</th>
                                <th>Product Name</th>
                                <th>Qty</th>
                                <th>Cost</th>
                                <th>Gross</th>
                                <th>MRP</th>
                            </tr>
                        </thead>
                        <tbody id='datarow'>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 colorred">
                    <h5>Total Qty :- <span id='TotalQty'></span></h5>
                </div>
                <div class="col-md-4 colorred">
                    <h5>Total Cost :-
                        <span id='TotalFree'></span>
                    </h5>
                </div>
                <div class="col-md-4 colorred">
                    <h5> Total MRP :-
                        <span id='TotalGross'></span>
                    </h5>

                </div>
            </div>
        </div>

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
    $("#supplername").blur(function() {
        document.getElementById("pageloader").style.display = "block";
        $("#supplername option").each(function(i, el) {
            data[$(el).data("value")] = $(el).val();
        });
        var supplername = $('#supplername').val();
        var supplernameID = $('#supplername1 [value="' + supplername + '"]').data('value');
        document.getElementById('supplerid').value = supplernameID;
        $(".pageloader").fadeOut("slow");
    });
});

function check() {
    var startdate = new Date($('#startdate').val());
    var enddate = new Date($('#enddate').val());
    var labelvalue = document.getElementById('supplername').val();

    if (startdate.getTime() === enddate.getTime() && labelvalue != '') {
        Swal.fire({
            title: 'Warning',
            text: 'Start Date and End Date is Same,Please Changes That',
            icon: 'error',
            timer: 20000,
            timerProgressBar: true,
        });
        return false;
    }
};

function billdetail($BillNo, $client, $billdate, $clientname) {
    modal.style.display = "block";

    document.getElementById("viewname").innerHTML = $client;
    document.getElementById("viewbill").innerHTML = $BillNo;
    document.getElementById("viewbilldate").innerHTML = $clientname;


    var Perurl = "/pawanenterprises/Purchase/billdetail";
    var data = {
        'BillNo': $BillNo
    };
    $.ajax({
        data: data,
        type: "POST",
        url: Perurl,
        crossOrigin: false,
        dataType: 'json',
        success: function(result) {

            // console.log(result['status']);
            var tabledata = result['status'];
            var table = document.getElementById('table1');
            var tempqty = 0;
            var tempfree = 0;
            var tempgross = 0.00;
            var srno = 1;
            for (var i = 0; i < tabledata.length; i++) {
                tempqty += parseInt(tabledata[i]['Qty'], 10);
                tempfree += parseInt(tabledata[i]['Cost'], 10);
                tempgross += parseFloat(tabledata[i]['MRP']);
                $("#table1 tbody").append(`<tr>
                                        <td>` + srno + `</td>
                                        <td>` + tabledata[i]['ProductName'] + `</td>
                                        <td>` + tabledata[i]['Qty'] + `</td>
                                        <td>` + tabledata[i]['Cost'] + `</td>
                                        <td>` + tabledata[i]['Qty'] * tabledata[i]['Cost'] + `</td>
                                        <td>` + tabledata[i]['MRP'] + `</td>
                                        </tr>`);
                srno++;
            }
            document.getElementById("TotalQty").innerHTML = tempqty;
            document.getElementById("TotalFree").innerHTML = tempfree;
            document.getElementById("TotalGross").innerHTML = (tempgross).toFixed(2);


        }
    });

}
// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

var close = document.getElementById("close");
// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on <span> (x), close the modal
close.onclick = function() {
    $("#datarow").find("tr").remove();
    modal.style.display = "none";
}
</script>