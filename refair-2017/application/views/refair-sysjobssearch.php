<div id="refair-content" class="white content-main centered" >
  <h1>Your posted jobs</h1>
  <?php
    $jobs = $this->dataJobs;
    if(count($jobs)>0){
    foreach ($jobs as $value){
  ?>
  <div class="green" style="overflow:hidden !important;">
    <div id="refair-result" style="float:left;text-align:left;">
      <p>ID:<b> <div id="jobid" ><?php echo $value->id ; ?></div></b></p>
      <p>Role:<b> <div id="jobtitle" ><?php echo $value->jobtitle ; ?></div></b></p>
      <p>Location:<b> <div id="location"><?php echo $value->location; ?></div></b></p>
      <p>Experience required: <b><div id="required_exp"><?php echo $value->required_exp; ?></div></b></p>
      <p>Salary offered: <b><div id="required_fund"><?php echo $value->required_fund; ?></div></b></p>
      <p>Relocation: <b><div id="required_relocation"><?php echo $value->required_relocation; ?></div></b></p>
      <p>Remote: <b><div id="required_remote"><?php echo $value->required_remote; ?></div></b></p>
      <p>Keywords: <b><div id="keywords"><?php echo $value->keywords; ?></div></b></p>
      <p>Registered: <b><div id="regdate"><?php echo $value->regdate; ?></div></b></p>
    </div>
    <form>
      <div id="refer-but">      
	<p id="refer-but" name="<?php echo $value->id.'~'.$value->jobtitle.'~'.$value->keywords.'~'.$value->location; ?>"  class="btn btn-raised active butdialog" data-target="#refer" data-toggle="modal">Refer someone<div class="ripple-container"></div></p></div>
      <div id="apply-but">      
	<p id="apply-but"  name="<?php echo $value->id.'~'.$value->jobtitle.'~'.$value->keywords.'~'.$value->location; ?>" class="btn btn-raised active butdialog" data-target="#apply" data-toggle="modal">Apply<div class="ripple-container"></div></p></div>
      <div class="hidden" id="abbrev"><?php echo $value->id.'~'.$value->jobtitle.'~'.$value->keywords.'~'.$value->location; ?></div>
    </form>
    <p>Description:<b> <?php echo $value->description; ?></b></p>
  </div>
  
  <br/>
  <?php
    }    
    }?>
  
</div>
