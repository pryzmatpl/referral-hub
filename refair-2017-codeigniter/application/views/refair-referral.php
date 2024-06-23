<?php
  function shortString($yourString,$maxsize){
          if (strlen($yourString) > $maxsize) // if you want...
          {
              $maxLength = $maxsize;
              $yourString = substr($yourString, 0, $maxLength).'...';
          }
          return $yourString;
      }

$jobs = $this->dataReferrals;
if(count($jobs)>0){
foreach ($jobs as $value){
?>
<div class="green" style="float:left;overflow:hidden !important; width:45% !important; margin:19px;height:650px;display:block;">
  <div id="refair-referral" style="text-align:left;">
    <h1><?php echo('<i>'.$value->name.'</i> at <u>'.$value->location_id.'</u>'); ?> - Your referral</h1>
    <h2>Registered <?php echo($value->regdate); ?></h2>
    <p>ID:<b> <div class="green" id="jobid" ><?php echo $value->id ; ?></div></b></p>
    <p>Person Referring the person referred:<b> <div class="green" id="jobtitle" ><?php echo $value->referrer_id ; ?></div></b></p>
    <p>Person Referred:<b> <div class="green" id="location"><?php echo $value->referred_id; ?></div></b></p>
    <p>Keywords: <b><div  class="green" id="keywords"><?php echo $value->keywords; ?></div></b></p>
    <p>Registered: <b><div  class="green"  id="regdate"><?php echo $value->regdate; ?></div></b></p>
  </div>
  <?php if($this->userAble){ ?>
  <form >
    <div id="delete-but-div" style="float:left;margin-left:14px;">      
      <p name="<?php echo $value->id.'~'.$value->hash; ?>" class="btn btn-raised active deleteref-call" data-target="#deleteref-body" data-toggle="modal">Delete<div class="ripple-container"></div></p>
    </div>
  </form>
  <?php } ?>
</div>
<?php  } } ?>

<?php if($this->userAble){ ?>
<div class="content-main green" style="width:45%;margin:19px;float:left;height:650px;display:block;">
  <div class="row">
    <div class="col-lg-8 col-lg-offset-2">
      <h1>Google maps & Bootstrap tutorial from <a href="http://bootstrapious.com">Bootstrapious.com</a></h1>
      <p class="lead">This is a demo for our tutorial showing you how to add a custom styled Google maps into a Bootstrap page.</p>
      <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo.</p>
      <p><strong>Pellentesque habitant morbi tristique</strong> senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. <em>Aenean ultricies mi vitae est.</em> Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, <code>commodo vitae</code>, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. <a href="#">Donec non enim</a> in turpis pulvinar facilisis. Ut felis.</p>
    </div>
  </div>
  <div id="map"></div>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBitQWDLqUmKD6XBC4kT_-FArmh7RsiNo"></script>
</div> 
<?php } ?>

  <?php
    $valueDesc = $this->jobDescs;
  ?>
<div class="content-main " style="float:left;overflow:hidden !important; padding:19px;width:49%;height:650px;display:block;">
  <div class="refair-jobdesc" style="text-align:left;">
    <p><div class="green" id="jobtitle" ><h3><?php echo $valueDesc->jobtitle ; ?></div></h3></p>
    <div class="boxes">Location:<b> <div class="green" id="location"><?php echo $valueDesc->location; ?></div></b></div>
    <div class="boxes">Experience required: <b><div class="green"  id="required_exp"><?php echo $valueDesc->required_exp; ?></div></b></div>
    <div class="boxes">Salary offered: <b><div  class="green" id="required_fund"><?php echo $valueDesc->required_fund; ?></div></b></div>
    <div class="boxes">Relocation: <b><div  class="green" id="required_relocation"><?php echo $valueDesc->required_relocation; ?></div></b></div>
    <div class="boxes">Remote: <b><div  class="green" id="required_remote"><?php echo $valueDesc->required_remote; ?></div></b></div>
    <div class="boxes">Keywords: <b><div  class="green" id="keywords"><?php echo shortString($valueDesc->keywords,23); ?></div></b></div>
    <div class="boxes">Registered: <b><div  class="green"  id="regdate"><?php echo $valueDesc->regdate; ?></div></b></div>
    <div class="boxes">Description:<b> <div class="green" id="description"><?php echo $valueDesc->description; ?></div></b></div>
  </div>
  <h2><a href="<?php echo base_url('Refair/describejob/'.$this->hhhash.'/'.$valueDesc->id); ?>">View </a></h2>
  <form style="float:right;margin-right:5%">
    <div id="refer-but" style="float:left;margin-left:14px;">      
      <p id="refer-but" name="<?php echo $valueDesc->id.'~'.$valueDesc->jobtitle.'~'.$valueDesc->keywords.'~'.$valueDesc->location; ?>"  class="btn btn-raised active butdialog" data-target="#refer" data-toggle="modal">Refer someone<div class="ripple-container"></div></p>
    </div>
    <div id="apply-but" style="float:left;margin-left:14px;">      
      <p id="apply-but"  name="<?php echo $valueDesc->id.'~'.$valueDesc->jobtitle.'~'.$valueDesc->keywords.'~'.$valueDesc->location; ?>" class="btn btn-raised active butdialog" data-target="#apply" data-toggle="modal">Apply<div class="ripple-container"></div></p>
    </div>
    <?php      if($this->userAble){ ?>
    <div id="delete-but" style="float:left;margin-left:14px;">      
      <p id="delete-but"  name="<?php echo $valueDesc->id.'~'.$valueDesc->jobtitle.'~'.$valueDesc->keywords.'~'.$valueDesc->location; ?>" class="btn btn-raised active butdialog" data-target="#delete-jobss" data-toggle="modal">Delete<div class="ripple-container"></div></p>
    </div>
    <?php      } ?>
    <div class="hidden" id="abbrev"><?php echo $valueDesc->id.'~'.$valueDesc->jobtitle.'~'.$valueDesc->keywords.'~'.$valueDesc->location; ?></div>
  </form>
</div>

<div id="userref-trackref" class=" content-main" style="overflow:hidden;width:48%;float:left;padding:16px;height:650px;display:block;"> 
  {refair-trackreferral}
</div>


<div class="content-main" style="float:left;overflow:hidden !important; margin:10px;width:51%;height:650px;display:block;">
  {refair-describeperk}
</div>

<div id="userref-locations" class=" content-main" style="overflow:hidden;width:48%;float:right;padding:16px;height:650px;display:block;"> 
  {refair-location}
</div>

