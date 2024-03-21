<div class="jumbotron red">Your searches on Prism:</div>
<?php
foreach($this->keyhistory as $key=>$value){
    ?>
    <div class="jumbotron content-main">
    <div >
    <p>Result from <?php echo $value->regdate; ?></p>
    <h5><?php echo $value->keyone; ?></h5>
    <h5><?php echo $value->keytwo; ?></h5>
    <h5><?php echo $value->keythree; ?></h5>
    </div><div> 
    <h5><?php echo $value->searchterm; ?></h5>
    <?php
    $hashedlink = base_url("Welcome/justquery/".base64_encode($value->keyone.'~'.$value->keytwo.'~'.$value->keythree.'~'.$value->searchterm));
    ?>
    <a> <?php echo $hashedlink; ?> </a>
    </div>
    </div>
    <?php } ?>