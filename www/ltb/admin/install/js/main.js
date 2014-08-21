$(function() {

	$("#nextButton").click(function() {
		var page = $("#configPage").val();
		$("#page"+page).hide();
		page++;
		$("#page"+page).show();
		if (page > 1) {
			$("#prevButton").show();
		}
		$("#configPage").val(page);
	});

	$("#prevButton").click(function() {
		var page = $("#configPage").val();
		$("#page"+page).hide();
		if (page >= 1) {
			page--;
			$("#page"+page).show();
			if (page == 1) {
				$("#prevButton").hide();
			}
		}
		$("#configPage").val(page);
	});

});
