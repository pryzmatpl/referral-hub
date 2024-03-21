<br/>
<div class="jumbotron content-main">
<?php
$user = $this->user_model->get_by('email',$this->emailes);
print_r($user);
    ?>
</div>