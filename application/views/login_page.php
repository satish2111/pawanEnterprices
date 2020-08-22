<?php 
$title='Login';
include('startheader.php'); ?>
<link rel=stylesheet href="<?php echo base_url('assests/css/login.css'); ?>">
<div class="container">
    <div class="row">
        <div class="offset-3 col-md-6 col-sm-12 mt-5">      
            <form action="<?= base_url(); ?>login/doLogin" method="post">
                <h2>Login Page</h2>
                <hr />
                <?php if ($this->session->flashdata()) { ?>
                <div class="alert alert-warning">
                    <?= $this->session->flashdata('msg'); ?>
                </div>
                <?php } ?>
                <div class="form-group">
                    <label for="email">Email address:</label>
                    <input type="text" name="email" autocomplete="off" placeholder="Username"required class="form-control" id="email">
                </div>
                <div class="form-group">
                    <label for="pwd">Password:</label>
                    <input type="password" name="password"  autocomplete="off" placeholder="Password" required class="form-control password" id="pwd">
                    <span toggle="#pwd" class="fas fa-eye field-icon toggle-password"></span>
                </div>
                <button type="submit" class="btn btn-success">Login</button>
                <span class="float-right"><a href="<?= base_url() . 'register'; ?>" class="btn btn-normal">Register</a></span>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(".toggle-password").click(function() {

  $(this).toggleClass("fa-eye fa-eye-slash");
  var input = $($(this).attr("toggle"));
  if (input.attr("type") == "password") {
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }
});
</script>