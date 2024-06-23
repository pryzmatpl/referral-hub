<div id="deletelocation-body" class="modal fade " role="document" >
  <div class="modal-content">
    <div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title" id="myModalLabel">Delete a location</h4>
    </div>
    <div class="modal-body">
      <form id="form-delloc" method="post" accept-charset="utf-8" class="centered form-group" style="width:100%;">
	<div class="form-group label-floating is-empty "  style="padding:3px !important">
	  <label class="control-label">Are you sure to remove location <div id="location-delname"></div>
	    <input type="hidden" class="hidden placeholder-for-locid" val=""></input>
	    <input type="hidden" class="hidden placeholder-for-lochash" value=""></input>
	  </label>
	</div>
	<div id="deletediv-location" name="deletediv-location">
	  <p id="deleteloc-final" name="delete-location" class="btn btn-raised active delete-location"><a>Delete<div class="ripple-container"></div></a></p>
	</div>
      </form>
      <div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
