<div id="deleteperk-body" class="modal fade " role="document" >
  <div class="modal-content">
    <div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title" id="myModalLabel">Delete your perk</h4>
    </div>
    <div class="modal-body">
      <form id="form-delperk" method="post" accept-charset="utf-8" class="centered form-group" style="width:100%;">
	<div class="form-group label-floating is-empty "  style="padding:3px !important">
	  <label class="control-label">Are you sure about removing your perk? It will be gone forever!
	    <div id="perkname-del" class="perkname-del"></div>
	    <input type="hidden" class="hidden placeholder-for-perkuid"></input>
	    <input type="hidden" class="hidden placeholder-for-perkid"></input>
	  </label>
	  <span class="help-block"></span>
	</div>
	<div id="deletediv-perk" name="deletediv-perk">
	  <p id="deleteperk-final" name="deleteperk-final" class="btn btn-raised active deleteperk-final"><a>Delete<div class="ripple-container"></div></a></p>
	</div>
      </form>
      <div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
 
