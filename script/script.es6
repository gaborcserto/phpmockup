import {$} from './lib/jquery.es6';

$(document).ready(() => {
	formSubmit($('#form'));
});

function formSubmit (formId) {

	formId.on('submit', (e)=> {
		e.preventDefault();

		let form_data = new FormData(formId);
		form_data.append( 'image', $('#image')[0].files[0]);
		form_data.append( 'title', $('input[name=title]').val());
		form_data.append( 'description', $('input[name=description]').val());

		let titleGroup = '#title-group';
		let descriptionGroup = '#description-group';
		let imageGroup = '#image-group';
		let formGroup = '#form';

		$(formGroup).removeClass('has-error');
		$('div.alert').detach();

		// eslint-disable-next-line no-console
		console.log(form_data);

		$.ajax({
			url: 'ajax.php?upload',
			method: 'POST',
			data: form_data,
			processData: false,
			contentType: false,
			dataType: 'json',
			success: ((res) => {
				// eslint-disable-next-line no-console
				console.log(res);
				if(res.success === true) {
					$(formId).append('<div class="alert alert-success">' + res.message + '</div>');
				}
				if(res.success === false) {
					if (res.errors.title) {
						$(titleGroup).addClass('has-error');
						$(titleGroup).append('<div class="alert alert-danger" role="alert">' + res.errors.title + '</div>');
					}

					if (res.errors.description) {
						$(descriptionGroup).addClass('has-error');
						$(descriptionGroup).append('<div class="alert alert-danger" role="alert">' + res.errors.description + '</div>');
					}

					if (res.errors.image) {
						$(imageGroup).addClass('has-error');
						$(imageGroup).append('<div class="alert alert-danger" role="alert">' + res.errors.image + '</div>');
					}

					if (res.errors.form) {
						$(formGroup).addClass('has-error');
						$(formGroup).append('<div class="alert alert-danger" role="alert">' + res.errors.form + '</div>');
					}
				}
			}),
			error: ((res) => {
				// eslint-disable-next-line no-console
				console.log(res);
			})
		});
		return false;
	});
}
