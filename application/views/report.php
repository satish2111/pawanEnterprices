<?php
$title='Party Wise Report';
include('header.php');?>
<style>
.col-md-4{
    margin:0;
}
.col-md-4>button,
.col-md-4>a {
    margin-right: 2rem;
}
.btn-warning{
    margin-top:0.5rem;
    margin-left:4rem;
    font-weight:900;
}
.btn-warning:hover{
    color:#ffc107;
    background-color:#000;
    font-weight:100;
    border:1px solid #ffc107;
}
@media print{

#mytable,.table td, .table th {
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
    <div class="row">
    
        <div class="col-md-4">
            <div class="form-group row align-items-center">
                <div class="col-md-4">
                    <label for="Billno"><b>Start Date<sup class="star">*</sup></b></label>
                </div>
                <div class="col-md-8">
                    <input class="form-control" id="startdate" data-format="dd/MM/yyyy" name="stardate" type="date"
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
            <button class="btn btn-success" id='search' title='Get Data'>Search</button>
            <a href="<?php echo base_url().'sale/report' ?>" class="btn btn-danger" title='Final Cancel'>Cancel</a>
            <button class="btn btn-primary" onclick='printContent(mytable)'>Print</button>
            <button class="btn btn-warning" id='amt'>Purachas | Sale Amt</button>
        </div>
    </div>
    <div id='total' style='display:none'>
    <h4>Total Purchase :- <span id='purchase' style='color:red'></span></h4>
    <h4>Total Sale :- <span id='sales' style='color:green'></span></h4>
    </div>
    <div id='mytable' style='display:none'>
        <h2 class='text-center'>Party Wish Report</h2>
        <h4 class='text-center'>From:-<span id='labelstartdate'></span> To :-<span id='labelenddate'></span> </h4>
        <table id="table1" class="table table-bordred table-striped">
            <!-- <thead>
                
            </thead> -->
            <tbody id='billDetails'>


            </tbody>

        </table>
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
    $('#amt').click(function(){
        var startdate = new Date($('#startdate').val());
        var enddate = new Date($('#enddate').val());

        if (startdate.getTime() === enddate.getTime()) {
            Swal.fire({
                title: 'Warning',
                text: 'Start Date and End Date is Same,Please Changes That',
                icon: 'error',
                timer: 2000,
                timerProgressBar: true,
            });
        }
        else{
            var data = {
                'startdate': document.getElementById('startdate').value,
                'enddate': document.getElementById('enddate').value
            };
            document.getElementById("pageloader").style.display = "block";
            $.ajax({
                data: data,
                type: "POST",
                url: "/pawanenterprises/index.php/sale/reportsalepurchase",
                crossOrigin: false,
                dataType: 'json',
                success: function(result) {
                    $(".pageloader").fadeOut("slow");
                    if (result['status'] == "success")
                    {
                        document.getElementById("total").style.display = "block";
                        document.getElementById('purchase').innerHTML=result['PurchaseReport']['PurchaseAmount'];
                        document.getElementById('sales').innerHTML=result['saleReport']['SaleAmount'];   
                    }
                    else {
                        Swal.fire('Warning', 'Something Wrong', 'warning');
                    }
                }
            });
        }
    });

    $('#search').click(function() {
        var startdate = new Date($('#startdate').val());
        var enddate = new Date($('#enddate').val());
        document.getElementById("total").style.display = "none";
        if (startdate.getTime() === enddate.getTime()) {
            Swal.fire({
                title: 'Warning',
                text: 'Start Date and End Date is Same,Please Changes That',
                icon: 'error',
                timer: 2000,
                timerProgressBar: true,
            });
        } else {
            var data = {
                'startdate': document.getElementById('startdate').value,
                'enddate': document.getElementById('enddate').value
            };
            document.getElementById("pageloader").style.display = "block";
            $.ajax({
                data: data,
                type: "POST",
                url: "/pawanenterprises/index.php/sale/reportdata",
                crossOrigin: false,
                dataType: 'json',
                success: function(result) {
                    $(".pageloader").fadeOut("slow");
                    if (result['status'] == "success") {
                        document.getElementById('labelstartdate').innerHTML = getdateformat(
                            startdate);
                        document.getElementById('labelenddate').innerHTML = getdateformat(
                            enddate);
                       var data = result['clientreport'].reduce((h, {
                            Sale_id,
                            Billdate,
                            DueDate,
                            TotalAmt,
                            PaidAmt,
                            OutstandingAmt,
                            Name,
                            Address,
                            client_id
                        }) => {
                            return Object.assign(h, {
                                [client_id]: (h[client_id] || []).concat({
                                    Sale_id,
                                    Billdate,
                                    DueDate,
                                    TotalAmt,
                                    PaidAmt,
                                    OutstandingAmt,
                                    Name,
                                    Address

                                })
                            })
                        }, {})
                        for (var [clientId, bill] of Object.entries(data)) {
                            $("#table1 tbody").append(`<tr><td colspan= 6><b>Party Name ${bill[0].Name}</td></tr>
                                                    <tr><td>Bill No</td>
                                                    <td>Bill Date</td>
                                                    <td>Total Amt</td>
                                                    <td>Due Date</td>
                                                    <td>Paid Amt</td>
                                                    <td>outstanding</td></tr>`);
                            var billTotalAmt = 0;
                            var billTotalPaidAmt = 0;
                            var billTotalOutAmt = 0;
                            for (var singleBill of bill) {
                                $("#table1 tbody").append(
                                    `<tr> <td> ${singleBill.Sale_id} </td>,
                                     <td> ${singleBill.Billdate} </td> ,
                                     <td> ${singleBill.TotalAmt} </td> ,
                                     <td> ${singleBill.DueDate} </td> ,
                                     <td> ${singleBill.PaidAmt} </td>,
                                     <td> ${singleBill.OutstandingAmt} </td> </tr>
                                `);
                                billTotalAmt += parseFloat(singleBill.TotalAmt);
                                billTotalPaidAmt += parseFloat(singleBill.PaidAmt);
                                billTotalOutAmt += parseFloat(singleBill.OutstandingAmt);
                            }
                            $("#table1 tbody").append(
                                `<tr> <td colspan = 2 > <b style='float: right;'>Total</b> </td>,
                                 <td> <b>${billTotalAmt.toFixed(2)}</b> </td> ,
                                 <td> <b></b> </td> ,
                                 <td> <b> ${billTotalPaidAmt.toFixed(2)}</b> </td>,
                                 <td> <b>${billTotalOutAmt.toFixed(2)}</b> </td> </tr>
                            `);
                        }
                        document.getElementById("mytable").style.display = "block";
                    } 
                    
                    else {
                        Swal.fire('Warning', 'Something Wrong', 'warning');
                    }
                }
            });

            //{Swal.fire('success', 'Saving', 'success');}
        }
    });

    function getdateformat(dateThatFromat) {
        var today = new Date(dateThatFromat);
        var dd = today.getDate();
        var mm = today.getMonth() + 1; //January is 0!
        var yyyy = today.getFullYear();
        if (dd < 10) {
            dd = '0' + dd
        }
        if (mm < 10) {
            mm = '0' + mm
        }
        return dd + '-' + mm + '-' + yyyy;
    }
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