
$(function () {

	$('.overlay').hide();
	$('.import-results').addClass('hide').removeClass('alert-danger').removeClass('alert-warning').removeClass('alert-success');
	//$('.import-results .result-content').html('');

	'use strict';
	// Change this to the location of your server-side upload handler:
	var url = '/jQueryFileUpload/server/php/',
		uploadButton = $('<button/>')
			.addClass('btn btn-primary')
			.prop('disabled', true)
			.text('Processing...')
			.on('click', function () {
				var $this = $(this),
					data = $this.data();
				$this
					.off('click')
					.text('Abort')
					.on('click', function () {
						$this.remove();
						data.abort();
					});
				data.submit().always(function () {
					$this.remove();
				});
			});

	$('#fileupload').fileupload({
		url: url,
		dataType: 'json',
		autoUpload: false,
		acceptFileTypes: /(\.)(csv)$/i,
		maxFileSize: 999000,
		// Enable image resizing, except for Android and Opera,
		// which actually support image resizing, but fail to
		// send Blob objects via XHR requests:
		disableImageResize: /Android(?!.*Chrome)|Opera/
			.test(window.navigator.userAgent),
		previewMaxWidth: 100,
		previewMaxHeight: 100,
		previewCrop: true
	}).on('fileuploadadd', function (e, data) {
		data.context = $('<div/>').appendTo('#files');
		$.each(data.files, function (index, file) {
			var node = $('<p/>')
				.append($('<span/>').text(file.name));
			if (!index) {
				node
					.append('<br>')
					.append(uploadButton.clone(true).data(data));
			}
			node.appendTo(data.context);
		});
	}).on('fileuploadprocessalways', function (e, data) {
		var index = data.index,
			file = data.files[index],
			node = $(data.context.children()[index]);
		if (file.preview) {
			node
				.prepend('<br>')
				.prepend(file.preview);
		}
		if (file.error) {
			node
				.append('<br>')
				.append($('<span class="text-danger"/>').text(file.error));
		}
		if (index + 1 === data.files.length) {
			data.context.find('button')
				.text('Upload')
				.prop('disabled', !!data.files.error);
		}
	}).on('fileuploadprogressall', function (e, data) {
		var progress = parseInt(data.loaded / data.total * 100, 10);
		$('#progress .progress-bar').css(
			'width',
			progress + '%'
		);
	}).on('fileuploaddone', function (e, data) {

		$('.fileinput-button').hide();

		window.setTimeout(function() {
			$('#progress .progress-bar').removeClass('progress-bar-striped');
			$('#progress').removeClass('active');
		}, 500);


		$.each(data.result.files, function (index, file) {
			//console.log(file);

			if (file.url) {
				var link = $('<a>')
					.attr('target', '_blank')
					.prop('href', file.url);
				$(data.context.children()[index])
					.wrap(link);

				process_uploaded_file(file.name);

			} else if (file.error) {
				var error = $('<span class="text-danger"/>').text(file.error);
				$(data.context.children()[index])
					.append('<br>')
					.append(error);
			}


		});

	}).on('fileuploadfail', function (e, data) {
		$.each(data.files, function (index) {
			var error = $('<span class="text-danger"/>').text('File upload failed.');
			$(data.context.children()[index])
				.append('<br>')
				.append(error);
		});
	}).prop('disabled', !$.support.fileInput)
		.parent().addClass($.support.fileInput ? undefined : 'disabled');


	function process_uploaded_file(filename) {

		$('.overlay').show();
		$('.import-results').removeClass('hide');

		$.ajax({
			url			: '/sales/import',
			type   		: 'POST',
			dataType 	: 'json',
			data   		: {filename: filename},

			success		: function (response) {
				//json = $.parseJSON(response);
				console.log(response);


				if (response.type == 'success') {
					$('.import-results').addClass('box-success');
					$('.import-results .box-title').html('Results ... All OK!');
				}
				if (response.type == 'error') {
					$('.import-results').addClass('box-danger');
					$('.import-results .box-title').html('Results ... Errors!');
				}
				if (response.type == 'warning') {
					$('.import-results').addClass('box-warning');
					$('.import-results .box-title').html('Results ... Warning!');
				}

				$.each(response.messages, function(index, value) {
					$('.import-results .result-content').append($('<div>'+value+'</div>'));
				});
				$('.import-results .result-content').append($('<br>'));

				if (response._platform) {
					$('.import-results .result-content').append($('<div>Platform detected: <strong>'+response._platform+'</strong></div>'));
				}
				if (response._stats) {
					$('.import-results .result-content').append($('<div>'+response._stats+'</div>'));
				}


				if (response._parsed) {
					$('#import_parsed').html(response._parsed);
				}

				if (response._imported) {
					$('#import_imported').html(response._imported);
				}

				if (response._duplicates) {
					$('#import_duplicates').html(response._duplicates);
				}

				if (response._failed) {
					$('#import_failed').html(response._failed);
				}

				if (response._parsed) {
					$('#import_parsed').html(response._parsed);
				}


				$('.overlay').hide();

				/*
				window.setTimeout(function () {
					$('#reload-grid').click();
				}, 1000);
				*/

				import_finished = true;
			},

			error		: function (e) 	{
				console.log(e);
			}
		});
	}

});