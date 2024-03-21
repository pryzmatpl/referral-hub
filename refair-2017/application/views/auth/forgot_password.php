<?php
$CI=&get_instance();

$requri = $_SERVER['REQUEST_URI'];

$uri = explode('/', $requri);
$len = count($uri);
$referrer = $uri[$len-1];


?>
<center>
<h1><?php echo lang('forgot_password_heading');?></h1>
<p><?php echo "Please help us find your account."; ?></p>

<div id="infoMessage" class="content-main jumbotron silver"><?php echo $message;?><br/>

<?php echo form_open("auth/forgot_password");?>

      <p>
      	<label for="identity"><?php echo (($type=='email') ? sprintf(lang('forgot_password_email_label'), $identity_label) : sprintf(lang('forgot_password_identity_label'), $identity_label));?></label> <br />
      	<?php echo form_input($identity);?>
      </p>

      <p><?php echo form_submit('submit', lang('forgot_password_submit_btn'));?></p>

<?php echo form_close();?>
</div>
</center>