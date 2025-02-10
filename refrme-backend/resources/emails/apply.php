<?php include('template_header.php'); ?>
<img src="https://backend.refair.me/uploads/refairme.png" height="50" style="margin-bottom: 15px;">
<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
    Hello, <?php echo $_SESSION['user']->email; ?> and <?php echo $job->user->email; ?>.
</p>
<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
    This email confirms application of <?php echo $_SESSION['user']->email; ?> for
    <a href="<?php echo env('FRONTEND_URL'); ?>job/<?php echo $job->id; ?>">job <?php echo $job->id; ?>, <?php echo $job->title; ?></a>
</p>
<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
    Please continue your recrutiment process via email!
</p>
<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
    Have a good day!
</p>
<?php include('template_footer.php'); ?>