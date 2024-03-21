<div id="locadd-body" class="modal fade " role="document" >
  <div class="modal-content ">
    <div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title" id="myModalLabel">Add location Modal</h4>
    </div>
    <div class="modal-body" style="display:block;">
      <form id="form-locadd" method="post" accept-charset="utf-8" class="centered form-group" style="width:100%";>
	<h2> Add a new location</h2>
	<div class="form-group label-floating is-empty col-md-4"  style="padding:3px !important">
	  <label for="loc_name" class="control-label">Name of location</label>
	  <input class="form-control place_locname" type="text" value="">
	  <span class="help-block"></span>
	</div>
	<div class="form-group label-floating is-empty col-md-4"  style="padding:3px !important">
	  <label for="loc_city" class="control-label">City of location</label>
	  <input class="form-control place_loccity" type="text" >
	  <span class="help-block"></span>
	</div>
	<div class="form-group label-floating is-empty col-md-4"  style="padding:3px !important">
	  <label for="loc_country" class="control-label">Country of location</label>
	  <input class="form-control place_loccountry" type="text" >
	  <span class="help-block"></span>
	</div>
	<div class="form-group label-floating is-empty col-md-4"  style="padding:3px !important">
	  <label for="loc_address" class="control-label">Address of location</label>
	  <input class="form-control place_locaddress" type="text" value="">
	  <span class="help-block"></span>
	</div>
	<div class="form-group label-floating is-empty col-md-4"  style="padding:3px !important">
	  <label for="loc_zip" class="control-label">Zip of location</label>
	  <input class="form-control place_loczip"  type="text" value="">
	  <span class="help-block"></span>
	</div>
	<div class="form-group label-floating is-empty col-md-4"  style="padding:3px !important">
	  <label for="loc_lat" class="control-label">Lat of location</label>
	  <input class="form-control place_loclat" type="text" value="">
	  <span class="help-block"></span>
	</div>
	<div class="form-group label-floating is-empty col-md-4"  style="padding:3px !important">
	  <label for="loc_lon" class="control-label">Lon of location</label>
	  <input class="form-control place_loclon" type="text" value="">
	  <span class="help-block"></span>
	</div>
	<div class="form-group col-md-8"  style="padding:3px !important">
	  <label for="loc_desc" class="control-label">Desc of location</label>
	  <textarea class="form-control place_locdesc" rows=10 ></textarea>
	  <span class="help-block"></span>
	</div>
	<input class="form-control place_locuid" type="hidden" value="<?php echo $this->emailes; ?>"></input> 
	<input class="form-control place_lochash" type="hidden" value="hawking"></input> 
	<input class="form-control place_locid" type="hidden" value="hawking"></input> 
	<div id="locadd" style="float:left;margin-left:14px;">      
	  <p class="locadd-final btn ">SAVE LOCATION<div class="ripple-container"></div></p>
	</div>
	<div class="hidden" id="abbrev"></div>
      </form>
      <div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



  
