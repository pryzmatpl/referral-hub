  <div class="silver" style="height:650px; width:350px;float:left;margin:15px;display:block;padding:15px;">
    <div id="refair-result" style="text-align:left;overflow:hidden;display:block;width:100% !important;">
      <div style="boxes">
        <p><div class="silver" id="jobtitle" style="padding:13px;width:100%"><h3><?php echo $value->jobtitle ; ?></div></h3></p>
        <h2 style="margin-top:15px !important;"><a href="<?php echo base_url('Refair/describejob/'.$this->hhhash.'/'.$value->id); ?>">View </a></h2>
        <div class="boxes">Registered: <?php echo $value->regdate; ?></div>
      </div>
      <div class="boxes">Location:<b> <div class="green" id="location"><?php echo $value->location; ?></div></b></div>
      <div class="boxes">Experience required: <b><div class="green"  id="required_exp"><?php echo $value->required_exp; ?></div></b></div>
      <br/>
      <div class="boxes" >
        <div class="" style="float:left;">Relocation: <b><div  class="green" id="required_relocation"><?php echo $value->required_relocation; ?></div></b></div>
        <div class="" style="float:right;">Remote: <b><div  class="green" id="required_remote"><?php echo $value->required_remote; ?></div></b></div>
      </div>
      <div class="boxes">Keywords: <b><div  class="green" id="keywords"><?php echo shortString($value->keywords,23); ?></div></b></div>
    </div></br>
    <form style="width:100%;margin-bottom:5px;padding-bottom:5px;padding-right:5px;display:block;overflow:hidden;">
      <div  style="float:right;margin-left:14px;">      
        <p name="<?php echo $value->id.'~'.$value->jobtitle.'~'.$value->keywords.'~'.$value->location.'~'.$this->emailes; ?>"  class="btn btn-raised active refair-call" style="width:90%;" data-target="#refer-body" data-toggle="modal">Refer someone<div class="ripple-container"></div></p>
      </div>
      <div  style="float:right;margin-left:14px;">      
        <p name="<?php echo $value->id.'~'.$value->jobtitle.'~'.$value->keywords.'~'.$value->location.'~'.$this->emailes; ?>" class="btn btn-raised active apply-call" data-target="#apply-body" data-toggle="modal">Apply<div class="ripple-container"></div></p>
      </div>
      <?php      if($this->userAble){ ?>
      <div  style="float:right;margin-left:14px;">      
	<p name="<?php echo $value->id.'~'.$value->poster_id.'~'.$value->jobtitle.'~'.$value->keywords.'~'.$value->location.'~'.$this->emailes; ?>" class="btn btn-raised active deletejob-call" data-target="#delete-jobss" data-toggle="modal">Delete<div class="ripple-container"></div></p>
      </div>
      <?php      } ?>
      <div class="hidden" id="abbrev"><?php echo $value->id.'~'.$value->jobtitle.'~'.$value->keywords.'~'.$value->location; ?></div>
    </form>
  </div>
