<?php
$title='Sale-Edit';
include('header.php');?>
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
                <p style="font-size: 20px; color:green"><?php  echo $this->session->flashdata('success'); ?></p>
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
                        autocomplete="off" required tabindex="-1" value='<?php $result->totalAmt->DueDate; ?>'
                        readonly />
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
                    <input id="client" list="client1" placeholder="Select Client Name" type="text" name="Client"
                        class="form-control" style="text-transform: uppercase;"
                        value='<?php echo $result->clientdetail->Name?>'>
                    <datalist id='client1' required name='client1'>
                        <option data-value='<?php echo $result->clientdetail->client_id?>'
                            data-createdate='<?php echo $result->clientdetail->creditdays ?>'
                            value="<?php echo $result->clientdetail->Name; ?>">
                        </option>
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
                            $productnamelist=$this->sale->productDatalist();
							
							foreach ($productnamelist as $productData)
							{ ?>
                        <option data-value='<?php echo $productData->Qty ?>' data-mrp='<?php echo $productData->MRP?>'
                            value="<?php echo $productData->ProductName ?>">
                        </option>
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
        style='background-color:#cccccc47; padding:0.5rem 0; border-radius: 15px;align-items:center;'>
        <div class="col-md-3 align-items-center">
            <div class="form-group row ">
                <div class="col-md-4">
                    <label for="nameField" class="col-sm-9">Total_Item:-</label>
                </div>
                <div class="col-md-8">
                    <label for="nameField" name="totalitem" id="totalitem" class="col-sm-3"
                        style="color:red; font-weight:500; font-size:1rem;"><?php echo $result->amt->Totalitem;?></label>
                </div>
            </div>
        </div>
        <div class="col-md-3 align-items-center">
            <div class="form-group row ">
                <div class="col-md-4">
                    <label for="nameField" class="col-sm-12">Total_QTY+Free:-</label>
                </div>
                <div class="col-md-8">
                    <label for="nameField" name="totalqty" id="totalqty" class="col-sm-12"
                        style="color:red; font-weight:500; font-size:1rem;text-align: right;padding-right:30%"> <?php echo $result->amt->Totalqty; ?></label>
                </div>
            </div>
        </div>
        <div class="col-md-3 align-items-center">
            <div class="form-group row ">
                <div class="col-md-4">
                    <label for="nameField" class="col-sm-2">Total:- </label>
                </div>
                <div class="col-md-8">
                    <input type="text" name='totalgross' readonly id='totalgross' class="form-control"
                        value="<?php echo $result->totalAmt->TotalAmt;?>"
                        style="color:red; font-weight:500; font-size:1rem;" />
                </div>
            </div>
        </div>
        <div class="col-md-3 align-items-center">
            <button class="btn btn-primary" id='done' title='Final Save'>Done</button>
            <a href="<?php echo base_url().'index.php/sale' ?>" class="btn btn-danger"
                title='Final Cancel'>Cancel</a>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-lg-12 table-responsive">
            <table id="table1" name="table1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>SRNO</th>
                        <th>ProductName</th>
                        <th>Qty</th>
                        <th>Free</th>
                        <th>DP (<small>MRP</small>)</th>
                        <th>Gross</th>
                        <th>Remove</th>
                         <th style='display:none'>newsrno</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                $cnt=1;
				if(!empty($result))
				{
                     foreach($result->tabledata as $row){
                         ?>
                         <tr>
                         <td><?php echo $cnt ?></td>
                         <td><?php echo $row->ProductName; ?></td>
                         <td><?php echo $row->Qty; ?></td>
                         <td><?php echo $row->Free; ?></td>
                         <td><?php echo $row->mrp; ?></td>
                         <td><?php echo $row->productwisegross; ?></td>
                         <td><a href="javascript:void(0);" class="remCF">
                                <span class="fas fa-trash"> Remove</a></td>
                        <td style='display:none'></td>
                         </tr>
                         <?php
                         $cnt++;
                     }
                }?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="pageloader" style='display: none;' id='pageloader'></div>
</div>
<?php include('footer.php'); ?>
<script src="<?php echo base_url('assests/js/saleAdd.js'); ?>"></script>
<script type="text/javascript">
$(document).ready(function() {
    var Billdate = "<?php echo  date('yy/m/d',strtotime($result->totalAmt->Billdate))?>";
    var Duedate = "<?php echo  date('yy/m/d',strtotime($result->totalAmt->DueDate))?>";

    var tempDate = getdateformat(Billdate);
    var tempDueDate = getdateformat(Duedate);
    $('#date').val(tempDate);
    $('#Duedate').val(tempDueDate);

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
    return yyyy + '-' + mm + '-' + dd;
}
</script>