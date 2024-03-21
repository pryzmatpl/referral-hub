<div id="refair-location" class="content-main centered" style="overflow:hidden;padding:19px;">
  <h1 class="jumbotron">Location of referral:</h1>
  <?php  $value = $this->jobRefLocation; ?>
  <div class="green" style="overflow:hidden !important;">
    <div id="refair-result" style="text-align:left;">
      <h2 style="float:right;padding-bottom:10px !important;">
	<a href=<?php echo base_url("Refair/describelocation/".$this->hhhash."/".$value->id); ?> >View </a>
      </h2>
        <h2><?php echo $value->name ; ?></h2><br/>
<div class="boxes">City:<b> <div class="green" id="loc_city"><?php echo $value->city; ?></div></b></div>
<div class="boxes">Country: <b><div class="green"  id="loc_Country"><?php echo $value->country; ?></div></b></div>
<div class="boxes">Addres: <b><div  class="green" id="loc_address"><?php echo $value->address; ?></div></b></div>
<div class="boxes">Zip: <b><div  class="green" id="loc_zip"><?php echo $value->zip; ?></div></b></div>
<div class="boxes">Lat: <b><div  class="green" id="loc_lat"><?php echo $value->lat; ?></div></b></div>
<div class="boxes">Lon: <b><div  class="green" id="loc_lon"><?php echo $value->lng; ?></div></b></div>
<div class="boxes">Registered: <b><div  class="green"  id="loc_regdate"><?php echo $value->regdate; ?></div></b></div>
<div class="boxes">Description:<b> <div class="green" id="loc_desc"><?php echo $value->description; ?></div></b></div>
<?php if($this->userAble){ ?>
<form>
  <div id="edi-but" style="float:left;margin-left:14px;">      
    <p  name="<?php echo $value->id.'~'.$value->name.'~'.$value->city.'~'.$value->country.'~'.$value->address.'~'.$value->zip.'~'.$value->lat.'~'.$value->lng.'~'.$value->regdate.'~'.$value->description; ?> " class="btn btn-raised active butdialog" data-target="#addlocation" data-toggle="modal">Show and edit location<div class="ripple-container"></div></p></div>
  <div id="deli-but" style="float:left;margin-left:14px;">      
    <p name="<?php echo $value->id.'~'.$value->name.'~'.$value->city.'~'.$value->country.'~'.$value->address.'~'.$value->zip.'~'.$value->lat.'~'.$value->lng.'~'.$value->regdate.'~'.$value->description.'~'.$value->hash; ?> " class="btn btn-raised active deletelocation-call" data-target="#deletelocation-body" data-toggle="modal">Delete location<div class="ripple-container"></div></p></div>
  <div class="hidden" id="abbrev"><?php echo $value->id; ?></div>
</form>
</div>
</div>
<?php } ?>
</div>

