<?php
$title='Stock and Sale Compared';
include('header.php'); ?>
<style>
@media print {

#mytable,
.table td,
.table th {
    border: 1px solid #000;
}
}
</style>

<div class="container">

    <div class="pageloader" style='display: none;' id='loader'></div>
    <div class="row mt-5" id='content'>
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
    <button class="btn btn-primary" onclick='printContent(mytable)' id='print'>Print</button>
    <?php if(!empty($result)) 
    { $cnt=1; ?>
    <div class="table-responsive mt-3" id='mytable'>
    
        <table id="table" class="table table-bordred table-striped" style="table-layout: fixed; width: 100%">
            <thead>
                <th>Srno</th>
                <th>Prouct Name</th>
                <th>Total Purchase</th>
                <th>Total Purchase Available Qty </th>
                <th>Total Sale </th>
                <th>Puchase Bill Numbers</th>
                <th>Sale Bill Numbers</th>
            </thead>
            <tbody>
                <?php 
                foreach($result as $row)
                { ?>
                <tr>
                <td><?php echo htmlentities($cnt);?></td>
                <td><?php echo htmlentities($row['ProductName']);?></td>
                <td><?php echo htmlentities($row['purchaseQty']);?></td>
                <td><?php echo htmlentities($row['PurchaseAvailableQty']);?></td>
                <td><?php echo htmlentities($row['TotalSale']);?></td>
                <td style="word-wrap: break-word"><?php echo htmlentities($row['PurchaseBillNo']);?></td>
                <td style="word-wrap: break-word"><?php echo htmlentities($row['SaleBillNo']);?></td>
                </tr>
                <?php 
                $cnt++; } ?>
            </tbody>
        </table>
    </div>
    <?php } ?>
</div>

<?php include('footer.php'); ?>
<script>
var loader;

function loadNow(opacity) {
    if (opacity <= 0) {
        displayContent();
    } else {
        loader.style.opacity = opacity;
        window.setTimeout(function() {
            loadNow(opacity - 0.05);
        }, 50);
    }
}

function displayContent() {
    loader.style.display = 'none';
    document.getElementById('content').style.display = 'block';
}

document.addEventListener("DOMContentLoaded", function() {
    loader = document.getElementById('loader');
    loadNow(1);
    document.getElementById("loader").style.display = "block";

});

function printContent(el) {
    var restorepage = document.body.innerHTML;
    var printcontent = document.getElementById("mytable").innerHTML;
    document.body.innerHTML = printcontent;
    window.print();
    document.body.innerHTML = restorepage;
}
</script>