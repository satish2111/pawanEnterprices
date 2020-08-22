<?php include('startheader.php'); ?>
<style type="text/css">
    .field-icon {
    float: right;
    margin-right: 8px;
    margin-top: -27px;
    position: relative;
    z-index: 2;
    cursor: pointer;
}
</style>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-sm-12 mt-5">
            <form action="<?= base_url(); ?>register/doRegister" method="post">
                <h2>Registration</h2>
                <hr />
                <!-- show error messages if the form validation fails -->
                <?php if ($this->session->flashdata()) { ?>
                <div class="alert alert-danger">
                    <?=$this->session->flashdata('errors'); ?>
                </div>
                <?php } ?>
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" name="name" required class="form-control" placeholder="Full Name" id="name">
                </div>
                <div class="form-group">
                    <label for="email">Email address:</label>
                    <input type="email" name="email" required placeholder="Email" class="form-control" id="email">
                </div>
                 <div class="form-group">
                    <label for="name">User Name:</label>
                    <input type="text" name="username" required  placeholder="UserName" class="form-control" id="username">
                </div>
                <div class="form-group">
                    <label for="pwd">Password:</label>
                    <input type="password" name="password" placeholder="Password" required class="form-control" id="pwd">
                    <span toggle="#pwd" class="fas fa-eye field-icon toggle-password"></span>
                </div>
                <button type="submit" class="btn btn-success">Submit</button>
                <span class="float-right"><a href="<?= base_url() . 'login'; ?>" class="btn btn-primary">Login</a></span>
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