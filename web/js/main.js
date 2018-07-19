
//global js stuff

function keepSearchingURL() {
	var _date_range, _date_type, _commission_type, _advertiser, _chartdiv_profit_interval;

	_date_range = $("input[name=date_range]").val();
	_date_type = $('#date_type').val();
	_commission_type = $('#commission_type').val();
	_advertiser = $('#sale-advertiser').val();
	_chartdiv_profit_interval = $('#chartdiv_profit_interval').val();

	url = window.location.href;
	url = updateQueryStringParameter(url, 'date_range', _date_range);
	url = updateQueryStringParameter(url, 'date_type', _date_type);
	url = updateQueryStringParameter(url, 'commission_type', _commission_type);
	url = updateQueryStringParameter(url, 'advertiser', _advertiser);
	url = updateQueryStringParameter(url, 'chartdiv_profit_interval', _chartdiv_profit_interval);

	return url;
}


// adauga sau modifica daca deja exista un parametru din URI
// foarte util la filtrarile cu CGridView pe GET daca mai sunt necesari si alti parametri custom
function updateQueryStringParameter(uri, key, value) {

	if (value == undefined) {
		return uri;
	}

	var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
	var separator = uri.indexOf('?') !== -1 ? "&" : "?";
	if (uri.match(re)) {
		return uri.replace(re, '$1' + key + "=" + value + '$2');
	} else {
		return uri + separator + key + "=" + value;
	}
}

$(function () {

	$('.filter_date_range').on('click', function (e) {
		url = keepSearchingURL();
		window.location.href = url;

		e.preventDefault();
	});

	$('.chartdiv_profit_interval').on('click', function (e) {

		var interval = $(this).html();
		$('#chartdiv_profit_interval').val(interval);

		url = keepSearchingURL();
		window.location.href = url;
	});


});