<div id="success" class="<?=!empty($url) ? 'visible' : ''?>">
	<h3>Here's your shortened link!</h3>
	<input name="short" type="text" value="<?=$url?>" <?=!empty($url) ? 'autofocus' : ''?>>
	<p class="text">But remember: under no circumstances should you ever share this link with anyone! Links weren't made for that.</p>
</div>