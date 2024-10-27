//Prism JS dependencies begin here:

/* --prism I like this cuz it's simple
 * Lightweight JSONP fetcher copied for prism.
 * Copyright 2010-2012 Erik Karlsson. All rights reserved.
 * BSD licensed
 */


/*
 * Usage:
 * 
 * JSONP.get( 'someUrl.php', {param1:'123', param2:'456'}, function(data){
 *   //do something with data, which is the JSON object you should retrieve from someUrl.php
 * });
 */
var JSONP = (function(){
    var counter = 0, head, window = this, config = {};
    function load(url, pfnError) {
	var script = document.createElement('script'),
	    done = false;
	script.src = url;
	script.async = true;
	
	var errorHandler = pfnError || config.error;
	if ( typeof errorHandler === 'function' ) {
	    script.onerror = function(ex){
		errorHandler({url: url, event: ex});
	    };
	}
	
	script.onload = script.onreadystatechange = function() {
	    if ( !done && (!this.readyState || this.readyState === "loaded" || this.readyState === "complete") ) {
		done = true;
		script.onload = script.onreadystatechange = null;
		if ( script && script.parentNode ) {
		    script.parentNode.removeChild( script );
		}
	    }
	};
	
	if ( !head ) {
	    head = document.getElementsByTagName('head')[0];
	}
	head.appendChild( script );
    }
    function encode(str) {
	return encodeURIComponent(str);
    }
    function jsonp(url, params, callback, callbackName) {
	var query = (url||'').indexOf('?') === -1 ? '?' : '&', key;
	
	callbackName = (callbackName||config['callbackName']||'callback');
	var uniqueName = callbackName + "_json" + (++counter);
	
	params = params || {};
	for ( key in params ) {
	    if ( params.hasOwnProperty(key) ) {
		query += encode(key) + "=" + encode(params[key]) + "&";
	    }
	}	
	
	window[ uniqueName ] = function(data){
	    callback(data);
	    try {
		delete window[ uniqueName ];
	    } catch (e) {}
	    window[ uniqueName ] = null;
	};
	
	load(url + query + callbackName + '=' + uniqueName);
	return uniqueName;
    }
    function setDefaults(obj){
	config = obj;
    }
    return {
	get:jsonp,
	init:setDefaults
    };
}());

//Javascript Definitive Guide 6th edition DOM readiness
var whenReady = (function() { // This function returns the whenReady() function
    var funcs = [];    // The functions to run when we get an event
    var ready = false; // Switches to true when the handler is triggered

    // The event handler invoked when the document becomes ready
    function handler(e) {
        // If we've already run once, just return
        if (ready) return;

        // If this was a readystatechange event where the state changed to
        // something other than "complete", then we're not ready yet
        if (e.type === "readystatechange" && document.readyState !== "complete")
            return;
        
        // Run all registered functions.
        // Note that we look up funcs.length each time, in case calling
        // one of these functions causes more functions to be registered.
        for(var i = 0; i < funcs.length; i++) 
            funcs[i].call(document);

        // Now set the ready flag to true and forget the functions
        ready = true;
        funcs = null;
    }

    // Register the handler for any event we might receive
    if (document.addEventListener) {
        document.addEventListener("DOMContentLoaded", handler, false);
        document.addEventListener("readystatechange", handler, false);
        window.addEventListener("load", handler, false);
    }
    else if (document.attachEvent) {
        document.attachEvent("onreadystatechange", handler);
        window.attachEvent("onload", handler);
    }

    // Return the whenReady function
    return function whenReady(f) {
        if (ready) f.call(document); // If already ready, just run it
        else funcs.push(f);          // Otherwise, queue it for later.
    }
}());

//Prism begins here////////////////////////////////////////////////////////////
//Prism begins here////////////////////////////////////////////////////////////
//And we're off

