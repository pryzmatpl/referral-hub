<div id="refair-userprofile" class="white content-main centered" style="overflow:hidden !important;" >
  <?php
    $jobs = $this->dataJobs;
  $hhhash = $this->hhhash;
  if(count($jobs)>0){
  foreach ($jobs as $value){
  ?>
  <div style="overflow:hidden !important;float:left !important; width:50%; float:left;">
    <h1><?php echo $value->jobtitle ?></h1>
    <p>Registered: <h2><div id="regdate"><?php echo $value->regdate; ?></div></h2></p>
    <p>ID:<h2> <div class="" id="jobid" ><?php echo $value->id ; ?></div></h2></p>
    <p><huge>Role:</huge><h1> <div class="" id="jobtitle" ><?php echo $value->jobtitle ; ?></div></h1></p>
    <p>Location:<h2> <div class="" id="location"><?php echo $value->location; ?></div></h2></p>
    <p>Experience required: <h2><div class=""  id="required_exp"><?php echo $value->required_exp; ?></div></h2></p>
    <p>Salary offered: <h2><div  class="" id="required_fund"><?php echo $value->required_fund; ?></div></h2></p>
    <p>Relocation: <h2><div  class="" id="required_relocation"><?php echo $value->required_relocation; ?></div></h2></p>
    <p>Remote: <h2><div  class="" id="required_remote"><?php echo $value->required_remote; ?></div></h2></p>
    <p>Keywords: <h2><div  class="" id="keywords"><?php echo $value->keywords; ?></div></h2></p>
    <p>Description:<h3> <div style="width:500px" class="" id="description"><?php echo $value->description; ?></div></h3></p>
    <form style="float:left;" >
      <div id="refer-but" style="float:left;margin-left:14px;">      
	<p id="refer-but" name="<?php echo $value->id.'~'.$value->jobtitle.'~'.$value->keywords.'~'.$value->location; ?>"  class="btn btn-raised active butdialog" data-target="#refer" data-toggle="modal">Refer someone<div class="ripple-container"></div></p>
      </div>
      <div id="apply-but" style="float:left;margin-left:14px;">      
	<p id="apply-but"  name="<?php echo $value->id.'~'.$value->jobtitle.'~'.$value->keywords.'~'.$value->location; ?>" class="btn btn-raised active butdialog" data-target="#apply" data-toggle="modal">Apply<div class="ripple-container"></div></p>
      </div>
      <?php if($this->userAble == false){ ?>
      <div id="delete-but" style="float:left;margin-left:14px;">      
	<p id="delete-but"  name="<?php echo $value->id.'~'.$value->jobtitle.'~'.$value->keywords.'~'.$value->location; ?>" class="btn btn-raised active butdialog" data-target="#delete-jobss" data-toggle="modal">Delete<div class="ripple-container"></div></p>
      </div>
      <div id="jobdescedit-but" style="float:left;margin-left:14px;">      
	<p id="jobdescedit-but"  name="<?php echo $value->id.'~'.$value->jobtitle.'~'.$value->keywords.'~'.$value->location; ?>" class="btn btn-raised active butdialog" data-target="#edit-jobss" data-toggle="modal">Edit this Job<div class="ripple-container"></div></p>
      </div>
      <?php } ?>
      <div class="hidden" id="abbrev"><?php echo $value->id.'~'.$value->jobtitle.'~'.$value->keywords.'~'.$value->location; ?></div>
    </form>
    <br/>
    <br/>
  </div>
  <div style="width:40%;float:right">
    <div id="resp"></div>
  </div>

  <?php
    } }   
    ?>
</div>
