<center>
<div class="jumbotron silverplain"><h1><?php echo lang('register_heading');?></h1></div>
<p><?php echo lang('register_subheading');?></p>

<div id="infoMessage" class="jumbotron"><?php echo $message;?>
<?php $fattr = array('class' => 'form-signin form-group'); ?>
<?php echo form_open("auth/register",$fattr);?>

  <p><strong>Username</strong>
    <?php echo lang('register_username_label', 'username');?><br/>
    <?php echo form_input($username);?>
  </p>

  <p><strong>Email</strong>
    <?php echo lang('register_identity_label', 'identity');?><br/>
    <?php echo form_input($identity);?>
  </p>

  <p><strong>Password:</strong>
    <?php echo lang('register_password_label', 'password');?><br/>
    <?php echo form_input($password);?>
  </p>

<p><strong>Password repeat:</strong>
    <?php echo lang('register_passwordrep_label', 'passwordrep');?><br/>
    <?php echo form_input($passwordrep);?>
  </p>


  <p><?php echo form_submit('submit', lang('register_submit_btn'));?></p>

<?php echo form_close();?>

</div>
</center>