function prismDiffract() {
    // markup is on the page

    var $mouseX = 0, $mouseY = 0;
    var $xp = 0, $yp =0;    var once_bar = true;

    $(document).mousemove(function(e){
	$mouseX = e.pageX;
	$mouseY = e.pageY;    
    });

    $("#salarySlider").slider({});
    //$("#experienceSlider").slider({}); experience removed
    $("#addjob-required_fund").slider({});
    $("#addjob-required_exp").slider({});
    
    $(".password .email").keypress( function(event){
	if(e.which==13){
	    $(".form-signin").validate();
	}
    });

    $(".btn .btn-lg .btn-primary .btn-block").click( function(event){
	$(".form-signin").validate();
    });
    
    if(Cookies.get('Slavingway')){
	$('#cookie').hide();
    }
    
    $('#cookie-consent').click(function(){
	$('#cookie').hide();
	Cookies.set('Slavingway','Kompot'); // => 'value'
    });

}

function hndlr(response) {
    for (var i = 0; i < response.items.length; i++) {
        var item = response.items[i];
        // in production code, item.htmlTitle should have the HTML entities escaped.
        document.getElementById("content").innerHTML += "<br>" + item.htmlTitle;
    }
}

$('#messgPostwriter').click(function(event){
    $("#form").validate();
    $(".email").change();
    isload();
    event.preventDefault();
    sendmailtopostwriter(mailtopost);
});

$('#perkbut-add').click(function(event){
    event.preventDefault();
    $("#perkbutt-add-form").validate();
    $("#perkbutt-add-form").change();
    isload();
    addperk(resp);
});

$('#addjob-location').keypress(function(e){
    var locname_val = $('#addjob-location').val()+'~'+$('#addjob-location').attr('title').val();
});

$('#refair-search').click(function(event){
    event.preventDefault();
    refairer.validate();
    refairer.change();
    search_job(called);
});

$('#editloc-but').click( function(e){
    console.log(e);
    $('.modal #addlocation').removeData();
    var abbrev = $(this).attr('name');
    var alldata = abbrev.split('~');
    $('#loc_name').val(alldata[1]);
    $('#loc_city').val(alldata[2]);
    $('#loc_country').val(alldata[3]);
    $('#loc_zip').val(alldata[5]);
    $('#loc_address').val(alldata[4]);
    $('#loc_lon').val(alldata[6]);
    $('#loc_lat').val(alldata[7]);
    $('#loc_desc').val(alldata[9]);
    $('#loc_uid').val(alldata[8]);
    $('.modal #addlocation').modal('open');
}  );

