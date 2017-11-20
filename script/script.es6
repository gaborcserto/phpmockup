import {$} from './lib/jquery.es6';

$(document).ready(() => {
	pageLoad();
	formSubmit($('#upload-form'));
});

function pageLoad() {
	let formLink = '.form-link';
	let imageLink = '.images-link';
	let main = $('main');

	$('#form').on('click',() =>{
		$(formLink).addClass('active');
		$(imageLink).removeClass('active');
		main.html(main.load('form.tpl'));
	});
	$('#images').on('click',() =>{
		loadGallery();
		$(imageLink).addClass('active');
		$(formLink).removeClass('active');
		main.html(main.load('images.tpl'));
	});
}

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

function loadGallery (){
	let json = 'result.json.php';
	let galleryItems = [];

	$.getJSON( json, () => {}).done( (jsonData) => {
		$.each( jsonData.data, ( i, item ) => {
			galleryItems.push(
				'<div class="col-sm-3">' +
					'<div class="card">' +
						'<img class="card-img-top" src="' + item.url + '" alt="Card image cap">' +
						'<div class="card-body">' +
							'<h4 class="card-title">' + item.title + '</h4>' +
							'<p class="card-text">' + item.description + '</p>' +
						'</div>' +
					'</div>' +
				'</div>'
			);
		});
		// eslint-disable-next-line
		$('.row').html( galleryItems.join(""));
	});
}
