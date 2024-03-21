<div id="apply-body" class="modal fade " role="document" >
  <div class="modal-content">
    <div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title" id="myModalLabel">Apply Modal</h4>
    </div>
    <div class="modal-body">
      <form id="form-apply" method="post" accept-charset="utf-8" class="centered form-group" style="width:100%;">
	<div class="form-group label-floating is-empty "  style="padding:3px !important">
	  <label for="role" class="control-label">Job Role</label>
	  <input class="form-control prismbar placeholder-for-role-apply" name="apply-role" type="text" value="">
	  <span class="help-block"></span>
	</div>
	<div class="form-group label-floating is-empty "  style="padding:3px !important">
	  <input class="hidden placeholder-for-email-apply" name="apply-email" id="apply-email" type="hidden" value="<?php echo $this->emailes; ?>">
	  <input class="hidden placeholder-for-location-apply" name="apply-location" >
	  <input class="hidden placeholder-for-jobid-apply" name="apply-jobid" id="apply-jobid" type="hidden" >
	  <span class="help-block"></span>
	</div>
	<div class="form-group label-floating is-empty "  style="padding:3px !important">
	  <label for="role" class="control-label">Skills as comma separated keywords</label>
	  <input class="form-control prismbar placeholder-for-keywords-apply" name="apply-keywords" type="text" ></input>
	  <span class="help-block"></span>
	</div>
	<div id="applydiv" name="applydiv">
	  <p id="apply-final"  class="btn btn-raised active apply-final"><a>Apply<div class="ripple-container"></div></a></p>
	</div>
      </form>
      <div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
