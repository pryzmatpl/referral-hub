<?php
  $CI=&get_instance();
  $requri = $_SERVER['REQUEST_URI'];
  $uri = explode('/', $requri);
  $len = count($uri);
  $referrer = $uri[$len-1];
  ?>
<div class="row">
  <br/>
  <div class="col-md-1"></div>
  <div class="col-md-5 jumbotron" style="float:left;">
    <div class=" form-signin col-md-12" style="float:right;">
      <center>
      <h2>Login to Refair</h2>
      <?php
	if(!strcmp($referrer,'login')){
	echo form_open("auth/login");
	}else{
	echo form_open("auth/login/".$referrer);
	}
	?>
      <div class="form-group label-floating is-empty "  style="padding:3px !important">
	<p style="font-size:24px !important;">
	  <strong>Email</strong>
	</p>
	<?php $fattre = array('class' => 'form-signin form-group'); ?>
	<?php echo form_input($identity,$fattre);?>
      </div>
      <div class="form-group label-floating is-empty  "  style="padding:3px !important">
	<p style="font-size:24px !important;">
	  <strong>Password</strong>
	</p>
	<?php echo form_input($password);?>
      </div>
      <div class="col-md-12 form-group label-floating is-empty  "  style="padding:3px !important; margin:0 auto;">
	<div class="col-md-12">
	  <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>
	</div>
	<strong>Remember</strong>
      </div><br/>
      <div class="form-group label-floating is-empty  "  style="margin:13px !important; ">
	<p class="col-md-12" style="font-size:20px;"><?php echo form_submit('submit', lang('login_submit_btn'));?></p>
      </div>
      <a style="margin:0 auto" href="<?php echo base_url("Auth/forgot_password"); ?>"><?php echo lang('login_forgot_password');?></a>
      <?php echo form_close();?>
    </div>
    </center>
    </div>
  
    <div class="col-md-5 " style="float:left;">
      <div id="infoMessage" class="jumbotron"><?php ?>
    <div class="col-md-12">
      <a  id="googlelogin" class="blue googlelogin col-md-5" href="#">Sign in with Google</a>
      <div class="col-md-2"></div>
      <a  id="officelogin" class="blue officelogin col-md-5" href="#">Sign in with Microsoft</a>
    </div>
      <h2 style="padding-top:30px;margin-top:30px">Let's get started</h2>
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
    </div>
      <div class="col-md-1"></div>

  </div>
  <div class="legal">
  </div>






