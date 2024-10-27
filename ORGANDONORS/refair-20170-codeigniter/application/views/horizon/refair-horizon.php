<?php ?>
<div style="float:right; width:63%;">
  <h2>Add your Linked In in order to invite your friends </h2>
    <form method="post" action="" id="refairer_import">
        <input type="hidden" name="abbrev" id="abbrev" value="<?php echo $this->hhhash; ?>" />
        <input type="hidden" name="filename-monit" id="filename-monit" />
        <input type="file" name="fileli" id="fileli" size="20" onclick="$("#filename-monit").val( $this.val() );"/>
 
        <input type="submit" name="upload" id="upload" value="Upload"/>
    </form>
    <div id="files"></div>
</div>
