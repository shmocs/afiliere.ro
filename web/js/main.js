
//global js stuff
$(function () {

	$('.dashboard_filter_date_range').on('click', function (e) {

		var date_range = $("input[name=date_range]").val();
		window.location.href = '/site/index?date_range='+date_range;

		e.preventDefault();
	});

});