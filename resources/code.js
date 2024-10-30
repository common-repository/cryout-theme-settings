
/* wp 4.4 compatibility */
jQuery(document).ready(function(){
	jQuery("#main-options #accordion h2").each(function(){
		jQuery(this).replaceWith("<h3>" + jQuery(this).html() + "</h3>");
	});
})

/* themes slider */
jQuery(document).ready(function (jQuery) {
	interval = 25000;
	// autoplay
	setInterval(function () {
		moveRight();
	}, interval);


	var slideCount = jQuery('#slider ul li').length;
	var slideWidth = jQuery('#slider ul li').width();
	var slideHeight = jQuery('#slider ul li').height();
	var sliderUlWidth = slideCount * slideWidth;

	jQuery('#slider').css({ width: slideWidth, height: slideHeight });

	jQuery('#slider ul').css({ width: sliderUlWidth, marginLeft: - slideWidth });

	jQuery('#slider ul li:last-child').prependTo('#slider ul');

	function moveLeft() {
		jQuery('#slider ul').animate({
			left: + slideWidth
		}, 500, function () {
			jQuery('#slider ul li:last-child').prependTo('#slider ul');
			jQuery('#slider ul').css('left', '');
		});
	};

	function moveRight() {
		jQuery('#slider ul').animate({
			left: - slideWidth
		}, 500, function () {
			jQuery('#slider ul li:first-child').appendTo('#slider ul');
			jQuery('#slider ul').css('left', '');
		});
	};

	jQuery('a.control_prev').on('click',function ( e ) {
		e.preventDefault();
		moveLeft();
	});

	jQuery('a.control_next').on('click',function ( e ) {
		e.preventDefault();
		moveRight();
	});

});

/* FIN */