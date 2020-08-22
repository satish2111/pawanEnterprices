<?php
$title='Sale-Add';
include('header.php'); ?>
<style type="text/css">
.col-md-4 {
    margin-bottom: 0;
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
                <div class="col-md-3">
                    <input type="hidden" id="session" value="<?php echo $this->session->id;?>">
                    <label for="Billno"><b>BillDate</b><sup class="star">*</sup></label>
                </div>
                <div class="col-md-9">
                    <input class="form-control" id="date" data-format="dd/MM/yyyy" name="date" type="date"
                        autocomplete="off" required tabindex="0" />
                </div>
            </div>
            <?php echo form_error('billdate'); ?>
        </div>
        <div class="col-md-4">
            <div class="form-group row align-items-center">
                <div class="col-md-3">
                    <input type="hidden" id="session" value="<?php echo $this->session->id;?>">
                    <label for="Billno"><b>DueDate</b><sup class="star">*</sup></label>
                </div>
                <div class="col-md-9">
                    <input class="form-control" id="Duedate" data-format="dd/MM/yyyy" name="date" type="date"
                        autocomplete="off" required tabindex="-1" readonly />
                </div>
            </div>
            <?php echo form_error('billdate'); ?>
        </div>
        <div class="col-md-4">
            <div class="form-group row align-items-center">
                <div class="col-md-3">
                    <label for="laClient"><b>Client</b><sup class="star">*</sup></label>
                </div>
                <div class="col-md-9">
					<input id="client" list="client1" placeholder="Select Client Name" 
					type="text" name="Client" class="form-control" 
					style="text-transform: uppercase;">
                    <datalist id='client1' required name='client1'>
                        <?php
							$this->load->model('SaleModel', 'sale');
							$clientnamelist=$this->sale->clientlist();
							foreach ($clientnamelist as $row)
					{ ?>
                        <option data-value='<?php echo $row->client_id?>'
							data-createdate='<?php echo $row->creditdays?>'  
							value=<?php echo $row->FirstName; ?>
                            <?php echo set_select('Client',$row->FirstName); ?>>
                        </option>
                        <?php
					}
					?>
                    </datalist>
                    <?php echo form_error('Client'); ?>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group row align-items-center">
                <div class="col-md-3" style='max-width:17%;'>
                    <label for="Product"><b>Product</b><sup class="star">*</sup></label>
                </div>
                <div class="col-md-6" style='max-width:70%;'>
                    <input type="text" name="txtProduct" list='productname1' value="" id='productname'
                        placeholder="Product Name" class="form-control" required>
                    <datalist id='productname1' required name='productname1'>
                        <?php
							$this->load->model('SaleModel', 'sale');
							$productnamelist=$this->sale->productlist();
							$i=0;
							foreach ($productnamelist as $row)
							{ $i++;?>
								<option data-value='<?php echo $row->Qty ?>' 
								data-mrp='<?php echo $row->MRP?>'
								value="<?php echo $row->ProductName ?>"></option>
								<?php
							}
					?>
                    </datalist>
                </div>
                <div class="col-md-3" style='padding:0'>
                    <label id='totalstockqty' style='color:green;font-size: 1.5rem;font-weight: 900'></label>
                </div>
                <?php echo form_error('product'); ?>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group row align-items-center">
                <div class="col-md-3">
                    <label for="Qty"><b>Qty</b> <sup class="star">*</sup></label>
                </div>
                <div class="col-md-9">
                    <input type="text" name="txtquantity" value="" id='quantity' placeholder="Qty" oninput="calculate()"
                        class="form-control" oninput="calculate()" onkeypress="return isNumberKey(event)" maxlength="5"
                        required>
                </div>
            </div>
            <?php echo form_error('quantity'); ?>
        </div>
        <div class="col-md-4">
            <div class="form-group row align-items-center">
                <div class="col-md-3">
                    <label for="mrp"><b>DP</b><small style='font-size: 0.8rem;'>(MRP)</small> <sup class="star"
                            style='float: right;margin-top: -0.7rem;'>*</sup></label>
                </div>
                <div class="col-md-9">
                    <input type="text" name="txtmra" value="" id='mrp' placeholder="MRP" class="form-control"
                        onkeypress="return isNumberKey(event)" oninput="calculate()" maxlength="5" required>
                </div>
            </div>
            <?php echo form_error('mrp'); ?>
        </div>
        <div class="col-md-4">
            <div class="form-group row align-items-center">
                <div class="col-md-2">
                    <label for="free"><b>Free</b></label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="txtfree" value="" id='free' placeholder="Free" class="form-control"
                        onkeypress="return isNumberKey(event)" maxlength="5">
                </div>
            </div>
            <?php echo form_error('mrp'); ?>
        </div>
        <div class="col-md-3">
            <div class="form-group row align-items-center">
                <div class="col-md-3">
                    <label for="free"><b>Gross</b></label>
                </div>
                <div class="col-md-9">
                    <input type="text" name="txtgross" value="" id='gross' placeholder="Gross" class="form-control"
                        onkeypress="return isNumberKey(event)" tabindex="-1" maxlength="5" readonly>
                </div>
            </div>
            <?php echo form_error('mrp'); ?>
        </div>
        <div class="col-md-5">

            <button class="btn btn-primary" id='btnsave' title="Save In Table">Save</button>
            <a href="<?php echo base_url().'index.php/sale' ?>" class="btn btn-danger" id='canel'
                title="Cancel the Data">Cancel</a>
            <button class="btn btn-warning" name='reset' id='reset' title="Reset the BoxText">Reset</button>
        </div>
        <div class="col-md-12">
            <hr />
        </div>
    </div>
    <div class="row" id="totalpanel"
        style='display: none;background-color:#cccccc47; padding:0.5rem 0; border-radius: 15px;align-items:center;'>
        <div class="col-md-3 align-items-center">
            <div class="form-group row ">
                <div class="col-md-4">
                    <label for="nameField" class="col-sm-9">Total_Item:-</label>
                </div>
                <div class="col-md-8">
                    <label for="nameField" name="totalitem" id="totalitem" class="col-sm-3"
                        style="color:red; font-weight:500; font-size:1rem;">0</label>
                </div>
            </div>
        </div>
        <div class="col-md-3 align-items-center">
            <div class="form-group row ">
                <div class="col-md-4">
                    <label for="nameField" class="col-sm-9">Total_QTY</label>
                </div>
                <div class="col-md-8">
                    <label for="nameField" name="totalqty" id="totalqty" class="col-sm-3"
                        style="color:red; font-weight:500; font-size:1rem;">0</label>
                </div>
            </div>
        </div>
        <div class="col-md-3 align-items-center">
            <div class="form-group row ">
                <div class="col-md-4">
                    <label for="nameField" class="col-sm-2">Total:- </label>
                </div>
                <div class="col-md-8">
                    <input type="text" name='totalgross' readonly id='totalgross' class="form-control" value=""
                        style="color:red; font-weight:500; font-size:1rem;" />
                </div>
            </div>
        </div>
        <div class="col-md-3 align-items-center">
            <button class="btn btn-primary" id='done' title='Final Save'>Done</button>
            <a href="<?php echo base_url().'index.php/purchase' ?>" class="btn btn-danger"
                title='Final Cancel'>Cancel</a>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-lg-12 table-responsive">
            <table id="table1" name="table1" class="table table-bordered table-striped" style="display: none;">
                <thead>
                    <tr>
                        <th>SRNO</th>
                        <th>ProductName</th>
                        <th>Qty</th>
                        <th>Free</th>
                        <th>DP (<small>MRP</small>)</th>
                        <th>Gross</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <div class="pageloader" style='display: none;' id='pageloader'></div>
</div>
<?php include('footer.php'); ?>
<script type="text/javascript">
$(document).ready(function() {
    $('#date').val(new Date().toISOString().substr(0, 10));
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
    document.getElementById("date").setAttribute("max", today);
    var id = 1;
    document.getElementById("Duedate").value = today;


    function dateclick() {
        document.getElementById("date").innerHTML = new Date();
    }
    var UserId = document.getElementById("session").value;
    //client vaild from backend start//
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
                        $("#client option").each(function(i, el) {
                            data[$(el).data("value")] = $(el).val();
                        });
                        var client = $('#client').val();
                        var NoofDay = $('#client1 [value="' + client + '"]').data(
                            'createdate');
                        var today = new Date();
                        var dd = today.getDate() + NoofDay;
                        var mm = today.getMonth() + 1; //January is 0!
                        var yyyy = today.getFullYear();
                        if (dd < 10) {
                            dd = '0' + dd
                        }
                        if (mm < 10) {
                            mm = '0' + mm
                        }
                        today = yyyy + '-' + mm + '-' + dd;
                        document.getElementById("Duedate").value = today;

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



    //qty check
    $('#quantity').blur(function() {
        var quantity = document.getElementById('quantity').value;
        if (quantity != '') {
            var qtyavailable = document.getElementById('totalstockqty').innerHTML;
            var test = qtyavailable.split("-");
            var onlyavailablequantity = test[1];
            if (Number(quantity) > Number(onlyavailablequantity)) {
                Swal.fire({
                    title: 'Error',
                    text: 'Quantity should Not be greater than available quantity',
                    icon: 'error',
                    showCancelButton: false,
                    timer: 6000,
                    timerProgressBar: true,
                });
                document.getElementById('quantity').value = '';
                $("#quantity").focus();
            }

        }
    })

    // free check Available not
    $('#free').blur(function() {
        var free = document.getElementById('free').value;
        if (free != '') {
            var qtyavailable = document.getElementById('totalstockqty').innerHTML;
            var quantity = document.getElementById('quantity').value;
            var test = qtyavailable.split("-");

            var onlyavailablequantity = test[1];
            var finalqty = Number(quantity) + Number(free);

            if (Number(onlyavailablequantity) < Number(finalqty)) {

                Swal.fire({
                    title: 'Error',
                    text: 'Quantity + Free should Not be greater than available quantity',
                    icon: 'error',
                    showCancelButton: false,
                    timer: 10000,
                    timerProgressBar: true,
                });
                document.getElementById('free').value = '';
                $("#free").focus();
            }
        }
    });
    // free check Available end


    // check product
    $("#productname").blur(function() {

        var textboxvalue = document.getElementById("productname").value;

        if (textboxvalue != '') {
            document.getElementById("pageloader").style.display = "block";
            var data = {
                'textboxvalue': textboxvalue,
            };
            $.ajax({
                data: data,
                type: "POST",
                url: "/pawanenterprises/sale/checkproductname",
                crossOrigin: false,
                dataType: 'json',
                success: function(result) {
                    $(".pageloader").fadeOut("slow");
                    if (result.status == "success") {
                        //console.log(result.status);
                        $("#productname option").each(function(i, el) {
                            data[$(el).data("value")] = $(el).val();
                        });
                        var productname = $('#productname').val();
                        var productqty = $('#productname1 [value="' + productname + '"]')
							.data('value');
							var mrp =$('#productname1 [value="' + productname + '"]')
							.data('mrp');
							document.getElementById("mrp").value=mrp;	
                        document.getElementById("totalstockqty").innerHTML = "Qty-" +
							productqty;
                        if (document.getElementById("totalstockqty").innerHTML ==
                            "Qty-undefined") {
                            Swal.fire({
                                title: 'Error',
                                text: 'Quantity Not Available',
                                icon: 'error',
                                showCancelButton: false,
                                timer: 2000,
                                timerProgressBar: true,
                            });
                            document.getElementById("productname").value = '';
                            $("#productname").focus();
                        }
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Product Name Not Found',
                            icon: 'error',
                            showCancelButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                        });
                        document.getElementById("productname").value = '';
                        $("#productname").focus();
                    }
                }
            });
        }
    });


    // save in tabel
    $("#btnsave").click(function() {
        var errormessgae = '';
        if ($('#date').val() == 0) {
            errormessgae += 'Product Name, '
        }
        if ($('#client').val() == 0) {
            errormessgae += 'Client, '
        }
        if ($('#productname').val() == 0) {
            errormessgae += 'productname, '
        }
        if ($('#quantity').val() == 0) {
            errormessgae += 'Qty, '
        }
        if ($('#mrp').val() == 0) {
            errormessgae += 'MRP'
        }

        if (errormessgae != '') {
            var finalerror = errormessgae;
            errormessgae = '';
            errormessgae += 'Please fill the follow field <br/> ' + finalerror;
            //console.log(errormessgae);
            Swal.fire('Error list', errormessgae, "error");
        }
        if (errormessgae == '') {
            var table = document.getElementById('table1');
            var rowcount = table.rows.length;
            if (rowcount == 1 && id != 1) {
                id = id - 1;
            }
            if (document.getElementById("free").value == "") {
                document.getElementById("free").value = '0';
            }
            var newid = rowcount;
            //console.log(newid);
            $("#table1 tbody").append('<tr id="' + newid + '">\n\<td>' + newid +
                '</td>\n\<td class="productname' + newid + '">' + $("#productname").val() +
                '</td>\n\<td class="quantity' + newid + '">' + $("#quantity").val() +
                '</td>\n\<td class="free' + newid + '">' + $("#free").val() +
                '</td>\n\<td class="mrp(dp)' + newid + '">' + $("#mrp").val() +
                '</td>\n\<td class="Gross' + newid + '">' + $("#gross").val() +
                '</td>\n\<td ><a href="javascript:void(0);" class="remCF">Remove</a></td>\'</tr>');

            var tempqty = document.getElementById("quantity").value;
            var tempgross = document.getElementById("gross").value;

            var totalqty1 = document.getElementById("totalqty").innerHTML;
            var totalgross1 = document.getElementById("totalgross").value;

            if (totalgross1 == '') {
                totalgross1 = 0;
            }
            document.getElementById("totalitem").innerHTML = newid;
            document.getElementById("totalqty").innerHTML = parseInt(totalqty1, 10) + parseInt(tempqty,
                10);
            document.getElementById("totalgross").value = (parseFloat(tempgross) + parseFloat(
                totalgross1)).toFixed(2)

            document.getElementById("productname").value = "";
            document.getElementById("quantity").value = "";
            document.getElementById("mrp").value = "";
            document.getElementById("gross").value = "";
            document.getElementById("totalstockqty").innerHTML = "";
            document.getElementById("free").value = "";


            $("#productname").focus();
            document.getElementById('table1').style.display = "inline-table"
            document.getElementById('totalpanel').style.display = "flex"

        }
    });

    // reset star

    $("#reset").click(function() {
        document.getElementById("productname").value = "";
        document.getElementById("quantity").value = "";
        document.getElementById("free").value = "";
        document.getElementById("mrp").value = "";
        document.getElementById("gross").value = "";
        document.getElementById("totalstockqty").innerHTML = "";
        $("#productname").focus();
    });
    // reset end


    // final save with database
    $('#done').click(function() {
        var table = document.getElementById('table1');
        var rowcount = table.rows.length;
        var item = document.getElementById("totalitem").innerHTML;
        if (rowcount == 1 && item == 0) {
            swal('Warning', 'Bill Can not save Empty', "error");
            return;
        } else {
            $("#client option").each(function(i, el) {
                data[$(el).data("value")] = $(el).val();
            });
            var client = $('#client').val();
            var clientID = $('#client1 [value="' + client + '"]').data('value');
            //console.log(clientID);
            var saletable = [];
            var totalbill = [];

            var billwisetotal = {
                'BillDate': document.getElementById('date').value,
                'client_id': clientID,
                'TotalAmt': document.getElementById('totalgross').value,
                'createdate': document.getElementById('Duedate').value,
                'AddBy': UserId,
                //'BillNo': document.getElementById('billno').value,

                // 'Pur_Id': document.getElementById('PurId') === null ? 0 : document.getElementById('PurId').value,
            }
            totalbill.push(billwisetotal);
            $('#table1 tr').each(function(row, tr) {
                if ($(tr).find('td:eq(0)').text() == "") {

                } else {
                    var sub = {
                        'ProductName': $(tr).find('td:eq(1)').text(),
                        'Qty': $(tr).find('td:eq(2)').text(),
                        'Free': $(tr).find('td:eq(3)').text(),
                        'mrp': $(tr).find('td:eq(4)').text(),
                        'productwisegross': $(tr).find('td:eq(5)').text(),
                    }
                    saletable.push(sub);
                }
                Swal.fire({
                        title: 'Are You sure',
                        text: 'To Save The Bill ?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Save it!'
                    })
                    .then((result) => {
                        if (result.value) {
                            var data = {
                                'saletable': saletable,
                                'totalbill': totalbill
                            };
                            //console.log(data);
                            document.getElementById("pageloader").style.display = "block";
                            $.ajax({
                                data: data,
                                type: "POST",
                                url: "/pawanenterprises/index.php/sale/addsale",
                                crossOrigin: false,
                                dataType: 'json',
                                success: function(result) {
                                    $(".pageloader").fadeOut("slow");
                                    if (result.status == "success") {
                                        Swal.fire({
                                                title: 'Successfully Saved',
                                                text: 'Redirecting...',
                                                icon: 'success',
                                                timer: 2000,
                                                timerProgressBar: true,
                                                buttons: false,
                                            })
                                            .then(() => {
                                                location.href =
                                                    "/pawanenterprises/index.php/sale/index";
                                            })
                                    } else {
                                        Swal.fire('Warning', 'Error Saving',
                                            'warning', 2000);
                                    }
                                }
                            });
                        }
                    })
            })

        }
    });

    // final save with database end




})

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

function calculate() {
    var quantity = document.getElementById('quantity').value;
    var rate = document.getElementById('mrp').value;
    var grossbefore = quantity * rate;


    if (quantity != '' && rate != '') {

        document.getElementById('gross').value = (parseFloat(grossbefore)).toFixed(2);
    }
}
</script>