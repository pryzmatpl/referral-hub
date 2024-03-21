var tag = document.createElement('script');
tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
var player;
var done = false;
var ytcfg;
var request;
var videoid;

function searchA() {
    		    // request = gapi.client.youtube.search.list({
		    // 	part: "snippet",
		    // 	type: "video",
		    // 	q: encodeURIComponent($("demail").val()).replace(/%20/g, "+"),
		    // 	maxResults: 1,
		    // 	order: "viewCount",
		    // 	publishedAfter: "2015-01-01T00:00:00Z"
			
		    // 	var request = gapi.client.youtube.channels.list({
                    //         part: 'statistics',
                    //         forUsername : 'GameSprout'
		    // 	});
			
		    // 	request.execute(function(response) {
		    // 	});
		    }
							     
function fireRequest(key){
    // gapi.client.setApiKey('AIzaSyARvwirFktEIi_BTaKcCi9Ja-m3IEJYIRk');
    // gapi.client.load('youtube', 'v3', function() {
    // 	searchA();
    // });
}

function onYouTubeIframeAPIReady() {
    // player = new YT.Player('player', {
    //     height: '390',
    //     width: '640',
    //     videoId: 'bHQqvYy5KYo',
    //     events: {
    // 	    'onReady': onPlayerReady,
    // 	    'onStateChange': onPlayerStateChange
    //     }
    // });
}

// 4. The API will call this function when the video player is ready.
function onPlayerReady(event) {
    event.target.playVideo();
}

// 5. The API calls this function when the player's state changes.
//    The function indicates that when playing a video (state=1),
//    the player should play for six seconds and then stop.

function onPlayerStateChange(event) {
    if (event.data == YT.PlayerState.PLAYING && !done) {
        setTimeout(stopVideo, 6000);
        done = true;
    }
}

function stopVideo() {
    player.stopVideo();
}
