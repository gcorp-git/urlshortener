<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>URLShortener Inc.</title>
	<link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
	<main>
		<h1>Welcome to URLShortener Inc.</h1>
		<p class="text">We are the best yet another one link shortening service. Our server's uptime is 100%, except for those days when, you know, something just goes wrong and nothing works. But don't worry, you may always contact our support service - we will never read any of your messages but we sure wish you luck!</p>

		<?=web::render('form', ['url' => $url])?>
		<?=web::render('success', ['url' => $url])?>
	</main>

	<script src="https://cdn.jsdelivr.net/npm/axios@0.12.0/dist/axios.min.js"></script>
	<script src="/assets/js/script.js"></script>
</body>
</html>