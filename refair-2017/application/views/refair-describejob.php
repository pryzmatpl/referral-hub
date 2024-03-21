<div id="refair-jobdescs" class="white content-main centered" style="overflow:hidden !important;" >
  <?php
    $jobs = $this->dataJobs;
  $hhhash = $this->hhhash;
  if(count($jobs)>0){
  foreach ($jobs as $value){
  ?>
  <div class="green" style="overflow:hidden !important;">
    <div id="refair-result" style="text-align:left;">
      <div style="display:block;overflow:hidden;">
	<p><div class="green" id="jobtitle" style="float:left;width:70%"><h3><?php echo $value->jobtitle ; ?></div></h3></p>
	<h2 style="float:right;width:22%"><a href="<?php echo base_url('Refair/describejob/'.$this->hhhash.'/'.$value->id); ?>">View </a></h2>
      </div>
      <br/>
      <div class="boxes">Location:<b> <div class="green" id="location"><?php echo $value->location; ?></div></b></div>
      <div class="boxes">Experience required: <b><div class="green"  id="required_exp"><?php echo $value->required_exp; ?></div></b></div>
      <div class="boxes">Salary offered: <b><div  class="green" id="required_fund"><?php echo $value->required_fund; ?></div></b></div>
      <div class="boxes">Relocation: <b><div  class="green" id="required_relocation"><?php echo $value->required_relocation; ?></div></b></div>
      <div class="boxes">Remote: <b><div  class="green" id="required_remote"><?php echo $value->required_remote; ?></div></b></div>
      <div class="boxes">Keywords: <b><div  class="green" id="keywords"><?php echo $value->keywords; ?></div></b></div>
      <div class="boxes">Registered: <b><div  class="green"  id="regdate"><?php echo $value->regdate; ?></div></b></div>
      <div class="boxes">Description:<b> <div class="green" id="description"><?php echo $value->description; ?></div></b></div>
    </div>
    <form style="float:right;margin-right:5%">
      <div  style="float:left;margin-left:14px;">      
        <p name="<?php echo $value->id.'~'.$value->jobtitle.'~'.$value->keywords.'~'.$value->location.'~'.$this->emailes; ?>"  class="btn btn-raised active refair-call" data-target="#refer-body" data-toggle="modal">Refer someone<div class="ripple-container"></div></p>
      </div>
      <div  style="float:left;margin-left:14px;">      
	<p name="<?php echo $value->id.'~'.$value->jobtitle.'~'.$value->keywords.'~'.$value->location.'~'.$this->emailes; ?>" class="btn btn-raised active apply-call" data-target="#apply-body" data-toggle="modal">Apply<div class="ripple-container"></div></p>
      </div>
      <?php      if($this->userAble){ ?>
      <div  style="float:left;margin-left:14px;">      
	<p name="<?php echo $value->id.'~'.$value->poster_id.'~'.$value->jobtitle.'~'.$value->keywords.'~'.$value->location.'~'.$this->emailes; ?>" class="btn btn-raised active deletejob-call" data-target="#delete-jobss" data-toggle="modal">Delete<div class="ripple-container"></div></p>
      </div>
      <?php      } ?>
      <div class="hidden" id="abbrev"><?php echo $value->id.'~'.$value->jobtitle.'~'.$value->keywords.'~'.$value->location; ?></div>
    </form>
  </div>
  <br/>
  <div style="width:40%;float:right">
    <div id="resp"></div>
    {refair-describeperk}
  </div>

  <?php
    } }   
    ?>
</div>
