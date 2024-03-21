<div id="refair-imports" class="white content-main centered">
  <h1>Your imported friends</h1>
  
  <?php
    if(count($this->paginatedResults)>0){
  foreach ($this->paginatedResults as $value){
  ?>
  <div class="green" style="overflow:hidden !important;">
    <div id="refair-result" style="text-align:left;">
      <div class="boxes">ID:<b> <div class="green" ><?php echo $value->id ; ?></div></b></div>
      <div class="boxes"><huge>First Name:</huge><b> <div class="green" ><?php echo $value->firstName ; ?></div></b></div>
      <div class="boxes">Last Name:<b> <div class="green" ><?php echo $value->lastName; ?></div></b></div>
      <div class="boxes">Company: <b><div class="green"  ><?php echo $value->company; ?></div></b></div>
      <div class="boxes">Title: <b><div  class="green" ><?php echo $value->title; ?></div></b></div>
      <div class="boxes">Email: <b><div  class="green" ><?php echo $value->email; ?></div></b></div>
      <div class="boxes">Phone: <b><div  class="green" ><?php echo $value->phone; ?></div></b></div>
      <div class="boxes">Notes: <b><div  class="green" ><?php echo $value->notes; ?></div></b></div>
      <div class="boxes">Tags: <b><div  class="green"  ><?php echo $value->tags; ?></div></b></div>
      <div class="boxes">Registered: <b><div  class="green"  ><?php echo $value->regdate; ?></div></b></div>
    </div>
    <form style="float:right;margin-right:5%">
      <div  style="float:left;margin-left:14px;">      
        <p name="<?php echo $value->id.'~'.$value->firstName.'~'.$value->lastName.'~'.$value->company.'~'.$value->email; ?>"  class="btn btn-raised active refair-call" data-target="#refer-body" data-toggle="modal">Invite your friend to refair.me<div class="ripple-container"></div></p>
      </div>
      <div  style="float:left;margin-left:14px;">      
	<p name="<?php echo $value->id.'~'.$value->firstName.'~'.$value->lastName.'~'.$value->company.'~'.$value->email; ?>" class="btn btn-raised active apply-call" data-target="#apply-body" data-toggle="modal">Refer this person to a job<div class="ripple-container"></div></p>
      </div>
      <div class="hidden" id="abbrev"><?php echo $value->id.'~'.$value->jobtitle.'~'.$value->keywords.'~'.$value->location; ?></div>
    </form>
  </div>
  <br/>
  <?php
    }    
    }?>

<div id="pagination">
<ul class="tsc_pagination">  <!-- Show pagination links -->
  <?php foreach ($this->pagLinks as $link) {
        foreach ($link as $finlink)
        	echo "<li>". $finlink."</li>";
  } ?>
</ul>
</div>
