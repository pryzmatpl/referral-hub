<div id="refer-body" class="modal fade" role="document">
  <div class="modal-content">
    <div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
	<span aria-hidden="true">&times;</span>
      </button>
      <h4 class="modal-title" id="modal-refer">Refer someone for job
	<div class="placeholder-for-jobid-ref"></div>
      </h4>
    </div>
    <div class="modal-body ">
      <form id="form-refer" method="post" accept-charset="utf-8" class="centered form-group" style="width:100%;">
	<div class="form-group label-floating is-empty "  style="padding:3px !important">
	  <label for="role-refer" class="control-label">Job Role
	  </label>
	  <input class="form-control prismbar placeholder-for-role-ref" name="role-refer" id="role-refer" type="text">
	  <span class="help-block">
	  </span>
	</div>
	<div class="form-group label-floating is-empty "  style="padding:3px !important">
	  <label for="referred-email" class="control-label">Refer a person for this job (email):
	  </label>
	  <input class="form-control prismbar placeholder-for-referred-mail-ref" name="referred-email" id="referred-email" type="text" value="">
	  <span class="help-block">
	  </span>
	</div>
	<span class="help-block">
	</span>
	<div class="form-group label-floating is-empty "  style="padding:3px !important">
	  <label for="referrer-location" class="control-label">Location
	  </label>
	  <input class="form-control prismbar placeholder-for-location-ref" name="referrer-location" id="referrer-location" type="text" value="">
	  <span class="help-block">
	  </span>
	</div>
	<div class="form-group label-floating is-empty "  style="padding:3px !important">
	  <label for="keywords" class="control-label">Job Role keywords
	  </label>
	  <input class="form-control prismbar placeholder-for-keywords-ref" name="keywords" id="keywords" type="text"></input>
	  <span class="help-block">
	    ex.: <strong>developer,c++,qt,opencv</strong>
	  </span>
	</div>
	<input class="hidden placeholder-for-referrer-mail-ref" type="hidden"></input>
	<input class="hidden placeholder-for-referred-mail-ref" type="hidden"></input>
	<div id="referrerdiv" name="referrerdiv">
	  <p id="referrer-refer" name="referrer-refer" class="btn btn-raised active refair-final ">Refair
	    <div class="ripple-container"></div>
	  </p>
	</div>
	<input class="hidden" name="referrer-email" id="referrer-email" type="hidden" value="<?php echo $this->emailes; ?>"></input>
	<input class="hidden" name="jobid" id="jobid" type="text/hidden" value="" ></input>
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>

