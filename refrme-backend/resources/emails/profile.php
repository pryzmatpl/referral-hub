<center>
  <table width="700" border="0" cellpadding="0" cellspacing="0" style="border-collapse :collapse; color: #787878; font-size: 20px; font-family: Optima, Arial, sans-serif; margin: 0 auto; border-spacing: 0;">
    <tr>
      <td colspan="2" style="font-family: sans-serif; font-size: 14px; vertical-align: top; border-radius: 5px; text-align: center;">
	<h2 style="padding:20px;">We do research on Wrocław's local IT market. You then get an overview of the situation. </h2>
      </td>
    </tr>
    <tr>
      <td colspan="2" style="font-family: sans-serif; font-size: 14px; vertical-align: top; border-radius: 5px; text-align: center;">
	<p style="padding:20px;"><u><a href="https://refair.me">Job updates right to your inbox. All the new trends for developing your professional career. </a></u></p>
      </td>
    </tr>
    <tr>
      <td colspan="2" style="font-family: sans-serif; font-size: 14px; vertical-align: top; border-radius: 5px; text-align: center; background-color:#a5d3ff;">
	<h2 style="padding:20px;"><i>Here are some key points about Wrocław's job market</i></h2>
      </td>
    </tr>
    <tr><td><img width="350" src="https://backend.refair.me/uploads/mail22.png" style="display: block;"></td><td style="background-color:#F0F8FF;padding:20px;"><p>We'll keep you updated on your local IT trends so you know what skills are in demand.</p></td></tr>
    <tr><td style="padding:20px;background-color:#F0F8FF;"><p>Check how your salary ranks amongst your peers.</p></td><td><img width="350" src="https://backend.refair.me/uploads/mail33.png" style="display: block;"></td></tr>
    <tr><td><img width="350" src="https://backend.refair.me/uploads/mail44.png" style="display: block;"></td><td style="padding:20px;background-color:#F0F8FF;"><p>Should I stay or should I go? We analyzed over 2500 local developers. See if they got promoted or moved to a new job.</p></td></tr>
    <tr>
      <td colspan="2" style="font-family: sans-serif; font-size: 14px; vertical-align: top; border-radius: 5px; text-align: center;">
	<h1 style="padding:20px;"><b><a href="https://refair.me">Never miss a job opportunity. Your skills and profile will automatically match you to interesting job offers.</a></b></h1>
      </td>
    </tr>
    <tr>
      <td colspan="2" style="padding: 0 !important;">
        <table width="100%" style="border-collapse: collapse; color: #787878; font-size: 18px;">
          <?php
           use App\Models\JobDesc;
           foreach ($matched_jobs as $category => $jobs) {
          foreach ($jobs as $j) {
          $job = JobDesc::with('company')->where('id', '=', $j['id'])->first();
	  ?>
          <tr style="background-color: #fff; cursor: pointer; border-bottom: 5px solid #dae3f3;">
            <td style="padding: 20px;">
              <a href="https://refair.me/job/<?php echo $job->id; ?>" style="text-decoration: none; color: inherit !important;">
                <div>
                  <div style="float: right; color: #000000; margin-left: 20px;">
                    <?php echo $job->salary_min; ?> - <?php echo $job->salary_max; ?> <?php //echo $job->currency; ?>PLN
                  </div>
                  <img width="120px" src="<?php echo $job->company->logo; ?>" style="float: left; margin-right: 20px; max-height: 50px;">
                  <div style="color: #338ba5; font-weight: bold; margin-bottom: 8px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo $job->title; ?></div>
                  <div style="margin-bottom: 15px;"><?php echo $job->company->name; ?></div>
                </div>
                <hr style="margin-bottom: 20px; border: 0; border-top: 1px solid rgba(0,0,0,.1);">
                <div class="font-size: 16px;">
                  <div style="margin-bottom: 15px;">
                    <span style="color: #000000; font-size: 16px;">Tech:</span>
                    <?php foreach ($job->skills as $skill) { ?>
                    <span style="background-color: #4a90e2; color: #fff; padding: 3px 8px; margin: 1px; font-size: 15px; border-radius: 30px;"><?php echo $skill['name']; ?></span>
                    <?php } ?>
                  </div>
                  <div style="margin-bottom: 15px;">
                    <span style="color: #000000; font-size: 16px;">Location:</span> <?php echo $job->location; ?>
                  </div>
                </div>
              </a>
            </td>
          </tr>
          <?php
           }
           }
           ?>
        </table>
      </td>
    </tr>
  </table>
</center>
<?php include('template_footer.php'); ?>
