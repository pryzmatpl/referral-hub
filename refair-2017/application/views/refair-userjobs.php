<?php
  function shortString($yourString,$maxsize){
  if (strlen($yourString) > $maxsize) // if you want...
{
$maxLength = $maxsize;
$yourString = substr($yourString, 0, $maxLength).'...';
}
return $yourString;
}
?>
<div id="refair-dashuserjobs" class="col-md-12" >
  <h1 class="jumbotron col-md-12">Your job offers</h1>
  <?php
    $jobs = $this->dataJobs;
  if(count($jobs)>0){
  foreach ($jobs as $value){
  ?>
  <div id="refair-result" class="col-md-12" style="background:#fe8888;padding-top:20px;">
    <div class="jumbotron col-md-12">
      <a style="font-size:24px;" href="<?php echo base_url('Refair/describejob/'.$this->hhhash.'/'.$value->id); ?>" >
      <?php echo $value->jobtitle ; ?></a>
</div>

<div class="col-md-4">Registered: <?php echo $value->regdate; ?></div>
  <?php 
    $values = base64_decode($value->location);
    $values = explode('~',$values); //My god each db must have a hash to it
    ?>

  <div class=" col-md-4">Location:<b> <div class=" " id="location"><?php echo $values[2]; ?></div></b></div>
  <div class=" col-md-4">Experience required: <b><div  id="required_exp"><?php echo $value->required_exp; ?></div></b></div>
  <div class=" col-md-4">Fund required: <b><div  id="required_exp"><?php echo $value->required_fund; ?></div></b></div>
  <div class="col-md-3" >Relocation: <b><div  id="required_relocation"><?php echo $value->required_relocation; ?></div></b></div>
  <div class=" col-md-2">Remote: <b><div  class=" " id="required_remote"><?php echo $value->required_remote; ?></div></b></div>
  <div class=" col-md-7">Keywords: <b><div class="refair_userjobs_keywords">
	<?php echo shortString($value->keywords,23); ?></div></b>
  </div>
    <form class="col-md-12">
      <div  class="col-md-8">
	<p name="<?php echo $value->id.'~'.$value->jobtitle.'~'.$value->keywords.'~'.$value->location.'~'.$this->emailes; ?>"  class="btn  refair-call" style="width:90%;" data-target="#refer-body" data-toggle="modal">Refer someone<div class="ripple-container"></div></p>
      </div>
      <div  class="col-md-4">
	<p name="<?php echo $value->id.'~'.$value->jobtitle.'~'.$value->keywords.'~'.$value->location.'~'.$this->emailes; ?>" class="btn  apply-call" data-target="#apply-body" data-toggle="modal">Apply<div class="ripple-container"></div></p>
      </div>
      <?php      if($this->userAble){ ?>
      <div  class="col-md-4">
	<p name="<?php echo $value->id.'~'.$value->poster_id.'~'.$value->jobtitle.'~'.$value->keywords.'~'.$value->location.'~'.$this->emailes; ?>" class="btn  deletejob-call" data-target="#delete-jobss" data-toggle="modal">Delete<div class="ripple-container"></div></p>
      </div>
      <?php      } ?>
      <div class="hidden" id="abbrev"><?php echo $value->id.'~'.$value->jobtitle.'~'.$value->keywords.'~'.$value->location; ?></div>
    </form>
  </div>
  <?php
    }    
    }?>
</div>


