

$(function () {
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

});

