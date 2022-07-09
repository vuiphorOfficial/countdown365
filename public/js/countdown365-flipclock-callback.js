(function( $ ) {
	'use strict';
     //console.log(flipclock);           
	 $(document).ready(function() {

	    var clocks = [];
	    $(".clock").each(function() {
	        var clock = $(this),
	            date = (new Date(clock.data("countdown365")).getTime() - new Date().getTime()) / 1000;

	        clock.FlipClock(date, {
	            clockFace: "DailyCounter",
	            countdown: true,
	           
	        });

	        clocks.push(clock);
	        		   
	    }); 

	    console.log(flipclock);      
	});

})( jQuery );