$('#locadd-but').click(function(e){
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

$('.addjob-call').on('click', function(event){
    event.preventDefault();
    $("#form-addjob").change();
    isload();
    addjobdesc(debugapply);
});

$('#refairer_import').on('submit', function(e){
    e.preventDefault();
    pathArr=location.href.split('/');
    protocol=pathArr[0];
    host=pathArr[2];
    url=protocol+'//'+host;
    
    isload();
    
    $('#filename-monit').prop('value', $('#fileli').val()['file_name'] );
    
    if( $('#fileli').val=='' ){
	alert("No File Chosen");
    }else{
	$.ajax({
	    url: url + "/Refair/upload",
	    type: "POST",
	    data: new FormData(this),
	    contentType: false,
	    cache: false,
	    processData : false,
	    success: resp,
	    error: error
	});
    }
});

$('.addlocation-call').on('click',function(event){
    $('.modal #addlocation').modal('open');
});

//Delete location funcionality v   
$('.deletelocation-call').on('click',function(event){
    $('.modal #deletelocation-body').modal('open');
    var abbrev = $(this).attr('name').split('~');
    console.log(abbrev);
    $('.placeholder-for-lochash').val(abbrev[10]);
    $('.placeholder-for-locid').val(abbrev[0]);
    $('#location-delname').html(abbrev[1]);
});

$('#deleteloc-final').click( function(event){
    $('#form-delloc').change();
    event.preventDefault();
    isload();
    delete_loc(debugapply);
});
///Delete location functionality^

//Delete referral functionality v
$('.deleteref-call').on('click',function(event){
    $('.modal #deleteref-body').modal('open');
    var abbrev = $(this).attr('name').split('~');
    console.log(abbrev);
    $('.placeholder-for-refid').val(abbrev[0]);
    $('.placeholder-for-refhash').val(abbrev[1]);
    $('.delref-name').html(abbrev[1]);
});

$('#deleteref-final').click( function(event){
    $('#form-delref').change();
    event.preventDefault();
    isload();
    delete_referral(debugapply);
});
///Delete referral functionality ^

//Delete perk funcionality v   
$('.deleteperk-call').on('click',function(event){
    $('.modal #deleteperk-body').modal('open');
    var abbrev = $(this).attr('name').split('~');
    console.log(abbrev);
    $('.placeholder-for-perkuid').val(abbrev[1]);
    $('.placeholder-for-perkid').val(abbrev[0]);
    $('#perkname-del').html(abbrev[0]);
});

$('.deleteperk-final').click( function(event){
    $('#form-delperk').change();
    event.preventDefault();
    isload();
    delete_perk(resp);
});
///Delete perk functionality^

//Refer someone funcionality v   
$('.refair-call').on('click',function(event){
    $('.modal #refer-body').modal('open');
    var abbrev = $(this).attr('name').split('~');
    console.log(abbrev);
    $('.placeholder-for-referrer-mail-ref').val(abbrev[4]);
    $('.placeholder-for-jobid-ref').val(abbrev[0]);
    $('.placeholder-for-role-ref').val(abbrev[1]);
    $('.placeholder-for-keywords-ref').val(abbrev[2]);
    $('.placeholder-for-location-ref').val(abbrev[3]);
});

$('.refair-final').click( function(event){
    $('#form-refer').change();
    $('#form-refer').validate();
    event.preventDefault();
    isload();
    refer(resp);
});
///Refer someone functionality^

/// Delete job functionality V
$('.deletejob-call').on('click',function(event){
    $('.modal #delete-jobss').modal('open');
    var abbrev = $(this).attr('name').split('~');
    console.log(abbrev);
    $('.placeholder-for-delete-jobid').val(abbrev[0]);
    $('.placeholder-for-delete-uid').val(abbrev[1]);
});

$('.deletejob-final').click(function(event){
    $('#form-deletejob').change()
    event.preventDefault();
    isload();
    delete_job(resp);
});    
// Delete job functionality ^

/// Apply for a job functionality V
$('.apply-call').on('click',function(event){
    $('.modal #apply-body').modal('open');
    var abbrev = $(this).attr('name').split('~');
    console.log(abbrev);
    $('.placeholder-for-email-apply').val(abbrev[4]);
    $('.placeholder-for-role-apply').val(abbrev[1]);
    $('.placeholder-for-keywords-apply').val(abbrev[2]);
    $('.placeholder-for-jobid-apply').val(abbrev[0]);
    $('.placeholder-for-location-apply').val(abbrev[3]);
});

$('.apply-final').click(function(event){
    $("#form-apply").change();
    event.preventDefault();
    isload();
    apply(debugapply);
});
// Apply for job functionality ^

$('.modal').on('hidden', function() {$(this).removeData();});

//Event Callbacks v

function restartApplyRefer(resp){
    ///Refer someone functionality^
    called(resp);
    isdone();
}

function isload() { $body=$("body");
		    $body.addClass("loading");
		  }

function isdone() {
    $body=$("body");
    $body.removeClass("loading");
} 

function derefer() { $body=$("body");
		     $body.removeClass("refer-modall");
		   }
function deapply() { $body=$("body");
		     $body.removeClass("apply-modall");
		   }

function iserror() { $body=$("body");
		     $body.addClass("error");
		     alert("An error has occured, I'm so sorry. Please refresh :)");
		   }

function called(response){
    results = response;
    $('#resp').html(results);
    whenReady(prismDiffract);
    isdone();
}

function calledloc(response){
    results = response;
    $('#resp').html(results);
    alert(results);
    isdone();
    $('#addlocation').modal('toggle');
    setTimeout(
        function() 
	{
	    location.reload();
	}, 0001);    
}

function calledfile(response){
    results = response;
    $('#files').html(results);
    isdone();
}

function mailtopost(response){
    results = response;
    $('#mesgresp').html(results);
    isdone();
}

function jobdesc(response){
    results = response;
    $('#jobdesc_div').html(results);
    isdone();
    setTimeout(
        function() 
	{
	    location.reload();
	}, 0001);    
}

function resp(response){
    results = response;
    $('#resp').html(results);
    isdone();
    setTimeout(
        function() 
	{
	    location.reload();
	}, 0001);    
}


function showjobdescs(response){
    results = response;
    isdone();
    $('#jobdesc_div').html(results);
}

function error(response){
    results=response;
    iserror();
}

function debugapply(response){
    $('.modal').modal('hide');
    $('#resp').val(response);
    isdone();
    setTimeout(
        function() 
	{
	    location.reload();
	}, 0001);    
}

function generalend(response){
    alert('generalend ' + response);
    isdone();
}

function debugrefer(response){
    $('.modal #refer').modal('close');
    alert('debug refer: ' +response);
    isdone();
}

//Event callbacks ^

function sendmailtopostwriter(callback){
    var email_name = $('#email_name').val();
    var message = $('#message').val();
    
    pathArr=location.href.split('/');
    protocol=pathArr[0];
    host=pathArr[2];
    url=protocol+'//'+host;
    $.ajax({
	type:"GET",
	async:true,
	url: url+'/Postwriter/sendmailtopostwriter/<?php echo $hhash; ?>', 
	data: {'email_name': email_name,
	       'message': message},
	success: callback,
	error: error
    });
}

function addjobdesc(callback){
    var $jobtitle = $('#addjob-jobtitle').val();
    var $description = $('#addjob-description').val();
    var $required_exp = $('#addjob-required_exp').val();
    var $required_fund = $('#addjob-required_fund').val();
    var $required_relocation = $('#required_relocation').val();
    var $required_remote = $('#addjob-required_remote').val();
    var $location = $('.selectpicker').val();
    var $keywords = $('#addjob-keywords').val();
    var $poster_id = $('#addjob-poster_id').val();
    var $bounty_id = 0.03; //this is where we set the bounty now - must be changed after discussion

    pathArr=location.href.split('/');
    protocol=pathArr[0];
    host=pathArr[2];
    url=protocol+'//'+host;
    
    $.ajax({
	type:"GET",
	async:true,
	url: url+'/Refair/addjobdesc/', 
	data: {'jobtitle': $jobtitle,
	       'description': $description,
	       'required_exp': $required_exp,
	       'required_fund': $required_fund,
	       'required_relocation': $required_relocation,
	       'required_remote': $required_remote,
	       'location': $location,
	       'keywords': $keywords,
	       'poster_id': $poster_id,
	       'bounty_id': $bounty_id,
	      },
	success: callback,
	error: error
    });
}

function addperk(callback){
    var $perkname = $('#new-perk-name').val();
    var $perkforhire = $('#new-perk-for-hire').is(':checked');
    var $perkforreferral = $('#new-perk-for-referring').is(':checked'); 
    var $perkuid = $('#new-perk-uid').val();
    var $perkjobid = $('#new-perk-jobid').val();
    var $perktarget = 'none';

    if($perkforhire && $perkforreferral){
	$perktarget = 'both';
    }else if ($perkforhire) {
	$perktarget = 'for-hire';
    }else if ($perkforreferral) {
	$perktarget = 'for-referral';
    }else $perktarget = 'noshow';

    
    pathArr=location.href.split('/');
    protocol=pathArr[0];
    host=pathArr[2];
    url=protocol+'//'+host;
    
    $.ajax({
	type:"GET",
	async:true,
	url: url+'/Refair/addperk/', 
	data: {'newperk-name': $perkname,
	       'newperk-posterid':$perkuid,
	       'newperk-jobid':$perkjobid,
	       'newperk-target':$perktarget
	      },
	success: callback,
	error: error
    });
}

function refer(callback){
    var Arole = $('.placeholder-for-role-ref').val();
    var Areferrer = $('.placeholder-for-referrer-mail-ref').val();
    var Areferred = $('.placeholder-for-referred-mail-ref').val();
    var Akeywords = $('.placeholder-for-keywords-ref').val();
    var Areflocation = $('.placeholder-for-location-ref').val();
    var Ajobid = $('.placeholder-for-jobid-ref').val();

    pathArr=location.href.split('/');
    protocol=pathArr[0];
    host=pathArr[2];
    url=protocol+'//'+host;
    $.ajax({
	type:"GET",
	async:true,
	url: url+'/Refair/refer/', 
	data: {'role': Arole,
	       'referrer': Areferrer,
	       'referred': Areferred,
	       'keywords': Akeywords,
	       'location':Areflocation,
	       'jobid':Ajobid},
	success: callback,
	error: error
    });
}

function apply(callback){
    var Arole = $('.placeholder-for-role-apply').val();
    var Aapplier = $('.placeholder-for-email-apply').val();
    var Bapplier = Aapplier;
    var Akeywords = $('.placeholder-for-keywords-apply').val();
    var Alocation = $('.placeholder-for-location-apply').val();
    var Ajobid = $('.placeholder-for-jobid-apply').val();
    
    pathArr=location.href.split('/');
    protocol=pathArr[0];
    host=pathArr[2];
    url=protocol+'//'+host;
    $.ajax({
	type:"GET",
	async:true,
	url: url+'/Refair/refer/', 
	data: {'role': Arole,
	       'referrer': Aapplier,
	       'referred': Bapplier,
	       'keywords': Akeywords,
	       'location':Alocation,
	       'jobid':Ajobid},
	success: callback,
	error: error
    });
}

function delete_job(callback){
    var Ajobid = $('#delete-jobname').val();
    
    pathArr=location.href.split('/');
    protocol=pathArr[0];
    host=pathArr[2];
    aurl=protocol+'//'+host;
    $.ajax({
	type:"GET",
	async:true,
	url: aurl+'/Refair/deletejob/', 
	data: {'jobid':urlencode(base64_encode(Ajobid))},
	success: callback,
	error: error
    });
}

function delete_referral(callback){
    var Arefid = $('.placeholder-for-refid').val();
    var Arefhash = $('.placeholder-for-refhash').val();

    pathArr=location.href.split('/');
    protocol=pathArr[0];
    host=pathArr[2];
    url=protocol+'//'+host;
    $.ajax({
	type:"GET",
	async:true,
	url: url+'/Refair/deleteref/', 
	data: {'refid': base64_encode(Arefid),
	       'refhash': base64_encode(Arefhash)},
	success: callback,
	error: error
    });
}

function delete_loc(callback){
    var Alochash = $('.placeholder-for-hash').val();
    var Alocid = $('.placeholder-for-locid').val();

    pathArr=location.href.split('/');
    protocol=pathArr[0];
    host=pathArr[2];
    url=protocol+'//'+host;
    $.ajax({
	type:"GET",
	async:true,
	url: url+'/Account/deleteloc/', 
	data: {'lochash':base64_encode(Alochash),
	       'locid':base64_encode(Alocid)},
	success: callback,
	error: error
    });
}

function delete_perk(callback){
    var $Aperkid = $('.placeholder-for-perkid').val();
    var $Auid = $('.placeholder-for-perkuid').val();

    pathArr=location.href.split('/');
    protocol=pathArr[0];
    host=pathArr[2];
    url=protocol+'//'+host;
    $.ajax({
	type:"GET",
	async:true,
	url: url+'/Refair/delperk/', 
	data: {'perkid':$Aperkid,
	       'uid':$Auid},
	success: callback,
	error: error
    });
}



function utf8_to_b64( str ) {
    return window.btoa(unescape(encodeURIComponent( str )));
}

function b64_to_utf8( str ) {
    return decodeURIComponent(escape(window.atob( str )));
}

function base64_encode(stringToEncode) { // eslint-disable-line camelcase
    //  discuss at: http://locutus.io/php/base64_encode/
    // original by: Tyler Akins (http://rumkin.com)
    // improved by: Bayron Guevara
    // improved by: Thunder.m
    // improved by: Kevin van Zonneveld (http://kvz.io)
    // improved by: Kevin van Zonneveld (http://kvz.io)
    // improved by: Rafał Kukawski (http://blog.kukawski.pl)
    // bugfixed by: Pellentesque Malesuada
    //   example 1: base64_encode('Kevin van Zonneveld')
    //   returns 1: 'S2V2aW4gdmFuIFpvbm5ldmVsZA=='
    //   example 2: base64_encode('a')
    //   returns 2: 'YQ=='
    //   example 3: base64_encode('✓ à la mode')
    //   returns 3: '4pyTIMOgIGxhIG1vZGU='

    if (typeof window !== 'undefined') {
	if (typeof window.btoa !== 'undefined') {
	    return window.btoa(decodeURIComponent(encodeURIComponent(stringToEncode)))
	}
    } else {
	return new Buffer(stringToEncode).toString('base64')
    }

    var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/='
    var o1
    var o2
    var o3
    var h1
    var h2
    var h3
    var h4
    var bits
    var i = 0
    var ac = 0
    var enc = ''
    var tmpArr = []

    if (!stringToEncode) {
	return stringToEncode
    }

    stringToEncode = decodeURIComponent(encodeURIComponent(stringToEncode))

    do {
	// pack three octets into four hexets
	o1 = stringToEncode.charCodeAt(i++)
	o2 = stringToEncode.charCodeAt(i++)
	o3 = stringToEncode.charCodeAt(i++)

	bits = o1 << 16 | o2 << 8 | o3

	h1 = bits >> 18 & 0x3f
	h2 = bits >> 12 & 0x3f
	h3 = bits >> 6 & 0x3f
	h4 = bits & 0x3f

	// use hexets to index into b64, and append result to encoded string
	tmpArr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4)
    } while (i < stringToEncode.length)

    enc = tmpArr.join('')

    var r = stringToEncode.length % 3

    return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3)
}

