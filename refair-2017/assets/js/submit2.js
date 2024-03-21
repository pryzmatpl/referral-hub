function isload() { $body=$("body");
		    $body.addClass("loading");
		  }
function isdone() { $body=$("body");
		    $body.removeClass("loading"); } 

function iserror() { $body=$("body");
		     $body.addClass("error");
		     alert("An error has occured, please refresh (Sorry - This is BETA!) and you adopt early :)");
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

    
    function hndlr(response) {
	for (var i = 0; i < response.items.length; i++) {
            var item = response.items[i];
            // in production code, item.htmlTitle should have the HTML entities escaped.
            document.getElementById("content").innerHTML += "<br>" + item.htmlTitle;
	}
    }

    // $('#modal').hide();
    
    $("#submit").click(function(event) {
	$("#form").validate();
	$(".search").change();
	isload();
	event.preventDefault();
	perform(called);
    })
});

function called(response){
    results = response;
    $('#resp').html(results);
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
    
    pathArr=location.href.split('/');
    protocol=pathArr[0];
    host=pathArr[2];
    url=protocol+'//'+host;
    $.ajax({
	type:"GET",
	async:true,
	url: url+'/welcome/submitctrl/', 
	data: {'keyone': adname,
	       'keytwo': ademail,
	       'keythree': admobile,
	       'searchterm': adaddress},
	success: called,
	error: error
    });
}
