<?php

require '../header.php';

BB_default($_GET['sort'],  'newest');
BB_default($_GET['after'], 0);


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
		$statement .= "ORDER BY likes DESC, downloads DESC";
		break;
		
	default:
		http_response_code(400);
		die;
}

$statement .= $statement_footer;

echo "<code>$statement</code>";

$q = BB_sqlStatement($statement, array(':offset' => $_GET['after']));

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
	
	$commentno = $db->querySingle('SELECT COUNT(*) FROM comments WHERE songid = \'' .
		$result['songid'] . '\'');
	
	echo '
<article class="song">
	<div class="SongMeta">
		<div class="vertical">
			<p class="SongName"><a href="/viewsong.php?id=' . $result['songid'] . '">' .
				htmlentities($result['name']) . '</a></p>
			<p class="SongDesc">' . htmlentities($result['summary']) . '</p>
		</div>
		<div class="horizontal">
			<img title="likes" class="SongInteract" src="/assets/likes.png">
			<p title="likes" class="SongCounter">' . htmlentities($result['likes']) . '</p>
			<img title="clicks" class="SongInteract" src="/assets/downloads.png">
			<p title="clicks" class="SongCounter">' . htmlentities($result['downloads']) . '</p>
			<img title="page views" class="SongInteract" src="/assets/views.png">
			<p title="page views" class="SongCounter">' . htmlentities($result['views']) . '</p>
			<img title="comments" class="SongInteract" src="/assets/comments.png">
			<p title="comments" class="SongCounter">' . htmlentities($commentno) . '</p>
			<p class="dim" title="' . date(DATE_RFC2822, $result['createdtime']) .
			'">' . BB_time_ago($result['createdtime']) . '</p>
		</div>
	</div>
	
	<div class="SongData">
		<div class="Platforms">
			<p> by <em class="SongAuthor">' . htmlentities($username) . '</em></p>
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