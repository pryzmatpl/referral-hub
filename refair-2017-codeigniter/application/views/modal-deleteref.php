<div id="deleteref-body" class="modal fade " role="document" >
  <div class="modal-content">
    <div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title" id="myModalLabel">Delete your referral</h4>
    </div>
    <div class="modal-body">
      <form id="form-delref" method="post" accept-charset="utf-8" class="centered form-group" style="width:100%;">
	<div class="form-group label-floating is-empty "  style="padding:3px !important">
	  <label class="control-label">Are you sure about removing your referral? It will be gone forever! <div class="delref-name"></div>
	    <span class="help-block"></span>
	    <input type="hidden" class="hidden placeholder-for-refid"></input>
	    <input type="hidden" class="hidden placeholder-for-refhash"></input>
	  </label>
	</div>
	<div id="deletediv-ref" name="deletediv-ref">
	  <p id="deleteref-final" name="deleteref-final" class="btn btn-raised active delete-location"><a>Delete<div class="ripple-container"></div></a></p>
	</div>
      </form>
      <div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

