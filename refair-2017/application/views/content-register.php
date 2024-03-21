<div class="col-lg-4 col-lg-offset-4">
    <h2>Hello There</h2>
    <h5>Please enter the required information below. </h5>
    <h3>We will sign you up for beta!</h3>
<?php 
    $fattr = array('class' => 'form-signin form-group');
    echo form_open('account', $fattr); ?>
    <div class="form-group">
      <?php echo form_input(array('name'=>'email', 'id'=> 'email', 'placeholder'=>'Email', 'class'=>'email form-control blue' , 'type'=>'email', 'value'=> set_value('email'))); ?>
      <?php echo form_error('email');?>
    </div>
    <div class="form-group">
      <?php echo form_input(array('name'=>'password', 'id'=> 'password', 'placeholder'=>'Password', 'class'=>'password form-control red', 'type'=>'password', 'value'=> set_value('password'))); ?>
      <?php echo form_error('passwd');?>
    </div>
    <div class="form-group">
      <?php echo form_input(array('name'=>'passwordrep', 'id'=> 'passwordrep', 'placeholder'=>'Password Repeat', 'class'=>'password form-control red', 'type'=>'password', 'value'=> set_value('passwordrepeat'))); ?>
      <?php echo form_error('passwd');?>
    </div>
    <?php echo form_submit(array('value'=>'Sign up/Log in', 'class'=>'btn btn-lg btn-primary btn-block')); ?>
    <?php echo form_close(); ?>
</div>

