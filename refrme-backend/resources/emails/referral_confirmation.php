<?php include('template_header.php'); ?>
<p style="font-family: sans-serif; font-size: 20px; font-weight: bold; margin: 0; Margin-bottom: 20px; text-align:center;">Congratulations<br/><?php echo $user_email; ?></p>
<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 40px; text-align:center;">You refered <span style="color:#007bff;"><?php echo $email; ?></span> to <span style="color:#007bff;"><?php echo $company; ?></span> to work as <span style="color:#007bff;"><?php echo $job; ?></span></p>
<table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; box-sizing: border-box;">
    <tbody>
    <tr>
        <td align="center" style="font-family: sans-serif; font-size: 14px; vertical-align: top; padding-bottom: 40px;">
            <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;">
                <tbody>
                    <tr>
                        <td style="font-family: sans-serif; font-size: 14px; vertical-align: top; background-color: #3498db; border-radius: 5px; text-align: center;">
                            <a href="http://<?php echo env('FRONTEND_URL') ?>:8080" target="_blank" style="display: inline-block; color: #ffffff; background-color: #3498db; border: solid 1px #3498db; border-radius: 5px; box-sizing: border-box; cursor: pointer; text-decoration: none; font-size: 14px; font-weight: bold; margin: 0; padding: 12px 25px; text-transform: capitalize; border-color: #3498db;">
                                Visit us on Refair.me
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px; text-align:center;">Greetings from the Refair team.</p>
<?php include('template_footer.php'); ?>
