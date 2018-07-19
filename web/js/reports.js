

$(function () {

	$('[data-toggle="tooltip"]').tooltip();

	//$('#example1').DataTable();
	$('#global_report_table').DataTable({
		'paging'      : true,
		'pageLength'	: 30,
		'lengthChange': false,
		'searching'   : true,
		'ordering'    : true,
		'info'        : true,
		'autoWidth'   : false
	});


	$('#sale-advertiser').on('change', function (e) {
		url = keepSearchingURL();
		window.location.href = url;
	});

	$('#date_type').on('change', function (e) {
		url = keepSearchingURL();
		window.location.href = url;
	});

	$('#commission_type').on('change', function (e) {
		url = keepSearchingURL();
		window.location.href = url;
	});






});

