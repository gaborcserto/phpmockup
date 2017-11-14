import {$} from './lib/jquery.es6';

$(document).ready(() => {
	formSubmit('.file-upload__form');
});

function formSubmit (form) {

	$(form).on('submit', (event)=> {

		let formData = {
				'title'			: $('input[name=title]').val(),
				'description'	: $('input[name=description]').val(),
				'image'			: $('input[name=image]').val()
			},
			titleGroup 			= '#title-group',
			descriptionGroup 	= '#description-group',
			imageGroup 			= '#image-group',
			formGroup 			= '#form';


		$(formGroup).removeClass('has-error');

		// eslint-disable-next-line no-console
		console.log(formData);

		$.ajax({
			type        : 'POST',
			url         : 'ajax.php',
			data        : formData,
			dataType    : 'json',
			encode      : true
		})
			.done((data) => {
				// eslint-disable-next-line no-console
				//console.log(data);

				$(formGroup).append('<div class="alert alert-success">' + data.message + '</div>');
			})
			.fail((data) =>{
				// eslint-disable-next-line no-console
				//console.log(data);

				if (data.errors.title) {
					$(titleGroup).addClass('has-error');
					$(titleGroup).append('<div class="alert alert-danger" role="alert">' + data.errors.title + '</div>');
				}

				if (data.errors.description) {
					$(descriptionGroup).addClass('has-error');
					$(descriptionGroup).append('<div class="alert alert-danger" role="alert">' + data.errors.description + '</div>');
				}

				if (data.errors.image) {
					$(imageGroup).addClass('has-error');
					$(imageGroup).append('<div class="alert alert-danger" role="alert">' + data.errors.image + '</div>');
				}

				if (data.errors.form) {
					$(formGroup).addClass('has-error');
					$(formGroup).append('<div class="alert alert-danger" role="alert">' + data.errors.form + '</div>');
				}
			});

		event.preventDefault();
	});

}

