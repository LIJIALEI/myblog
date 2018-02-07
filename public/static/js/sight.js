$(function(){   
		  
	var interval;
	$(".sightPic img").click(function cover(){
			$(this).addClass("zoom").fadeOut(600,append);		
			function append(){
			$(this).removeClass("zoom").appendTo(".sightPic").show();
			
			}	
	  
	})
	
	function auto(){
			var play = $(".sightPic").children("img").first();
			play.addClass("zoom").fadeOut(600,append);		
			function append(){
			$(this).removeClass("zoom").appendTo(".sightPic").show();
			
			}
			interval = setTimeout(auto,4000);
	}
	
	$(".sightPic img").hover(function(){
			stopPlay();
	},function(){
			interval = setTimeout(auto,4000);
	})
	
	function stopPlay(){
		clearTimeout(interval)
	}
	auto();
					
})