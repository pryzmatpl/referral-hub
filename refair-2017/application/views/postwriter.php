<?php
  $email = '';
  $message = '';
  ?>
<br/>
<center>
  <br/>
  <div class="jumbotron content-main backdropped" style="text-align:justify;">
    <h2>The honest content creation service</h2>
    <p>Postwriter is an on-demand content creation service for tech companies and start-ups. Build an audience with quality content based on your true voice. 
    </p><p>
      You can order: <br/>
      -cold email campaigns  <br/>
      -newsletters  <br/>
      -blog posts  <br/>
      -news posts  <br/>
    </p><p>Leave a message below to receive a quote. </p>
  </div>
  <div class="jumbotron green content-main ">
    <?php echo form_open("/Postwriter/sendmailtopostwriter/");?>

    <p>
      <?php echo("Your email address:"); ?><br/>
      <?php echo form_input($email,"Email",array("id"=>"email_name","class"=>"email_name","name"=>"email_name")); ?>
    </p>

    <p>
      <?php echo "Your message to PostWriter:" ?><br/>
      <?php echo form_textarea($message,"Your Message",array("id"=>"message","class"=>"message","name"=>"message"));?>
    </p>
    <p id="messgPostwriter"  class="btn btn-raised active">Message Postwriter<div class="ripple-container"></div></a></p></div>
<?php echo form_close();?>
<div class="mesgresp" id="mesgresp" name="mesgresp"></div>
</div>


</div>
<div class="jumbotron content-main" style="text-align:justify;">
  <p>
    Sneak peek at other stuff I do :<br/>
    - <a href="https://soundcloud.com/user-77272601/difficulties-of-running-a-digital-agency-in-a-highly-competitive-market"> podcast that I made </a>(because why not)<br/>
    - <a href="https://www.facebook.com/notes/slavingway/content-is-everything-communication-as-sharing-content-and-the-key-to-influencin/709295812562040"> article that I wrote</a><br/>
    - <a href="https://www.quora.com/My-first-startup-seems-to-have-failed-now-what">quora answer that I wrote </a><br/>
    -  <a href="http://sweetfishmedia.com/quality-content-marketing-michal-slupski/">(bonus) podcast I was in </a>
  </p>
  
</div>
</center>

