$(document).ready(function() {
    var id = 1;
    // function dateclick() {
    //     document.getElementById("date").innerHTML = new Date();
    // }
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
                    if (result['status'] == "success") {
                        Swal.fire({
                            title: result['clientTotalAmt']['Total'],
                            text: 'Total Amount Outstanding',
                            icon: 'info',
                            showCancelButton: false,
                            timer: 6000,
                            timerProgressBar: true,
                        });
                        $("#client option").each(function(i, el) {
                            data[$(el).data("value")] = $(el).val();
                        });
                        var client = $('#client').val();
                        var NoofDay = $('#client1 [value="' + client + '"]').data(
                            'createdate');
                        var today = new Date($('#date').val());
                        today.setDate(today.getDate() + NoofDay);
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
    //test

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
            if (free == '0.5' || free == '0.50') {
                mrp = document.getElementById('mrp').value;
                halfmrp = mrp / 2;
                totalGross = document.getElementById('gross').value;
                finalTotalProduct = totalGross - halfmrp;
                document.getElementById('gross').value = finalTotalProduct;
            } else if (free == 0 || free > 0) {
                calculate();
            }

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
                        var mrp = $('#productname1 [value="' + productname + '"]')
                            .data('mrp');
                        document.getElementById("mrp").value = mrp;
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
            var url = window.location.pathname;
            var test = url.split("/");
            var currow = $(this).closest('tr');
            if (test[4] != 'getdetails') {
                $("#table1 tbody").append('<tr id="' + newid + '">\n\<td>' + newid +
                    '</td>\n\<td class="productname' + newid + '">' + $("#productname").val() +
                    '</td>\n\<td class="quantity' + newid + '">' + $("#quantity").val() +
                    '</td>\n\<td class="free' + newid + '">' + $("#free").val() +
                    '</td>\n\<td class="mrp(dp)' + newid + '">' + $("#mrp").val() +
                    '</td>\n\<td class="Gross' + newid + '">' + $("#gross").val() +
                    '</td>\n\<td ><a href="javascript:void(0);" class="remCF"><span class="fas fa-trash"> Remove</a></td>\'</tr>');
            } else if (test[4] == 'getdetails') {
                var newsrno = 1;
                $("#table1 tbody").append('<tr id="' + newid + '">\n\<td>' + newid +
                    '</td>\n\<td class="productname' + newid + '">' + $("#productname").val() +
                    '</td>\n\<td class="quantity' + newid + '">' + $("#quantity").val() +
                    '</td>\n\<td class="free' + newid + '">' + $("#free").val() +
                    '</td>\n\<td class="mrp(dp)' + newid + '">' + $("#mrp").val() +
                    '</td>\n\<td class="Gross' + newid + '">' + $("#gross").val() +
                    '</td>\n\<td ><a href="javascript:void(0);" class="remCF"><span class="fas fa-trash"> Remove</a></td><td class="newsrno' + newid + '" style="display:none">' + newsrno + '</td\'</tr>');
            }
            var tempqty = document.getElementById("quantity").value;
            var tempgross = document.getElementById("gross").value;
            var tempfree = document.getElementById("free").value;
            if (tempfree != '') {
                tempqty = parseInt(tempfree) + parseInt(tempqty);
            }

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
        var url = window.location.pathname;
        var test = url.split("/");

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
            var saletable = [];
            var totalbill = [];


            var billwisetotal = {
                'BillDate': document.getElementById('date').value,
                'client_id': clientID,
                'TotalAmt': document.getElementById('totalgross').value,
                'createdate': document.getElementById('Duedate').value,
                'AddBy': UserId,
            }
            totalbill.push(billwisetotal);
            $('#table1 tr').each(function(row, tr) {
                if ($(tr).find('td:eq(0)').text() == "") {

                } else {
                    if (test[4] != 'getdetails') {
                        var sub = {
                            'ProductName': $(tr).find('td:eq(1)').text(),
                            'Qty': $(tr).find('td:eq(2)').text(),
                            'Free': $(tr).find('td:eq(3)').text(),
                            'mrp': $(tr).find('td:eq(4)').text(),
                            'productwisegross': $(tr).find('td:eq(5)').text(),
                        }

                    } else if (test[4] == 'getdetails') {
                        var sub = {
                            'ProductName': $(tr).find('td:eq(1)').text(),
                            'Qty': $(tr).find('td:eq(2)').text(),
                            'Free': $(tr).find('td:eq(3)').text(),
                            'mrp': $(tr).find('td:eq(4)').text(),
                            'productwisegross': $(tr).find('td:eq(5)').text(),
                            'newsrno': $(tr).find('td:eq(7)').text(),
                            'Fk_Sale_id': test[5],
                        }
                    }
                    saletable.push(sub);
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
                                document.getElementById("pageloader").style.display = "block";
                                var urlforpost = test[4] != 'getdetails' ? '/pawanenterprises/index.php/sale/addsale' : '/pawanenterprises/index.php/sale/saleedit';
                                $.ajax({
                                    data: data,
                                    type: "POST",
                                    url: urlforpost,
                                    crossOrigin: false,
                                    dataType: 'json',
                                    success: function(result) {
                                        $(".pageloader").fadeOut("slow");
                                        if (result.status == "success") {
                                            var client = {
                                                'clientID': clientID
                                            };
                                            $.ajax({
                                                data: client,
                                                type: "POST",
                                                url: "/pawanenterprises/index.php/sale/getmaxbillno",
                                                crossOrigin: false,
                                                dataType: 'json',
                                                success: function(result) {
                                                    var successMgs = 'Bill No is ' + result.Billdetail.Billno +
                                                        '\nClient Name is ' + result.Billdetail.Name;
                                                    Swal.fire({
                                                            title: 'Successfully Saved',
                                                            text: successMgs,
                                                            icon: 'success',
                                                            timer: 21000,
                                                            timerProgressBar: true,
                                                            buttons: false,
                                                        })
                                                        .then(() => {
                                                            location.href =
                                                                "/pawanenterprises/index.php/sale/index";
                                                        })
                                                }
                                            })
                                        } else if (result.status == "success-only-Read") {
                                            var client = {
                                                'clientID': clientID
                                            };
                                            $.ajax({
                                                data: client,
                                                type: "POST",
                                                url: "/pawanenterprises/index.php/sale/getmaxbillno",
                                                crossOrigin: false,
                                                dataType: 'json',
                                                success: function(result) {
                                                    var successMgs = 'Bill No is ' + result.Billdetail.Billno +
                                                        '\nClient Name is ' + result.Billdetail.Name;
                                                    Swal.fire({
                                                            title: 'Successfully Edit',
                                                            text: successMgs,
                                                            icon: 'success',
                                                            timer: 21000,
                                                            timerProgressBar: true,
                                                            buttons: false,
                                                        })
                                                        .then(() => {
                                                            location.href =
                                                                "/pawanenterprises/index.php/sale/index";
                                                        })
                                                }
                                            })
                                        } else {
                                            Swal.fire('Warning', 'Error Saving',
                                                'warning', 2000);
                                        }
                                    }
                                });


                            }
                        })
                }
            });
        }
    });

    // final save with database end
    $("#table1").on('click', '.remCF', function() {
        var self = this;
        var url = window.location.pathname;
        var test = url.split("/");
        var currow = $(this).closest('tr');
        if (test[4] == 'getdetails') {
            var newsrnoget = currow.find('td:eq(7)').text();
            if (newsrnoget && newsrnoget != '') {
                tabelRowRemove(this);
                return;
            }
            $("#client option").each(function(i, el) {
                data[$(el).data("value")] = $(el).val();
            });
            var client = $('#client').val();
            var NoofDay = $('#client1 [value="' + client + '"]').data(
                'createdate');
            var totalbill = [];
            var updatedforgross = (currow.find('td:eq(5)').text());
            var forUpdatetotalGorss = document.getElementById("totalgross").value;
            var finalgross = (parseFloat(forUpdatetotalGorss) - parseFloat(updatedforgross)).toFixed(2);

            var details = {
                'SaleBillNo': test[5],
                'ProductName': currow.find('td:eq(1)').text(),
                'Qty': currow.find('td:eq(2)').text(),
                'free': currow.find('td:eq(3)').text(),
                'gross': finalgross,
                'AddBy': UserId,
            }
            totalbill.push(details);
            Swal.fire({
                title: 'Are You sure',
                text: 'Delete From The Bill ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Save it!'
            }).then((result) => {
                if (result.value) {
                    var data = {
                        'totalbill': totalbill
                    };
                    document.getElementById("pageloader").style.display = "block";
                    $.ajax({
                        data: data,
                        type: "POST",
                        url: "/pawanenterprises/index.php/sale/editPurchasedelete",
                        crossOrigin: false,
                        dataType: 'json',
                        success: function(result) {
                            $(".pageloader").fadeOut("slow");
                            if (result.status == "success-full") {
                                Swal.fire({
                                        title: 'Data Is Successfully Delete',
                                        text: 'Redirecting...',
                                        icon: 'success',
                                        timer: 2000,
                                        timerProgressBar: true,
                                        buttons: false,
                                    })
                                    .then(function() {
                                        tabelRowRemove(self);
                                        //location.href = "/pawanenterprises/index.php/purchase/index";
                                    })
                            } else {
                                Swal.fire('Warning', 'Error Saving', 'warning');
                            }
                        }
                    });

                }
            });
        } else if (test[4] != 'getdetails') {
            tabelRowRemove(this);
        }
    });
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

