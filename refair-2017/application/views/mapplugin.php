 <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBitQWDLqUmKD6XBC4kT_-FArmh7RsiNo&callback=initMap"
  type="text/javascript"></script>
<script type="text/javascript">
            print_r "echo";
$(function () {

    function initMap() {

        <?php 
        $jobs = $this->dataReferrals;
        $vaue = $jobs[0];
        ?>

        var location = new google.maps.LatLng(<?php echo $value->lat; ?>, <?php echo $value->lon; ?>);

        var mapCanvas = document.getElementById('map');
        var mapOptions = {
            center: location,
            zoom: 16,
            panControl: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        var map = new google.maps.Map(mapCanvas, mapOptions);

    }

    google.maps.event.addDomListener(window, 'load', initMap);
});
</script>

