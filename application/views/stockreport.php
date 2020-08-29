<?php
$title='Current Stock';
include('header.php'); ?>
<style>
.blocks {
    width: 100%;
}

.block-col {
    width: 100%;
    float: left;
}

.block-col {
    padding: 0;
    margin: 0;
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
            <div class="col-md-4">
                <button class="btn btn-primary" onclick='printContent(mytable)'>Print</button>
            </div>
            <div class="table-responsive mt-3 blocks" id='mytable'>
                <h1>Current Stock Till Date:<?php echo date("d-m-Y") ?></h1>
                <table class="table table-bordred table-striped block-col">
                    <thead>
                        <th>#</th>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>MRP</th>
                        <th>Total</th>
                    </thead>
                    <tbody>

                        <?php  
                        $Amttotal=null;
                        $cnt=1;
                        foreach($result as $key => $rw) {  $Amttotal+= ($rw->Total); ?>
                        <tr>
                            <td><?php echo $cnt;?></td>
                            <td><?php echo $rw->ProductName; ?> </td>
                            <td><?php echo $rw->Qty;?></td>
                            <td><?php echo $rw->MRP;?></td>
                            <td><?php echo $rw->Total;?></td>
                        </tr>
                        <?php 
                    
                    $cnt++; }?>
                        <tr>
                            <td colspan='5'><h4 style='float:right;color:green;margin-right: 4%;'><?php echo number_format($Amttotal,2)?></h4></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include('footer.php'); ?>
<script>
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