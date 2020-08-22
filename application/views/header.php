<?php include('startheader.php');
date_default_timezone_set('Asia/Calcutta'); ?>
<div class="container">
	<div class="col-lg-12 col-sm-12">
		<div class="row">
			<nav class="navbar navbar-expand-lg navbar-light bg-light ">
				<a class="navbar-brand" href="#"></a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarText">
					<ul class="navbar-nav mr-auto" id='menu'>
						<li><a href='<?php echo base_url().'dashboard' ?>'>Home</a></li>
						<li><a class='prett' href='#'>Master</a>
						<ul class='menus'>
							<li><a href='<?php echo base_url().'read/userdata' ?>'>Client</a></li>
							<li><a href='<?php echo base_url().'suppler' ?>'>Suppliers</a></li>
						</ul>
					</li>
					<li><a class='' href='<?php echo base_url().'Purchase' ?>'>Purchase</a>
					<li><a class='' href='<?php echo base_url().'sale' ?>'>Sale</a>
					<li><a class='' href='<?php echo base_url().'report' ?>'>report</a>
				</ul>
				<span class="navbar-text">
					<label style="color:#fff; font-size:1em; margin-right:20px;">Hello. <?php echo $this->session->name; ?></label>
					<a href="<?php echo base_url().'index.php/read/dbbackup';?>" style="color:#fff; font-size:1em; margin-right:20px;">Bankup</a>
					<a href="<?php echo base_url().'login/logout'?>" title="Logout" style="color: #fff;">Logout</a>
				</span>
			</div>
		</nav>
	</div>
</div>
</div>