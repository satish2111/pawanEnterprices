// $(window).load(function() {
//   $(".pageloader").fadeOut("slow");
// });
$(document).ready(function() {
	$("#mrp").blur(function() {
		var cost = document.getElementById("cost").value;
		var mrp = document.getElementById("mrp").value;
		if (mrp != '') {
			if (Number(cost) > Number(mrp)) {
				Swal.fire({
					title: 'Error',
					text: 'MRP should greater than cost',
					icon: 'error',
					showCancelButton: false,
					timer: 2000,
					timerProgressBar: true,
				});
				//document.getElementById("mrp").value='';
				//$("#mrp").focus();
			}
		}
	});

	var UserId = document.getElementById("session").value;
	$("#supplername").blur(function() {
		var textboxvalue = document.getElementById("supplername").value;
		if (textboxvalue != '') {
			document.getElementById("pageloader").style.display = "block";
			var data = {
				'textboxvalue': textboxvalue,
			};
			$.ajax({
				data: data,
				type: "POST",
				url: "/pawanenterprises/index.php/Purchase/checkuser",
				crossOrigin: false,
				dataType: 'json',
				success: function(result) {
					$(".pageloader").fadeOut("slow");
					if (result.status == "success") {
						console.log(result.status);
					} else {
						Swal.fire({
							title: 'Error',
							text: 'Suppler Name Not Found',
							icon: 'error',
							showCancelButton: false,
							timer: 2000,
							timerProgressBar: true,
						});
						document.getElementById("supplername").value = '';
						$("#supplername").focus();
					}
				}
			});
		}
	});

	$('[id^=cost]').keypress(validateNumber);
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


	$("#btnsave").click(function() {
		var errormessgae = '';
		if ($('#billno').val() == 0) {
			errormessgae += 'Bill No, '
		}
		if ($('#billdate').val() == 0) {
			errormessgae += 'Bill Date, '
		}
		if ($('#supplername').val() == 0) {
			errormessgae += 'Suppler, '
		}
		if ($('#productname').val() == 0) {
			errormessgae += 'Product Name, '
		}
		if ($('#qty').val() == 0) {
			errormessgae += 'Qty, '
		}
		if ($('#cost').val() == 0) {
			errormessgae += 'Cost, '
		}
		if ($('#mrp').val() == 0) {
			errormessgae += 'MRP'
		}
		if (errormessgae != '') {
			finalerror = errormessgae;
			errormessgae = '';
			errormessgae += 'Please fill the follow field <br/> ' + finalerror;
			Swal.fire('Error list', errormessgae,"error");
		}
		if (errormessgae == '') {
			var table = document.getElementById('table1');
			var rowcount = table.rows.length;
			if (rowcount == 1 && id != 1) {
				id = id - 1;
			}
			var newid = rowcount;
			var newsrno = 1;
			var url = window.location.pathname;
			var test = url.split("/");
			if (test[4] != 'getdetails') {
				$("#table1 tbody").append('<tr id="' + newid + '">\n\<td>' + newid + '</td>\n\<td class="productname' + newid + '">' + $("#productname").val() + '</td>\n\<td class="quantity' + newid + '">' + $("#quantity").val() + '</td>\n\<td class="cost' + newid + '">' + $("#cost").val() + '</td>\n\<td class="mrp(dp)' + newid + '">' + $("#mrp").val() + '</td>\n\<td class="Gross' + newid + '">' + $("#gross").val() + '</td>\n\<td ><a href="javascript:void(0);" class="remCF"><span class="fas fa-trash"> Remove</a></td>\n\<td width="40px" style="display:none;" class="date' + newid + '">' + $("#date").val() + '</td>\n\<td width="40px" style="display:none;" class="billno' + newid + '">' + $("#billno").val() + '</td>\'</tr>');
			} else {
				$("#table1 tbody").append('<tr id="' + newid + '">\n\<td>' + newid + '</td>\n\<td class="productname' + newid + '">' + $("#productname").val() + '</td>\n\<td class="quantity' + newid + '">' + $("#quantity").val() + '</td>\n\<td class="cost' + newid + '">' + $("#cost").val() + '</td>\n\<td class="mrp(dp)' + newid + '">' + $("#mrp").val() + '</td>\n\<td class="Gross' + newid + '">' + $("#gross").val() + '</td>\n\<td ><a href="javascript:void(0);" class="remCF"><span class="fas fa-trash"> Remove</a></td>\n\<td width="40px" style="display:none;" class="date' + newid + '">' + $("#date").val() + '</td>\n\<td width="40px" style="display:none;" class="billno' + newid + '">' + $("#billno").val() + '</td>\n\<td width="40px" style="display:none;" class="newsrno' + newid + '">' + newsrno + '</td>\'</tr>');
			}
			var tempqty = document.getElementById("quantity").value;
			var tempgross = document.getElementById("gross").value;
			var totalqty1 = document.getElementById("totalqty").innerHTML;
			var totalgross1 = document.getElementById("totalgross").value;
			if (totalgross1 == '') {
				totalgross1 = 0;
			}
			document.getElementById("totalitem").innerHTML = newid;
			document.getElementById("totalqty").innerHTML = parseInt(totalqty1, 10) + parseInt(tempqty, 10);
			document.getElementById("totalgross").value = (parseFloat(tempgross) + parseFloat(totalgross1)).toFixed(2)

			document.getElementById("productname").value = "";
			document.getElementById("quantity").value = "";
			document.getElementById("cost").value = "";
			document.getElementById("mrp").value = "";
			document.getElementById("gross").value = "";


			$("#productname").focus();
			document.getElementById('table1').style.display = "inline-table"
			document.getElementById('totalpanel').style.display = "flex"
		}

	});

	$("#table1").on('click', '.remCF', function() {
		var self=this;
		var url = window.location.pathname;
		var test = url.split("/");
		var currow = $(this).closest('tr');
		//console.log(currow);
		//console.log(test[4]);
		if (test[4] == 'getdetails') {
			var newsrnoget=currow.find('td:eq(9)').text();
			// newsrno 1 
			// no col. for old and get database
			if(newsrnoget && newsrnoget!='')
			{
				tabelRowRemove(this);
				return;
			}
			$("#supplername option").each(function(i, el) {
				data[$(el).data("value")] = $(el).val();
			});
			var supplername = $('#supplername').val();
			var supplernameID = $('#supplername1 [value="' + supplername + '"]').data('value');
			var totalbill = [];

			var billwisetotal = {
				'suppler_id': supplernameID,
				'BillNo': document.getElementById('billno').value,
				'BillDate': document.getElementById('date').value,
				'AddBy': UserId,
				'productname': currow.find('td:eq(1)').text(),
				'Pur_fk_Id': currow.closest('tr').attr('id'),
			}
			totalbill.push(billwisetotal);
			Swal.fire({
					title: 'Are You sure',
					text: 'Delete From The Bill ?',
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Yes, Save it!'
				})
				.then((result) => {
					if (result.value) {
						var data = {
							'totalbill': totalbill
						};
						document.getElementById("pageloader").style.display = "block";
						$.ajax({
							data: data,
							type: "POST",
							url: "/pawanenterprises/index.php/purchase/editPurchasedelete",
							crossOrigin: false,
							dataType: 'json',
							success: function(result) {
								$(".pageloader").fadeOut("slow");
								if (result.status == "success") {
									Swal.fire({
										title: 'Successfully',
										text: 'Data Is Delete and Update also',
										icon: 'success',
										timer: 2000,
										timerProgressBar: true,
										buttons: false,
									})
								} else if (result.status == "success-full") {
									Swal.fire({
											title: 'Data Is Successfully Delete',
											text: 'Redirecting...',
											icon: 'success',
											timer: 2000,
											timerProgressBar: true,
											buttons: false,
										})
										.then(function () {
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
		}
		else if(test[4] != 'getdetails'){
				tabelRowRemove(this);
		}
		
	});

	$("#reset").click(function() {
		document.getElementById("productname").value = "";
		document.getElementById("quantity").value = "";
		document.getElementById("cost").value = "";
		document.getElementById("mrp").value = "";
		document.getElementById("gross").value = "";
		$("#productname").focus();
	});


	$('#done').click(function() {
		var table = document.getElementById('table1');
		var rowcount = table.rows.length;
		var item = document.getElementById("totalitem").innerHTML;
		if (rowcount == 1 && item == 0) {
			swal('Warning', 'Bill Can not save Empty', "error");
			return;
		} else {
			$("#supplername option").each(function(i, el) {
				data[$(el).data("value")] = $(el).val();
			});
			var supplername = $('#supplername').val();
			var supplernameID = $('#supplername1 [value="' + supplername + '"]').data('value');
			var purchasestockwise = [];
			var totalbill = [];
			var url = window.location.pathname;
			var test = url.split("/");

			var billwisetotal = {
				'suppler_id': supplernameID,
				'BillNo': document.getElementById('billno').value,
				'BillDate': document.getElementById('date').value,
				'Total_Amt': document.getElementById('totalgross').value,
				'AddBy': UserId,
				'Pur_Id': document.getElementById('PurId') === null ? 0 : document.getElementById('PurId').value,
			}

			totalbill.push(billwisetotal);
			$('#table1 tr').each(function(row, tr) {
				if ($(tr).find('td:eq(0)').text() == "" ) {

				} 
				else {
					if (test[4] != 'getdetails') {
						var sub = {
							'BillDate': document.getElementById('date').value,
							'BillNo': document.getElementById('billno').value,
							'srno': $(tr).find('td:eq(0)').text(),
							'suppler_id': supplernameID,
							'ProductName': $(tr).find('td:eq(1)').text(),
							'Qty': $(tr).find('td:eq(2)').text(),
							'Cost': $(tr).find('td:eq(3)').text(),
							'MRP': $(tr).find('td:eq(4)').text(),
							'Status': 'A',
							'AddBy': UserId,
						}
						
						purchasestockwise.push(sub);
					} else if (test[4] == 'getdetails') {
						var sub = {
							'BillDate': document.getElementById('date').value,
							'BillNo': document.getElementById('billno').value,
							'srno': $(tr).find('td:eq(0)').text(),
							'suppler_id': supplernameID,
							'ProductName': $(tr).find('td:eq(1)').text(),
							'Qty': $(tr).find('td:eq(2)').text(),
							'Cost': $(tr).find('td:eq(3)').text(),
							'MRP': $(tr).find('td:eq(4)').text(),
							'Status': 'A',
							'AddBy': UserId,
							'newsrno': $(tr).find('td:eq(9)').text(),
						}

						purchasestockwise.push(sub);
					}
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
								'purchasestockwise': purchasestockwise,
								'totalbill': totalbill
							};
							document.getElementById("pageloader").style.display = "block";
							if (test[4] != 'getdetails') {
								$.ajax({
									data: data,
									type: "POST",
									url: "/pawanenterprises/index.php/purchase/addPurchase",
									crossOrigin: false,
									dataType: 'json',
									success: function(result) {
										$(".pageloader").fadeOut("slow");
										console.log(result.status);
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
													location.href = "/pawanenterprises/index.php/purchase/index";
												})
										} 
										else if(result.status=="success-only-delete")
										{
											Swal.fire({
													title: 'Successfully Saved',
													text: 'Redirecting...',
													icon: 'success',
													timer: 2000,
													timerProgressBar: true,
													buttons: false,
												})
												.then(() => {
													location.href = "/pawanenterprises/index.php/purchase/index";
												})
										}
										

										else {
											Swal.fire('Warning', 'Error  Saving', 'warning');
										}
									}
								});
							} else if (test[4] == 'getdetails') {
								//console.log(data);
								$.ajax({
									data: data,
									type: "POST",
									url: "/pawanenterprises/index.php/purchase/Purchasenew",
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
													location.href = "/pawanenterprises/index.php/purchase/index";
												})
										} 
										else if(result.status=="success-only-delete")
										{
											Swal.fire({
													title: 'Successfully Saved',
													text: 'Redirecting...',
													icon: 'success',
													timer: 2000,
													timerProgressBar: true,
													buttons: false,
												})
												.then(() => {
													location.href = "/pawanenterprises/index.php/purchase/index";
												})
										}
										else if(result.status=="success-only-Read")
										{
											Swal.fire({
													title: 'Successfully Saved',
													text: 'Redirecting...',
													icon: 'success',
													timer: 2000,
													timerProgressBar: true,
													buttons: false,
												})
												.then(() => {
													location.href = "/pawanenterprises/index.php/purchase/index";
												})
										}
										else {
											Swal.fire('Warning', 'Error 99999 Saving', 'warning');
										}
									}
								});
							}
						} else {
							Swal.fire({
								title: 'Cancel!',
								text: "Your click on Cancel.",
								icon: 'success',
								timer: 2000,
								timerProgressBar: true,
							})
						}
					});
			});
		}



	});
});


function isNumberKey(evt) {
	var charCode = (evt.which) ? evt.which : event.keyCode
	if (charCode > 31 && (charCode < 48 || charCode > 57))
		return false;
	return true;
}

function calculate() {
	var quantity = document.getElementById('quantity').value;
	var rate = document.getElementById('cost').value;
	var grossbefore = quantity * rate;
	if (quantity != '' && rate != '') {

		document.getElementById('gross').value = (parseFloat(grossbefore)).toFixed(2);
	}
}

function validateNumber(event) {
	var key = window.event ? event.keyCode : event.which;
	if (event.keyCode === 8 || event.keyCode === 46) {
		return true;
	} else if (key < 48 || key > 57) {
		return false;
	} else {
		return true;
	}
}


function tabelRowRemove(p)
{
	var currow = $(p).closest('tr');
	console.log(currow);
	var tqty = currow.find('td:eq(2)').text();
		var trate = currow.find('td:eq(3)').text();
		var tgross = currow.find('td:eq(5)').text();


		/*textbox value*/
		var totalqty1 = document.getElementById("totalqty").innerHTML;
		var totalgross1 = document.getElementById("totalgross").value;
		/*end*/


		var afterqty = 0,
			aftergross = 0;
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