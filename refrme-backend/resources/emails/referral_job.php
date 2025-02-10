<?php include('template_header.php'); ?>
<p style="font-family: sans-serif; font-size: 20px; font-weight: bold; margin: 0; Margin-bottom: 20px; text-align:center;">Congratulations<br/><?php echo $email; ?></p>
<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 30px; text-align:center;">
    You received a new job proposal at <span style="color:#007bff;"><?php echo $company; ?></span> as <span style="color:#007bff;"><?php echo $job; ?></span>. <span style="color:#007bff;"><?php echo $user_email; ?></span> told us that this work can be interesting for you.
</p>
<table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; box-sizing: border-box;">
    <tbody>
    <tr>
        <td align="center" style="font-family: sans-serif; font-size: 14px; vertical-align: top; padding-bottom: 10px;">
        Are you interested in?
        </td>
    </tr>
    <tr>
        <td align="center" style="font-family: sans-serif; font-size: 14px; vertical-align: top; padding-bottom: 50px;">
            <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;">
                <tbody>
                    <tr>
                        <td style="width:80px; font-family: sans-serif; font-size: 14px; vertical-align: top; text-align: center;">
                            <a href="<?php echo env('FRONTEND_URL') ?>" target="_blank" style="display:block; width:80px; color: #ffffff; background-color: #3498db; border: solid 1px #3498db; border-radius: 5px; box-sizing: border-box; cursor: pointer; text-decoration: none; font-size: 14px; font-weight: bold; margin: 0; padding: 12px 0; text-transform: capitalize; border-color: #3498db;">
                                Yes
                            </a>
                        </td>
                        <td width="10" style="width:10px;"></td>
                        <td style="width:80px; font-family: sans-serif; font-size: 14px; vertical-align: top; text-align: center;">
                            <a href="http://<?php echo env('FRONTEND_URL') ?>" target="_blank" style="display:block; width:80px; color: #ffffff; background-color: #6c757d; border: solid 1px #6c757d; border-radius: 5px; box-sizing: border-box; cursor: pointer; text-decoration: none; font-size: 14px; font-weight: bold; margin: 0; padding: 12px 0; text-transform: capitalize; border-color: #6c757d;">
                                No
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