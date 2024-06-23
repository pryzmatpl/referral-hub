<?php
  $CI=&get_instance();
  $requri = $_SERVER['REQUEST_URI'];
  $uri = explode('/', $requri);
  $len = count($uri);
  $referrer = $uri[$len-1];
  ?>
<br/>
<div class="jumbotron silverplain">
  <h1><?php echo lang('login_heading');?></h1>
  <p><?php echo lang('login_subheading');?></p>
  <p class="blue">If you have just registered, <a>please check your email now</a> to activate.</a></p>
</div>
<br/>
<div style="width:20%;margin:0 auto;" >
  <?php
    if(!strcmp($referrer,'login')){
    echo form_open("auth/login");
    }else{
    echo form_open("auth/login/".$referrer);
    }
    ?>
  <div class="form-group label-floating is-empty  "  style="padding:3px !important">
    <label for="identity" class="control-label help-block">
      <?php echo lang('login_identity_label', 'identity');?>
    </label>
    <?php echo form_input($identity);?>
  </div>
  <div class="form-group label-floating is-empty  "  style="padding:3px !important">
    <label for="password" class="control-label help-block">
      <?php echo lang('login_password_label', 'password');?>
    </label>
    <?php echo form_input($password);?>
  </div>
  <div class="form-group label-floating is-empty  "  style="padding:3px !important">
    <label for="remember" class="control-label help-block">
      <?php echo lang('login_remember_label', 'remember');?>    <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>

    </label>
  </div>
  
  <div class="form-group label-floating is-empty  "  style="padding:3px !important; ">
    <p class=""><?php echo form_submit('submit', lang('login_submit_btn'));?></p>
  </div>
  <?php echo form_close();?>
  
  <a style="margin:0 auto" href="<?php echo base_url("Auth/forgot_password"); ?>"><?php echo lang('login_forgot_password');?></a>
</div>
</div>
</center>
