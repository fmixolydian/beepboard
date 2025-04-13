<?php

require '../config.php';
require '../functions.php';

if (array_key_exists("token", $_COOKIE)) {
	
	$data = BB_getUserdataByToken($_COOKIE['token']);
	
	if ($data == NULL) {
		header('Location: /login.php');
		die;
	}
} else {
	header('Location: /login.php');
	die;
}

require '../header.php';

?>

<style>
	article {
		background-color: #222;
		width: calc(800px - 10px * 2);
		padding: 10px;
		min-height: 200px;
	}
</style>

<article>
	<form name="sus" method="post" action="/api/submit.php" class="Submit" onsubmit="return validateSubmitSongForm()">
			<div class="SubmitName">
				<p>song name <span title="the song's title, DUH :/ (3-32 chars)">(?)</span></p>
				<input required="true" type="text" name="songname">
			</div>
			
			<div class="SubmitSummary">
				<p>song summary <span title="the song description showed in the song list (max. 120 chars)">(?)</span></p>
				<input type="text" name="summary">
			</div>
			
			<div class="SubmitDesc">
				<p>song description <span title="the song description showed in the song page (max. 4096 chars)">(?)</span></p>
				<textarea name="songdesc" rows="6" cols="80"></textarea>
			</div>
			
			<div class="SubmitURL">
				<p>song url</p>
				<textarea id="SongURL" name="url" rows="1" cols="80"></textarea>
			</div>
			
			<div class="SubmitMod">
				<p>song mod (auto detect)
					<img id="ModLogo" src="/assets/mods/unknown.png"></img>
					<span id="ModTitle" name="ModTitle">
						unknown
					</span>
				</p>
			</div>
			
			<small>(<strong>warning</strong>: tinyurl/isgd/etc links are NOT allowed!)</small>
			
			<input type="submit">
			
		</div>
	
	
	<script>
		document.getElementById("SongURL").addEventListener('input', submit_onUrlChange);
	</script>
	
	</form>
</article>
			
		</main>
	</body>
</html>