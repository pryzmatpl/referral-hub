<div id="refair-referrals" class="col-md-12 content-main" style="padding:19px;" >
  <?php
    $jobs = $this->dataReferrals;
  if(count($jobs)>0){ ?>
  <h1 class="jumbotron">Your referrals</h1>
  <div class="col-md-12 row">
  <?php  foreach ($jobs as $value){
	 ?>
  <div class="col-md-6">
    <div id="refair-referral" style="text-align:left;padding:19px">
      <p>ID:<b> <div class="col-md-4" id="jobid" ><?php echo $value->id ; ?></div></b></p>
      <p>Person Referring the person referred:<b> <div class="col-md-4" id="jobtitle" ><?php echo $value->referrer_id ; ?></div></b></p>
      <p>Person Referred:<b> <div class="col-md-4" id="location"><?php echo $value->referred_id; ?></div></b></p>
      <p>Keywords: <b><div  class="col-md-4" id="keywords"><?php echo $value->keywords; ?></div></b></p>
      <p>Registered: <b><div  class="col-md-4"  id="regdate"><?php echo $value->regdate; ?></div></b></p>
    </div>
    <form >
      <div id="refer-but" style="float:left;margin-left:14px;">      
        <a id="refer-but" name="<?php echo $value->id.'~'.$value->hash; ?>"  class="btn btn-raised active butdialog" href="<?php echo base_url("/Refair/describereferral/".$this->hhhash."/".$value->id ); ?>" >Monitor<div class="ripple-container"></div></a>
      </div>
      <div  style="float:left;margin-left:14px;">      
	<p name="<?php echo $value->id.'~'.$value->hash; ?>" class="btn btn-raised active inquire-call" data-target="#ref-inquire" data-toggle="modal">Inquire<div class="ripple-container"></div></p>
      </div>
      <div id="delete-but-div" style="float:left;margin-left:14px;">      
	<p  name="<?php echo $value->id.'~'.$value->hash; ?>" class="btn btn-raised active deleteref-call" data-target="#deleteref-body" data-toggle="modal">Delete<div class="ripple-container"></div></p>
      </div>
    </form>
  </div>
  <?php }}
	$jobstw = $this->dataReferralsReferred;
  if(count($jobstw)>0){ ?>
  <h1 class="jumbotron">You have referred the following people:</h1>
  <div class="col-md-12 row">
  <?php  foreach ($jobstw as $value){ ?>
  <div class="col-md-6">
    <div id="refair-referral" class="referral-referral" style="text-align:left;padding:19px">
      <p>ID:<b> <div class="col-md-4" id="jobid" ><?php echo $value->id ; ?></div></b></p>
      <p>Person Referring the person referred:<b> <div class="col-md-4" id="jobtitle" ><?php echo $value->referrer_id ; ?></div></b></p>
      <p>Person Referred:<b> <div class="col-md-4" id="location"><?php echo $value->referred_id; ?></div></b></p>
      <p>Keywords: <b><div  class="col-md-4" id="keywords"><?php echo $value->keywords; ?></div></b></p>
      <p>Registered: <b><div  class="col-md-4"  id="regdate"><?php echo $value->regdate; ?></div></b></p>
    </div>
    <form >
      <div id="refer-but" style="float:left;margin-left:14px;">      
        <a id="refer-but" name="<?php echo $value->id.'~'.$value->hash; ?>"  class="btn btn-raised active butdialog" href="<?php echo base_url("/Refair/describereferral/".$this->hhhash."/".$value->id ); ?>" >Monitor<div class="ripple-container"></div></a>
      </div>
      <div id="apply-but" style="float:left;margin-left:14px;">      
	<p id="apply-but"  name="<?php echo $value->id.'~'.$value->hash; ?>" class="btn btn-raised active butdialog" data-target="#apply" data-toggle="modal">Inquire<div class="ripple-container"></div></p>
      </div>
      <?php      if($this->userAble){ ?>
      <div id="delete-but-div" style="float:left;margin-left:14px;">      
	<p id="deleteref-call"  name="<?php echo $value->id.'~'.$value->hash; ?>" class="btn btn-raised active butdialog" data-target="#deleteref-body" data-toggle="modal">Delete<div class="ripple-container"></div></p>
      </div>
      <?php } ?>
    </form>
  </div>
  <?php  }}
	 $jobsthre = $this->dataApplications;
  if(count($jobsthre)>=1){
         ?>
  <h1 class="jumbotron">Your job applications:</h1>
  <div class="col-md-12 row">
  <?php
    if(count($jobsthre)>=1){
    foreach ($jobsthre as $value){
	 if( !strcmp($value->referrer_id,$value->referred_id)){
	 ?>
  <div class="col-md-6">
    <div id="refair-referral" class="referral-referral" style="text-align:left;padding:19px">
      <h2>You applied for <b> <div class="col-md-4"  style="width:20%;float:left;margin:10px;padding:5px;" id="jobid" ><?php echo $value->id ?></div>
	    </b><?php echo $value->name ; ?> </h2><br/><br/>
      <p>Keywords: <b><div  class="col-md-4" id="keywords"><?php echo $value->keywords; ?></div></b></p>
      <p>Registered: <b><div  class="col-md-4"  id="regdate"><?php echo $value->regdate; ?></div></b></p>
      <h2 style="float:right;width:22%"><a href="<?php echo base_url('Refair/describejob/'.$this->hhhash.'/'.$value->jobid); ?>">View Job Ad </a></h2>
    </div>
    <form >
      <div id="refer-but" style="float:left;margin-left:14px;">      
        <a id="refer-but" name="<?php echo $value->id.'~'.$value->hash; ?>"  class="btn btn-raised active butdialog" href="<?php echo base_url("/Refair/describereferral/".$this->hhhash."/".$value->id ); ?>" >Monitor<div class="ripple-container"></div></a>
      </div>
      <div id="apply-but" style="float:left;margin-left:14px;">      
	<p id="apply-but"  name="<?php echo $value->id.'~'.$value->hash; ?>" class="btn btn-raised active butdialog" data-target="#apply" data-toggle="modal">Inquire<div class="ripple-container"></div></p>
      </div>
      <?php      if($this->userAble){ ?>
      <div id="delete-but-div" style="float:left;margin-left:14px;">      
	<p id="deleteref-call"  name="<?php echo $value->id.'~'.$value->hash; ?>" class="btn btn-raised active butdialog" data-target="#deleteref-body" data-toggle="modal">Delete<div class="ripple-container"></div></p>
      </div>
      <?php } ?>
    </form>
  </div>
  <br/>
  <?php  }}}}?>

</div>
