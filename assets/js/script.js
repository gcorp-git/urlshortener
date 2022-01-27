;(function(){
	'use strict';

	const $form = document.getElementById('form');
	const $success = document.getElementById('success');

	$form.addEventListener('submit', function(e) {
		e.preventDefault();

		const $url = e.target.querySelector('input[name="url"]');
		const $button = e.target.querySelector('button[type="submit"]');

		$button.disabled = true;
		$success.setAttribute('class', '');

		axios
			.post('/', { url: $url.value })
			.then(response => {
				$button.disabled = false;

				const $short = $success.querySelector('input[name="short"]');

				$short.value = location.origin + '/' + response.data;

				$success.setAttribute('class', 'visible');
				
				$short.focus();
				$short.select();
			})
			.catch(e => {
				$button.disabled = false;

				const message = 'Error: ' + e.data.error;

				console.error(message);

				alert(message);
			});
	});

})();