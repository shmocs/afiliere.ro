

$(function () {
	/*
	$('#example1').DataTable();
	$('#example2').DataTable({
		'paging'      : true,
		'lengthChange': false,
		'searching'   : false,
		'ordering'    : true,
		'info'        : true,
		'autoWidth'   : false
	});
	*/
	$('#add_sales').on('click', function (evt) {
		//$('#sales-import-form')[0].reset();

		$('.fileinput-button').show();
		$('#progress .progress-bar').css('width', '0%');
		$('.files').html('');

	});

});

