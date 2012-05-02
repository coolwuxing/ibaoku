$(function() {
	$('#home_ad_slider').flexslider({
		animation: "slide",
		controlsContainer: ".home_center_ad",
		slideshowSpeed: 5000,
		directionNav:true,
		controlNav:false
	});
	$('#home_special_slider').flexslider({
		animation: "slide",
		controlsContainer: ".special_slide",
		slideDirection:"vertical",
		slideshowSpeed: 3000,
		directionNav:false,
		controlNav:false
	});
	$('#g_nav_ol li').hover(function() {
		$('#g_nav_ol li').removeClass("selected");
		$(this).addClass("selected");
		
		$('#g_nav_dropdown .dropdown_cont').hide();
		$i = $(this).index();
		if ($i == 0) return;
		$('#g_nav_dropdown .dropdown_cont:eq('+$i+')').show();
	});
	$('#g_nav_dropdown .dropdown_cont').mouseleave(function() {
		console.log("out\n");
		$('#g_nav_ol li').removeClass("selected");
		$('#g_nav_home_li').addClass("selected");
		$('#g_nav_dropdown .dropdown_cont').hide();
	});
	
});