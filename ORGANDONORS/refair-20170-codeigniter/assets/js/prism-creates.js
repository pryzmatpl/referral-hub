//Adding a locatino
$('.locadd-call').click(function(e){
    $("#form-addlocation").validate();
    e.preventDefault();

    var $locname = $('#loc_name').val();
    var $city = $('#loc_city').val();
    var $country = $('#loc_country').val();
    var $address = $('#loc_address').val();
    var $zip = $('#loc_zip').val();
    var $lat = $('#loc_lat').val();
    var $lon = $('#loc_lon').val();
    var $userid = $('#loc_uid').val();
    var $descr = $('#loc_desc').val();

    pathArr=location.href.split('/');
    protocol=pathArr[0];
    host=pathArr[2];
    url=protocol+'//'+host;
    
    isload();

    $.ajax({
	url: url + "/Account/addlocation",
	type: "GET",
	async: true,
	data: {'loc_name': $locname,
	       'loc_city': $city,
	       'loc_country': $country,
	       'loc_address': $address,
	       'loc_zip': $zip,
	       'loc_lat': $lat,
	       'loc_lon': $lon,
	       'loc_desc' : $descr,
	       'loc_userid': $userid},
	success: calledloc,
	error: error
    });});
