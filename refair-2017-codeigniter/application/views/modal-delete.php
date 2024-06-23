<div id="delete-jobss" class="modal fade " role="document" >
  <div class="modal-content">
    <div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title" id="myModalLabel">Delete a Job</h4>
    </div>
    <div class="modal-body">
      <form id="form-deletejob" method="post" accept-charset="utf-8" class="centered form-group" style="width:100%;">
	<div class="form-group label-floating is-empty "  style="padding:3px !important">
	  <label for="role" class="control-label">Job ID</label>
	  <input class="form-control prismbar placeholder-for-delete-jobid" name="delete-jobname" id="delete-jobname" type="text" >
	  <span class="help-block"></span>
	</div>
	<div id="deletediv" name="deletediv">
	  <p name="deletejob-final" class="btn btn-raised active deletejob-final"><a>Delete<div class="ripple-container"></div></a></p>
	</div>
      </form>
      <div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
