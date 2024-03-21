function isload() { $body=$("body");
		    $body.addClass("loading");
		  }
function isdone() { $body=$("body");
		    $body.removeClass("loading"); } 

function iserror() { $body=$("body");
		     $body.addClass("error");
		     alert("An error has occured, I'm so sorry. Please refresh :)");
		  }

$(document).ready(function() {

    var once_bar = true;
    var once_key = true;
    var twice_key = true;
    var thrice_key = true;
    var quad_key = true;

    $(".password .email").keypress( function(event){
	if(e.which==13){
	    $(".form-signin").validate();
	}
    });

    $(".btn .btn-lg .btn-primary .btn-block").click( function(event){
	$(".form-signin").validate();
    });
    
    $('#demail').keypress(function(e){
	if(e.which==13){
	    $("#form").validate();
	    $(".search").change();
	    isload();
	    perform(called);
	}
    });
    $('#dname').keypress(function(e){
	if(e.which==13){
	    $("#form").validate();
	    $(".search").change();
	    isload();
	    perform(called);
	}
    });
    $('#dmobile').keypress(function(e){
	if(e.which==13){
	    $("#form").validate();
	    $(".search").change();
	    isload();
	    perform(called);
	}
    });

    $('#daddress').keypress(function(e){
	if(e.which==13){
	    $("#form").validate();
	    $(".search").change();
	    isload();
	    perform(called);
	}
    });

    $('#daddress').focus(function(){
	if(once_key){
	    $(this).val('');
	    once_key=false;
	}
    });
    
    $('#demail').focus(function(){
	if(twice_key){
	    $(this).val('');
	    twice_key=false;
	}
    });
    
    $('#dname ').focus(function(){
	if(thrice_key){
	    $(this).val('');
	    thrice_key=false;
	}
    });
    
    $('#dmobile').focus(function(){
	if(quad_key){
	    $(this).val('');
	    quad_key=false;
	}
    });

    if(Cookies.get('Slavingway')){
	$('#cookie').hide();
    }
    
    $('#cookie-consent').click(function(){
	$('#cookie').hide();
	Cookies.set('Slavingway','Kompot'); // => 'value'
    });
    
    function hndlr(response) {
	for (var i = 0; i < response.items.length; i++) {
            var item = response.items[i];
            // in production code, item.htmlTitle should have the HTML entities escaped.
            document.getElementById("content").innerHTML += "<br>" + item.htmlTitle;
	}
    }

    $("#submit").click(function(event) {
	$("#form").validate();
	$(".search").change();
	isload();
	event.preventDefault();
	perform(called);
    });

    $('#messgPostwriter').click(function(event){
	$("#form").validate();
	$(".email").change();
	isload();
	event.preventDefault();
	sendmailtopostwriter(mailtopost);
    });

    $('#referrer-refer').click(function(event){
	alert("TEST");

    });
    
    $('.dialog-button').each(function() {  
	$.data(this, 'dialog', 
	       $(this).next('.refdialog').dialog({
		   autoOpen: false,  
		   modal: true,  
		   title: 'Apply',  
		   width: 600,  
		   height: 400,  
		   position: [200,0],  
		   draggable: false  
	       })
	      );  
    }).click(function() {  
	$.data(this, 'dialog').dialog('open');  
	return false;  
    });
    
    

});


function called(response){
    results = response;
    $('#resp').html(results);
    isdone();
}

function mailtopost(response){
    results = response;
    $('#mesgresp').html(results);
    isdone();
}
    

function error(response){
    results=response;
    iserror();
}

function perform(callback){
    var adname = $('#dname').val();
    var ademail = $('#demail').val();
    var admobile = $('#dmobile').val();
    var adaddress = $('#daddress').val();
    var uid = $('#emailuid').text();
    
    pathArr=location.href.split('/');
    protocol=pathArr[0];
    host=pathArr[2];
    url=protocol+'//'+host;
    $.ajax({
	type:"GET",
	async:true,
	url: url+'/Research/submitctrl/', 
	data: {'keyone': adname,
	       'keytwo': ademail,
	       'keythree': admobile,
	       'searchterm': adaddress,
	       'emailuid':uid},
	success: called,
	error: error
    });
}

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
	url: url+'/Postwriter/sendmailtopostwriter/', 
	data: {'email_name': email_name,
	       'message': message},
	success: mailtopost,
	error: error
    });
}
