$(document).ready(function () {
    //якорь
    $("#anchor").click(function () {
        var destination = $('#head').offset().top;
        jQuery("html:not(:animated),body:not(:animated)").animate({scrollTop: destination}, 800);
        return false;
    });
    $(window).scroll(function (){
    	var h = $(window).scrollTop();
    	if (h == 0) {
    		$('.anchor').fadeOut(300);
    	}
    	else {
    		$('.anchor').fadeIn(500);
    	}
    });
    $('.fade_out').click(function(){
        $('.fade_out').toggleClass('fade_down');
        $('.filter').toggleClass('filter_toggle');
    });
    var height = $(window).height();
    var margin = height * 0.5;
    if (height < 600) {
        $('.filter').css({'height': height - 20 + 'px','margin-top':- margin + 10 + 'px'});
    };
});