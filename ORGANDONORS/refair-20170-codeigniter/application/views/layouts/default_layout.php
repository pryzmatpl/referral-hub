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

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url() ?>/assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="<?php echo base_url() ?>/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo base_url() ?>/assets/css/justified-nav.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../../assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">
      <!-- The justified navigation menu is meant for single line per list item.
           Multiple lines will require custom code not provided by Bootstrap. -->
      <div class="masthead">
        <h3 class="text-muted">Easy writing</h3>
        <nav>
          <ul class="nav nav-justified">
            <li class="active"><a href="/welcome">Home</a></li>
            <li><a href="/welcome/<?php echo strtolower($this->menus[0]) ?>"><?php echo $this->menus[0] ?></a></li>
            <li><a href="/welcome/<?php echo strtolower($this->menus[1]) ?>"><?php echo $this->menus[1] ?></a></li>
            <li><a href="/welcome/<?php echo strtolower($this->menus[2]) ?>"><?php echo $this->menus[2] ?></a></li>
            <li><a href="/welcome/<?php echo strtolower($this->menus[3]) ?>"><?php echo $this->menus[3] ?></a></li>
          </ul>
        </nav>
      </div>

      <!-- Jumbotron -->
      <div class="jumbotron">
        <h1>PostWriter.co</h1>
        <p class="lead">We make writing easy.</p>
        <p><a class="btn btn-lg btn-success" href="welcome/getstarted" role="button">Start now</a></p>
      </div>

<div class = "jumbotron">
    {submit}
    {popular}
    {fame}
    {notifications}
    {popularlist}
    {famelist}
    {notificationslist}
</div>

      <!-- Site footer -->
      <footer class="footer">
       {footer}
      </footer>

    </div> <!-- /container -->

  </body>
</html>
<div name="modall" id="modall" class="modall"><!-- Place at bottom of page --></div>
<?php } ?>
