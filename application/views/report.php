<?php
$title='Party Wise Report';
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
            <div class="form-group row align-items-center">
                <div class="col-md-4 ">
                    <label for="Paid"><b>Paid/UnPaid<sup class="star">*</sup></b></label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="txtPaidUnPaid" list='PaidUnPaid1' value="" id='PaidUnPaid'
                        placeholder="Paid / Un-Paid" class="form-control" required>
                    <datalist id='PaidUnPaid1' required name='PaidUnPaid1'>
                        <option value="Paid">Paid</option>
                        <option value="Un-Paid">Un-Paid</option>
                    </datalist>
                </div>
            </div>

            <?php echo form_error('billdate'); ?>
        </div>

        <div class="col-md-12 text-center">
            <button class="btn btn-success" id='search' title='Get Data'>Search</button>
            <a href="<?php echo base_url().'sale/report' ?>" class="btn btn-danger" title='Final Cancel'>Cancel</a>
            <button class="btn btn-warning" id='amt'>Total Purchase | Sale Amount</button>
            <button class="btn btn-primary" onclick='printContent(mytable)' style='display: none;' id='print'>Print</button>
            
        </div>
    </div>
    <div id='total' class='mt-5' style='display:none'>
        <table id="total" class="table table-bordred table-striped">
            <thead>
                <tr>
                    <td colspan='8'>
                        <h4 class='text-center'>Total As No <br/> From:- <span id='labelstartdate'></span> To :- <span id='labelenddate'></span> </h4>
                    </td>
                </tr>
                <tr>
                    <td>#</td>
                    <td style='color:red;font-weight:500;'>Purchase</td>
                    <td style='color:red;font-weight:500;'>Purchase Amount Paid</td>
                    <td style='color:red;font-weight:500;'>Purchase Amount Outstanding</td>
                    <td style='color:#007bff;font-weight:500;'>Current Stock (Suppler)</td>
                    <td style='color:#007bff;font-weight:500;'>Current Stock (DP)</td>
                    <td style='color:green; font-weight:400;'>Sale On MRP</td>
                    <td style='color:green; font-weight:400;'>Sale On Cost</td>
                    <td style='color:green; font-weight:400;'>Payment Get From Party  </td>
                    <td style='color:green; font-weight:400;'>Payment Amount Outstanding From Party </td>
                </tr>
            <tbody id='total'>
                <tr>
                    <td>1</td>
                    <td> <span id='purchase'></span></td>
                    <td> <span id='purchasePaid'></span></td>
                    <td> <span id='purchaseOutstanding'></span></td>
                    <td> <span id='currentStockCost'></span></td>
                    <td> <span id='currentStock'></span></td>
                    <td> <span id='sale'></span></td>
                    <td> <span id='saleCost'></span></td>
                    <td> <span id='saleGet'></span></td>
                    <td> <span id='saleOutstanding'></span></td>
                </tr>
            </tbody> 
            </thead>
        </table>
    </div>
    <div id='mytable' class='mt-3' style='display:none'>
        <h2 class='text-center'>Party Wish Report</h2>
        <h4 class='text-center'>From:-<span id='labelstartdatefor'></span> To :-<span id='labelenddatefor'></span> </h4>
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
    $('#amt').click(function() {
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
        } else {
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
                    if (result['status'] == "success") {
                        document.getElementById("total").style.display = "block";
                        document.getElementById("mytable").style.display = "none";
                    document.getElementById("print").style.display = "none";
                        
                        document.getElementById('labelstartdate').innerHTML = getdateformat(
                            startdate);
                        document.getElementById('labelenddate').innerHTML = getdateformat(
                            enddate);
                        document.getElementById('purchase').innerHTML = result[
                            'PurchaseReport']['PurchaseAmount'];
                            document.getElementById('currentStockCost').innerHTML = result[
                            'PurchaseStockReport']['Cost'];
                        document.getElementById('currentStock').innerHTML = result[
                            'PurchaseStockReport']['MRP'];
                        document.getElementById('purchasePaid').innerHTML = result[
                            'purchasePaid']['PPaid'];
                        document.getElementById('purchaseOutstanding').innerHTML = result[
                            'purchaseOutstanding']['TotalAmt'];


                        document.getElementById('saleCost').innerHTML = result[
                            'SaleWiseTotal']['Cost'];    
                        document.getElementById('saleGet').innerHTML = result['saleGet'][
                            'SPaid'];
                           var totalsale=result['saleReport']['SaleAmount'];
                           var salegetpayment=result['saleGet']['SPaid'];
                            var SOutstanding= parseFloat(totalsale)-parseFloat(salegetpayment);
                        document.getElementById('saleOutstanding').innerHTML = SOutstanding.toFixed(2);
                        document.getElementById('sale').innerHTML = result['saleReport'][
                            'SaleAmount'];
                    } else {
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
        var paidornot = ($('#PaidUnPaid')).val();
        if (startdate.getTime() === enddate.getTime() && paidornot == '') {
            Swal.fire({
                title: 'Warning',
                text: 'Start Date and End Date is Same,Please Changes That And Select Paid OR Un-Paid',
                icon: 'error',
                timer: 6000,
                timerProgressBar: true,
            });
        } else if (paidornot == '') {
            Swal.fire({
                title: 'Warning',
                text: 'Please Select Paid OR Un-Paid',
                icon: 'error',
                timer: 6000,
                timerProgressBar: true,
            });
        } else {
            var data = {
                'startdate': document.getElementById('startdate').value,
                'enddate': document.getElementById('enddate').value,
                'paidornot': paidornot,
            };
            document.getElementById("pageloader").style.display = "block";
            document.getElementById("print").style.display = "inline-block";
            var x = document.getElementById("table1").rows.length;
            if (x != '0') {
                // $("#table1").empty();
                $("#table1 > tbody").html("");
            }
            $.ajax({
                data: data,
                type: "POST",
                url: "/pawanenterprises/index.php/sale/reportdata",
                crossOrigin: false,
                dataType: 'json',
                success: function(result) {
                    $(".pageloader").fadeOut("slow");
                    if (result['status'] == "success") {

                        document.getElementById("total").style.display = "none";
                        document.getElementById('labelstartdatefor').innerHTML = getdateformat(
                            startdate);
                        document.getElementById('labelenddatefor').innerHTML = getdateformat(
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
                            $("#table1 tbody").append(`<tr><td colspan= 6><b>Party Name :- ${bill[0].Name}</td></tr>
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
                    } else {
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