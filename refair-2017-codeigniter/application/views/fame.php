<?php
if(isset($args)){
    echo $args['list'];
    echo "listing";
}else{
    echo "<h2>Fame</h2>";
    echo "<p>Most popular patterns </p>";
    echo "<p><a class=\"btn btn-raised btn-info\" href=\"/welcome/famelist\" role=\"button\">View details &raquo;</a></p>";
}