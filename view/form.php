<form id="form" action="/" method="post">
	<input type="url" name="url" placeholder="https://" <?=empty($url) ? 'autofocus' : ''?>>
	<button type="submit">get short link</button>
</form>