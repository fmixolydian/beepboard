<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../header.php';
if (!array_key_exists('sort',  $_GET)) $_GET['sort']  = 'newest';
if (!array_key_exists('after', $_GET)) $_GET['after'] = 0;

$db = new SQLite3(DB_PATH);

$statement = "SELECT * from songs ";

$statement_footer = " LIMIT 10 OFFSET :offset";

switch ($_GET['sort']) {
	case 'random':
		$statement .= "ORDER BY RANDOM()";
		break;
		
	case 'featured':
		$statement .= "WHERE featured = 1";
		break;
	
	case 'newest':
		$statement .= "ORDER BY createdtime DESC";
		break;
	
	case 'popular':
		$statement .= "ORDER BY downloads DESC";
		break;
		
	default:
		http_response_code(400);
		die;
}

$statement .= $statement_footer;

echo "<code>$statement</code>";

$st = $db->prepare($statement);
$st->bindParam(':offset', $_GET['after'], SQLITE3_INTEGER);
$q = $st->execute();

?>

			<p>Sort by <a href="/songs.php?sort=featured">featured</a>,
			           <a href="/songs.php?sort=newest">newest</a>,
			           <a href="/songs.php?sort=popular">popular</a>,
			           <a href="/songs.php?sort=random">random</a>
			           </p>
			<div class="songs">
			
<?php

while ($result = $q->fetchArray(SQLITE3_ASSOC)) {
	
	$username = $db->querySingle('SELECT username FROM users WHERE userid = \'' .
		$result['authorid'] . '\'');
	
	echo '
<article>
	<div class="SongMeta">
		<div class="horizontal">
			<p class="SongName">' . $result['name'] . '</p>
			<p> · </p>
			<p class="SongDesc">' . $result['desc'] . '</p>
		</div>
		<div class="horizontal">
			<img class="SongInteract" src="/assets/like.png">
			<p class="SongCounter">' . $result['likes'] . '</p>
			<img class="SongInteract" src="/assets/downloads.png">
			<p class="SongCounter">' . $result['downloads'] . '</p>
		</div>
	</div>
	
	<div class="SongData">
		<div class="Platforms">
			<p> by <em class="SongAuthor">' . $username . '</em></p>
			<a target=_blank href="/api/downloadsong.php?id=' . $result['songid'] . '">
				<img class="SongPlatform" src="/assets/beepbox.png"/>
			</a>
		</div>
	</div>
</article>
';
}

?>
			</div>
			
			<footer>
				<p><a href=<?='songs.php?sort='.max($_GET['sort'].'&after='.($_GET['after']-10), 0) ?>>&lt;</a>
					Page <?= $_GET['after'] / 10 + 1 ?>
					<a href=<?='songs.php?sort='.$_GET['sort'].'&after='.($_GET['after']+10) ?>>&gt;</a></p>
			</footer>
		</main>
	</body>
</html>