function urldecode (str) {
    return decodeURIComponent((str + '').replace(/\+/g, '%20'));
}

function urlencode (str) {
    //       discuss at: http://locutus.io/php/urlencode/
    //      original by: Philip Peterson
    //      improved by: Kevin van Zonneveld (http://kvz.io)
    //      improved by: Kevin van Zonneveld (http://kvz.io)
    //      improved by: Brett Zamir (http://brett-zamir.me)
    //      improved by: Lars Fischer
    //         input by: AJ
    //         input by: travc
    //         input by: Brett Zamir (http://brett-zamir.me)
    //         input by: Ratheous
    //      bugfixed by: Kevin van Zonneveld (http://kvz.io)
    //      bugfixed by: Kevin van Zonneveld (http://kvz.io)
    //      bugfixed by: Joris
    // reimplemented by: Brett Zamir (http://brett-zamir.me)
    // reimplemented by: Brett Zamir (http://brett-zamir.me)
    //           note 1: This reflects PHP 5.3/6.0+ behavior
    //           note 1: Please be aware that this function
    //           note 1: expects to encode into UTF-8 encoded strings, as found on
    //           note 1: pages served as UTF-8
    //        example 1: urlencode('Kevin van Zonneveld!')
    //        returns 1: 'Kevin+van+Zonneveld%21'
    //        example 2: urlencode('http://kvz.io/')
    //        returns 2: 'http%3A%2F%2Fkvz.io%2F'
    //        example 3: urlencode('http://www.google.nl/search?q=Locutus&ie=utf-8')
    //        returns 3: 'http%3A%2F%2Fwww.google.nl%2Fsearch%3Fq%3DLocutus%26ie%3Dutf-8'

    str = (str + '')

    // Tilde should be allowed unescaped in future versions of PHP (as reflected below),
    // but if you want to reflect current
    // PHP behavior, you would need to add ".replace(/~/g, '%7E');" to the following.
    return encodeURIComponent(str)
	.replace(/!/g, '%21')
	.replace(/'/g, '%27')
	.replace(/\(/g, '%28')
	.replace(/\)/g, '%29')
	.replace(/\*/g, '%2A')
	.replace(/%20/g, '+')
}


