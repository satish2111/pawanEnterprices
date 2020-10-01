<?php
$title='Missing Bill Number list';
include('header.php'); ?>
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
    <table id="mytable" class="table table-bordred table-striped">
        <thead>
            <th>Srno</th>
            <th>Bill Missing Number</th>
        </thead>
        <tbody>
            <tr>
                <?php
                    $cnt=1;
                if(!empty($result))
                {
                    foreach($result as $row)
                    {
                        $missingno= $row->Missing_before_this-1;
                        if($missingno!=0){
                        ?>
                    <tr>
                        <td><?php echo $cnt?></td>
                        <td><?php echo htmlentities($missingno); ?></td>
                    </tr>
            <?php $cnt++; } }   
                    } ?>

        </tbody>
</div>
<?php include('footer.php'); ?>