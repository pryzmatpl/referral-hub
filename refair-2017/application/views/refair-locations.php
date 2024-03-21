<div class="col-md-12" id="resp-loc"></div>

<div id="refair-locations" class="content-main col-md-12 row">
  <h1 class="jumbotron col-md-12">Your locations:</h1>
  <?php if($this->userAble){ ?>
  <form class="col-md-12">
    <div class="addlocation-call-div col-md-12">
      <p name="locadd-new"  class="btn locadd-call"
	 data-target="#locadd-body"
	 data-toggle="modal">Click here to add location
	<div class="ripple-container"></div>
      </p>
    </div>
  </form>
  <?php } 
	$jobs = $this->dataLocations;
  if(count($jobs)>0){
      foreach ($jobs as $value){
  ?>

  <div class="col-md-12 row content-main" style="border:1px solid #feaece;padding:20px;margin:10px;background:#ffccee;">
    <div class="col-md-12 jumbotron">
      <a class="active" style="font-size:24px;"
	 href="<?php echo base_url("Refair/describelocation/".$this->hhhash."/".$value->id); ?>" >
      <?php echo $value->name ; ?>
</a>
</div>

<div class="col-md-12 row">
  <div class="col-md-4">City:<b> <div class="loc_city"><?php echo $value->city; ?></div></b></div>
  <div class="col-md-4">Country: <b><div class="loc_country"><?php echo $value->country; ?></div></b></div>
  <div class="col-md-4">Address: <b><div  class="loc_address"><?php echo $value->address; ?></div></b></div>
</div>

<div class="col-md-12 row">
  <div class="col-md-4">Zip: <b><div class="loc_zip"><?php echo $value->zip; ?></div></b></div>
  <div class="col-md-4">Lat: <b><div class="loc_lat"><?php echo $value->lat; ?></div></b></div>
  <div class="col-md-4">Lon: <b><div class="loc_lon"><?php echo $value->lng; ?></div></b></div>
</div>

<div class="col-md-12 row">
  <div class="col-md-4">Registered: <b><div  class="loc_regdate"><?php echo $value->regdate; ?>
    </div></b>
  </div>
  <div class="col-md-8">Description:<b>
      <div class="loc_desc"><?php echo urldecode($value->description); ?>
    </div></b>
  </div>
</div>

<?php if($this->userAble){ ?>
<form class="col-md-12" style="margin-top:20px;">
  <div class="col-md-7">
    <p  name="<?php echo $value->id.'~'.$value->name.'~'.$value->city.'~'.$value->country.'~'.$value->address.'~'.$value->zip.'~'.$value->lat.'~'.$value->lng.'~'.$value->regdate.'~'.$value->description; ?> " class="btn locadd-call" data-target="#locadd-body" data-toggle="modal">
      Show and edit location
      <div class="ripple-container"></div>
    </p>
  </div>
  <div style="col-md-6">
    <p name="<?php echo $value->id.'~'.$value->name.'~'.$value->city.'~'.$value->country.'~'.$value->address.'~'.$value->zip.'~'.$value->lat.'~'.$value->lng.'~'.$value->regdate.'~'.$value->description.'~'.$value->hash; ?> " class="btn deletelocation-call" data-target="#deletelocation-body" data-toggle="modal">
      Delete location
      <div class="ripple-container"></div>
    </p>
  </div>
  <div class="hidden" id="abbrev"><?php echo $value->id; ?></div>
</form>
<?php } ?>
</div>
<br/>
<?php }} ?>
</div>



