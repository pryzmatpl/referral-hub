<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
  if(isset($this->menus)){
?>
<html lang="en">
  <head>
    {head}
    {metas}
    {scripts}
    {styles}                                                                  
    <link rel="icon" href="../../favicon.ico">
    {title}

    <!-- Just for debugging purposes. Don\'t actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../../assets/js/ie-emulation-modes-warning.js"></script>

    <link href="<?php echo base_url(); ?>assets/css/roboto.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/material.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/ripples.min.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
    {flash}
  <body>
     <?php if($this->session->flashdata('succes')){; ?>
                            <div class="alert alert-dismissable alert-success">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong><?php echo $this->session->flashdata('succes'); ?>!</strong>
                            </div>
                <?php } ?>

                   <?php if($this->session->flashdata('fail')){; ?>
                            <div class="alert alert-dismissable alert-danger">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong><?php echo $this->session->flashdata('fail'); ?></strong> 
                        </div>
                <?php } ?>

                <?php if($this->session->flashdata('delete')){; ?>
                            <div class="alert alert-dismissable alert-success">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong><?php echo $this->session->flashdata('delete'); ?></strong> 
                        </div>
                <?php } ?>

<div class="well page active" id="quantum" style="display: block;">		

<div class="navbar navbar-material-light-blue">
              <div class="container-fluid">
                <div class="navbar-header">
                  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-inverse-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </button>
                  <a class="navbar-brand" href="/welcome"><img src="<?php echo base_url() ?>assets/images/logo-prism.png" style="height:inherit; margin-top:-15px;">
  Your browser does not support SVG
</object>
  </a>
                </div>

                <div class="navbar-collapse collapse navbar-material-light-blue-collapse">
                  <ul class="nav navbar-nav">
  <?php
  foreach($this->menus as $menustr){
      $menuarr=explode('~',$menustr);
      echo "<li><a href=\"".base_url($menuarr[0])."\">".$menuarr[1]."</a></li>";
  }
  ?>
                  </ul>
                  <form class="navbar-form navbar-left">
  <p>{quotes}</p>
                  </form>
                  <ul class="nav navbar-nav navbar-right">
                    <li><a href="<?php echo base_url('Welcome/tutorial'); ?>">Tutorial</a></li>
                    <li class="dropdown">
  <a href="bootstrap-elements.html" data-target="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">Dropdown<b class="caret"></b></a>
                      <ul class="dropdown-menu">
    <?php
  foreach($this->menus as $menustr){
      $menuarr=explode('~',$menustr);
      echo "<li><a href=\"".base_url($menuarr[0])."\">".$menuarr[1]."</a></li>";
  }
  ?>
                  </ul>
                  </li>
                </div>
              </div>
            </div>
  
<div class="container">
    {contente}
    {submit}
    {history}
<div class="jumbotron" style="margin-top:393px;">Hello! Find your research results faster with key pattern sort</div>
            
      <!-- Example row of columns -->
      <div class="row" style="margin-top:100px;">
        <div class="col-lg-4">
          {popular}
        </div>
        <div class="col-lg-4">
           {fame}
       </div>
        <div class="col-lg-4">
           {notifications}
        </div>
      </div>
</div>
      <!-- Site footer -->
      <footer class="footer">
       {footer}
       {error}
      </footer>

    </div> <!-- /container -->
</div>
  </body>
</html>
       <div name="modall" id="modall" class="modall"><!-- Place at bottom of page -->{waiting}</div>
<?php } ?>
