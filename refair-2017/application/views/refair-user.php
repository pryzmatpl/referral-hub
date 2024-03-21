
<?php
  $jobdesc = array(
  'id'=>NULL,
'jobtitle'=>NULL,
'description'=>NULL,
'required_exp'=>NULL,
'required_fund'=>NULL,
'required_relocation'=>NULL,
'required_remote'=>NULL,
'regdate'=>NULL );
?>

<div class="content-main col-md-4" >
  <h1 class="jumbotron col-md-12">Add a Job</h1>
  <form id="form-addjob" method="post" accept-charset="utf-8" class="form-group col-md-12" style="background:#aaaacc;padding-top:20px;">
    <div class="form-group label-floating is-empty col-md-4 "  >
      <label for="jobtitle" class="control-label">Job Role</label>
      <input class="form-control place_jobname " name="addjob-jobtitle" id="addjob-jobtitle" type="text" placeholder="Lead Developer"></input>
      <span class="help-block"></span>
    </div>
    <div class="form-group label-floating is-empty col-md-8 "  >
      <label for="description" class="control-label">Please describe the position as briefly as possible:</label>
      <textarea class="form-control " name="addjob-description" id="addjob-description" type="multiline" value="" cols="40" rows="5"></textarea>
      <span class="help-block"></span>
    </div>
    <div class="form-group label-floating is-empty col-md-6">
      <label for="required_exp" class="control-label">Experience from 0 to 100</label>
      <p class="range-field">
	<input
	  id="place_jobrequired_exp"
	  type="text"
	  name="place_jobrequired_exp"
	  data-provide="slider"
	  data-slider-min="1"
	  data-slider-max="99"
	  data-slider-step="1"
	  data-slider-value="50"
	  data-slider-tooltip="show" style="width:100%"></input>
      </p>
    </div>
    <div  class="form-group label-floating is-empty col-md-6  ">
      <label for="required_fund">Salary level per annuum (thousands)</label>
      <p class="range-field">
	<input
	  id="place_jobrequired_fund"
	  type="text"
	  name="place_jobrequired_fund"
	  data-provide="slider"
	  data-slider-min="1"
	  data-slider-max="399"
	  data-slider-step="1"
	  data-slider-value="50"
	  data-slider-tooltip="show" style="width:100%"></input>
      </p>
    </div>
    <div class="form-group label-floating is-empty col-md-6  " >
      <label for="required_relocation" class="control-label">Requires relocation (0 = no, 1 = yes)</label>
      <input type="range" class="place_jobrequired_relocation" min="0" max="1" ></input>
    </div>
    <div class="form-group label-floating is-empty col-md-6"  >
      <label for="required_remote" class="control-label">Remote work available (0 = no, 1 = yes):</label>
      <input type="range" class="place_jobrequired_remote" min="0" max="1"></input>
      <span class="help-block"></span>
    </div>
    <div class="form-group label-floating is-empty col-md-12 "  >
      <label for="place_jobkeywords" class="control-label">Job skills keywords (Photoshop, C++, Design Thinking):</label>
      <input class="form-control place_jobkeywords" type="text" placeholder="c++,unix,networking"></input>
      <span class="help-block">ex.: <strong>developer,c++,qt,opencv</strong></span>
    </div>
    <div id="addlocation-call-div col-md-4" >
      Location:
      <select class="selectpicker jobselectpicker col-md-12" data-show-subtext="true" id="selecctlocation" data-live-search="true" >
	<?php foreach($this->dataLocations as $locations){
	echo "<option data-subtext=\"".$locations->id.",".$locations->address."\" value=\"".$locations->hash."\">".$locations->name."</option>";
	}
	?>
      </select>
    </div>
    <div id="addlocation-call-div col-md-6" >      
      <a  href="#refair-locations">Click here to add location </a>
    </div>
    <div id="addjobdiv" class="col-md-6" name="addjobdiv"><p id="addjob-call" name="addjob" class="btn addjob-call ">
	Add Job<div class="ripple-container"></div></p>
    </div>
    <input class="hidden place_jobposter_id" type="text/hidden" value="<?php echo $this->emailes; ?>"></input>
  </form>
</div>


<div id="userdash-userjobs" class="content-main col-md-4" >
  {refair-userjobs}
</div>
<div id="userdash-locations" class=" content-main col-md-4">
  {refair-locations}
</div>
<div id="userdash-referrals" class=" content-main col-md-4" >
  {refair-referrals}
</div>

