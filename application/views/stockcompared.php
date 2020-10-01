<?php
$title='Stock and Sale Compared';
include('header.php'); ?>

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
    <div class="table-responsive mt-3">
        <table id="mytable" class="table table-bordred table-striped">
            <thead>
                <th>Srno</th>
                <th>Prouct Name</th>
                <th>Total Purchase</th>
                <th>Total Status Update</th>
                <th>Total Sale </th>
                <th>Puchase Bill Numbers</th>
                <th>Sale Bill Numbers</th>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<?php include('footer.php'); ?>