function tabelRowRemove(p) {
    var currow = $(p).closest('tr');
    var tqty = currow.find('td:eq(2)').text();
    var tfree = currow.find('td:eq(3)').text();
    var tgross = currow.find('td:eq(5)').text();

    /*textbox value*/
    var totalqty1 = document.getElementById("totalqty").innerHTML;
    var totalgross1 = document.getElementById("totalgross").value;
    /*end*/


    var afterqty = 0,
        aftergross = 0;

    if (tfree != '') {
        tqty = parseInt(tqty, 10) + parseInt(tfree, 10);
    }
    afterqty = parseInt(totalqty1, 10) - parseInt(tqty, 10);
    aftergross = (parseFloat(totalgross1) - parseFloat(tgross)).toFixed(2);

    if (isNaN(afterqty)) afterqty = 0;
    document.getElementById("totalqty").innerHTML = afterqty;
    document.getElementById("totalgross").value = aftergross;

    var table = document.getElementById('table1');
    if (rowcount == 1) {
        document.getElementById("totalitem").innerHTML = 0;
    }
    $(p).parent().parent().remove();
    var rowcount = table.rows.length;
    rowcount--;
    document.getElementById("totalitem").innerHTML = rowcount;
    for (i = 0; i <= rowcount; i++) {
        if (i != 0) {
            document.getElementById('table1').rows[i].cells[0].innerHTML = i;
        }
    }
}

function validateFloatKeyPress(el, evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    var number = el.value.split('.');
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    //just one dot
    if (number.length > 1 && charCode == 46) {
        return false;
    }
    //get the carat position
    var caratPos = getSelectionStart(el);
    var dotPos = el.value.indexOf(".");
    if (caratPos > dotPos && dotPos > -1 && (number[1].length > 1)) {
        return false;
    }
    return true;
}


function getSelectionStart(o) {
    if (o.createTextRange) {
        var r = document.selection.createRange().duplicate()
        r.moveEnd('character', o.value.length)
        if (r.text == '') return o.value.length
        return o.value.lastIndexOf(r.text)
    } else return o.selectionStart
}