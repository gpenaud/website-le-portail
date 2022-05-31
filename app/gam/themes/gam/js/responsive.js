var responsive = false;

function toggleMenu() {
	console.log("toggleMenu");
	if (!responsive) {
		return;
	}
	if (!$("#hamburger").hasClass("hamburger-x")) {
		console.log("showMenu");
		showMenu("100vw");
	} else {
		console.log("hideMenu");
		hideMenu();
	}
}

function showMenu(w) {
	if (!responsive)
		return;
	$("#nav").animate({
		width: w,
		opacity: "1"
	}, 500, "swing");
	$("#hamburger").addClass("hamburger-x");
	$("#nav>ul a, #nav").on("click", hideMenu);
}

function hideMenu() {
	if (!responsive)
		return;
	$("#hamburger").delay(200).removeClass("hamburger-x");
	$("#nav").animate({
		width: "0vw",
		opacity: "0"
	}, 300, "swing");
	$("#nav>ul a, #nav").off("click");
}

$(window).resize(function() {
	if ($(window).innerWidth() <= 768) {
		responsive = true;
		hideMenu();
		$("#hamburger").on("click", toggleMenu);
		//        $(".hover").on('touchstart touchend', function(e) {
		//            e.preventDefault();
		//            $(this).toggleClass('hover-effect');
		//        });
	} else {
		$("#hamburger").off("click");
		showMenu("90%");
		responsive = false;
		//        $(".hover").off('touchstart touchend');
	}
});

$(document).ready(function() {
	$("#nav>ul li").addClass("hover");
	$(window).resize();
});