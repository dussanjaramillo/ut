

<div class="center-form">
<h1><?php echo lang('login_heading');?></h1>

<?php echo form_open("auth/login");?>
<?php if( isset( $message ) && !empty( $message )  ) { ?>
  <div class="alert alert-success">
    <?php echo $message;?>
  </div>
<?php } ?>
  <p>
    <?php echo lang('login_identity_label', 'identity');?>
    <?php echo form_input($identity);?>
  </p>

  <p>
    <?php echo lang('login_password_label', 'password');?>
    <?php echo form_input($password);?>
  </p>
 <div class="control-group">
    <div class="controls">
      <label class="checkbox">

             <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>
             <?php echo lang('login_remember_label', 'remember');?>
      </label>
      <button type="submit" name="submit" value="Ingresar" class="btn btn-success btn-right">
      	<i class="fa fa-sign-in fa-lg"></i> Ingresar
      </button>
     </div>
</div>

<?php echo form_close();?>

<p><a href="forgot_password"><?php echo lang('login_forgot_password');?></a></p>
</div>