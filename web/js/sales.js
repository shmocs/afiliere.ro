
var import_finished = false;

$(function () {
	//$('.sidebar-toggle').click();

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

	$('[data-toggle="tooltip"]').tooltip();

	$('#add_sales').on('click', function (evt) {
		//$('#sales-import-form')[0].reset();

		$('.fileinput-button').show();
		$('#progress .progress-bar').css('width', '0%');
		$('.files').html('');

	});


	$('.test-btn').on('click', function () {
		$("#w4").yiiGridView("applyFilter");
	});


	$('#modal-import').on('hidden.bs.modal', function () {
		if (import_finished) {
			window.location.href = window.location.href.replace(/#$/, '');
		}
	});


	$('.download').on('click', function (e) {
		e.preventDefault();

		window.location.href = $(this).data('href');
		return false;
	});


});

