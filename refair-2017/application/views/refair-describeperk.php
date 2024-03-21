<div class="content-describeperk">
  <?php if ( $this->userAble == true ){ ?>
  <div id="perk-add-div" class="centered" style="overflow:hidden !important;align:0 auto;margin:10px;padding:10px;">
    <form id="perkbut-add-form" method="post" accept-charset="utf-8" class="centered form-group">
      <div class="refair-result content-main" style="text-align:left;">
	<h2>Add a perk to this job:</h2>
	<div class="form-group label-floating is-empty green input-group-addon"  style="padding:3px !important">
	  <label for="new-perk-name" class="control-label">Name your perk <u>iff</u> succesfull hire</label>
	  <input class="form-control prismbar" name="new-perk-name" id="new-perk-name" type="text" value="">
	  <p class="help-block">For example, <i>Bounty of 3%</i> or a fixed <i>1000 $</i></p>
	</div><br/>
	<div class="form-group label-floating is-empty green input-group-addon"  style="padding:3px !important">
	  <p class="help-block">Who gets the perk?</p>
	  <label for="new-perk-for-referring" class="control-label">The person that referred your hire:</label>
	  <input class="btn-group-justified"  name="new-perk-for-referring" id="new-perk-for-referring" type="checkbox" aria-label="new-perk-for-reffering"></input>
	  <label for="new-perk-for-hire" class="control-label">Your hire:</label>
	  <input class="btn-group-justified" name="new-perk-for-hire" id="new-perk-for-hire" type="checkbox" aria-label="new-perk-for-hire"></input>
	  <input class="hidden" name="new-perk-jobid" id="new-perk-jobid" type="hidden" value="<?php echo $this->jobid; ?>" aria-label="new-perk-jobid"></input>
	  <input class="hidden" name="new-perk-uid" id="new-perk-uid" type="hidden" value="<?php echo $this->uid; ?>" aria-label="new-perk-uid"></input>
	</div>
      </div><br/>
      <div style="float:left;margin-left:14px;" class="centered">      
	<p id="perkbut-add" class="btn btn-raised active">Add New Perk<div class="ripple-container"></div></p>
      </div>
      <div class="hidden" id="abbrev"></div>
    </form>
  </div>
  <?php } ?>
  <?php
    $perks = $this->dataPerks; //Expect that value->id is visible from view describing job
  if( (count($perks)>=1) ){
  ?>
  <div class="content-main"><h2>The perks you give for this job</h2>
    <?php
      foreach($perks as $value){
      ?>
    <div class="green" style="overflow:hidden !important;align:0 auto;;display:block;float:left;">
      <div id="perk-result" style="text-align:left;">
	<p>Name:<b> <div class="green" id="perk-jobid" ><?php echo $value->name ; ?></div></b></div>
      <p><huge>Agreed:</huge><b> <div class="green" id="perk-status" ><?php echo $value->agreed_employer.'~'.$value->agreed_employee.'~'.$value->agreed_referee ; ?></div></b></p>
      <p><huge>Target:</huge><b> <div class="green"  id="perk-target" ><?php echo $value->target ; ?></div></b></p>
      <br/>
      <form >
	<div style="float:left;margin-left:14px;">      
	  <p id="perkbut-agree" name="<?php echo $value->id.'~'.$value->uid.'~'.$value->jobid; ?>"  class="btn btn-raised active butdialog" data-target="#refer" data-toggle="modal">Disagree<div class="ripple-container"></div></p>
	</div>
	<div style="float:left;margin-left:14px;">      
	  <p id="perkbut-disagree"  name="<?php echo $value->id.'~'.$value->uid.'~'.$value->jobid; ?>" class="btn btn-raised active butdialog" data-target="#apply" data-toggle="modal">Agree<div class="ripple-container"></div></p>
	</div>
	<div style="float:left;margin-left:14px;">      
	  <p name="<?php echo $value->id.'~'.$value->uid.'~'.$value->jobid; ?>" class="btn btn-raised active deleteperk-call" data-target="#deleteperk-body" data-toggle="modal">Delete<div class="ripple-container"></div></p>
	</div>
	<div class="hidden" id="abbrev"><?php echo $value->id.'~'.$value->uid.'~'.$value->jobid.'~'.$value->hash; ?></div>
      </form>
    </div>
    <?php }} ?>
    </div>

